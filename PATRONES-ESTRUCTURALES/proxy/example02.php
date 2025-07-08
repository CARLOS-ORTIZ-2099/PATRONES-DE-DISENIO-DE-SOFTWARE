<?php

/* La interfaz Subject describe la interfaz de un objeto real.
   Lo cierto es que muchas aplicaciones reales podrían no tener esta interfaz 
   claramente definida.
   Si te encuentras en esa situación, lo mejor sería extender el Proxy desde 
   una de tus clases de aplicación existentes. Si esto te resulta complicado, 
   extraer una interfaz adecuada debería ser el primer paso.
*/
interface Downloader
{
  public function download(string $url): string;
}


/* El Sujeto Real realiza el trabajo real, aunque no de la manera más 
   eficiente.
   Cuando un cliente intenta descargar el mismo archivo por segunda vez, 
   nuestro descargador hace precisamente eso, en lugar de obtener el 
   resultado de la caché.
*/
class SimpleDownloader implements Downloader
{
  public function download(string $url): string
  {
    debuguear("Downloading a file from the Internet.");
    $result = file_get_contents($url);
    debuguear("Downloaded bytes: " . strlen($result));
    return $result;
  }
}




/* La clase Proxy es nuestro intento de hacer la descarga más eficiente. 
   Encapsula el objeto descargador real y lo delega en las primeras llamadas 
   de descarga. 
   El resultado se almacena en caché, lo que hace que las llamadas 
   posteriores devuelvan un archivo existente en lugar de volver a 
   descargarlo.
   Tenga en cuenta que el Proxy DEBE implementar la misma interfaz que el Sujeto Real.
*/
class CachingDownloader implements Downloader
{
  private $downloader;

  private $cache = [];

  public function __construct(SimpleDownloader $downloader)
  {
    $this->downloader = $downloader;
  }

  public function download(string $url): string
  {
    if (!isset($this->cache[$url])) {
      debuguear("CacheProxy MISS. ");
      $result = $this->downloader->download($url);
      $this->cache[$url] = $result;
    } else {
      debuguear("CacheProxy HIT. Retrieving result from cache.");
    }
    return $this->cache[$url];
  }
}



/* El código del cliente puede emitir varias solicitudes de descarga 
   similares. 
   En este caso, el proxy de caché ahorra tiempo y tráfico al 
   servir los resultados desde la caché.
   El cliente desconoce que trabaja con un proxy porque trabaja con 
   descargadores a través de la interfaz abstracta.
*/
function clientCode(Downloader $subject)
{
  $result = $subject->download("http://example.com/");

  // Las solicitudes de descarga duplicadas podrían almacenarse en caché para ganar velocidad.

  $result = $subject->download("http://example.com/");
}

debuguear("Executing client code with real subject:");
$realSubject = new SimpleDownloader();
clientCode($realSubject);


debuguear("Executing the same client code with a proxy:");
$proxy = new CachingDownloader($realSubject);
clientCode($proxy);

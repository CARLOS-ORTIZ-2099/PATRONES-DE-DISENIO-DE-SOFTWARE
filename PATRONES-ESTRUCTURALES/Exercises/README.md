# PROYECTOS PRACTICOS APLICANDO PATRONES DE DISEÑO

## 🧩 Implementar Adapter + Decorator en la Integración de una API Externa de Pagos

### 📌 Contexto del ejercicio:

Tu ERP necesita integrarse con un proveedor externo de pagos (ej. PasarelaPay). Esta API externa tiene una interfaz distinta a la que tu sistema espera. Además, deseas añadir comportamientos adicionales como registro de logs , validación previa o notificaciones, sin modificar el código ni de la API externa ni de tu lógica central.

Este ejercicio mezcla dos patrones:

- Adapter para traducir interfaces incompatibles.
- Decorator para añadir comportamientos dinámicos antes o después del procesamiento de pagos.

### 📌 ¿Qué problema resuelve el Adapter?

- La API externa expone métodos como realizarPagoEntero(int $monto) y estadoPago(string $codigo), mientras que tu sistema trabaja con procesarPago(float $monto) y verificarEstado(string $id).

- Necesitás un Adaptador para que tu sistema use la API como si fuese nativa, sin modificar ni el cliente ni la API.

### 📌 ¿Qué aporta el Decorator?

- Loguear operaciones: Guardar cada intento de pago con timestamp.

- Enviar notificaciones: Alertar por email o push cuando una transacción exceda cierto monto.

- Validar el monto antes de ejecutar: Por ejemplo, bloquear si es negativo o excesivo.

No querés modificar la clase de pagos ni el adaptador. Por eso usás Decoradores que envuelven el objeto adaptado y extienden su comportamiento.

### 🛠️ Lo que se espera del ejercicio:

- ✔ Diseñar una interfaz común de pagos (ProcesadorPagoInterface)
- ✔ Adaptar la API externa a esa interfaz usando un Adapter
- ✔ Implementar Decoradores que puedan registrar, validar o notificar pagos
- ✔ Permitir encadenar decoradores fácilmente
- ✔ Simular al menos dos escenarios: uno básico y otro con decoradores apilados

---

## 📁 Modelar una Estructura de Carpetas con Composite y Simplificar su Acceso con Facade

### 📌 Contexto del ejercicio:

Estás desarrollando un sistema de gestión de archivos para un ERP, similar a un gestor de documentos internos. Este sistema puede contener:

- Archivos individuales (como PDFs, imágenes, documentos)

- Carpetas que contienen archivos o incluso más carpetas

- Operaciones comunes como: calcular tamaño total, listar contenidos, mover/copiar estructuras

Naturalmente, esto forma una estructura jerárquica en árbol, ideal para aplicar el patrón Composite.

Sin embargo, el cliente (el sistema ERP) no debería preocuparse por cómo se recorre esta jerarquía o cómo se calcula la información. Para eso usás una fachada que simplifica el trabajo con este árbol.

### 📌 ¿Qué problema resuelve Composite?

Permite tratar de forma uniforme a archivos y carpetas. Un archivo individual y una carpeta que contiene múltiples archivos y subcarpetas comparten la misma interfaz.

Por ejemplo:

```php
$elemento->obtenerTamaño();
// funciona sin importar si es un archivo o carpeta

```

### 📌 ¿Qué problema resuelve Facade?

Tu cliente (el sistema ERP) necesita realizar operaciones complejas sobre esta estructura:

- Calcular espacio usado por una carpeta raíz

- Buscar un archivo por nombre en toda la jerarquía

- Exportar una vista del árbol

En vez de forzar al cliente a navegar por el árbol manualmente, le das una clase FileManagerFacade que expone métodos simples:

```php
$gestor = new FileManagerFacade($carpetaRaiz);
$gestor->mostrarEstructura();
$gestor->obtenerTamañoTotal();
$gestor->buscar("manual.pdf");

```

### 🛠️ Lo que se espera del ejercicio:

- ✔ Diseñar una interfaz ElementoArchivoInterface con métodos como obtenerTamaño() y mostrar()

- ✔ Implementar Archivo como clase hoja

- ✔ Implementar Carpeta como clase compuesta que contiene otros elementos (archivos o carpetas)

- ✔ Crear un FileManagerFacade que provea métodos simplificados para operaciones frecuentes (tamaño total, búsqueda, impresión jerárquica, etc.)

- ✔ Simular al menos dos estructuras: una carpeta raíz con varios archivos simples, y otra con subcarpetas y múltiples niveles

### 📦 Bonus opcional

- Agregá un decorador para contar accesos o simular permisos de lectura.

- Implementá una exportación en JSON usando el Facade.

- Usá recursión internamente pero mantené la API del Facade limpia y amigable.

## Gestión de Documentos Sensibles con Proxy

### 📌 Contexto del ejercicio:

Estás desarrollando un sistema interno de gestión documental para tu ERP. Algunos documentos son sensibles (por ejemplo, contratos confidenciales, reportes de gastos o archivos contables) y deben ser accesibles solo por ciertos perfiles de usuario (ej. “admin”).

Además, cargar estos archivos es costoso (pesados o externos), así que quieres que el archivo se cargue solo cuando se lo necesite verdaderamente.

### 🎯 ¿Qué patrones usar?

Proxy de acceso: controla permisos de lectura según el rol del usuario.

Proxy de carga diferida: no carga el contenido hasta que se solicite explícitamente (lazy load).

### El ProxyDocumento debería controlar internamente:

- Si el usuario puede acceder (por rol)

- Si el documento ya fue cargado o no (simular carga costosa)

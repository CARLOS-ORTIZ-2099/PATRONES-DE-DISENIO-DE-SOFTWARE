# PROYECTOS PRACTICOS APLICANDO PATRONES DE DISEÑO

## Implementar Singleton en la gestión de configuración global

📌 Contexto del ejercicio de Singleton: El objetivo del ejercicio es gestionar una configuración global en una aplicación, asegurando que solo exista una instancia que controle todos los parámetros. Esto evita que diferentes partes del sistema creen su propia configuración y generen inconsistencias.

📌 ¿Qué tipo de configuración se necesita manejar?

- Parámetros globales: Información que toda la aplicación necesita, como el nombre del sistema, el idioma por defecto, etc.

- Conexión a base de datos: Servidor, usuario, contraseña, puerto (para que toda la aplicación use el mismo acceso).

- Credenciales de API: Claves para consumir servicios externos sin duplicaciones.

- Modo de ejecución: Si la aplicación está en modo desarrollo o producción (esto cambia cómo se comporta).

- Archivos de configuración: Rutas a archivos esenciales, como logs o configuraciones externas.

📌 Lo que se espera del ejercicio: ✔ Implementar una clase Singleton que centralice y gestione estas configuraciones. ✔ Garantizar que todos los módulos de la aplicación accedan a la misma instancia del objeto de configuración. ✔ Permitir establecer y obtener valores de configuración dinámicamente, evitando múltiples instancias.

Conclusión: ✔ El Singleton en este ejercicio debe encargarse de almacenar parámetros esenciales para toda la aplicación. ✔ Debe asegurar una única instancia accesible en cualquier parte del sistema, evitando inconsistencias.

## Implementar Builder en la generación de documentos

📌 Contexto del ejercicio: El propósito de este ejercicio es crear documentos de manera flexible y paso a paso, evitando constructores sobrecargados con demasiados parámetros. El patrón Builder te permitirá estructurar la generación del documento en distintos componentes sin afectar su lógica interna.

📌 ¿Por qué se usa Builder en documentos?

- Permite agregar secciones opcionales: No todos los documentos tienen título, imágenes o pie de página.
- Facilita la creación de múltiples representaciones: Puedes generar documentos en HTML, PDF o texto plano sin cambiar la estructura del código base.
- Evita constructores largos y difíciles de mantener: En lugar de recibir todos los parámetros en un solo constructor, cada parte del documento se define progresivamente.

📌 ¿Qué debe incluir el Builder en este ejercicio?

- Título → Cada documento puede tener un título opcional.
- Contenido → El cuerpo del documento, que puede ser texto, imágenes o una combinación.
- Pie de página → Opcional, con firma o información adicional.
- Formato de salida → Permitir exportar el documento como texto, HTML o PDF.

📌 Lo que se espera del ejercicio: ✔ Separar la construcción del documento de su representación final. ✔ Permitir crear distintos documentos sin modificar la lógica base. ✔ Asegurar que cada paso de construcción sea claro y flexible.

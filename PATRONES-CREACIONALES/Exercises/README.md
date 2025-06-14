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

## Implementar Factory Method en la Gestión de Vehículos de Alquiler

Objetivo: Implementar una fábrica de vehículos donde el sistema pueda instanciar distintos tipos de vehículos sin depender directamente de sus clases concretas.

📌 Lo que debes hacer:

- Definir una interfaz VehiculoInterface con métodos getTipo() y getDescripcion().
- Crear una fábrica abstracta FactoryVehiculo, con el método createVehiculo().
- Implementar fábricas concretas FactoryAuto, FactoryMoto, FactoryCamion, cada una generando su propio tipo de vehículo.
- El cliente debe poder solicitar vehículos sin conocer su implementación interna.
- Verificar que se puedan agregar más tipos de vehículos sin modificar el código base.

📌 Desafío adicional: ✅

- ¿Qué pasa si en el futuro necesitas vehículos eléctricos?
- ¿Cómo adaptar Factory Method sin modificar la lógica de creación?

## Implementar Prototype en un Sistema de Clonación de Jugadores de Fútbol

Objetivo: Desarrollar un sistema que permita clonar jugadores ya configurados, evitando repetir su configuración inicial.

📌 Lo que debes hacer:

- Definir una clase Jugador, con atributos como nombre, edad, equipo y posición.
- Implementar \_\_clone() para que los jugadores clonados no compartan referencias con el original.
- Crear una lista de jugadores y clonar varios para cambiar su equipo y posición.
- Comprobar que las instancias clonadas son independientes en memoria del original.

📌 Desafío adicional: ✅

- ¿Cómo manejar jugadores con historial de equipos sin compartir referencias entre clones?

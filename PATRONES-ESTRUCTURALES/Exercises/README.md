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

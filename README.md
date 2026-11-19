# 🧩⚙️ TaskBoard — Semana 18

![Laravel](https://img.shields.io/badge/Laravel-12-red?logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.2-blue?logo=php)
![MySQL](https://img.shields.io/badge/MySQL-MariaDB-orange?logo=mysql)
![Sanctum](https://img.shields.io/badge/Laravel-Sanctum-red)
![SOLID](https://img.shields.io/badge/SOLID-Arquitectura-6C63FF)
![UPED](https://img.shields.io/badge/UPED-Integración_de_Sistemas-0B2E59)

Proyecto desarrollado para la asignatura **Integración de Sistemas** de la **Universidad Pedagógica de El Salvador (UPED)**.

Durante la **Semana 18** se trabajó la arquitectura y las buenas prácticas del proyecto TaskBoard, refactorizando la lógica de transacciones hacia una capa de servicios, aplicando principios SOLID, implementando reglas de comisión extensibles y reemplazando estados escritos manualmente por un Enum.

**Estudiante:** Josthyn Stanley Cruz Vásquez  
**Carrera:** Ingeniería en Sistemas y Computación  
**Universidad:** Universidad Pedagógica de El Salvador  
**Ciclo:** 02-2026  

---

# 🎯 Objetivo general

Mejorar la organización y mantenibilidad de la API de TaskBoard mediante una refactorización controlada, separando responsabilidades entre controladores, Form Requests, servicios, recursos y reglas de negocio, sin perder el comportamiento esperado de la aplicación.

---

# 🧱 Arquitectura y separación de responsabilidades

Durante la Semana 18 se revisó el método `store` de las transacciones para identificar las responsabilidades que se encontraban concentradas en el controlador.

Entre ellas se encontraban:

```text
Validación de entrada
Verificación del comercio del usuario
Cálculo de comisión
Registro de la transacción
Registro de eventos
Uso de transacciones de base de datos
Registro en logs
Construcción de la respuesta
```

La solución consistió en mover la lógica de negocio a una capa de servicios y dejar al controlador principalmente como coordinador de la solicitud.

---

# 🛠️ TransaccionService

Se creó el servicio:

```text
app/Services/TransaccionService.php
```

Este servicio concentra la lógica relacionada con:

- registro de transacciones;
- cálculo de comisiones;
- cambio de estado;
- validación de reglas de negocio;
- creación de eventos de transacción;
- uso de `DB::transaction`;
- registro de información mediante `Log`.

El servicio no recibe objetos `Request` ni devuelve respuestas HTTP directamente.

---

# 📥 Refactorización de store

El método `store` del controlador API fue simplificado para delegar la lógica al servicio.

Flujo actual:

```text
Request
   ↓
GuardarTransaccionApiRequest
   ↓
TransaccionApiController
   ↓
TransaccionService
   ↓
Modelo / Base de datos
   ↓
TransaccionResource
```

Antes de realizar la refactorización se ejecutaron pruebas automatizadas para establecer una red de seguridad.

Después de mover la lógica al servicio se volvieron a ejecutar las pruebas y se comprobó que el comportamiento esperado se mantuviera.

---

# 🔄 Refactorización de update

También se implementó y refactorizó el proceso de actualización del estado de una transacción.

Se creó:

```text
app/Http/Requests/UpdateTransaccionRequest.php
```

La validación de entrada se realiza en el Form Request, mientras que la regla de negocio permanece en `TransaccionService`.

Regla principal:

```text
Solo una transacción en estado Iniciada puede cambiar de estado.
```

Además:

- una transacción ajena devuelve `404`;
- una transacción que ya no está iniciada devuelve `422`;
- el cambio de estado registra un evento;
- la actualización se ejecuta dentro de una transacción de base de datos.

---

# 🧩 Principio Abierto/Cerrado · OCP

Para evitar condicionales crecientes en el cálculo de comisiones se implementó una estructura basada en reglas.

Interfaz principal:

```text
app/Services/Comisiones/ReglaComision.php
```

Cada tipo de comisión implementa esta interfaz.

## Reglas implementadas

| Método de pago | Clase | Comisión |
|---|---|---:|
| Tarjeta | `ComisionTarjeta` | 3.5 % |
| Transferencia | `ComisionTransferencia` | $1.50 |
| Billetera | `ComisionBilletera` | 2 % |

---

# 🧮 Calculadora de comisiones

Se creó:

```text
app/Services/CalculadoraComision.php
```

La calculadora recibe un conjunto de reglas y utiliza la primera que corresponda al método de pago solicitado.

Esto permite agregar nuevas reglas sin modificar directamente la lógica interna de la calculadora.

---

# 🔌 Contenedor de servicios de Laravel

Durante el ejercicio se produjo intencionalmente el error:

```text
BindingResolutionException
```

Laravel podía resolver clases automáticamente, pero no podía determinar qué objetos debían formar parte del arreglo de reglas de comisión.

La solución fue registrar manualmente `CalculadoraComision` en:

```text
app/Providers/AppServiceProvider.php
```

Reglas registradas:

```text
ComisionTarjeta
ComisionTransferencia
ComisionBilletera
```

---

# 💳 Métodos de pago disponibles

Actualmente la API acepta:

```text
tarjeta
transferencia
billetera
```

El campo utilizado por el proyecto es:

```text
metodo_pago
```

---

# 🏷️ Enum de estados

Como mejora adicional se creó:

```text
app/Enums/EstadoTransaccion.php
```

Estados disponibles:

```text
Iniciada
Completada
Fallida
```

El modelo `Transaccion` utiliza un cast para convertir automáticamente el campo `estado` en una instancia de `EstadoTransaccion`.

---

# 🔬 Experimento con Tinker

Se ejecutó:

```bash
php artisan tinker
```

Luego:

```php
App\Models\Transaccion::first()->estado
```

Resultado:

```text
App\Enums\EstadoTransaccion
name: "Iniciada"
value: "Iniciada"
```

Esto comprobó que el atributo ya no devuelve un string simple, sino una instancia del Enum.

Por esta razón, en `TransaccionResource` se utiliza:

```php
$this->estado->value
```

para devolver el estado como texto dentro del JSON.

---

# ✅ Pruebas automatizadas

Se ejecutaron pruebas específicas para el registro de transacciones:

```bash
php artisan test --filter=RegistrarTransaccion
```

Resultado:

```text
6 pruebas aprobadas
14 assertions
```

También se ejecutaron las pruebas de actualización:

```bash
php artisan test --filter=ActualizarTransaccion
```

Resultado:

```text
3 pruebas aprobadas
4 assertions
```

Finalmente se ejecutó toda la suite:

```bash
php artisan test
```

Resultado final:

```text
15 pruebas aprobadas
31 assertions
```

---

# 🧪 Casos verificados

Entre los casos comprobados se encuentran:

- registro de transacción con tarjeta;
- comisión de tarjeta del 3.5 %;
- comisión fija de transferencia;
- comisión de billetera del 2 %;
- comercio ajeno;
- datos inválidos;
- solicitud sin token;
- actualización correcta del estado;
- intento de actualizar una transacción no iniciada;
- intento de modificar una transacción ajena;
- registro del evento de creación;
- validaciones del monto;
- protección del estado enviado por el cliente.

---

# 🤖 Uso de IA y verificación

La IA fue utilizada como apoyo durante el proceso de refactorización.

Se verificaron especialmente los siguientes puntos:

| Verificación | Resultado |
|---|---|
| No cambiar nombres de rutas sin necesidad | ✅ |
| Mantener las reglas de negocio | ✅ |
| No utilizar métodos estáticos en el servicio | ✅ |
| El servicio no recibe `Request` | ✅ |
| No devolver `response()` desde el servicio | ✅ |
| Ejecutar pruebas después de los cambios | ✅ |

La comprobación final se realizó ejecutando la suite automatizada del proyecto.

---

# 📝 Registro de cambios

Los cambios de la Semana 18 fueron documentados en:

```text
CAMBIOS.md
```

Se registraron en la sección **Cambiado**:

- refactorización del registro de transacciones hacia `TransaccionService`;
- refactorización de la actualización de estado;
- implementación de reglas de comisión aplicando OCP;
- soporte para tarjeta, transferencia y billetera;
- incorporación de `EstadoTransaccion`.

---

# 🌿 Rama de trabajo

La versión final correspondiente a la Semana 18 se encuentra en la rama:

```text
refactor/servicios
```

Esta rama contiene la refactorización, las reglas de comisión, el Enum y las pruebas automatizadas desarrolladas durante la práctica.

---

# 🎤 Responsables de defensa · Laboratorio III

| Bloque | Tema | Responsable |
|---|---|---|
| A | API REST y JSON | Josthyn Stanley Cruz Vásquez |
| B | Autenticación con Sanctum | Pendiente de asignación |
| C | Pruebas y manejo de errores | Pendiente de asignación |
| D | IA y mantenimiento | Pendiente de asignación |

## Contenido de cada bloque

### Bloque A · API REST y JSON

- Uso de `apiResource` y API Resources.
- Manejo de respuestas JSON.
- Uso de Form Requests.
- Validaciones de entrada.
- Separación entre controlador y servicio.
- Uso de `TransaccionResource`.

### Bloque B · Autenticación con Sanctum

- Autenticación mediante Laravel Sanctum.
- Uso de tokens.
- Rutas protegidas mediante `auth:sanctum`.
- Restricción de acceso a recursos pertenecientes a otros usuarios.

### Bloque C · Pruebas y manejo de errores

- Pruebas Feature.
- Respuestas `404` y `422`.
- Pruebas antes y después de refactorizar.
- Uso de `DB::transaction`.
- Verificación final con 15 pruebas aprobadas.

### Bloque D · IA y mantenimiento

- Uso controlado de IA.
- Revisión del código generado.
- Aplicación de principios SOLID.
- Registro de cambios en `CAMBIOS.md`.
- Refactorización hacia una capa de servicios.

---

# ✅ Resultado final

```text
Pruebas de store                      → Completadas
TransaccionService                    → Implementado
Refactorización de store              → Completada
Refactorización de update             → Completada
Reglas de comisión OCP                → Implementadas
Comisión de tarjeta                   → 3.5 %
Comisión de transferencia             → $1.50
Comisión de billetera                 → 2 %
Binding manual en AppServiceProvider  → Completado
Enum EstadoTransaccion                → Implementado
Experimento con Tinker                → Completado
CAMBIOS.md                            → Actualizado
Suite final                           → 15 pruebas / 31 assertions
```

---

# 🛠️ Tecnologías y herramientas utilizadas

- Laravel 12
- PHP 8.2
- MySQL / MariaDB
- Laravel Sanctum
- Eloquent ORM
- REST
- JSON
- PHPUnit
- Form Requests
- API Resources
- Service Layer
- Principios SOLID
- Git
- GitHub
- XAMPP

---

# ▶️ Ejecución del proyecto

```bash
git clone https://github.com/StanleyJv/taskboard-semana18.git
cd taskboard-semana18
git checkout refactor/servicios
composer install
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

API:

```text
http://127.0.0.1:8000/api
```

Para ejecutar las pruebas:

```bash
php artisan test
```

---

# 🔗 Repositorio

https://github.com/StanleyJv/taskboard-semana18

**Rama final:** `refactor/servicios`

---

**Integración de Sistemas · Ciclo 02-2026 · UPED**

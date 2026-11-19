# 🧩⚙️ TaskBoard — Semana 17

![Laravel](https://img.shields.io/badge/Laravel-12-red?logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.2-blue?logo=php)
![MySQL](https://img.shields.io/badge/MySQL-MariaDB-orange?logo=mysql)
![Sanctum](https://img.shields.io/badge/Laravel-Sanctum-red)
![Spring Boot](https://img.shields.io/badge/Spring_Boot-Framework_Comparado-6DB33F?logo=springboot&logoColor=white)
![UPED](https://img.shields.io/badge/UPED-Integración_de_Sistemas-0B2E59)

Proyecto desarrollado para la asignatura **Integración de Sistemas** de la **Universidad Pedagógica de El Salvador (UPED)**.

Durante la **Semana 17** se trabajó la comparación de frameworks de back-end, el análisis de equivalencias entre tecnologías, la elaboración de una matriz de decisión ponderada y la justificación técnica de la elección de Laravel para el proyecto TaskBoard.

**Estudiante:** Josthyn Stanley Cruz Vásquez  
**Carrera:** Ingeniería en Sistemas y Computación  
**Universidad:** Universidad Pedagógica de El Salvador  
**Ciclo:** 02-2026  

---

# 🎯 Objetivo general

Comparar Laravel con otros frameworks de back-end, identificar equivalencias entre sus principales componentes y justificar técnicamente la elección de Laravel para TaskBoard mediante criterios relacionados con experiencia del equipo, tiempo de desarrollo, ecosistema, costo de hosting y rendimiento.

---

# 🔄 Comparación de frameworks

Durante la semana se analizaron implementaciones equivalentes de un endpoint de transacciones en:

- Laravel
- Spring Boot
- Django REST Framework
- Express

Se compararon aspectos como:

```text
Rutas y verbos HTTP
Autenticación
Validación de entrada
Acceso a datos
Protección contra IDOR
Respuestas JSON
Códigos HTTP
```

También se observó cómo cada framework distribuye la lógica entre controladores, servicios, validadores y herramientas propias del ecosistema.

---

# 📚 Diccionario de equivalencias

Para el análisis principal se eligió **Spring Boot** como framework de comparación con Laravel.

| Concepto | Laravel | Spring Boot |
|---|---|---|
| Crear un proyecto | `composer create-project` | Spring Initializr / `spring init` |
| Rutas | `routes/api.php` | `@RequestMapping`, `@GetMapping`, `@PostMapping` |
| Controlador | `Controller` | `@RestController` |
| ORM | Eloquent | Spring Data JPA + Hibernate |
| Migraciones | `php artisan migrate` | Flyway o Liquibase |
| Validación | Form Request | Bean Validation con `@Valid` |
| Salida JSON | API Resource | DTO + Jackson |
| Autenticación de API | Sanctum | Spring Security |
| Pruebas | PHPUnit + `postJson` | JUnit + Spring Boot Test / MockMvc |
| Consola | Artisan | Maven / Gradle + Spring Boot CLI |

---

# 📊 Matriz de decisión de TaskBoard

## Contexto del proyecto

TaskBoard es una pasarela de pagos desarrollada por un equipo pequeño con experiencia en PHP y Laravel. El proyecto tiene un plazo corto, presupuesto limitado y requiere una API segura y fácil de mantener.

| Criterio | Peso | Laravel | Spring Boot | Django |
|---|---:|---:|---:|---:|
| Experiencia del equipo | 30 % | 5 | 2 | 2 |
| Tiempo de desarrollo | 25 % | 5 | 2 | 4 |
| Ecosistema para API y autenticación | 20 % | 5 | 5 | 4 |
| Costo de hosting | 15 % | 5 | 3 | 4 |
| Rendimiento | 10 % | 3 | 5 | 3 |
| **Total ponderado** | **100 %** | **4.80** | **3.05** | **3.30** |

## Cálculo de Laravel

```text
Laravel = (0.30 × 5) + (0.25 × 5) + (0.20 × 5) + (0.15 × 5) + (0.10 × 3)
Laravel = 1.50 + 1.25 + 1.00 + 0.75 + 0.30
Laravel = 4.80
```

---

# 🧪 Sensibilidad de la decisión

Se modificó el peso de dos criterios para comprobar si la elección de Laravel dependía demasiado de una sola condición.

Cambio realizado:

```text
Experiencia del equipo: 30 % → 15 %
Rendimiento:             10 % → 25 %
```

Nuevos resultados:

```text
Laravel      → 4.50
Spring Boot  → 3.50
Django       → 3.45
```

Laravel continúa con el puntaje más alto, por lo que la decisión se considera relativamente robusta incluso aumentando la importancia del rendimiento.

---

# 🌐 Análisis de escenarios

Se evaluaron cuatro escenarios con necesidades distintas:

| Caso | Framework elegido | Motivo principal |
|---|---|---|
| Sistema de citas para clínicas | ASP.NET Core | Equipo con experiencia en C# e infraestructura Microsoft |
| Portal educativo con panel administrativo | Django | Experiencia en Python y panel de administración integrado |
| Recepción de datos de miles de sensores | Express / NestJS | Muchas conexiones concurrentes y bajo consumo de recursos |
| Pago de impuestos municipales | Laravel | Experiencia en PHP, bajo costo y plazo corto |

---

# 📄 Anexo A · Comparación Laravel vs. Spring Boot

El cuadro comparativo y la conclusión tecnológica de la Semana 17 se encuentran en:

```text
docs/anexo-a-comparativo.md
```

Aspectos comparados:

- lenguaje y ecosistema
- curva de aprendizaje
- ORM y acceso a datos
- autenticación de API
- herramientas de pruebas
- despliegue y hosting
- escenario donde conviene más cada framework

## Conclusión

Para TaskBoard, nuestra pasarela de pagos, elegimos Laravel porque el proyecto se ha desarrollado durante el ciclo con PHP y Laravel, lo que reduce la curva de aprendizaje y permite trabajar con mayor rapidez. También contamos con herramientas como Eloquent para el acceso a datos y Sanctum para la autenticación de nuestra API, además de poder utilizar opciones de hosting de bajo costo.

Spring Boot sería una mejor alternativa para un sistema empresarial de mayor escala, especialmente si el equipo tuviera experiencia en Java y existieran mayores requisitos de rendimiento o integración con su ecosistema. Sin embargo, para TaskBoard, por el plazo disponible, la experiencia del equipo y los recursos del proyecto, Laravel es la opción que mejor se adapta a nuestras necesidades.

---

# 🤖 Uso de IA y verificación

Se revisaron afirmaciones generadas con apoyo de IA y se contrastaron con documentación oficial.

| Afirmación | Verificación |
|---|---|
| Spring Boot puede trabajar con Spring Data JPA para acceso a datos mediante repositorios | ✅ Correcta |
| Spring Boot permite validación mediante Bean Validation y anotaciones de `jakarta.validation` | ✅ Correcta |
| Spring Boot dispone de herramientas como `@SpringBootTest` y MockMvc para realizar pruebas | ✅ Correcta |

Fuentes utilizadas:

- Spring Data JPA: https://docs.spring.io/spring-data/jpa/reference/index.html
- Spring Framework Validation: https://docs.spring.io/spring-framework/reference/web/webmvc/mvc-controller/ann-validation.html
- Spring Boot Testing: https://docs.spring.io/spring-boot/reference/testing/spring-boot-applications.html

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
- Códigos HTTP `200`, `201`, `204`, `404` y `422`.
- Validación mediante Form Requests.
- Uso de reglas como `Rule::in` y `exists`.

### Bloque B · Autenticación con Sanctum

- Registro e inicio de sesión.
- Generación y uso de tokens.
- Cierre de sesión y revocación de tokens.
- Rutas protegidas mediante `auth:sanctum`.
- Protección para impedir el acceso a recursos de otros usuarios.

### Bloque C · Pruebas y manejo de errores

- Colección y bitácora con 15 o más casos de prueba.
- Verificación de respuestas y códigos HTTP.
- Errores corregidos y verificados.
- Respuestas de error en JSON.
- Uso de `DB::transaction`.

### Bloque D · IA y mantenimiento

- Ficha de uso crítico de IA.
- Verificación de información generada con IA.
- Mantenimiento correctivo y evolutivo.
- Registro de cambios en `CAMBIOS.md`.

---

# 🗣️ Hoja de preparación para el debate

La dinámica **“Defiende tu framework”** y el sorteo correspondiente fueron cancelados por el docente durante la clase del **12 de noviembre de 2026** por falta de tiempo.

Por esta razón, esta sección no aplica para la entrega de la Semana 17.

---

# ✅ Resultado final

```text
Comparación de frameworks              → Completada
Diccionario de equivalencias           → Completado
Matriz ponderada                       → Laravel 4.80
Experimento de sensibilidad            → Completado
Escenarios E, F, G y H                 → Justificados
Anexo A                                → Preparado
Conclusión tecnológica                 → Completada
Verificación de IA                     → Completada
Responsables de defensa en README      → Registrados
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
- Bruno
- Spring Boot (comparación)
- Spring Data JPA
- Spring Security
- Git
- GitHub
- XAMPP

---

# ▶️ Ejecución del proyecto

```bash
git clone https://github.com/StanleyJv/taskboard-semana17.git
cd taskboard-semana17
composer install
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

API:

```text
http://127.0.0.1:8000/api
```

---

# 🔗 Repositorio

https://github.com/StanleyJv/taskboard-semana17

---

**Integración de Sistemas · Ciclo 02-2026 · UPED**

# Anexo A · Cuadro comparativo de frameworks

| Criterio | Laravel | Spring Boot |
|---|---|---|
| Lenguaje y ecosistema | PHP. Ecosistema amplio para aplicaciones web y APIs. | Java. Ecosistema fuerte para sistemas empresariales y servicios de gran escala. |
| Curva de aprendizaje | Más suave para nuestro equipo porque ya trabajamos con PHP y Laravel. | Más pronunciada por el tipado de Java y la estructura del framework. |
| ORM y acceso a datos | Eloquent ORM. | Spring Data JPA + Hibernate. |
| Autenticación de API | Laravel Sanctum. | Spring Security. |
| Herramientas de pruebas | PHPUnit y pruebas HTTP con postJson. | JUnit, Spring Boot Test y MockMvc. |
| Despliegue y hosting | Hosting económico y abundante para PHP. | Generalmente requiere JVM y más recursos de servidor. |
| Escenario donde conviene más | Proyectos pequeños o medianos donde importa desarrollar rápido y con bajo costo. | Sistemas empresariales grandes, críticos o con equipos que ya dominan Java. |

## Conclusión

Para TaskBoard elegimos Laravel porque nuestro equipo ya tiene experiencia con PHP, permite desarrollar más rápido y tiene un costo de hosting accesible. Además, cuenta con herramientas como Eloquent y Sanctum que cubren las necesidades de nuestra API. Spring Boot sería preferible en un sistema empresarial grande, con un equipo que domine Java y mayores requisitos de rendimiento y escalabilidad, pero en nuestro contexto Laravel se adapta mejor al plazo y recursos disponibles.
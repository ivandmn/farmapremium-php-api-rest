# Prueba Técnica para Desarrollador PHP Backend

## 📋 Descripción del Proyecto

Deberás desarrollar una **API REST para un sistema de gestión de tareas** simplificado. El sistema permitirá crear, listar, actualizar y eliminar tareas, así como asignarlas a usuarios.

## ⏱️ Tiempo Estimado

**4-5 horas máximo**

## 🎯 Objetivos de Evaluación

1. **Docker**: Configuración del entorno de desarrollo
2. **Principios SOLID**: Aplicación correcta de los principios
3. **Arquitectura Hexagonal**: Separación de capas y dependencias
4. **Unit Testing**: Cobertura de tests unitarios (mínimo 70%)
5. **Tests Funcionales**: Tests de integración de los endpoints

## 📝 Requisitos Funcionales

### Entidades

#### Task (Tarea)

- `id`: UUID
- `title`: string (máximo 255 caracteres)
- `description`: text
- `status`: enum (pending, in_progress, completed)
- `priority`: enum (low, medium, high)
- `assignedTo`: UUID (usuario asignado, opcional)
- `dueDate`: datetime (fecha límite, opcional)
- `createdAt`: datetime
- `updatedAt`: datetime

#### User (Usuario) - Simplificado

- `id`: UUID
- `name`: string
- `email`: string (único)
- `createdAt`: datetime

### Endpoints Requeridos

```
POST   /api/tasks           - Crear nueva tarea
GET    /api/tasks           - Listar tareas (con filtros opcionales)
GET    /api/tasks/{id}      - Obtener detalle de una tarea
PUT    /api/tasks/{id}      - Actualizar tarea
DELETE /api/tasks/{id}      - Eliminar tarea
PATCH  /api/tasks/{id}/assign - Asignar tarea a usuario

POST   /api/users           - Crear usuario (simplificado)
GET    /api/users           - Listar usuarios
```

### Reglas de Negocio

1. Una tarea no puede ser asignada a un usuario que no existe
2. No se pueden crear tareas con fecha límite en el pasado
3. El cambio de estado debe seguir este flujo: `pending → in_progress → completed`
4. Una tarea completada no puede cambiar a otro estado
5. Solo se pueden eliminar tareas en estado `pending`

## 🏗️ Requisitos Técnicos

### 1. Dockerización

```dockerfile
# Estructura mínima requerida:
/project
├── docker-compose.yml
├── Dockerfile
├── .env.example
└── ...
```

- PHP 8.2 o superior
- MySQL/PostgreSQL
- Servidor web (nginx/apache)
- Configuración para desarrollo local

### 2. Arquitectura Hexagonal

```
/src
├── Application/
│   ├── UseCase/
│   │   ├── CreateTask/
│   │   │   ├── CreateTaskUseCase.php
│   │   │   ├── CreateTaskRequest.php
│   │   │   └── CreateTaskResponse.php
│   │   └── ...
│   └── Service/
├── Domain/
│   ├── Model/
│   │   ├── Task.php
│   │   └── User.php
│   ├── Repository/
│   │   ├── TaskRepositoryInterface.php
│   │   └── UserRepositoryInterface.php
│   ├── ValueObject/
│   └── Exception/
└── Infrastructure/
    ├── Controller/
    │   ├── TaskController.php
    │   └── UserController.php
    ├── Repository/
    │   ├── MySqlTaskRepository.php
    │   └── MySqlUserRepository.php
    └── Persistence/
```

### 3. Principios SOLID

Deberás demostrar la aplicación de:

- **S**ingle Responsibility Principle
- **O**pen/Closed Principle
- **L**iskov Substitution Principle
- **I**nterface Segregation Principle
- **D**ependency Inversion Principle

### 4. Testing

#### Unit Tests (PHPUnit)

```php
// Ejemplo de estructura
/tests
├── Unit/
│   ├── Domain/
│   │   └── Model/
│   │       └── TaskTest.php
│   └── Application/
│       └── UseCase/
│           └── CreateTaskUseCaseTest.php
└── Functional/
    └── Controller/
        └── TaskControllerTest.php
```

**Cobertura mínima requerida**: 70% en Domain y Application

#### Tests Funcionales

- Tests de integración de todos los endpoints
- Validación de respuestas HTTP
- Validación de reglas de negocio

### 5. Tecnologías Permitidas

**Framework**:

- Symfony 6+ (recomendado)
- Laravel 10+
- Slim Framework 4

**ORM/Database**:

- Doctrine ORM
- Eloquent
- PDO nativo

**Testing**:

- PHPUnit
- Pest PHP
- Behat (opcional para BDD)

## 📦 Entregables

1. **Código fuente** en repositorio Git (GitHub, GitLab, Bitbucket)
2. **README.md** con:

   - Instrucciones de instalación y ejecución
   - Documentación de la API (puede ser Swagger/OpenAPI)
   - Decisiones técnicas tomadas
   - Posibles mejoras futuras

3. **Docker Compose** funcional con:

   ```bash
   docker-compose up -d
   docker-compose exec app composer install
   docker-compose exec app php bin/console doctrine:migrations:migrate
   docker-compose exec app php bin/phpunit
   ```

4. **Colección de Postman** o archivo **Insomnia** con ejemplos de requests

## ✅ Criterios de Evaluación

### Arquitectura y Diseño (35%)

- Correcta implementación de arquitectura hexagonal
- Separación de responsabilidades
- Aplicación de principios SOLID
- Uso correcto de patrones de diseño

### Calidad del Código (25%)

- Código limpio y legible
- Nomenclatura consistente
- Manejo de errores
- Validaciones

### Testing (20%)

- Cobertura de tests
- Calidad de los tests
- Tests unitarios vs funcionales

### Funcionalidad (15%)

- Cumplimiento de requisitos
- Manejo de casos edge
- Respuestas API coherentes

### Docker y DevOps (5%)

- Facilidad de despliegue
- Configuración correcta

## 💡 Consejos

1. **Prioriza la arquitectura** sobre la cantidad de features
2. **No es necesario implementar autenticación** (asume que viene en headers)
3. **Usa DTOs** para la comunicación entre capas
4. **Implementa al menos un patrón de diseño** (Repository, Factory, Strategy, etc.)
5. **Documenta decisiones importantes** en el código

## 🚫 No es Necesario

- Sistema de autenticación/autorización
- Frontend
- Cache
- Colas/Jobs
- Websockets
- Logging avanzado (solo básico)

## 📊 Ejemplo de Respuestas API

### POST /api/tasks

```json
// Request
{
  "title": "Implementar nueva feature",
  "description": "Desarrollar el módulo de reportes",
  "priority": "high",
  "dueDate": "2024-12-31T23:59:59Z"
}

// Response 201
{
  "id": "550e8400-e29b-41d4-a716-446655440000",
  "title": "Implementar nueva feature",
  "description": "Desarrollar el módulo de reportes",
  "status": "pending",
  "priority": "high",
  "assignedTo": null,
  "dueDate": "2024-12-31T23:59:59Z",
  "createdAt": "2024-01-15T10:30:00Z",
  "updatedAt": "2024-01-15T10:30:00Z"
}
```

### GET /api/tasks?status=pending&priority=high

```json
// Response 200
{
  "data": [
    {
      "id": "550e8400-e29b-41d4-a716-446655440000",
      "title": "Implementar nueva feature",
      "status": "pending",
      "priority": "high",
      "assignedTo": {
        "id": "660e8400-e29b-41d4-a716-446655440001",
        "name": "John Doe"
      },
      "dueDate": "2024-12-31T23:59:59Z"
    }
  ],
  "meta": {
    "total": 1,
    "page": 1,
    "limit": 10
  }
}
```

## 🎯 Bonus Points (Opcional)

Si terminas antes de tiempo, puedes agregar:

1. **Event Sourcing** básico para auditoría
2. **CQRS** pattern para las consultas
3. **API Documentation** con OpenAPI/Swagger
4. **GitHub Actions** para CI/CD
5. **Mutation Testing** con Infection PHP
6. **Static Analysis** con PHPStan o Psalm
7. **Code Coverage Badge** en el README

---

## 📝 Notas Finales

- Valoramos más **calidad sobre cantidad**
- El código debe ser **production-ready** en términos de estructura
- Puedes usar librerías/packages que consideres necesarios
- Si no alcanzas a completar algo, documenta qué harías y cómo

**¡Buena suerte! 🚀**

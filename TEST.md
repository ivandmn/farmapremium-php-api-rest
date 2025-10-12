# PHP Backend Developer Technical Test

## 📋 Project Description

You must develop a **REST API for a simplified task management system**.  
The system should allow creating, listing, updating, and deleting tasks, as well as assigning them to users.

## ⏱️ Estimated Time

**4–5 hours maximum**

## 🎯 Evaluation Objectives

1. **Docker**: Proper setup of the development environment
2. **SOLID Principles**: Correct application of software design principles
3. **Hexagonal Architecture**: Clear separation of layers and dependencies
4. **Unit Testing**: Unit test coverage (minimum 70%)
5. **Functional Testing**: Integration tests for the endpoints

## 📝 Functional Requirements

### Entities

#### Task

- `id`: UUID
- `title`: string (max 255 characters)
- `description`: text
- `status`: enum (pending, in_progress, completed)
- `priority`: enum (low, medium, high)
- `assignedTo`: UUID (optional — assigned user)
- `dueDate`: datetime (optional — deadline)
- `createdAt`: datetime
- `updatedAt`: datetime

#### User (Simplified)

- `id`: UUID
- `name`: string
- `email`: string (unique)
- `createdAt`: datetime

### Required Endpoints

```
POST   /api/tasks             - Create a new task  
GET    /api/tasks             - List tasks (with optional filters)  
GET    /api/tasks/{id}        - Retrieve a task by ID  
PUT    /api/tasks/{id}        - Update a task  
DELETE /api/tasks/{id}        - Delete a task  
PATCH  /api/tasks/{id}/assign - Assign a task to a user  

POST   /api/users             - Create a new user  
GET    /api/users             - List users  
```

### Business Rules

1. A task cannot be assigned to a non-existent user
2. Tasks cannot be created with a past due date
3. Status transitions must follow this flow: `pending → in_progress → completed`
4. A completed task cannot change to another status
5. Only tasks in `pending` status can be deleted

## 🏗️ Technical Requirements

### 1. Dockerization

```dockerfile
# Minimum structure
/project
├── docker-compose.yml
├── Dockerfile
├── .env.example
└── ...
```

- PHP 8.2 or higher
- MySQL or PostgreSQL
- Web server (nginx or apache)
- Local development configuration

### 2. Hexagonal Architecture

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

### 3. SOLID Principles

You must demonstrate the correct application of:

- **S**ingle Responsibility Principle
- **O**pen/Closed Principle
- **L**iskov Substitution Principle
- **I**nterface Segregation Principle
- **D**ependency Inversion Principle

### 4. Testing

#### Unit Tests (PHPUnit)

```php
// Example structure
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

**Minimum coverage required:** 70% in Domain and Application layers.

#### Functional Tests

- Integration tests for all endpoints
- Validation of HTTP responses
- Validation of business rules

### 5. Allowed Technologies

**Frameworks:**

- Symfony 6+ (recommended)
- Laravel 10+
- Slim Framework 4

**ORM/Database:**

- Doctrine ORM
- Eloquent
- Native PDO

**Testing:**

- PHPUnit
- Pest PHP
- Behat (optional for BDD)

## 📦 Deliverables

1. **Source code** in a Git repository (GitHub, GitLab, or Bitbucket)
2. **README.md** including:
    - Installation and execution instructions
    - API documentation (Swagger/OpenAPI)
    - Technical decisions made
    - Possible future improvements
3. **Functional Docker setup** with:
   ```bash
   docker-compose up -d
   docker-compose exec app composer install
   docker-compose exec app php bin/console doctrine:migrations:migrate
   docker-compose exec app php bin/phpunit
   ```
4. **Postman collection** or **Insomnia file** with request examples

## ✅ Evaluation Criteria

### Architecture & Design (35%)

- Correct implementation of hexagonal architecture
- Proper separation of concerns
- Application of SOLID principles
- Use of design patterns

### Code Quality (25%)

- Clean and readable code
- Consistent naming conventions
- Error handling
- Input validation

### Testing (20%)

- Test coverage
- Quality of tests
- Balance between unit and functional tests

### Functionality (15%)

- Compliance with requirements
- Handling of edge cases
- Consistent API responses

### Docker & DevOps (5%)

- Ease of deployment
- Correct configuration

## 💡 Tips

1. **Prioritize architecture** over feature quantity
2. **Authentication is not required** (assume headers provide it)
3. **Use DTOs** for communication between layers
4. **Implement at least one design pattern** (Repository, Factory, Strategy, etc.)
5. **Document important decisions** in code or README

## 🚫 Not Required

- Authentication/authorization system
- Frontend
- Caching
- Queues/Jobs
- Websockets
- Advanced logging (basic is fine)

## 📊 Example API Responses

### POST /api/tasks

```json
// Request
{
  "title": "Implement new feature",
  "description": "Develop the reporting module",
  "priority": "high",
  "dueDate": "2024-12-31T23:59:59Z"
}

// Response 201
{
  "id": "550e8400-e29b-41d4-a716-446655440000",
  "title": "Implement new feature",
  "description": "Develop the reporting module",
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
      "title": "Implement new feature",
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

## 🎯 Bonus Points (Optional)

If you finish early, you can add:

1. Basic **Event Sourcing** for auditing
2. **CQRS** pattern for queries
3. **API documentation** with OpenAPI/Swagger
4. **GitHub Actions** for CI/CD
5. **Mutation Testing** using Infection PHP
6. **Static Analysis** with PHPStan or Psalm
7. **Code Coverage Badge** in the README

---

## 📝 Final Notes

- We value **quality over quantity**
- Code should be **production-ready** in terms of structure
- You may use any libraries/packages you consider necessary
- If you cannot complete something, document what you would do and how

**Good luck! 🚀**

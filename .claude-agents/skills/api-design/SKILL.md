# API Design Skill

REST and GraphQL API design patterns and best practices.

## What I Know

### REST API Design

**Resource Naming**
```
GET    /users          # List users
GET    /users/123      # Get specific user
POST   /users          # Create user
PUT    /users/123      # Update user (full)
PATCH  /users/123      # Update user (partial)
DELETE /users/123      # Delete user
```

**HTTP Status Codes**
```
200 OK              - Successful GET, PUT, PATCH
201 Created         - Successful POST
204 No Content      - Successful DELETE
400 Bad Request     - Invalid input
401 Unauthorized    - Not authenticated
403 Forbidden       - Authenticated but not authorized
404 Not Found       - Resource doesn't exist
409 Conflict        - Resource already exists
422 Unprocessable   - Validation errors
500 Server Error    - Server failure
```

**Request/Response Format**
```json
// Request
POST /api/users
{
  "email": "user@example.com",
  "username": "johndoe",
  "password": "SecurePass123!"
}

// Success Response (201)
{
  "data": {
    "id": "123",
    "email": "user@example.com",
    "username": "johndoe",
    "createdAt": "2024-01-01T00:00:00Z"
  }
}

// Error Response (422)
{
  "error": {
    "code": "VALIDATION_ERROR",
    "message": "Validation failed",
    "details": [
      {
        "field": "email",
        "message": "Email is required"
      }
    ]
  }
}
```

### API Versioning

**URL Versioning**
```
/api/v1/users
/api/v2/users
```

**Header Versioning**
```
GET /api/users
Accept: application/vnd.myapi.v2+json
```

### Authentication

**JWT Bearer Token**
```
Authorization: Bearer eyJhbGciOiJIUzI1NiIs...
```

**API Key**
```
Authorization: ApiKey YOUR_API_KEY
```

### Pagination

**Cursor-Based**
```json
{
  "data": [...],
  "pagination": {
    "next": "eyJpZCI6IjEyMyJ9",
    "limit": 20,
    "hasMore": true
  }
}
```

**Offset-Based**
```json
{
  "data": [...],
  "pagination": {
    "page": 1,
    "limit": 20,
    "total": 100,
    "totalPages": 5
  }
}
```

### Rate Limiting

**Headers**
```
X-RateLimit-Limit: 1000
X-RateLimit-Remaining: 999
X-RateLimit-Reset: 1635724800
```

**Response (429)**
```json
{
  "error": {
    "code": "RATE_LIMIT_EXCEEDED",
    "message": "Rate limit exceeded",
    "retryAfter": 3600
  }
}
```

---

*Part of SuperAI GitHub - Centralized Claude Code Configuration*
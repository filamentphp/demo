# Documentation Skill

Technical documentation standards and patterns.

## What I Know

### Code Documentation

**JSDoc (JavaScript/TypeScript)**
```typescript
/**
 * Creates a new user in the system.
 *
 * @param {CreateUserDto} data - User creation data
 * @param {string} data.email - User's email address
 * @param {string} data.username - Unique username
 * @param {string} data.password - User's password
 * @returns {Promise<User>} The created user object
 * @throws {ConflictException} If email or username already exists
 * @throws {BadRequestException} If validation fails
 *
 * @example
 * const user = await userService.createUser({
 *   email: 'user@example.com',
 *   username: 'johndoe',
 *   password: 'SecurePass123!'
 * })
 */
async createUser(data: CreateUserDto): Promise<User> {
  // Implementation...
}
```

**Python Docstrings**
```python
def create_user(email: str, username: str, password: str) -> User:
    """
    Create a new user in the system.

    Args:
        email: User's email address
        username: Unique username
        password: User's password (will be hashed)

    Returns:
        The created User object

    Raises:
        ConflictError: If email or username already exists
        ValidationError: If validation fails

    Example:
        >>> user = create_user('user@example.com', 'johndoe', 'pass123')
        >>> print(user.id)
        '123'
    """
    # Implementation...
```

### API Documentation

**OpenAPI Specification**
```yaml
openapi: 3.0.0
info:
  title: My API
  version: 1.0.0
  description: API documentation
paths:
  /users:
    post:
      summary: Create user
      requestBody:
        required: true
        content:
          application/json:
            schema:
              type: object
              properties:
                email:
                  type: string
                  format: email
                username:
                  type: string
      responses:
        '201':
          description: User created
          content:
            application/json:
              schema:
                $ref: '#/components/schemas/User'
```

### README Structure

```markdown
# Project Name

Brief description of the project.

## Features

- Feature 1
- Feature 2
- Feature 3

## Installation

\`\`\`bash
npm install my-project
\`\`\`

## Usage

\`\`\`javascript
const project = require('my-project')
project.doSomething()
\`\`\`

## API Documentation

Link to full API docs...

## Contributing

Guidelines for contributors...

## License

MIT
```

### Documentation Best Practices

1. **Write for your audience** - Consider who will read this
2. **Keep it current** - Update docs with code changes
3. **Provide examples** - Show, don't just tell
4. **Use consistent formatting** - Follow style guides
5. **Include visuals** - Diagrams, screenshots when helpful
6. **Test instructions** - Verify steps actually work

---

*Part of SuperAI GitHub - Centralized Claude Code Configuration*
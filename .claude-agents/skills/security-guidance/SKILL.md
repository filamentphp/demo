# Security Guidance Skill

Security best practices, common vulnerabilities, and secure coding patterns.

## What I Know

### OWASP Top 10 (2021)

**A01: Broken Access Control**
```javascript
// Bad: No authorization check
app.get('/admin/users', (req, res) => {
  res.json(db.users.getAll())
})

// Good: Authorization check
app.get('/admin/users', requireAdmin, (req, res) => {
  if (!req.user.isAdmin) {
    return res.status(403).json({ error: 'Forbidden' })
  }
  res.json(db.users.getAll())
})
```

**A02: Cryptographic Failures**
```javascript
// Bad: Storing passwords in plain text
user.password = plainTextPassword

// Good: Using bcrypt with salt
const bcrypt = require('bcrypt')
const hashedPassword = await bcrypt.hash(plainTextPassword, 10)
user.password = hashedPassword
```

**A03: Injection**
```javascript
// Bad: SQL injection vulnerability
const query = `SELECT * FROM users WHERE id = ${userId}`

// Good: Parameterized queries
const query = 'SELECT * FROM users WHERE id = ?'
db.query(query, [userId])
```

**A04: Insecure Design**
```javascript
// Bad: Hardcoded secrets
const API_KEY = 'sk_live_1234567890abcdef'

// Good: Environment variables
const API_KEY = process.env.API_KEY
```

**A05: Security Misconfiguration**
```javascript
// Bad: Error messages leak information
app.use((err, req, res, next) => {
  res.json({
    error: err.message,
    stack: err.stack // Leaks implementation details
  })
})

// Good: Generic error messages
app.use((err, req, res, next) => {
  console.error(err.stack) // Log detailed error
  res.status(500).json({
    error: 'Internal server error'
  })
})
```

**A06: Vulnerable Components**
```bash
# Bad: Outdated dependencies
npm install express@4.0.0

# Good: Regular updates
npm audit fix
npm update
```

**A07: Authentication Failures**
```javascript
// Bad: Weak password requirements
if (password.length >= 4) {
  // Accept weak password
}

// Good: Strong password requirements
const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/
if (passwordRegex.test(password)) {
  // Accept strong password
}
```

**A08: Software/Data Integrity**
```javascript
// Bad: Using unverified dependencies
import { maliciousPackage } from 'unverified-source'

// Good: Verified dependencies
import { trustedPackage } from 'npm'
```

**A09: Security Logging**
```javascript
// Bad: Not logging security events
function login(username, password) {
  authenticate(username, password)
}

// Good: Logging security events
function login(username, password) {
  const result = authenticate(username, password)
  logger.info('Login attempt', {
    username,
    success: result.success,
    ip: req.ip,
    userAgent: req.headers['user-agent']
  })
  return result
}
```

**A10: Server-Side Request Forgery (SSRF)**
```javascript
// Bad: Allowing arbitrary URLs
app.get('/proxy', (req, res) => {
  fetch(req.query.url) // User can access internal resources
    .then(response => response.json())
    .then(data => res.json(data))
})

// Good: URL whitelist
const allowedUrls = [
  'https://api.example.com',
  'https://api.trusted-source.com'
]

app.get('/proxy', (req, res) => {
  const url = new URL(req.query.url)
  if (!allowedUrls.includes(url.origin)) {
    return res.status(400).json({ error: 'URL not allowed' })
  }
  fetch(url)
    .then(response => response.json())
    .then(data => res.json(data))
})
```

### Security Best Practices

**1. Input Validation**
```javascript
// Validate all user input
const schema = z.object({
  email: z.string().email(),
  age: z.number().min(0).max(120),
  name: z.string().min(2).max(100)
})

function createUser(data) {
  const validated = schema.parse(data)
  // Process validated data
}
```

**2. Output Encoding**
```javascript
// Bad: Raw user input in HTML
div.innerHTML = userInput

// Good: Sanitized output
div.textContent = userInput

// Or use a sanitizer
import DOMPurify from 'dompurify'
div.innerHTML = DOMPurify.sanitize(userInput)
```

**3. Authentication**
```javascript
// Use JWT with expiration
const jwt = require('jsonwebtoken')

function generateToken(user) {
  return jwt.sign(
    { userId: user.id },
    process.env.JWT_SECRET,
    { expiresIn: '1h' }
  )
}
```

**4. HTTPS Only**
```javascript
// Force HTTPS in production
if (process.env.NODE_ENV === 'production') {
  app.use((req, res, next) => {
    if (!req.secure) {
      return res.redirect(`https://${req.headers.host}${req.url}`)
    }
    next()
  })
}
```

**5. Rate Limiting**
```javascript
const rateLimit = require('express-rate-limit')

const limiter = rateLimit({
  windowMs: 15 * 60 * 1000, // 15 minutes
  max: 100 // limit each IP to 100 requests per windowMs
})

app.use('/api/', limiter)
```

---

*Part of SuperAI GitHub - Centralized Claude Code Configuration*
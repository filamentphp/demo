# Code Review Skill

Code review patterns, best practices, and evaluation criteria.

## What I Know

### Review Process

**1. Understand Context**
- What problem is being solved?
- What are the requirements?
- What are the constraints?

**2. Check Functionality**
- Does the code work as intended?
- Are edge cases handled?
- Is error handling appropriate?

**3. Assess Quality**
- Is code readable and maintainable?
- Are patterns followed consistently?
- Is naming clear and descriptive?

**4. Security & Performance**
- Are there security vulnerabilities?
- Is performance optimized?
- Are resources managed properly?

### Review Checklist

**Code Quality**
- [ ] Code follows project style guide
- [ ] Functions are small and focused
- [ ] Variable names are descriptive
- [ ] Magic numbers are replaced with constants
- [ ] Comments explain "why", not "what"
- [ ] No dead or commented code

**Error Handling**
- [ ] Errors are caught and handled
- [ ] Error messages are helpful
- [ ] Failures are logged appropriately
- [ ] No silent failures

**Testing**
- [ ] Unit tests cover new code
- [ ] Tests cover edge cases
- [ ] Test names are descriptive
- [ ] Tests are independent

**Security**
- [ ] Input is validated
- [ ] Output is escaped/sanitized
- [ ] Authentication/authorization checks
- [ ] No hardcoded secrets
- [ ] Dependencies are up to date

**Performance**
- [ ] No unnecessary computations
- [ ] Database queries are optimized
- [ ] Caching is used where appropriate
- [ ] Resources are properly cleaned up

### Code Review Feedback Template

```markdown
## Review Summary

**Overall:** [Positive/Constructive/Negative]

### Strengths
- What was done well
- What patterns were followed correctly

### Issues

#### Critical
- Must-fix issues with examples

#### Important  
- Should-fix issues with examples

#### Suggestions
- Nice-to-have improvements

### Questions
- Clarification needed on certain approaches

### Next Steps
- What to do before merging
```

### Common Issues to Look For

**1. TypeScript**
```typescript
// Bad: Using any
function processData(data: any) {
  return data.map((item: any) => item.value)
}

// Good: Proper typing
interface DataItem {
  value: string
}

function processData(data: DataItem[]): string[] {
  return data.map(item => item.value)
}
```

**2. React**
```jsx
// Bad: Missing dependencies
useEffect(() => {
  fetchData(userId)
}, []) // Missing userId dependency

// Good: All dependencies included
useEffect(() => {
  fetchData(userId)
}, [userId])
```

**3. API Design**
```typescript
// Bad: Inconsistent response structure
{ data: { user: {...} } }
{ user: {...} }
{ error: null }
{ success: true, result: {...} }

// Good: Consistent structure
{ data: User, error: null }
{ data: null, error: Error }
```

---

*Part of SuperAI GitHub - Centralized Claude Code Configuration*
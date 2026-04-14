# Code Review Prompt Template

Use this prompt template for comprehensive code reviews.

## Instructions

Review the following code changes focusing on:

1. **Code Quality**
   - Readability and maintainability
   - Consistent naming conventions
   - Proper error handling
   - Code duplication

2. **Potential Issues**
   - Edge cases and null checks
   - Race conditions or async issues
   - Resource leaks (unclosed connections, memory)
   - Performance bottlenecks

3. **Testing**
   - New code has test coverage
   - Tests cover edge cases
   - Test naming is descriptive

4. **Documentation**
   - Complex logic is commented
   - API changes are documented
   - Breaking changes are noted

5. **Linked Issues**
   - If issues are linked, verify the PR addresses them

## Output Format

```markdown
## Summary
[Brief overview of changes]

## Issues Found
- **Critical**: [blockers that must be fixed]
- **Important**: [should fix before merge]
- **Suggestions**: [nice-to-have improvements]

## Positive Notes
[What was done well]
```

---

*Part of SuperAI GitHub - Prompt Templates*
# Issue Triage Prompt Template

Use this prompt template for automatic issue triage.

## Instructions

A new issue has been reported. Help triage it by:

## Current Issue

**Title:** {ISSUE_TITLE}
**Body:** {ISSUE_BODY}
**Author:** {ISSUE_AUTHOR}
**Number:** #{ISSUE_NUMBER}

## Related Issues Found

{RELATED_ISSUES}

## Triage Steps

1. **Check for Duplicates**
   - Review the related issues above
   - If this is a duplicate, mention it and reference the original issue number

2. **Understand the Issue**
   - Analyze what the user is reporting
   - Identify the core problem

3. **Identify the Type**
   - Classify as: bug, feature request, question, or performance issue

4. **Assess Priority**
   - Determine: critical, high, medium, or low
   - Consider impact and urgency

5. **Suggest Labels**
   - Propose appropriate GitHub labels
   - Examples: bug, enhancement, documentation, good first issue, help wanted

6. **Provide Initial Response**
   - If it's a common issue or duplicate, provide a helpful response
   - For bugs, suggest possible root causes and information needed
   - For feature requests, assess alignment with project goals

## Output Format

```markdown
## Issue Analysis

**Type:** [bug/feature/question/performance]
**Priority:** [critical/high/medium/low]
**Duplicate:** [yes/no + reference]

## Classification

[Detailed analysis of the issue]

## Suggested Labels

- label1
- label2
- label3

## Initial Response

[Helpful response to the user]

## Next Steps

[What should happen next]
```

---

*Part of SuperAI GitHub - Prompt Templates*
# Security Audit Prompt Template

Use this prompt template for comprehensive security audits.

## Instructions

Perform a comprehensive security audit of this repository focusing on:

### OWASP Top 10 (2021)

1. **A01:2021 Broken Access Control** (CWE-200, CWE-284, CWE-285, CWE-352)
2. **A02:2021 Cryptographic Failures** (CWE-259, CWE-327, CWE-328)
3. **A03:2021 Injection** (CWE-79, CWE-89, CWE-78)
4. **A04:2021 Insecure Design** (CWE-209, CWE-256, CWE-501)
5. **A05:2021 Security Misconfiguration** (CWE-16, CWE-611)
6. **A06:2021 Vulnerable Components** (CWE-1035)
7. **A07:2021 Auth Failures** (CWE-287, CWE-384, CWE-522)
8. **A08:2021 Software/Data Integrity** (CWE-494, CWE-502)
9. **A09:2021 Security Logging Failures** (CWE-117, CWE-223, CWE-532)
10. **A10:2021 SSRF** (CWE-918)

### Additional Checks

- **Dependency Vulnerabilities** with CVE references
- **Exposed Secrets** - API keys, passwords, tokens
- **Authentication/Authorization Issues**

## Output Format

For each finding, provide:

```markdown
### [SEVERITY] [Vulnerability Title]

**Location:** `file/path.ts:123`

**CWE:** CWE-XXX (Name)
**OWASP:** A0X:2021 (Category)
**CVE:** CVE-XXXX-XXXXX (if applicable)

**Description:**
[Clear explanation of the vulnerability]

**Vulnerable Code:**
```language
// The problematic code
```

**Impact:**
- [Potential consequences]
- [Attack scenarios]

**Recommendation:**
```language
// Secure code example
```

**References:**
- [Link to CWE]
- [Link to OWASP]
```

### Severity Levels

- **🔴 CRITICAL** - Remote code execution, data breach
- **🟠 HIGH** - Significant data exposure, privilege escalation
- **🟡 MEDIUM** - Limited data exposure, requires conditions
- **🟢 LOW** - Best practice, minimal impact

---

*Part of SuperAI GitHub - Prompt Templates*
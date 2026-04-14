# Frontend Design Skill

UI/UX design patterns, accessibility, and responsive design principles.

## What I Know

### Design Principles

**Visual Hierarchy**
```css
/* Clear hierarchy with size and weight */
.heading-primary {
  font-size: 2.5rem;
  font-weight: 700;
  line-height: 1.2;
}

.heading-secondary {
  font-size: 1.8rem;
  font-weight: 600;
  line-height: 1.3;
}

.body-text {
  font-size: 1rem;
  font-weight: 400;
  line-height: 1.6;
}

.caption-text {
  font-size: 0.875rem;
  font-weight: 400;
  line-height: 1.4;
  color: #666;
}
```

**Spacing System**
```css
/* Consistent spacing scale */
:root {
  --space-xs: 0.25rem;   /* 4px */
  --space-sm: 0.5rem;    /* 8px */
  --space-md: 1rem;      /* 16px */
  --space-lg: 1.5rem;    /* 24px */
  --space-xl: 2rem;      /* 32px */
  --space-2xl: 3rem;     /* 48px */
}

/* Usage */
.card {
  padding: var(--space-lg);
  margin-bottom: var(--space-xl);
}
```

**Color System**
```css
:root {
  /* Primary colors */
  --primary-50: #f0f9ff;
  --primary-500: #0ea5e9;
  --primary-700: #0369a1;
  
  /* Semantic colors */
  --color-bg: #ffffff;
  --color-bg-secondary: #f8fafc;
  --color-text: #0f172a;
  --color-text-secondary: #64748b;
  --color-border: #e2e8f0;
  
  /* Status colors */
  --color-success: #22c55e;
  --color-warning: #f59e0b;
  --color-error: #ef4444;
  --color-info: #3b82f6;
}
```

### Responsive Design

**Mobile-First Approach**
```css
/* Base styles for mobile */
.container {
  width: 100%;
  padding: 0 var(--space-md);
}

/* Tablet */
@media (min-width: 768px) {
  .container {
    max-width: 720px;
    margin: 0 auto;
  }
}

/* Desktop */
@media (min-width: 1024px) {
  .container {
    max-width: 960px;
  }
}

/* Large desktop */
@media (min-width: 1280px) {
  .container {
    max-width: 1200px;
  }
}
```

**Grid System**
```css
.grid {
  display: grid;
  gap: var(--space-md);
  grid-template-columns: 1fr;
}

@media (min-width: 768px) {
  .grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (min-width: 1024px) {
  .grid {
    grid-template-columns: repeat(3, 1fr);
  }
}
```

### Accessibility (WCAG 2.1 AA)

**Semantic HTML**
```html
<!-- Use semantic elements -->
<header>
  <nav aria-label="Main navigation">
    <ul>
      <li><a href="/">Home</a></li>
      <li><a href="/about">About</a></li>
    </ul>
  </nav>
</header>

<main>
  <article>
    <h1>Article Title</h1>
    <p>Article content...</p>
  </article>
</main>

<footer>
  <p>&copy; 2024 Company</p>
</footer>
```

**Focus States**
```css
/* Visible focus indicators */
a:focus,
button:focus {
  outline: 2px solid var(--primary-500);
  outline-offset: 2px;
}

/* Skip to main content */
.skip-to-main {
  position: absolute;
  top: -40px;
  left: 0;
  background: var(--primary-500);
  color: white;
  padding: 8px;
  text-decoration: none;
}

.skip-to-main:focus {
  top: 0;
}
```

**Color Contrast**
```css
/* Ensure WCAG AA contrast ratios (4.5:1 for normal text) */
.text-on-primary {
  color: var(--color-bg); /* White on primary background */
}

.text-secondary {
  color: var(--color-text-secondary); /* Sufficient contrast */
}
```

**ARIA Labels**
```html
<!-- Icon buttons need labels -->
<button aria-label="Close dialog">
  <svg><!-- close icon --></svg>
</button>

<!-- Form labels -->
<label for="email">Email</label>
<input id="email" type="email" required aria-required="true">

<!-- Live regions -->
<div role="status" aria-live="polite">
  Form submitted successfully
</div>
```

### Component Design

**Button Component**
```css
.btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: var(--space-sm) var(--space-md);
  font-weight: 500;
  border-radius: 6px;
  border: none;
  cursor: pointer;
  transition: all 0.2s;
}

.btn-primary {
  background-color: var(--primary-500);
  color: white;
}

.btn-primary:hover {
  background-color: var(--primary-700);
}

.btn-primary:active {
  transform: scale(0.98);
}

.btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}
```

**Card Component**
```css
.card {
  background: var(--color-bg);
  border: 1px solid var(--color-border);
  border-radius: 8px;
  padding: var(--space-lg);
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.card:hover {
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.card-header {
  margin-bottom: var(--space-md);
  padding-bottom: var(--space-md);
  border-bottom: 1px solid var(--color-border);
}

.card-body {
  line-height: 1.6;
}
```

### Typography

**Font Scale**
```css
/* Modular scale */
h1 { font-size: 2.5rem; /* 40px */ }
h2 { font-size: 2rem;   /* 32px */ }
h3 { font-size: 1.5rem; /* 24px */ }
h4 { font-size: 1.25rem; /* 20px */ }
h5 { font-size: 1rem;   /* 16px */ }
h6 { font-size: 0.875rem; /* 14px */ }
```

**Line Height**
```css
/* Readable line lengths and spacing */
body {
  font-size: 1rem;
  line-height: 1.6;
  max-width: 70ch; /* Optimal reading length */
}

headings {
  line-height: 1.2;
}
```

---

*Part of SuperAI GitHub - Centralized Claude Code Configuration*
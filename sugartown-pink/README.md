# Sugartown Design System — README

This design system governs **structure, meaning, and evolution**, not just visuals.

If you are:
- Writing CSS
- Editing PHP templates
- Generating code via AI
- Adding new UI patterns

You must follow this document.

---

## Core Rule

**Visual similarity does not equal semantic correctness.**

If something looks like a card, it must be a card.

---

## Light Mode First (Non-Negotiable)

The default visual baseline is **Light Mode**.

Dark Mode and Knowledge Graph styling:
- Are variants
- Use modifiers
- Must not redefine structure

If a component does not work in Light Mode, it is broken.

---

## Component Vocabulary (Authoritative)

### Cards
- `.st-card`
- `.st-card__header`
- `.st-card__body`
- `.st-card__meta`
- `.st-card__footer`

Variants:
- `.st-card--light` (default)
- `.st-card--dark`
- `.st-card--kg-dark`

Allowed aliases:
- `.pink-card`
- `.gem-card`

Do NOT invent new card classes.

---

### Callouts
- `.st-callout`
- `.st-callout--info`
- `.st-callout--soft`
- `.st-callout--dark`
- `.st-callout--kg-dark`

Allowed alias:
- `.filter-active-notice`

---

### Tags / Pills
- `.st-tag` (or `.skill-tag` alias)

Do NOT create page-specific pill styles.

---

## Styling Rules

- All component styles live in `style.css`
- No component CSS in page-specific files
- Variants use modifiers, not new components
- Avoid `!important` except as a temporary bridge

---

## Layout Rules

- All cards must be `display:flex; flex-direction:column`
- Footers must stick to bottom via layout, not magic numbers
- Grids must use shared min/max rules

---

## Dark Mode / Knowledge Graph

Dark Mode is a **variant**, not a redesign.

Correct:
```html
<article class="st-card st-card--kg-dark">

# Sugartown Pink

**Status:** 🟡 Transitional  
**Type:** WordPress Block Theme + Emerging Design System  
**Base Theme:** Twenty Twenty-Five  
**Primary Site:** sugartown.io  
**Detailed Spec:** See `sugartown-cms/docs` (Project 003)

---

## Purpose of This Repository <a id="purpose"></a>

This repository currently serves **two intentional purposes**:

1. **A production WordPress block theme** powering sugartown.io  
2. **A migration layer** for developing a small, portable design system  
   (“Sugartown Pink”) in preparation for an eventual CMS transition away from WordPress

These two concerns **coexist by design**.  
This README exists to explain *how* and *why*—for humans, future maintainers, and AI tooling.

---

## 1. Core Architecture (What This Repo Is) <a id="core-architecture"></a>

At its core, this repository is a **WordPress block theme**.

- **Base:** Twenty Twenty-Five (TT5)
- **Runtime:** WordPress Block Editor
- **Deployment:** Standard WP theme (PHP, `theme.json`, CSS)
- **Audience:** Contributors working on sugartown.io today

This repo should be treated as **production WordPress code** unless explicitly stated otherwise.

---

## 2. Why This Is More Than “Just a Theme” <a id="beyond-theme"></a>

Sugartown Pink is **not** merely custom WordPress styling.

This theme is being used as a **controlled environment** to:

- Layer a minimal design system on top of WordPress
- Reduce reliance on WordPress block markup over time
- Establish HTML + CSS contracts that can migrate to a future CMS

WordPress is the **current host platform**, not the long-term architectural goal.

---

## 3. Sugartown Pink (Design System in Progress) <a id="design-system-in-progress"></a>

“Sugartown Pink” is a small, opinionated design system being developed *inside* this theme.

It is intentionally:

- **Minimal** — cards, grids, typography, tokens  
- **Portable** — HTML + CSS first  
- **CMS-agnostic** — not conceptually tied to WordPress  

At present, Sugartown Pink is **not a standalone package**.  
It lives here because WordPress is the current runtime environment.

Over time, these primitives are expected to:
- Stabilize
- Be documented independently
- Migrate cleanly to a non-WordPress CMS or frontend architecture

---

## 4. Layout System (Current) <a id="layout-system"></a>

### Canonical Grid Wrapper <a id="canonical-grid"></a>

All card-based collections must use the shared grid pattern:

- `.st-grid-wrapper`
- `.st-grid` / `.gem-grid`

This grid contract ensures:
- Responsive auto-fill behavior
- Equal-height cards
- Predictable breakpoints
- No layout collapse or overlap

Archive- or page-specific grid hacks are not supported.

---

## 5. Component Vocabulary (Current Reality) <a id="component-vocabulary"></a>

### Legacy Card: `.pink-card` <a id="legacy-pink-card"></a>

`.pink-card` represents an earlier iteration of the system.

- Still supported for **existing content**
- Should **not** be used for new work
- Retained to avoid unnecessary churn

### Canonical Card: `st-card` <a id="canonical-st-card"></a>

The canonical card definition is governed by the **Design System Contracts**  
(see Appendix A).

---

## 6. WordPress-Specific Concerns (Implementation Details) <a id="wp-boundaries"></a>

The following concerns are still owned by WordPress and this theme:

- Block editor templates
- `theme.json` tokens
- PHP template wiring
- WordPress serialization quirks

These are considered **implementation details**, not design system features.

They may change—or disappear—during a future CMS migration.

---

## 7. Migration Intent (Non-Binding) <a id="migration-intent"></a>

This repository is part of a longer-term effort to move away from WordPress as the primary CMS for Sugartown.

This README will evolve as:
- Design system contracts stabilize
- CMS boundaries become clearer
- Components are extracted or re-homed

Nothing in this file should be interpreted as a promise of timeline, tooling, or platform.

---

## 8. How to Read This Repo Today <a id="how-to-read"></a>

If you are:

- **Editing content or layouts today**  
  → Treat this as a WordPress theme

- **Working on components or CSS**  
  → Prefer design system primitives over WP block markup

- **Planning for future CMS migration**  
  → Focus on HTML + CSS contracts, not WordPress APIs

---

# Appendix A: Design System Contracts (Stable) <a id="design-system-contracts"></a>

This appendix defines **stable, enforceable contracts** for the Sugartown design system.

These rules are intended to change **slowly** and may be referenced by:
- Release notes
- CI checks
- AI tooling
- Documentation governance

---

## A.1 Canonical Card Contract <a id="contract-st-card"></a>

### `st-card`

`st-card` is the **single canonical card primitive** for Sugartown.

All card-based surfaces—including:
- homepage grids
- archive views
- search results
- featured collections

**must** use the `st-card` structure and tokens.

### Required Structure

```text
.st-card
  ├─ .st-card__bg        (optional)
  ├─ .st-card__inner
      ├─ .st-card__header
      ├─ .st-card__content
      ├─ .st-card__footer
      ├─ .st-card__tags
      └─ .st-card__media
```

Header/footer pinning, spacing, and z-index behavior are defined by the component
contract and must not be overridden per surface.

---

## A.2 Grid Contract <a id="contract-grid"></a>

All collections of `st-card` components must be wrapped in the canonical grid:

- `.st-grid-wrapper`
- `.st-grid` / `.gem-grid`

Inline grid definitions or archive-specific layout overrides are not permitted.

---

## A.3 Token Usage <a id="contract-tokens"></a>

Visual properties must flow through system tokens where defined:

- Color
- Radius
- Typography
- Spacing
- Elevation / shadow

Hard-coded overrides are allowed **only** when a token does not yet exist.

---

## A.4 Deprecation Policy <a id="contract-deprecation"></a>

- Deprecated components remain supported until explicitly removed
- New work must target canonical components
- Deprecation notices must be documented here **and** in `CHANGELOG.md`

---

## A.5 WordPress Boundary Rule <a id="contract-wp-boundary"></a>

Design system contracts must **not** depend on:

- WordPress block class names
- Editor-generated wrapper markup
- WP-specific DOM structure

If a component cannot survive outside WordPress, it is **not** a design system primitive.

---

### End of Document

This README is expected to evolve.  
The **Design System Contracts appendix is expected to remain stable**.

Changes to Appendix A should be rare, deliberate, and explicitly versioned.

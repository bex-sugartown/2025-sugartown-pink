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

### Interactive Primitive: `st-chip` <a id="st-chip"></a>

**Status:** ✅ Canonical (v2025.12.27)  
**Purpose:** Interactive pill/tag component for filters, category links, and navigation

`st-chip` is the canonical interactive primitive for clickable tags, category pills, and filter elements.

**Structure:**
```html
<a href="..." class="st-chip">
    Label Text
</a>
```

**Variants:**
- `.st-chip` (default: light background, pink hover)
- `.st-chip--active` (selected state)
- `.st-chip--dark` (dark background variant)

**Usage Context:**
- Archive filter bars (Project, Status, Topic)
- Hero "Start Here" navigation links
- Category/tag pill collections
- Any interactive chip/pill UI pattern

**States:**
- Default: subtle background, border
- Hover: pink background (#FF69B4), white text
- Active: maintains pink background, bold weight

**Container Utility:**
Use `.st-chip-row` to wrap multiple chips with consistent gap spacing:
```html
<div class="st-chip-row">
    <a href="..." class="st-chip">Chip 1</a>
    <a href="..." class="st-chip st-chip--active">Chip 2</a>
    <a href="..." class="st-chip">Chip 3</a>
</div>
```

**Design Intent:**
Provides consistent interactive behavior across all chip-style UI elements. Replaces inconsistent custom filter/tag implementations.

---

### Typography Primitive: `st-label` <a id="st-label"></a>

**Status:** ✅ Canonical (v2025.12.27)  
**Purpose:** Consistent metadata label typography

`st-label` standardizes label styling for metadata fields across cards, filters, and content sections.

**Structure:**
```html
<span class="st-label">Project:</span>
<span class="st-label">Category:</span>
<span class="st-label">Next Step:</span>
```

**Visual Properties:**
- Color: `#999` (light gray)
- Font weight: 500 (medium)
- Font size: 0.9rem (or inherit)
- Text transform: none (lowercase preserved)
- White-space: nowrap (prevents awkward wrapping)

**Usage Context:**
- Card metadata rows ("Project:", "Category:", "Tags:")
- Filter section headers
- Form field labels
- Any metadata key/label pairing

**Deprecated Alternative:**
- ~~`.kg-filter-bar__label`~~ → Use `.st-label` instead

**Design Intent:**
Creates visual hierarchy by distinguishing labels from their values. Ensures consistent typography across all metadata displays.

---

### Layout Utility: `st-chip-row` <a id="st-chip-row"></a>

**Status:** ✅ Canonical (v2025.12.27)  
**Purpose:** Uniform horizontal chip/pill layout with wrapping

**Structure:**
```html
<div class="st-chip-row">
    <!-- Multiple st-chip elements -->
</div>
```

**Behavior:**
- Flexbox row layout with wrapping
- Consistent gap spacing (8px default)
- Aligns chips to flex-start
- Handles overflow gracefully

**Usage Context:**
- Filter bars (horizontal chip collections)
- Tag/category pill rows in cards
- Multi-select UI patterns

**Design Intent:**
Eliminates inconsistent spacing and alignment in chip collections. Single utility for all horizontal pill layouts.

---

### Template Part Integration Pattern

WordPress Block Theme template parts (Site Editor managed) must be rendered in PHP templates using `do_blocks()`:
```php
// header.php
if ( has_block( 'core/template-part', 'pink-header' ) ) {
    echo do_blocks( '<!-- wp:template-part {"slug":"pink-header"} /-->' );
}

// footer.php
if ( has_block( 'core/template-part', 'pink-footer' ) ) {
    echo do_blocks( '<!-- wp:template-part {"slug":"pink-footer"} /-->' );
}
```

This ensures visual consistency between CMS-managed pages and PHP archive templates.

**Shipped:** v2025.12.24 (resolved header/footer parity across archive + content pages)

---

## 5.2 Archive Filter System <a id="archive-filter-system"></a>

**Status:** ✅ Canonical (v2025.12.27)  
**Components:** `st-chip`, `st-label`, `st-chip-row`

### Filter Bar Architecture

The archive filter system uses interactive `st-chip` primitives organized by filter category.

**Structure:**
```html
<div class="kg-filter-bar">
    <div class="kg-filter-section">
        <span class="st-label">Project:</span>
        <div class="st-chip-row">
            <a href="..." class="st-chip">PROJ-001</a>
            <a href="..." class="st-chip st-chip--active">PROJ-002</a>
        </div>
    </div>
    
    <div class="kg-filter-section">
        <span class="st-label">Topic:</span>
        <div class="kg-filter-dropdown">
            <!-- Multi-column dropdown with st-chip elements -->
        </div>
    </div>
</div>
```

### Filter Types

1. **Inline Chips** (Project, Status)
   - Horizontal chip row with wrapping
   - Single-select behavior
   - Direct category filtering

2. **Dropdown Filters** (Topic)
   - Floating multi-column layout
   - Checkbox-style multi-select
   - Tag-based filtering with OR logic

### Mobile Behavior

**Breakpoint:** 800px

- **Desktop (>800px):**
  - Inline horizontal layout
  - Multi-column dropdown for Topic
  
- **Mobile (≤800px):**
  - Full-width overlay
  - Single-column chip stacking
  - Sticky filter bar

### Content Container Alignment

**Rule:** All filter bars and hero sections must align with the 800px content container.

```css
.kg-filter-bar,
.kg-hero {
    max-width: 800px;
    margin: 0 auto;
}
```

**Rationale:**
- Maintains visual continuity with main content
- Prevents filter elements from spanning full viewport width
- Creates consistent left-edge alignment on wide screens

**Applies to:**
- Archive filter bars
- Hero "Start Here" sections
- Any full-width interactive navigation elements

**Shipped:** v2025.12.27

---

## 5.3 Deprecated Components <a id="deprecated-components"></a>

### ~~`.kg-filter-bar__label`~~ → Use `.st-label` <a id="deprecated-kg-filter-label"></a>

**Status:** ⚠️ Deprecated (v2025.12.27)  
**Replacement:** `.st-label`

**Reason for Deprecation:**
- Inconsistent naming convention (prefixed with `kg-` instead of `st-`)
- Hardcoded uppercase text-transform (not flexible)
- Tied to filter-bar context (not reusable)

**Migration Path:**
```html
<!-- OLD (Deprecated) -->
<span class="kg-filter-bar__label">PROJECT</span>

<!-- NEW (Canonical) -->
<span class="st-label">Project:</span>
```

**Breaking Changes:**
- Text transform: uppercase → none (preserve natural case)
- Context independence: filter-specific → universal label primitive

**Sunset Timeline:**
- v2025.12.27: Marked deprecated, `.st-label` canonical
- v2026.01.xx: Remove from codebase (TBD)

**Support Status:**
- Still rendered if present in legacy markup
- No new usage permitted
- Existing instances should migrate on next edit

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

## A.1 Canonical Card Contract (`st-card`) <a id="contract-st-card"></a>

The `st-card` is the atomic unit of the Sugartown Knowledge Graph. It is a strictly controlled container that enforces content hierarchy regardless of content length.

**Status:** Stable (v3.3.3)
**Implementation:** `style.css` (Section 5.0)

### 1. Anatomy & DOM Structure
The card uses a `flex-column` layout with a nested `CSS Grid` header to prevent layout collision between metadata and status badges.

```html
<article class="st-card [st-card--dark]">
  <div class="st-card__bg"></div>

  <div class="st-card__inner">
    
    <header class="st-card__header">
      <div class="st-card__eyebrow">Project ID</div>  <div class="st-badge">Status</div>              <h2 class="st-card__title">Title</h2>           <div class="st-card__subtitle">Category</div>   </header>

    <div class="st-card__content">
      <p>Excerpt or Description...</p>
      </div>

    <footer class="st-card__footer">
      <div class="st-card__action">Next Step</div>    <time class="st-card__date">YYYY-MM-DD</time>   </footer>

  </div>
</article>
```

### 2. Layout Behavior (The "Split" Contract)
To ensure visual alignment across a grid of uneven content:

1.  **Height:** Always `100%` of grid cell.
2.  **Pinning:** `.st-card__inner` uses `flex: 1 1 auto` to fill available space.
3.  **Footer:** `.st-card__footer` uses `margin-top: auto` to aggressively push to the bottom edge.
4.  **Header:** Uses `grid-template-columns: 1fr auto` to ensure the Badge never overlaps the Eyebrow.

### 3. Token Mapping (The "Pink" Contract)
Visual properties must reference system tokens, not hardcoded hex values.

| Property | Token / Rule | Context |
| :--- | :--- | :--- |
| **Border** | `1px solid var(--st-card-border)` | Restored in v3.3.3 |
| **Radius** | `var(--st-radius-md)` (12px) | Standard card curvature |
| **Shadow** | `var(--st-shadow-card)` | Pink-tinted elevation |
| **Font (Eyebrow)** | `'Menlo', monospace` | Terminal aesthetic |
| **Font (Title)** | `Playfair Display` | Editorial aesthetic |

### 4. Variants & Logic

#### Light Variant (Default)
* **Background:** `var(--st-color-surface)` (#fff)
* **Text:** `var(--st-color-text)` (#1e1e1e)
* **Border:** `#FE1295` (Primary Brand)

#### Dark Variant (`.st-card--dark`)
* **Trigger:** System logic applies this class if tags include: `system`, `meta`, `architecture`, `dx`.
* **Background:** `#0f1117` (Void)
* **Text:** `rgba(230,232,238,0.72)` (Muted Cool White)
* **Tags:** Inverted "Terminal" style (transparent bg + pink border).

### 5. Interaction States
* **Hover:** `transform: translateY(-4px)` + Enhanced Shadow.
* **Click Target:** The title link (`st-card__title a`) should be the primary interaction.
* **Tags:** Individual tags (`st-card__tag`) remain clickable and independent of the card link.
  
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

## A.6 Content Model Separation Rule <a id="contract-content-separation"></a>

**Established:** v2025.12.24

Narrative content must not be hardcoded in templates.

**Enforcement:**
- Landing page content → First-class Gems (CPT with meta fields)
- Archive templates → Structure only (grids, filters, pagination)
- PHP templates → Layout logic, not storytelling

**Rationale:**
Hardcoded narrative in templates:
- Cannot be queried or filtered
- Does not participate in taxonomy
- Creates CMS/template boundary violations
- Blocks future headless migrations

**Example Violation (Deprecated):**
```php
// ❌ WRONG: Hardcoded narrative in archive-gem.php
<div class="archive-intro">
    <h1>The Knowledge Graph</h1>
    <p>This is the story of how scope creep became a feature...</p>
</div>
```

**Correct Pattern (Enforced):**
```php
// ✅ CORRECT: Narrative as queryable Gem
$landing_gem = get_posts([
    'name' => 'knowledge-graph-landing',
    'post_type' => 'gem',
    'posts_per_page' => 1
]);

if ( $landing_gem && !is_filtered_archive() ) {
    echo render_gem_content( $landing_gem[0] );
}
```

This rule applies to:
- Section landing pages
- Archive introductions
- Feature explanations
- Any prose longer than UI microcopy

---

## CSS Architecture Rules for AI Assistants

### Before Writing Any CSS:

1. **Check if DS component exists:** Search `style.css` for `.ds-*` classes
2. **Use tokens, never hardcode:** Every value must be `var(--ds-*)`
3. **Semantic naming only:** `.ds-metadata` not `.ethics-metadata`
4. **10-line rule:** If page CSS >10 lines, you're doing it wrong
5. **Ask first:** "Should this be added to the design system?"

### Response Template:

When user asks for page styling:

1. First, identify which DS components to use
2. If component missing, propose adding it to DS (not page CSS)
3. Show HTML using DS classes
4. Show minimal page CSS (if any)
5. Flag if approach violates DS principles

### Red Flags:
- Creating `.page-specific-thing` classes
- Hardcoded `#hex` colors or `px` values
- Duplicating existing DS patterns
- Page CSS >10 lines

---

### End of Document

This README is expected to evolve.  
The **Design System Contracts appendix is expected to remain stable**.

Changes to Appendix A should be rare, deliberate, and explicitly versioned.
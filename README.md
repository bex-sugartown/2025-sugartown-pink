# Sugartown Design System (v3.3)

**Status:** 🟡 In Flux (Hybrid: WP Theme + Independent System)  
**Detailed Spec:** [See PRD Project 003](https://github.com/bex-sugartown/sugartown-cms/tree/main/docs)

This system governs **structure, meaning, and layout**. It creates a portable visual language that exists independently of the WordPress Block Editor.

---

## 1. Core Architecture: Decoupled & Generated
We have moved away from "styling WordPress blocks." We now "inject clean HTML" into WordPress.

* **HTML Source:** Generated programmatically via `layout_engine.py` (Python).
* **Styling Source:** Rendered via `style.css` (WordPress Theme).
* **The Rule:** Do not rely on `wp-block-group` for layout. Own the container.

---

## 2. The Grid System (`.st-grid-wrapper`)
The universal layout engine for cards.
* **Behavior:** Responsive Grid (`display: grid`).
* **Logic:** `auto-fill` + `minmax(300px, 1fr)` (Prevents collapse & overlap).
* **Usage:** Must wrap all card collections.

---

## 3. Component Vocabulary (Authoritative)

### A. The Pink Card (`.pink-card`) [Stable v3.3]
The primary unit of content.
* **Structure:** Flat HTML. No internal WP block wrappers.
* **Box Model:** `box-sizing: border-box` (Critical for overlap prevention).
* **Layers:**
    * `z-0`: Texture (`.pink-card__bg`)
    * `z-1`: White Canvas
    * `z-2`: Content (`.pink-card__content` + `.pink-card__media`)
* **Media:** Native SVG icons (Hot Pink), centered, no filters.

### B. Typography & Globals
* **Headings:** `Playfair Display` (The Human Voice).
* **Data/UI:** `Fira Sans` & `Menlo` (The System Voice).
* **Code:** Dark Mode Terminal blocks (`.wp-block-code`).

---

## 4. AI Usage Policy
When generating UI code:
1.  **Do not** use WordPress Block classes (`wp-block-columns`, `wp-block-image`) for internal component structure.
2.  **Use** the `layout_engine` Python module to generate standard HTML.
3.  **Verify** against `style.css` v3.3+ to ensure box-model safety.
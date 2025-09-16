# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a **Japanese static website template** for creating landing pages from Figma designs. The project uses pure HTML, CSS, and vanilla JavaScript with no build process - it's designed for direct deployment of static files.

## Architecture & Structure

### Technology Stack
- **Frontend**: Pure HTML5, CSS3, vanilla JavaScript
- **CSS Reset**: Ress CSS (located in `assets/css/ress.css` and `assets/css/ress.min.css`)
- **Architecture**: FLOCSS + BEM methodology
- **Responsive**: Mobile-first with single breakpoint at 768px
- **Build Process**: None (static files only)

### Directory Structure
```
project-root/
├─ index.html
├─ RULES.md              # Comprehensive Japanese development guidelines
└─ assets/
   ├─ css/
   │  ├─ ress.css         # CSS reset (full)
   │  ├─ ress.min.css     # CSS reset (minified)
   │  └─ style.css        # Main stylesheet (to be created)
   ├─ img/                # Images directory
   └─ js/
      └─ script.js        # Main JavaScript (optional)
```

## CSS Architecture (FLOCSS + BEM)

### Naming Convention Prefixes
- **Layout**: `l-` (e.g., `l-inner`, `l-main`, `l-section`)
- **Component**: `c-` (e.g., `c-button`, `c-section-title`)
- **Page**: `p-` (e.g., `p-section`, `p-faq`)
- **Utility**: `u-` (e.g., `u-ar-16x9`, `u-ar-4x3`, `u-ar-1x1`)
- **JavaScript hooks**: `js-` (e.g., `js-accordion`, `js-hamburger`, `js-drawer`)

### Standard Section Structure
```html
<section class="p-section">
  <div class="p-section__inner l-inner">
    <div class="p-section__heading">
      <hgroup class="c-section-title">
        <h2 class="c-section-title__main">タイトル</h2>
        <p class="c-section-title__sub">サブ</p>
      </hgroup>
    </div>
    <div class="p-section__content"><!-- コンテンツ --></div>
  </div>
</section>
```

## Development Standards

### CSS Standards
- **Units**: Use `px` exclusively (no rem, em, or other units)
- **Responsive**: Mobile-first approach with `@media (min-width: 768px)`
- **Variables**: Use CSS custom properties for colors, typography, and spacing
- **Reset**: Always include `assets/css/ress.min.css` before main stylesheet
- **Images**: Parent containers manage aspect ratios, images use `width:100%; height:auto; object-fit:cover`

### HTML Standards
- **Semantic**: Use proper HTML5 semantic elements
- **Headings**: h1 for logo/site title, maintain proper heading hierarchy
- **Images**: Include `width` and `height` attributes for CLS prevention
- **Language**: Set `lang="ja"` for Japanese content

### JavaScript Standards
- **Vanilla JS**: No frameworks or libraries
- **Pattern**: Use class-based components for interactive elements
- **Initialization**: Wrap in `DOMContentLoaded` event listener
- **Hooks**: Use `.js-` prefixed classes for JavaScript targets

### Image Management
- **Formats**: Prefer JPG/JPEG, use PNG only for transparency, WebP supported
- **Location**: Store in `assets/img/` directory
- **Aspect Ratios**: Use utility classes (`u-ar-16x9`, `u-ar-4x3`, `u-ar-1x1`)

## Common JavaScript Components

### Available JS Hooks
- `.js-accordion` - Accordion/FAQ functionality
- `.js-hamburger` - Mobile menu trigger
- `.js-drawer` - Mobile navigation drawer
- `.js-header` - Header interactions
- `.js-to-top` - Scroll-to-top button

### Standard Accordion Pattern
```javascript
class Accordion {
  constructor(el) {
    this.el = el;
    this.q = el.querySelector('.p-faq__q');
    this.a = el.querySelector('.p-faq__a');
    this.q && this.q.addEventListener('click', () => this.toggle());
  }
  toggle() {
    const open = this.el.classList.toggle('is-open');
    if (this.a) this.a.style.maxHeight = open ? this.a.scrollHeight + 'px' : '0';
  }
}
```

## Important Notes

- This project has comprehensive development guidelines in `RULES.md` (in Japanese)
- The project is template-based - no build tools or package managers
- All styles and scripts are included directly in HTML files
- The codebase follows strict Japanese web development conventions
- Focus on pixel-perfect implementation from Figma designs
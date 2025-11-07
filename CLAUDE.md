# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a flexible frontend development template that supports multiple build targets:

- **Static HTML** (default): Traditional HTML/CSS/JavaScript
- **EJS Mode**: Template-based HTML generation with JSON data
- **WordPress Mode**: PHP theme development with optional Local by Flywheel integration

The project uses Gulp for task automation, Sass for styling, and Babel for JavaScript transpilation.

## Build Configuration

All build settings are managed via environment variables in [environments/.env.local](environments/.env.local):

- `EJS_MODE`: Set to `true` for EJS template compilation, `false` for static HTML
- `WP_MODE`: Set to `true` for WordPress theme development, `false` otherwise
- `WP_LOCAL_MODE`: Set to `true` to sync directly to Local by Flywheel theme directory
- `SITE_TITLE`: WordPress site name (for Local by Flywheel path resolution)
- `THEME_NAME`: WordPress theme folder name
- `LOCAL_SITE_DOMAIN`: Local by Flywheel domain (e.g., `template.local`)
- `JPEG_QUALITY`: JPEG compression quality (0-100)
- `BROWSERS`: Browserslist configuration for autoprefixer

## Common Commands

**Initial Setup:**

```bash
cd _gulp
npm i
```

**Development:**

```bash
cd _gulp
npx gulp
```

Starts BrowserSync development server with file watching. Keep this running during development.

**Production Build:**

```bash
cd _gulp
npx gulp build
```

Cleans output directories and performs production build with optimizations.

**Production Build (Skip Images):**

```bash
cd _gulp
npx gulp build_no_images
```

Same as `build` but skips image optimization (useful for faster rebuilds).

**Package Management:**

```bash
cd _gulp
ncu                    # Check for outdated packages
ncu -u                 # Update package.json to latest versions
npm audit              # Check for vulnerabilities
npm audit fix          # Fix vulnerabilities (safe)
npm audit fix --force  # Fix vulnerabilities (aggressive)
```

## Project Architecture

### Directory Structure

```
src/                           # All source files (ONLY edit here)
│
├─ ejs/                        # EJS templates (when EJS_MODE=true)
│   ├─ common/                 # Header, footer, head partials
│   ├─ component/              # Reusable UI components (_c-*.ejs)
│   ├─ project/                # Page-specific sections (_p-*.ejs)
│   ├─ pageData/               # JSON data for templates
│   └─ lowerpage/              # Lower-level page templates
│
├─ wp/                         # WordPress theme files (when WP_MODE=true)
│   ├─ functions-lib/          # Modular function PHP files (f-*.php)
│   └─ parts/                  # Template parts
│
├─ sass/                       # Sass stylesheets
│   ├─ foundation/             # Reset, base, initialize
│   ├─ layout/                 # Layout components (_l-*.scss)
│   ├─ global/                 # Variables, mixins, breakpoints
│   └─ object/                 # FLOCSS methodology
│       ├─ component/          # Reusable components (_c-*.scss)
│       ├─ project/            # Page-specific styles (_p-*.scss)
│       └─ utility/            # Utility classes (_u-*.scss)
│
├─ assets/                     # Static assets
│   ├─ css/                    # Pre-compiled CSS (optional)
│   ├─ js/                     # JavaScript
│   └─ images/                 # Source images (auto-optimized + WebP)
│
├─ dist/                       # Output for static/EJS build (DO NOT EDIT)
└─ distwp/                     # Output for WordPress build (DO NOT EDIT)

_gulp/                         # Gulp build system
│
├─ gulpfile.js                 # Gulp task definitions
└─ package.json                # Dependencies + Node.js version pinning (Volta)
```

### Sass Architecture

This project follows **FLOCSS** (Foundation, Layout, Object, Component, Component, Scope) methodology:

- **Foundation**: Reset and base styles that apply globally
- **Layout**: Structural layout components (header, footer, grids)
- **Object/Component**: Reusable UI components (buttons, cards, forms)
- **Object/Project**: Page-specific or feature-specific modules
- **Object/Utility**: Single-purpose utility classes (margins, display, etc.)

**Responsive Design Configuration:**
The project supports both mobile-first and desktop-first development. Configure in [src/sass/global/\_breakpoints.scss](src/sass/global/_breakpoints.scss):

```scss
$startFrom: sp; // Set to 'sp' for mobile-first or 'pc' for desktop-first
```

Use the `mq()` mixin for responsive styles:

```scss
@include mq(md) {
  /* Tablet and up */
}
@include mq(lg) {
  /* Desktop and up */
}
```

Breakpoint values:

- `sm: 600px`
- `md: 768px`
- `lg: 1024px`
- `xl: 1440px`

**Sass Directory Structure:**
```
src/sass/
│
├─ style.scss                      # Main entry point (imports all modules)
│
├─ global/                         # Global utilities (imported in all files)
│   ├─ _index.scss                 # Re-exports all global modules
│   ├─ _setting.scss               # Variables, CSS custom properties
│   ├─ _function.scss              # Sass functions (r(), vw(), fluidRange())
│   ├─ _mixin.scss                 # Sass mixins (m-text, etc.)
│   └─ _breakpoints.scss           # Responsive breakpoints & mq() mixin
│
├─ foundation/                     # Foundation layer (global base styles)
│   ├─ _reset.scss                 # CSS reset
│   ├─ _base.scss                  # Root font-size, html/body styles
│   └─ _init.scss                  # Initialize styles
│
├─ layout/                         # Layout layer (structural components)
│   ├─ _l-fv.scss                  # First view layout
│   ├─ _l-section.scss             # Section layout
│   ├─ _l-inner.scss               # Inner container (configurable types)
│   ├─ _l-footer.scss              # Footer layout
│   ├─ _l-lower-heading.scss       # Lower page heading layout
│   └─ _l-lower-top.scss           # Lower page top layout
│
└─ object/                         # Object layer (FLOCSS)
    │
    ├─ component/                  # Reusable UI components
    │   ├─ _c-button-normal.scss   # Standard button
    │   ├─ _c-breadcrumb.scss      # Breadcrumb navigation
    │   ├─ _c-card-post.scss       # Post card component
    │   ├─ _c-hamburger.scss       # Hamburger menu icon
    │   ├─ _c-pagination.scss      # Pagination component
    │   └─ _c-section-title.scss   # Section title component
    │
    ├─ project/                    # Page/feature-specific styles
    │   ├─ _p-header.scss          # Site header
    │   ├─ _p-footer.scss          # Site footer
    │   ├─ _p-drawer.scss          # Drawer navigation
    │   ├─ _p-fv.scss              # First view / Hero section
    │   ├─ _p-loading.scss         # Loading animation
    │   ├─ _p-faq.scss             # FAQ section
    │   ├─ _p-form.scss            # Form styles
    │   ├─ _p-section-grid.scss    # Grid section
    │   ├─ _p-section-slide.scss   # Slider section
    │   ├─ _p-section-table.scss   # Table section
    │   ├─ _p-lower-heading.scss   # Lower page heading
    │   ├─ _p-archive.scss         # Archive page
    │   ├─ _p-single.scss          # Single post page
    │   ├─ _p-page.scss            # Standard page
    │   ├─ _p-content.scss         # Content area
    │   ├─ _p-column1.scss         # 1-column layout
    │   ├─ _p-column2.scss         # 2-column layout
    │   ├─ _p-sub-contact.scss     # Contact section
    │   ├─ _p-error.scss           # Error page
    │   └─ _p-xxx.scss             # Template for new project styles
    │
    └─ utility/                    # Single-purpose utility classes
        ├─ _u-sp.scss              # Show on mobile only
        ├─ _u-pc.scss              # Show on desktop only
        └─ _u-keyframe.scss        # Animation keyframes
```

### EJS Template System

When `EJS_MODE=true`, the build system compiles `.ejs` files to HTML:

- **Partials**: Files prefixed with `_` are partials (not compiled to standalone HTML)
- **Component naming**: `_c-*` for components, `_p-*` for project sections
- **Data injection**: JSON files in `src/ejs/pageData/` are automatically loaded and available in templates
- **Usage**: `<%= json.pageData.title %>` or `<%= json.cards[0].title %>`

Each JSON file becomes a namespace (filename without extension).

**EJS Directory Structure:**
```
src/ejs/
│
├─ index.ejs                       # Top page (compiled to HTML)
├─ lowerpage/
│   └─ index.ejs                   # Lower page template
│
├─ common/                         # Shared layout partials
│   ├─ _head.ejs                   # <head> section (meta, title, OGP)
│   ├─ _header.ejs                 # Site header with navigation
│   └─ _footer.ejs                 # Site footer
│
├─ component/                      # Reusable UI components
│   ├─ _c-button-normal.ejs        # Standard button component
│   ├─ _c-card.ejs                 # Card component
│   └─ _c-section-title.ejs        # Section title component
│
├─ project/                        # Page-specific sections
│   ├─ _p-drawer.ejs               # Drawer navigation
│   ├─ _p-faq.ejs                  # FAQ section
│   ├─ _p-fv.ejs                   # First view / Hero section
│   ├─ _p-loading.ejs              # Loading animation
│   ├─ _p-section-grid.ejs         # Grid layout section
│   ├─ _p-section-slide.ejs        # Slider section
│   └─ _p-section-table.ejs        # Table section
│
└─ pageData/                       # JSON data for templates
    ├─ pageData.json               # Page metadata (title, description, OGP)
    ├─ cards.json                  # Card component data
    └─ table.json                  # Table data
```

### WordPress Development

When `WP_MODE=true`:

- Files from `src/wp/` are copied to `distwp/`
- If `WP_LOCAL_MODE=true`, files are also synced to Local by Flywheel theme directory at `~/Local Sites/{SITE_TITLE}/app/public/wp-content/themes/{THEME_NAME}`
- Modular functions are organized in `src/wp/functions-lib/` with descriptive filenames:
  - `f-0base_*`: Core setup and enqueue scripts
  - `f-acf_*`: Advanced Custom Fields customizations
  - `f-cf7_*`: Contact Form 7 customizations
  - `f-custom_post_*`: Custom post type utilities
  - `f-menu_*`: Admin menu customizations
  - `f-scf_*`: Smart Custom Fields utilities

**WordPress Directory Structure:**
```
src/wp/
│
├─ style.css                       # Theme stylesheet (required by WordPress)
├─ functions.php                   # Main functions file (loads modules)
├─ index.php                       # Main template file
├─ header.php                      # Site header
├─ footer.php                      # Site footer
├─ single.php                      # Single post template
├─ page.php                        # Page template
├─ archive.php                     # Archive template
├─ archive-news.php                # Custom post type archive (news)
├─ category.php                    # Category archive template
├─ taxonomy.php                    # Custom taxonomy template
├─ 404.php                         # 404 error page
│
├─ page-*.php                      # Page-specific templates
│   ├─ page-contact.php            # Contact page
│   ├─ page-inquiry.php            # Inquiry form page
│   ├─ page-privacy-policy.php     # Privacy policy
│   └─ page-terms.php              # Terms of service
│
├─ parts/                          # Reusable template parts
│   ├─ c-breadcrumb.php            # Breadcrumb component
│   ├─ c-card-post.php             # Post card component
│   ├─ c-pagination.php            # Pagination component
│   ├─ c-section-title.php         # Section title component
│   ├─ p-drawer.php                # Drawer navigation
│   └─ p-lower-heading.php         # Lower page heading
│
└─ functions-lib/                  # Modular function files
    │
    ├─ f-0base_functions.php       # Core utility functions
    ├─ f-0base_script.php          # Enqueue CSS/JS files
    ├─ f-0base_set_up.php          # Theme setup and features
    │
    ├─ f-acf_add_menu.php          # ACF admin menu customization
    │
    ├─ f-cf7_custom_select.php     # CF7 custom select fields
    ├─ f-cf7_recaptcha.php         # CF7 reCAPTCHA integration
    ├─ f-cf7_reset.php             # CF7 form reset functionality
    ├─ f-cf7_validation.php        # CF7 validation rules
    │
    ├─ f-custom_post_filter.php    # Custom post type filters
    ├─ f-custom_post_list.php      # Custom post list modifications
    ├─ f-custom_post_page.php      # Custom post type pagination
    ├─ f-custom_post_slug.php      # Custom post type slug management
    ├─ f-custom_post_sort.php      # Custom post type sorting
    ├─ f-custom_tax_limit.php      # Custom taxonomy limits
    │
    ├─ f-menu_post.php             # Post menu customizations
    ├─ f-menu_restrict.php         # Menu access restrictions
    ├─ f-add_custom_menu.php       # Add custom admin menus
    │
    ├─ f-breadcrumb-navxt.php      # Breadcrumb NavXT configuration
    ├─ f-js_data.php               # Pass PHP data to JavaScript
    ├─ f-login_logo.php            # Custom login logo
    ├─ f-page_editor.php           # Page editor customizations
    ├─ f-post-list.php             # Post list utilities
    ├─ f-redirect.php              # Redirect rules
    └─ f-template-customize.php    # Template customizations
```

### JavaScript Architecture

Main JavaScript file: [src/assets/js/script.js](src/assets/js/script.js)

Key utilities included:

- **ToggleClass**: Reusable tab/accordion system with data attributes
- **DrawerToggle**: Hamburger menu and drawer navigation system
- **GSAP/ScrollTrigger**: Animation library (pre-registered)
- **SplitText**: Text animation plugin

Breakpoint detection:

```javascript
const breakpoint = 768;
function isSP() {
  return window.innerWidth < breakpoint;
}
```

## Build System Details

### Gulp Tasks (gulpfile.js:336-367)

The Gulp build system is defined in [\_gulp/gulpfile.js](_gulp/gulpfile.js):

- **Default task** (`npx gulp`): Development mode with BrowserSync and file watching
- **build**: Production build with full optimization and directory cleanup
- **build_no_images**: Production build without image processing

### Asset Processing

- **Sass**: Compiled to CSS with autoprefixer, declaration sorting, media query merging. Outputs both expanded and minified versions with source maps.
- **JavaScript**: Transpiled with Babel (ES6+ → ES5), minified with Uglify. Outputs both standard and `.min.js` versions.
- **Images**: Optimized with imagemin (JPEG/PNG/SVG) + automatic WebP conversion. Only changed images are processed.
- **EJS**: Compiled to formatted HTML with automatic blank line removal and beautification.

### BrowserSync Configuration

- **Static mode**: Serves `dist/` directory on `localhost:3000`
- **WordPress mode**: Proxies to Local by Flywheel domain (e.g., `http://template.local/`)

## Development Workflow

1. Configure build mode in [environments/.env.local](environments/.env.local)
2. Run `cd _gulp && npm i` (first time only)
3. Run `npx gulp` to start development server
4. Edit files in `src/` directory only
5. Changes automatically compile and reload browser
6. Run `npx gulp build` before deployment

## Important Notes

- **Never edit** files in `dist/` or `distwp/` - they are auto-generated
- **Never modify** files in `base/` directory (if present)
- Delete `_gulp/node_modules/` before sharing project (reinstall with `npm i`)
- Ensure Sass partials (files starting with `_`) are properly imported
- When using WordPress mode, ensure Local by Flywheel site name matches `SITE_TITLE` in .env
- The `style.scss` file uses Sass module system (`@use`) with glob patterns for automatic imports

## Node.js Version Management

This project uses **Volta** for Node.js version pinning (defined in [\_gulp/package.json](_gulp/package.json)):

- Node.js: `20.18.1`
- npm: `11.0.0`

If Volta is installed, it will automatically use the correct versions.

## Coding Standards

### Core Principles

**1. Consistency**
- Maintain uniform coding style across all files and languages
- Follow established naming patterns and file structures
- Use consistent indentation (2 spaces) throughout the project

**2. Maintainability**
- Write self-documenting code with clear naming
- Keep components modular and single-purpose
- Minimize nesting depth (max 2-3 levels)
- Avoid high specificity in CSS selectors

**3. Security**
- Escape all dynamic output (WordPress: `esc_url()`, `esc_html()`, `esc_attr()`)
- Use EJS escaping syntax `<%= %>` by default for user input
- Never output raw user data without sanitization

**4. Accessibility**
- Use semantic HTML5 elements (`<header>`, `<nav>`, `<main>`, `<section>`)
- Include ARIA attributes where necessary (`aria-label`, `aria-expanded`)
- Maintain proper heading hierarchy
- Provide alternative text for images

### Naming Conventions (BEM + FLOCSS)

**BEM Structure:**
- **Block**: `prefix-block`
- **Element**: `block__element`
- **Modifier**: `block--modifier`
- **State**: `.is-state`

**FLOCSS Prefixes:**
- `c-` = Component (reusable UI elements)
- `p-` = Project (page/feature-specific)
- `l-` = Layout (structural containers)
- `u-` = Utility (single-purpose classes)

**File Naming:**
- EJS partials: `_prefix-name.ejs` (underscore = not compiled standalone)
- PHP template parts: `prefix-name.php`
- SCSS files: `_prefix-name.scss`
- PHP functions: `f-category_description.php`

**Example:**
```
_c-button.ejs  →  c-button.php  →  _c-button.scss
(Component button across all languages)
```

### Language-Specific Guidelines

#### HTML/EJS

**Formatting:**
- 2-space indentation
- Attribute order: `class` → `data-*` → others

**EJS Syntax:**
- `<%= value %>` — Output with escaping (default)
- `<%- include() %>` — Output without escaping (for partials)
- `<% code %>` — Execute code without output
- `<%# comment %>` — Non-rendered comments

**Data Attributes:**
- Use `data-*` for JavaScript hooks instead of classes
- Example: `data-drawer`, `data-hamburger`

**Template Includes:**
- Document parameters with EJS comments: `<%# Parameters: title, link %>`
- Pass data as object literals

#### PHP (WordPress)

**Formatting:**
- 2-space indentation
- Always use `<?php ?>` (long form, never short tags)
- Omit closing tags in PHP-only files

**Function Naming:**
- Theme functions: `my_` prefix + snake_case
- Include context in names: `custom_select_values_contact`

**Template Syntax:**
- Use alternative syntax for HTML blocks: `if():` / `endif;`
- Declare variables at top of template

**WordPress Hooks:**
- Register hooks before function definitions
- Use descriptive function names that indicate purpose

**Security:**
- Always escape output with appropriate functions
- URLs: `esc_url()`
- HTML text: `esc_html()`
- Attributes: `esc_attr()`

#### SCSS/Sass

**Formatting:**
- 2-space indentation
- Maximum 2-3 nesting levels
- Single blank lines between sections

**Property Ordering (by category):**
1. Position/Layout
2. Sizing
3. Spacing
4. Border/Background
5. Typography
6. Animation

**Module System:**
- Use `@use` instead of `@import`
- Import `@use 'global' as *;` at top of files
- Use `@forward` in `_index.scss` for module exports

**Responsive Design:**
- Always use `mq()` mixin with breakpoint names (`sm`, `md`, `lg`, `xl`)
- Never write raw media queries
- Mobile-first approach by default

**Sizing:**
- Use `r()` function for all sizing (never raw `px` values except 1px borders)
- Use `fluidRange()` for responsive sizing between breakpoints

**Variables:**
- Define CSS custom properties in `:root` within `_setting.scss`
- Naming: `--category-name` (e.g., `--color-white`, `--zi-header`)
- Use predefined z-index scale: `--zi-*`

**Hover States:**
- Wrap in `@media (any-hover: hover)` to prevent touch device issues

**Specificity:**
- Prefer single class selectors
- Never use ID selectors in styles
- Avoid `!important` except for third-party overrides
- Use state classes: `.block.is-active`

### Comment Conventions

**Markers (all languages):**
- `// !` — Important/critical information
- `// *` — Regular notes
- `// ?` — Questions/clarifications

**Documentation:**
- EJS: `<%# component parameters %>` for template documentation
- PHP: `/** @link ... */` for function documentation
- SCSS: Multi-line `/* */` for section headers

### Cross-Language Consistency

| Aspect | Convention |
|--------|-----------|
| Indentation | 2 spaces (all files) |
| Component files | `_c-button.ejs`, `c-button.php`, `_c-button.scss` |
| Project files | `_p-header.ejs`, `p-header.php`, `_p-header.scss` |
| BEM naming | `block__element--modifier` (all languages) |
| State classes | `.is-active`, `.is-scrolled` (all languages) |
| JS hooks | `data-*` attributes (HTML/EJS/PHP) |

This ensures easy navigation between related files and a maintainable codebase across all languages.

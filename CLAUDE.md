# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Frontend development template supporting multiple build targets:

- **Static HTML** (default): Traditional HTML/CSS/JavaScript
- **EJS Mode**: Template-based HTML generation with JSON data
- **WordPress Mode**: PHP theme development with Local by Flywheel integration

Stack: Gulp, Sass (Dart Sass), Babel, BrowserSync

**Requirements**: Node.js 20.18.1+, npm 11.0.0+ (managed via [Volta](https://volta.sh/))

## Common Commands

```bash
npm i                         # Install dependencies
npx gulp                      # Development server with file watching
npx gulp build                # Production build (WebP images only)
npx gulp build-with-original  # Production build (original + WebP images)
npx gulp build-without-images # Build without image processing
npx gulp clean                # Delete build output (dist/distwp)

npm run lint                  # Lint SCSS + JS
npm run lint:fix              # Auto-fix lint issues
npm run lint:html             # Lint compiled HTML (run after build)
npm run lint:php              # Lint PHP (requires Composer)
npm run format                # Format SCSS + JS with Prettier
```

## Build Configuration

Setup: `cp environments/.env.sample environments/.env.local`

Edit `environments/.env.local`:

| Variable        | Values         | Description                     |
| --------------- | -------------- | ------------------------------- |
| `EJS_MODE`      | `true`/`false` | Enable EJS template compilation |
| `WP_MODE`       | `true`/`false` | Enable WordPress theme output   |
| `WP_LOCAL_MODE` | `true`/`false` | Sync to Local by Flywheel       |
| `THEME_NAME`    | string         | WordPress theme folder name     |

## Architecture

### Directory Structure

- `src/` — **Source files (edit here only)**
  - `ejs/` — EJS templates (partials prefixed with `_`)
  - `wp/` — WordPress theme files
  - `sass/` — FLOCSS-structured stylesheets
  - `assets/` — JS, images, static CSS
- `dist/` — Static/EJS build output (**auto-generated, never edit**)
- `distwp/` — WordPress build output (**auto-generated, never edit**)

### FLOCSS Sass Structure

```
sass/
├── foundation/           # Reset, base styles
├── layout/_l-*.scss      # Structural (header, footer, inner)
├── global/               # Variables, mixins, functions
└── object/
    ├── component/_c-*.scss  # Reusable UI
    ├── project/_p-*.scss    # Page-specific
    └── utility/_u-*.scss    # Single-purpose helpers
```

## Coding Standards

### Naming (BEM + FLOCSS)

| Prefix | Usage                 | Example                |
| ------ | --------------------- | ---------------------- |
| `c-`   | Reusable component    | `c-button`, `c-card`   |
| `p-`   | Page/feature-specific | `p-header`, `p-fv`     |
| `l-`   | Layout structure      | `l-inner`, `l-section` |
| `u-`   | Utility               | `u-sp`, `u-pc`         |

State classes: `is-*`, `has-*`

### SCSS Rules

- **First line of partials**: `@use 'global' as *;`
- **Units**: Use `r()` function for rem conversion — raw px values forbidden
- **Responsive**: Mobile-first with `@include mq('md')` — raw media queries forbidden
- **Breakpoints**: `sm: 600px`, `md: 768px`, `lg: 1024px`, `xl: 1440px`
- **Nesting**: Max 3 levels; BEM nesting only for `img`, pseudo-elements, media queries
- **Modifiers**: Use multi-class `.classname.classname--modifier`
- **Hover**: Wrap in `@media (any-hover: hover)` for touch device exclusion

### HTML/EJS/PHP Rules

- **Comments**: Use EJS (`<%# %>`) or PHP (`<?php // ?>`) comments, not HTML
- **JS hooks**: Use `data-*` attributes, not classes
- **Images**: Required attrs: `width`, `height`, `alt`, `loading` (eager for first section, lazy otherwise)
- **Semantics**: Proper heading hierarchy; h1 = logo (top) or page title (subpages)

### Section Template Pattern

```html
<div class="p-(name)">
	<div class="p-(name)__inner l-inner">
		<div class="p-(name)__heading">
			<h2 class="c-section-title">Title</h2>
		</div>
		<div class="p-(name)__content">
			<!-- content -->
		</div>
	</div>
</div>
```

## Key Functions & Mixins

| Function     | Usage                 | Example                          |
| ------------ | --------------------- | -------------------------------- |
| `r()`        | px to rem             | `r(16)`                          |
| `mq()`       | Responsive breakpoint | `@include mq('md')`              |
| `vw()`       | Viewport width calc   | `vw(100, $width-sp)`             |
| `vwLinear()` | Linear interpolation  | `vwLinear(768, 768, 1080, 1366)` |

## WordPress-Specific

- Load theme URI: `<?php global $theme_uri; ?>`
- Escape functions: `esc_url()`, `esc_html()`, `esc_attr()`, `wp_kses_post()`
- Template parts: `<?php get_template_part('/parts/c-card-name', null, ['post_id' => $id]); ?>`
- Modular functions in `functions-lib/` with `f-` prefix

## Figma MCP Integration

When a Figma URL is provided, the workflow auto-executes:

1. Fetch design via MCP (`get_design_context`, `get_screenshot`)
2. `section-analyzer` parses structure → generates manifest in `.claude/progress/`
3. `section-orchestrator` generates code per section
4. Output: HTML, SCSS, JS following project conventions

Agent configurations: `.claude/agents/`

## Detailed Rules

- **HTML/EJS/PHP**: `.claude/rules/RULES_HTML.md`
- **SCSS**: `.claude/rules/RULES_SCSS.md`

## Deploy Commands (WordPress only)

```bash
npx gulp ssh_test             # Test SSH connection
npx gulp deploy               # Build + deploy to production
npx gulp deploy-with-original # Build (with original images) + deploy
npx gulp deploy_only          # Deploy without rebuild
npx gulp watch-deploy         # Auto-deploy on file save (⚠️ production)
```

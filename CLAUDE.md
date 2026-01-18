# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a flexible frontend development template that supports multiple build targets:

- **Static HTML** (default): Traditional HTML/CSS/JavaScript
- **EJS Mode**: Template-based HTML generation with JSON data
- **WordPress Mode**: PHP theme development with optional Local by Flywheel integration

The project uses Gulp for task automation, Sass for styling, and Babel for JavaScript transpilation.

## Build Configuration

All build settings are managed via environment variables in `environments/.env.local`:

| Variable | Description |
|----------|-------------|
| `EJS_MODE` | `true` for EJS template compilation, `false` for static HTML |
| `WP_MODE` | `true` for WordPress theme development |
| `WP_LOCAL_MODE` | `true` to sync to Local by Flywheel theme directory |
| `SITE_TITLE` | WordPress site name (for Local by Flywheel path) |
| `THEME_NAME` | WordPress theme folder name |
| `LOCAL_SITE_DOMAIN` | Local by Flywheel domain (e.g., `template.local`) |

## Common Commands

```bash
# Initial Setup
npm i

# Development (starts BrowserSync with file watching)
npx gulp

# Production Build
npx gulp build

# Production Build (skip image optimization)
npx gulp build_no_images

# Linting
npm run lint              # Run all linters (SCSS + JS)
npm run lint:fix          # Auto-fix all issues
npm run lint:html         # Check compiled HTML (after build)
npm run lint:php          # Check PHP files (requires Composer)

# Formatting
npm run format            # Format all files (SCSS + JS)
npm run format:check      # Check formatting without changes
```

## Project Architecture

### Directory Structure

```
src/                    # Source files (ONLY edit here)
├── ejs/                # EJS templates (when EJS_MODE=true)
├── wp/                 # WordPress theme (when WP_MODE=true)
├── sass/               # Sass stylesheets (FLOCSS methodology)
└── assets/             # Static assets (js, images, css)

dist/                   # Output for static/EJS build (DO NOT EDIT)
distwp/                 # Output for WordPress build (DO NOT EDIT)
```

### Sass Architecture (FLOCSS)

- **Foundation** (`foundation/`): Reset and base styles
- **Layout** (`layout/_l-*.scss`): Structural components (header, footer, inner)
- **Object/Component** (`object/component/_c-*.scss`): Reusable UI components
- **Object/Project** (`object/project/_p-*.scss`): Page-specific styles
- **Object/Utility** (`object/utility/_u-*.scss`): Single-purpose classes

**Key functions and mixins:**
- `r()`: Convert px to rem (e.g., `r(16)`)
- `mq()`: Responsive breakpoints (e.g., `@include mq('md')`)
- Breakpoints: `sm: 600px`, `md: 768px`, `lg: 1024px`, `xl: 1440px`

### EJS Template System

When `EJS_MODE=true`:
- Files prefixed with `_` are partials (not compiled standalone)
- JSON files in `src/ejs/pageData/` are auto-loaded as `json.{filename}`
- Component naming: `_c-*` for components, `_p-*` for project sections

### WordPress Development

When `WP_MODE=true`:
- Files from `src/wp/` are copied to `distwp/`
- Modular functions in `functions-lib/` with `f-` prefix
- Template parts in `parts/` directory

## Coding Standards

### Core Principles

- **Indentation**: Tabs (all files)
- **Naming**: BEM + FLOCSS (`block__element--modifier`, `.is-state`)
- **JS Hooks**: Use `data-*` attributes instead of classes

### FLOCSS Prefixes

| Prefix | Usage | Example |
|--------|-------|---------|
| `c-` | Reusable UI component | `c-button`, `c-card` |
| `p-` | Page/feature-specific | `p-header`, `p-fv` |
| `l-` | Layout structure | `l-inner`, `l-section` |
| `u-` | Utility class | `u-sp`, `u-pc` |

### Detailed Rules

For comprehensive coding rules, see:
- **HTML/EJS/PHP**: `.claude/rules/RULES_HTML.md`
- **SCSS**: `.claude/rules/RULES_SCSS.md`

## Figma MCP Integration

This project supports automatic code generation from Figma designs via MCP (Model Context Protocol).

### Automatic Workflow Trigger

When a Figma URL is provided, the following workflow executes automatically:

1. **Design fetch**: Retrieve design data via MCP
2. **Section analysis**: `section-analyzer` parses design structure
3. **Code generation**: `section-orchestrator` generates code per section
4. **Output**: HTML, SCSS, and JS files following project conventions

### Available Agents

| Agent | Purpose |
|-------|---------|
| `section-analyzer` | Analyze Figma design and generate manifest |
| `section-orchestrator` | Coordinate section-by-section code generation |
| `html-structure` | Generate semantic HTML5 with BEM classes |
| `sass-flocss` | Generate FLOCSS-compliant SCSS |
| `js-component` | Generate JavaScript for UI interactions |
| `code-reviewer` | Quality check and fix suggestions |

See `.claude/CLAUDE.md` for detailed MCP workflow configuration.

## Development Workflow

1. Configure build mode in `environments/.env.local`
2. Run `npm i` (first time only)
3. Run `npx gulp` to start development server
4. Edit files in `src/` directory only
5. Changes automatically compile and reload browser
6. Run `npx gulp build` before deployment

## Important Notes

- **Never edit** files in `dist/` or `distwp/` - they are auto-generated
- Use Volta for Node.js version management (pinned in package.json)
- Sass uses `@use` module system with glob patterns for automatic imports

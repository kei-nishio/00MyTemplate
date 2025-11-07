---
name: sass-flocss
description: FLOCSS準拠Sass生成。r()関数、mq()ミックスイン、パフォーマンス配慮。
tools: Read, Write, Edit, Grep
---

# 役割

Figma デザインから、CLAUDE.md の規約に準拠した Sass を生成。

## 必須ルール

### 1. ファイル配置

```
scss/
├── foundation/
│   ├── _base.scss           # Base styles
│   ├── _init.scss           # Variables, colors, breakpoints
│   └── _reset.scss          # CSS reset
├── global/
│   ├── _breakpoints.scss    # Media query breakpoints
│   ├── _function.scss       # Sass functions
│   ├── _index.scss          # Import entry
│   ├── _mixin.scss          # Common mixins
│   └── _setting.scss        # Base reset & typography
├── layout/
│   ├── _l-xxx.scss          # Layout
│   └── _l-yyy.scss          # Layout
├── object/
│   ├── component/
│   │   ├── _c-button.scss   # Button component
│   │   └── _c-card.scss     # Card component
│   ├── project/
│   │   ├── _p-fv.scss       # Fv section
│   │   └── _p-xxx.scss      # Section project
│   └── utility/
│       ├── _u-keyframe.scss # Keyframe utilities
│       ├── _u-pc.scss       # Display only pc utilities
│       └── _u-sp.scss       # Display only sp utilities
└── style.scss               # Main import file
```

### 2. 先頭行

```scss
@use 'global' as *;
```

### 3. 単位

- `r()`関数必須（例: `r(16)`, `r(24)`）
- 生の px 値禁止（1px border は例外）

### 4. レスポンシブ

```scss
// Mobile-first
.p-section {
  padding: r(40);

  @include mq('md') {
    padding: r(80);
  }
}
```

### 5. CSS 変数

- 色: `var(--color-*)`
- z-index: `var(--zi-*)`
- フォント: `var(--font-*)`

### 6. BEM 記法

- **Layout**: `.l-header`, `.l-footer`, `.l-main`
- **Component**: `.c-button`, `.c-card`, `.c-form`
- **Project**: `.p-hero`, `.p-features`, `.p-pricing`
- **Utility**: `.u-mt-10`, `.u-text-center`, `.u-hidden`

```scss
// Block
.c-card {
}

// Element
.c-card__title {
}
.c-card__body {
}
.c-card__footer {
}

// Modifier
.c-card--featured {
}
```

### 7. hover

```scss
@media screen and (any-hover: hover) {
  .c-button:hover {
    opacity: 0.9;
  }
}
```

### 8. プロパティ順序

1. position/layout
2. sizing
3. spacing
4. border/background
5. typography
6. animation

### 9. パフォーマンス

- `will-change`: アニメーション要素のみ
- `transform`, `opacity` でアニメーション（reflow 回避）

### 10. インデント

2 スペース

## 禁止

- 生の px, rem, em
- !important（特別な理由なく）
- ID セレクタ
- 深いネスト（3 階層まで）

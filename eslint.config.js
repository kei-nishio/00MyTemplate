import js from '@eslint/js';
import globals from 'globals';
import eslintConfigPrettier from 'eslint-config-prettier';

export default [
  js.configs.recommended,
  eslintConfigPrettier,
  {
    files: ['src/**/*.js'],
    languageOptions: {
      ecmaVersion: 2022,
      sourceType: 'script',
      globals: {
        ...globals.browser,
        // GSAP
        gsap: 'readonly',
        ScrollTrigger: 'readonly',
        SplitText: 'readonly',
        // Swiper
        Swiper: 'readonly',
        // Lottie
        lottie: 'readonly',
        // ScrollHint
        ScrollHint: 'readonly',
        // WordPress / Theme
        pageUrl: 'readonly',
        wp: 'readonly',
        jQuery: 'readonly',
        $: 'readonly',
      },
    },
    rules: {
      indent: ['error', 'tab', { SwitchCase: 1 }],
      'linebreak-style': ['error', 'unix'],
      quotes: ['error', 'single', { avoidEscape: true }],
      semi: ['error', 'always'],
      'no-unused-vars': [
        'warn',
        {
          argsIgnorePattern: '^_',
          varsIgnorePattern: '^_',
        },
      ],
      'no-console': 'off',
      'no-constant-condition': 'off',
      'prefer-const': 'warn',
      'no-var': 'error',
      eqeqeq: ['error', 'always', { null: 'ignore' }],
      curly: ['error', 'multi-line'],
      'no-multiple-empty-lines': ['error', { max: 2, maxEOF: 1 }],
      'comma-dangle': ['error', 'only-multiline'],
      'no-trailing-spaces': 'error',
      'object-curly-spacing': ['error', 'always'],
      'array-bracket-spacing': ['error', 'never'],
      'space-before-blocks': 'error',
      'keyword-spacing': ['error', { before: true, after: true }],
      'space-infix-ops': 'error',
      'brace-style': ['error', '1tbs', { allowSingleLine: true }],
    },
  },
  {
    ignores: ['dist/**', 'distwp/**', '**/*.min.js'],
  },
];

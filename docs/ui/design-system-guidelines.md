# Assault Online Design System (v1)

## Tokens
- Colors: use CSS variables in `:root` from `wp-content/themes/ao_theme/css/design-system.css`.
- Spacing scale: `--ao-space-1..6` (4px..32px).
- Radius: `--ao-radius-sm/md/lg`.
- Shadow: `--ao-shadow-1` for elevated cards/panels.
- Motion: `--ao-ease` for interaction transitions.

## Component usage
- Primary action: `.mainSubmit` or `.profileButton`.
- Secondary action: `.cancelButton`.
- Panel/card containers: `.contentBox`, `.statCol-3`, `.unitRow`, `.loginfield`.
- Structured list headers/rows: `.headerRow` + `.userRow`.
- Inputs: native `input/select/textarea` now inherit tokenized styles.

## Utilities replacing inline styles
- No padding: `.u-no-padding`
- Underlined links: `.u-link-underline`
- Hidden blocks: `.u-hidden`
- Word wrapping long tokens: `.u-word-break`
- Standardized green tier backgrounds: `.u-bg-1..u-bg-4`

## Accessibility
- Focus visibility is enforced with `:focus-visible` ring.
- Contrast improved via darker surfaces + light text.
- Minimum interaction size increased with consistent button/input padding.

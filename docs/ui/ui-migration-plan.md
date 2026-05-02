# UI Migration Plan

## Before audit
- UI styling was fragmented across theme CSS, page-level `<style>` tags, and many inline `style="..."` attributes.
- Components (buttons, rows, headers, inputs) had inconsistent color, radius, spacing, and hover/focus behavior.
- Repeated hardcoded RGBA values were used across user/clan/profile/attack pages.

## After (this migration phase)
- Added a global tokenized design layer (`design-system.css`) and loaded it globally.
- Standardized core interaction components: buttons, input controls, headers, row panels, cards.
- Introduced utility classes to replace common inline style cases.
- Added design system documentation and usage rules.

## Next phase backlog
1. Replace remaining inline styles in attack/toplist/clan pages with semantic classes.
2. Move page-level `<style>` blocks from templates into scoped CSS modules/files.
3. Consolidate repeated row color math into reusable class names/server-side helpers.
4. Add visual regression snapshots for major templates (dashboard/profile/attack/toplists).
5. Add QA sweep for responsive breakpoints and keyboard-only navigation.

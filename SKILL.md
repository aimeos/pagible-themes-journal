---
name: journal
description: Editorial magazine theme with a centered masthead, serif headlines, structured story grids, and restrained ochre accents.
license: MIT
metadata:
  author: Aimeos
---

# Journal Theme Design System

## Mission

Build credible editorial pages that make dense reporting easy to scan without turning the interface into a dashboard.

## Brand

Journal is sober, structured, and image-led. The page surround is warm gray (`#F1EFEC`), story modules are white, text is charcoal (`#1F1E1C`), and ochre (`#9A7112`) marks sections, actions, and active states. It uses Pico CSS and the existing markup in `theme/views/`.

## Style Foundations

- Typography: Georgia or the nearest system serif for masthead and headlines; Arial/Helvetica/system sans-serif for navigation, metadata, controls, and body UI
- Headline rhythm: compact line height, strong weight, no centered marketing copy except the masthead
- Surfaces: white editorial modules separated by warm-gray gutters and thin rules
- Geometry: `--pico-border-radius: 0`; cards, dialogs, fields, and buttons stay square
- Accent: ochre is reserved for categories, primary actions, focus, and active navigation
- Images: editorial crops use `16 / 10`, article leads use `16 / 9`, and split features use `4 / 3`
- Maximum width: 80rem page frame, 76rem content, 64rem article components, 48rem prose

## Component Rules

- Header: center the masthead above a separate category rail on desktop; collapse to one accessible menu below 992px
- Hero: use a five-to-seven split with text first and one strong image second; never layer decorative gradients over the media
- Story grids: give one lead story more visual weight and keep supporting stories compact
- Cards: use a top rule instead of rounded containers or shadows; change the rule to ochre on hover
- Articles: keep introductions and body copy on a narrow measure, while cover images may use the full content width
- Buttons: uppercase sans-serif labels, square borders, ochre primary action, white secondary action
- Footer: use sectioned link columns, a strong top rule, and the same masthead identity as the header

## Accessibility

- Keep text and controls at WCAG 2.2 AA contrast or better
- Use a visible 3px ochre focus outline with a 3px offset
- Preserve semantic headings, navigation labels, dialogs, details, and skip links
- Do not use color alone to identify an active item; combine color with borders, text, or position
- Keep touch targets at least 2.25rem high and allow long navigation labels to wrap on mobile
- Respect `prefers-reduced-motion` for image scaling and scrolling

## Content and Tone

- Write precise, direct headlines that state the tension or consequence
- Prefer concrete nouns, quantities, and decisions over slogans
- Keep summaries to two or three sentences and say why the story matters
- Use descriptive link text such as `Analyse zu Stromnetzen lesen`, not `Mehr erfahren`

## Prohibited Patterns

- No pill buttons, rounded cards, glass effects, heavy shadows, or decorative textures
- No invented utility classes or component markup; style the structures from `theme/views/`
- No oversized marketing gradients or animation that competes with reading
- No copied publication branding, logos, article text, or proprietary photography

## QA Checklist

- Masthead is centered and the category rail remains usable at 320px and 1280px
- Keyboard focus is visible on navigation, dropdowns, search, forms, and article links
- Lead and supporting story hierarchy is clear without relying on image content
- All card and article images retain intentional aspect ratios without distortion
- Long German labels wrap without overlapping search or subscription actions
- Reduced-motion mode removes nonessential image scaling
- JSON schema, PHP syntax, demo seeding, representative route rendering, PHPUnit, and PHPStan pass

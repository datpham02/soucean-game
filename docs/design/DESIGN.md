---
version: alpha
name: Soucean Game
description: >-
  Calm, neutral design system for the Soucean Game website. The site chrome stays
  quiet so the games are the colourful part. Light and dark themes, Vietnamese
  first, WCAG 2.1 AA with every ratio measured.
colors:
  # --- Light theme (default) ---
  bg: "#F3F3F0"
  surface: "#FAFAF8"
  sunken: "#E8E8E3"
  border: "#D5D5CE"
  border-strong: "#82827A"
  text: "#1D211F"
  text-muted: "#555B57"
  primary: "#1F6B5C"
  primary-hover: "#175347"
  on-primary: "#FBFBF9"
  secondary: "#87552A"
  success: "#2C6B3F"
  error: "#A32F28"
  warning: "#7A5410"
  focus: "#1A5FB4"
  # --- Dark theme ---
  bg-dark: "#181B1C"
  surface-dark: "#212527"
  sunken-dark: "#111314"
  border-dark: "#343A3C"
  border-strong-dark: "#6B7376"
  text-dark: "#E7E9E7"
  text-muted-dark: "#A7AEAA"
  primary-dark: "#5FC9B4"
  primary-hover-dark: "#7FD7C5"
  on-primary-dark: "#0C241F"
  secondary-dark: "#D9A071"
  success-dark: "#74C98A"
  error-dark: "#F09087"
  warning-dark: "#DCA94A"
  focus-dark: "#8CBEF5"
typography:
  # Sizes below are the MOBILE (default) step of the scale, matching the
  # mobile-first defaults in tokens.css. Desktop sizes are in the table in
  # "Typography" and are applied at the 768px switch.
  display:
    fontFamily: Soucean Sans
    fontSize: 32px
    fontWeight: 600
    lineHeight: 1.2
    letterSpacing: -0.01em
  headline-lg:
    fontFamily: Soucean Sans
    fontSize: 26px
    fontWeight: 600
    lineHeight: 1.3
    letterSpacing: -0.01em
  headline-md:
    fontFamily: Soucean Sans
    fontSize: 21px
    fontWeight: 600
    lineHeight: 1.3
    letterSpacing: 0em
  headline-sm:
    fontFamily: Soucean Sans
    fontSize: 18px
    fontWeight: 600
    lineHeight: 1.35
    letterSpacing: 0em
  body-lg:
    fontFamily: Soucean Sans
    fontSize: 17px
    fontWeight: 400
    lineHeight: 1.6
    letterSpacing: 0em
  body-md:
    fontFamily: Soucean Sans
    fontSize: 16px
    fontWeight: 400
    lineHeight: 1.6
    letterSpacing: 0em
  body-sm:
    fontFamily: Soucean Sans
    fontSize: 14px
    fontWeight: 400
    lineHeight: 1.55
    letterSpacing: 0em
  label-md:
    fontFamily: Soucean Sans
    fontSize: 14px
    fontWeight: 500
    lineHeight: 1.4
    letterSpacing: 0.01em
  label-sm:
    fontFamily: Soucean Sans
    fontSize: 12px
    fontWeight: 500
    lineHeight: 1.4
    letterSpacing: 0.01em
  caption:
    fontFamily: Soucean Sans
    fontSize: 12px
    fontWeight: 400
    lineHeight: 1.4
    letterSpacing: 0.01em
spacing:
  none: 0px
  xs: 4px
  sm: 8px
  md: 12px
  lg: 16px
  xl: 20px
  ad-clearance: 24px
  2xl: 32px
  3xl: 40px
  4xl: 48px
  5xl: 64px
  6xl: 80px
rounded:
  none: 0px
  sm: 6px
  md: 10px
  lg: 14px
  xl: 20px
  full: 9999px
components:
  page:
    backgroundColor: "{colors.bg}"
    textColor: "{colors.text}"
    typography: "{typography.body-md}"
  page-dark:
    backgroundColor: "{colors.bg-dark}"
    textColor: "{colors.text-dark}"
  card:
    backgroundColor: "{colors.surface}"
    textColor: "{colors.text}"
    borderColor: "{colors.border}"
    rounded: "{rounded.md}"
    padding: "{spacing.lg}"
  card-dark:
    backgroundColor: "{colors.surface-dark}"
    textColor: "{colors.text-dark}"
    borderColor: "{colors.border-dark}"
  well:
    backgroundColor: "{colors.sunken}"
    textColor: "{colors.text}"
    rounded: "{rounded.md}"
  well-dark:
    backgroundColor: "{colors.sunken-dark}"
    textColor: "{colors.text-dark}"
  caption-text:
    backgroundColor: "{colors.surface}"
    textColor: "{colors.text-muted}"
    typography: "{typography.caption}"
  caption-text-dark:
    backgroundColor: "{colors.surface-dark}"
    textColor: "{colors.text-muted-dark}"
  button-primary:
    backgroundColor: "{colors.primary}"
    textColor: "{colors.on-primary}"
    typography: "{typography.label-md}"
    rounded: "{rounded.md}"
    padding: "{spacing.md}"
    height: 44px
  button-primary-hover:
    backgroundColor: "{colors.primary-hover}"
    textColor: "{colors.on-primary}"
  button-primary-dark:
    backgroundColor: "{colors.primary-dark}"
    textColor: "{colors.on-primary-dark}"
  button-primary-dark-hover:
    backgroundColor: "{colors.primary-hover-dark}"
    textColor: "{colors.on-primary-dark}"
  button-secondary:
    backgroundColor: "{colors.surface}"
    textColor: "{colors.primary}"
    borderColor: "{colors.border-strong}"
    rounded: "{rounded.md}"
    height: 44px
  button-secondary-dark:
    backgroundColor: "{colors.surface-dark}"
    textColor: "{colors.primary-dark}"
    borderColor: "{colors.border-strong-dark}"
  link:
    backgroundColor: "{colors.bg}"
    textColor: "{colors.primary}"
  link-dark:
    backgroundColor: "{colors.bg-dark}"
    textColor: "{colors.primary-dark}"
  badge:
    backgroundColor: "{colors.surface}"
    textColor: "{colors.secondary}"
    typography: "{typography.label-sm}"
    rounded: "{rounded.full}"
  badge-dark:
    backgroundColor: "{colors.surface-dark}"
    textColor: "{colors.secondary-dark}"
  status-success:
    backgroundColor: "{colors.bg}"
    textColor: "{colors.success}"
  status-success-dark:
    backgroundColor: "{colors.bg-dark}"
    textColor: "{colors.success-dark}"
  status-error:
    backgroundColor: "{colors.surface}"
    textColor: "{colors.error}"
  status-error-dark:
    backgroundColor: "{colors.surface-dark}"
    textColor: "{colors.error-dark}"
  status-warning:
    backgroundColor: "{colors.bg}"
    textColor: "{colors.warning}"
  status-warning-dark:
    backgroundColor: "{colors.bg-dark}"
    textColor: "{colors.warning-dark}"
  focus-ring:
    backgroundColor: "{colors.bg}"
    textColor: "{colors.focus}"
  focus-ring-dark:
    backgroundColor: "{colors.bg-dark}"
    textColor: "{colors.focus-dark}"
  ad-slot:
    backgroundColor: "{colors.sunken}"
    textColor: "{colors.text-muted}"
    rounded: "{rounded.sm}"
    padding: "{spacing.ad-clearance}"
---

# Soucean Game — Design System

This is the single source of truth for the look of the Soucean Game website. It
is the foundation Task of milestone M1: every later M1 prototype imports
[`prototypes/assets/tokens.css`](prototypes/assets/tokens.css) and
[`prototypes/assets/base.css`](prototypes/assets/base.css) from here, and no
later Task redefines a colour, a type step, a spacing step or a breakpoint.

**Scope.** This document fixes the foundations: colour, typography, spacing,
shape, elevation, motion, the M1 breakpoints, and a named component inventory.
The component *designs* are drawn in M1-04; the inventory below only names them
and fixes their tokens.

**Amendments.** This document may only be changed by a recorded Board decision.
There is one, logged in "Layout & Spacing":

| Date | Amendment | Decided by | Raised from |
| --- | --- | --- | --- |
| 2026-11-04 | Added a second switch, **`wide` = 1024px**, for the play-page side rail | Board | GAM-23 (M1-02) §4.4 |

---

## Overview

Soucean Game is a website with many games that people come to play in order to
relax. The chrome around a game has one job: get out of the way.

**Personality.** Calm, plain, friendly, unhurried. A quiet reading room with a
shelf of games in it — not an arcade, not a casino, not a children's toy.

**The governing rule: the games are the colourful part.** Every game thumbnail
and every game frame is loud, bright and different, because games are. The site
around them is therefore deliberately quiet — soft neutral surfaces, one
restrained accent, no gradients, no saturated full-page colour, no neon. If a
page looks slightly plain with the thumbnails removed, that is correct. It stops
the chrome competing with the games, and it stops the ads competing with
either.

**Audience.** Casual players of all ages, Vietnamese first, on mobile and
desktop alike. Mobile is the default; desktop is the enhancement.

**Emotional target.** Restful, legible, predictable. Nothing blinks, nothing
pulses, nothing demands attention that has not earned it.

**Brand.** There is no logo yet. The brand mark is a plain text wordmark,
"Soucean Game", set in the product typeface at `headline-md`, weight 600,
in `text`. Do not invent a logo, a mascot or a custom lettering treatment in
M1 — that is a Board decision, not a Task decision.

**Language.** Vietnamese first, English later. Vietnamese sets longer than
English for the same content and stacks diacritics above and below the x-height,
so this system buys vertical room (generous line heights) and horizontal room
(no tight letter-spacing) everywhere. Never design a layout that only works at
one string length.

---

## Colors

The palette is a soft, slightly warm neutral base with **one** restrained accent
and a small set of meaning colours. Nothing in it is pure black or pure white:
`#F3F3F0` and `#181B1C` are the extremes, which takes the glare off long
sessions in both themes.

- **Primary — "Still Teal" (`#1F6B5C` light, `#5FC9B4` dark):** the single
  accent. A deep, desaturated blue-green used for the primary action, links and
  the current state in navigation. Teal sits opposite the warm neutral base on
  the colour wheel, so it reads as deliberate and distinct without being bright;
  it is also uncommon in game thumbnail art, so the chrome does not accidentally
  echo whichever game is on screen.
- **Secondary — "Clay" (`#87552A` light, `#D9A071` dark):** the one permitted
  second accent, for small non-interactive marks only — a "new" tag, a category
  chip, a week badge. Clay is the warm neutral base pushed to its own saturation,
  which keeps it harmonious instead of introducing a third colour family.
  **Never use Clay for an action**; only Primary is actionable.
- **Neutrals — "Paper" (`#F3F3F0` → `#FAFAF8` → `#E8E8E3`) and "Slate Night"
  (`#181B1C` → `#212527` → `#111314`):** the page, the raised surface and the
  sunken well. Both ramps are very slightly warm/green-grey rather than pure
  grey, so they sit comfortably beside the teal.
- **Text (`#1D211F` light, `#E7E9E7` dark):** a soft near-black and a soft
  near-white. Both are pulled back from the extremes so that body text is
  comfortable rather than harsh, while still measuring far above AA.
- **Meaning colours — Success (`#2C6B3F` / `#74C98A`), Error (`#A32F28` /
  `#F09087`), Warning (`#7A5410` / `#DCA94A`):** muted, same saturation family
  as the rest. Colour is never the only signal; every status also carries an
  icon and a word.
- **Focus (`#1A5FB4` / `#8CBEF5`):** a blue reserved exclusively for the
  keyboard focus ring. It is deliberately *not* the primary teal, so a focused
  control never reads as merely "selected".

### Theme mapping

Light is the default. Dark is applied by `prefers-color-scheme: dark` **and** by
a manual `data-theme="dark"` attribute; the manual attribute wins, so a player
can override the system. Every role below exists in both themes — there is no
light-only or dark-only colour in this system.

| Role | Token | Light | Dark |
| --- | --- | --- | --- |
| Page background | `bg` | `#F3F3F0` | `#181B1C` |
| Raised surface (cards, header) | `surface` | `#FAFAF8` | `#212527` |
| Sunken well (ad slot, code, inset) | `sunken` | `#E8E8E3` | `#111314` |
| Decorative divider | `border` | `#D5D5CE` | `#343A3C` |
| Component boundary | `border-strong` | `#82827A` | `#6B7376` |
| Body text | `text` | `#1D211F` | `#E7E9E7` |
| Secondary text | `text-muted` | `#555B57` | `#A7AEAA` |
| Primary action / link | `primary` | `#1F6B5C` | `#5FC9B4` |
| Primary action, hovered | `primary-hover` | `#175347` | `#7FD7C5` |
| Text on a primary fill | `on-primary` | `#FBFBF9` | `#0C241F` |
| Secondary accent (marks only) | `secondary` | `#87552A` | `#D9A071` |
| Success | `success` | `#2C6B3F` | `#74C98A` |
| Error | `error` | `#A32F28` | `#F09087` |
| Warning | `warning` | `#7A5410` | `#DCA94A` |
| Keyboard focus ring | `focus` | `#1A5FB4` | `#8CBEF5` |

Note that dark mode uses *lighter, less saturated* variants of the accents
rather than the same hex values — a teal that is legible on paper is not legible
on slate, and vice versa. The two themes were measured independently.

### Measured contrast

Every ratio below was **measured**, not estimated. Method: each colour's
relative luminance was obtained from `design.md lint` (`@google/design.md`
0.4.0), which reports the exact WCAG 2.1 ratio for any pair it finds below
4.5:1; pairs were measured against `#FFFFFF` and `#000000` references to recover
each luminance, and every pair in the tables below was then re-declared and
re-linted. Values are truncated, never rounded up, so no figure here overstates
the real ratio. Three independent predictions were checked against the tool and
matched to two decimal places before these tables were written.

**Requirements applied:** 4.5:1 for body text, 3:1 for large text (≥ 24px
regular or ≥ 19px semibold) and for non-text UI boundaries and the focus
indicator.

#### Light theme

| Foreground | Background | Measured | Needs | |
| --- | --- | --- | --- | --- |
| `text` `#1D211F` | `bg` `#F3F3F0` | **14.65:1** | 4.5 | PASS |
| `text` `#1D211F` | `surface` `#FAFAF8` | **15.58:1** | 4.5 | PASS |
| `text` `#1D211F` | `sunken` `#E8E8E3` | **13.24:1** | 4.5 | PASS |
| `text-muted` `#555B57` | `bg` `#F3F3F0` | **6.25:1** | 4.5 | PASS |
| `text-muted` `#555B57` | `surface` `#FAFAF8` | **6.65:1** | 4.5 | PASS |
| `primary` `#1F6B5C` | `bg` `#F3F3F0` | **5.69:1** | 4.5 | PASS |
| `primary` `#1F6B5C` | `surface` `#FAFAF8` | **6.05:1** | 4.5 | PASS |
| `on-primary` `#FBFBF9` | `primary` `#1F6B5C` | **6.08:1** | 4.5 | PASS |
| `on-primary` `#FBFBF9` | `primary-hover` `#175347` | **8.55:1** | 4.5 | PASS |
| `secondary` `#87552A` | `bg` `#F3F3F0` | **5.62:1** | 4.5 | PASS |
| `secondary` `#87552A` | `surface` `#FAFAF8` | **5.97:1** | 4.5 | PASS |
| `success` `#2C6B3F` | `bg` `#F3F3F0` | **5.75:1** | 4.5 | PASS |
| `error` `#A32F28` | `bg` `#F3F3F0` | **6.31:1** | 4.5 | PASS |
| `error` `#A32F28` | `surface` `#FAFAF8` | **6.71:1** | 4.5 | PASS |
| `warning` `#7A5410` | `bg` `#F3F3F0` | **6.09:1** | 4.5 | PASS |
| `focus` `#1A5FB4` | `bg` `#F3F3F0` | **5.65:1** | 3 | PASS |
| `focus` `#1A5FB4` | `surface` `#FAFAF8` | **6.01:1** | 3 | PASS |
| `focus` `#1A5FB4` | `sunken` `#E8E8E3` | **5.11:1** | 3 | PASS |
| `border-strong` `#82827A` | `bg` `#F3F3F0` | **3.48:1** | 3 | PASS |
| `border-strong` `#82827A` | `surface` `#FAFAF8` | **3.71:1** | 3 | PASS |
| `border-strong` `#82827A` | `sunken` `#E8E8E3` | **3.15:1** | 3 | PASS |
| `primary` fill `#1F6B5C` | `bg` `#F3F3F0` | **5.69:1** | 3 | PASS |

#### Dark theme

| Foreground | Background | Measured | Needs | |
| --- | --- | --- | --- | --- |
| `text` `#E7E9E7` | `bg` `#181B1C` | **14.19:1** | 4.5 | PASS |
| `text` `#E7E9E7` | `surface` `#212527` | **12.66:1** | 4.5 | PASS |
| `text` `#E7E9E7` | `sunken` `#111314` | **15.27:1** | 4.5 | PASS |
| `text-muted` `#A7AEAA` | `bg` `#181B1C` | **7.66:1** | 4.5 | PASS |
| `text-muted` `#A7AEAA` | `surface` `#212527` | **6.83:1** | 4.5 | PASS |
| `primary` `#5FC9B4` | `bg` `#181B1C` | **8.65:1** | 4.5 | PASS |
| `primary` `#5FC9B4` | `surface` `#212527` | **7.72:1** | 4.5 | PASS |
| `on-primary` `#0C241F` | `primary` `#5FC9B4` | **8.13:1** | 4.5 | PASS |
| `on-primary` `#0C241F` | `primary-hover` `#7FD7C5` | **9.63:1** | 4.5 | PASS |
| `secondary` `#D9A071` | `bg` `#181B1C` | **7.59:1** | 4.5 | PASS |
| `secondary` `#D9A071` | `surface` `#212527` | **6.77:1** | 4.5 | PASS |
| `success` `#74C98A` | `bg` `#181B1C` | **8.61:1** | 4.5 | PASS |
| `error` `#F09087` | `bg` `#181B1C` | **7.46:1** | 4.5 | PASS |
| `error` `#F09087` | `surface` `#212527` | **6.66:1** | 4.5 | PASS |
| `warning` `#DCA94A` | `bg` `#181B1C` | **8.09:1** | 4.5 | PASS |
| `focus` `#8CBEF5` | `bg` `#181B1C` | **8.92:1** | 3 | PASS |
| `focus` `#8CBEF5` | `surface` `#212527` | **7.96:1** | 3 | PASS |
| `focus` `#8CBEF5` | `sunken` `#111314` | **9.60:1** | 3 | PASS |
| `border-strong` `#6B7376` | `bg` `#181B1C` | **3.58:1** | 3 | PASS |
| `border-strong` `#6B7376` | `surface` `#212527` | **3.19:1** | 3 | PASS |
| `border-strong` `#6B7376` | `sunken` `#111314` | **3.85:1** | 3 | PASS |
| `primary` fill `#5FC9B4` | `bg` `#181B1C` | **8.65:1** | 3 | PASS |

#### Decorative pairs — below 3:1 on purpose

These three pairs do **not** meet 3:1 and are not required to, because they
carry no information. They are recorded so a later Task does not mistake them
for boundaries.

| Pair | Light | Dark | Rule |
| --- | --- | --- | --- |
| `border` on `bg` | 1.33:1 | 1.50:1 | Decorative divider only. If a line delimits a control, use `border-strong`. |
| `surface` on `bg` | 1.06:1 | 1.12:1 | Surface separation is carried by the border and the radius, not by the fill. |

> **Rule for every later Task:** if you need a new colour, you do not have one.
> Ask on the Task. Do not hand-pick a hex, and do not use a colour whose ratio
> against its real background has not been measured and written down here.

---

## Typography

One family, used everywhere. No decorative or display face.

### The family

```
--font-sans: system-ui, -apple-system, "Segoe UI", Roboto, "Noto Sans",
             "Liberation Sans", Arial, sans-serif;
```

**Why a system stack and not a webfont.** M1 may add no runtime dependency and
make no third-party or CDN request (`AGENTS.md` §1, and this Task's rules), so a
Google Fonts or CDN-hosted face is not available to us. A self-hosted file is
possible in principle but would mean shipping and licensing a binary in a
milestone whose stack is still undecided. The system stack costs nothing, loads
instantly, adds no request, and leaks no visitor data to a font host — which
also satisfies the "no trackers, no third-party requests" rule directly.

**Vietnamese coverage — what is verified and what is not.** This is a hard
requirement, not a preference: the UI is Vietnamese first and must render
`ế ệ ẫ ộ ờ ữ ạ ỉ ỹ đ Đ` correctly. In modern UTF-8 web content these are
*precomposed* characters in Latin Extended Additional (U+1EA0–U+1EF9), which is
a stricter test than "supports Latin Extended".

The stack is ordered so the first match on each platform is the platform's own
UI face. Coverage was checked against vendor documentation during this Task,
with this result:

| Position | Resolves to | Vietnamese coverage |
| --- | --- | --- |
| `system-ui`, `-apple-system` | SF (macOS/iOS), Segoe UI (Windows), Roboto (Android) | see below |
| `"Segoe UI"` | Windows | **Documented.** Microsoft lists Vietnamese explicitly among the supported scripts. |
| `Roboto` | Android | **Not verified in this Task** — the source was unreachable. |
| `"Noto Sans"` | Linux / fallback | **Not verified in this Task** — the source was unreachable. |
| `"Liberation Sans"` | Linux | **Not verified in this Task.** |
| `Arial` | legacy backstop | **Not established.** See the caveat below. Last on purpose. |
| Apple SF via `-apple-system` | macOS/iOS | **Not confirmed.** Apple documents Latin, Greek and Cyrillic and 150+ languages but does not name Vietnamese in published docs. |

**The Arial caveat, recorded so nobody re-derives it.** Arial's font metadata
sets the Windows code page `1258 Vietnamese` bit, and that is *not* evidence of
the coverage we need: CP1258 encodes Vietnamese tone marks as **combining**
diacritics over base letters, so a font can set that bit while shipping few or
no precomposed U+1EA0–U+1EF9 glyphs. Do not treat the CP1258 bit as proof.

**Why the ordering still holds despite the gaps.** Font fallback on every major
platform is **per-codepoint, not per-run**: if the first font has `e` but lacks
`ế`, the renderer swaps in a different font for that one character. The visible
symptom is tone marks that do not match the rest of the word. Putting each
platform's own UI face first minimises how often that substitution happens,
and putting Arial last keeps the least-established face out of the way.

> **Open verification item for M1-04.** The browser check in M1-04 must render a
> Vietnamese pangram containing stacked marks (`ế ệ ẫ ộ ờ ữ ạ ỉ ỹ đ`) at
> `caption` (12px) and `display` size, in both themes, on at least one Windows,
> one Apple and one Android target, and confirm no per-character font
> substitution and no clipping. If a platform fails, the fix is to add a
> self-hosted face — not to reorder blindly.

**If a later milestone wants a branded face**, it must be self-hosted, licence
recorded in the repository, and checked for the full precomposed Vietnamese
glyph set before adoption — not just "Latin Extended". That is a Board decision.

### The scale

Ten levels. Mobile is the default (the system is mobile-first); desktop values
apply from the 768px switch described in "Layout".

| Token | Role | Mobile | Desktop | Weight | Line height | Tracking |
| --- | --- | --- | --- | --- | --- | --- |
| `display` | Page hero, home page only | 32px | 40px | 600 | 1.2 | −0.01em |
| `headline-lg` | Page title (`h1`) | 26px | 32px | 600 | 1.3 | −0.01em |
| `headline-md` | Section title (`h2`), wordmark | 21px | 24px | 600 | 1.3 | 0 |
| `headline-sm` | Card title (`h3`), dialog title | 18px | 20px | 600 | 1.35 | 0 |
| `body-lg` | Lead paragraph, game description | 17px | 18px | 400 | 1.6 | 0 |
| `body-md` | Default body text | 16px | 16px | 400 | 1.6 | 0 |
| `body-sm` | Dense text, table cells | 14px | 14px | 400 | 1.55 | 0 |
| `label-md` | Buttons, form labels, nav | 14px | 14px | 500 | 1.4 | +0.01em |
| `label-sm` | Badges, chips, table headers | 12px | 12px | 500 | 1.4 | +0.01em |
| `caption` | Ad label, metadata, footnotes | 12px | 12px | 400 | 1.4 | +0.01em |

**Weights.** Three only: 400 body, 500 controls and labels, 600 headings. Never
more than two weights in a single view.

### Rules forced by Vietnamese

These are not stylistic preferences — breaking them clips or collides glyphs.

- **Line height never below 1.3, body never below 1.55.** Vietnamese stacks a
  tone mark above an already-accented vowel (`ế`, `ữ`) and places marks below
  the baseline (`ụ`, `ợ`). Tight leading makes the mark of one line touch the
  descender of the line above.
- **Never tighten tracking past −0.01em.** Diacritics need horizontal room;
  negative tracking is what makes `Trò chơi` look smudged.
- **16px minimum for body text on mobile.** Below that the tone marks stop being
  distinguishable from each other, and on iOS it triggers auto-zoom on inputs.
- **Never use `text-transform: uppercase` on Vietnamese running text.** Uppercase
  Vietnamese loses legibility because the marks crowd the cap height. Uppercase
  is permitted only for single-word `label-sm` chips, and even there it is
  discouraged.
- **Measure:** 35–60 characters per line on mobile, 60–75 on desktop.

---

## Layout & Spacing

### The M1 breakpoints — fixed here

These are **the only two widths any M1 Task designs at.** A later Task may not
introduce a third, and may not move these.

| Name | Width | Use |
| --- | --- | --- |
| **Mobile** | **375px** | The mobile design width for every M1 wireframe and prototype. |
| **Desktop** | **1280px** | The desktop design width for every M1 wireframe and prototype. |

There are exactly two CSS switch points, also fixed here so that no Task invents
one:

| Name | Value | Meaning |
| --- | --- | --- |
| **Switch** | **768px** | `min-width: 768px` turns on the desktop type scale and the desktop layout. Below it, the mobile scale applies. |
| **Wide** | **1024px** | `min-width: 1024px` turns on the play-page **side rail**. Between 768px and 1023px the page uses the desktop type scale and gutters but stays single-column, and the game frame takes the full content width. |

So: two design widths (375, 1280), two media queries (768, 1024). That is the
complete set. Layout is mobile-first — the mobile rules are the unconditional
defaults and the two queries only add.

Three layout bands follow from the two switches. Name them this way everywhere:

| Band | Range | Play page |
| --- | --- | --- |
| **mobile** | < 768px | single column, mobile type scale, 16px gutter |
| **compact** | 768–1023px | single column, desktop type scale, 32px gutter |
| **wide** | ≥ 1024px | two columns: game frame + 300px side rail |

#### Why `wide` is 1024px — Board amendment of 2026-11-04

The original single switch put the side rail beside the game frame from 768px
up. GAM-23 §4.4 did the arithmetic and reported the result: at 768px the frame
is **372 × 209** next to a **300 × 250** ad, so the ad is taller than the game
and 81% of its width. **The game is the centre of this site; that inverts it.**
The Board's decision was to add the switch rather than accept the band.

The value is derived, not chosen by taste. With the rail present:

```text
main column = viewport − 2×32 (gutters) − 300 (rail) − 32 (column gap)
            = viewport − 396
game frame  = main column at 16:9
```

Two conditions make a rail worth having, and both are about the frame keeping
its place as the largest thing on the page:

| Condition | Arithmetic | Floor |
| --- | --- | --- |
| The frame is **taller** than the rail ad, so the ad sits wholly beside it | `main × 9/16 ≥ 250` → `main ≥ 445` | **841px** |
| The frame is at least **twice the ad's width**, so it still dominates | `main ≥ 2 × 300 = 600` | **996px** |

The binding floor is **996px**. The breakpoint is set at **1024px**: the nearest
standard viewport width above the floor — tablet landscape, and the most common
third breakpoint on the web, so the boundary falls *between* device classes
instead of inside one. At 1024px the main column is **628px** and the frame is
**628 × 353** — 2.09× the ad's width and 1.41× its height. Both conditions hold
with margin at every width where the rail exists, which was the point.

Below 1024px the 300 × 250 rectangle is not deleted — it moves below the game
info, the position `requirements.md` §5.1 already names for mobile. Ad inventory
per page is unchanged in every band. The geometry is in
[`ad-slots.md`](ad-slots.md) §4; this document fixes only the breakpoint.

### Spacing scale

A 4px base grid. Every margin, padding and gap in the product is one of these.

| Token | Value | Typical use |
| --- | --- | --- |
| `none` | 0 | Reset |
| `xs` | 4px | Icon-to-label gap |
| `sm` | 8px | Inside a chip, between stacked labels |
| `md` | 12px | Button padding, tight list rows |
| `lg` | 16px | Card padding, default gap, page gutter on mobile |
| `xl` | 20px | Card padding on desktop |
| **`ad-clearance`** | **24px** | **See below — the ad rule.** |
| `2xl` | 32px | Gap between cards, page gutter on desktop |
| `3xl` | 40px | Between minor sections |
| `4xl` | 48px | Between major sections |
| `5xl` | 64px | Above the footer |
| `6xl` | 80px | Page top/bottom on desktop |

### The ad clearance token

`ad-clearance` is **24px** and exists because of a hard product rule, not for
rhythm. `docs/requirements.md` §5 requires a minimum margin between an ad slot
and the game frame, and the layout in that section shows **≥ 24px**.

- **`--space-ad-clearance` is the minimum separation between any ad slot and the
  game frame, on every side, in every band.** It is a floor, not a target:
  M1-02 may use more, never less.
- An ad never sits inside or overlapping a game frame (`AGENTS.md` §5.3).
- An ad slot reserves its own box so the page does not shift when it fills.
- M1-02 owns the ad-slot layout spec and consumes this token. It must not
  redefine the value.

### Containers and gutters

| | Mobile (375) | Compact (768–1023) | Desktop (1280) |
| --- | --- | --- | --- |
| Page gutter | `lg` (16px) | `2xl` (32px) | `2xl` (32px) |
| Max content width | 100% | 100% | 1200px, centred |
| Game grid | 1 column | 3 columns, `2xl` gap | 3 columns, `2xl` gap |
| Play page | 1 column | 1 column, **no side rail** | frame + 300px rail |
| Vertical rhythm between sections | `3xl` (40px) | `4xl` (48px) | `4xl` (48px) |

Only the **play page** changes at `wide` (1024px); every other page is governed
by `switch` (768px) alone, as before.

### Touch and target size

Minimum interactive target **44×44px** at every breakpoint, with at least 8px
between adjacent targets. Where the visible control is smaller (an icon button,
a checkbox), the hit area is padded out to 44px — the visual size shrinks, the
target does not.

---

## Elevation & Depth

Depth is carried by **tonal layers and borders, not by heavy shadows.** This is
what keeps the chrome quiet.

Three levels only:

| Level | Light | Dark | Use |
| --- | --- | --- | --- |
| **Flat** | `bg`, no border | `bg`, no border | The page itself |
| **Raised** | `surface` + 1px `border` + `shadow-sm` | `surface` + 1px `border` (no shadow) | Cards, header, game tiles |
| **Overlay** | `surface` + 1px `border-strong` + `shadow-md` | `surface` + 1px `border-strong` | Dialogs, menus, toasts |

```
--shadow-sm: 0 1px 2px rgba(29, 33, 31, 0.06);
--shadow-md: 0 4px 12px rgba(29, 33, 31, 0.10);
```

**Shadows are suppressed in dark mode.** A shadow is a darkening, and darkening
a dark surface does nothing — on slate it reads as mud. Dark mode separates
layers by lifting the surface tone (`#181B1C` → `#212527`) and by the border
instead. Both themes therefore keep the same three levels; only the mechanism
changes.

Never invent a fourth level, and never stack shadows to fake one.

---

## Shapes

**Friendly, not childish.** A medium corner radius throughout: soft enough to
feel approachable, not so round that the UI reads as a toy.

| Token | Value | Use |
| --- | --- | --- |
| `none` | 0 | Full-bleed media, table edges |
| `sm` | 6px | Inputs, chips, small controls, the ad slot container |
| `md` | 10px | **The default.** Buttons, cards, dialogs |
| `lg` | 14px | Game tiles, large panels |
| `xl` | 20px | The hero block on the home page |
| `full` | 9999px | Pills, avatars, the theme toggle |

Do not mix rounded and square corners within one view. Icons are **simple line
icons** at a uniform 1.5px stroke, from a single set, sized by a token
(`16 / 20 / 24px`) — never emoji, never mixed filled-and-outline at the same
level.

### Motion

Minimal by design. Motion explains a change; it never decorates.

- Durations: **120ms** for state changes (hover, press), **200ms** for entering
  elements, **140ms** for leaving (exits are faster than entrances).
- Easing: decelerate on entry (`cubic-bezier(0, 0, 0.2, 1)`), accelerate on exit.
- Animate `transform` and `opacity` only — never width, height, top or left.
- At most one or two moving elements in a view.
- **`prefers-reduced-motion: reduce` is honoured globally** in `base.css`:
  durations collapse to ~0 and nothing animates. This is a requirement, not a
  nicety.

---

## Components

The inventory below **names** the components and fixes their tokens. The visual
designs are drawn in **M1-04**; the ad-slot layout is **M1-02**; the page
wireframes are **M1-03**. No design work for these belongs in this Task.

### Keyboard focus — specified here, used by all of them

A visible focus indicator is mandatory on every focusable element.

```
--focus-ring-width: 3px;
--focus-ring-offset: 2px;
outline: var(--focus-ring-width) solid var(--color-focus);
outline-offset: var(--focus-ring-offset);
```

- **Light:** `#1A5FB4` — measured **5.65:1** on `bg`, **6.01:1** on `surface`,
  **5.11:1** on `sunken`. Required: 3:1. Passes everywhere.
- **Dark:** `#8CBEF5` — measured **8.92:1** on `bg`, **7.96:1** on `surface`,
  **9.60:1** on `sunken`. Required: 3:1. Passes everywhere.

**Why the 2px offset is not optional.** The focus blue and the primary teal are
close in luminance — measured against each other they are **1.01:1**, which is
to say invisible. A ring drawn directly on a primary-filled button would
therefore vanish. The offset pushes the ring onto the page or surface behind the
control, where the measured ratios above apply. If a design genuinely needs a
ring *inside* a primary fill, use `on-primary` instead: measured **6.08:1** on
`primary` (light) and **8.13:1** on `primary` (dark).

Further rules: never remove the outline without replacing it; use `:focus-visible`
so pointer users do not see a ring on click; and the focused control must never
be hidden behind a sticky header or the cookie banner (WCAG 2.2 "focus not
obscured").

### Inventory

**Chrome**
`wordmark` · `site-header` · `primary-nav` · `theme-toggle` · `footer` ·
`skip-link` · `breadcrumb`

**Game surfaces**
`game-card` (thumbnail, title, short description, controls, Play button,
top-3 strip) · `game-grid` · `game-frame` · `game-meta`

**Leaderboard**
`leaderboard-table` · `rank-row` · `own-rank-row` (highlighted) · `week-badge`

**Controls**
`button-primary` · `button-secondary` · `button-quiet` · `link` · `icon-button`
· `input-text` · `input-search` · `select` · `checkbox` · `radio` · `toggle` ·
`pagination`

**Feedback and overlay**
`dialog` · `toast` · `inline-error` · `error-summary` · `empty-state` ·
`skeleton` · `badge` · `chip`

**Commercial and compliance**
`ad-slot` (reserves its box; `sunken` fill; `caption` label; `ad-clearance`
margin from the game frame) · `cookie-banner` (Accept / Reject / Settings, equal
weight, no ad script before consent)

### Rules that bind the whole inventory

- **One primary action per view.** `button-primary` appears once; everything
  else is `button-secondary` or `button-quiet`.
- **`secondary` (Clay) is never an action.** Marks and badges only.
- **Every control has a visible text label.** Icon-only controls carry an
  accessible name and a 44px target.
- **Status is never colour alone** — icon plus word, always.
- **Inputs have visible labels**, not placeholder-only, with errors below the
  field and linked by `aria-describedby`.
- **The ad slot is not focusable ahead of the page's own content** and never
  traps focus (`docs/requirements.md` §5).

---

## Do's and Don'ts

**Colour**

- **Do** keep the chrome quiet so the game thumbnails are the brightest thing on
  the page.
- **Do** use `primary` for exactly one thing per view: the action that matters.
- **Don't** introduce a colour that is not in the table above. If you need one,
  raise it on the Task.
- **Don't** use pure `#000` or `#FFF` as a background in either theme.
- **Don't** use a gradient as a surface, or a saturated full-page colour.
- **Don't** use `secondary` for a button, a link, or anything clickable.

**Contrast and accessibility**

- **Do** measure every new text/background pair and write the ratio down here
  before using it.
- **Do** keep body text at 4.5:1 and UI boundaries and the focus ring at 3:1.
- **Don't** remove a focus outline. Ever. Replace it or keep it.
- **Don't** rely on `border` to delimit a control — it measures 1.33:1. Use
  `border-strong`.
- **Don't** assume a light-mode pair is fine in dark mode. Measure both.

**Typography**

- **Do** check Vietnamese diacritics render and do not clip at every size.
- **Do** keep body line height at 1.6 and headings at 1.3 or looser.
- **Don't** tighten tracking below −0.01em, and don't uppercase Vietnamese
  running text.
- **Don't** use more than two font weights in one view, or add a second family.
- **Don't** set body text below 16px on mobile.

**Layout and motion**

- **Do** design at 375px and 1280px, and only at those widths.
- **Do** check the play page at 768px and 1024px as well — the `compact` band is
  verified, not designed. Its layout follows from the single-column rules.
- **Do** keep at least `ad-clearance` (24px) between any ad and the game frame.
- **Do** honour `prefers-reduced-motion`.
- **Don't** invent a breakpoint, a spacing value, or a corner radius outside the
  scales above. The breakpoints are 768 and 1024; a third needs a Board decision
  recorded in the amendments table at the top of this document.
- **Don't** put an ad inside, overlapping, or above the game in the focus order.
- **Don't** animate layout properties, or move more than one or two things at
  once.

---

## Tooling

This file is written in the Google DESIGN.md format and is validated with the
`design.md` CLI (`@google/design.md` 0.4.0), installed by the Board for this
milestone.

```
design.md lint docs/design/DESIGN.md        # validate
design.md export docs/design/DESIGN.md --format css-vars
```

**Current lint status: 0 errors, 4 warnings.** All four are the same warning,
on `components.*.borderColor`:

> `'borderColor' is not a recognized component sub-token.`

This is expected and deliberate. The format's component schema has no border
property, but which border a component uses is real design information —
`card` uses `border`, `button-secondary` uses `border-strong` — and the format
specification explicitly says a consumer should *accept an unknown component
property with a warning*. The alternative, dropping the property, would make
the four border colours unreferenced and produce four `orphaned-tokens`
warnings instead, which would wrongly imply the tokens are dead. There are no
contrast warnings and no errors.

The `css-vars` export emits the same `--color-*` names that
[`prototypes/assets/tokens.css`](prototypes/assets/tokens.css) defines by hand,
so the two stay legible against each other. `tokens.css` remains the file the
prototypes import: it carries the light/dark switching, the responsive type
scale and the motion tokens, which the flat export cannot express.

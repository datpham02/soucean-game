# Ad-slot layout — Soucean Game

**Status:** Normative. Milestone M1 (Design), Task GAM-23 (M1-02). Owner:
`ui-designer`, through `project-manager`. Last updated: 2026-11-04.

This document decides, **once**, how ad slots sit on every page of Soucean Game:
how many there are, how big they are, where they go, and how far they stay from
the game. No later mockup, wireframe or component design re-decides any of it.

It is downstream of three documents and does not contradict them:

| Source | What it fixes | What this document does |
| --- | --- | --- |
| [`../requirements.md`](../requirements.md) §5 | the allowed positions, the ≥ 24 px margin, the no-overlap and no-sticky rules | implements them as geometry |
| [`DESIGN.md`](DESIGN.md) (M1-01) | `--space-ad-clearance`, the 375 / 768 / 1280 breakpoints, gutters, the `ad-slot` component | consumes them; redefines none |
| [`../../AGENTS.md`](../../AGENTS.md) §5.3 | ads never inside or overlapping a game frame | states it as a layout invariant |

Its CSS counterpart is [`prototypes/assets/ads.css`](prototypes/assets/ads.css),
which reserves the box for every slot in this inventory.

**Ads are the only revenue, and they live on the website page only — never
inside a game.** The whole of this spec follows from the second half of that
sentence.

---

## 1. What is in scope, and what is deliberately absent

In scope: slot inventory, fixed sizes, clearance geometry, stacking order,
reservation and layout shift, and how a reviewer measures all of it.

**Deliberately absent, and a defect if a later Task adds it in M1:**

- any ad network, ad tag, ad script, `<iframe>`, pixel, tracker or third-party
  request — M1 is design, M6 is integration;
- any backend, hosting or ad-network configuration (`AGENTS.md` §1);
- any framework, build step, runtime dependency or package manifest;
- any new colour, spacing step or breakpoint — all three are M1-01's.

Every slot in this document is an **empty placeholder box**.

---

## 2. The five rules, stated plainly

These are the rules. Everything after this section is the geometry that
delivers them.

1. **Clearance.** At least **24 px** of empty space between any ad and the game
   frame, on every side the two are adjacent, at every breakpoint. The value is
   `--space-ad-clearance`, defined in M1-01 and never redefined here. It is a
   floor, not a target.
2. **No overlap, no containment.** No ad overlaps the game frame, and no ad slot
   is a descendant of the game frame element. An `.ad-slot` inside `.game-frame`
   is a defect.
3. **No sticky, no fixed.** No ad uses `position: sticky` or `position: fixed`,
   no float, no `z-index`, no `transform`. No pop-up, no interstitial, no
   overlay. An ad never moves relative to the page while the page scrolls.
4. **Fixed, pre-reserved sizes.** Every slot's box is at its final height before
   anything loads into it, so the game frame never jumps. Expected Cumulative
   Layout Shift contribution from ad slots: **zero**.
5. **No sidebar on mobile.** The mobile play page has exactly **two** banners:
   one above the game frame, one after the game info.

---

## 3. Slot inventory

Five slots, three creative formats. **A slot that is not in this table is a
defect** (`requirements.md` §5.1).

| Id | CSS class | Format | Fixed size | Pages | Breakpoint |
| --- | --- | --- | --- | --- | --- |
| `ad-header-leaderboard` | `.ad-slot--header-leaderboard` | leaderboard | **728 × 90** | every page, in the site header | desktop only (≥ 768 px) |
| `ad-rail-rectangle` | `.ad-slot--rail-rectangle` | medium rectangle | **300 × 250** | play page `/games/<game_id>`, top of the right-hand rail, above the weekly board | desktop only (≥ 768 px) |
| `ad-incontent-banner` | `.ad-slot--incontent-banner` | leaderboard | **728 × 90** | play page, after the game info | desktop only (≥ 768 px) |
| `ad-mobile-top` | `.ad-slot--mobile-top` | large mobile banner | **320 × 100** | play page, above the game frame | mobile only (< 768 px) |
| `ad-mobile-after-info` | `.ad-slot--mobile-after-info` | medium rectangle | **300 × 250** | play page, after the game info | mobile only (< 768 px) |

So the play page carries **three** slots at desktop and **exactly two** at
mobile. Every other page carries **one** at desktop (the header leaderboard) and
**none** at mobile.

### Why these sizes

- **728 × 90** and **300 × 250** come straight from `requirements.md` §5.1. The
  in-content banner is specified there only as "fixed, reserved"; it is fixed
  here at 728 × 90 so the desktop page needs **two** creative sizes, not three.
- **320 × 100** is the mobile banner above the frame. It fits the 343 px mobile
  content column with room to spare, and it is short enough to keep the game
  frame near the top of a phone screen.
- **300 × 250** is reused for the lower mobile slot, so the whole site needs
  only two creative sizes in total.

### Why mobile carries no ad outside the play page

`requirements.md` §5.1 names the mobile positions relative to the game frame
("above the frame", "after the game info"), and the desktop header leaderboard
does not fit a 343 px column. Rather than invent a mobile home-page slot, this
spec adds none. **Open item for the Board** — see §11.

---

## 4. Reference page geometry

The numbers below are the reference layout. M1-03 builds the play-page wireframe
to it; M1-04 designs the components inside it. A later Task may increase any
clearance and may never decrease one.

Everything is taken from M1-01: gutter `--layout-gutter` (16 px mobile, 32 px
desktop), max content width `--layout-max-width` (1200 px), section rhythm
`--space-4xl` (48 px desktop) and `--space-3xl` (40 px mobile).

### 4.1 Desktop — viewport 1280 px

```text
viewport 1280
├─40─┬──────────────── content column 1200 ────────────────┬─40─┤

┌───────────────────── site-header ─────────────────────────────┐
│                  ┌──────────────────────────┐                 │
│                  │ ad-header-leaderboard    │  728 × 90       │
│                  │ (centred in 1200)        │                 │
│                  └──────────────────────────┘                 │
└───────────────────────────────────────────────────────────────┘
                              ▲ ≥ 120 px clearance to the frame
   <h1> game title

   ┌──────── main column 868 ────────┐  32  ┌─── rail 300 ───┐
   │                                 │ ◄──► │                │
   │        game-frame 868 × 488     │      │ ad-rail-       │
   │        (16:9, letterboxed)      │      │ rectangle      │
   │                                 │      │ 300 × 250      │
   │                                 │      └────────────────┘
   └─────────────────────────────────┘      weekly leaderboard
                 ▼ 48 px
   game-meta: controls · how to play
                 ▼ ≥ 72 px clearance to the frame
   ┌──────────────────────────┐
   │ ad-incontent-banner      │  728 × 90, centred in 868
   └──────────────────────────┘
```

| Measure | Value | Where it comes from |
| --- | --- | --- |
| Content column | 1200 px | `--layout-max-width` |
| Rail column | 300 px | the rectangle format |
| Grid column gap | **32 px** | `--space-2xl`; minimum permitted is `--space-ad-clearance` |
| Main column | **868 px** | 1200 − 300 − 32 |
| Game frame | 868 × 488 | main column at 16:9 |

### 4.2 Mobile — viewport 375 px

```text
viewport 375
├16┬──────── content column 343 ────────┬16┤

   <h1> game title
   ┌───────────────────────────────┐
   │ ad-mobile-top    320 × 100    │  centred in 343
   └───────────────────────────────┘
                 ▼ 24 px  ← the clearance floor, exactly
   ┌───────────────────────────────────┐
   │  game-frame  343 × 193  (16:9)    │
   └───────────────────────────────────┘
                 ▼ 40 px
   game-meta: controls · how to play
                 ▼ ≥ 64 px clearance to the frame
   ┌─────────────────────────────┐
   │ ad-mobile-after-info        │  300 × 250, centred in 343
   └─────────────────────────────┘

   weekly leaderboard
```

**No sidebar. No third banner. No ad in the mobile header.**

### 4.3 The one structural rule the layout must honour

Each page column is a **flex column** (`display: flex; flex-direction: column`)
with **no `gap`** — the vertical rhythm comes from each block's own margins.

This is not decoration. Margins of flex items never collapse, so the ad slot's
own 24 px margin is always *added* to whatever separation the page already has,
and can never be absorbed into it. (Even in a plain block container the floor
would survive, because margin collapsing takes the **larger** of two margins,
never the smaller — but then the measured totals in §5 would be `max()` rather
than sums, and the spec prefers numbers a reviewer can add up.)

---

## 5. Clearance — the 24 px rule

### 5.1 How clearance is measured

Clearance is the **empty distance between the ad's border box and the game
frame's border box, on the axis along which the two are adjacent.**

No slot in this inventory is adjacent to the frame on more than one axis:

- the three banner slots sit **above or below** the frame and overlap it in
  horizontal extent, so only the vertical distance is meaningful;
- the rail slot sits **beside** the frame, and its 250 px height falls entirely
  within the frame's 488 px height, so only the horizontal distance is
  meaningful.

Rule 1 of §2 — "on every side the two are adjacent" — is therefore satisfied by
one number per slot.

### 5.2 Measured values, per slot, per breakpoint

| Slot | Breakpoint | Adjacency | Clearance | Arithmetic |
| --- | --- | --- | --- | --- |
| `ad-header-leaderboard` | desktop | frame is below | **≥ 120 px** | 24 slot `margin-block-end` + 16 header padding + 48 main padding + 32 title margin, plus the title's own height |
| `ad-rail-rectangle` | desktop | frame is to the inline-start | **32 px exactly** | play-grid `column-gap: var(--space-2xl)`; the binding minimum is `var(--space-ad-clearance)` = 24 |
| `ad-incontent-banner` | desktop | frame is above | **≥ 72 px** | 48 frame→game-meta (`--space-4xl`) + 24 slot `margin-block-start`, plus the game-meta block's height |
| `ad-mobile-top` | mobile | frame is below | **24 px exactly** | 24 slot `margin-block-end` = `var(--space-ad-clearance)` |
| `ad-mobile-after-info` | mobile | frame is above | **≥ 64 px** | 40 frame→game-meta (`--space-3xl`) + 24 slot `margin-block-start`, plus the game-meta block's height |

Every number above is a token, never a literal. `ad-mobile-top` is the tight
case and sits exactly on the floor; every other slot has room above it.

No ad is directly adjacent to the game's controls area: the `game-meta` block
that carries the controls always sits between the frame and the slot below it
(`requirements.md` §5.2).

### 5.3 Which clearance ads.css owns, and which the page owns

`ads.css` puts `margin-block: var(--space-ad-clearance)` on **every** slot. That
is a guarantee the stylesheet makes on its own, in any layout: four of the five
clearances above cannot fall below 24 px no matter what page wraps them.

The **one exception is `ad-rail-rectangle`'s horizontal clearance.** The rail
column is exactly 300 px wide — the width of the creative — so an inline margin
on the slot would make its box 324 px and overflow the column, pushing the
layout rather than protecting it. `ads.css` therefore sets `margin-inline: 0` on
that slot, and the horizontal clearance is the play-page grid's `column-gap`.

That makes one binding requirement on M1-03:

> **The play-page grid's `column-gap` is at least `var(--space-ad-clearance)`.**
> The reference layout uses `var(--space-2xl)` (32 px). It may be increased. It
> may never go below 24 px, and it may never be zero.

This is the single clearance a reviewer must check against the page rather than
against `ads.css`, and §10 says how.

---

## 6. No overlap, no ad inside the frame

Stated as rules, and true by construction:

- **An ad slot is never a descendant of the game frame element.** Every slot is
  a sibling of the frame, in the page's normal flow. `.game-frame .ad-slot` must
  never match anything.
- **Every slot is a normal-flow block box.** `ads.css` sets `position: static`,
  `float: none`, `z-index: auto` and `transform: none` on `.ad-slot`. None of
  the four may be overridden.
- **No negative margins on an ad slot, ever.** A negative margin is the only way
  a static box can be pulled over a sibling, so it is forbidden outright.
- The reference geometry in §4 is overlap-free by arithmetic: at desktop the
  rail slot's inline-start edge is 900 px from the content-column origin and the
  frame's inline-end edge is at 868 px; the two banner slots are separated from
  the frame on the block axis by the distances in §5.2.

§10 gives a Console snippet that prints an `overlaps` boolean for every slot.

---

## 7. No sticky, no fixed

> **No ad may use `position: sticky` or `position: fixed`.** No pop-up, no
> interstitial, no overlay, no ad that follows the scroll.

`requirements.md` §5.1 already marks the desktop sidebar **"Not sticky."** This
spec extends that to all five slots, as `requirements.md` §5.3 requires.

Two reasons, both load-bearing:

1. **The game.** A position-sticky ad beside a 488 px frame would ride up over
   the frame as the player scrolls. That is the one thing `AGENTS.md` §5.3
   forbids absolutely.
2. **Accessibility.** WCAG 2.2 AA *Focus Not Obscured* requires that sticky UI,
   overlays and banners never hide the keyboard-focused control. A sticky ad is
   exactly the pattern that criterion exists to stop, and the cookie banner
   already occupies the site's only justified sticky surface.

`ads.css` writes the rule as CSS on `.ad-slot`, so a slot is static by default
and someone has to actively override it to break the rule. §10 gives the grep
and the computed-style check that catch it if they do.

---

## 8. Fixed sizes and zero layout shift

### 8.1 How the box is reserved

```css
.ad-slot {
  box-sizing: border-box;
  inline-size: min(var(--ad-slot-inline), 100%);
  block-size: var(--ad-slot-block);   /* literal, explicit, unconditional */
}
```

- **The block size is an explicit literal pixel value.** It is applied by the
  stylesheet, so it is in force at the first paint — before any creative, before
  any consent decision, before any network request. There is nothing to load and
  therefore nothing to resize. Everything below the slot is already in its final
  position.
- **`aspect-ratio` is deliberately not used.** It is the right tool for a box
  whose height follows a fluid width, and it reserves height just as well. These
  are fixed-size creatives: their height must not change when the column width
  changes, so an explicit `block-size` says what is meant. If a fluid slot is
  ever introduced, `aspect-ratio` on the reserved box is the correct mechanism
  and this paragraph is the reason it was not needed in M1.
- **The inline size is capped at `100%`.** A 728 px box in the 704 px content
  column of a 768 px viewport would otherwise force a horizontal scrollbar on
  the whole page. Narrowing a box moves nothing below it, so the cap costs no
  stability. At integration (M6) a slot whose measured box is narrower than its
  format width requests no creative.

### 8.2 Expected Cumulative Layout Shift: zero

**The expected CLS contribution of the ad slots is `0` — not "under 0.1", not
"small". Zero.** There is no state in which a slot changes size after the first
paint:

| Event | Effect on the slot | Shift |
| --- | --- | --- |
| First paint | box is already at its final size | 0 |
| Consent accepted | the creative is drawn **inside** an already-sized box | 0 |
| Consent rejected | the box stays reserved and stays empty, or shows a house promotion at the same size | 0 |
| Ad request fails or is blocked | the box stays reserved and stays empty | 0 |
| Theme switch | no size token changes | 0 |
| Viewport resize across 768 px | slots swap, but shift from a viewport resize is excluded from the CLS metric | not counted |

The third and fourth rows are the ones that usually break: a slot that is
reserved only *after* the ad network answers still shifts the page when it is
blocked. Here the box is reserved by the page's own stylesheet and never depends
on an answer.

---

## 9. Stacking, DOM order and accessibility

### 9.1 Desktop play page

```
site-header
  wordmark · nav · theme-toggle
  ad-header-leaderboard
main
  h1 game title
  play-grid
    main column:  game-frame → game-meta → ad-incontent-banner
    rail column:  ad-rail-rectangle → weekly leaderboard
footer
```

The rail is **second in DOM order**, after the whole main column, and the grid
places it in column 2. Nothing is visually reordered, so reading order and
visual order agree.

### 9.2 Mobile play page — exactly two banners

```
site-header
  wordmark · nav · theme-toggle          ← no ad
main
  h1 game title
  ad-mobile-top            320 × 100
  game-frame
  game-meta                controls · how to play
  ad-mobile-after-info     300 × 250
  weekly leaderboard
footer
```

One banner above the game frame, one after the game info. No sidebar, no rail,
no third slot — the desktop-only slots are `display: none` below 768 px, so they
cannot reappear by accident.

### 9.3 Accessibility rules for a slot

`requirements.md` §5 requires that an ad slot is not focusable ahead of the
page's own content and never traps keyboard focus. Concretely:

- **Markup.** `<aside class="ad-slot …" aria-label="Quảng cáo">` with a visible
  `.ad-slot__label`. A named landmark is skippable; an unlabelled grey box is
  not.
- **Never focusable.** No `tabindex` on a slot, now or at integration. The
  placeholder contains no interactive element.
- **The header slot is behind the skip link.** The site `skip-link` targets
  `#main`, so a keyboard user passes the header banner in one keystroke.
- **Not colour alone.** The slot is identified by its visible label, not by its
  `sunken` fill.
- **Nothing moves.** No animation, no auto-rotation, no carousel in a slot — so
  the WAI pause/stop requirement never applies.
- **At integration (M6)** the creative `<iframe>` carries a `title`, stays
  inside the reserved box, and is never given a positive `tabindex`.

Vietnamese first: the placeholder label is "Quảng cáo". The English string is
"Advertisement" when the site gains a second language.

---

## 10. How to verify

Two breakpoints, the ones M1-01 fixed: **desktop 1280 px** and **mobile 375 px**.
Device-toolbar responsive mode, device pixel ratio 1, browser zoom 100 %.

### 10.1 Build the check page

Save the document in §12 as `docs/design/prototypes/ad-slots-check.html` (it is
deliberately not committed — see §11) and open it in a browser. It links
`tokens.css`, `base.css` and `ads.css` from `assets/` and carries only the page
skeleton of §4 in its own `<style>` block.

### 10.2 Measure every clearance and prove no overlap

Open the Console at each width and run:

```js
const f = document.getElementById('game-frame').getBoundingClientRect();
for (const el of document.querySelectorAll('.ad-slot')) {
  const cs = getComputedStyle(el);
  if (cs.display === 'none') continue;
  const a = el.getBoundingClientRect();
  console.log(el.id, {
    box: `${Math.round(a.width)}x${Math.round(a.height)}`,
    gapAbove: Math.round(f.top - a.bottom),   // slot is above the frame
    gapBelow: Math.round(a.top - f.bottom),   // slot is below the frame
    gapLeft:  Math.round(f.left - a.right),   // slot is left of the frame
    gapRight: Math.round(a.left - f.right),   // slot is right of the frame
    overlaps: !(a.right  <= f.left || a.left >= f.right ||
                a.bottom <= f.top  || a.top  >= f.bottom),
    position: cs.position,
  });
}
```

The clearance for a slot is its **one positive gap**; the other three are
negative and meaningless. Check against §5.2:

| Width | Slot | `box` | clearance | `overlaps` | `position` |
| --- | --- | --- | --- | --- | --- |
| 1280 | `ad-header-leaderboard` | `728x90` | `gapAbove` ≥ 120 | `false` | `static` |
| 1280 | `ad-rail-rectangle` | `300x250` | `gapRight` = 32 | `false` | `static` |
| 1280 | `ad-incontent-banner` | `728x90` | `gapBelow` ≥ 72 | `false` | `static` |
| 375 | `ad-mobile-top` | `320x100` | `gapAbove` = 24 | `false` | `static` |
| 375 | `ad-mobile-after-info` | `300x250` | `gapBelow` ≥ 64 | `false` | `static` |

Any `overlaps: true`, any `position` other than `static`, or any clearance below
24 fails the review. At 375 px the three desktop slots must be absent from the
output, and at 1280 px the two mobile slots must be absent — that is the "no
sidebar on mobile, exactly two banners" check.

If you prefer the DevTools box model: select the slot in Elements, read
`margin` in the Computed pane — the frame-facing side reads `24px` on all five
slots — then hover the frame to see the gap highlighted.

### 10.3 Confirm no shift

With the Console still open, reload the page with this running:

```js
let cls = 0;
new PerformanceObserver(l => {
  for (const e of l.getEntries()) if (!e.hadRecentInput) cls += e.value;
}).observe({ type: 'layout-shift', buffered: true });
setTimeout(() => console.log('CLS', cls), 3000);
```

Expected: **`CLS 0`** at both widths. Lighthouse's CLS figure for the page must
also be `0`.

Then prove the reservation holds when something arrives. In the Console:

```js
const slot = document.getElementById('ad-rail-rectangle');
slot.insertAdjacentHTML('beforeend',
  '<div style="width:300px;height:250px;background:#ccc"></div>');
```

Nothing on the page may move, and the CLS figure must stay `0`. That is the same
event an ad creative would cause at M6.

### 10.4 Confirm the rules are not quietly broken

Run from the repository root:

```sh
# 1. Nothing is lifted out of normal flow.
grep -nE '^[[:space:]]*(position:[[:space:]]*(sticky|fixed)|float:[[:space:]]*(left|right)|z-index:[[:space:]]*[0-9]|transform:[[:space:]]*(translate|scale|rotate|matrix|skew|perspective))' \
  docs/design/prototypes/assets/ads.css

# 2. No ad network, script, iframe, font or network reference of any kind.
grep -nEi 'doubleclick|googlesyndication|adsbygoogle|gpt\.js|<script|<iframe|@font-face|url\([^)]*:' \
  docs/design/prototypes/assets/ads.css

# 3. No colour literal and no negative margin — every value is a token.
grep -nE '#[0-9a-fA-F]{3,8}|^[[:space:]]*[a-z-]*margin[a-z-]*:[^;]*-[0-9]' \
  docs/design/prototypes/assets/ads.css
```

All three must return **nothing**.

Checks 1 and 3 are anchored to the start of a declaration line on purpose. The
scope comment at the top of `ads.css` spells the forbidden declarations out in
prose — `position: sticky`, `position: fixed` — so an unanchored grep matches
the rule that forbids them and wastes a reviewer's time. A match on an actual
declaration line is a failure; the comment is not.

### 10.5 Also check, by eye

- Both themes (`data-theme="dark"` on `<html>`) — the slot reads as a reserved
  box in each, and nothing about its size changes.
- Tab through the page: the header banner is skippable via the skip link, no
  slot takes focus, and no focused control is ever covered.
- No horizontal scrollbar at 375, 768 or 1280 px.

---

## 11. Handoff, and one open item

**For M1-03 (wireframes)** — the play-page wireframe inherits §4 and §9. Two
things are binding rather than suggestive: the play-grid `column-gap` is at
least `var(--space-ad-clearance)` (§5.3), and each page column is a flex column
without `gap` (§4.3).

**For M1-04 (components)** — the `ad-slot` component is already fully specified
by `ads.css`; M1-04 should not restyle it. `game-meta` is M1-04's, and its
presence between the frame and the lower banner is load-bearing here.

**For M6 (ads integration)** — a creative is drawn inside an already-sized box
and never resizes it; a slot narrower than its format width requests no
creative; no script loads before consent; with consent rejected the box stays
reserved and empty.

**Open item for the Board.** Mobile currently carries ads on the play page only
(§3). If a mobile slot is wanted on the home page or the leaderboards,
`requirements.md` §5.1 has to name it first — this spec will not add a position
that document does not allow. Raised through `project-manager`.

**A note on the check page.** GAM-23 owns exactly two files, this one and
`ads.css`, and the Task says the PR touches only those. The runnable
demonstration is therefore shipped as §12 rather than as a committed
`.html` file. If the Board or the reviewer would rather have it tracked in the
repository, say so on the Task and it will be added as
`docs/design/prototypes/ad-slots-check.html`.

---

## 12. Reference check page

Self-contained. The `<style>` block holds **only** the page skeleton of §4 — no
ad styling, no colours, no sizes of its own; all of that comes from the three
linked stylesheets. No script, no iframe, no network request.

```html
<!doctype html>
<html lang="vi">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Ad-slot clearance check — Soucean Game (GAM-23)</title>
<link rel="stylesheet" href="assets/tokens.css">
<link rel="stylesheet" href="assets/base.css">
<link rel="stylesheet" href="assets/ads.css">
<style>
  /* Page skeleton only — section 4 of docs/design/ad-slots.md.
     Every value is a token from tokens.css. */
  .site-header {
    padding-block: var(--space-lg);
    background-color: var(--color-surface);
  }
  .shell {
    max-inline-size: calc(var(--layout-max-width) + 2 * var(--layout-gutter));
    padding-inline: var(--layout-gutter);
    margin-inline: auto;
  }
  .page-main { padding-block: var(--space-3xl); }
  @media (min-width: 768px) { .page-main { padding-block: var(--space-4xl); } }

  /* Flex columns with NO gap: margins of flex items never collapse, so the
     ad slot's own clearance is always added, never absorbed. Section 4.3. */
  .play-main,
  .play-rail { display: flex; flex-direction: column; }

  .play-grid { display: block; }
  @media (min-width: 768px) {
    .play-grid {
      display: grid;
      /* At least var(--space-ad-clearance). Section 5.3. */
      grid-template-columns: minmax(0, 1fr) 300px;
      column-gap: var(--space-2xl);
    }
  }

  .page-title { margin-block-end: var(--space-2xl); }

  /* Stand-ins for M1-03/M1-04 content. */
  .game-frame {
    aspect-ratio: 16 / 9;
    background-color: var(--color-sunken);
    border: 1px solid var(--color-border-strong);
    border-radius: var(--radius-md);
    display: grid;
    place-items: center;
  }
  .game-meta,
  .weekly-board {
    padding: var(--space-lg);
    background-color: var(--color-surface);
    border: 1px solid var(--color-border);
    border-radius: var(--radius-md);
  }
  .game-meta { margin-block-start: var(--space-3xl); }
  @media (min-width: 768px) {
    .game-meta { margin-block-start: var(--space-4xl); }
  }
  .weekly-board { margin-block-start: var(--space-3xl); }
</style>
</head>
<body>

<a class="skip-link" href="#main">Bỏ qua tới nội dung chính</a>

<header class="site-header">
  <div class="shell">
    <strong>Soucean Game</strong>
    <aside class="ad-slot ad-slot--header-leaderboard"
           id="ad-header-leaderboard" aria-label="Quảng cáo">
      <span class="ad-slot__label">Quảng cáo · 728 × 90</span>
    </aside>
  </div>
</header>

<main id="main" class="page-main">
  <div class="shell">
    <h1 class="page-title">Tên trò chơi</h1>

    <div class="play-grid">
      <div class="play-main">

        <!-- Mobile only: one banner above the frame. -->
        <aside class="ad-slot ad-slot--mobile-top"
               id="ad-mobile-top" aria-label="Quảng cáo">
          <span class="ad-slot__label">Quảng cáo · 320 × 100</span>
        </aside>

        <!-- The ad slots are SIBLINGS of the frame, never descendants. -->
        <div class="game-frame" id="game-frame">game frame (16:9)</div>

        <div class="game-meta">
          <h2>Cách chơi</h2>
          <p>Điều khiển và hướng dẫn chơi. Khối này luôn nằm giữa khung trò
             chơi và biểu ngữ bên dưới.</p>
        </div>

        <!-- Desktop only: after the game info, never directly under the frame. -->
        <aside class="ad-slot ad-slot--incontent-banner"
               id="ad-incontent-banner" aria-label="Quảng cáo">
          <span class="ad-slot__label">Quảng cáo · 728 × 90</span>
        </aside>

        <!-- Mobile only: after the game info. -->
        <aside class="ad-slot ad-slot--mobile-after-info"
               id="ad-mobile-after-info" aria-label="Quảng cáo">
          <span class="ad-slot__label">Quảng cáo · 300 × 250</span>
        </aside>

      </div>

      <div class="play-rail">
        <!-- Desktop only: top of the rail, above the weekly board. Not sticky. -->
        <aside class="ad-slot ad-slot--rail-rectangle"
               id="ad-rail-rectangle" aria-label="Quảng cáo">
          <span class="ad-slot__label">Quảng cáo · 300 × 250</span>
        </aside>

        <div class="weekly-board">
          <h2>Bảng xếp hạng tuần</h2>
        </div>
      </div>
    </div>
  </div>
</main>

</body>
</html>
```

Reading order and visual order agree at every width. `ad-mobile-top` is the
first child of `.play-main` and sits above the frame in both DOM and visual
order; `ad-mobile-after-info` follows the game-meta block in both; the rail
comes after the whole main column in DOM and is placed in column 2 by the grid.
That is why the page needs no `order` property anywhere, and why a screen
reader and a sighted user meet the slots in the same sequence.

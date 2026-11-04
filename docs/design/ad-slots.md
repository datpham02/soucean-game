# Ad-slot layout — Soucean Game

**Status:** Normative. Milestone M1 (Design), Task GAM-23 (M1-02). Owner:
`ui-designer`, through `project-manager`. Last updated: 2026-11-04.

> **Board decision of 2026-11-04.** The side rail now turns on at **1024px**,
> not 768px, and between 768px and 1023px the game frame takes the full content
> width. `DESIGN.md` carries the matching M1-01 amendment and the derivation of
> the 1024px value. §4.4 is the new geometry; §4.5 records what the decision
> replaced. Open item 2 of §11 is closed by it.

This document decides, **once**, how ad slots sit on every page of Soucean Game:
how many there are, how big they are, where they go, and how far they stay from
the game. No later mockup, wireframe or component design re-decides any of it.

It is downstream of three documents and does not contradict them:

| Source | What it fixes | What this document does |
| --- | --- | --- |
| [`../requirements.md`](../requirements.md) §5 | the allowed positions, the ≥ 24 px margin, the no-overlap and no-sticky rules | implements them as geometry |
| [`DESIGN.md`](DESIGN.md) (M1-01) | `--space-ad-clearance`, the 375 / 768 / **1024** / 1280 breakpoints, gutters, the `ad-slot` component | consumes them; redefines none |
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
- any new colour, spacing step or breakpoint — all three are M1-01's. The
  1024px `wide` switch is **not** an exception to this: it is an amendment *to*
  M1-01, decided by the Board and recorded in `DESIGN.md`. This document
  consumes it like any other foundation token and defines nothing.

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

Five slots, three creative formats, **three layout bands**. **A slot that is not
in this table is a defect** (`requirements.md` §5.1).

The bands come from M1-01's two switches and are named the same way everywhere:

| Band | Range | Play page |
| --- | --- | --- |
| **mobile** | < 768 px | single column, mobile type scale, 16 px gutter |
| **compact** | 768–1023 px | single column, desktop type scale, 32 px gutter, **no rail** |
| **wide** | ≥ 1024 px | game frame + 300 px side rail |

| Id | CSS class | Format | Fixed size | Pages | Bands |
| --- | --- | --- | --- | --- | --- |
| `ad-header-leaderboard` | `.ad-slot--header-leaderboard` | leaderboard | **728 × 90** | every page, in the site header | compact + wide (≥ 768 px) |
| `ad-incontent-banner` | `.ad-slot--incontent-banner` | leaderboard | **728 × 90** | play page, after the game info | compact + wide (≥ 768 px) |
| `ad-rail-rectangle` | `.ad-slot--rail-rectangle` | medium rectangle | **300 × 250** | play page `/games/<game_id>`, top of the right-hand rail, above the weekly board | **wide only (≥ 1024 px)** |
| `ad-after-info-rectangle` | `.ad-slot--after-info-rectangle` | medium rectangle | **300 × 250** | play page, after the game info | **mobile + compact (< 1024 px)** |
| `ad-mobile-top` | `.ad-slot--mobile-top` | large mobile banner | **320 × 100** | play page, above the game frame | mobile only (< 768 px) |

So the play page carries **exactly three** slots in the compact and wide bands
and **exactly two** in the mobile band. Every other page carries **one** above
768 px (the header leaderboard) and **none** below it.

### The 300 × 250 rectangle is one position that moves, not two slots

`ad-rail-rectangle` and `ad-after-info-rectangle` are the same creative format
and are **mutually exclusive**: the rail one is on the page only at ≥ 1024 px,
the after-info one only below it. Exactly one 300 × 250 box exists on the play
page at every width.

They are two classes rather than one because they are in different places in the
DOM and have different margins — the rail one is top-aligned with the frame and
takes its horizontal clearance from the grid gap (§5.3), the after-info one is
centred in the content column with its own margins on all sides. Giving one
class two layouts would mean overriding margins per band, which is exactly the
kind of conditional geometry §8.1 exists to avoid.

**Nothing is lost below 1024 px.** This was the Board's instruction of
2026-11-04: the rail rectangle *moves below the game info*, it is not deleted.
Ad inventory per page is identical in the compact and wide bands.

### Why these sizes

- **728 × 90** and **300 × 250** come straight from `requirements.md` §5.1. The
  in-content banner is specified there only as "fixed, reserved"; it is fixed
  here at 728 × 90 so the desktop page needs **two** creative sizes, not three.
- **320 × 100** is the mobile banner above the frame. It fits the 343 px mobile
  content column with room to spare, and it is short enough to keep the game
  frame near the top of a phone screen. It exists only in the mobile band: from
  768 px up the header leaderboard is already the banner above the frame, and a
  second one there would be ad-stacking.
- **300 × 250** is reused for the after-info slot, so the whole site needs only
  two creative sizes in total — and so the rectangle can move between the rail
  and the after-info position without M6 needing a third creative.

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

Three bands, in this section:

| | Band | Where |
| --- | --- | --- |
| §4.1 | **wide**, at the 1280 px design width | below |
| §4.2 | **mobile**, at the 375 px design width | below |
| §4.3 | the structural rule all three bands share | below |
| §4.4 | **compact**, 768–1023 px | below |
| §4.5 | why the rail starts at 1024 px | below |

### 4.1 Wide band — viewport 1280 px

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

This two-column layout exists **only at ≥ 1024 px**. The same arithmetic at
narrower widths is in §4.4 and §4.5.

### 4.2 Mobile band — viewport 375 px

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
   │ ad-after-info-rectangle     │  300 × 250, centred in 343
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

A flex column has one side effect this rule has to pay for: the block axis
becomes the flex main axis, where items shrink by default. `ads.css` pays it
with `flex: none` on every slot, so the reserved height holds however the column
is sized. See §8.1 — it is the declaration that makes the reservation real.

### 4.4 Compact band — 768 to 1023 px

**No side rail.** The game frame takes the whole content column, and the
300 × 250 rectangle sits after the game info, in the position
`requirements.md` §5.1 names for mobile. The page is one column, exactly as in
the mobile band; what changes at 768 px is the type scale, the gutter and which
slots are on the page — not the number of columns.

```text
viewport 768 … 1023
├─32─┬────────── content column 704 … 959 ──────────┬─32─┤

┌───────────────────── site-header ─────────────────────┐
│          ┌──────────────────────────┐                 │
│          │ ad-header-leaderboard    │  728 × 90       │
│          └──────────────────────────┘                 │
└───────────────────────────────────────────────────────┘
                      ▲ ≥ 120 px clearance to the frame
   <h1> game title
   ┌───────────────────────────────────────────────────┐
   │                                                   │
   │   game-frame  704 × 396  …  959 × 539  (16:9)     │
   │                                                   │
   └───────────────────────────────────────────────────┘
                 ▼ 48 px
   game-meta: controls · how to play
                 ▼ ≥ 72 px clearance to the frame
   ┌─────────────────────────────┐
   │ ad-after-info-rectangle     │  300 × 250, centred
   └─────────────────────────────┘
                 ▼ 48 px
   ┌──────────────────────────┐
   │ ad-incontent-banner      │  728 × 90, centred
   └──────────────────────────┘

   weekly leaderboard
```

| Measure | At 768 px | At 1023 px | How |
| --- | --- | --- | --- |
| Shell content | 704 px | 959 px | viewport − 2 × 32 (`--layout-gutter`, desktop) |
| Main column | **704 px** | **959 px** | the full content column — no rail is subtracted |
| Game frame | **704 × 396** | **959 × 539** | main column at 16:9 |
| `ad-header-leaderboard` | 704 × 90 | 728 × 90 | `min(728px, 100%)`; reaches its format at 792 px |
| `ad-after-info-rectangle` | 300 × 250 | 300 × 250 | fits the column at every width in the band |
| `ad-incontent-banner` | 704 × 90 | 728 × 90 | `min(728px, 100%)`; reaches its format at 792 px |

Compare the "Game frame" row with the table in §4.5: at 768 px the frame is
**704 × 396 instead of 372 × 209** — 1.9× the width and 3.6× the area, for the
same three ad slots on the page. That is what the Board's decision bought.

**Two ad slots are adjacent in this band**, the rectangle and the in-content
banner, separated by 48 px (24 + 24, and §4.3 guarantees those margins are
additive). That is deliberate and it is the price of keeping the inventory
whole: both sit *after* the game info, neither is adjacent to the frame, and the
`game-meta` block separates both from it. Nothing may be inserted between them
that would put an ad closer to the frame than §5.2 allows.

Below 792 px the two leaderboard slots measure narrower than their 728 px
format, so under the §8.1 rule they **request no creative at M6**. Between
792 px and 1023 px both measure a full 728 px — which the old 768 px rail layout
never achieved for the in-content banner at any width below 1124 px.

### 4.5 Why the rail starts at 1024 px — and what that replaced

The derivation of 1024 px is in `DESIGN.md`, "Why `wide` is 1024px". In short:
with a rail the main column is `viewport − 396`, and the frame stays the larger
element only when the main column is at least twice the ad's width
(`main ≥ 600` → viewport ≥ 996 px). 1024 px is the nearest standard viewport
width above that floor; there the main column is 628 px and the frame is
**628 × 353**, 2.09× the ad's width and 1.41× its height.

For the record, this is the layout the Board's decision replaced — the rail
starting at 768 px, as this spec originally had it:

| Measure | At 768 px, **rejected layout** |
| --- | --- |
| Main column | 372 px (704 − 300 − 32) |
| Game frame | **372 × 209** |
| `ad-rail-rectangle` | 300 × 250 — 81 % of the frame's width, and **taller than the frame** |
| `ad-incontent-banner` | 372 × 90 — no creative at M6 |

No rule was broken by it: the clearance was 32 px, above the floor, and nothing
shifted. It was a *proportion* failure — `requirements.md` §5.2 calls 24 px "the
floor, not the target", and an ad that out-measures the game beside it inverts
what this site is for. The Board's ruling of 2026-11-04 was that the game is the
centre of the site and a 372 × 209 frame is too small, so M1-01 gains a
breakpoint rather than this spec accepting the band.

One consequence worth naming for M1-03: the rail's `wide` layout now satisfies
§5.1's single-axis adjacency claim at **every** width it exists at. The rail ad
is 250 px tall and the frame is ≥ 353 px tall from 1024 px up, so the ad always
falls entirely within the frame's height and only the horizontal distance is
meaningful. Under the rejected layout that was false at 768 px, where the ad was
taller than the frame.

**A value the Board may want to revisit, not a blocker.** The in-content banner
does not reach its full 728 px format in the wide band until 1124 px
(`728 + 32 + 300 + 64`), so between 1024 and 1123 px it measures 628–727 px and
requests no creative at M6. Setting `wide` at 1124 px instead of 1024 px would
close that gap, at the cost of a compact band stretching to 1123 px — well past
the 768–1000 px band the Board described. 1024 px is the value this spec
implements; moving it is a one-line change in `DESIGN.md`, `tokens.css` and
`ads.css` §4 if the Board prefers.

---

## 5. Clearance — the 24 px rule

### 5.1 How clearance is measured

Clearance is the **empty distance between the ad's border box and the game
frame's border box, on the axis along which the two are adjacent.**

No slot in this inventory is adjacent to the frame on more than one axis:

- the four banner and rectangle slots in normal flow sit **above or below** the
  frame and overlap it in horizontal extent, so only the vertical distance is
  meaningful;
- the rail slot sits **beside** the frame, and its 250 px height falls entirely
  within the frame's height — 353 px at 1024 px, 488 px at 1280 px, and larger
  at every width in between — so only the horizontal distance is meaningful.

The second bullet is true at **every** width the rail exists at, which is one of
the reasons `wide` starts at 1024 px (§4.5). Rule 1 of §2 — "on every side the
two are adjacent" — is therefore satisfied by one number per slot.

### 5.2 Measured values, per slot, per band

| Slot | Band | Adjacency | Clearance | Arithmetic |
| --- | --- | --- | --- | --- |
| `ad-header-leaderboard` | compact + wide | frame is below | **≥ 120 px** | 24 slot `margin-block-end` + 16 header padding + 48 main padding + 32 title margin, plus the title's own height |
| `ad-rail-rectangle` | wide | frame is to the inline-start | **32 px exactly** | play-grid `column-gap: var(--space-2xl)`; the binding minimum is `var(--space-ad-clearance)` = 24 |
| `ad-incontent-banner` | wide | frame is above | **≥ 72 px** | 48 frame→game-meta (`--space-4xl`) + 24 slot `margin-block-start`, plus the game-meta block's height |
| `ad-incontent-banner` | compact | frame is above | **≥ 370 px** | the ≥ 72 above, plus the rectangle's 250 height and the 24 + 24 between the two slots |
| `ad-after-info-rectangle` | compact | frame is above | **≥ 72 px** | 48 frame→game-meta (`--space-4xl`) + 24 slot `margin-block-start`, plus the game-meta block's height |
| `ad-after-info-rectangle` | mobile | frame is above | **≥ 64 px** | 40 frame→game-meta (`--space-3xl`) + 24 slot `margin-block-start`, plus the game-meta block's height |
| `ad-mobile-top` | mobile | frame is below | **24 px exactly** | 24 slot `margin-block-end` = `var(--space-ad-clearance)` |

Every number above is a token, never a literal. `ad-mobile-top` is the tight
case and sits exactly on the floor; every other slot has room above it. The
compact band is the most generous of the three — the rail ad's 32 px horizontal
clearance is the smallest number anywhere above 768 px, and it is in the wide
band.

No ad is directly adjacent to the game's controls area: the `game-meta` block
that carries the controls always sits between the frame and the slot below it
(`requirements.md` §5.2).

### 5.3 Which clearance ads.css owns, and which the page owns

`ads.css` puts `margin-block: var(--space-ad-clearance)` on **every** slot. That
is a guarantee the stylesheet makes on its own, in any layout: every clearance
in §5.2 except one cannot fall below 24 px no matter what page wraps them.

The **one exception is `ad-rail-rectangle`'s horizontal clearance.** The rail
column is exactly 300 px wide — the width of the creative — so an inline margin
on the slot would make its box 324 px and overflow the column, pushing the
layout rather than protecting it. `ads.css` therefore sets `margin-inline: 0` on
that slot, and the horizontal clearance is the play-page grid's `column-gap`.

Since the Board decision that override lives **inside the `min-width: 1024px`
block in `ads.css` §4**, next to the `display: flex` that puts the slot on the
page. The two belong together: the slot exists only in the wide band, and the
only reason to zero its inline margin is that in the wide band it is in a
300 px column. It is written `.ad-slot.ad-slot--rail-rectangle` so it beats the
base `margin-inline: auto` on specificity, not on where it happens to sit in the
file. `ad-after-info-rectangle`, which carries the same format below 1024 px,
keeps the normal `margin-inline: auto` and centres itself in the content column.

That makes one binding requirement on M1-03:

> **The play-page grid's `column-gap` is at least `var(--space-ad-clearance)`.**
> The reference layout uses `var(--space-2xl)` (32 px). It may be increased. It
> may never go below 24 px, and it may never be zero. The requirement applies in
> the **wide band only** — below 1024 px the play page has no second column, so
> there is no column gap to get wrong.

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
- The reference geometry in §4 is overlap-free by arithmetic: in the wide band
  at 1280 px the rail slot's inline-start edge is 900 px from the content-column
  origin and the frame's inline-end edge is at 868 px; the banner slots are
  separated from the frame on the block axis by the distances in §5.2. In the
  compact and mobile bands nothing is beside the frame at all, so every
  separation is a block-axis one and the arithmetic is a single column of sums.

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
  flex: none;                         /* ...and unconditional under §4.3 */
}
```

- **The block size is an explicit literal pixel value.** It is applied by the
  stylesheet, so it is in force at the first paint — before any creative, before
  any consent decision, before any network request. There is nothing to load and
  therefore nothing to resize. Everything below the slot is already in its final
  position.
- **`flex: none` is what makes that unconditional rather than merely intended.**
  §4.3 requires every page column to be a flex column, and in a column flex
  container the block axis *is* the main axis, where a flex item defaults to
  `flex-shrink: 1`. So a `block-size` alone is only a *preferred* height: give an
  ancestor column a definite height smaller than its content — a play view at
  `height: 100dvh`, or a rail matched to the 488 px frame — and the browser
  compresses the slot below its reservation, after which the slot's height tracks
  its siblings' heights. That is precisely the reflow this document exists to
  prevent, and it is invited by the structure this document itself mandates.
  `flex: none` is `flex-grow: 0; flex-shrink: 0; flex-basis: auto`, so the used
  main size stays `block-size` whatever the free space is. It is inert when the
  slot is a grid item or an ordinary block. No page should ever want an ad slot
  to flex; **M1-03 must not remove it.**
- **The 1 px edge is an outline, not a border.** With `box-sizing: border-box` a
  border is subtracted from the reserved box, so a 728 × 90 slot would offer a
  726 × 88 content box and clip the creative by a pixel a side. An outline is
  drawn outside the layout model, so the content box equals the format size
  exactly; `outline-offset: -1px` keeps it inside the box so it does not eat into
  the clearance either. Neither choice can shift the page — the outer box is
  identical — but the outline is the one that is still correct at M6.
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
  format width requests no creative. Three widths where that bites are on the
  record: both leaderboard slots below 792 px (§4.4), and `ad-incontent-banner`
  between 1024 and 1123 px, where the rail has taken 332 px off the main column
  (§4.5).
- **Changing band is a resize, not a shift.** Crossing 1024 px swaps the rail
  slot for the after-info slot and re-sizes the game frame, which looks like a
  large movement and is not counted: layout shift caused by a viewport resize is
  excluded from the CLS metric (§8.2). Within a band nothing about a slot's box
  depends on anything that loads. The two classes exist precisely so that
  neither slot has to change its own geometry when the band changes — each is
  simply on the page or not.

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
| Viewport resize across 768 px or 1024 px | slots swap and, at 1024 px, the frame resizes — but shift from a viewport resize is excluded from the CLS metric | not counted |

The third and fourth rows are the ones that usually break: a slot that is
reserved only *after* the ad network answers still shifts the page when it is
blocked. Here the box is reserved by the page's own stylesheet and never depends
on an answer.

---

## 9. Stacking, DOM order and accessibility

**The DOM is identical in all three bands.** Nothing is reordered, moved or
re-parented by a media query: the bands differ only in which slots are
`display: none`. That is why no `order` property appears anywhere in this spec
and why reading order and visual order agree at every width — the three listings
below are the same document, with different slots hidden.

### 9.1 Wide play page — ≥ 1024 px

```
site-header
  wordmark · nav · theme-toggle
  ad-header-leaderboard
main
  h1 game title
  play-grid                              ← two columns
    main column:  game-frame → game-meta → ad-incontent-banner
    rail column:  ad-rail-rectangle → weekly leaderboard
footer
```

The rail is **second in DOM order**, after the whole main column, and the grid
places it in column 2.

### 9.2 Compact play page — 768 to 1023 px

```
site-header
  wordmark · nav · theme-toggle
  ad-header-leaderboard
main
  h1 game title
  play-grid                              ← one column: the two groups stack
    main column:  game-frame → game-meta
                  → ad-after-info-rectangle   300 × 250
                  → ad-incontent-banner       728 × 90
    rail column:  weekly leaderboard          ← the rail slot is absent here
footer
```

The "rail column" is still the same element; with `display: block` on the grid
it simply stacks below the main column, carrying only the weekly leaderboard.
This is the Board's "the rail rectangle moves below the game info": the
rectangle is now `ad-after-info-rectangle`, in the main column, where mobile
already put it.

### 9.3 Mobile play page — exactly two banners, < 768 px

```
site-header
  wordmark · nav · theme-toggle          ← no ad
main
  h1 game title
  ad-mobile-top              320 × 100
  game-frame
  game-meta                  controls · how to play
  ad-after-info-rectangle    300 × 250
  weekly leaderboard
footer
```

One banner above the game frame, one after the game info. No sidebar, no rail,
no third slot — `ad-header-leaderboard`, `ad-incontent-banner` and
`ad-rail-rectangle` are all `display: none` below 768 px, so they cannot
reappear by accident.

### 9.4 Accessibility rules for a slot

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

**Six widths.** The two M1-01 design widths, plus the four the Board named when
it added the `wide` breakpoint on 2026-11-04 — the band edges are where this
geometry can go wrong, so they are checked, not assumed:

| Width | Band | What it proves |
| --- | --- | --- |
| **375** | mobile | the mobile design width; the 24 px floor case |
| **768** | compact | the **lower edge** of the compact band — the old failure point |
| **900** | compact | mid-band; both leaderboards at their full 728 px |
| **1000** | compact | the **last width with no rail**; the frame at its widest single-column size |
| **1024** | wide | the **first width with a rail** — the Board's new breakpoint |
| **1280** | wide | the desktop design width |

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
negative and meaningless. Check against §5.2 — and note that the number of lines
printed is itself part of the check: **two at 375 px, three at every other
width**.

| Width | Slot | `box` | clearance | `overlaps` | `position` |
| --- | --- | --- | --- | --- | --- |
| 375 | `ad-mobile-top` | `320x100` | `gapAbove` = 24 | `false` | `static` |
| 375 | `ad-after-info-rectangle` | `300x250` | `gapBelow` ≥ 64 | `false` | `static` |
| 768 | `ad-header-leaderboard` | `704x90` | `gapAbove` ≥ 120 | `false` | `static` |
| 768 | `ad-after-info-rectangle` | `300x250` | `gapBelow` ≥ 72 | `false` | `static` |
| 768 | `ad-incontent-banner` | `704x90` | `gapBelow` ≥ 370 | `false` | `static` |
| 900 | `ad-header-leaderboard` | `728x90` | `gapAbove` ≥ 120 | `false` | `static` |
| 900 | `ad-after-info-rectangle` | `300x250` | `gapBelow` ≥ 72 | `false` | `static` |
| 900 | `ad-incontent-banner` | `728x90` | `gapBelow` ≥ 370 | `false` | `static` |
| 1000 | `ad-header-leaderboard` | `728x90` | `gapAbove` ≥ 120 | `false` | `static` |
| 1000 | `ad-after-info-rectangle` | `300x250` | `gapBelow` ≥ 72 | `false` | `static` |
| 1000 | `ad-incontent-banner` | `728x90` | `gapBelow` ≥ 370 | `false` | `static` |
| 1024 | `ad-header-leaderboard` | `728x90` | `gapAbove` ≥ 120 | `false` | `static` |
| 1024 | `ad-rail-rectangle` | `300x250` | `gapRight` = 32 | `false` | `static` |
| 1024 | `ad-incontent-banner` | `628x90` | `gapBelow` ≥ 72 | `false` | `static` |
| 1280 | `ad-header-leaderboard` | `728x90` | `gapAbove` ≥ 120 | `false` | `static` |
| 1280 | `ad-rail-rectangle` | `300x250` | `gapRight` = 32 | `false` | `static` |
| 1280 | `ad-incontent-banner` | `728x90` | `gapBelow` ≥ 72 | `false` | `static` |

Any `overlaps: true`, any `position` other than `static`, or any clearance below
24 fails the review.

**The band checks, which are the point of the new breakpoint:**

- At **375** the three ≥ 768 slots are absent — "no sidebar on mobile, exactly
  two banners".
- At **768, 900 and 1000** `ad-rail-rectangle` is **absent** and
  `ad-after-info-rectangle` is **present**. `ad-mobile-top` is absent.
- At **1024 and 1280** exactly the reverse: the rail is present and the
  after-info rectangle is absent. Exactly one 300 × 250 box at every width.
- Also measure the frame itself. `document.getElementById('game-frame')
  .getBoundingClientRect()` must read **704 × 396** at 768, **936 × 527** at
  1000, and **628 × 353** at 1024 — the shrink at the breakpoint is expected and
  is what buys the rail its room (§4.5).

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

Finally, prove the reservation survives a squeezed flex column — the failure
`flex: none` exists to stop (§8.1). Both page columns are flex columns (§4.3),
so run the check **in each band**, against whichever column actually holds a
300 × 250 slot at that width.

At **1024 and 1280** (wide — the rail holds it):

```js
document.querySelector('.play-rail').style.height = '300px';  // < the rail's content
console.log(document.getElementById('ad-rail-rectangle').getBoundingClientRect().height);
```

At **768, 900 and 1000** (compact — the main column holds it):

```js
document.querySelector('.play-main').style.height = '400px';  // < the column's content
console.log(document.getElementById('ad-after-info-rectangle').getBoundingClientRect().height);
```

Expected in both cases: **`250`**. Reset with `.style.height = ''`.

The forced height has to be smaller than the column's content for the check to
mean anything — a squeeze that leaves positive free space passes whether
`flex: none` is there or not. If you want to see the failure, delete
`flex: none` from `ads.css` and re-run: the slot will come back well under 250.
The exact compressed number depends on the column's other children, so don't
assert it; **250 is the only number that matters.**

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
- No horizontal scrollbar at 375, 768, 900, 1000, 1024 or 1280 px. 768 px and
  1024 px are the ones that matter: at 768 the 728 px leaderboard meets a 704 px
  column, and at 1024 the rail appears and takes 332 px out of the main column.
- Drag the viewport slowly through 1024 px. The rail should appear and the
  after-info rectangle disappear in the same frame, with exactly one 300 × 250
  box visible throughout — never two, never none.

---

## 11. Handoff, and one open item

**For M1-03 (wireframes)** — the play-page wireframe inherits §4 and §9. Three
things are binding rather than suggestive: the play-grid becomes two columns at
**1024 px, not 768 px** (§4.4, §4.5), its `column-gap` is at least
`var(--space-ad-clearance)` (§5.3), and each page column is a flex column
without `gap` (§4.3).

The wireframe needs **three** play-page states, not two: wide (1280), compact
(768–1023) and mobile (375). The compact state is the one to draw carefully —
it is new, it is where the game frame is widest relative to the viewport, and
it is the one that carries two ad slots in sequence after the game info (§4.4).

**For M1-04 (components)** — the `ad-slot` component is already fully specified
by `ads.css`; M1-04 should not restyle it. `game-meta` is M1-04's, and its
presence between the frame and the lower banner is load-bearing here.

**For M6 (ads integration)** — a creative is drawn inside an already-sized box
and never resizes it; a slot narrower than its format width requests no
creative; no script loads before consent; with consent rejected the box stays
reserved and empty. Two ids carry the 300 × 250 format and they are mutually
exclusive by viewport width (§3), so M6 must target **whichever of the two is
on the page**, not assume the rail one — and must never request creatives for
both.

**For M1-03 again, explicitly:** `flex: none` on `.ad-slot` is load-bearing, not
housekeeping. The wireframe may give the play view any height it likes *because*
of it (§8.1).

**Open items for the Board.** One open, one closed:

1. **Mobile carries ads on the play page only** (§3). **Open.** If a mobile slot
   is wanted on the home page or the leaderboards, `requirements.md` §5.1 has to
   name it first — this spec will not add a position that document does not
   allow.
2. ~~**The layout between 768 px and roughly 1000 px.**~~ **Closed by the Board
   on 2026-11-04.** The ruling: the game is the centre of the site and a
   372 × 209 frame is too small, so M1-01 gains a third breakpoint rather than
   this spec accepting the band. `wide` is **1024 px**, derived in `DESIGN.md`
   and recorded in its amendments table; the rail exists only above it; below
   it the 300 × 250 rectangle moves to the after-info position and the game
   frame takes the full content column. §4.4 is the new geometry, §4.5 keeps the
   rejected layout on the record. §4.5 also names one value the Board may wish
   to revisit later — 1124 px would additionally give the in-content banner its
   full 728 px format in the wide band — but that is an improvement to consider,
   not an objection to the decision.

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

  /* One column in the mobile AND compact bands; two only in the wide band.
     1024px, not 768px — Board decision of 2026-11-04, sections 4.4 and 4.5.
     Below it .play-rail simply stacks under .play-main, carrying only the
     weekly board, because the rail ad slot is display:none there. */
  .play-grid { display: block; }
  @media (min-width: 1024px) {
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

        <!-- Below 1024px only: after the game info. This is where the rail
             rectangle lives in the mobile and compact bands. -->
        <aside class="ad-slot ad-slot--after-info-rectangle"
               id="ad-after-info-rectangle" aria-label="Quảng cáo">
          <span class="ad-slot__label">Quảng cáo · 300 × 250</span>
        </aside>

        <!-- 768px and up: after the game info, never directly under the frame. -->
        <aside class="ad-slot ad-slot--incontent-banner"
               id="ad-incontent-banner" aria-label="Quảng cáo">
          <span class="ad-slot__label">Quảng cáo · 728 × 90</span>
        </aside>

      </div>

      <div class="play-rail">
        <!-- 1024px and up only: top of the rail, above the weekly board.
             Not sticky. Absent below 1024px, where the rectangle above
             carries the same format instead. -->
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

Reading order and visual order agree at every width, in all three bands.
`ad-mobile-top` is the first child of `.play-main` and sits above the frame in
both DOM and visual order; `ad-after-info-rectangle` and then
`ad-incontent-banner` follow the game-meta block in both; the rail comes after
the whole main column in DOM and is placed in column 2 by the grid in the wide
band, or stacked below it in the other two. That is why the page needs no
`order` property anywhere, and why a screen reader and a sighted user meet the
slots in the same sequence.

**One DOM, three bands.** No element moves, is re-parented or is reordered by a
media query — the only thing a band changes is which slots are `display: none`.
That is what makes "the rail rectangle moves below the game info" cost nothing
structurally: the box below the game info is always in the markup, and above
1024 px it is simply the other one that is showing.

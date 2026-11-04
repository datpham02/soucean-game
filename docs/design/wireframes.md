# Wireframes — Soucean Game

**Status:** Normative for page structure. Milestone M1 (Design), Task GAM-24
(M1-03). Owner: `ui-designer`, through `project-manager`. Last updated:
2026-11-04.

This document indexes the low-fidelity wireframes for **every page of the
site**, names the regions on each one, and fixes the **reading order** those
regions appear in. The drawings are in
[`prototypes/wireframes/`](prototypes/wireframes/index.html) and are static HTML:
open a file in a browser and it works. No server, no build step, no dependency,
no network request.

This is the cheap round. Layout mistakes are caught here, before any
high-fidelity work starts — every mockup Task (M1-05…M1-09) waits on it.

It is downstream of three documents and contradicts none of them:

| Source | What it fixes | What this document does |
| --- | --- | --- |
| [`../requirements.md`](../requirements.md) §2, §5 | the page list, what each page holds, the ad rules | gives each page a structure and a reading order |
| [`DESIGN.md`](DESIGN.md) (M1-01) | the two design widths, the two switches, spacing, type, the component inventory | designs at those widths; invents no third |
| [`ad-slots.md`](ad-slots.md) (M1-02) | ad inventory, sizes, clearance, stacking, no-sticky | places the slots exactly as specified, and measures it |

---

## 1. Scope — and what is deliberately absent

In scope: which regions exist on each page, where they sit at each design
width, what order they are read in, which fields come from where, and where the
empty, loading and error states will go.

**Deliberately absent, and a defect if a later Task reads one into these
sheets:**

- **Final colour.** The sheets use only `surface`, `sunken`, `border`,
  `border-strong`, `text` and `text-muted`. The accent teal appears nowhere. A
  wireframe that is already coloured has made M1-05…M1-09's decisions for them.
- **Imagery and final copy.** Thumbnails are grey boxes; running text is grey
  bars. Text appears only where the text *is* the structure — a region name, a
  field label, a button label.
- **Component design.** `game-card`, `button-primary`, `leaderboard-table` and
  the rest are **named** here and **designed in M1-04**. A grey box labelled
  "Chơi" is a position, not a button.
- **The state designs themselves.** Every sheet *marks* where the empty,
  loading and error states go, and says who designs them. Drawing them is
  M1-04's and the page Tasks' work.
- **The game platform contract.** The manifest fields on the home sheet are
  tagged by *purpose* — art, title, short description, play link — not by key
  name. The manifest format is [GAM-16] and out of M1 entirely.
- **Any framework, build step, runtime dependency, package manifest, analytics,
  tracker, font file or third-party request.** M1 adds none of these.

---

## 2. How a sheet is built

One sheet per page. Each sheet stacks every view of that page:

| View | Width | Band | Where it comes from |
| --- | --- | --- | --- |
| **Mobile** | 375 px | `mobile` (< 768) | `DESIGN.md`, the mobile design width |
| **Desktop** | 1280 px | `wide` (≥ 1024) | `DESIGN.md`, the desktop design width |
| **Compact** | 768 px | `compact` (768–1023) | **play page only** — `ad-slots.md` §11 |

**Sixteen views across eight pages, plus one.** The seventeenth is the compact
play page. It is **not** a third design width: `DESIGN.md` says design at 375
and 1280 and *check* the play page at 768 and 1024. `ad-slots.md` §11 asks for
three play-page states because the compact band is new, carries two ad slots in
sequence, and is where the game frame is widest relative to the viewport. So
that one view is drawn as a **verification view** and is labelled as one on the
sheet.

### The one thing a sheet does that the product will not

A view is a **fixed-width box**: the mobile view is exactly 375 px wide
whatever window the sheet is read in. So the layout band is a property of the
*view*, not of the reader's window — which is what lets a reviewer see the real
mobile layout on a desktop monitor with no device toolbar, no resizing and no
server.

In the product the band comes from a viewport media query. On a sheet it comes
from the view's class, and
[`prototypes/wireframes/wireframe.css`](prototypes/wireframes/wireframe.css) §4
restates the band gating on that basis.

**It restates `display`, and the rail slot's two margin overrides, and nothing
else.** Every size, every block margin, `flex: none`, `position: static` and
the 24 px clearance stay in `ads.css`. That is the point: the geometry a
reviewer measures on these sheets is M1-02's geometry, not a copy of it that
can drift from it.

#### The one exception, stated: the type scale is not view-bound

`tokens.css` §7 steps five type tokens — `--text-display`, `--text-headline-lg`,
`--text-headline-md`, `--text-headline-sm` and `--text-body-lg` — in a
**viewport** `@media (min-width: 768px)` on `:root`. The shim does not restate
that step, so **in any window ≥ 768 px wide the eight mobile views render those
five tokens at their desktop sizes** — the visible case is `.wf-h1`, 18 px on a
phone and 20 px on a sheet read on a desktop monitor. It is 2 px on one element
per view and it changes no layout, no region and no reading order, so it does
not affect anything these wireframes decide. But it is a real exception to
"a view's band is a property of the view", and the next reader should know it
is there rather than discover it.

It is left as an exception on purpose. Restating `18px` on `.wf-view--mobile`
would put a type-scale literal into `wireframes/`, which the inherit table below
forbids precisely because such a literal can drift from `tokens.css`. The
type-accurate way to read a mobile view at its own type scale is a narrow
window; M2 gets it for free, because there the band comes from the viewport.

**`--layout-gutter` has the same viewport dependency and *is* handled**, which
is the deliberate half of the same problem: `wireframe.css` §2 gives each view
its own `--wf-gutter` from the spacing tokens rather than reading
`--layout-gutter`, because the gutter moves the layout and the type step does
not.

### What the sheets inherit

| Inherited | From | Rule |
| --- | --- | --- |
| Colour, type scale, spacing, radius, breakpoints, motion | [`prototypes/assets/tokens.css`](prototypes/assets/tokens.css) | no value of those scales is rewritten in `wireframes/` |
| Reset, element defaults, focus ring, table defaults | [`prototypes/assets/base.css`](prototypes/assets/base.css) | not extended |
| Ad slot boxes, sizes, clearance, no-sticky | [`prototypes/assets/ads.css`](prototypes/assets/ads.css) | every slot on these sheets is a real `.ad-slot` |

### Conventions on a sheet

| Mark | Meaning |
| --- | --- |
| Grey box | a place for content |
| Dashed edge | a region boundary |
| Numbered circle | position in the **reading order** — which is DOM order, which is tab order |
| "Trạng thái: …" | where the loading / empty / error states go, and who designs them |
| `manifest` pill | the value comes from the game's own manifest (GAM-16) |
| "đo đạc" table under a view | real numbers, measurable on that view |

The sheets are written **Vietnamese first**, like the product
(`DESIGN.md` → "Language"), including the ad label `Quảng cáo` that `ads.css`
and `ad-slots.md` §9.4 fix.

---

## 3. The index

| # | Page | Route | Sheet | Views |
| --- | --- | --- | --- | --- |
| 1 | Home / game list | `/` | [`01-home.html`](prototypes/wireframes/01-home.html) | 375 · 1280 |
| 2 | Play page | `/games/<game_id>` | [`02-play.html`](prototypes/wireframes/02-play.html) | 375 · **768** · 1280 |
| 3 | Leaderboards | `/leaderboards`, `/leaderboards/<game_id>` | [`03-leaderboards.html`](prototypes/wireframes/03-leaderboards.html) | 375 · 1280 |
| 4 | Account and profile | `/account` | [`04-account.html`](prototypes/wireframes/04-account.html) | 375 · 1280 |
| 5 | Privacy policy | `/privacy` | [`05-privacy.html`](prototypes/wireframes/05-privacy.html) | 375 · 1280 |
| 6 | Cookie banner and settings | a surface, not a route | [`06-cookie-consent.html`](prototypes/wireframes/06-cookie-consent.html) | 375 · 1280 |
| 7 | About | `/about` | [`07-about.html`](prototypes/wireframes/07-about.html) | 375 · 1280 |
| 8 | Terms | `/terms` | [`08-terms.html`](prototypes/wireframes/08-terms.html) | 375 · 1280 |

[`index.html`](prototypes/wireframes/index.html) is the contents page for the
set. Routes are `requirements.md` §2's **proposed** routes; M2 confirms them
(§8, open question 4), and nothing here depends on the final strings.

### Chrome shared by all eight pages

Every page opens with the same four and closes with the same one. They are not
repeated in each page's table below.

| Order | Region | Component | Notes |
| --- | --- | --- | --- |
| 1 | Skip link | `skip-link` | targets `#main`; **before the header ad slot**, so one keystroke passes it (`ad-slots.md` §9.4) |
| 2 | Wordmark | `wordmark` | plain text, `headline-md`. No logo exists in M1. |
| 3 | Primary navigation | `primary-nav` | a disclosure button below the switch, inline links above it. Current page marked by weight **and** an underline, never colour alone. |
| 4 | Theme toggle | `theme-toggle` | light / dark; the manual choice beats the system preference (`tokens.css` §3) |
| 5 | Header ad | `ad-header-leaderboard` | **728 × 90, ≥ 768 px only.** Absent in the mobile band on every page. |
| last | Footer | `footer` | About · Terms · Privacy · **Cookie settings** (→ `/privacy#cookies`) |

---

## 4. The pages

Each table below is the **reading order**: DOM order, tab order and the order
the numbered circles run in on the sheet. Where a view's order differs, the
difference is stated.

### 4.1 Home / game list — `/`

| Order (375 / 1280) | Region | Component | What it holds |
| --- | --- | --- | --- |
| 5 / 6 | Hero | — | `h1` at `display`, one line of introduction |
| 6 / 7 | Game grid | `game-grid` | `h2` "Trò chơi"; **1 column at 375, 3 columns with a 32 px gap at 1280** |
| 7 / 8 | Game card ×N | `game-card` | the six fields below |
| 8 / 9 | Footer | `footer` | shared |

A card, in its own reading order:

| | Field | Source | Note |
| --- | --- | --- | --- |
| a | Thumbnail, 16:9 | **manifest — art** | grey box here; the game supplies the only colour on the page |
| b | Title (`h3`) | **manifest — title** | |
| c | Short description | **manifest — short description** | |
| d | Controls | **manifest — controls** | added by `requirements.md` §2 |
| e | This week's top 3 | **platform data, not the manifest** | the leaderboard, so it is the one part of a card with loading / empty / error states |
| f | "Chơi" action | **manifest — play link** | the card's primary action |

The four fields the Task names — title, art, short description, play link — are
tagged `manifest` on the sheet. **The manifest format is not designed here**
(GAM-16); the tags say *where a value comes from*, not what the key is called.

**States.** The grid is generated from the manifests **at build time**
(`requirements.md` §2), so the grid itself has an **empty** state (no games
yet) and an **error** state (the list could not be built) but no loading state.
The top-3 strip inside each card is live data and has all three. Both are
marked on the sheet.

**Open for M1-04 / M1-05.** `DESIGN.md` allows one `button-primary` per view,
and a grid of N cards has N "Chơi" buttons. The wireframe marks "Chơi" as the
*card's* primary action and leaves the expression to M1-04: `button-primary`
inside the card, `button-secondary`, or the whole card as one link. It is a
component question, not a layout one, so it does not block this Task.

### 4.2 Play page — `/games/<game_id>`

The one page whose structure is fixed by another document. `ad-slots.md` §4 is
the geometry, §9 is the stacking, and this wireframe is built to both. **One
DOM, three bands**: all five ad slots are in the markup of every view and only
`display` differs, so nothing is reordered or re-parented at a breakpoint.

**Mobile · 375** — exactly two banners, no rail:

| Order | Region | Component |
| --- | --- | --- |
| 5 | Game title | `h1` |
| 6 | Ad, above the frame | `ad-mobile-top` · 320 × 100 |
| 7 | Game frame | `game-frame` · 343 × 193 |
| 8 | Game info | `game-meta` — controls · how to play |
| 9 | Ad, after the game info | `ad-after-info-rectangle` · 300 × 250 |
| 10 | Weekly leaderboard | `leaderboard-table` (short form) |
| 11 | Footer | shared |

**Compact · 768** (verification view) — one column, no rail, two slots in
sequence after the game info:

| Order | Region | Component |
| --- | --- | --- |
| 6 | Game title | `h1` |
| 7 | Game frame | `game-frame` · **704 × 396** |
| 8 | Game info | `game-meta` |
| 9 | Ad, after the game info | `ad-after-info-rectangle` · 300 × 250 |
| 10 | Ad, in content | `ad-incontent-banner` · 704 × 90 |
| 11 | Weekly leaderboard | — |
| 12 | Footer | shared |

**Desktop · 1280** — two columns from 1024 px up:

| Order | Region | Component |
| --- | --- | --- |
| 6 | Game title | `h1` |
| 7 | Game frame | `game-frame` · **868 × 488** |
| 8 | Game info | `game-meta` |
| 9 | Ad, in content | `ad-incontent-banner` · 728 × 90 |
| 10 | Ad, top of the rail | `ad-rail-rectangle` · 300 × 250 |
| 11 | Weekly leaderboard | — |
| 12 | Footer | shared |

Note orders 9 and 10 at 1280: **the rail comes after the whole main column in
DOM order** (`ad-slots.md` §9.1), so the in-content banner is read, and tabbed
past, before the rail. Visually the rail sits in column 2 beside the frame, so
a sighted reader meets 10 and 11 **before** 9: on this one view the numbered
sequence is the DOM and tab order but not the top-to-bottom visual order, and
that is the intended trade (§6). It is also why the page needs no `order`
property anywhere — the DOM is already in the order we want read.

**States.** The frame marks loading · error · "needs JavaScript"; the weekly
board marks loading · empty (nobody has scored this week) · error. `game-meta`
is static.

**The rule this page exists to honour:** `game-meta` always sits between the
frame and the banner below it, so no ad is ever directly under the frame and no
ad is adjacent to the controls (`requirements.md` §5.1, §5.2).

### 4.3 Leaderboards — `/leaderboards`, `/leaderboards/<game_id>`

| Order (375 / 1280) | Region | Component | What it holds |
| --- | --- | --- | --- |
| 5 / 6 | Page title | `h1` | "Bảng xếp hạng" |
| 6 / 7 | Game selector | `select` at 375, linked chips at 1280 | real links to each game's route, not JS-only tabs |
| 7 / 8 | **Your result** | `own-rank-row` + personal best | **above both boards** |
| 8 / 9 | This week | `leaderboard-table` | rank · player · score, with your row marked |
| 9 / 10 | Last week (final) | `leaderboard-table` | |
| 10 / 11 | Footer | shared | |

At 1280 the two boards sit **side by side**; at 375 they stack, this week
first. "Your result" is full width in both.

**Why your rank is above the boards and also inside them.** The question "where
am I" is answered without scrolling and without a sticky row — this page has no
sticky element. Inside the table, your row is marked by a heavier border **and
the word "Bạn"**, never by colour alone (`DESIGN.md` → "Status is never colour
alone").

**States.** Each board: loading (skeleton rows) · empty (nobody has scored) ·
error. "Your result" has a **fourth**: *not signed in / no score yet* — an
anonymous player still has a rank, but a player who has never scored does not.
M1-04 designs all four.

The week boundary (Monday 00:00 UTC+7) is printed next to the selector.
`requirements.md` §8 open question 5 has GAM-16 confirming it.

### 4.4 Account and profile — `/account`

| Order (375 / 1280) | Region | Component | What it holds |
| --- | --- | --- | --- |
| 5 / 6 | Page title | `h1` | "Tài khoản" |
| 6 / 7 | In-page contents | `primary-nav` (local) | three links: Hồ sơ · Đăng nhập · **Dữ liệu của tôi** |
| 7 / 8 | Profile | `input-text` + `button-primary` | display name (visible label, moderated), Save |
| 8 / 9 | Sign-in | buttons | identity state ("playing anonymously"), link Google, link email, sign out |
| 9 / 10 | **My data** | buttons | what is stored · view it · **Xóa dữ liệu của tôi** |
| 10 / 11 | Delete confirmation | `dialog` | modal; drawn in place on the sheet |
| 11 / 12 | Footer | shared | |

**"Delete my data" is first-class, and the wireframe says so three ways:** it is
one of three sibling `h2` sections, not a sub-item of settings and not inside a
collapsed "danger zone"; it is in the in-page contents directly under the `h1`,
so it is one action away from the top of the page; and its label states plainly
what it does. It still goes through a confirmation dialog, because it cannot be
undone — confirmation is there to prevent a mistake, not to discourage the
choice.

**No player id is printed.** The page shows identity *state* only. Linking
keeps the same player id, so no score is orphaned (`requirements.md` §3,
OQ-036).

**States.** Profile: loading · saving · moderation error (inline, under the
field, linked by `aria-describedby`) · saved. Sign-in: linked · not linked ·
sending · provider error. Delete: confirming · deleting · done (signed out) ·
error.

**Open for M1-04.** "Xóa vĩnh viễn" is a destructive action and `DESIGN.md` has
no destructive-button style — the palette's only red is `error`, which belongs
to error states. This wireframe does not invent a colour (the rule is explicit:
if you need a new colour, you do not have one). **M1-04 or the Board decides
how a destructive action is expressed.**

### 4.5 Privacy policy — `/privacy`

The long-form document template, shared with Terms and About: `h1`, last
updated, in-page contents, then numbered sections.

| Order (375 / 1280) | Region | What it holds |
| --- | --- | --- |
| 5 / 6 | Page title | `h1` |
| 6 / 7 | Last updated | date |
| 7 / 8 | In-page contents | the seven sections |
| 8 / 9 | 1 · What is stored | the field table of `requirements.md` §4.1, **and what is not stored**: no email copy, no IP address, no telemetry |
| 9 / 10 | 2–5 · Why · How long · Who processes it · Your rights | processors: backend platform, hosting, ad network; rights links straight to `/account → Xóa dữ liệu của tôi` |
| 10 / 11 | 6 · **Cookies** — anchor `#cookies` | the cookie groups, and the control that reopens the settings dialog |
| 11 / 12 | 7 · Contact | |
| 12 / 13 | Footer | shared |

`#cookies` is a **section, not a page**: `requirements.md` §2 puts the settings
link at `/privacy#cookies`, so that section both explains and acts. It is the
one place a player changes their mind after the banner has gone.

**States.** The body is static: no loading, no empty. The only page-level
failure is a 404/500, which the page Task owns. The "Mở cài đặt cookie" control
has saving · error. Both are marked.

The legal text itself is **not** this Task's: it comes from OQ-039 and must
cover Decree 13/2023, and GDPR if EU players are expected.

### 4.6 Cookie banner and settings

A **surface, not a route** (`requirements.md` §2): the banner appears on first
visit on whatever page the visitor landed on, and the settings dialog opens
from it or from `/privacy#cookies`.

| Order (375 / 1280) | Region | Component | What it holds |
| --- | --- | --- | --- |
| 5 / 6 | The page behind | — | stays readable and keyboard-operable; the banner is **not** modal |
| 6 / 7 | Cookie banner | `cookie-banner` | one line of explanation, a "learn more" link, and **three equal-weight actions**: Chấp nhận · Từ chối · Cài đặt |
| 7 / 8 | Settings dialog | `dialog` | Essential (always on, cannot be turned off) · Advertising (off by default) · Save · Cancel |
| 8 / 9 | Footer | shared | |

At 375 the three actions stack full width, each ≥ 44 px with 8 px between — at
343 px three Vietnamese labels do not fit on one readable row. At 1280 they sit
in a row, still identical in size and style.

**Equal weight is a requirement, not a preference** (`requirements.md` §2):
"Từ chối" is never smaller, quieter or further away than "Chấp nhận", and
neither is filled while the other is bare.

**There is no analytics category**, because the site collects none
(`requirements.md` §4.2). Do not add a switch for something that does not
exist.

**No ad script loads before consent, and no slot shifts because of a choice.**
Every ad box is reserved by the page's own stylesheet before any decision, so
"Từ chối" leaves a correctly sized empty box rather than a jumping page
(`ad-slots.md` §8.2).

**Focus.** The banner is the site's **one** justified sticky surface — which is
precisely why no ad may be sticky (`ad-slots.md` §7). It must never cover the
focused control (WCAG 2.2 *Focus Not Obscured*), it does not trap focus, and it
is last in `<body>`. The settings dialog *is* modal: it traps focus while open,
closes on Esc, and returns focus to the control that opened it.

On the sheets the banner is drawn in place at the foot of the view instead of
stuck to the reader's window, so the view stays measurable.

**Open (OQ-038).** EU/UK visitors may require a certified consent tool. If one
is adopted it replaces the *mechanism*, not the layout: the structure, the equal
weighting and "no script before consent" hold either way.

### 4.7 About — `/about`

The shortest page on the site, and the document template without the contents
list — three sections do not need one.

| Order (375 / 1280) | Region | What it holds |
| --- | --- | --- |
| 5 / 6 | Page title | `h1` "Giới thiệu" |
| 6 / 7 | Last updated | date |
| 7 / 8 | 1 · About the studio | |
| 8 / 9 | 2 · Our games | a paragraph and a link back to `/` — **the game list is not duplicated here** |
| 9 / 10 | 3 · Contact | the same contact address Privacy §7 gives |
| 10 / 11 | Footer | shared |

**States.** Static: no loading, no empty; page-level error only. Marked on the
sheet.

The page exists because `requirements.md` §2 requires it and because ad
networks usually expect one (OQ-038).

### 4.8 Terms — `/terms`

The same document template as Privacy. Different sections, identical structure —
so M1-04 designs **one** long-form template, not three.

| Order (375 / 1280) | Region | What it holds |
| --- | --- | --- |
| 5 / 6 | Page title | `h1` "Điều khoản sử dụng" |
| 6 / 7 | Last updated | date |
| 7 / 8 | In-page contents | the seven sections |
| 8 / 9 | 1–2 · Accepting the terms · Account and display name | links to `/account`; display names are moderated |
| 9 / 10 | 3 · Fair play and scores | the player-facing form of `requirements.md` §4.2: scores are granted server-side, a client-submitted score is a request and not a fact |
| 10 / 11 | 4–7 · Advertising · Content and ownership · Liability · Changes and contact | §4 links to `/privacy#cookies` |
| 11 / 12 | Footer | shared |

**States.** Static, as Privacy. Legal text from OQ-039.

---

## 5. The play page against the ad-slot spec

This is the acceptance criterion with numbers behind it, so a reviewer can
check it rather than take it on trust. Every figure is produced by `ads.css`
and the page structure; none is written into `wireframe.css`.

| View | Slot | Box | Clearance to the frame | Required |
| --- | --- | --- | --- | --- |
| 375 | `ad-mobile-top` | 320 × 100 | **24 px exactly**, above the frame | ≥ 24 |
| 375 | `ad-after-info-rectangle` | 300 × 250 | ≥ 64 px, below | ≥ 24 |
| 768 | `ad-header-leaderboard` | 704 × 90 | ≥ 120 px, above | ≥ 24 |
| 768 | `ad-after-info-rectangle` | 300 × 250 | ≥ 72 px, below | ≥ 24 |
| 768 | `ad-incontent-banner` | 704 × 90 | ≥ 370 px, below | ≥ 24 |
| 1280 | `ad-header-leaderboard` | 728 × 90 | ≥ 120 px, above | ≥ 24 |
| 1280 | `ad-rail-rectangle` | 300 × 250 | **32 px**, horizontally | ≥ 24 |
| 1280 | `ad-incontent-banner` | 728 × 90 | ≥ 72 px, below | ≥ 24 |

And the structural rules, each true by construction on the sheets:

- **Nothing inside the frame.** Every slot is a **sibling** of the frame.
  `.wf-frame .ad-slot` matches nothing on any sheet.
- **Nothing overlapping.** In the wide view the rail slot's inline-start edge
  is 900 px from the content-column origin and the frame's inline-end edge is
  at 868 px. Everywhere else the separation is on the block axis and is a
  single column of sums.
- **Nothing sticky.** No slot is sticky, fixed, floated, z-indexed or
  transformed — `ads.css` writes those four as CSS and nothing here overrides
  them. The only sticky surface on the whole site is the cookie banner.
- **Fixed, pre-reserved sizes.** Each slot's height is an explicit literal from
  `ads.css`, in force at the first paint. Expected layout-shift contribution:
  zero.
- **Mobile has no sidebar** — one banner above the frame, one after the game
  info, and the other three slots are `display: none` so they cannot reappear
  by accident.
- **The play grid's column gap is 32 px**, above the 24 px floor
  `ad-slots.md` §5.3 binds M1-03 to, and it exists only in the wide band.
- **Each page column is a flex column with no `gap`** (`ad-slots.md` §4.3), so
  every slot's own 24 px margin is added to the page's rhythm and can never be
  absorbed into it.
- **`flex: none` is untouched.** `ad-slots.md` §11 says explicitly that M1-03
  must not remove it. It is not removed, not overridden, and not relied on
  being overridable.

---

## 6. Accessibility fixed at the wireframe stage

Accessibility is in the acceptance criteria, not a follow-up (`AGENTS.md` §6).
What a wireframe can settle, it settles here:

- **Landmarks and order.** Every page is skip link → header → `main` → footer.
  **DOM order = tab order = the numbered sequence, at every width, on every
  sheet**, and no view reorders anything: one DOM per page, bands differ only in
  the `display` of five ad slots, and no `order` property is used anywhere.
- **Visual order matches that sequence everywhere except the wide play page,
  deliberately.** At 1280 the rail — the rail ad (10) and the weekly board (11)
  — sits top-right, level with the frame (7), so a sighted reader *sees* it
  before the in-content banner (9) at the foot of the main column. The rail is
  read, and tabbed past, **after** the whole main column. That is the DOM order
  `ad-slots.md` §9.1 fixes, and it is also the order to want: content before
  rail is what keeps the meaningful sequence meaningful (WCAG 1.3.2) and the
  focus order sensible (WCAG 2.4.3). The numbered circles are the DOM and tab
  order; on that one view they are not the top-to-bottom visual order.
- **The skip link comes before the header ad**, so one keystroke passes the
  banner (`ad-slots.md` §9.4).
- **One `h1` per page**, then `h2` sections, then `h3` within them. No level is
  skipped. On a sheet the levels are written into the stand-ins, because a
  sheet carrying many views must not contain many real `h1`s.
- **Every control has a visible text label** and a target of at least
  44 × 44 px, with at least 8 px between adjacent targets.
- **Inputs have visible labels**, never placeholder-only, with errors below the
  field and linked by `aria-describedby`.
- **Status is never colour alone** — the current nav item, your leaderboard
  row, and every state carry a word or a shape as well.
- **Ad slots are never focusable** and never trap focus. They are labelled
  landmarks (`<aside aria-label="Quảng cáo">`), so a screen-reader user can
  skip one instead of meeting an unexplained grey box.
- **Focus is never obscured** (WCAG 2.2): no sticky ad, and the cookie banner
  must not cover the focused control.
- **Modal means modal**: the delete-confirmation and cookie-settings dialogs
  trap focus, close on Esc, and return focus to their opener. They are the only
  focus traps on the site.
- **Vietnamese first**: the sheets are set in Vietnamese, so a region that only
  fits in English is caught here rather than in M1-05.

The measured contrast of every pair these sheets use is already recorded in
`DESIGN.md` → "Measured contrast". No new pair is introduced.

---

## 7. How to verify

No build, no server, no dependency: open
[`prototypes/wireframes/index.html`](prototypes/wireframes/index.html) and
follow the links.

### 7.1 By eye, on every sheet

1. **Sixteen views are present**, plus the compact play view — each sheet
   carries its views stacked and titled.
2. **Both themes.** Set `data-theme="dark"` on `<html>` in DevTools. Nothing
   changes size; everything stays legible.
3. **No brand colour anywhere.** If a sheet looks teal, something imported a
   decision it should not have.
4. **Tab through a sheet.** Focus is always visible, never hidden, and the
   order matches the numbered circles.
5. **The mobile view is the mobile layout** even on a wide monitor — that is
   the point of the fixed-width views.

### 7.2 Measure the play page

Open `02-play.html`, open the Console, and run:

```js
for (const view of document.querySelectorAll('.wf-view')) {
  const f = view.querySelector('.wf-frame').getBoundingClientRect();
  console.group(view.className.replace('wf-view wf-view--', ''));
  console.log('frame', Math.round(f.width) + ' x ' + Math.round(f.height));
  for (const el of view.querySelectorAll('.ad-slot')) {
    const cs = getComputedStyle(el);
    if (cs.display === 'none') continue;
    const a = el.getBoundingClientRect();
    console.log(el.className.replace('ad-slot ad-slot--', ''), {
      box: Math.round(a.width) + 'x' + Math.round(a.height),
      gapAbove: Math.round(f.top - a.bottom),   // slot is above the frame
      gapBelow: Math.round(a.top - f.bottom),   // slot is below the frame
      gapLeft:  Math.round(f.left - a.right),   // slot is left of the frame
      gapRight: Math.round(a.left - f.right),   // slot is right of the frame
      overlaps: !(a.right  <= f.left || a.left >= f.right ||
                  a.bottom <= f.top  || a.top  >= f.bottom),
      position: cs.position,
    });
  }
  console.groupEnd();
}
```

A slot's clearance is its **one positive gap**; the other three are negative
and meaningless. Expected:

| Group | Lines | Frame | Must read |
| --- | --- | --- | --- |
| `mobile` | **2** | 343 × 193 | `mobile-top` 320x100, `gapAbove` 24 · `after-info-rectangle` 300x250, `gapBelow` ≥ 64 |
| `compact` | **3** | 704 × 396 | `header-leaderboard` 704x90 ≥ 120 · `after-info-rectangle` 300x250 ≥ 72 · `incontent-banner` 704x90 ≥ 370 |
| `wide` | **3** | 868 × 488 | `header-leaderboard` 728x90 ≥ 120 · `incontent-banner` 728x90 ≥ 72 · `rail-rectangle` 300x250, `gapRight` 32 |

**The number of lines is itself part of the check.** Two in the mobile group is
"no sidebar on mobile, exactly two banners"; three in each of the others, with
exactly one 300 × 250 box in every group.

Any `overlaps: true`, any `position` other than `static`, or any clearance
below 24 fails the review.

### 7.3 Confirm the rules are not quietly broken

From the repository root:

```sh
# 1. No script, no iframe, no font file, no network reference of any kind.
grep -rnEi '<script|<iframe|@font-face|@import|src=|https?:|url\(' \
  docs/design/prototypes/wireframes/

# 2. Nothing is lifted out of normal flow, and no ad geometry is redefined.
grep -nE '^[[:space:]]*(position:[[:space:]]*(sticky|fixed)|float:[[:space:]]*(left|right)|z-index:[[:space:]]*[0-9]|transform:)' \
  docs/design/prototypes/wireframes/wireframe.css

# 3. No colour literal, and no new breakpoint.
grep -nE '#[0-9a-fA-F]{3,8}|rgba?\(|@media' \
  docs/design/prototypes/wireframes/wireframe.css
```

All three must return **nothing**. Check 1 covers the HTML as well as the CSS:
the sheets reference only the three M1 stylesheets and each other, by relative
path, and `<link rel="stylesheet" href="...">` is the only external reference
on any of them — which is why the pattern list above deliberately does not
include `href`.

Check 3 returning nothing is the strong one: `wireframe.css` contains **no
media query at all**, because a view's band comes from its class. That is also
how you know no Task has smuggled a third breakpoint into M1.

### 7.4 Also check

- **No horizontal scrollbar on the page itself** at any reader width. A 1280 px
  view scrolls inside its own container, never the document.
- **`prefers-reduced-motion`**: nothing on these sheets animates at all.
- **A page with the stylesheets removed** still reads top to bottom in the
  right order — the structure is in the HTML, not in the CSS.

---

## 8. Handoff

**To M1-04 (components).** Every component these sheets place is named in
`DESIGN.md`'s inventory and positioned here; M1-04 designs them, plus the
**empty, loading and error states** each sheet marks. Three things M1-04 owns
that this Task deliberately left open:

1. **How a destructive action is expressed** (§4.4). There is no style for one
   and this Task did not invent a colour.
2. **How a card's primary action is expressed** when a view has N cards
   (§4.1).
3. **The long-form document template** — one template serves Privacy, Terms and
   About (§4.5–4.8).

`ad-slots.md` §11 already tells M1-04 not to restyle `ad-slot`, and that
`game-meta`'s position between the frame and the lower banner is load-bearing.

**To M1-05…M1-09 (the page mockups).** The structure, the region list and the
reading order on these sheets are **the brief**. A mockup may make a region
beautiful; it may not move one, drop one, reorder one, or add an ad slot. If a
mockup needs a structural change, raise it on the Task and this document is
amended first — the same rule `ad-slots.md` applies to ad geometry.

**To M2 (the stack) and M4 (tests).** Each page gets a Playwright end-to-end
test once the stack lands (`requirements.md` §2). These sheets are where the
assertions come from: landmark order, heading levels, tab order, and the ad
geometry table in §5.

**To M6 (ads integration).** Nothing here requests a creative. Two ids carry
the 300 × 250 format and they are mutually exclusive by width; target whichever
is on the page.

### Open items

1. **Mobile carries no ad outside the play page** (`ad-slots.md` §3, still open
   for the Board). These wireframes draw what that spec allows: zero ad slots
   in the mobile band on the home, leaderboards, account, privacy, about and
   terms sheets. If the Board wants a mobile slot elsewhere,
   `requirements.md` §5.1 has to name the position first, then `ad-slots.md`,
   then these sheets.
2. **Routes are the plan's proposed routes** (`requirements.md` §8, question 4).
   M2 confirms them. No structure here depends on the final strings.
3. **The compact play view is drawn at 768 px**, the lower edge of the band and
   the old failure point. `ad-slots.md` §10 asks a reviewer to check the real
   product at 900, 1000 and 1024 as well; that is a prototype check against
   `ads.css`, not a wireframe, and it is unchanged by this Task.

Three things the compact sheet is simply the first place anyone can *see*.
None is a defect in these wireframes — each is drawn exactly as its spec says —
but each is now visible, so it is recorded rather than left to be rediscovered.

4. **Ad density in the compact band.** At 768 px `ad-after-info-rectangle`
   (300 × 250) and `ad-incontent-banner` (728 × 90, drawn 704 wide) stack in
   direct sequence 48 px apart — roughly 388 px of ad between the game info and
   the footer. Both slots are in `requirements.md` §5.1 and the inventory is the
   Board's 2026-11-04 decision, so this Task drew what it was told to draw. It
   is what an ad-network placement policy (OQ-038) will have to answer.
5. **The compact header reserves a box no creative can fill.**
   `min(728px, 100%)` shrinks the header leaderboard to 704 px at 768, so the
   band holds 90 px of height a 728-wide creative will never occupy — `ads.css`'s
   documented "requests no creative" behaviour, correctly drawn, but in practice
   a permanently empty strip at the top of every page in that band. Worth
   settling before M6.
6. **The sheets hard-code `display: flex` as the ad slot's "on" value**
   (`wireframe.css` §4, three places), because that is what `ads.css` §2 sets.
   If `ads.css` ever changes that display type the sheets keep `flex` silently.
   The comment in `wireframe.css` §4 says so; the cross-reference can only be
   one-way, because `ads.css` belongs to M1-02 and this Task does not edit it.

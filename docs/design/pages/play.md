# Play page — `/games/<game_id>`

**Status:** Normative for the play page's layout, controls and states. Milestone
M1 (Design), Task SOU-4 (M1-06). Owner: `ui-designer`, through
`project-manager`. Last updated: 2026-11-05.

The mockup is [`../prototypes/play.html`](../prototypes/play.html) and is static
HTML: open it in a browser and it works. No server, no build step, no
dependency, no network request.

This is the highest-stakes page in M1 — the one where the game is played and the
one where the ads sit. Every ad rule from M1-02 is enforced visibly here and
measured in §8.

It is downstream of five documents and contradicts none of them:

| Source | What it fixes | What this document does |
| --- | --- | --- |
| [`../../requirements.md`](../../requirements.md) §2, §5 | the page's content, the allowed ad positions, the 24 px margin, no-overlap, no-sticky | builds the page to them |
| [`../DESIGN.md`](../DESIGN.md) (M1-01) | colour, type, spacing, shape, motion, the three breakpoints, the measured contrast table | designs inside the measured pairs, inventing no value |
| [`../ad-slots.md`](../ad-slots.md) (M1-02) | the slot inventory, the sizes, the clearance geometry, the bands | places all five slots exactly as specified and measures every clearance |
| [`../wireframes.md`](../wireframes.md) (M1-03) §4.2 | the regions, the reading order, where each state goes | makes those regions high-fidelity; moves none, drops none, reorders none |
| [`../components.md`](../components.md) (M1-04) | every component on the page, and the three state patterns | reuses them; **adds no shared component** |

---

## 1. Scope — and what is deliberately absent

In scope: the page's composition at both design widths plus the compact
verification band, in both themes, with every state; the game frame and its
ratio; the controls and the game info block; the weekly board as a view onto
the leaderboards; where every ad slot sits and how far it is from the frame;
the keyboard order; and the contrast of every pair used.

**Deliberately absent, and a defect if a later Task reads one into this page:**

- **The game platform contract.** GAM-16 is out of M1 entirely, and SOU-4 says
  so in as many words. Nothing here designs or assumes an embedding API: there
  is no frame element of any embedding kind, no mount call, no message channel,
  no manifest key and no score-submission path. The frame is **a box with a
  ratio**, which is all M1 is allowed to decide. §5 lists the controls this
  costs us, by name, rather than leaving the omission to be guessed at.
- **Any ad network, ad tag, ad script, embedded frame, pixel or tracker.** Every
  slot is an empty placeholder box. M1 is design; M6 is integration, behind
  consent.
- **A new shared component.** Nothing here is added to `components.css`. The
  three compositions this page makes on top of library components are named in
  §9 and reported rather than promoted.
- **A new colour, type step, spacing step, radius or breakpoint.** All five
  belong to M1-01. This page introduces none, and §11 shows that every pair it
  uses was already measured.
- **The leaderboards page.** The full board, the period and scope switches and
  the "your result" block are M1-07's. This page links to them and designs none
  of them (§6).
- **Any framework, build step, runtime dependency, package manifest, analytics
  or font file.**

---

## 2. The page, in one paragraph

The title, then the game, then everything that explains the game, then the
week's scores. The frame is the largest thing on the page at every width and is
the only element whose size is fixed by a ratio rather than by its content —
which is exactly why every ad clearance is measured against it. Below the frame
sits the one block that is never allowed to move: `game-meta`, carrying the
controls and the game info, and standing between the frame and the banner under
it so that no ad is ever directly beneath the game. The weekly board is a
three-row view of the leaderboard beside the frame at ≥ 1024 px and below it
elsewhere. **The chrome is deliberately quiet, because the game is the colour on
this page** — if the page looks plain with the frame emptied, that is correct.

---

## 3. The real page markup

The mockup sheet stacks five renders in one document, so its chrome is demoted
out of the landmark roles and its headings sit lower (`components.md` §2.3 fixes
that convention and `play.html` §9.2 restates it). **This is the markup of the
real page**, with the real landmarks and the real heading levels, so M2 can lift
it directly rather than un-demote the sheet.

```html
<body class="page">
  <a class="skip-link" href="#main">Tới nội dung chính</a>

  <header class="site-header">                 <!-- banner -->
    <div class="shell site-header__bar">
      <a class="wordmark" href="/">Soucean Game</a>
      <nav aria-label="Chính">                 <!-- navigation -->
        <button class="btn btn--quiet site-nav__toggle" type="button"
                aria-expanded="false" aria-controls="nav">…Menu</button>
        <ul class="site-nav__list" id="nav">
          <li><a class="site-nav__link" href="/">Trò chơi</a></li>
          <li><a class="site-nav__link" href="/leaderboards">Bảng xếp hạng</a></li>
          <li><a class="site-nav__link" href="/account">Tài khoản</a></li>
        </ul>
      </nav>
      <div class="site-header__actions">
        <fieldset class="theme-toggle">…Sáng / Tối…</fieldset>
      </div>
    </div>
    <div class="shell">
      <!-- AFTER the skip link, so one keystroke passes it. >= 768px only. -->
      <aside class="ad-slot ad-slot--header-leaderboard" aria-label="Quảng cáo">
        <span class="ad-slot__label">Quảng cáo</span>
      </aside>
    </div>
  </header>

  <main id="main" class="shell main" tabindex="-1">  <!-- main -->
    <h1 class="play-title">Tên trò chơi</h1>

    <div class="play-grid">
      <div class="play-main">

        <!-- mobile band only -->
        <aside class="ad-slot ad-slot--mobile-top" aria-label="Quảng cáo">…</aside>

        <!-- THE FRAME. A SIBLING of every slot, never an ancestor of one. -->
        <div class="game-frame game-frame--mounted" id="game-frame" tabindex="0"
             role="group" aria-label="Khung trò chơi — Tên trò chơi"
             aria-describedby="game-frame-hint">
          <!-- the game is drawn here -->
        </div>

        <section class="game-meta card" aria-labelledby="how-to-play">
          <p class="game-meta__hint" id="game-frame-hint">Khung trò chơi là một
             điểm dừng Tab…</p>
          <h2 class="game-meta__title" id="how-to-play">Cách chơi</h2>
          <div class="game-meta__controls">
            <button class="btn btn--primary" type="button">Toàn màn hình</button>
            <button class="btn btn--secondary" type="button">Tải lại trò chơi</button>
          </div>
          <dl class="game-meta__facts">…Điều khiển · Số người chơi · Một ván…</dl>
          <div class="game-meta__how"><p>…</p></div>
        </section>

        <!-- below 1024px only -->
        <aside class="ad-slot ad-slot--after-info-rectangle" aria-label="Quảng cáo">…</aside>
        <!-- 768px and up -->
        <aside class="ad-slot ad-slot--incontent-banner" aria-label="Quảng cáo">…</aside>
      </div>

      <div class="play-rail">
        <!-- 1024px and up only -->
        <aside class="ad-slot ad-slot--rail-rectangle" aria-label="Quảng cáo">…</aside>

        <section class="weekly-board card">
          <h2 class="weekly-board__title">Bảng xếp hạng tuần này</h2>
          <p class="weekly-board__week"><span class="badge">…</span></p>
          <table class="board">…</table>
          <p class="weekly-board__more">
            <a class="link" href="/leaderboards/<game_id>">Xem bảng xếp hạng đầy đủ</a>
          </p>
        </section>
      </div>
    </div>
  </main>

  <footer class="site-footer">…</footer>       <!-- contentinfo -->
</body>
```

**Heading levels on the real page:** exactly one `<h1>` (the game title), then
`<h2>` for "Cách chơi" and for "Bảng xếp hạng tuần này". No level is skipped and
nothing below `h2` is needed.

**One DOM, three bands.** All five ad slots are in the markup at every width;
the band changes only which of them is `display: none`. Nothing is reordered,
moved or re-parented by a media query, which is why no `order` property appears
anywhere in `play.css` and why reading order and visual order agree
(`ad-slots.md` §9).

---

## 4. The game frame

### 4.1 One element, one ratio, four displays

`.game-frame` is the box every clearance in §8 is measured against, so two
things about it are not negotiable:

- **It is exactly 16:9 at every width.** `ad-slots.md` §5 measures against this
  border box, and `components.md` §4.1 already requires it of `.state--frame`.
- **It is a sibling of every ad slot, never an ancestor of one.**
  `.game-frame .ad-slot` must never match anything; §10 gives the one-line check.

Ready, loading, error and "needs JavaScript" are the **same element** with a
different class list and different children:

| Display | Markup | Box |
| --- | --- | --- |
| Ready (the game is mounted) | `.game-frame.game-frame--mounted`, `tabindex="0"` | 16:9 |
| Loading | `.game-frame.state.state--frame.state--loading` with `aria-busy="true"`; the announcing region is a separate polite live region beside it, never the frame itself (`components.md` §4.3 note 3) | 16:9, **exactly** |
| Error | `.game-frame.state.state--frame.state--error`, `role="status"` | 16:9, or taller if it has to be — see §6.2 |
| Needs JavaScript | `.game-frame.state.state--frame` holding a `.notice--warning` | 16:9 |

### 4.2 Frame size, per width

Width follows the main column; height follows the ratio. Nothing about it
depends on anything that loads.

| Viewport | Band | Content column | Main column | Frame |
| --- | --- | --- | --- | --- |
| 375 | mobile | 343 | 343 | **343 × 193** |
| 768 | compact | 704 | 704 | **704 × 396** |
| 900 | compact | 836 | 836 | 836 × 470 |
| 1000 | compact | 936 | 936 | **936 × 527** |
| 1024 | wide | 960 | 628 | **628 × 353** |
| 1280 | wide | 1200 | 868 | **868 × 488** |

Content column = viewport − 2 × `--layout-gutter` (16 px mobile, 32 px from the
switch up), capped at `--layout-max-width` (1200 px). Main column = content
column, minus 300 + 32 in the wide band only. **These are `ad-slots.md` §4's
reference numbers exactly** — this page reproduces that geometry rather than
re-deriving it.

The shrink at 1024 px, from 936 × 527 to 628 × 353, is expected: it is what buys
the rail its room, and layout shift caused by a viewport resize is excluded from
the CLS metric (`ad-slots.md` §8.2).

### 4.3 It never overflows the viewport

Horizontally it cannot: the frame is at most the width of the content column,
which is itself the viewport minus two gutters.

Vertically there is one case the ratio alone does not cover — a short landscape
phone, where the content column can be wider than 16/9 of the viewport height
and an uncapped frame would be taller than the screen. `play.css` §2 caps it:

```css
.page .game-frame { --game-frame-max-inline: calc(100dvh * 16 / 9); }
```

Two things about that line are deliberate:

- **The cap is on the inline axis.** A `max-block-size` would clamp the height
  and silently break the 16:9 ratio; capping the width keeps the ratio exact and
  simply makes the frame smaller, centred in its column. A narrower frame can
  only *increase* every clearance in §8, never reduce one, because all of them
  are margins.
- **It is scoped to `.page`, the real page's body class.** A cap expressed in
  `dvh` is a statement about a *viewport*, and a fixed-width view on the mockup
  sheet has no viewport of its own — applying it there would make the frame a
  reviewer measures depend on the height of their window, which would wreck the
  one thing the sheet exists for.

**This is the one thing on this page that no upstream document asked for.** It
is raised in §9.2 rather than slipped through; deleting those two lines changes
nothing else.

### 4.4 What the frame is not

It is not an embedded document, a canvas, a mount point or an adapter. M1 draws
a box with a ratio and a placeholder caption. Which element the box eventually
contains, and how the game is put into it, is GAM-16's and M2's — and when that
happens, the two properties above (16:9, and the frame's keyboard contract in
§7) are what the replacement has to keep.

---

## 5. Game controls and the game info block

### 5.1 Where they live, and why they are not a separate region

`game-meta` is the block `wireframes.md` §4.2 names "controls · how to play",
and its position between the frame and the banner below it is load-bearing:
it is what keeps every ad off the frame and away from the controls
(`requirements.md` §5.1 and §5.2, `ad-slots.md` §11). So the controls are
**inside `game-meta`, as its first interactive content** — not a new region
between the frame and the block. Adding a region there would be a structural
change, and a structural change needs `wireframes.md` amended first, which is
not this Task's to do.

Reading order inside the block: the keyboard-contract line · the controls row ·
the facts list · how to play. The surface, the edge, the radius and the padding
are `components.css`'s `.card`; this page adds no second surface.

### 5.2 The two controls, and the ones that are deliberately missing

| Control | Style | What it does | Why it is allowed in M1 |
| --- | --- | --- | --- |
| **Toàn màn hình** | `.btn--primary` | Fullscreen on the frame box | The browser's own Fullscreen API, applied to an element this page owns. It asks the game for nothing. |
| **Tải lại trò chơi** | `.btn--secondary` | Re-mounts the contents of the frame box | Same action the error state's *Thử lại* performs. Page-side, not a call into the game. |

**Deliberately absent, every one of them for the same reason:** play, pause,
restart the run, mute, change difficulty, submit a score. Each needs to talk to
the game, which means it needs the game platform contract — GAM-16, out of M1
scope by SOU-4's own words. Designing a pause button now would be designing half
of that contract in the wrong document.

**This page has exactly one primary action and it is "Toàn màn hình".**
`components.md` §5.5 lists the play page as one of the two pages that carry a
`.btn--primary`; this is it. Everything else on the page is secondary or a link.

The two buttons are **inert in the mockup**: there is no script anywhere in this
Task's files. They are drawn in all five states (default, hover, keyboard focus,
active, disabled) in `play.html` §4, using M1-04's forced-state convention, so
the reviewer sees the rule that will ship rather than a redrawing of it. A
disabled control always says what is happening — "Đang tải lại…" — never a
dimmed ordinary label.

### 5.3 The facts list

Three rows: **Điều khiển** (the control scheme), **Số người chơi**, **Một ván**
(roughly how long a round takes). They come from the game, not from the
platform. The table above says *where a value comes from*; it proposes no key
name and no schema, and a later Task must not read one out of it — the manifest
format is GAM-16's.

---

## 6. States

### 6.1 Which block each region uses

Per `components.md` §4.4, which is normative. The copy is adjusted to the
subject, the structure is not.

| Region | Empty | Loading | Error |
| --- | --- | --- | --- |
| **Game frame** | — (a game is either there or it is not) | `.state--frame` with `aria-busy="true"` and static skeleton bars; the visually hidden "Đang tải trò chơi…" sits in the separate polite live region beside it (`components.md` §4.3 note 3) | "Không tải được trò chơi" · "Kiểm tra kết nối rồi thử lại." · *Thử lại*; the error block is itself the polite live region (`status`, not `alert`) |
| **Weekly board** | "Chưa có ai ghi điểm tuần này" · "Hãy là người đầu tiên — ván của bạn lên bảng ngay khi chơi xong." | 3 skeleton rows, visually hidden "Đang tải bảng xếp hạng tuần…" | "Không tải được bảng xếp hạng" · "Kiểm tra kết nối rồi thử lại." · *Thử lại* |
| **`game-meta`** | — static | — static | — static |

**"Needs JavaScript" is not an error.** Nothing failed, so it is a
`.notice--warning` inside the frame box, exactly as `components.md` §4.4
requires. The rest of the page — title, game info, weekly board, footer, all
navigation — works without JavaScript and stays readable behind it.

**Both error states are `role="status"`, not `role="alert"`.** The player did
not ask for either load, so the failure waits for a pause rather than cutting
them off; the *Thử lại* button is reachable by Tab whenever they get there.
`components.md` §4.3 fixes this and it is not re-litigated here.

**The empty state carries no action, on purpose.** `components.md` §4.2 makes
the action optional, and `components.md` §4.4's suggested *Chơi ngay* would, on
this page, be a button pointing at the thing the reader is already looking at.
The title and the sentence stay; the button is dropped. An empty state is also
not an error: no error colour, no alarm.

**Nothing animates.** No shimmer, no pulse, no spinner. The "it is working"
signal is the live-region text, because a shimmer tells a screen-reader user
nothing; the bars are `aria-hidden`. Setting `prefers-reduced-motion: reduce`
changes nothing, because nothing moved.

### 6.2 The frame's reservation, and the one place it is relaxed

**Loading is exactly 16:9 at every width.** That is the acceptance criterion —
loading reserves the frame's final size — and it is met by construction rather
than by tuning: the box is the same element, with the same ratio, before and
after the game arrives. Nothing below it moves, so the frame's contribution to
Cumulative Layout Shift is zero.

**The error state is allowed to exceed the ratio, and only at the mobile band.**
At 375 px the frame is 343 × 193, and the error pattern `components.md` §4.2
fixes has five required parts — icon, the word "Lỗi", a title naming what
failed, one sentence, and a retry. Even at the tighter padding `play.css` gives
a state block inside the frame, that is a few pixels more than 193 px holds.
The three available answers were to clip it, to drop a required part of the
pattern, or to let the box grow. **This page grows the box**, via
`min-block-size: min-content`, so the frame is the larger of the ratio and what
the error needs. Above the mobile band the ratio always wins and the rule has no
effect.

What that costs, stated plainly: **at the mobile band only, loading → error
moves what is below the frame down by a few pixels.** What it does not cost: no
ad slot changes size, every clearance in §8 is a margin and so is unchanged,
nothing above the frame moves, and loading → loaded still moves nothing at all.

### 6.3 The weekly board's four displays are one height

The three state blocks take their reserved height from `--state-reserve`,
computed in `play.css` §4 from the same tokens the real table reads — the
caption's line box plus its margin, the header row, and three data rows with
their padding. So *loading → empty*, *loading → error* and *loading → loaded*
all move nothing.

It is exact to within the collapsed hairline row borders (under 4 px in total),
which is as far as it can be computed without rendering it. §10 gives the
one-line check that confirms it in a browser.

`components.md` §4.1 names `.state--reserve-sm` for "a short weekly board"; this
board is a real three-row `.board` table, which is taller than that step, so the
reserve is derived from the content rather than rounded to a named one. It is a
`calc()` and not a height on purpose: a height would be a literal, and a literal
would drift from the type scale the moment the scale moved.

---

## 7. Keyboard, focus and reading order

DOM order = tab order = reading order. No positive `tabindex`, no `order`
property, no focus trap, and **nothing on this page is sticky** — so no focused
control is ever obscured (WCAG 2.2 *Focus Not Obscured*).

| # | Stop | Note |
| --- | --- | --- |
| 1 | `skip-link` → `#main` | First in the document, **before** the header ad slot, so one keystroke passes the banner. |
| 2 | Wordmark | Links to `/`. |
| 3 | Nav disclosure | Below 768 px only. |
| 4–6 | Trò chơi · Bảng xếp hạng · Tài khoản | The play page is not one of the three, so no item carries `aria-current`. |
| 7–8 | Giao diện: Sáng · Tối | Two real radios; Tab enters the group, arrow keys move within it. |
| — | *the ad slots* | **Not stops.** No `tabindex`, no interactive content, in any of the three. |
| 9 | **The game frame** | **Exactly one stop.** |
| 10 | Toàn màn hình | The page's one primary action. |
| 11 | Tải lại trò chơi | |
| 12 | Xem bảng xếp hạng đầy đủ | In the wide band the rail follows the whole main column in DOM, so this link is tabbed to *after* the in-content banner even though a sighted reader meets it earlier (`ad-slots.md` §9.1). That is the intended trade and it is why the page needs no `order`. |
| last | Four footer links | Each a 44 px target. |

### 7.1 The frame's keyboard contract

- **The frame is one tab stop, and only when a game is mounted in it.**
  `tabindex="0"`, an accessible name (`aria-label`), and a description
  (`aria-describedby`) pointing at the visible hint line at the top of
  `game-meta`. While it is loading or has failed, the frame carries no
  `tabindex`: a stop into an empty box leads nowhere, and the thing that must be
  reachable then is the error block's *Thử lại*, which is an ordinary stop.
- **Focus is visible on it.** `base.css` draws the ring on `:focus-visible` with
  a 2 px offset, which puts it on the page background behind the frame rather
  than on the frame's `sunken` fill — `focus` on `bg` measures 5.65 light /
  8.92 dark against a 3:1 requirement.
- **Tab is never trapped.** One more Tab leaves the frame. The hint line says so
  in the open, in Vietnamese, so a keyboard user does not have to find out by
  experiment.

**This last point is a binding constraint on M2 and on GAM-16, not a wish.** The
game inside the frame must not swallow Tab or `preventDefault` it, and if the
frame becomes an embedded element at M2 the tab order must still pass through it
and continue. M1 cannot enforce that from here, so it is written down here and
handed on in §13.

---

## 8. Ad slots, and the measured clearance

### 8.1 Inventory, per band

`ad-slots.md` §3 verbatim. This Task adds no slot, removes none, moves none and
**restyles none** — `ads.css` is the sole owner of `.ad-slot` (`ad-slots.md`
§11), and `play.css` contains no `.ad-slot` declaration except the sheet's band
mirror, which restates `display` and the rail slot's two margin overrides and
nothing else.

| Slot | Format | mobile < 768 | compact 768–1023 | wide ≥ 1024 |
| --- | --- | --- | --- | --- |
| `ad-header-leaderboard` | 728 × 90 | absent | present | present |
| `ad-mobile-top` | 320 × 100 | **present** | absent | absent |
| `ad-after-info-rectangle` | 300 × 250 | **present** | present | absent |
| `ad-incontent-banner` | 728 × 90 | absent | present | present |
| `ad-rail-rectangle` | 300 × 250 | absent | absent | **present** |
| **Total on the page** | | **2** | **3** | **3** |

**On mobile there is no sidebar**: exactly one banner above the frame and one
after the game info. **Exactly one 300 × 250 box exists at every width** — the
rail one above 1024 px, the after-info one below it, never both and never
neither.

### 8.2 Measured clearance, per slot, per breakpoint

Clearance is the empty distance between the ad's border box and the frame's
border box, on the axis along which the two are adjacent. **No slot is adjacent
to the frame on more than one axis**, so one number per slot is the whole claim:
the four in-flow slots sit above or below the frame and overlap it horizontally,
and the rail slot sits beside it and is 250 px tall against a frame that is
353 px tall at 1024 px and 488 px at 1280 px, so it always falls within the
frame's height.

| Slot | Band | Frame is | Clearance | Arithmetic, in tokens |
| --- | --- | --- | --- | --- |
| `ad-mobile-top` | mobile | below | **24 px exactly** | the slot's own `margin-block-end` = `--space-ad-clearance` |
| `ad-after-info-rectangle` | mobile | above | **≥ 64 px** | 40 frame→`game-meta` (`--space-3xl`) + 24 slot `margin-block-start`, plus `game-meta`'s own height |
| `ad-header-leaderboard` | compact | below | **≥ 146 px** | 24 slot `margin-block-end` + 1 header border + 48 `main` padding (`--space-4xl`) + 32 `h1` margin (`--space-2xl`) + the `h1`'s own line box (≥ 41.6) |
| `ad-after-info-rectangle` | compact | above | **≥ 72 px** | 48 frame→`game-meta` (`--space-4xl`) + 24 slot `margin-block-start`, plus `game-meta`'s height |
| `ad-incontent-banner` | compact | above | **≥ 370 px** | the ≥ 72 above, plus the rectangle's 250 height and the 24 + 24 between the two slots |
| `ad-header-leaderboard` | wide | below | **≥ 146 px** | identical to the compact row |
| `ad-rail-rectangle` | wide | inline-start | **32 px exactly** | the play grid's `column-gap` = `--space-2xl`; the binding minimum is `--space-ad-clearance` = 24 |
| `ad-incontent-banner` | wide | above | **≥ 72 px** | 48 frame→`game-meta` (`--space-4xl`) + 24 slot `margin-block-start`, plus `game-meta`'s height |

Every number is a token, never a literal. The two exact cases — `ad-mobile-top`
at 24 and `ad-rail-rectangle` at 32 — are the tight ones; everything else has
the whole `game-meta` block between it and the frame on top of the arithmetic.

**One row's arithmetic differs from `ad-slots.md` §5.2's, and it is worth
naming rather than banking quietly.** That document derives the header slot's
clearance as 24 slot margin + **16 header padding** + 48 `main` padding + 32
title margin, "plus the title's own height", and states the result as ≥ 120 px.
This page's header has no block padding of its own — the slot's 24 px margin
sits directly against the header's 1 px bottom border — so the corresponding
term here is **1, not 16**. Counting the `h1`'s own line box, which §5.2 leaves
out of its stated figure, the clearance is **146.6 px** in both bands: above the
≥ 120 px the spec states, and far above the 24 px floor. `ad-slots.md` §4 allows
a later Task to increase a clearance and never to decrease one, and the measured
total here is an increase.

**The two margins always add, never absorb each other.** Each page column is a
flex column with **no `gap`** (`ad-slots.md` §4.3, and `play.css` §1 says so in
the rule itself): margins of flex items never collapse, so the slot's own 24 px
is always added to the separation the page already has. That is what makes the
column above a set of sums a reviewer can add up.

### 8.3 The other four rules

- **No ad inside the frame, and no overlap.** Every slot is a *sibling* of the
  frame in normal flow; `.game-frame .ad-slot` matches nothing, and §10 checks
  it. In the wide band at 1280 px the rail slot's inline-start edge is 900 px
  from the content-column origin and the frame's inline-end edge is at 868 px;
  in the other two bands nothing is beside the frame at all.
- **No sticky, no fixed.** `ads.css` writes `position: static`, `float: none`,
  `z-index: auto` and `transform: none` onto `.ad-slot`, and `play.css`
  overrides none of the four. The site's one sticky surface is the consent
  banner, which is exactly why `ad-slots.md` §7 forbids a sticky ad. No ad on
  this page moves relative to the page while it scrolls.
- **Fixed sizes, reserved up front.** Each slot's block size is an explicit
  pixel value applied by the stylesheet, in force at the first paint — before
  any creative, before any consent decision, before any request. Expected CLS
  contribution from the ad slots: **zero**.
- **No ad network, tag, script, embedded frame, pixel or tracker** in any of
  this Task's three files. Every slot is an empty placeholder box.

### 8.4 No ad is adjacent to the controls

`game-meta` always sits between the frame and the slot below it, and the
controls are inside `game-meta` (§5.1). So the nearest ad to any control is the
whole height of the facts list and the how-to-play text away, plus the 24 px
floor — `requirements.md` §5.2 satisfied structurally rather than by a measured
value that a later edit could erode.

---

## 9. Open items, and the three things that want a decision

### 9.1 The controls row is this Task's judgement call — for the reviewer

SOU-4 names "game controls" as a region to design and also forbids assuming the
game platform contract. Those two pull in opposite directions, because most
controls a player expects on a play page are calls into the game. §5.2 resolves
it by shipping only the two controls the *page* can perform on its own, and by
naming the omitted ones explicitly.

**For `web-engineer`:** is "Toàn màn hình" genuinely page-side? The intent is
`requestFullscreen()` on the frame box, which does not ask the game for
anything — but if a game is eventually embedded as a separate document, the
embedding element needs the fullscreen permission, and that *is* a line in the
contract. If you think it is, say so and the control becomes an M6 item instead,
leaving "Tải lại trò chơi" as the row's only control and the page with no
primary action.

### 9.2 The viewport height cap on the frame — for the reviewer and the Board

`play.css` §2's `.page .game-frame { --game-frame-max-inline: calc(100dvh * 16 / 9) }`
is the one thing on this page no upstream document asked for (§4.3). It solves
a real case — a short landscape phone — and it cannot reduce any clearance,
because narrowing the frame only moves its edges away from the slots beside it.
If the reviewer or the Board reads it as out of scope, deleting those two lines
changes nothing else on the page.

### 9.3 `.skeleton__block--fill` was not used — for M1-04's owner

`components.css` §17 offers `.skeleton__block--fill` for exactly this page's
loading frame: "the game frame's placeholder, where the 16:9 is reserved by
`.state--frame`". Reading the rules together, its `block-size: 100%` resolves
against a grid row that `.state`'s `align-content: center` leaves content-sized,
so the percentage has no definite height to resolve against and the bar looks
likely to collapse to nothing. **This Task had no browser, so that is a reading
of the CSS and not a measurement**, and rather than patch a library component
from a page (`components.md` §7.1 forbids it) the loading frame uses ordinary
`.skeleton__bar`s instead. The frame's reservation does not depend on the bars
in either case: it is the 16:9 box itself.

For M1-04's owner to confirm in a browser and either fix or document.

### 9.4 The weekly board shows three rows and no "your rank"

A player outside the top three sees no row of their own here. That is
deliberate: "your result" is a region `wireframes.md` §4.3 puts on the
**leaderboards** page, and `components.md` §4.4 gives it its own four states
there. Duplicating it here would be designing part of M1-07 in M1-06. The board
links to that page, from every state.

### 9.5 The ad-slot label is still an unmeasured pair

`ads.css` sets `.ad-slot__label` to `text-muted` on the slot's `sunken` fill;
`DESIGN.md` does not publish that pair, and `components.md` §2.1's surface rule
says "`sunken` carries only `text`". `home.md` §9.3 raised this for the Board as
an M1-02 item. **It is re-raised here because this page carries three slots
rather than one**, and this Task can no more fix it than M1-05 could:
`ad-slots.md` §11 forbids restyling `.ad-slot`, and there was no measuring tool
in this environment, so no ratio is published here either. Either the pair is
measured and added to `DESIGN.md`, or the label moves to `text`.

### 9.6 Design tooling was not available

`google-design-md` and the taste skill may be used only after GAM-19 passes and
the Board installs them (security-architecture §11). **Neither is enabled for
this agent, and neither was used.** No uninstalled tool was substituted for
either. This matches what `components.md` §8.4 and `home.md` §9.4 recorded.

---

## 10. How to verify

No build, no server, no dependency: open
[`../prototypes/play.html`](../prototypes/play.html).

### 10.1 Six checks a reviewer can run

From the repository root. All six must return **nothing**.

```sh
# 1. No script, no embedded frame, no font file, no network reference of any
#    kind. (<link rel="stylesheet" href="..."> is relative and is deliberately
#    not matched — the pattern catches a remote reference, not a local one.)
grep -nEi '<script|<iframe|@font-face|src=|https?:' \
  docs/design/prototypes/play.html

# 2. No colour literal anywhere in the stylesheet.
grep -nE '#[0-9a-fA-F]{3,8}|rgba?\(|hsla?\(' \
  docs/design/prototypes/assets/play.css

# 3. No inline style attribute.
grep -n 'style="' docs/design/prototypes/play.html

# 4. No breakpoint other than M1-01's two switches.
grep -nE '@media' docs/design/prototypes/assets/play.css \
  | grep -vE '768px|1024px'

# 5. Nothing on this page lifts an ad slot out of normal flow.
grep -nE '^[[:space:]]*(position:[[:space:]]*(sticky|fixed)|float:[[:space:]]*(left|right)|z-index:[[:space:]]*[0-9]|transform:[[:space:]]*(translate|scale|rotate|matrix|skew|perspective))' \
  docs/design/prototypes/assets/play.css

# 6. No negative margin anywhere — a negative margin is the only way a static
#    box can be pulled over a sibling. The minus has to be preceded by a space
#    or a colon: `--space-2xl` is a token name, not a negative number, and an
#    unanchored `-[0-9]` would match it and waste a reviewer's time.
grep -nE 'margin[a-z-]*:[^;]*[[:space:]:]-[0-9]' \
  docs/design/prototypes/assets/play.css
```

**Why these commands are here and not in `play.html`.** Check 1's pattern
searches for a source attribute and a protocol prefix *in that file*. Printed
inside it, the command would match itself and the check would fail on its own
documentation. A markdown file is outside the scanned set.

### 10.2 Measure every clearance, in the sheet

The sheet holds five renders, so there is no single `#game-frame` to select.
Scope the snippet to one view and run it once per view — `.mk-view--mobile`,
`.mk-view--compact`, `.mk-view--desktop`:

```js
const view = document.querySelector('.mk-view--desktop');   // then --compact, --mobile
const f = view.querySelector('.game-frame').getBoundingClientRect();
for (const el of view.querySelectorAll('.ad-slot')) {
  const cs = getComputedStyle(el);
  if (cs.display === 'none') continue;
  const a = el.getBoundingClientRect();
  console.log(el.className.replace('ad-slot ', ''), {
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
console.log('frame', Math.round(f.width) + 'x' + Math.round(f.height));
console.log('ads inside the frame:',
            document.querySelectorAll('.game-frame .ad-slot').length);
```

The clearance for a slot is its **one positive gap**; the other three are
negative and meaningless. **The number of lines printed is itself part of the
check: two in the mobile view, three in the other two.**

| View | Slot | `box` | clearance | `overlaps` | `position` |
| --- | --- | --- | --- | --- | --- |
| mobile 375 | `--mobile-top` | `320x100` | `gapAbove` = 24 | `false` | `static` |
| mobile 375 | `--after-info-rectangle` | `300x250` | `gapBelow` ≥ 64 | `false` | `static` |
| compact 768 | `--header-leaderboard` | `704x90` | `gapAbove` ≥ 146 | `false` | `static` |
| compact 768 | `--after-info-rectangle` | `300x250` | `gapBelow` ≥ 72 | `false` | `static` |
| compact 768 | `--incontent-banner` | `704x90` | `gapBelow` ≥ 370 | `false` | `static` |
| desktop 1280 | `--header-leaderboard` | `728x90` | `gapAbove` ≥ 146 | `false` | `static` |
| desktop 1280 | `--rail-rectangle` | `300x250` | `gapRight` = 32 | `false` | `static` |
| desktop 1280 | `--incontent-banner` | `728x90` | `gapBelow` ≥ 72 | `false` | `static` |

Frame, same snippet: **343 × 193** in the mobile view, **704 × 396** in the
compact view, **868 × 488** in the desktop view. `ads inside the frame` must
print **0**.

Any `overlaps: true`, any `position` other than `static`, any clearance below
24, or any slot in a band it does not belong to **fails the review**.

### 10.3 Measure the two zero-shift claims

The frame's four displays, within one view — every number must be identical:

```js
const v = document.querySelector('.mk-view--mobile');
[...v.querySelectorAll('.game-frame')].map(e => {
  const r = e.getBoundingClientRect();
  return `${Math.round(r.width)}x${Math.round(r.height)}`;
});
```

Expected: the ready, loading and "needs JavaScript" boxes are identical. **The
error box in the mobile view is the documented exception of §6.2** and may be a
few pixels taller; in the 1280 view all four are identical.

The weekly board's four displays — every number must be the same, to within the
collapsed hairline borders:

```js
[...document.querySelectorAll('.weekly-board .board, .weekly-board__state')]
  .map(e => Math.round(e.getBoundingClientRect().height));
```

### 10.4 Confirm no shift at all, on the real widths

Reload with this running, at 375, 768 and 1280:

```js
let cls = 0;
new PerformanceObserver(l => {
  for (const e of l.getEntries()) if (!e.hadRecentInput) cls += e.value;
}).observe({ type: 'layout-shift', buffered: true });
setTimeout(() => console.log('CLS', cls), 3000);
```

Expected **`CLS 0`**. Then prove the reservation survives something arriving in
a slot, and survives a squeezed flex column — `ad-slots.md` §10.3 gives both
snippets and they apply unchanged to this page's `.play-main` and `.play-rail`.

### 10.5 By eye

1. **Five renders.** Light/dark × 375/1280, plus the compact band at 768.
2. **Count the slots in each:** two at 375, three at 768, three at 1280, and
   exactly one 300 × 250 box in all three.
3. **Tab through the sheet.** The ring is always visible and never hidden; no ad
   slot takes focus; the game frame is exactly one stop and Tab leaves it.
4. **Nothing animates.** No shimmer on the skeleton bars, no pulse. Set
   `prefers-reduced-motion: reduce` and nothing changes.
5. **Greyscale it.** The error block, the warning notice and the player's own
   row are all still readable, because none of them is colour alone.
6. **Drag a real page slowly through 1024 px.** The rail appears and the
   after-info rectangle disappears in the same frame, with exactly one
   300 × 250 box visible throughout — never two, never none.
7. **No horizontal scrollbar** at 375, 768, 900, 1000, 1024 or 1280 px.

---

## 11. Contrast

**This page introduces no new foreground/background pair.** Every pair it uses
was already measured — in `DESIGN.md` → "Measured contrast", or in
`components.md` §3.4 for the four `primary-hover` pairs. No ratio below is one
this Task had to produce, and every row carries its source.

| Where it is used | Foreground | Background | Light | Dark | Needs | Source | |
| --- | --- | --- | --- | --- | --- | --- | --- |
| Frame placeholder caption | `text` | `sunken` | 13.24 | 15.27 | 4.5 | DESIGN.md | PASS |
| Game info title, names in the weekly board | `text` | `surface` | 15.58 | 12.66 | 4.5 | DESIGN.md | PASS |
| Text in a state block and in the notice | `text` | `bg` | 14.65 | 14.19 | 4.5 | DESIGN.md | PASS |
| Keyboard-contract line, facts labels | `text-muted` | `surface` | 6.65 | 6.83 | 4.5 | DESIGN.md | PASS |
| State block sentence | `text-muted` | `bg` | 6.25 | 7.66 | 4.5 | DESIGN.md | PASS |
| "Xem bảng xếp hạng đầy đủ", secondary button on a card | `primary` | `surface` | 6.05 | 7.72 | 4.5 | DESIGN.md | PASS |
| "Thử lại" in a state block, footer links | `primary` | `bg` | 5.69 | 8.65 | 4.5 | DESIGN.md | PASS |
| "Toàn màn hình" label | `on-primary` | `primary` | 6.08 | 8.13 | 4.5 | DESIGN.md | PASS |
| "Toàn màn hình" label, hovered | `on-primary` | `primary-hover` | 8.55 | 9.63 | 4.5 | DESIGN.md | PASS |
| Hovered button or link, on a card | `primary-hover` | `surface` | 8.49 | 9.14 | 4.5 | components.md §3.4 | PASS |
| Hovered button or link, on the page | `primary-hover` | `bg` | 7.98 | 10.25 | 4.5 | components.md §3.4 | PASS |
| The week badge | `secondary` | `surface` | 5.97 | 6.77 | 4.5 | DESIGN.md | PASS |
| Error icon and the word "Lỗi" | `error` | `bg` | 6.31 | 7.46 | 4.5 | DESIGN.md | PASS |
| Warning icon and the word "Cảnh báo" | `warning` | `bg` | 6.09 | 8.09 | 4.5 | DESIGN.md | PASS |
| Focus ring on the frame and in a state block | `focus` | `bg` | 5.65 | 8.92 | 3 | DESIGN.md | PASS |
| Focus ring in the header and on a card | `focus` | `surface` | 6.01 | 7.96 | 3 | DESIGN.md | PASS |
| Focus ring on the player's own row | `focus` | `sunken` | 5.11 | 9.60 | 3 | DESIGN.md | PASS |
| Frame edge, state block edge, secondary button edge | `border-strong` | `bg` | 3.48 | 3.58 | 3 | DESIGN.md | PASS |
| Secondary button edge on a card | `border-strong` | `surface` | 3.71 | 3.19 | 3 | DESIGN.md | PASS |
| The player's own row edge | `border-strong` | `sunken` | 3.15 | 3.85 | 3 | DESIGN.md | PASS |

**The focus ring on the frame is the row worth checking twice.** The frame is
`sunken`-filled, but the ring is drawn with a 2 px offset, so it lands on the
page background behind the frame, not on the fill — 5.65 light / 8.92 dark
against a 3:1 requirement. That offset is load-bearing and `base.css` says so.

**Two pairs below 3:1, on purpose.** The card edge and the table row rule
(`border` on `surface`) and the skeleton bar (`border` on `bg`, 1.33 light /
1.50 dark) are decorative: they carry no information, the bars are
`aria-hidden`, and the "it is loading" signal is the live-region text beside
them. WCAG 1.4.11 exempts pure decoration, and both are already recorded as
decorative by `DESIGN.md` and `components.md` §3.3. **A control boundary on this
page is always `border-strong`, never `border`.**

One pair this page uses and cannot measure is the ad-slot label — see §9.5. It
belongs to `ads.css` and to M1-02, and no ratio is invented for it here.

---

## 12. What this Task did not verify

Recorded plainly, because an unverified claim presented as verified is worse
than a gap.

- **No rendered visual check. No browser was available in this environment**, so
  **the sheet has not been opened and looked at.** The markup and stylesheet are
  checked statically — the six commands in §10.1 all return nothing, tag balance
  and unique `id`s were verified mechanically, and every `<use>` resolves to a
  symbol defined in the same document — but *how it looks* is for the
  `web-engineer` review and the `qa` pass, not a claim this Task is making.
- **The clearances in §8.2 are derived, not browser-measured.** Every term in
  them is a fixed token applied by a stylesheet, so the arithmetic is
  deterministic and a reviewer can add it up — but it is arithmetic over the CSS,
  not a `getBoundingClientRect()` reading. §10.2 is the command that turns it
  into a measurement, and it is a review step. The same is true of the frame
  sizes in §4.2 and of the reserved height in §6.3.
- **No contrast was re-measured.** Every ratio in §11 is quoted with its source.
  This Task computed none of them and had no tool to.
- **The keyboard order is designed, not exercised.** §7's table is what the DOM
  says; that a real browser agrees is a review step. So is the claim that the
  frame does not trap Tab — in M1 the frame is an empty box, so the real test
  arrives with the game at M2.
- **The Vietnamese font check remains open**, as `components.md` §8.3 left it.
  No platform was tested here.
- **`.skeleton__block--fill`** was reasoned about, not observed (§9.3).

---

## 13. Handoff

**To `web-engineer` (the named reviewer).** SOU-4 asks you for the feasibility
of the frame sizing and, specifically, that the ad slots cannot shift the frame.
The three things that most want your judgement:

1. **§8.2 and §8.3** — the frame's size is set by a ratio on a column width, and
   every slot's box is set by an explicit pixel height in `ads.css`. Neither
   reads the other, so no slot can resize the frame. Is that argument complete?
2. **§9.1** — is "Toàn màn hình" genuinely page-side, or does it need something
   from the game platform contract?
3. **§9.2** — the viewport height cap. In or out?

**To M1-07 (leaderboards).** The weekly board here is a view, not a design: it
reuses `.board` and links to your page. "Your result", the period switch and the
full table are yours, and this page deliberately shows none of them (§9.4). The
link target this page assumes is `/leaderboards/<game_id>`.

**To M2 (the stack).** §3 is the markup to lift. Two properties of the frame are
contracts, not styling: **16:9 at every width** (every ad clearance is measured
against it) and **one tab stop that Tab can leave** (§7.1). When the frame stops
being an empty box, both must survive.

**To M4 (tests).** Every claim in §7, §8 and §10 is a Playwright assertion: the
tab order, the visible ring on the frame, the live-region roles, the per-band ad
inventory, the clearance of every slot at 375 / 768 / 900 / 1000 / 1024 /
1280 px, `.game-frame .ad-slot` matching nothing, and CLS = 0.

**To M6 (ads).** Nothing here requests a creative. This page carries three slots
above 768 px and two below it; `ad-after-info-rectangle` and
`ad-rail-rectangle` carry the same 300 × 250 format and are mutually exclusive
by width, so target whichever is on the page and never both.

**To the Board.** §9.2 (the viewport cap), §9.5 (the ad-slot label's unmeasured
pair, re-raised from `home.md` §9.3) and §9.6 (the design tooling was not
installed, so it was not used).

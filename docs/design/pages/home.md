# Home / game list — `/`

**Status:** Normative for the home page's layout and states. Milestone M1
(Design), Task SOU-3 (M1-05). Owner: `ui-designer`, through `project-manager`.
Last updated: 2026-11-05.

The mockup is [`../prototypes/home.html`](../prototypes/home.html) and is static
HTML: open it in a browser and it works. No server, no build step, no
dependency, no network request.

It is downstream of five documents and contradicts none of them, with **one
exception that is raised rather than resolved** — see §7.1.

| Source | What it fixes | What this document does |
| --- | --- | --- |
| [`../../requirements.md`](../../requirements.md) §2, §5 | the page list, the card's fields, the ad rules | builds the page to them |
| [`../DESIGN.md`](../DESIGN.md) (M1-01) | colour, type, spacing, shape, motion, breakpoints, the measured contrast table | designs inside the measured pairs, inventing no value |
| [`../ad-slots.md`](../ad-slots.md) (M1-02) | ad inventory, sizes, clearance, no-sticky | places the one slot this page has, exactly as specified |
| [`../wireframes.md`](../wireframes.md) (M1-03) §4.1 | regions, reading order, which field comes from where | makes those regions high-fidelity; moves none, drops none, reorders none |
| [`../components.md`](../components.md) (M1-04) | every component on the page, and the three state patterns | reuses them; **adds no shared component** |

---

## 1. Scope — and what is deliberately absent

In scope: the page's composition at both design widths, in both themes, with
every state; which fields the card consumes; where the one ad slot sits; the
keyboard order; and the contrast of every pair used.

**Deliberately absent, and a defect if a later Task reads one into this page:**

- **The manifest format.** The card's fields are tagged by *purpose* — cover
  art, title, short description, controls, play link — never by key name. The
  format is GAM-16 and out of M1 entirely. §4 says where a value comes from, not
  what the key is called.
- **A new shared component.** Nothing here is added to `components.css`. The one
  place this Task composes on top of a shared component is named in §7.2 and
  reported rather than promoted.
- **A new colour, type step, spacing step, radius or breakpoint.** All five
  belong to M1-01. This page introduces none, and §6 shows that every pair it
  uses was already measured.
- **Any framework, build step, runtime dependency, package manifest, analytics,
  tracker, font file, ad tag or third-party request.**

---

## 2. The page, in one paragraph

A quiet hero, then a grid of games. The hero is the only place on the site that
spends `--text-display` and `--radius-xl`, which is why it is a raised plate
rather than bare text — an `xl` radius needs an edge to be a radius of. Below it
the grid is one column at 375 px and three columns with a 32 px gap at 1280 px,
and each card is the same component in both. **The chrome stays deliberately
plain, because the thumbnails are the colourful part.** If the page looks
slightly bare with the cover art removed, that is correct and it is
`DESIGN.md`'s governing rule, not an oversight.

---

## 3. The real page markup

The mockup sheet stacks four renders in one document, so its chrome is demoted
out of the landmark roles and its headings sit one level deeper
(`components.md` §2.3 fixes that convention, and `home.html` §8.2 restates it).
**This is the markup of the real page**, with the real landmarks and the real
heading levels, so M2 can lift it directly rather than un-demote the sheet.

```html
<body class="page">
  <a class="skip-link" href="#main">Tới nội dung chính</a>

  <header class="site-header">            <!-- banner -->
    <div class="shell site-header__bar">
      <a class="wordmark" href="/">Soucean Game</a>
      <nav aria-label="Chính">            <!-- navigation -->
        <button class="btn btn--quiet site-nav__toggle" type="button"
                aria-expanded="false" aria-controls="nav">…Menu</button>
        <ul class="site-nav__list" id="nav">
          <li><a class="site-nav__link" href="/" aria-current="page">Trò chơi</a></li>
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
    <section class="page-section hero">
      <h1 class="hero__title">Soucean Game</h1>
      <p class="hero__lede">Một câu giới thiệu.</p>
    </section>

    <section class="page-section">
      <h2 class="page-section__title">Trò chơi</h2>
      <ul class="game-grid">
        <li class="game-card">
          <img class="game-card__art" src="…" alt="" width="…" height="…">
          <h3 class="game-card__title">Tên trò chơi</h3>
          <p class="game-card__desc">Mô tả ngắn.</p>
          <p class="game-card__meta">Điều khiển: …</p>
          <dl class="game-card__top3">…</dl>
          <p class="game-card__action">
            <a class="btn btn--secondary btn--block" href="/games/…">Chơi<span
               class="u-visually-hidden"> Tên trò chơi</span></a>
          </p>
        </li>
        …
      </ul>
    </section>
  </main>

  <footer class="site-footer">…</footer>  <!-- contentinfo -->
</body>
```

**Heading levels on the real page:** exactly one `<h1>` (the hero), then `<h2>`
for the grid's section, then `<h3>` for each card title. No level is skipped and
nothing below `h3` is needed.

**On the cover art.** The sheet draws `.game-card__art` as an empty `<div>`
because it has no image to show and must make no network request. On the real
page it is an `<img>` with an **empty `alt`**: the title immediately below it is
the accessible name of the game, so describing the art as well would make a
screen-reader user hear the same game twice. It carries `width` and `height` so
the 16:9 box is reserved before the file arrives — the same anti-shift mechanism
`components.md` §5.7 already specifies.

---

## 4. The card, and the fields it consumes

**Six slots. Five come from the game's own manifest; one does not.**

| | Slot | Source | Note |
| --- | --- | --- | --- |
| a | Cover art, 16:9 | **manifest — cover art** | Reserves 16:9 before the image arrives. The one place the *game* supplies colour. |
| b | Title | **manifest — title** | `<h3>`. **Not a link** — see §5. |
| c | Short description | **manifest — short description** | One to two lines. The layout must not depend on the string's length. |
| d | Controls | **manifest — controls** | The fifth field, added by `requirements.md` §2 via `wireframes.md` §4.1. |
| e | This week's top 3 | **platform data — not the manifest** | Which is why it is the one part of a card with its own three states. |
| f | "Chơi" action | **manifest — play link** | The card's only control. |

SOU-3 names four fields — *title*, *cover art*, *short description*,
*play link*. *Controls* is the fifth and comes from `requirements.md` §2. **The
card consumes these five manifest fields and no others.**

**The manifest format is not designed here.** That is GAM-16 and outside M1. The
table says *where a value comes from*; it proposes no key name and no schema,
and a later Task must not read one out of it.

---

## 5. One tab stop per card

`wireframes.md` §4.1 left open how a card's primary action is expressed when a
view holds N cards, and `components.md` §6.2 decided it. This page implements
that decision and does not re-open it:

- The "Chơi" button is `.btn--secondary`, **never** `.btn--primary`. A grid of N
  equal games has no single primary action, so making every card primary would
  make none of them primary. **The home page therefore has no filled button at
  all**, which is the honest answer rather than a gap.
- **One control per card, out of the card's own chrome.** The art is not a link
  and the title is not a link. Three links per game would give a keyboard user
  three stops per game and a screen-reader user three identical entries in a
  links list. This rule is about the card's chrome and never forbade the top-3
  strip's own *Thử lại* in its error state (§6.3) — see the two bullets below.
- The pointer hit area is stretched over the whole card by `::after`, so a mouse
  can click anywhere while the **accessible** target stays the one button.
- The focus ring is drawn around **the whole card**, because the whole card is
  what will activate. A ring around the button alone would describe the wrong
  target.
- **Both of those last two hold only while "Chơi" is the card's only control.**
  When the top-3 strip is in its error state the card holds *Thử lại* as well,
  and `components.css` §11 switches the stretch, the card ring and the ring
  suppression off together, so *Thử lại* is clickable and each control draws
  its own ring. This is `accessibility-report.md` A-01 and A-13, fixed in the
  component library; the page adds no class and no markup for it.
- The accessible name is "Chơi *&lt;tên trò chơi&gt;*" via a visually hidden
  span.

### Full tab order

| # | Stop | Note |
| --- | --- | --- |
| 1 | `skip-link` → `#main` | First in the document, **before** the header ad slot. |
| 2 | Wordmark | Links to `/`. |
| 3 | Nav disclosure | Below 768 px only; above it the button is gone and the links are inline. |
| 4–6 | Trò chơi · Bảng xếp hạng · Tài khoản | Current page marked by weight **and** underline **and** `aria-current="page"` — never colour alone. |
| 7–8 | Theme: Sáng · Tối | Two real radios. Tab enters the group, arrow keys move within it — the native radio contract, with no JavaScript. |
| — | *the ad slot* | **Not a stop.** No `tabindex`, no interactive content. |
| 9 … 9+N | **One stop per card** | Two for a card whose top-3 strip is in its error state: *Thử lại* comes before "Chơi", in DOM order, and each draws its own focus ring (§6.3). |
| last | Four footer links | Each a 44 px target. |

Nothing on this page is sticky, there is no dialog and there is no focus trap,
so no focused control is ever obscured (WCAG 2.2 *Focus Not Obscured*).

---

## 6. States

### 6.1 The grid

| State | Block | Copy |
| --- | --- | --- |
| **Empty** | `.state--empty .state--reserve-lg` | "Chưa có trò chơi nào" · "Danh sách sẽ xuất hiện khi trò chơi đầu tiên được thêm." |
| **Loading** | the real grid, holding skeleton cards — see §6.2 — with `aria-busy="true"` on the block that is replaced | visually hidden "Đang tải danh sách trò chơi…", in the persistent live region beside that block (`components.md` §4.3 note 3) |
| **Error** | `.state--error .state--reserve-lg`, `role="status"` | "Không dựng được danh sách trò chơi" · "Kiểm tra kết nối rồi thử lại." · *Thử lại* |

**The empty state carries no action, on purpose.** `components.md` §4.2 makes
the action optional, and there is nothing a player can do to add a game. A
button that leads nowhere is worse than no button. An empty state is also not an
error: it carries no error colour and no alarm.

**The error state is `role="status"`, not `role="alert"`.** The player did not
ask for this load, so the failure must wait for a pause rather than cut them
off. The *Thử lại* button is reachable by Tab whenever they get there.
`components.md` §4.3 fixes this and the reasoning is not re-litigated here.

### 6.2 The loading grid, and why it is not a `.state` block

SOU-3 requires that the loading state "uses skeleton cards that reserve the
final card size so the grid does not jump". **A single `.state` block cannot
meet that**, because it reserves exactly one height, and what must not move here
is the grid's column geometry *and* each card's box.

So the loading grid is **the real `.game-grid` holding real `.game-card`
shells**, each containing a `.skeleton` that stands in for the card's six slots.
The consequence is the point: the grid has the same columns, the same 32 px gap,
the same card padding and the same card radius whether it is loading or loaded,
because it is the same two components in both states. There is no second layout
for a breakpoint to get wrong.

Each skeleton child reserves the **line box of the element it replaces**,
computed from the same type tokens that element reads:

| Class | Reserves | Expression |
| --- | --- | --- |
| `.sk-reserve--title` | `.game-card__title` | `--text-headline-sm × --leading-headline-sm` |
| `.sk-reserve--line` | one line of `.game-card__desc` | `--text-body-sm × --leading-body-sm` |
| `.sk-reserve--meta` | `.game-card__meta` | `--text-caption × --leading-label` |
| `.sk-reserve--top3` | the whole top-3 strip | `2 × --space-md + label line + 3 × body-sm line` |
| `.sk-reserve--action` | the card's action row | `--target-min` |

They are `calc()` expressions rather than heights on purpose: a height would be
a literal, and a literal would drift from the type scale the moment the scale
moved.

**What is exactly reserved and what is approximate — stated plainly.** The
thumbnail (16:9), the action row (`--target-min`) and the top-3 strip are exact.
The three text rows reserve the line box of *one* line each; a real description
that wraps to two lines on a narrow card is one line taller than its skeleton.
That is a property of content-driven card heights, not a defect that more CSS
would fix, and it is recorded here rather than claimed away.

**Nothing animates.** No shimmer, no pulse. `DESIGN.md`'s emotional target is
"nothing blinks, nothing pulses", and a shimmer sweep is the clearest violation
of it as well as the loading idiom most likely to trouble a reader with
vestibular sensitivity. The "it is working" signal is the live-region text; the
bars are `aria-hidden`, because a shimmer tells a screen-reader user nothing.

### 6.3 The top-3 strip

Per `components.md` §4.4, with `.state--reserve-sm`:

| State | Copy |
| --- | --- |
| Empty | "Chưa có điểm tuần này" · "Hãy là người đầu tiên." |
| Loading | 3 skeleton rows carrying `aria-busy="true"`; the visually hidden "Đang tải bảng tuần…" sits in the persistent live region beside them, per `components.md` §4.3 note 3 |
| Error | "Không tải được bảng tuần" · *Thử lại* |

**A broken strip does not break the card.** The art, title, description and
"Chơi" button are all still there and still work — **and so does *Thử lại***.
In the error state the card has two tab stops, not one: `components.css` §11
drops the stretched hit area and the card-level ring for exactly this case, so
the retry button is reachable by pointer and each control shows its own focus
ring (`accessibility-report.md` A-01, A-13). See §5's card bullets. The state block supplies its
own `bg` fill inside the `surface` card — that is the surface rule of
`components.md` §2.1, not decoration: it is what lets a state sit inside a card
without this Task inventing a colour or publishing an unmeasured ratio.

---

## 7. Contrast

**This page introduces no new foreground/background pair.** Every pair it uses
was already measured — in `DESIGN.md` → "Measured contrast", or in
`components.md` §3.4 for the four `primary-hover` pairs. No ratio below is one
this Task had to produce, and every row carries its source.

| Where it is used | Foreground | Background | Light | Dark | Needs | Source | |
| --- | --- | --- | --- | --- | --- | --- | --- |
| Hero title, card title | `text` | `surface` | 15.58 | 12.66 | 4.5 | DESIGN.md | PASS |
| Text in a state block | `text` | `bg` | 14.65 | 14.19 | 4.5 | DESIGN.md | PASS |
| Hero lede, card description and controls | `text-muted` | `surface` | 6.65 | 6.83 | 4.5 | DESIGN.md | PASS |
| State block sentence | `text-muted` | `bg` | 6.25 | 7.66 | 4.5 | DESIGN.md | PASS |
| "Chơi" label on a card, nav link | `primary` | `surface` | 6.05 | 7.72 | 4.5 | DESIGN.md | PASS |
| "Thử lại" in a state block, footer link | `primary` | `bg` | 5.69 | 8.65 | 4.5 | DESIGN.md | PASS |
| Hovered button or link, on a card | `primary-hover` | `surface` | 8.49 | 9.14 | 4.5 | components.md §3.4 | PASS |
| Hovered button or link, on the page | `primary-hover` | `bg` | 7.98 | 10.25 | 4.5 | components.md §3.4 | PASS |
| Error icon and the word "Lỗi" | `error` | `bg` | 6.31 | 7.46 | 4.5 | DESIGN.md | PASS |
| Focus ring on the page | `focus` | `bg` | 5.65 | 8.92 | 3 | DESIGN.md | PASS |
| Focus ring on a card, in the header | `focus` | `surface` | 6.01 | 7.96 | 3 | DESIGN.md | PASS |
| State block edge, secondary button edge | `border-strong` | `bg` | 3.48 | 3.58 | 3 | DESIGN.md | PASS |
| Secondary button edge on a card | `border-strong` | `surface` | 3.71 | 3.19 | 3 | DESIGN.md | PASS |
| Text on the ad slot's fill | `text` | `sunken` | 13.24 | 15.27 | 4.5 | DESIGN.md | PASS |

**Two pairs below 3:1, on purpose.** The card edge (`border` on `surface`) and
the skeleton bar (`border` on `bg`, 1.33 light / 1.50 dark) are decorative: they
carry no information, the skeleton bars are `aria-hidden`, and the "it is
loading" signal is the live-region text beside them. WCAG 1.4.11 exempts pure
decoration, and both are already recorded as decorative by `DESIGN.md` and
`components.md` §3.3. **A control boundary on this page is always
`border-strong`, never `border`.**

---

## 8. Ad slots

The home page carries **exactly one** slot, and only from 768 px up:
`ad-header-leaderboard`, **728 × 90**, in the site header. In the mobile band it
carries **none**. Both facts are `ad-slots.md` §3 verbatim, and this Task adds
no slot, removes none, moves none and restyles none — `ads.css` is the sole
owner of `.ad-slot` (`ad-slots.md` §11).

**The 24 px clearance rule does not apply on this page**, because it measures
the distance to the *game frame* and the home page has none. The wireframe's own
measure table already says so.

The slot sits in the header **after** the skip link, so one keystroke passes it
(`ad-slots.md` §9.4). It is never focusable, never traps focus, is never sticky,
and contains nothing interactive. There is no ad network, ad tag, script,
`iframe`, pixel or third-party request anywhere in this Task's files — M1 is
design, M6 is integration.

---

## 9. Open items, and one conflict

### 9.1 The home grid's loading state contradicts `components.md` §4.4

`components.md` §4.4 gives the home grid **no** loading state, on the stated
ground that the grid is built from the manifests at build time
(`wireframes.md` §4.1, `requirements.md` §2). **SOU-3's acceptance criteria
require one**, in terms specific enough to be deliberate: skeleton cards that
reserve the final card size.

This Task implemented SOU-3's instruction, because it is the later and more
specific one, and raises the contradiction here rather than silently picking a
side. Three outcomes are available and the choice is not this Task's:

1. **Keep it.** The grid may well be rendered client-side once M2 picks the
   stack, in which case the state is needed and the anti-shift guarantee is
   real. `components.md` §4.4 then gains a row.
2. **Drop it.** If the grid is guaranteed to be build-time HTML forever, the
   state is unreachable and the markup is dead weight. §3 of `home.html` and
   §6.2 above would be removed; nothing else changes.
3. **Keep it, scoped.** Mark it as the state for a *client-side re-render*
   (filter, search, pagination) rather than for first paint.

**For:** the reviewer (`web-engineer`) and the Board.

### 9.2 A missing primitive, reported rather than invented

`components.md` §7.1 forbids a page from adding a variant to the shared library.
The thing this page wanted and did not find is **a skeleton block that reserves
a named element's line box**. It was composed page-side as the five
`.sk-reserve--*` classes in §6.2 — all token-derived, none of them a colour or a
spacing value — rather than promoted into `components.css`.

**Recommendation:** if the reviewer agrees the pattern is general, these belong
in `components.css` §17 next to `.skeleton__bar--title`, and this page's copies
should be deleted. If not, they stay page-scoped and the next page Task that
needs them will re-derive them, which is the cost of leaving them here.

### 9.3 The ad slot's label is an unmeasured pair

`ads.css` sets `.ad-slot__label` to `text-muted` on the slot's `sunken` fill.
`DESIGN.md` does not publish that pair, and `components.md` §2.1 states the rule
it sits against: "`sunken` carries only `text`". This Task cannot fix it —
`ad-slots.md` §11 forbids restyling `.ad-slot` — and had no measuring tool in
this environment, so it does not publish a ratio of its own.

**For:** the Board, as an M1-02 item. Either the pair is measured and added to
`DESIGN.md`, or the label moves to `text`.

### 9.4 Design tooling was not available

`google-design-md` and the taste skill may be used only after GAM-19 passes and
the Board installs them (security-architecture §11). **Neither is enabled for
this agent, and neither was used.** No uninstalled tool was substituted for
either. This matches what M1-04 recorded in `components.md` §8.4.

---

## 10. How to verify

No build, no server, no dependency: open
[`../prototypes/home.html`](../prototypes/home.html).

### 10.1 Four checks a reviewer can run

From the repository root. All four must return **nothing**.

```sh
# 1. No script, no iframe, no font file, no network reference of any kind.
#    (<link rel="stylesheet" href="..."> is relative and is deliberately not
#    matched — the pattern catches a remote reference, not a local one.)
grep -nEi '<script|<iframe|@font-face|src=|https?:' \
  docs/design/prototypes/home.html

# 2. No colour literal anywhere in the stylesheet.
grep -nE '#[0-9a-fA-F]{3,8}|rgba?\(|hsla?\(' \
  docs/design/prototypes/assets/home.css

# 3. No inline style attribute.
grep -n 'style="' docs/design/prototypes/home.html

# 4. No breakpoint other than M1-01's switch.
grep -nE '@media' docs/design/prototypes/assets/home.css | grep -v '768px'
```

**Check 2 is the strong one.** The only literals left in `home.css` are the
1 px hairline borders and `768px` inside the media query — where CSS cannot read
a custom property, which is the same literal, for the same reason, that
`tokens.css`, `base.css`, `components.css` and `ads.css` each repeat and each
document.

**Why these commands are here and not in `home.html`.** Check 1's pattern
searches for an `src` attribute and an `http` prefix *in that file*. Printed
inside it, the command would match itself and the check would fail on its own
documentation. A markdown file is outside the scanned set, which is the only
place the command stays true.

### 10.2 By eye

1. **Four renders in §1.** Light/dark × 375/1280.
2. **Tab through the sheet.** The focus ring is always visible, never hidden,
   and the order matches the visual order. Each card is one stop and the ring
   wraps the whole card.
3. **The skip link works** — each render's link jumps to that render's `main`.
4. **Nothing animates.** No shimmer on the skeleton bars. Set
   `prefers-reduced-motion: reduce` and nothing changes, because nothing moved.
5. **Greyscale it.** The error state is still readable, because it carries an
   icon and the word "Lỗi" as well as a colour.
6. **Measure the zero-shift claim.** In the Console, compare the empty and error
   blocks in §3's dark 1280 render:
   `[...document.querySelectorAll('.grid-state')].map(e => e.getBoundingClientRect().height)`
   — every number must be the same.
7. **Remove the cover art** and the page looks slightly plain. That is correct.

### 10.3 Widths

The two design widths only: **375** and **1280**. This page is governed by the
`switch` (768 px) alone; `wide` (1024 px) belongs to the play page's side rail
and is used nowhere here.

---

## 11. What this Task did not verify

Recorded plainly, because an unverified claim presented as verified is worse
than a gap.

- **No rendered visual check.** No browser was available in this environment, so
  **the sheet has not been opened and looked at.** The markup and stylesheet are
  checked statically (§10.1) and structurally — tag balance, unique `id`s and
  every `<use>` resolving to a symbol defined in the same document were all
  verified mechanically — but *how it looks* is for the `web-engineer` review and
  the `qa` pass, not a claim this Task is making.
- **No contrast was re-measured.** Every ratio in §7 is quoted with its source.
  This Task computed none of them and had no tool to.
- **The keyboard order is designed, not exercised.** §5's table is what the DOM
  says; that a real browser agrees is a review step.
- **The Vietnamese font check remains open**, as `components.md` §8.3 left it.
  This page sets Vietnamese throughout, including stacked marks at `display`
  size in the hero, so it is further evidence for that check — but no platform
  was tested here.

---

## 12. Handoff

**To `web-engineer` (reviewer).** The two things worth your judgement are §9.1
(does the home grid have a loading state at all?) and §6.2 (is the skeleton-card
construction the right one, and should the five `.sk-reserve--*` classes be
promoted into `components.css`?). The card-grid feasibility question SOU-3 names
is §3's markup and §5's one-stop-per-card decision.

**To M2 (the stack).** §3 is the markup to lift. The grid is built from the
manifests; this page states which five fields it reads and nothing about how
they are spelled.

**To M4 (tests).** Every claim in §5, §6 and §10.2 is a Playwright assertion:
the tab order, the visible ring around the card rather than the button, the
live-region roles, the one-slot-above-768-and-none-below inventory, and the
zero-shift guarantee between the three grid states.

**To M6 (ads).** Nothing here requests a creative. This page has one slot and it
is the header leaderboard.

# Component library — Soucean Game

**Status:** Normative for component design and for the three shared state
patterns. Milestone M1 (Design), Task GAM-25 (M1-04). Owner: `ui-designer`,
through `project-manager`. Last updated: 2026-11-04.

This document designs the shared components **once**, so that the five page
Tasks that follow (M1-05…M1-09) can run in parallel without redesigning the
same button five times. It also fixes the **canonical empty, loading and error
state patterns** that every page fills in — which is how the "states for every
page" requirement is met without duplicating it eight ways.

The drawings are in [`prototypes/components.html`](prototypes/components.html)
and are static HTML: open the file in a browser and it works. No server, no
build step, no dependency, no network request.

It is downstream of four documents and contradicts none of them:

| Source | What it fixes | What this document does |
| --- | --- | --- |
| [`../requirements.md`](../requirements.md) §2, §4, §5 | the pages, the data rules, equal-weight consent, the ad rules | builds components that obey them |
| [`DESIGN.md`](DESIGN.md) (M1-01) | colour, type, spacing, shape, motion, breakpoints, the **measured contrast table**, the component inventory | designs the named inventory, inside the measured pairs, inventing no value |
| [`ad-slots.md`](ad-slots.md) (M1-02) | ad geometry, clearance, no-sticky | does not touch `.ad-slot` at all (§11 of that document) |
| [`wireframes.md`](wireframes.md) (M1-03) | regions, reading order, where each state goes | designs what those sheets marked, and answers the three questions they left open |

---

## 1. How to read this, and how to check it

### 1.1 What the specimen sheet shows

Every component is rendered **four times**: light and dark theme, at **375 px**
and **1280 px** — the only two design widths M1-01 permits. Every interactive
component shows all five states: default, hover, **keyboard focus**, active and
disabled.

Both themes appear on one page with no script, because `<html>` carries
`data-theme="light"`. That excludes `tokens.css`'s `prefers-color-scheme` block,
so the light panels are light even for a reader whose machine is set to dark,
and the dark panels are the ones carrying `data-theme="dark"` themselves. The
selector `[data-theme='dark']` matches any element, not just `:root`, which is
what makes a dark panel inside a light page possible at all.

### 1.2 The forced-state convention

A static page cannot hover itself. Every interactive state is therefore written
as a pair in `components.css`:

```css
.btn--primary:hover, .btn--primary.is-hover { ... }
```

The specimen goes through **the same declaration block** as the real
pseudo-class, so a reviewer is looking at the rule that will ship, not a
hand-drawn imitation of it. `.is-hover`, `.is-focus`, `.is-active` exist for the
sheet and must never appear on a page. The real focus ring is still proved by
tabbing, not by `.is-focus`.

### 1.3 The one thing the sheet cannot fake

`tokens.css` §7 steps five type tokens — `--text-display`, `--text-headline-lg`,
`--text-headline-md`, `--text-headline-sm`, `--text-body-lg` — in a **viewport**
media query on `:root`. A 375 px panel cannot restate that step without writing
type-size literals into `components.css`, which is exactly the drift the
no-raw-values rule exists to prevent.

So **in a window ≥ 768 px wide, the 375 px panels render those five tokens at
their desktop size.** It affects headings only; it changes no layout, no
component geometry and no state. To read the mobile panels at their true type
scale, narrow the window below 768 px. In the product the question does not
arise, because there the band comes from the viewport.

This is the same trade `wireframes.md` §2 recorded, for the same reason, and it
is recorded here rather than left to be rediscovered.

### 1.4 Checks a reviewer can run

From the repository root. All four must return **nothing**.

```sh
# 1. No script outside the one justified inline block, no iframe, no font
#    file, no network reference of any kind.
grep -nEi '<iframe|@font-face|@import +url\(["'"'"']?http|src=|https?:' \
  docs/design/prototypes/components.html

# 2. No colour literal anywhere in the stylesheet.
grep -nE '#[0-9a-fA-F]{3,8}|rgba?\(|hsla?\(' \
  docs/design/prototypes/assets/components.css

# 3. No inline style attribute on the sheet.
grep -n 'style="' docs/design/prototypes/components.html

# 4. No breakpoint other than M1-01's switch.
grep -nE '@media' docs/design/prototypes/assets/components.css | grep -v '768px'
```

Check 2 is the strong one. The only length literals in `components.css` are
hairline edges (`1px`, `2px`), the reset constants in `.u-visually-hidden`, the
1 px press shift, and `768px` inside the media query — where CSS cannot read a
custom property, which is why `tokens.css` repeats the same literal in its own
`@media` rules and says so.

`@import url('tokens.css')` on line 1 is a **relative, same-directory** import.
It is the one `@import` in the file and it makes no network request; check 1's
pattern is written to catch a remote one and let this one through.

### 1.5 By eye

1. **Four renders per component.** Light/dark × 375/1280, on every section.
2. **Tab through the whole sheet.** The focus ring is always visible, never
   hidden behind anything, and the order matches the visual order.
3. **Open the real dialog** (§8 of the sheet). Tab cycles inside it; Esc closes
   it; focus returns to the button that opened it.
4. **Nothing animates.** No skeleton shimmer, no pulse. Set
   `prefers-reduced-motion: reduce` and nothing changes, because nothing moved.
5. **Greyscale it.** Every status is still readable, because every status
   carries an icon and a word as well as a colour.

---

## 2. The rules that bind every component here

### 2.1 The surface rule — the one that shapes everything

`DESIGN.md` measured a specific **set** of foreground/background pairs and is
explicit: *"if you need a new colour, you do not have one"*, and *"do not use a
colour whose ratio against its real background has not been measured and written
down here."*

This Task takes that literally. **Every pair used in this library is a pair
`DESIGN.md` already measured.** No new pair is introduced, so no ratio in §3 is
one this Task had to produce. In practice that means:

- **`sunken` carries only `text`** (plus the focus ring and a `border-strong`
  edge). No muted text, no accent and no meaning colour is ever set on a sunken
  fill — those pairs were never measured.
- **`surface` carries** `text`, `text-muted`, `primary`, `secondary`, `error`,
  `focus`, `border-strong` — but **not** `success` and **not** `warning`, which
  `DESIGN.md` measured against `bg` only.
- **Anything needing a meaning colour or a skeleton bar supplies its own `bg`
  fill.** That is why the inline notice and all three state blocks are
  `bg`-filled wells with a `border-strong` edge, in every context, *including
  inside a card*.

That last point looks like a styling choice and is not. It is what lets a
success notice sit inside a card without this Task either inventing a colour or
publishing a ratio it had no tool to measure. See §8.1 for why that tool was not
available.

### 2.2 The rules inherited from M1-01

- **One primary action per view.** `.btn--primary` is the only filled button and
  appears once. Everything else is secondary, quiet or destructive.
- **`secondary` (Clay) is never an action.** `.badge` is the only component that
  uses it, and nothing in `.badge` is focusable.
- **Status is never colour alone.** Icon plus word, always.
- **Every control has a visible text label**, or an accessible name plus a 44 px
  target if it is icon-only.
- **Inputs have visible labels**, never placeholder-only, with errors below the
  field and linked by `aria-describedby`.
- **Minimum target 44 × 44 px** at every breakpoint, ≥ 8 px between adjacent
  targets.
- **No ad slot is restyled.** `ads.css` owns `.ad-slot` and `ad-slots.md` §11
  says M1-04 must not touch it. There is no `.ad-slot` rule in
  `components.css`.

### 2.3 Landmarks and heading levels on a real page

The specimen sheet deliberately **demotes** its chrome, because a document may
have only one `banner`, one `contentinfo` and one `main`, and §2 of the sheet
shows four copies of each. So on the sheet:

- the header is a `<div class="site-header">`, never a `<header>` element — a
  plain `div` is never a landmark;
- the footer **is** a `<footer>` element, but nested inside `<section>`, which
  demotes it out of `contentinfo` by the HTML spec's own rule;
- `main` is a `<div class="shell main" tabindex="-1">`, not a `<main>` element —
  demoted out of the landmark, but it still has to answer the skip link, so it
  keeps the `tabindex` the real element carries (see the bullet below);
- each pane's `<nav>` is a real `<nav>` and stays one — several labelled
  navigation landmarks in a document are valid, and keeping them real is what
  lets the nav component be reviewed as a landmark at all.

A **real page** uses the elements, not the demoted stand-ins:

```html
<body>
  <a class="skip-link" href="#main">Tới nội dung chính</a>
  <header class="site-header">        <!-- banner -->
    …wordmark, theme toggle…
    <nav aria-label="Chính">…</nav>   <!-- navigation -->
    …header ad slot, AFTER the skip link…
  </header>
  <main id="main" tabindex="-1">…</main>  <!-- main -->
  <footer class="site-footer">…</footer>  <!-- contentinfo -->
  <aside class="consent" aria-label="Cookie">…</aside>  <!-- last in body -->
</body>
```

- Exactly **one `<h1>` per page**, then `<h2>` sections, then `<h3>` within
  them. No level is skipped.
- A `game-card` title is an `<h3>` on the home page (under the grid's `<h2>`).
  On the sheet it is rendered as `<h4>` because the sheet's own outline puts it
  one level deeper; the page level is the one above.
- A `dialog` title is an `<h2>` inside the dialog — a dialog starts its own
  outline, so it does not continue the page's.
- The skip link is **first in the document and before the header ad slot**, so
  one keystroke passes the banner (`ad-slots.md` §9.4).
- **`#main` carries `tabindex="-1"`, and so does the sheets' `<div class="shell
  main">` stand-in.** The attribute is not decoration and not a tab stop: `-1`
  makes the element programmatically focusable without putting it in the tab
  order. The design calls `.focus()` on `#main` by name — after Chấp nhận /
  Từ chối and after the cookie dialog saves (`privacy-consent.md` §9, because
  the button that opened the dialog no longer exists) — and on an element that
  is not focusable that call silently does nothing: focus falls back to
  `<body>` and the next Tab starts again at the skip link. Fixes
  `accessibility-report.md` A-02. The skip link itself is unaffected either
  way; current browsers set the sequential focus navigation starting point on
  fragment navigation, so this is a scripted-focus fix, not a skip-link one.

---

## 3. Contrast

### 3.1 Method, and what it is not

Ratios in §3.2 are quoted from `DESIGN.md` → "Measured contrast". Ratios in
§3.4 are measured in this Task from the token hex values in
`prototypes/assets/tokens.css`, using the same WCAG 2.1 formula:

- convert sRGB channels to linear light;
- compute relative luminance `L = 0.2126R + 0.7152G + 0.0722B`;
- compute contrast as `(L_lighter + 0.05) / (L_darker + 0.05)`.

Values are shown to 2 decimals by truncation to match `DESIGN.md`'s display
style.

Requirements applied: **4.5:1** for body text, **3:1** for large text (≥ 24 px
regular or ≥ 19 px semibold) and for non-text UI boundaries and the focus
indicator.

### 3.2 The pairs this library uses

Every one appears in `DESIGN.md`'s tables. Light theme first, dark theme in the
same row — the two themes were measured independently and both pass.

| Where it is used | Foreground | Background | Light | Dark | Needs | |
| --- | --- | --- | --- | --- | --- | --- |
| Body text on the page | `text` | `bg` | 14.64 | 14.19 | 4.5 | PASS |
| Text on a card, header, dialog, banner | `text` | `surface` | 15.58 | 12.66 | 4.5 | PASS |
| Text in the player's own rank row | `text` | `sunken` | 13.24 | 15.26 | 4.5 | PASS |
| Help text, card description, state body | `text-muted` | `bg` | 6.25 | 7.65 | 4.5 | PASS |
| Muted text on a card or in the footer | `text-muted` | `surface` | 6.65 | 6.82 | 4.5 | PASS |
| Link, secondary/quiet button label on the page | `primary` | `bg` | 5.69 | 8.66 | 4.5 | PASS |
| Link, secondary/quiet button label on a card | `primary` | `surface` | 6.06 | 7.72 | 4.5 | PASS |
| Primary button label | `on-primary` | `primary` | 6.11 | 8.15 | 4.5 | PASS |
| Primary button label, hovered | `on-primary` | `primary-hover` | 8.57 | 9.65 | 4.5 | PASS |
| Badge text | `secondary` | `surface` | 5.97 | 6.78 | 4.5 | PASS |
| Success notice title and icon | `success` | `bg` | 5.75 | 8.63 | 4.5 | PASS |
| Warning notice title and icon | `warning` | `bg` | 6.08 | 8.09 | 4.5 | PASS |
| Error notice, error state, error summary | `error` | `bg` | 6.30 | 7.45 | 4.5 | PASS |
| Field error text, destructive button label | `error` | `surface` | 6.71 | 6.65 | 4.5 | PASS |
| Focus ring on the page | `focus` | `bg` | 5.65 | 8.90 | 3 | PASS |
| Focus ring on a card or dialog | `focus` | `surface` | 6.01 | 7.94 | 3 | PASS |
| Focus ring on the own-rank row | `focus` | `sunken` | 5.11 | 9.58 | 3 | PASS |
| Control edges on the page | `border-strong` | `bg` | 3.48 | 3.57 | 3 | PASS |
| Control edges on a card | `border-strong` | `surface` | 3.70 | 3.19 | 3 | PASS |
| Own-rank row edge | `border-strong` | `sunken` | 3.14 | 3.84 | 3 | PASS |
| Primary fill as a boundary | `primary` | `bg` | 5.69 | 8.66 | 3 | PASS |

### 3.3 Two pairs that are below 3:1 on purpose

`DESIGN.md` already records both as **decorative**, with these ratios. They are
repeated here because this library uses them, and so that a later Task does not
mistake a skeleton bar for a boundary.

| Pair | Light | Dark | Where, and why it is exempt |
| --- | --- | --- | --- |
| `border` on `bg` | 1.32 | 1.49 | **The skeleton bar.** It carries no information and is `aria-hidden`; the "it is loading" signal is the live-region text beside it. WCAG 1.4.11 exempts pure decoration. Also the decorative divider and the card edge. |
| `surface` on `bg` | 1.06 | 1.12 | Surface separation is carried by the border and the radius, never by the fill. A `.btn--secondary` inside a `bg`-filled state block is delimited by its `border-strong` edge (3.48 / 3.57), not by its fill. |

### 3.4 Measured `primary-hover` pairs

`primary-hover` on `bg` and on `surface` is used for hovered links and hovered
secondary buttons. `DESIGN.md` publishes `primary-hover` only against
`on-primary`, so these four uses are measured here with the method in §3.1.

| Pair | Exact ratio | Reported (2dp, truncated) | Needs | Result |
| --- | --- | --- | --- | --- |
| Light: `primary-hover` `#175347` on `bg` `#F3F3F0` | 7.98801646 | 7.98 | 4.5 | PASS |
| Light: `primary-hover` `#175347` on `surface` `#FAFAF8` | 8.49762230 | 8.49 | 4.5 | PASS |
| Dark: `primary-hover` `#7FD7C5` on `bg` `#181B1C` | 10.25159729 | 10.25 | 4.5 | PASS |
| Dark: `primary-hover` `#7FD7C5` on `surface` `#212527` | 9.14832303 | 9.14 | 4.5 | PASS |

All four are comfortably above AA.

### 3.5 Disabled controls

`base.css` renders a disabled control at `opacity: 0.5`, which drops it below
4.5:1 against its background. **WCAG 2.1 SC 1.4.3 explicitly exempts** text that
is "part of an inactive user interface component". The reduced contrast is the
signal, and it is never the only one: a disabled control also carries the
`disabled` attribute (announced as "dimmed"/"unavailable"), a `not-allowed`
cursor, and in every case in this library a visible label saying what is
happening — "Đang lưu…", not a greyed-out "Lưu" with no explanation.

The same paragraph covers a button carrying **`aria-disabled="true"`**, which
gets the identical treatment by an explicit rule in `components.css` §7 — see
§5.5. The exemption applies for the same reason: the control is inactive, and
the dimming is a signal rather than text the user must read.

---

## 4. The three state patterns

This is the part every page reuses rather than reinvents.

### 4.1 One shell, three meanings, one reserved height

All three states are the **same box**: same padding, same border, same radius,
same `min-block-size`. Only the content inside differs.

That is what stops the page jumping — and not only at the moment loading
finishes. It also means **loading → error** moves nothing, and **empty → first
score** moves nothing. Switching between any two of the three is a zero-shift
transition, which is a stronger guarantee than "loading reserves the final
size" on its own.

A region reserves its height by choosing a modifier:

| Modifier | Height | Use |
| --- | --- | --- |
| `.state--reserve-sm` | `2 × space-6xl` (160 px) | the top-3 strip, a short weekly board, "your result" |
| `.state--reserve-md` | `3 × space-6xl` (240 px) | a full leaderboard, the account profile block |
| `.state--reserve-lg` | `5 × space-6xl` (400 px) | the home game grid |
| `.state--frame` | 16:9, no fixed height | the game frame — its height follows its width, and `ad-slots.md` measures every clearance against that box, so it must stay exactly 16:9 at every width |

Every value is a multiple of a spacing token. There is no pixel literal.

### 4.2 The anatomy and the copy pattern

**Empty** — icon · title · one sentence · optional one action.

- The **title names what is missing**, from the player's point of view: "Chưa có
  ai ghi điểm tuần này", not "Không có dữ liệu".
- The **sentence says what happens next**, and where there is something to do,
  the action says it: "Hãy là người đầu tiên." · *Chơi ngay*.
- An empty state is **not** an error. It carries no error colour and no alarm.

**Loading** — a visually hidden status line · skeleton bars shaped like the
content they stand in for.

- **Nothing animates.** `DESIGN.md`'s emotional target is "nothing blinks,
  nothing pulses, nothing demands attention that has not earned it", and a
  shimmer sweep is the clearest violation of it. It is also the loading idiom
  most likely to trouble a reader with vestibular sensitivity. The bars are
  static, which incidentally makes `prefers-reduced-motion` a non-issue here
  rather than a thing to remember.
- The **"it is working" signal is the live-region text**, because a shimmer
  tells a screen-reader user nothing at all. The bars are `aria-hidden`.

**Error** — icon · the word **"Lỗi"** · title naming what failed · one sentence ·
a **retry** action.

- Never a bare "Đã có lỗi xảy ra". The title says *what* failed: "Không tải được
  bảng xếp hạng".
- The sentence says what the player can do: "Kiểm tra kết nối rồi thử lại."
- **There is always a way forward.** Every error state in this library carries
  *Thử lại*.

### 4.3 The ARIA roles — and why loading is polite

This is the part a screen-reader pass checks, so it is specified exactly.

| Situation | Markup | Why |
| --- | --- | --- |
| A region is loading | `aria-busy="true"` on the state block, and the status text in a **separate** polite live region **beside** it — the two attributes never share an element, see note 3 | `status` is `aria-live="polite"`: it waits for a pause rather than cutting the reader off. A load the user did not ask for must not interrupt them. Putting `aria-busy` on the announcing element instead defers that announcement until busy clears, and busy never clears on a block that is replaced. |
| A background load **failed** | `role="status"` on the state block | Still polite. The user is reading; the failure is not more urgent than the sentence they are in the middle of. The retry button is reachable by Tab whenever they get there. |
| The user's **own action** failed (save, delete, consent) | `role="alert"` on the notice | `alert` is `aria-live="assertive"`. The user just acted and is waiting for the result, so interrupting is correct — that *is* the answer to what they asked. |
| The user's own action **succeeded** | `role="status"` on the notice | Polite. Good news can wait for a pause. |
| A form failed validation on submit | `role="alert"` on `.error-summary`, and move focus to it (`tabindex="-1"`) | Announced *and* reached, so a keyboard user is put at the list of what to fix rather than left at the submit button. |
| A field is invalid | `aria-invalid="true"` on the control, `aria-describedby` pointing at the error text | Announced when the user arrives at the field, which is when it is useful. No live region — the error is not new by then. |

Three rules behind the table:

1. **A live region must exist in the DOM before the content is put into it.**
   Insert the empty `role="status"` container with the region, then fill it.
   Creating the element and its text in one step is the single most common
   reason a correctly-marked-up status is never announced.
2. **`aria-busy="true"` goes on the block that is loading, and comes off when it
   is done.** It suppresses partial announcements while the region churns.
3. **The announcing role and the busy flag never go on the same element.**
   `role="status"` marks the thing that speaks.
   `aria-busy="true"` marks the thing that is churning. They are never one
   element. This is rule 1 and rule 2 read together, and it is the one that was got
   wrong across the whole set (`accessibility-report.md` A-03). `aria-busy`
   asks assistive technology to *defer* announcing the region it is on. A
   loading block is **replaced** when the data arrives rather than having
   `aria-busy` set to `false`, so busy never clears on it and the deferred
   announcement never fires — and since the skeleton bars are `aria-hidden`,
   the user is told nothing at all.

   The live region is therefore a sibling of the block, present at rest and
   outside what gets replaced — the same shape `.consent-form__status` already
   uses on the privacy page:

   ```html
   <!-- Present and EMPTY at rest; the "it is working" signal is put HERE. -->
   <div class="u-visually-hidden" role="status">Đang tải bảng tuần…</div>

   <!-- Replaced wholesale when the data arrives. Carries aria-busy, no role. -->
   <div class="state state--loading" aria-busy="true">
     <div class="skeleton" aria-hidden="true"> … </div>
   </div>
   ```

   A page may give the region a named class instead of `.u-visually-hidden`
   when the text is meant to be seen; what it may not do is move the region
   back inside the block, or put `role="status"` on the block itself.

   **Caveat `accessibility-report.md` A-03 states and this Task keeps:** exact
   screen-reader behaviour with `aria-busy` on a live region varies by
   implementation, so this is a markup-level rule rather than an observed
   silence. It is correct either way; a real NVDA / VoiceOver pass in M2
   should confirm it.

   The **error** block is unchanged and keeps `role="status"` on itself: it
   carries no `aria-busy`, it is inserted rather than replaced, and it holds
   the retry control the reader needs to reach.

### 4.4 Which page fills in which state

Normative. The copy below is the pattern; a page Task may adjust wording to its
subject but may not change the structure, drop the retry, or invent a fourth
state block.

| Page · region | Empty | Loading | Error |
| --- | --- | --- | --- |
| **Home** · game grid | "Chưa có trò chơi nào" — "Danh sách sẽ xuất hiện khi trò chơi đầu tiên được thêm." | — (the grid is built at build time, so it has no loading state — `wireframes.md` §4.1) | "Không dựng được danh sách trò chơi" · *Thử lại* |
| **Home** · top-3 strip in a card | "Chưa có điểm tuần này" | 3 skeleton rows at the strip's height | "Không tải được bảng tuần" · *Thử lại* |
| **Play** · game frame | — | `.state--frame`, "Đang tải trò chơi…" | "Không tải được trò chơi" · *Thử lại*. **"Needs JavaScript" is not an error** — it is a `notice--warning`, because nothing failed. |
| **Play** · weekly board | "Chưa có ai ghi điểm tuần này" — "Hãy là người đầu tiên." · *Chơi ngay* | 3 skeleton rows | "Không tải được bảng xếp hạng" · *Thử lại* |
| **Leaderboards** · each board | "Chưa có ai ghi điểm" — "Bảng sẽ mở khi có điểm đầu tiên." | 10 skeleton rows | "Không tải được bảng xếp hạng" · *Thử lại* |
| **Leaderboards** · your result | **The fourth state** `wireframes.md` §4.3 asks for: "Bạn chưa có điểm nào" — "Chơi một ván để xuất hiện trên bảng." · *Chơi ngay* | 1 skeleton row | "Không tải được kết quả của bạn" · *Thử lại* |
| **Account** · profile | — | 1 skeleton row in the name field; while saving, the button reads "Đang lưu…" and is `disabled` | Moderation: `.field__error` under the field, `aria-describedby`. Submit failure: `.error-summary` + `role="alert"` + focus. |
| **Account** · delete my data | — | the button reads "Đang xóa…" and is `disabled` | `notice--error` inside the dialog, `role="alert"` |
| **Privacy · Terms · About** | — (static) | — | page-level 404/500 only, owned by that page's Task |
| **Cookie** · saving a choice | — | the button reads "Đang lưu…" and is `disabled` | `notice--error` inside the settings dialog, `role="alert"` |

**The "fourth state" is a fourth *fill*, not a fourth block.** "Bạn chưa có điểm
nào" is the empty pattern with different copy. `wireframes.md` §4.3 asked M1-04
to design all four; three blocks cover them.

---

## 5. Component reference

Each entry: what it is for · its states · its keyboard behaviour · which pages
use it.

### 5.1 Icons

Twelve line icons, drawn in this Task on a 24 × 24 grid at the uniform 1.5 px
stroke `base.css` sets. They are **original geometry** — circles, chevrons,
straight segments — so there is **no third-party asset and no licence to
record**, which `AGENTS.md` and this Task's constraints both require. They live
in one inline `<symbol>` sprite at the top of `components.html` and are used via
same-document `<use href="#id">`, which makes no request.

Every icon is `aria-hidden="true" focusable="false"`. The accessible name comes
from the text beside it, or from a `.u-visually-hidden` span when the control is
icon-only. **No icon is ever the only label.**

### 5.2 `skip-link`

**For:** letting a keyboard user reach `main` in one keystroke, past the header
ad.
**States:** off-screen; visible and pinned top-left on focus. Never
`display: none` — that would remove it from the tab order and defeat it.
**Focus ring:** this is the **one control in the library whose ring is drawn
inside its own edge** (`outline-offset` set to the negative of the ring's
*width*, −3 px). Pinned flush to the viewport corner, the shared ring — 3 px at
a +2 px offset — would be painted 2–5 px off-screen on two of its four sides.
Negating the *offset* instead would not be enough: the ring is painted outward
from the outline edge, so at −2 px its last 1 px would still fall outside the
border box. At −3 px it is flush inside that edge — same width, same colour,
same pair (`focus` on `surface`), and all four sides visible. Nothing else may
copy this: everywhere else the offset is what keeps the ring off a primary fill.
**Keyboard:** the **first** focusable element in the document, before the header
ad slot (`ad-slots.md` §9.4). Enter follows it to `#main`.
**Pages:** all eight.

### 5.3 `site-header` · `wordmark` · `primary-nav` · `theme-toggle`

**For:** the chrome every page opens with.
**States:** nav is a disclosure below 768 px, an inline list above it. The
current page is marked by **weight + underline + `aria-current="page"`** — the
CSS follows the attribute, so the mark and the announcement cannot disagree.
**Keyboard:** Tab order is wordmark → nav toggle → nav links → theme toggle.
The disclosure is a `<button>` with `aria-expanded`; Enter/Space toggles it.

#### The two interactive controls, state by state

`components.html` §2 renders the header once as composed chrome and then again
as a **five-state grid**, in both themes at both widths, so these two controls
are held to the same standard as the buttons in §5.5 rather than being shown
only at rest. Where a state does not exist, it is named and its absence is
justified — an omitted state and an impossible state are different claims, and
only the second one is being made here.

| State | `site-nav__link` | `theme-toggle__option` |
| --- | --- | --- |
| default | `text`, no underline; the current page carries weight + 2 px underline + `primary` | `text-muted` on transparent; the chosen option is `on-primary` on a `primary` plate, with weight |
| hover | `primary-hover` + underline | unchosen → `text`; **chosen → the plate moves to `primary-hover`**, label stays `on-primary` |
| keyboard focus | the shared ring: `focus` at the system width and offset, drawn by `base.css` on `:focus-visible`. No nav-specific focus rule exists, and none should | the same ring, moved onto the **visible label** — the real `<input>` is visually hidden, so a ring on the input would be a ring on nothing |
| active | `primary-hover` **and** a 1 px `translateY` — a nav link is both a link (§5.6's colour shift) and a target-sized control (§5.5's press nudge), so it does both | the 1 px `translateY`, the same press every pill-shaped control in this library makes |
| disabled | **does not apply** — see below | **does not apply** — see below |

**Why a nav link has no disabled state.** A route either exists, in which case
it is a link, or it does not, in which case it is absent from the menu. There
is no third condition in which the destination is real but temporarily
unreachable, so there is nothing for a disabled nav link to mean. The Account
link is the case worth naming explicitly: a signed-out player still follows it
— to sign in — so it is never dimmed. If a later milestone does find a route
that must be visible and unusable, that is an `aria-disabled="true"` anchor
**without `href`** per §5.6, and it needs a Task, not a local override here.

**Why the theme toggle has no disabled state.** Both themes always exist, the
choice is entirely local to the browser, and nothing is ever in flight that
could make it briefly unavailable. HTML would happily let the radios carry
`disabled`; this design says they never do.

**One cascade fix this grid forced out.** Drawing the current-page link in
hover exposed that it had none: `.site-nav__link[aria-current='page']` and
`.site-nav__link.is-hover` carry identical specificity (0-2-0) and the
`aria-current` block is written later, so it won and the current page was the
one nav item that did not answer the pointer. The same held for the chosen
theme option, where `:has(input:checked)` scores 0-2-1 against hover's 0-2-0.
Both now have explicit hover rules, and both land on `primary-hover` — the
pair `DESIGN.md` measures at **8.57:1** light and **9.65:1** dark against
`on-primary` (§3.2), so the fix does not spend any contrast.

The theme toggle is a **`<fieldset>` of two real `<input type="radio">`** — two
mutually exclusive choices, not a switch, because a switch implies an "off" this
control has no concept of. Using real radios rather than buttons wearing
`role="radio"` is a deliberate choice, not a detail: the radio role carries a
keyboard contract (arrow keys move within the group, Tab enters and leaves it,
only the checked option is a tab stop), and native radios honour it with **no
script**. Buttons with `role="radio"` would need a roving `tabindex` to keep
that promise — JavaScript this library has no reason to ship. The input is
visually hidden rather than `display: none`, so it stays focusable and
announceable, and the focus ring is moved onto the visible label; a group that
is keyboard-operable but keyboard-invisible would be the worse failure.
**Pages:** all eight.
**Note:** the wordmark is plain text at `headline-md`. There is no logo in M1
and inventing one is a Board decision.

### 5.4 `site-footer`

**For:** About · Terms · Privacy · **Cookie settings** (→ `/privacy#cookies`).
**States:** none.
**Keyboard:** ordinary links, each a 44 px target.
**Pages:** all eight.

### 5.5 `btn` — primary, secondary, destructive, quiet, icon

**For:** every action on the site.
**States:** default · hover · focus · active · disabled, all five rendered.
Hover on a filled button is the `-hover` fill; on an outlined button it is the
**edge** — which keeps hover visible whether the button sits on `bg`, on
`surface`, or inside a state block. Active adds a 1 px downward `translateY`
(one of the two properties `DESIGN.md` allows to move). Disabled is `base.css`'s
`opacity: 0.5` plus `not-allowed`.
**Keyboard:** native `<button>` or `<a>`. Space and Enter on a button, Enter on
a link. Nothing here overrides either. A disabled button is not focusable; where
a page needs the control to stay reachable while unavailable, use
`aria-disabled="true"` on an enabled button and explain why in the label.
**Pages:** primary — play, account (Lưu). Secondary — everywhere, including
every state block's action and all three consent actions. Destructive — account
(Xóa dữ liệu của tôi) and its confirmation dialog. Quiet — nav disclosure,
dialog Hủy, icon close.

**`aria-disabled` is drawn, not only announced — required.** A button carrying
`aria-disabled="true"` **must** render at `opacity: 0.5` with a `not-allowed`
cursor, the same treatment `base.css` gives `:disabled`, and it must not take
the 1 px press shift. `components.css` §7 ships that as
`.btn[aria-disabled="true"]`; a page must not re-style it and must not omit it.
The reason is the rule this system keeps everywhere else, read backwards:
`:disabled` is a CSS pseudo-class and `aria-disabled` is an attribute, so
`base.css`'s selector does not match it, and an unavailable button would be
pixel-identical to an available one — the state announced to a screen reader
and invisible to everyone else, including the sighted keyboard user the
attribute exists to serve. Focus is **not** suppressed: staying focusable is
the whole reason the attribute is used instead of the property.

### 5.6 `link`

**For:** navigation inside running text.
**States:** default (underlined) · hover (thicker underline, `primary-hover`) ·
focus · active · `aria-disabled`.
**Keyboard:** native. A link is **underlined by default**; removing the
underline would make teal the only signal, which this system does nowhere else.
**Pages:** all eight.
**Note:** HTML has no disabled link. Where a page must show one, use an `<a>`
**without `href`** carrying `aria-disabled="true"` — announced as disabled and
not focusable.

### 5.7 `game-card` · `game-grid`

**For:** one game in the home grid.
**States:** default · hover · focus (ring around the **whole card** — but only
while the play link is the card's only control; see §6.2) and, separately, its
top-3 strip has the three data states.
**Keyboard:** **one tab stop per card**, two while the strip is in its error
state and offers *Thử lại*. See §6.2.
**Pages:** home. The strip's shape is reused on the play page.
**Note:** the thumbnail reserves 16:9 before the image arrives, so the grid
cannot jump. The game supplies the only colour on the page — the chrome stays
quiet, which is `DESIGN.md`'s governing rule.

### 5.8 `board` — leaderboard table and rank row

**For:** rank · player · score.
**States:** the player's own row is marked by **four** signals — heavier edge,
heavier weight, `sunken` plate, and the **word "Bạn"** inside the row. None of
them is colour. Region states are the three in §4.
**Keyboard:** a table is not interactive; Tab passes through it to the next
control. A screen reader navigates it with table commands, which is why it is a
real `<table>` with `<th scope="col">` and a `<caption>`, not a grid of divs.
**Pages:** play (short form), leaderboards.

### 5.9 `segmented` — the leaderboard period / scope control

**For:** switching period or scope on the leaderboards.
**States:** default · hover · focus · active · disabled · selected
(`aria-current="page"`).
**Keyboard:** **Tab moves between the options, and arrow keys do not.** These
are links to real routes — `wireframes.md` §4.3 is explicit that they must be,
not a JS-only tab widget — and Tab is how links work. Nothing here carries
`role="tab"`, and **a page Task must not add a roving `tabindex`**: that pattern
belongs to `role="tablist"`, and applying it to links breaks the one thing a
link is good at.
**Pages:** leaderboards. See §6.3 for a scope note a page Task must read.

### 5.10 `field` · `choice` · `error-summary`

**For:** every form input on the site.
**States:** default · focus · invalid (`aria-invalid="true"`) · disabled.
**Keyboard:** native controls throughout. The error summary is `tabindex="-1"`
and receives focus on a failed submit; each item links to its field's `id`.
**Pages:** account (display name), cookie settings (categories), leaderboards
(game selector at 375 px).
**Note:** label visible, always. Help text and error text **below** the control,
both named in `aria-describedby` so both are announced with it. The required/
optional mark is a **word**, not an asterisk — an asterisk alone is not a label
and is read as "star" by some screen readers.
**Target:** on a `.choice` the target is the **row**, not the box. A native
checkbox paints at `--icon-md` (20 px) because that is the size of a glyph; the
44 px of `--target-min` comes from the row, where the `<label for>` stretches to
the row's full height, takes a 44 px floor on its own width, and reaches back
over the flex gap so its box starts at the checkbox's own edge. **Nothing
moves** — the negative start margin is cancelled by an equal start padding —
but the dead strip between control and label, where a near-miss tap used to land
on nothing, is gone, and a short word like "Cần thiết" no longer decides how big
the control is. A page must not shrink the row or set the label to
`display: inline`, which would hand the target size back to the length of the
word.

### 5.11 `dialog`

**For:** the delete confirmation and the cookie settings.
**States:** closed · open. On the sheet it is drawn **in place** so the design
is visible in all four renders, and there is **one real `<dialog>`** behind a
button so the keyboard behaviour can actually be tested.
**Keyboard — and the criterion most often misread:**

> **"No keyboard trap" does not mean Tab must leave the dialog.** WCAG 2.1.2
> requires that the user can **get out** by keyboard. Esc, the Cancel button and
> the close button all do that, and all three return focus to the control that
> opened it. **Tab cycling inside an open modal is the required behaviour**
> (WCAG 2.4.3 and the ARIA dialog pattern), not the violation.

**The backdrop is dark in both themes.** `.dialog::backdrop` reads
`--dialog-scrim`, declared in `components.css` §14 as the dark ramp's
`--slate-900` — a primitive, so it is the same colour under light and dark. It
deliberately does **not** follow the theme. The earlier version reused
`--color-text` on the reasoning "the page's own ink at half strength", which is
true only in light; in dark `--color-text` is a near-white, so opening a dialog
washed the page out instead of dimming it. A scrim has one job, and that job is
the same in both themes.

Native `<dialog>.showModal()` provides the cycle, the inert background, Esc and
the top layer, so the product writes **no focus-management code of its own**.
In a destructive confirmation the **safe choice comes first in the tab order**:
the first thing a keyboard user reaches is the way out, not the irreversible
verb.
**Pages:** account, cookie settings (from the banner or `/privacy#cookies`).

### 5.12 `notice` — info · success · warning · error

**For:** a short message in the flow of a page.
**States:** four meanings, one shell. Each carries an icon **and** a word.
**Keyboard:** not focusable. A notice containing a control (rare) puts it in
normal order.
**Pages:** account (saved, moderation error), play ("needs JavaScript" — a
**warning**, not an error, because nothing failed), privacy (cookie settings
saved), cookie dialog (save failed).
**Note:** the shell supplies its own `bg` fill in every context. That is the
surface rule (§2.1), not decoration.

### 5.13 `consent` — the banner shell

**For:** the shell only. **The consent content, the categories and the legal
wording are M1-09's (GAM-30)** and nothing here decides them.
**States:** present on first visit, absent afterwards.
**Keyboard:** the banner is **not modal** — the page behind stays readable and
operable, and the banner does not trap focus. It is **last in `<body>`**, and it
must never cover the focused control (WCAG 2.2 *Focus Not Obscured*). It is the
site's **one** sticky surface, which is precisely why `ad-slots.md` §7 forbids a
sticky ad.
**Pages:** all eight, on first visit.
**Note:** the three actions are **identical** in size and style — all
`.btn--secondary`. `requirements.md` §2 makes equal weight a requirement, so
"Từ chối" is never smaller, quieter or further away than "Chấp nhận", and
neither is filled while the other is bare. At 375 px they stack full width,
because three Vietnamese labels do not fit on one readable row at 343 px; above
the switch they sit in a row, still identical.

### 5.14 `state` · `skeleton`

Specified in full in §4.

### 5.15 `badge`

**For:** a non-interactive mark — a week label, a category.
**States:** none. **Nothing in it is focusable**, by design: `secondary` (Clay)
is never an action.
**Pages:** leaderboards, play.

### 5.16 The long-form document template

`wireframes.md` §8 handed M1-04 **one** template for Privacy, Terms and About.
It is `.shell--text` — the shared page shell with the content column capped at
the reading measure — plus the components already above: `h1`, a last-updated
line, an in-page contents list of ordinary links, then numbered `h2` sections.
It introduces no new component, which is the point: three pages, one template,
nothing invented.

---

## 6. The three questions M1-03 left open

`wireframes.md` §8 handed exactly three decisions to this Task. Each is decided
here, with the reasoning, so a reviewer can disagree with the argument rather
than guess at it.

### 6.1 How a destructive action is expressed

**Decision: a destructive action uses the secondary geometry wearing `error` —
a `surface` plate, an `error` label, and an `error` edge doubled by an inset
ring. It is never a filled button.**

`DESIGN.md` has no destructive style and the palette's only red is `error`,
which it describes as belonging to error states. M1-03 correctly refused to
invent a colour. This Task does not invent one either: it **extends the role of
an existing token**, which is a design decision M1-03 explicitly delegated here.

Why this shape:

- **It introduces no colour.** `error` on `surface` is 6.71:1 light / 6.65:1
  dark and `error` on `bg` is 6.30 / 7.45 — all four already measured in
  `DESIGN.md`. Nothing new had to be measured.
- **It is deliberately not the loudest thing on the page.** A destructive action
  is *dangerous*, not *important*. A filled red button competes with the primary
  action for attention and invites the mis-click it is supposed to prevent.
- **It keeps "one primary action per view" intact.** On the account page the
  primary action is "Lưu". Deleting your data must not also be a filled button.
- **The doubled edge ships in the default state**, so the control reads as "not
  an ordinary button" *before* anyone hovers it.
- **It is always behind a confirmation dialog**, and the dialog puts the safe
  choice first in the tab order. The confirmation exists to prevent a mistake,
  not to discourage the choice — "Xóa dữ liệu của tôi" is a first-class action
  on the account page (`wireframes.md` §4.4) and this styling must not make it
  feel punished.

**This extends a token's role beyond what `DESIGN.md` wrote down, so it is
flagged for the Board rather than slipped through.** If the Board prefers a
dedicated destructive token, it is a one-line change in `components.css` and a
new row in `DESIGN.md`'s measured table.

### 6.2 How a card's primary action is expressed when a view has N cards

**Decision: one tab stop per card. The "Chơi" button is the card's only
control, styled `.btn--secondary`, and its hit area is stretched over the whole
card.**

- **`button-primary` is not used in the grid.** `DESIGN.md` allows one primary
  action per view, and a grid of N equal games has **no** single primary action.
  Making every card primary would make none of them primary. The home page
  therefore has no filled button, which is the honest answer.
- **One control per card, not three.** Making the thumbnail, the title *and* the
  button each a link would give a keyboard user three stops per game and a
  screen-reader user three identical links. The title is not a link; the button
  is.
- **The whole card is still clickable** for a pointer user, via a `::after`
  stretched over the card from the button.
- **The focus ring is drawn around the whole card**, because the whole card is
  what will activate. A ring around the button alone would describe the wrong
  target.
- **The accessible name is "Chơi <tên trò chơi>"**, via a `.u-visually-hidden`
  span. A list of links all reading "Chơi" is useless out of context, and
  context is exactly what a screen-reader links list removes.

**Three of those bullets stop being true the moment the card holds a second
control** — which it does whenever the top-3 strip is in its error state and
carries *Thử lại* (§4.4). The card then has two tab stops, not one, and:

- the stretched `::after` paints **over** the second control, so a pointer
  click on *Thử lại* lands on the play link and takes the player into the game
  instead (`accessibility-report.md` A-01);
- the card-level ring then describes the whole card for **both** stops, and
  the companion rule strips the ring from the focused control itself, so two
  consecutive tab stops look pixel-identical and neither says what Enter will
  do (A-13).

**So the rule is conditional, and the condition is in the stylesheet, not in
the page's memory.** `components.css` §11 gates all three rules — the stretch,
the card ring, the ring suppression — on one guard, `:not(:has(.state .btn))`.
A card whose only control is the play link behaves exactly as the bullets
above describe. A card that contains a state block with its own control gets
none of the three and falls back to `base.css` §4's per-element ring, which is
already correct for two stops. A page Task adds nothing and remembers nothing;
putting the state block in the card is enough.

Two consequences worth stating, so a later Task does not undo this:

- **"One control per card" is a rule about the card's *own* chrome** — the
  thumbnail, the title, the button — and never meant that a nested state block
  may not carry its retry. `home.md` §7's promise that a broken strip leaves
  the card "still there and still working" depends on that retry being
  reachable.
- **Do not put `.is-focus` on a card that holds a second control** in a mockup
  sheet. The forced-state class is the §1.2 paint convention and bypasses the
  guard, so it would draw the card ring this fix exists to suppress. Paint the
  ring on the focused control instead, which is what the page will really do.

### 6.3 The long-form document template

Decided in §5.16: one template, built from components already in this library.

**One scope note a page Task must read.** This Task was asked for a segmented
control for the leaderboard periods (§5.9), while `wireframes.md` §4.3 shows the
two boards — this week and last week — **displayed at the same time**, stacked
at 375 px and side by side at 1280 px. Both are correct: the component exists
and is specified, and the wireframe's structure is normative.

**If a page Task wants the segmented control to *replace* one of those boards,
that is a structural change and `wireframes.md` must be amended first** — the
same rule `ad-slots.md` applies to ad geometry. The control's natural use
without any amendment is the **game scope** selector (`/leaderboards` →
`/leaderboards/<game_id>`), which `wireframes.md` §4.3 already places on the
page.

---

## 7. Handoff

### 7.1 To M1-05…M1-09 (the page mockups)

- **Reuse these components. Do not re-style them.** If a page needs something
  this library does not have, raise it on the Task — do not add a colour, a
  spacing value or a variant locally.
- **Every page uses the three state blocks in §4**, filled in per §4.4. No page
  designs its own empty, loading or error block.
- **The structure is still `wireframes.md`'s.** This Task designs components; it
  moved no region and reordered nothing.
- **Do not restyle `.ad-slot`.** `ads.css` owns it; `ad-slots.md` §11 says so.
- **`game-meta`'s position between the game frame and the lower banner is
  load-bearing** (`ad-slots.md` §11). Nothing here changes it.
- Link the stack in order: `tokens.css`, `base.css`, `components.css`.

### 7.2 To M2 (the stack) and M4 (tests)

Every claim in §1.4, §1.5 and §4.3 is an assertion a Playwright test can make
once the stack lands: the tab order, the visible ring, the dialog's Esc-and-
return, the live-region roles, and the zero-shift guarantee between the three
states (measure the block's height in all three — it is the same number).

### 7.3 To the Board

One thing wants a decision rather than silent acceptance:

1. **The destructive-button styling extends `error` beyond the role
   `DESIGN.md` gives it** (§6.1).

---

## 8. What this Task did not verify

Recorded plainly, because an unverified claim presented as verified is worse
than a gap.

### 8.1 No full-table contrast audit was performed

This Task measured the four `primary-hover` pairs in §3.4 directly with the
WCAG 2.1 formula from token hex values. It did **not** rerun or re-audit all
`DESIGN.md` contrast rows in this environment. Ratios in §3.2 are therefore
quoted from `DESIGN.md` with source attribution, and only §3.4 is newly
computed in this Task.

### 8.2 No rendered visual check

No browser was available in this environment, so **the sheet has not been opened
and looked at**. The markup and stylesheet are checked statically (§1.4) and
structurally, but the following are for the `qa-engineer` review, not claims
this Task is making:

- that every component looks right in all four renders;
- that the keyboard focus order matches the visual order on the sheet;
- that the live regions actually announce, in a real screen reader;
- that the real dialog's Tab cycle, Esc and focus-return behave as specified.

### 8.3 The Vietnamese font check is still open

`DESIGN.md` → "Typography" left M1-04 an open verification item: render the
stacked-mark pangram at `caption` and `display` size, in both themes, on at
least **one Windows, one Apple and one Android** target, and confirm no
per-character font substitution and no clipping.

**The specimen is built** — §1 of `components.html` carries the pangram at both
sizes in both themes, which is the part this Task could deliver. **No platform
was checked**, because no browser was available. The item therefore **stays
open** and is handed to the `qa-engineer` review and to M2, where the real
product can be opened on real devices. It is not closed here.

### 8.4 Design tooling

`google-design-md` and the taste skill may be used only after GAM-19 passes and
the Board installs them. **Neither was used.** No uninstalled tool was
substituted for either.

---

## 9. Open items

1. **The destructive-button token role** (§6.1) — for the Board.
2. **The Vietnamese font check remains open** (§8.3) — the specimen exists; the
   three-platform pass does not.
3. **The segmented control vs the two-boards-at-once layout** (§6.3) — not a
   conflict today, but the first page Task that wants the control to replace a
   board must amend `wireframes.md` first.
4. **Nothing in this library is sticky except the consent banner**, and it is
   the only focus trap on the site along with the two dialogs. If a later Task
   needs a second sticky surface, `ad-slots.md` §7 has to be revisited, not this
   document.

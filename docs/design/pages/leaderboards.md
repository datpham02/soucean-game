# Leaderboards page — `/leaderboards`, `/leaderboards/<game_id>`

**Status:** Normative for the leaderboards page's layout, controls and states.
Milestone M1 (Design), Task SOU-5 (M1-07). Owner: `ui-designer`, through
`project-manager`. Last updated: 2026-11-05.

The mockup is [`../prototypes/leaderboards.html`](../prototypes/leaderboards.html)
and is static HTML: open it in a browser and it works. No server, no build step,
no dependency, no network request.

This is the page that answers four questions — **this week**, **last week**,
**where am I**, and **what is my best** — and it answers all four without a
sticky row, because the site has exactly one sticky surface and it is the
consent banner.

It is downstream of five documents and contradicts none of them:

| Source | What it fixes | What this document does |
| --- | --- | --- |
| [`../../requirements.md`](../../requirements.md) §2, §5 | the page's content, the data rules, the allowed ad positions | builds the page to them |
| [`../DESIGN.md`](../DESIGN.md) (M1-01) | colour, type, spacing, shape, motion, the breakpoints, the measured contrast table | designs inside the measured pairs, inventing no value |
| [`../ad-slots.md`](../ad-slots.md) (M1-02) | the slot inventory, the sizes, the bands, no-sticky | places the one slot this page has, exactly as specified |
| [`../wireframes.md`](../wireframes.md) (M1-03) §4.3 | the regions, the reading order, where each state goes | makes those regions high-fidelity; moves none, drops none, reorders none |
| [`../components.md`](../components.md) (M1-04) | every component on the page, and the three state patterns | reuses them; **adds no shared component and restyles none** |

---

## 1. Scope — and what is deliberately absent

In scope: the page's composition at both design widths, in both themes, with
every state; the two selectors and their keyboard behaviour; the table and its
semantics; where the player's own rank lives and what happens when they have
none; the one ad slot; the reading order; and the contrast of every pair used.

**Deliberately absent, and a defect if a later Task reads one into this page:**

- **The leaderboard's backend.** There is no backend before M4. SOU-5 says so.
  Nothing here designs a scoring contract, a submission path, a ranking rule, a
  tie-break, a reset job or a retention window. Every figure in the mockup is
  placeholder data.
- **The game platform contract.** GAM-16 is out of M1 entirely. Nothing here
  assumes a manifest key, a score field name or an identity mechanism.
- **The final routes.** `requirements.md` §8 open question 4 leaves the route
  strings to M2. The page is built so that nothing depends on the exact string:
  every selector option is a link or a form target, not a hard-coded path.
- **Any ad network, ad tag, ad script, embedded frame, pixel or tracker.** The
  one slot is an empty placeholder box. M1 is design; M6 is integration, behind
  consent.
- **A new shared component.** Nothing here is added to `components.css`, and
  nothing in `components.css` is restyled. §11 names the two compositions this
  page makes on top of library components and reports them rather than
  promoting them.
- **A new colour, type step, spacing step, radius or breakpoint.** All five
  belong to M1-01. This page introduces none, and §8 shows that every pair it
  uses was already measured.
- **Pagination as a mechanism.** The page shows that there is a next page and
  where the action sits. How paging is expressed in a URL is M2's.
- **Any framework, build step, runtime dependency, package manifest, analytics
  or font file.**

---

## 2. The page, in one paragraph

The title, then which game, then **you**, then this week, then last week. The
player's own result comes *before* either board, because the question "where am
I" should not cost a scroll — and answering it above the table is what lets this
page have no sticky element at all. Both boards are on the page at the same
time: stacked at 375 px with this week first, side by side at 1280 px. Inside a
board the player's own row is marked four ways and none of them is colour. The
page has no filled button anywhere, because a leaderboard has no single primary
action — the one thing a player might do here, "Chơi ngay", lives inside an
empty state and is a secondary button.

---

## 3. The real page markup

The mockup sheet stacks several renders in one document, so its chrome is
demoted out of the landmark roles and its headings sit lower (`components.md`
§2.3 fixes that convention and `leaderboards.html` §9 restates it). **This is
the markup of the real page**, with the real landmarks and the real heading
levels, so M2 can lift it directly rather than un-demote the sheet.

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
          <li><a class="site-nav__link" href="/leaderboards"
                 aria-current="page">Bảng xếp hạng</a></li>
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
    <h1 class="lb-title">Bảng xếp hạng</h1>

    <!-- 1. WHICH GAME. Both controls are in the DOM at both widths; the band
            decides which one is displayed. See section 5.1. -->
    <section class="lb-region" aria-label="Chọn trò chơi">
      <form class="lb-scope__form" method="get" action="/leaderboards">
        <div class="field lb-scope__field">
          <label class="field__label" for="game">Trò chơi</label>
          <select id="game" name="game" aria-describedby="game-help">
            <option value="…">Tên trò chơi</option>
          </select>
          <p class="field__help" id="game-help">Chọn rồi bấm “Xem”.</p>
        </div>
        <button class="btn btn--secondary" type="submit">Xem</button>
      </form>
      <nav class="lb-scope__chips" aria-label="Chọn trò chơi">
        <ul class="segmented">
          <li><a class="segmented__link" href="/leaderboards/<game_id>"
                 aria-current="page">Tên trò chơi</a></li>
        </ul>
      </nav>
      <p class="lb-week-note">Tuần bắt đầu 00:00 thứ Hai, giờ UTC+7.</p>
    </section>

    <!-- 2. YOU. Above both boards. This is why the page has no sticky row. -->
    <section class="lb-region lb-you card" aria-labelledby="you">
      <h2 class="lb-you__title" id="you">Kết quả của bạn</h2>
      <dl class="lb-you__grid">
        <div class="well lb-you__cell">
          <dt class="lb-you__label">Hạng của bạn · tuần này</dt>
          <dd class="lb-you__value">7</dd>
          <dd class="lb-you__meta">8.760 điểm · trong 128 người chơi</dd>
        </div>
        <div class="well lb-you__cell">
          <dt class="lb-you__label">Kỷ lục cá nhân</dt>
          <dd class="lb-you__value">13.905</dd>
          <dd class="lb-you__meta">Tuần 41 · 06/10/2026</dd>
        </div>
      </dl>
    </section>

    <!-- 3. THE PERIOD SWITCHER. Real links to the two boards below, which are
            both on the page. No aria-current — see section 5.2. -->
    <nav class="lb-periods" aria-label="Chuyển tới bảng">
      <p class="lb-periods__label">Chuyển tới</p>
      <ul class="segmented">
        <li><a class="segmented__link" href="#tuan-nay">Tuần này</a></li>
        <li><a class="segmented__link" href="#tuan-truoc">Tuần trước</a></li>
      </ul>
    </nav>

    <!-- 4. THE TWO BOARDS. Stacked at 375, side by side from 768. -->
    <div class="lb-boards">
      <section class="lb-board" id="tuan-nay" aria-labelledby="h-tuan-nay">
        <h2 class="lb-board__title" id="h-tuan-nay">Tuần này</h2>
        <p class="lb-board__week"><span class="badge">Tuần 45 · từ 03/11</span></p>
        <table class="board">
          <caption>Hạng 1–10, tuần này</caption>
          <thead>
            <tr>
              <th class="board__rank" scope="col">Hạng</th>
              <th scope="col">Người chơi</th>
              <th class="board__score" scope="col">Điểm</th>
            </tr>
          </thead>
          <tbody>
            <tr><td class="board__rank">1</td>
                <td class="lb-name">Minh</td>
                <td class="board__score">12.480</td></tr>
            <!-- …and the player's own row, when they are on this page: -->
            <tr class="board__row--own">
              <td class="board__rank">7</td>
              <td class="lb-name">Hải<span class="board__you">· Bạn</span></td>
              <td class="board__score">8.760</td></tr>
          </tbody>
        </table>
        <p class="lb-board__more">
          <a class="btn btn--secondary" href="…">Xem 10 hạng tiếp theo</a>
        </p>
      </section>

      <section class="lb-board" id="tuan-truoc" aria-labelledby="h-tuan-truoc">
        <h2 class="lb-board__title" id="h-tuan-truoc">Tuần trước</h2>
        <p class="lb-board__week">
          <span class="badge">Tuần 44 · 27/10 – 02/11 · đã chốt</span></p>
        <table class="board">…</table>
        <p class="lb-board__more">…</p>
      </section>
    </div>
  </main>

  <footer class="site-footer">…</footer>       <!-- contentinfo -->
</body>
```

**Heading levels on the real page:** exactly one `<h1>` ("Bảng xếp hạng"), then
three `<h2>` — "Kết quả của bạn", "Tuần này", "Tuần trước". No level is skipped
and nothing below `h2` is needed. The game-scope region and the period switcher
are labelled regions without a visible heading, which is correct for a control
group: the label is the control's, not a section title.

**One DOM, two bands.** Both game-scope controls and the one ad slot are in the
markup at every width; the band changes only which of them is `display: none`.
Nothing is reordered, moved or re-parented by a media query, which is why no
`order` property appears anywhere in `leaderboards.css` and why reading order
and visual order agree at both widths.

---

## 4. The four views SOU-5 asks for

All four are on one page, at the same time. That is the wireframe's structure
and this page does not change it.

| View | Where it is | How it is expressed |
| --- | --- | --- |
| **This week** | region 4, first board | `<table class="board">`, ten ranks per page, the week in a `badge` |
| **Last week** | region 4, second board | the same table, marked **đã chốt** in its badge, so "final" is a word and not an inference |
| **The player's own rank** | region 2, **above both boards**, *and* marked inside a board when the player is on the page being viewed | the "Kết quả của bạn" card, plus `.board__row--own` |
| **Personal best** | region 2, beside the rank | the second `well` in the same card: the figure, and the week it was set |

**Why the player's own rank is above the boards and also inside them.** The
question "where am I" is answered without scrolling and **without a sticky
row** — this page has no sticky element. `ad-slots.md` §7 depends on that
staying true: the consent banner is the site's one sticky surface, and that is
precisely why no ad may be sticky. A pinned leaderboard row would be a second
one, so this page answers the same need by putting the answer where the reader
already is.

Inside the table the own row is marked **four ways, none of them colour**: a
heavier edge, a heavier weight, a `sunken` plate, and the word **"Bạn"** in the
row itself.

---

## 5. The two controls

### 5.1 Game scope — and the submit button this Task adds

`wireframes.md` §4.3 fixes two controls for one job: a `select` at 375 px and
**linked chips** at 1280 px, and it is explicit that they must be *real links to
each game's route, not JS-only tabs*. Both are in the DOM at both widths and
`display` decides which one is live, so `display: none` removes the other from
the accessibility tree and the tab order as well as from the screen. A
screen-reader user meets one control, never two.

The chips are `components.css`'s `.segmented`, with `aria-current="page"` on
the game being shown — a real route genuinely is current.

**The `select` is wrapped in a `<form method="get">` with a visible "Xem"
button, and that button is this Task's addition.** The wireframe draws the
`select` alone. A bare `select` cannot navigate without script, and the same
paragraph of `wireframes.md` forbids a JS-only control, so the sketch as drawn
has no no-JavaScript answer. A GET form with a submit button is the answer that
costs nothing: it is one extra 44 px target on the mobile band only, it needs no
script, and it leaves the route string to M2. It is raised in §11 rather than
slipped through.

### 5.2 The period switcher — and the one tension in this Task's brief

SOU-5 asks that switching between this week and last week **use the segmented
control from M1-04, and add no new component**. This page does exactly that, and
the control is `.segmented`, unchanged and unrestyled.

**It does not replace a board, because it may not.** `wireframes.md` §4.3 shows
both boards displayed at the same time and that structure is normative;
`components.md` §6.3 states the consequence in as many words:

> If a page Task wants the segmented control to *replace* one of those boards,
> that is a structural change and `wireframes.md` must be amended first.

`wireframes.md` is not in this Task's write set, so amending it is not this
Task's to do. The design therefore satisfies both documents: **both boards stay
on the page, and the segmented control is how a reader moves between them.**
The two options are real in-page links to the two board sections. At 375 px,
where the boards stack and the page is long, that is a genuinely useful jump;
at 1280 px it is a named landmark that reaches either board in one keystroke.

**It carries no `aria-current`, on purpose.** `components.md` §5.9 lists
`aria-current="page"` as one of the control's states, not as a required one.
On a page where both periods are present, neither is "the current period", and
drawing a selected plate would be the control asserting something false — and
making it true would mean restyling `.segmented__link` for a different
`aria-current` value, which this Task is not allowed to do. The same page uses
the selected state correctly on the *game* scope control, so a reviewer can see
both behaviours side by side in `leaderboards.html` §5.

**Keyboard: Tab moves between the options and arrow keys do not.** These are
links. Nothing carries `role="tab"`, and this page adds **no roving
`tabindex`** — that pattern belongs to `role="tablist"` and applying it to links
breaks the one thing a link is good at. `components.md` §5.9 is explicit, and
this page does not deviate.

**If the Board prefers the replacing design** — one board at a time, the
segmented control switching between two routes — that is a one-paragraph
amendment to `wireframes.md` §4.3 and a Task for whoever owns it, followed by a
small change here. §11.1 states the trade.

---

## 6. States, and where your rank lives

### 6.1 Which block each region uses

Normative, and taken unchanged from `components.md` §4.4. No region on this page
designs its own empty, loading or error block, and no fourth block is invented.

| Region | Empty | Loading | Error |
| --- | --- | --- | --- |
| **Each board** | "Chưa có ai ghi điểm" — "Bảng sẽ mở khi có điểm đầu tiên." **No action**: nobody having scored is not something the reader can fix. | **10 skeleton rows**, at the board's reserved height | "Không tải được bảng xếp hạng" — "Kiểm tra kết nối rồi thử lại." · *Thử lại* |
| **Kết quả của bạn** | "Bạn chưa có điểm nào" — "Chơi một ván để xuất hiện trên bảng." · *Chơi ngay* | 1 skeleton row | "Không tải được kết quả của bạn" — "Kiểm tra kết nối rồi thử lại." · *Thử lại* |

**The two boards have independent states.** This week failing while last week
loads is ordinary, and the layout does not move when it happens, because each
state block is exactly as tall as the board it stands in for.

**The ARIA roles**, from `components.md` §4.3 and carried in the mockup markup:

- loading → `aria-busy="true"` on the state block, and the announcing
  `role="status"` on a **separate, persistent region beside it** — never on the
  same element, per `components.md` §4.3 note 3, because the block is replaced
  wholesale when the data lands and so never clears its own busy flag. The
  "it is working" signal is the visually hidden text in that region, because
  the skeleton bars are `aria-hidden` and a bar announces nothing;
- a background load that failed → `role="status"`, still **polite**. The reader
  did not ask for this load, so interrupting them mid-sentence is wrong; the
  *Thử lại* button is reachable by Tab whenever they get to it.
- **Nothing on this page is `role="alert"`**, because nothing on this page is a
  response to an action the reader just took.

**Nothing animates.** No shimmer, no pulse. The bars are static, which makes
`prefers-reduced-motion` a non-issue here rather than a thing to remember.

### 6.2 The fourth state, and what "unranked" means

`wireframes.md` §4.3 asks for a fourth state on "your result": *not signed in /
no score yet*. `components.md` §4.4 answers that it is a fourth **fill** of the
empty pattern, not a fourth block. This page implements that answer, and states
plainly what the two cases are:

| Situation | What the page shows |
| --- | --- |
| **You have a score this week** | the normal display: rank, score, personal best. The page does **not** distinguish a signed-in player from an anonymous one here, because the board identifies a player only by display name and nothing on this page depends on the difference. |
| **You have never scored** (signed in or not) | the **empty** fill: "Bạn chưa có điểm nào" — "Chơi một ván để xuất hiện trên bảng." · *Chơi ngay*. There is no own row anywhere in either table. |
| **You have a score, but your rank is not on the page being viewed** | the "Kết quả của bạn" card shows your real rank (412, say) and your score. **The table does not invent a row for you.** Under the table a line says so: "Hạng của bạn là **412**, không nằm trong trang này. Kết quả của bạn ở ngay đầu trang." |

The third row is the long-list case SOU-5 asks about, and it is drawn in
`leaderboards.html` §4. **The rule it follows:** `.board__row--own` appears only
when the player is genuinely among the ranks being displayed. A row that claims
a position the player does not hold on this page would be worse than no row.

### 6.3 The two reserved heights

Both are set by the page on `--state-reserve`, which is the variable
`components.css` §16 invites a page to set. Neither overrides a library rule.

| Block | Reserve | Why |
| --- | --- | --- |
| `.lb-board__state` | a `calc()` over the type and spacing tokens for **ten rows**, plus the header row, the caption, and the eleven 1 px collapsed row rules | the formula `play.css` §4 uses for the three-row weekly board, with ten rows instead of three and with the hairlines counted. A literal height would drift from the type scale the moment the scale moved; leaving the eleven hairlines out would make the reserve 11 px short, which is a jump rather than a rounding error. It also carries the `--space-lg` margin `base.css` puts under every `<table>`, so the action below the board sits in the same place in all four displays. |
| `.lb-you__state` | a `calc()` for the **loaded** display: two `well`s and a gap below the switch, **one** `well` above it | at the switch the two wells go side by side, so the loaded block is half as tall. The reserve is set per band for that reason — one reserve cannot describe two layouts. |

**What the board's reserve buys, exactly:** loading → empty, loading → error and
loading → loaded all move nothing, in either board, at either width. Here the
guarantee is **exact**, because a ten-row table is taller than any of the three
state blocks' own content, so the reserve binds in all three and they are the
same box. The board is the region SOU-5's acceptance criterion is about.

**What the "your result" reserve buys, and what it does not.** `loading →
loaded` moves nothing, in either band — that is the path a player takes on
almost every visit, and it is the one the criterion names. **The empty and error
fills are taller than this small region**, and deliberately so: their pattern is
fixed by `components.md` §4.2 and every part of it is required — icon, title,
one sentence, an action, and for the error the word "Lỗi" as well. At 375 px
the empty block runs roughly 10 px past the loaded display and the error block
roughly 40 px; above the switch, where the loaded block is one `well` tall, the
gap is larger.

The three available answers were to clip the pattern, to drop a part of it, or
to let the box grow. **This page grows the box** — the same trade `play.css` §2
records for the game frame's error state, for the same reason. Growing is the
safe direction: the reserved box is never *shorter* than what it stands in for,
so nothing is ever hidden and nothing is pulled upward. §12.2 is the command
that measures all of it, and a reviewer should read the numbers rather than take
this paragraph's word for them.

**One more thing the reserve costs, on the board:** ten `.skeleton__row`s are
shorter than ten real table rows, so the loading block carries slack at its
foot. A reviewer will see empty space under the skeleton and should know it is
deliberate. Tightening it would mean restyling `.skeleton__row`, which belongs
to M1-04. §11.2 raises it there.

---

## 7. Keyboard, focus and reading order

DOM order = tab order = top-to-bottom visual order, at **both** widths. This
page has no `order` property, no positive `tabindex`, and no element that a
media query moves — so there is nowhere for the three orders to disagree. (The
play page has one deliberate exception for its side rail; this page has none,
because it has no rail.)

| # | Tab stop | 375 px | 1280 px |
| --- | --- | --- | --- |
| 1 | `skip-link` → `#main` | yes | yes |
| 2 | Wordmark | yes | yes |
| 3 | Nav disclosure button | yes | — (hidden) |
| 4 | Three navigation links | when open | yes |
| 5 | Theme toggle (**one** stop for the group; arrows move within it) | yes | yes |
| 6 | The ad slot | **never** — a slot is not focusable at any width | **never** |
| 7 | `select` "Trò chơi", then "Xem" | yes | — (hidden) |
| 8 | Game chips, one stop each | — (hidden) | yes |
| 9 | The action inside "Kết quả của bạn", when the state has one | yes | yes |
| 10 | "Tuần này", "Tuần trước" | yes | yes |
| 11 | This week's table — **no stop at all** | yes | yes |
| 12 | "Xem 10 hạng tiếp theo", this week | yes | yes |
| 13 | Last week's table, then its action | yes | yes |
| 14 | Four footer links | yes | yes |

**A table is not a tab stop.** Tab passes through it to the next control. A
screen-reader user navigates it with table commands, which is exactly why this
is a real `<table>` with a `<caption>` and `<th scope="col">` rather than a grid
of `div`s. **Rank, player and score are each identifiable**: the caption names
the board and the period, and each cell is associated with its column header.

**Two different keyboard contracts, both native.** The two segmented controls
are links, so Tab moves between them. The theme toggle is two real
`<input type="radio">`, so arrows move within it and Tab enters and leaves it.
Neither needs a line of script, and neither may be given a roving `tabindex`.

**Focus is always visible.** `base.css` draws the ring on `:focus-visible` with
a 2 px offset, so on a selected chip — a filled `primary` plate — the ring lands
on the surface behind rather than on the fill, where focus blue against primary
teal would measure 1.01:1. §8 gives the three measured ring rows.

**Targets.** Minimum 44 × 44 px at both widths with at least 8 px between
adjacent targets: `.btn`, `.segmented__link` and `select` all take
`--target-min` from the library, and the segmented control's own `gap` is
`--space-sm`.

---

## 8. Contrast

**This page introduces no new foreground/background pair.** Every pair it uses
was already measured — in `DESIGN.md` → "Measured contrast", or in
`components.md` §3.4 for the four `primary-hover` pairs. No ratio below is one
this Task had to produce, and every row carries its source. Figures are
`DESIGN.md`'s, which are the exact ratios **truncated** to two decimals, the
single convention SOU-18 fixed for this repository.

| Where it is used | Foreground | Background | Light | Dark | Needs | Source | |
| --- | --- | --- | --- | --- | --- | --- | --- |
| Board cells, state block body, the figures in a `well` | `text` | `bg` | 14.64 | 14.19 | 4.5 | DESIGN.md | PASS |
| "Kết quả của bạn" title, chip label, header text | `text` | `surface` | 15.58 | 12.66 | 4.5 | DESIGN.md | PASS |
| The player's own row | `text` | `sunken` | 13.24 | 15.26 | 4.5 | DESIGN.md | PASS |
| Column headers, the week note, `well` labels, state sentence, the "not on this page" line | `text-muted` | `bg` | 6.25 | 7.65 | 4.5 | DESIGN.md | PASS |
| Footer fine print, muted text on a card | `text-muted` | `surface` | 6.65 | 6.82 | 4.5 | DESIGN.md | PASS |
| Footer and in-text links | `primary` | `bg` | 5.69 | 8.66 | 4.5 | DESIGN.md | PASS |
| "Xem", "Thử lại", "Chơi ngay", "Xem 10 hạng tiếp theo" | `primary` | `surface` | 6.06 | 7.72 | 4.5 | DESIGN.md | PASS |
| The selected game chip | `on-primary` | `primary` | 6.11 | 8.15 | 4.5 | DESIGN.md | PASS |
| The selected game chip, hovered | `on-primary` | `primary-hover` | 8.57 | 9.65 | 4.5 | DESIGN.md | PASS |
| A hovered chip or secondary button | `primary-hover` | `surface` | 8.49 | 9.14 | 4.5 | components.md §3.4 | PASS |
| A hovered link on the page | `primary-hover` | `bg` | 7.98 | 10.25 | 4.5 | components.md §3.4 | PASS |
| The week badge | `secondary` | `surface` | 5.97 | 6.78 | 4.5 | DESIGN.md | PASS |
| Error icon and the word "Lỗi" | `error` | `bg` | 6.30 | 7.45 | 4.5 | DESIGN.md | PASS |
| Focus ring on a page control, in a state block, on a chip's offset | `focus` | `bg` | 5.65 | 8.90 | 3 | DESIGN.md | PASS |
| Focus ring on a card or on a `surface`-filled control | `focus` | `surface` | 6.01 | 7.94 | 3 | DESIGN.md | PASS |
| Focus ring over the player's own row | `focus` | `sunken` | 5.11 | 9.58 | 3 | DESIGN.md | PASS |
| State block edge, `select` edge | `border-strong` | `bg` | 3.48 | 3.57 | 3 | DESIGN.md | PASS |
| Chip edge, secondary button edge | `border-strong` | `surface` | 3.70 | 3.19 | 3 | DESIGN.md | PASS |
| The player's own row edge | `border-strong` | `sunken` | 3.14 | 3.84 | 3 | DESIGN.md | PASS |

### 8.1 The boards sit on the page background, and that is a contrast decision

A `.board` inside a `.card` would draw its row rule as `border` on `surface` —
a pair `DESIGN.md` has **never measured**, and the rule at the end of that
section is "do not use a colour whose ratio against its real background has not
been measured and written down here". On the page background the same rule is
`border` on `bg`, which `DESIGN.md` already records as decorative at 1.32 / 1.49.

So the two boards are not in cards. It looks like a styling choice and it is
not: it is what keeps every pair on this page inside the set the Board has
already accepted, without this Task inventing a colour or publishing a ratio it
had no tool to measure.

### 8.2 Two pairs below 3:1, on purpose

The table row rule and the skeleton bars are `border` on `bg` (1.32 light /
1.49 dark), and `surface` on `bg` is 1.06 / 1.12. Both are already recorded as
**decorative** by `DESIGN.md` and `components.md` §3.3: they carry no
information, the skeleton bars are `aria-hidden`, and the "it is loading" signal
is the live-region text beside them. WCAG 1.4.11 exempts pure decoration. **A
control boundary on this page is always `border-strong`, never `border`.**

### 8.3 Rank and score are never emphasised by colour

There is no gold/silver/bronze treatment on the top three, and no colour ramp
down the score column. The rank is already stated, in words, in the "Hạng"
column; a second encoding in colour would repeat it through the worst available
channel and would fail greyscale, which is the test `DESIGN.md` applies to every
status. The player's own row is the one row that *is* emphasised, and all four
of its signals survive greyscale.

One pair this page uses and cannot measure is the ad-slot label — it belongs to
`ads.css` and to M1-02, and no ratio is invented for it here (§11.4, re-raised
from `play.md` §9.5).

---

## 9. Minimal data — what this page shows, and what it refuses to

**This page shows exactly four things, and nothing else:**

| Field | Why it is here |
| --- | --- |
| **Display name** | a leaderboard that does not name anyone is not a leaderboard |
| **Rank** | the subject of the page |
| **Score** | what the rank is computed from, so the ranking is checkable by eye |
| **The period** | which week the board is for, printed in the badge and in the caption |

Two derived figures appear in the "Kết quả của bạn" card — the number of players
in the period ("trong 128 người chơi") and the week a personal best was set.
Neither is personal data about anyone else: the first is a count, the second is
the player's own.

**Deliberately absent, and not to be added by a later Task without
`requirements.md` §5.1 naming it first:**

- no email address, and no part of one;
- no location, country, city, region or flag;
- no device, browser, operating system or screen data;
- no avatar, photo or image of any kind;
- no timestamp more precise than the week, and none at all per row;
- no identifier of any kind beyond the display name — no user id, no account
  id, no anonymous id, no session token, visible or in an attribute;
- no "players near you", "friends", or any grouping that would need data this
  page does not have.

**And no analytics, trackers or third-party requests.** There is no script, no
embedded frame, no font file, no pixel and no remote reference anywhere in
`leaderboards.html` or `leaderboards.css`. §12.1 gives the command that proves
it.

---

## 10. Ad slots

This page carries **exactly one** slot, and it is the site-wide header banner.

| Band | Slot | Format | Position |
| --- | --- | --- | --- |
| mobile · < 768 px | **none** | — | `ad-slots.md` §3: outside the play page the mobile band has no ad position at all, and that document will not add one until `requirements.md` §5.1 names it |
| compact + wide · ≥ 768 px | `ad-header-leaderboard` | 728 × 90 | in the site header, **after** the skip link |

**The 24 px clearance rule has no subject on this page.** `ad-slots.md` §5
measures clearance from the **game frame**, and this page has no game frame. That
is not a relaxation — it means the rule cannot be broken here. Everything else
in §9.4 of that document applies unchanged, and the markup honours all of it:

- the slot is `position: static`, in normal flow, with no `float`, no `z-index`
  and no `transform` anywhere on the page (§12.1, check 5);
- it is never focusable — no `tabindex`, no interactive element inside;
- it sits **after** the skip link, so one keystroke passes the whole banner;
- it is a named landmark (`aria-label="Quảng cáo"`) with a visible label, so it
  is identified by its label and not by its fill, and it is skippable;
- nothing in it moves, so the pause/stop requirement never applies.

**`leaderboards.css` contains no `.ad-slot` rule** other than the two
`display` lines in the sheet-only band mirror. `ads.css` is the sole owner
(`ad-slots.md` §11), it sets the slot's box by an explicit height, and nothing on
this page can resize it.

---

## 11. Open items, and the things that want a decision

### 11.1 The period switcher does not replace a board — for the Board

**This is the one place where SOU-5's wording and the upstream documents pull in
different directions**, so it is reported rather than resolved quietly. SOU-5
asks that the segmented control be how a player switches period;
`wireframes.md` §4.3 and `components.md` §6.3 require that both boards stay on
the page unless `wireframes.md` is amended first. §5.2 is the design that
satisfies both: the control is `.segmented`, it switches between the two
boards, and neither board is removed.

**If the Board wants one board at a time**, the change is small but it is not
this Task's to make: `wireframes.md` §4.3 has to be amended (it is not in this
Task's write set), and then this page's period switcher becomes two real routes
with `aria-current="page"` on the one being shown — which the library already
styles, with no new CSS. The cost of that version is that "last week" stops
being visible for comparison without a second navigation.

### 11.2 The skeleton row is shorter than a real board row — for M1-04's owner

`.skeleton__row` is roughly 37 px where a real `.board` row is roughly 50 px, so
a ten-row loading block carries visible slack at its foot (§6.3). This page
takes the slack rather than restyle a library component. If M1-04's owner would
rather the two matched, it is a change to `.skeleton__row`'s padding in
`components.css` and it affects every page that loads a board.

### 11.3 The mobile game selector has a submit button — for the reviewer

§5.1. The wireframe draws a bare `select`; a bare `select` cannot navigate
without script and the same paragraph forbids a JS-only control. The button is
the smallest honest fix. If the reviewer prefers the mobile band to carry the
chips too, the chips already exist and the form can simply be dropped — but a
horizontal chip row at 343 px is what the wireframe deliberately rejected.

### 11.4 The ad-slot label is still an unmeasured pair — for the Board

Re-raised from `play.md` §9.5 and `home.md` §9.3, unchanged. The pair belongs to
`ads.css` and to M1-02, and this Task invents no ratio for it.

### 11.5 Design tooling was not available

`google-design-md` and the taste skill may be used only after GAM-19 passes and
the Board installs them (security-architecture §11). **Neither was installed in
this environment, so neither was used**, and no uninstalled tool was substituted
for either.

---

## 12. How to verify

No build, no server, no dependency: open
[`../prototypes/leaderboards.html`](../prototypes/leaderboards.html).

### 12.1 Five checks a reviewer can run

From the repository root. All five must return **nothing**.

```sh
# 1. No script, no embedded frame, no font file, no network reference of any
#    kind. (<link rel="stylesheet" href="..."> is relative and is deliberately
#    not matched — the pattern catches a remote reference, not a local one.)
grep -nEi '<script|<iframe|@font-face|src=|https?:' \
  docs/design/prototypes/leaderboards.html

# 2. No colour literal anywhere in the stylesheet.
grep -nE '#[0-9a-fA-F]{3,8}|rgba?\(|hsla?\(' \
  docs/design/prototypes/assets/leaderboards.css

# 3. No inline style attribute.
grep -n 'style="' docs/design/prototypes/leaderboards.html

# 4. No breakpoint other than M1-01's switch. This page has no side rail, so
#    1024px must not appear either.
grep -nE '@media' docs/design/prototypes/assets/leaderboards.css \
  | grep -v '768px'

# 5. Nothing on this page is lifted out of normal flow — which is how the
#    no-sticky rule is checked, not only for the ad slot but for the
#    leaderboard row this page deliberately does not pin.
grep -nE '^[[:space:]]*(position:[[:space:]]*(sticky|fixed)|float:[[:space:]]*(left|right)|z-index:[[:space:]]*[0-9]|transform:[[:space:]]*(translate|scale|rotate|matrix|skew|perspective))' \
  docs/design/prototypes/assets/leaderboards.css
```

**Why these commands are here and not in `leaderboards.html`.** Check 1's
pattern searches for a source attribute and a protocol prefix *in that file*.
Printed inside it, the command would match itself and the check would fail on
its own documentation. A markdown file is outside the scanned set.

### 12.2 Measure the two zero-shift claims

The sheet holds several renders, so scope each snippet to one view. Every number
printed by each line must be the same.

```js
// The board: the ten-row table and all three state blocks, one height.
const v = document.querySelector('.mk-view--desktop');   // then --mobile
[...v.querySelectorAll('.lb-board .board, .lb-board__state')]
  .map(e => Math.round(e.getBoundingClientRect().height));

// "Kết quả của bạn": the loaded card body and all three state blocks.
[...document.querySelectorAll('.lb-you__grid, .lb-you__state')]
  .map(e => Math.round(e.getBoundingClientRect().height));
```

**On a board, all four numbers must be identical** — the table and the three
state blocks. **On "Kết quả của bạn", the loading block and the loaded grid must
be identical**, and the empty and error blocks may be taller, by roughly 10 px
and 40 px at 375 px and by more above the switch. No block may ever be
**shorter** than the display it stands in for. §6.3 is the argument for both
rules, and §11.2 for the slack inside the board's loading block.

### 12.3 Confirm the ad inventory, per band

```js
for (const v of document.querySelectorAll('.mk-view')) {
  const on = [...v.querySelectorAll('.ad-slot')]
    .filter(e => getComputedStyle(e).display !== 'none');
  console.log(v.className, on.length,
              on.map(e => e.getBoundingClientRect())
                .map(r => Math.round(r.width) + 'x' + Math.round(r.height)),
              on.map(e => getComputedStyle(e).position));
}
```

Expected: **0** slots in every `--mobile` view, **1** slot of `728x90` at
`static` in every `--desktop` view. Any other count, or any `position` other
than `static`, fails the review.

### 12.4 Confirm no shift at all, on the real widths

Reload with this running, at 375 px and 1280 px:

```js
let cls = 0;
new PerformanceObserver(l => {
  for (const e of l.getEntries()) if (!e.hadRecentInput) cls += e.value;
}).observe({ type: 'layout-shift', buffered: true });
setTimeout(() => console.log('CLS', cls), 3000);
```

Expected **`CLS 0`**.

### 12.5 By eye

1. **Four renders of the full page.** Light/dark × 375/1280, and four more for
   each group of state blocks.
2. **Count the ad slots:** none at 375, exactly one at 1280.
3. **No horizontal scrollbar at 375 px**, and no cell that overflows: give a
   player a 30-character display name and confirm the name wraps rather than
   pushing the score column off the screen.
4. **Tab through the sheet.** The ring is always visible and never hidden; no ad
   slot takes focus; neither table takes focus; the two chips of the period
   switcher are two ordinary stops and arrow keys do nothing in them.
5. **Nothing animates.** Set `prefers-reduced-motion: reduce` and confirm
   nothing changes, because nothing moved.
6. **Greyscale it.** The player's own row, the error block and the selected game
   chip are all still readable, because none of them is colour alone.
7. **Read the page with a screen reader's table commands.** The caption names
   the board and the period; each cell announces with its column header; the
   word "Bạn" is inside the own row and not only in its styling.

---

## 13. What this Task did not verify

Recorded plainly, because an unverified claim presented as verified is worse
than a gap.

- **No rendered visual check. No browser was available in this environment**, so
  **the sheet has not been opened and looked at.** The markup and stylesheet are
  checked statically — the five commands in §12.1 all return nothing, tag
  balance and unique `id`s were verified mechanically, every `<use>` resolves to
  a symbol defined in the same document, and every class used in the sheet
  resolves to a rule in one of the four stylesheets apart from two deliberate
  naming hooks that carry no declarations (`.lb-you` and `.lb-board`, which
  exist so the markup and a later test can name those regions) — but *how it
  looks* is for the `qa-engineer` review, not a claim this Task is making.
- **The reserved heights in §6.3 are derived, not browser-measured.** Every term
  is a token applied by a stylesheet, so the arithmetic is deterministic and a
  reviewer can add it up — but it is arithmetic over the CSS, not a
  `getBoundingClientRect()` reading. §12.2 is the command that turns it into a
  measurement, and it is a review step.
- **No contrast was re-measured.** Every ratio in §8 is quoted with its source.
  This Task computed none of them and had no tool to.
- **The keyboard order is designed, not exercised.** §7's table is what the DOM
  says; that a real browser agrees is a review step, and so is the claim that
  the live regions actually announce in a real screen reader.
- **The Vietnamese font check remains open**, as `components.md` §8.3 left it.
  No platform was tested here.
- **The long-display-name case in §12.5 item 3 was reasoned about, not
  observed.** `overflow-wrap: anywhere` on the name cell is the mechanism; that
  it is enough at 343 px is a review step.

---

## 14. Handoff

**To `qa-engineer` (the named reviewer).** SOU-5 asks you for an accessibility
review of the table semantics and the period switcher. The four things that most
want your judgement:

1. **§7 and §12.5 item 7** — are the table semantics genuinely sufficient?
   `<caption>` plus `<th scope="col">`, with the rank in a `<td>` rather than a
   `<th scope="row">`. The second form would make rank the row header, which
   reads better in some screen readers, but `base.css` styles every `<th>` as
   muted label text, so it would also make the rank column visually recede.
   `components.md` §5.8 specifies the `<td>` form; if you prefer the other, it
   needs a change in M1-04, not a local override here.
2. **§5.2** — the period switcher carries no `aria-current`. Is "no option is
   current, because both boards are present" the right call, or does an
   unselected segmented control read as broken?
3. **§6.2** — the three unranked cases, and especially the "your rank is not on
   this page" line. Is it findable enough without a pinned row?
4. **§5.1** — the mobile `select` plus "Xem" submit button.

**To the Board.** §11.1 (the period switcher versus `wireframes.md` §4.3 — the
one decision this Task could not take for itself), §11.4 (the ad-slot label's
unmeasured pair) and §11.5 (the design tooling was not installed, so it was not
used).

**To M1-04's owner.** §11.2 — `.skeleton__row` is shorter than a real board row.

**To M1-08 (account) and M1-09 (consent).** This page adds no shared component
and restyles none, so nothing here changes what you inherit. The "Kết quả của
bạn" card and the board are compositions of `.card`, `.well` and `.board`, and
they are documented here rather than promoted into the library.

**To M2 (the stack).** §3 is the markup to lift. Four properties are contracts,
not styling: **both boards on the page**, **"your result" above them**, **no
sticky element anywhere**, and **the table stays a real `<table>`**. The route
strings are yours; nothing here depends on them.

**To M4 (the backend) and the scoring contract.** This page is designed against
placeholder data and assumes only that the server can answer four questions: the
top N of a period, the viewer's own rank and score in that period, the viewer's
personal best and when it was set, and the number of players in the period. It
assumes nothing about how a score is submitted, validated or ranked, and
`AGENTS.md` §5.2 is unaffected: the client asks, the server decides.

**To M4 (tests).** Every claim in §7, §10 and §12 is a Playwright assertion: the
tab order, the visible ring, the live-region roles, the per-band ad inventory
(0 at 375 px, 1 at 1280 px), `position: static` on the slot, the equal heights
of the three state blocks, the absence of any sticky or fixed element on the
page, and CLS = 0 at both widths.

**To M6 (ads).** This page carries one slot above 768 px and none below it.
Nothing here requests a creative.

# About — `/about` · Terms — `/terms`

**Status:** Normative for both pages' layout and states. Milestone M1 (Design),
Task SOU-3 (M1-05). Owner: `ui-designer`, through `project-manager`.
Last updated: 2026-11-05.

The mockups are [`../prototypes/about.html`](../prototypes/about.html) and
[`../prototypes/terms.html`](../prototypes/terms.html), and are static HTML:
open one in a browser and it works. No server, no build step, no dependency, no
network request.

**One document for two pages, because they are one template.**
`wireframes.md` §4.8 is explicit: Terms is "the same document template as
Privacy. Different sections, identical structure — so M1-04 designs **one**
long-form template, not three." `components.md` §5.16 delivered that template.
This Task fills it in twice and would have been wrong to produce two designs.

It is downstream of five documents and contradicts none of them, with **one
exception that is raised rather than resolved** — see §5.

| Source | What it fixes | What this document does |
| --- | --- | --- |
| [`../../requirements.md`](../../requirements.md) §2 | that both pages exist and what they hold | builds them |
| [`../DESIGN.md`](../DESIGN.md) (M1-01) | colour, type, spacing, the reading measure, breakpoints, measured contrast | designs inside the measured pairs, inventing no value |
| [`../ad-slots.md`](../ad-slots.md) (M1-02) §3 | one header slot above 768 px, none below | places exactly that |
| [`../wireframes.md`](../wireframes.md) (M1-03) §4.7, §4.8 | the sections, the reading order, which page gets a contents list | makes those regions high-fidelity; moves none, drops none, reorders none |
| [`../components.md`](../components.md) (M1-04) §5.16 | the long-form document template and the three state patterns | reuses them; **adds no shared component** |

---

## 1. Scope — and what is deliberately absent

In scope: the template's composition at both design widths, in both themes, with
every state; the reading measure; the heading hierarchy; the in-page contents
list; the one ad slot; the keyboard order; and the contrast of every pair used.

**Deliberately absent, and a defect if a later Task reads one into these pages:**

- **The legal text itself.** It comes from **OQ-039** and must cover Decree
  13/2023, and GDPR if EU players are expected (`wireframes.md` §4.5, §4.8). The
  prose in both prototypes is **placeholder written to exercise the layout**, and
  `terms.html` carries a `notice--warning` saying so in every render so nobody
  can mistake it for a published term. **Nothing in this Task's files is legal
  advice or a commitment by the studio.**
- **The game list.** About links back to `/` and does **not** duplicate the grid
  (`wireframes.md` §4.7). A list that exists in two places is a list that will
  disagree with itself.
- **The Privacy page.** It uses this same template but belongs to M1-07. Nothing
  here is changed for it, and nothing here blocks it.
- **A new shared component**, colour, type step, spacing step, radius or
  breakpoint.
- **Any framework, build step, runtime dependency, package manifest, analytics,
  tracker, font file, ad tag or third-party request.**

---

## 2. The template, and the one thing that differs

Both pages are: `h1` → last-updated line → *(contents list, Terms only)* →
numbered `h2` sections. The column is `.shell--text`, capped at the reading
measure. There is no hero, no card grid and no imagery — these are documents,
and a document that decorates itself is a document that is harder to read.

**The one difference: About has no contents list, Terms does.**
`wireframes.md` §4.7 calls About "the document template without the contents
list — three sections do not need one", and §4.8 gives Terms seven sections and
a contents list. Three headings are their own index; seven are not. This is
M1-03's decision and this Task draws it rather than re-deciding it.

| | About | Terms |
| --- | --- | --- |
| Sections | 3 | 7 |
| In-page contents | no | yes |
| Last-updated line | yes | yes |
| Placeholder-text warning notice | no — the prose is ordinary copy, flagged on the sheet | **yes, in every render** — the real text is legal and comes from OQ-039 |

### Sections

**About** (`wireframes.md` §4.7): 1 · Về studio · 2 · Trò chơi của chúng tôi
(a paragraph and a link back to `/`) · 3 · Liên hệ (the same contact address
Privacy §7 gives — one source, two places that point at it).

**Terms** (`wireframes.md` §4.8): 1 · Chấp nhận điều khoản · 2 · Tài khoản và
tên hiển thị (links to `/account`; display names are moderated) · 3 · Chơi công
bằng và điểm số (the player-facing form of `requirements.md` §4.2: scores are
granted server-side, and a client-submitted score is a request, not a fact) ·
4 · Quảng cáo (links to the cookie settings) · 5 · Nội dung và quyền sở hữu ·
6 · Trách nhiệm · 7 · Thay đổi và liên hệ.

---

## 3. The real page markup

The mockup sheets stack four renders in one document, so their chrome is demoted
out of the landmark roles and their headings sit one level deeper
(`components.md` §2.3 fixes that convention). **This is the markup of the real
page**, with the real landmarks and the real heading levels, so M2 can lift it
directly. The chrome — skip link, header, nav, theme toggle, ad slot, footer —
is identical to [`home.md`](home.md) §3 and is elided here.

```html
<main id="main" class="shell shell--text main">
  <h1>Điều khoản sử dụng</h1>
  <p class="doc__updated">Cập nhật lần cuối: 5 tháng 11, 2026</p>

  <!-- Terms only. About has three sections and needs no contents list. -->
  <nav class="card" aria-label="Mục lục điều khoản">
    <ol class="doc__toc-list">
      <li><a class="link" href="#dieu-khoan-1">Chấp nhận điều khoản</a></li>
      …seven entries…
    </ol>
  </nav>

  <section class="doc-section" id="dieu-khoan-1">
    <h2 class="doc-section__title">1. Chấp nhận điều khoản</h2>
    <p>…</p>
  </section>
  …
</main>
```

**Heading levels on the real page:** exactly one `<h1>`, then one `<h2>` per
numbered section. There is no `<h3>`, because no section has sub-sections. No
level is skipped.

**The section number lives in the heading's text**, not in a CSS counter, so a
screen reader reads it and the contents list can match it. On the sheets the
`id`s are prefixed per render (`t-a-1`, `t-b-1`, …) because four renders share
one document and `id`s must be unique; the real page has one set, and the exact
slugs are M2's to choose.

---

## 4. The reading measure, and the two mechanisms that hold it

`DESIGN.md` asks for 35–60 characters per line on mobile and 60–75 on desktop.
Two different mechanisms hold that, and the difference matters:

- **`base.css` caps each `<p>` at `70ch`.** This keeps *paragraphs* in measure.
- **`.shell--text` caps the whole column at `70ch`.** This additionally keeps
  headings, lists, tables and the last-updated line in measure — none of which
  `base.css` touches. Without it an `<h2>` would run 1200 px wide above a 70ch
  paragraph, which looks broken and reads worse.

`components.md` §5.16 chose the column cap for exactly that reason, and this
Task uses it rather than re-capping anything itself.

**At 375 px the cap does nothing**: the content column is 343 px, narrower than
`70ch`, so the measure comes from the device. It binds only on desktop. That is
why the template needs no media query of its own to serve both bands.

### The contents list

- It is a labelled `<nav>` around an `<ol>` of **ordinary links** — exactly what
  `components.md` §5.16 specifies. The shell is `.card`, which already exists.
  **No new component.**
- **Target size.** A contents entry is a navigation target, not a link inside a
  sentence, so each is padded to the 44 px minimum `DESIGN.md` sets, with 8 px
  between adjacent entries. The visible text does not grow; the hit area does.
- **Two columns from 768 px.** Seven entries at 44 px is roughly 360 px — enough
  to push section 1 below the first screen on a laptop. `columns: 2` reads the
  same list in half the height and **does not change DOM order**, so the tab
  order stays 1→7.
- **Jumping works.** `base.css` already sets
  `:target { scroll-margin-block-start: var(--space-4xl) }`, so a section
  heading does not land flush against the viewport edge. Nothing on these pages
  is sticky, so nothing covers the destination.

---

## 5. States — and a conflict raised, not resolved

### 5.1 The conflict

`wireframes.md` §4.7 and §4.8, and `components.md` §4.4, all say the same thing:
About and Terms are **static** — no loading state, no empty state, **page-level
404/500 only**. **SOU-3's acceptance criteria require empty, loading and error
states for all three pages.**

This Task drew all three, because SOU-3's instruction is the later and explicit
one, and states plainly which are reachable today and which are not. Drawing a
state and claiming it is live are different things, and only the first is being
done here.

| State | Reachable today? | When it becomes reachable |
| --- | --- | --- |
| **Error** | **Yes.** 404 and 500 at page level — the failure the upstream docs already name. | Now. A wrong URL is enough. |
| **Empty** | **Yes, and currently true.** The legal text comes from OQ-039 and does not exist yet. | Whenever the document exists but is unpublished. |
| **Loading** | **No.** The pages are static, built at build time. | From M4, *if* the documents are served from the backend rather than inlined. |

**The empty state is not hypothetical for Terms.** If the page ships before
OQ-039 lands, this is exactly what a reader meets, and it must say so rather
than render a blank page or seven empty headings.

**For:** the reviewer (`web-engineer`) and the Board. If the Board prefers the
upstream reading, the loading state is the one to delete — it is the only one of
the three with no path to being reached — and that is a deletion of markup from
two files, with nothing else affected.

### 5.2 The three blocks

All three are the canonical M1-04 blocks with `.state--reserve-md`, not blocks
these pages drew. Because all three share one box, switching between any two
moves nothing.

| State | Copy (Terms; About is the same shape) |
| --- | --- |
| Empty | "Điều khoản chưa được công bố" · "Chúng tôi đang hoàn thiện văn bản. Mời bạn quay lại sau." · *Về trang chủ* |
| Loading | visually hidden "Đang tải điều khoản…", then skeleton text lines |
| Error | "Lỗi" · "Không tải được điều khoản" · "Kiểm tra kết nối rồi thử lại." · *Thử lại* |

The loading bars use `.sk-reserve--body`, which reserves one line box of
`--text-body-md × --leading-body` — the same token-derived approach
[`home.md`](home.md) §6.2 describes and §9.2 reports.

### 5.3 The 404, and the one state block without *Thử lại*

The page-level error is drawn **in the context of a full page** in
`about.html` §3.1 rather than as a loose block, because that is how a reader
meets it. The chrome stays intact — header, nav, footer — since someone who is
lost still needs a way onward, and a bare 404 does not give them one.

Its action is **"Về trang chủ", not "Thử lại"**. `components.md` §4.2 requires
that every error state carries a way forward, and retry is the usual one; but
reloading a URL that does not exist produces the same 404, so retry here would
be a button that is guaranteed to fail. The way forward is the home page. **This
is the only error state in this Task's files without *Thử lại*, and this is the
reason.** The word "Lỗi 404" is text, never colour alone.

---

## 6. Contrast

**Neither page introduces a new foreground/background pair.** Every pair was
already measured — in `DESIGN.md` → "Measured contrast", or `components.md` §3.4
for `primary-hover`. Every row carries its source.

| Where it is used | Foreground | Background | Light | Dark | Needs | Source | |
| --- | --- | --- | --- | --- | --- | --- | --- |
| Body text, every section heading, the `h1` | `text` | `bg` | 14.65 | 14.19 | 4.5 | DESIGN.md | PASS |
| Last-updated line | `text-muted` | `bg` | 6.25 | 7.66 | 4.5 | DESIGN.md | PASS |
| Link in running text, footer link, state action | `primary` | `bg` | 5.69 | 8.65 | 4.5 | DESIGN.md | PASS |
| Hovered link | `primary-hover` | `bg` | 7.98 | 10.25 | 4.5 | components.md §3.4 | PASS |
| Text in the header, footer and the contents card | `text` | `surface` | 15.58 | 12.66 | 4.5 | DESIGN.md | PASS |
| Contents link, nav link | `primary` | `surface` | 6.05 | 7.72 | 4.5 | DESIGN.md | PASS |
| Hovered contents link | `primary-hover` | `surface` | 8.49 | 9.14 | 4.5 | components.md §3.4 | PASS |
| Error icon and the word "Lỗi" | `error` | `bg` | 6.31 | 7.46 | 4.5 | DESIGN.md | PASS |
| Warning icon and the word "Cảnh báo" (Terms) | `warning` | `bg` | 6.09 | 8.09 | 4.5 | DESIGN.md | PASS |
| Focus ring on the page | `focus` | `bg` | 5.65 | 8.92 | 3 | DESIGN.md | PASS |
| Focus ring on the contents card, in the header | `focus` | `surface` | 6.01 | 7.96 | 3 | DESIGN.md | PASS |
| State block edge, secondary button edge | `border-strong` | `bg` | 3.48 | 3.58 | 3 | DESIGN.md | PASS |
| Text on the ad slot's fill | `text` | `sunken` | 13.24 | 15.27 | 4.5 | DESIGN.md | PASS |

**Why the warning notice supplies its own `bg` fill.** `DESIGN.md` measures
`warning` against `bg` **only**, and `components.md` §2.1 states the rule:
`surface` does not carry `success` or `warning`, because those pairs were never
measured. So the notice shell carries a `bg` fill in every context, including
inside a `surface` card. That is the surface rule, not a styling preference —
it is what makes the 6.09 / 8.09 above the real ratio rather than an
approximation of one.

**One pair below 3:1, on purpose.** The skeleton bar is `border` on `bg`
(1.33 light / 1.50 dark). It carries no information, it is `aria-hidden`, and
the "it is loading" signal is the live-region text beside it. WCAG 1.4.11
exempts pure decoration; `DESIGN.md` and `components.md` §3.3 both already
record it as decorative.

**Unresolved, and not this Task's to fix.** `ads.css` sets `.ad-slot__label` to
`text-muted` on `sunken` — a pair `DESIGN.md` does not publish, against a rule
(`components.md` §2.1) that says `sunken` carries only `text`. These pages
cannot restyle `.ad-slot` (`ad-slots.md` §11) and had no measuring tool here, so
no ratio is claimed. Raised as an M1-02 item for the Board; the same item is
recorded in [`home.md`](home.md) §9.3, since all three pages carry that slot.

---

## 7. Ad slots, and keyboard

### Ad slots

Each page carries **exactly one** slot, and only from 768 px up:
`ad-header-leaderboard`, **728 × 90**, in the site header. In the mobile band,
**none** — `ad-slots.md` §3 verbatim. This Task adds no slot, removes none,
moves none and restyles none; `ads.css` is the sole owner of `.ad-slot`.

The 24 px clearance rule does not apply here: it measures distance to the *game
frame*, and neither page has one. The slot sits after the skip link, is never
focusable, never traps focus, is never sticky and contains nothing interactive.
No ad network, tag, script, `iframe`, pixel or third-party request appears
anywhere in this Task's files.

### Keyboard

DOM order = tab order = reading order, at both widths. No `order` property and
no positive `tabindex` anywhere.

1. `skip-link` → `#main`, first in the document and **before** the header ad.
2. Wordmark; nav disclosure (below 768 px only); three nav links; two theme
   radios.
3. **Terms only:** the seven contents entries, in order 1→7 — `columns: 2`
   changes the visual arrangement, never the DOM.
4. Links in the body, in the order they appear in the sentence.
5. Four footer links, each a 44 px target.

Every link is underlined by default, so colour is never the only signal. The
current page in the footer carries `aria-current="page"`, so the visible mark
and the announced state cannot disagree. Neither page has a dialog, a sticky
element or a focus trap, so no focused control is ever obscured (WCAG 2.2).

---

## 8. How to verify

No build, no server, no dependency: open
[`../prototypes/about.html`](../prototypes/about.html) and
[`../prototypes/terms.html`](../prototypes/terms.html).

### 8.1 Four checks a reviewer can run

From the repository root. All four must return **nothing**.

```sh
# 1. No script, no iframe, no font file, no network reference of any kind.
#    (<link rel="stylesheet" href="..."> is relative and deliberately not
#    matched — the pattern catches a remote reference, not a local one.)
grep -nEi '<script|<iframe|@font-face|src=|https?:' \
  docs/design/prototypes/about.html docs/design/prototypes/terms.html

# 2. No colour literal anywhere in the stylesheet.
grep -nE '#[0-9a-fA-F]{3,8}|rgba?\(|hsla?\(' \
  docs/design/prototypes/assets/home.css

# 3. No inline style attribute.
grep -n 'style="' docs/design/prototypes/about.html \
  docs/design/prototypes/terms.html

# 4. No breakpoint other than M1-01's switch.
grep -nE '@media' docs/design/prototypes/assets/home.css | grep -v '768px'
```

**Why these commands live here and not in the sheets.** Check 1's pattern
searches for an `src` attribute and an `http` prefix *in those files*. Printed
inside one, the command would match itself and the check would fail on its own
documentation. A markdown file is outside the scanned set, which is the only
place the command stays true.

### 8.2 By eye

1. **Four renders per page.** Light/dark × 375/1280.
2. **The measure.** At 1280 px the text column stops at `70ch` and is *not*
   1200 px wide — and the headings stop there too, not just the paragraphs. The
   header and footer still run edge to edge: only the text is capped, not the
   page.
3. **Tab through a sheet.** The focus ring is always visible and the order
   matches the visual order. On Terms, the seven contents entries come in order
   1→7 even in two columns.
4. **Click a contents entry.** The section heading lands clear of the top edge,
   not flush against it.
5. **Nothing animates.** Set `prefers-reduced-motion: reduce` and nothing
   changes, because nothing moved.
6. **Greyscale it.** The error block and the warning notice are both still
   readable, because each carries an icon and a word as well as a colour.
7. **Measure the zero-shift claim.** In the Console on either sheet:
   `[...document.querySelectorAll('.state')].map(e => e.getBoundingClientRect().height)`
   — within one view, every number must be the same.
8. **On Terms, confirm the placeholder warning is in every render.** It is the
   one thing that must not be lost if the sheet is edited.

---

## 9. What this Task did not verify

- **No rendered visual check.** No browser was available in this environment, so
  **the sheets have not been opened and looked at.** Markup and stylesheet are
  checked statically (§8.1) and structurally — tag balance, unique `id`s and
  every `<use>` resolving to a symbol defined in the same document were verified
  mechanically — but *how they look*, and whether `70ch` reads well in
  Vietnamese at 1280 px, is for the `web-engineer` review and the `qa` pass.
- **No contrast was re-measured.** Every ratio in §6 is quoted with its source;
  this Task computed none and had no tool to.
- **The legal text is not reviewed, because it is not written.** OQ-039 owns it.
  Nothing in these files should be read as a term, a policy or a commitment.
- **The Vietnamese font check remains open**, as `components.md` §8.3 left it.
  These pages are the longest running Vietnamese text in M1 and are good
  evidence for it, but no platform was tested here.
- **`google-design-md` and the taste skill were not used.** Neither is enabled
  for this agent, and no uninstalled tool was substituted for either. This
  matches `components.md` §8.4.

---

## 10. Handoff

**To `web-engineer` (reviewer).** The judgement calls are §5.1 (do these static
pages get loading and empty states at all?) and §4 (is the column cap plus the
44 px contents entry the right template, or is the contents list too tall?).

**To M1-07 (Privacy).** This is the same template, and Privacy is its third use.
Take §3's markup and §4's contents-list rules as they are; §5's state table
applies to Privacy unchanged, with one addition Privacy owns — the "Mở cài đặt
cookie" control has its own saving and error states (`wireframes.md` §4.5).

**To M2 (the stack).** §3 is the markup to lift. The `id` slugs are yours to
choose; the only requirement is that the contents list and the section headings
agree.

**To M4 (tests).** §7's tab order, §4's measure cap, §5's state roles and §8.2's
zero-shift check are all Playwright assertions.

**To whoever writes OQ-039.** The template holds seven numbered sections on
Terms and three on About. Longer sections are fine; **a new top-level section on
Terms means the contents list grows**, which is the one thing in this design
that is sized to the current count.

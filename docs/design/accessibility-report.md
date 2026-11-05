# Accessibility audit — M1 exit criterion

**Task:** SOU-8 (M1-10) · **Milestone:** M1 (Design) · **Auditor:** `qa-engineer`
**Date:** 2026-11-05 · **Standard:** WCAG 2.1 level AA
**Scope:** every page delivered by M1-05 … M1-09, in every state, at both design
widths, in both themes.

This Task **is** the M1 exit criterion *"contrast and keyboard checks pass"*.
The verdict is in §9. It is a **FAIL**, on four Major findings, none of which
requires a palette change, a component redesign or a new breakpoint. §9 states
exactly what turns it into a pass.

---

## 1. Verdict in one paragraph

**Contrast passes.** Every foreground/background pair the designs actually use
meets its AA threshold, with margin; the tightest pair measures 3.15:1 against a
3:1 requirement. No colour has to change. **Keyboard traversal passes with one
exception** — reachability, tab order, no trap and a working skip link hold on
all eight pages, and the focus indicator is visible everywhere; but in one state
it describes the wrong control (A-13, the Home top-3 error card). **Focus
management and live regions do not pass.** The documented focus-return target
(`#main`) cannot receive focus as specified (A-02), and the loading live region
is marked in a way that suppresses the announcement it exists to make (A-03).
Both are named explicitly in this Task's acceptance criteria, and both are
small, local fixes. Two further Major findings (A-01 and A-13) sit on the same
component: a retry button that cannot be clicked, and a focus ring that cannot
be told apart from its neighbour's.

---

## 2. Method, and how to repeat it

Everything below is reproducible from the repository with no network access and
no build step. Open any file in `docs/design/prototypes/` directly in a browser.

### 2.1 Contrast — how the ratios were produced

WCAG 2.1 relative luminance and contrast, applied to the hex values in
`docs/design/prototypes/assets/tokens.css` §1 (the primitive palette):

```
for each sRGB channel c, normalised to 0..1:
    c_lin = c / 12.92                      if c <= 0.04045
    c_lin = ((c + 0.055) / 1.055) ** 2.4   otherwise

L     = 0.2126 * R_lin + 0.7152 * G_lin + 0.0722 * B_lin
ratio = (L_lighter + 0.05) / (L_darker + 0.05)
```

**Tooling limitation, stated plainly.** The review environment for this Task
permits `git`, `gh` and file reads, but refuses to execute a script interpreter
(`python <file>`, `python -c`, `node -e` and `awk` were each tried and refused by
the permission layer; `python --version` is permitted, so the refusal is of
script execution, not of Python). No browser automation (Playwright MCP) was
available either. The ratios below were therefore computed **by hand**, applying
the formula above. That is reliable to roughly ±0.003, which is ample for a
pass/fail call at 3:1 and 4.5:1, but it is **not** precise enough to settle a
disagreement in the second decimal place. Where a published figure sits inside
that error bar, this report says so rather than guessing.

Worked example, so any reviewer can check the method end to end —
`focus` on `bg`, dark theme (`--blue-300` `#8CBEF5` on `--slate-850` `#181B1C`):

| | R | G | B | L |
| --- | --- | --- | --- | --- |
| `#8CBEF5` | 0.262249 | 0.514918 | 0.913107 | **0.489950** |
| `#181B1C` | 0.009134 | 0.010960 | 0.011612 | **0.010619** |

`(0.489950 + 0.05) / (0.010619 + 0.05)` = **8.907**.

Eleven pairs were computed independently this way, chosen as: every published
figure this audit found in dispute and could adjudicate, the two pairs closest to
their threshold, and the two pairs nobody had recorded. They are listed in §4.2.
The remaining pairs were checked for *source* — that the pair is one
`DESIGN.md` → "Measured contrast" or `components.md` §3.4 already records — not
recomputed.

### 2.2 Keyboard — how the traversal was checked

Static source analysis of every prototype and of the canonical page HTML in
`docs/design/pages/*.md`, checking:

- every interactive element is a native `<a href>`, `<button>`, `<input>`,
  `<select>` or carries an explicit `tabindex="0"`;
- no positive `tabindex` anywhere (confirmed: zero occurrences);
- no CSS `order` property anywhere, so DOM order is tab order
  (confirmed: zero occurrences);
- `display:none` / `[hidden]` correctly removes closed disclosures from the
  tab ring;
- the focus ring is never suppressed without replacement, **and where a
  replacement ring is drawn on an ancestor, no two tab stops inside that
  ancestor can produce the same ring**;
- focus-return targets exist and are focusable.

That second clause was added after review. Worded as "never suppressed without
replacement" alone, the check passes `components.css:878` — a ring *is* drawn,
just on the card — and that is exactly how **A-13** survived the first pass.
Anyone repeating this audit should apply the clause as written.

The one live keyboard check that a static sheet can carry is in
`prototypes/components.html` §14: a real `<dialog>` opened with `showModal()`,
with the only JavaScript in the whole design set (`components.html:1815-1827`).
Open that file, press *Mở hộp thoại*, then test Tab cycling, Esc, and focus
return to the opener. That one is reproducible by anyone, in any browser.

### 2.3 What a static sheet cannot prove

Recorded here so the gaps are visible rather than silent:

- **Mobile nav disclosure.** `components.css:287` drives the menu from
  `[aria-expanded]`, which needs script. The closed and open states are both
  drawn (`home.html:87` closed, `home.html:179` open), but the transition
  between them is not exercisable.
- **The account and cookie dialogs** are static `<div class="dialog"
  role="dialog">` in the sheets, not `<dialog>` elements
  (`account.html:243-250` documents this). Their focus behaviour is specified in
  prose, not demonstrated. Only `components.html`'s dialog is live.
- **Screen-reader announcement.** A-03 below is a markup-level finding. Whether
  a given screen reader stays silent is implementation-dependent; the fix is
  correct regardless, but a real NVDA/VoiceOver pass belongs in M2.
- **Type scale in the 375 px panels.** `tokens.css` §7 steps five type tokens in
  a viewport media query, so in a window ≥ 768 px the 375 px panels render
  headings at desktop size. Narrow the window below 768 px to read them at their
  true scale. Already recorded by `wireframes.md` §2 and `components.md` §1.3.

---

## 3. Coverage — what was checked

### 3.1 Full-page renders

All eight pages render at **light × dark** and **375 px × 1280 px**: 32 renders,
all present and all inspected.

| Page | File | Light 375 | Dark 375 | Light 1280 | Dark 1280 |
| --- | --- | :-: | :-: | :-: | :-: |
| Home | `home.html` | yes | yes | yes | yes |
| Play | `play.html` | yes | yes | yes | yes |
| Leaderboards | `leaderboards.html` | yes | yes | yes | yes |
| Account | `account.html` | yes | yes | yes | yes |
| Privacy | `privacy.html` | yes | yes | yes | yes |
| Cookie settings | `cookie-settings.html` | yes | yes | yes | yes |
| About | `about.html` | yes | yes | yes | yes |
| Terms | `terms.html` | yes | yes | yes | yes |

`play.html` additionally renders a 768 px compact band (`play.html:540`), above
what was asked for.

### 3.2 State renders — the gap

The acceptance criterion asks for empty / loading / error at **both** widths in
**both** themes. Taking each sheet's own render captions as the evidence, five
pages demonstrate their state blocks in only some of the four cells. See **A-08**.

| Page | State coverage demonstrated | Missing cells |
| --- | --- | --- |
| Leaderboards | all three states × all four cells, twice (own-rank block and main board) | — |
| Account | all four cells, §2–§5 | — |
| Privacy | all four cells | — |
| Home — grid | L375 empty · D375 error · L1280 loading · D1280 empty+error | no single cell shows all three |
| Home — top-3 strip | L375 empty+loading · D375 error | both 1280 cells |
| Play — frame | L375 all four · D375 all four · L1280 loading+error | dark 1280 |
| Play — weekly board | L375 · D375 · L1280 | dark 1280 |
| About | L375 all three · D375 all three · L1280 | dark 1280 |
| Terms | L375 all three · D375 all three | both 1280 cells |
| Cookie settings | L375 · D1280 | dark 375, light 1280 |

**Not a gap:** the cookie settings screen has **no empty state**, and says so as
a claim rather than omitting it silently (`cookie-settings.html:653-662`): the
category list is site-defined and always holds exactly two entries, so an empty
settings screen would be a fault, not a state. `components.md` §4.4 records the
same. Accepted.

### 3.3 Rules checked and clean

- **No analytics, trackers, pixels or third-party requests anywhere.** A search
  across every prototype and stylesheet for `<script`, `<iframe`, `src=`,
  `http://`, `https://`, CDN hosts, font files and the common tag names returns
  nothing but prose mentions and the one inline dialog demo in
  `components.html`. Zero network requests. Nothing to flag.
- **No framework, build step, runtime dependency or package manifest** added
  anywhere in the design set.
- **`lang="vi"` and a unique `<title>`** on all eight pages.
- **Reduced motion:** nothing animates at all. `tokens.css` §8 collapses the
  duration tokens and `base.css` §9 is the backstop; the skeletons are static by
  deliberate decision (`components.css:1314-1328`). Turning on
  `prefers-reduced-motion` changes nothing, because nothing moved.

---

## 4. Contrast results

### 4.1 Verdict

**PASS.** Every pair in use meets its threshold. No measured value falls below
3:1 for a UI boundary or 4.5:1 for text, in either theme, at either width. The
two sub-3:1 pairs in the system (`border` on `surface`, `border` on `bg`, used
for card edges and skeleton bars) are purely decorative, carry no information,
and the skeleton bars are `aria-hidden` — WCAG 1.4.11 exempts them, and
`DESIGN.md` and `components.md` §3.3 both record them as decorative in advance.

The tightest real pairs, both of which pass:

| Pair | Theme | Measured | Needs |
| --- | --- | --- | --- |
| `border-strong` on `sunken` | light | **3.150** | 3 |
| `border-strong` on `surface` | dark | **3.196** | 3 |

### 4.2 Independently recomputed pairs

| # | Pair | Theme | This audit | `DESIGN.md` | Page sheets | Note |
| --- | --- | --- | --- | --- | --- | --- |
| 1 | `text` on `bg` | light | 14.648 | 14.64 | 14.65 | DESIGN.md correct per its own convention |
| 2 | `primary` on `bg` | light | 5.697 | 5.69 | 5.69 | agreed |
| 3 | `primary` on `surface` | light | 6.0596 | 6.06 | 6.05 | **inside error bar — cannot adjudicate** |
| 4 | `primary` on `bg` | dark | 8.662 | 8.66 | 8.65 | **page sheets wrong** |
| 5 | `focus` on `bg` | dark | 8.907 | 8.90 | 8.92 | **page sheets wrong** |
| 6 | `on-primary` on `primary` | light | 6.113 | 6.11 | 6.08 | **page sheets wrong** |
| 7 | `on-primary` on `primary` | dark | 8.155 | 8.15 | 8.13 | **page sheets wrong** |
| 8 | `border-strong` on `surface` | dark | 3.196 | 3.19 | 3.19 | agreed |
| 9 | `border-strong` on `sunken` | light | 3.150 | 3.14 | 3.15 | **inside error bar — cannot adjudicate** |
| 10 | `text-muted` on `sunken` | light | **5.657** | not recorded | not recorded | see A-07 |
| 11 | `text-muted` on `sunken` | dark | **8.232** | not recorded | not recorded | see A-07 |

`DESIGN.md` states its convention at line 358: *"the exact ratio, truncated —
never rounded, and never copied from a different document."* Rows 1 and 4–7 are
the page sheets departing from that; row 3 is `DESIGN.md` itself departing from
it, if the exact value is below 6.06. See **A-06**.

**None of these disagreements flips a pass/fail.** Every value involved clears
its threshold by a wide margin. The defect is accuracy and traceability of
published figures, not contrast.

---

## 5. Keyboard results

### 5.1 Verdict by check

| Check | Result | Evidence |
| --- | --- | --- |
| Every interactive element reachable | **pass** | All controls are native elements; the one `tabindex="0"` is the game frame (`play.html:127`), deliberate and named. |
| No positive `tabindex` | **pass** | Zero occurrences across all prototypes and page docs. |
| Tab order matches reading order | **pass** | Zero uses of CSS `order`. The one visual/DOM divergence — the play page's side rail coming last at ≥1024 px — is the correct pattern for supplementary content and is declared at `play.html:1117`. |
| Visible focus indicator | **pass, with A-13** | `base.css:160-164`, 3 px ring, 2 px offset, measured 5.11:1 or better light and 7.94:1 or better dark. The offset is load-bearing: focus blue on primary teal measures 1.01:1, so the offset moves the ring onto the surface behind. A ring is always *drawn* — but in one state it is drawn around the wrong element: see **A-13**. |
| No keyboard trap | **pass** | No sticky surface traps focus; the consent banner is explicitly non-modal (`cookie-settings.html:876-884`). Tab cycling *inside* an open `showModal()` dialog is the required behaviour of WCAG 2.1.2, not a violation, and Esc / Cancel / close all exit. |
| Skip to content | **pass, with A-02** | First focusable element in the document, before the header ad slot, on all eight pages. Never `display:none`. Lands on `#main` — but see A-02 for what `#main` is missing. |
| Focus moves **into** the consent banner | **pass** | `tabindex="-1"` on the `<aside>`, not on "Chấp nhận" — so no answer is pre-selected for an inertial Enter (`cookie-settings.html:865-874`, `privacy-consent.md:574`). Correct and well reasoned. |
| Focus **returns** on close | **fail** | See **A-02**. The return target is specified as `#main`, which is not focusable as written. |
| Modal focus trap and return | **pass in principle, unproven on 2 of 3 dialogs** | `components.html`'s live dialog is correct and testable. The account and cookie dialogs are static in the sheets; their contract is specified in prose only. |

### 5.2 Tab order, confirmed per page

Each sheet publishes its own tab-order table and each was checked against the
markup. They are accurate. The shared opening sequence on all eight pages:

1. skip link → `#main`
2. wordmark
3. nav disclosure (below 768 px only; absent above it)
4–6. Trò chơi · Bảng xếp hạng · Tài khoản
7–8. theme radios (one group, arrow keys within it — native radio contract, no
   roving tabindex, no script)
— ad slots are **not** tab stops: no `tabindex`, no interactive content, in any
   of the three slot positions
… then page content, then four footer links, each a 44 px target.

---

## 6. Not colour alone

**PASS.** Checked every state, rank, error and emphasis in both themes and in
greyscale reasoning:

| Signal | Carried by colour | Also carried by |
| --- | --- | --- |
| Current nav item | `primary` | semibold weight **and** a 2 px underline, driven by `aria-current` |
| Selected segmented item | filled plate | semibold weight, filled plate, `aria-current` |
| Chosen theme | filled plate | semibold weight, the plate itself, the word, native radio `checked` |
| Error state | `error` red | the error icon **and** the literal word "Lỗi" (`.state__label`) |
| Warning notice | `warning` amber | icon **and** the word "Cảnh báo" |
| Success notice | `success` green | icon **and** the word "Thành công" |
| Form field error | `error` red | icon, the word "Lỗi" in the message, `aria-invalid`, and an inset ring on the control |
| The player's own leaderboard row | — (no colour used) | semibold weight, a `sunken` plate, a 2 px `border-strong` edge, **and** the word "Bạn" in the row |
| Essential cookie category | — | the literal text "Luôn bật" beside the control |
| Ad slot | `sunken` fill | a visible "Quảng cáo" label and a named landmark |

The design system states this as a rule (`components.css:56-58`) and keeps it
everywhere. Nothing was found that conveys meaning by colour alone.

---

## 7. Semantics

| Check | Result |
| --- | --- |
| Landmarks | **pass on the real pages.** `pages/*.md` all specify `<main id="main">`, `<header>`, `<nav aria-label>`, `<footer>`. The hi-fi sheets deliberately demote these to `<div>` because a document may hold only one `<main>` and each sheet holds four to seven renders; `home.html:890-900` states this and points at the real HTML. Correct handling of a real constraint. |
| Heading order | **pass on seven pages, fail on one sheet.** No skipped level in home, play, leaderboards, privacy, cookie-settings, about, terms. `account.html` skips h3 → h6 about fifteen times. See **A-09**. The real page HTML in `account.md` is correct. |
| Form labels | **pass.** Every control has a visible `<label for>`. No placeholder-only labels anywhere. Help text and error text are below the control and wired by `aria-describedby`. The required mark is the word "bắt buộc", not an asterisk. |
| Table headers | **pass.** Leaderboards: `<caption>`, `<th scope="col">` on all three columns. Account data table: `<caption>`, `<th scope="col">` **and** `<th scope="row">`. |
| Live regions | **fail for loading, pass for error.** See **A-03**. |
| Icon handling | **pass.** Every decorative `<svg>` carries `aria-hidden="true" focusable="false"`; every icon-only control carries a visually hidden name. Every "Chơi" link is named "Chơi *&lt;game title&gt;*", so a links list is not fifteen identical entries. |

---

## 8. Findings

Severity: **Major** — breaks a named acceptance check or ships a non-functional
control. **Minor** — real defect, no user is blocked. **Advisory** — not a WCAG
2.1 AA failure; recorded because it breaks a rule this project set for itself.

Page / state / breakpoint / theme are given per finding; "all" means the finding
does not vary along that axis.

Findings are ordered by severity, not by ID. **A-13** was raised in review after
A-01 … A-12 were published; its ID is kept stable and it is filed with the other
Majors, next to A-01, which it shares a fix with.

---

### A-01 · Major · The retry button inside a game card cannot be clicked

- **Page:** Home (and any page placing a state block inside `.game-card`)
- **State:** top-3 strip, error · **Breakpoint:** both · **Theme:** both
- **Fix owner:** M1-04 component library (`components.css`), with SOU-3 (M1-05)
  as the consumer

`.game-card` is `position: relative` (`components.css:781`) and the card's play
link stretches a hit area over the entire card:

```css
.game-card__action .btn::after { content: ''; position: absolute; inset: 0; }
```

An absolutely-positioned `::after` paints above in-flow content. When the top-3
strip is in its error state, the card also contains a **"Thử lại" button**
(`home.html:677`) which has no `position` and no `z-index`, so it paints
*underneath* the overlay.

**Failure scenario:** a player's weekly board fails to load. They point at
"Thử lại" and click. The click lands on the stretched play link instead, and
they are navigated into the game rather than retrying the board. The button is
dead to mouse and touch. Keyboard users can still reach and activate it, so the
defect is pointer-only — which makes it easy to miss in a keyboard-focused review.

This also contradicts two documented claims:

- `home.md:273-274` — *"A broken strip does not break the card … still there and
  still work."*
- `components.md` §6.2 and `home.md:172` — *"One control per card."* In the error
  state the card has two controls, and the second one is unreachable by pointer.

**Suggested direction (the fix is the owning Task's call):** scope the stretched
hit area so it does not cover a nested state block, or drop the stretch on any
card that contains a second control.

**The fix must cover three rules, not one.** `components.css:862` is only the
pointer half of this defect. The same "the card is the control" assumption is
encoded again in the focus rules at `components.css:872` and `:878`, and that
half is **A-13**. Scoping the `::after` alone fixes the pointer bug and ships
the keyboard one. Whatever condition the owning Task uses to disable the stretch
— a modifier class, or a `:has()` test for a nested state block — must gate all
three rules together, so that a card with a second control gets no stretched hit
area, no card-level ring, and no suppression of the ring on its own buttons.

---

### A-13 · Major · In the error state the focus ring describes the wrong control

- **Page:** Home (and any page placing a state block inside `.game-card`)
- **State:** top-3 strip, error · **Breakpoint:** both · **Theme:** both
- **Fix owner:** M1-04 component library (`components.css`), with SOU-3 (M1-05)
  as the consumer — **same fix as A-01**

Found in review of this report, not in the original pass; numbered A-13 because
the earlier IDs were already published, and placed here with the other Majors.
Same card, same file and same root cause as A-01 — that assumption's pointer
half is A-01, and this is its keyboard half.

```css
.game-card:has(.btn:focus-visible),
.game-card.is-focus {
  outline: var(--focus-ring-width) solid var(--color-focus);
  outline-offset: var(--focus-ring-offset);
}

.game-card:has(.btn:focus-visible) .btn:focus-visible {
  outline: none;
}
```

`components.css:872-880`. The "Thử lại" button (`home.html:677`) is a `.btn`
inside `.game-card`, so `:has(.btn:focus-visible)` matches when it is focused:
the ring is drawn around the **whole card**, and the second rule then **removes
it from the button itself**. No rule restores it — the only `:focus-visible`
rules in the stylesheet set are `base.css:160`, `base.css:167`,
`components.css:224`, `:447`, `:872` and `:878`.

**Failure scenario:** a keyboard user on Home, top-3 strip in its error state.
Tab lands on "Thử lại" — the whole card rings. Tab again lands on "Chơi" — the
whole card rings identically. Two consecutive tab stops, one pixel-identical
indicator, and no way to tell which control is focused or what Enter will do.
Unlike A-01 this one is keyboard-only: a pointer user sees hover and the cursor.

The CSS comment at `components.css:869-871` states the rule's own premise —
*"the whole card is what will activate"* — which is true of "Chơi" and false of
"Thử lại". The premise, not the implementation, is what fails here, which is why
it is the same defect as A-01 wearing different clothes.

This does **not** fail WCAG 2.4.7 (a visible indicator is present) and it is not
a 2.4.11 failure under 2.1, which has no such criterion. It is a Major because
it defeats what the indicator is *for*: §5.1 of this report accepts the focus
ring as the evidence that keyboard users can tell where they are, and in this
state it is not evidence of that.

**Suggested direction:** gate both rules on the same condition as A-01's
`::after`, so that a card containing a second control falls back to `base.css`'s
per-element ring, which is already correct for this case.

---

### A-02 · Major · `#main` cannot receive the focus the design returns to it

- **Page:** all eight · **State:** all · **Breakpoint:** both · **Theme:** both
- **Fix owner:** every page doc in `docs/design/pages/`; the contract itself is
  SOU-7 (`privacy-consent.md`)

The canonical page HTML declares the main landmark without making it
programmatically focusable:

```html
<main id="main" class="shell main">
```

Confirmed in all six page docs: `home.md:96`, `account.md:101`,
`leaderboards.md:114`, `play.md:111`, `about-terms.md:102`,
`privacy-consent.md:103`. **None carries `tabindex="-1"`.**

But the focus contract requires it, by name, twice:

- `privacy-consent.md:601` — after Chấp nhận / Từ chối, focus goes to *"the top
  of `#main` (`tabindex="-1"`)"*.
- `cookie-settings.html:891,894` — the same, and again after the settings dialog
  saves, *"because the button that opened the dialog no longer exists, so
  returning focus to it is returning focus to nothing."*

**Failure scenario:** a keyboard user dismisses the consent banner on first
visit. The page calls `.focus()` on `<main id="main">`. Because the element is
not focusable, the call silently does nothing: focus falls back to `<body>` and
the user's next Tab starts again at the skip link, at the very top of the
document — precisely the outcome the design doc says must not happen. The same
happens after saving in the cookie dialog.

The skip link itself still works in current browsers, which set the sequential
focus navigation starting point on fragment navigation — so this is not a
skip-link failure. It is a scripted-focus failure.

**Evidence of the inconsistency being known:** three of the seven `main`
containers in `cookie-settings.html` (lines 346, 377, 410) *do* carry
`tabindex="-1"`; the other four, and every `main` in the other seven sheets, do
not.

**Fix:** `<main id="main" tabindex="-1">` in every page doc, and in the sheets
for consistency.

---

### A-03 · Major · The loading live region is marked so that it will not announce

- **Page:** all eight · **State:** loading · **Breakpoint:** both · **Theme:** both
- **Fix owner:** M1-04 component library (`components.md` §4.3, and the markup
  pattern each page copied from it)

Every loading block in the design set puts `role="status"` and
`aria-busy="true"` on the **same element**, with the hidden status text
**inside** that element:

```html
<div class="state state--loading" role="status" aria-busy="true">
  <span class="u-visually-hidden">Đang tải bảng tuần…</span>
  <div class="skeleton" aria-hidden="true"> … </div>
</div>
```

(`home.html:540,648`, `play.html:682,725,765,925`, `privacy.html:685,719,755,789`,
`cookie-settings.html:725,821`, `leaderboards.html:715,745,776`,
`components.html:1552,1575,1593`, and `components.md:359` which prescribes it.)

`aria-busy="true"` tells assistive technology to **defer** announcing that
region until busy clears. The text it is being asked to defer is the only
"it is working" signal the design has — `components.css:1314-1328` deliberately
removed the shimmer precisely because *"a shimmer tells a screen-reader user
nothing at all"*, and put the signal in this text. The block is then **replaced**
when loading finishes rather than having `aria-busy` set to `false`, so busy
never clears on that element and there is no moment at which the deferred
announcement fires.

`components.md:372-373` already states the correct principle —
*"`aria-busy="true"` goes on the block that is loading, and comes off when it is
done"* — but the markup puts the status region inside that same block, which
defeats it.

**Failure scenario:** a screen-reader user opens the leaderboards page on a slow
connection. The board is replaced by a skeleton. Nothing is announced. The
skeleton bars are `aria-hidden`, so there is no alternative signal. The user is
told nothing at all until the data arrives — the exact outcome the no-shimmer
decision was taken to avoid.

**The correct pattern already exists in this codebase:** `privacy.html:206`
carries `<div class="consent-form__status" role="status"></div>` — a persistent,
empty-at-rest live region, with no `aria-busy`, filled when there is something to
say. That is what `components.md` §4.3 note 1 describes.

**Fix:** keep `aria-busy="true"` on the region being replaced; move
`role="status"` and its text to a separate, persistent live region outside it.

**Caveat, stated honestly:** exact screen-reader behaviour with `aria-busy` on a
live region varies by implementation, so this is a markup-level finding rather
than an observed silence. The fix is correct regardless, and a real NVDA /
VoiceOver pass should confirm it in M2.

---

### A-04 · Minor · `aria-disabled` buttons look fully available

- **Page:** Account · **State:** default (no identity linked) · **Breakpoint:**
  both · **Theme:** both
- **Fix owner:** M1-04 component library (`components.css` §7, `components.md` §5.5)

`account.html:212,375,534,…` ships:

```html
<button class="btn btn--secondary" type="button"
        aria-disabled="true" aria-describedby="sowhy-a">Đăng xuất</button>
```

The decision to use `aria-disabled` on an enabled button rather than `disabled`
is sound and well argued (`account.html:204-210`): it keeps the control in the
tab order and lets the reason be announced. **But no stylesheet gives
`[aria-disabled="true"]` a visual treatment on a button.** A search of
`base.css`, `components.css` and `account.css` finds exactly one `aria-disabled`
rule, and it is for `.link` (`components.css:646`). `base.css:213`'s `:disabled`
selector does not match `aria-disabled`.

**Failure scenario:** the button renders pixel-identical to an available
secondary button. A sighted user — including a sighted keyboard user — reads it
as actionable, clicks, and nothing happens. The unavailable state is conveyed to
assistive technology only. That is the mirror image of the "never colour alone"
rule this system keeps so carefully everywhere else.

**Fix:** a `.btn[aria-disabled="true"]` rule giving the same visual treatment
`:disabled` gets, and a line in `components.md` §5.5 requiring it.

---

### A-05 · Minor · The dark-theme modal backdrop is a white veil

- **Page:** Account (confirm dialog), Cookie settings (settings dialog),
  Components (live dialog) · **State:** dialog open · **Breakpoint:** both ·
  **Theme:** dark only
- **Fix owner:** M1-04 component library (`components.css`)

```css
.dialog::backdrop {
  /* Token-derived, not a raw colour: the page's own ink at half strength. */
  background-color: var(--color-text);
  opacity: 0.5;
}
```
(`components.css:1127-1131`)

The comment's intent is "ink at half strength". In the light theme
`--color-text` is `--ink-900` `#1D211F` and the intent holds. **In the dark theme
`--color-text` is `--ink-100` `#E7E9E7`** — a near-white. The dark-theme modal
backdrop is therefore a 50 % **white** scrim over a dark page.

**Failure scenario:** a player on the account page in dark mode presses "Xóa dữ
liệu của tôi". The page behind the dialog flashes to a washed mid-grey instead of
dimming. It is visually jarring in exactly the way `DESIGN.md`'s stated emotional
target forbids, and it lowers the contrast of everything behind the dialog rather
than receding.

Reproducible now: open `prototypes/components.html`, switch the sheet to dark,
open the live dialog.

**Fix:** a backdrop token that resolves to a dark value in both themes, rather
than reusing `--color-text`.

---

### A-06 · Minor · Published contrast figures disagree between documents

- **Page:** Home, About, Play, Terms (their §Tương phản tables) · **State:** all ·
  **Breakpoint:** all · **Theme:** both, dark worse
- **Fix owner:** M1-01 (`DESIGN.md`, the source table) and each page Task for
  its own copy

`DESIGN.md:358` sets the rule: *"the exact ratio, truncated — never rounded, and
never copied from a different document."* The page mockups publish different
numbers for the same pairs:

| Pair | Theme | `DESIGN.md` | Page sheets | This audit |
| --- | --- | --- | --- | --- |
| `on-primary` on `primary` | light | 6.11 | **6.08** | 6.113 |
| `on-primary` on `primary` | dark | 8.15 | **8.13** | 8.155 |
| `on-primary` on `primary-hover` | light | 8.57 | **8.55** | — |
| `on-primary` on `primary-hover` | dark | 9.65 | **9.63** | — |
| `focus` on `bg` | dark | 8.90 | **8.92** | 8.907 |
| `focus` on `surface` | dark | 7.94 | **7.96** | — |
| `focus` on `sunken` | dark | 9.58 | **9.60** | — |
| `primary` on `bg` | dark | 8.66 | **8.65** | 8.662 |
| `primary` on `surface` | light | **6.06** | 6.05 | 6.0596 |
| `text` on `bg` | light | 14.64 | **14.65** | 14.648 |
| `text` on `sunken` | dark | 15.26 | **15.27** | — |
| `text-muted` on `bg` | dark | 7.65 | **7.66** | — |
| `text-muted` on `surface` | dark | 6.82 | **6.83** | — |
| `error` on `bg` | light / dark | 6.30 / 7.45 | **6.31 / 7.46** | — |
| `warning` on `bg` | light | 6.08 | **6.09** | — |
| `secondary` on `surface` | dark | 6.78 | **6.77** | — |
| `border-strong` on `bg` | dark | 3.57 | **3.58** | — |
| `border-strong` on `surface` | light | 3.70 | **3.71** | — |
| `border-strong` on `sunken` | light / dark | 3.14 / 3.84 | **3.15 / 3.85** | 3.150 / — |
| `border` on `bg` (decorative) | light / dark | 1.32 / 1.49 | **1.33 / 1.50** | — |

The last row also disagrees *within* the component layer:
`components.css:1326` says 1.33 / 1.50 while `account.css:301` says 1.32 / 1.49.
`components.css:147` says the focus ring measures "7.96:1 or better in dark"
against `DESIGN.md`'s 7.94.

Where this audit could adjudicate, the errors run **both ways**: the page sheets
are wrong on `on-primary` (by 0.02–0.03, outside any rounding convention) and on
`focus`/`primary` in dark; `DESIGN.md` appears to round rather than truncate on
`primary` on `surface` light.

**No pass/fail is affected.** Every figure involved clears its threshold with
room. The defect is that the Board is being shown figures that are not
reproducible from the stated method, in a document set whose whole discipline is
that they should be.

**Fix:** recompute the whole table once with a tool, publish it in `DESIGN.md`,
and have every other document cite it rather than restate it. Note that this
Task could not run that tool — see §2.1 — so the recomputation should be done in
an environment that can.

---

### A-07 · Minor · The ad-slot label uses an unmeasured pair and breaks the surface rule

- **Page:** Home, Play, Leaderboards (every page with an ad slot) · **State:**
  all · **Breakpoint:** 768 px and up (and the mobile slot on Play) · **Theme:** both
- **Fix owner:** M1-02 (`ads.css`, `ad-slots.md`), plus `DESIGN.md` for the table

`ads.css:162,175` renders the slot label as `--color-text-muted` on a
`--color-sunken` fill. Three problems, none of them a contrast failure:

1. **The pair is not recorded anywhere.** `DESIGN.md` → "Measured contrast"
   records `text-muted` on `bg` and on `surface`, and `text` on `sunken` — but
   not `text-muted` on `sunken`. `ad-slots.md` publishes no ratio for the label
   at all. `DESIGN.md` forbids using a pair that has not been measured and
   written down.
2. **It breaks the surface rule.** `components.css:41-43` states it flatly:
   *"`sunken` carries ONLY `text` … **No muted text**, no accent, no meaning
   colour is ever set on a sunken fill."*
3. **The page audit tables mis-state it.** `home.html:833` lists the ad-slot
   label as `text-muted` on **`bg`** (6.25 / 7.66), and `home.html:843` lists
   "text on the ad slot background" as `text` on `sunken`. Neither row describes
   what `ads.css` actually draws.

**Measured by this audit** (`--ink-600` `#555B57` on `--paper-200` `#E8E8E3`;
`--ink-300` `#A7AEAA` on `--slate-900` `#111314`):

| Pair | Light | Dark | Needs | Result |
| --- | --- | --- | --- | --- |
| `text-muted` on `sunken` | **5.657** | **8.232** | 4.5 | **PASS** |

So the label is legible in both themes. The finding is that it reached
production-candidate state without being measured, against a rule that exists to
stop exactly that, and that three documents describe it incorrectly.

**Fix:** add the measured pair to `DESIGN.md`, correct the page tables, and
either amend the surface rule to admit this pair or move the label to `text`.

---

### A-08 · Minor · State coverage is incomplete on five of eight pages

- **Page:** Home (top-3), Play, About, Terms, Cookie settings · **State:** varies ·
  **Breakpoint:** 1280 px mostly · **Theme:** dark mostly
- **Fix owner:** SOU-3 (home, about, terms), SOU-4 (play), SOU-7 (cookie settings)

The acceptance criterion asks for every state at both widths in both themes. The
missing cells are tabulated in §3.2. In summary: the dark 1280 state render is
missing on Play (frame and weekly board) and About; both 1280 state renders are
missing on Terms and on the Home top-3 strip; Cookie settings demonstrates its
dialog states only at light 375 and dark 1280.

This is a **demonstration** gap, not a proven defect — state blocks read their
colours from tokens and their geometry is width-driven, so the missing cells are
very unlikely to hold a surprise. But the criterion asks for the cross-product
and the Board asked for gaps to be visible rather than silent, so it is recorded
rather than waved through. Leaderboards, Account and Privacy are complete and
show what complete looks like.

---

### A-09 · Minor · Fifteen heading-level skips in the account mockup sheet

- **Page:** Account (`prototypes/account.html` only — the real page HTML is
  correct) · **State:** all specimens in §2–§5 · **Breakpoint:** both ·
  **Theme:** both
- **Fix owner:** SOU-6 (M1-08)

From §2 onward the sheet drops from the specimen caption `<h3 class="mk-cap">`
straight to `<h6>` for `.error-summary__title` and `.dialog__title`, skipping two
levels. First occurrence: `account.html:794` (h3) → `account.html:874` (h6).
It repeats at lines 888→968, 984→1025, 1040→1081, 1126→1131, 1231→1236,
1335→1343, 1371→1379, 1454→1464, 1531→1541, 1610→1621, 1660→1671, 1739→1744,
1761→1766, 1785→1791.

The §1 renders are correct (h3→h4→h5→h6); only the specimen sections skip. The
other seven sheets are clean — this was checked on every one.

**The real page is fine.** `account.md:102-171` uses h1 → h2 sections → h3, with
the dialog title as an h2 because a dialog starts its own outline. `account.md:1155`
claims *"one `<h1>` and no skipped level"*, which is true of the page and not of
the sheet — worth correcting so the claim is not read as covering both.

---

### A-10 · Advisory · Two elements carry `aria-current="page"` at once

- **Page:** Leaderboards · **State:** all · **Breakpoint:** both · **Theme:** both
- **Fix owner:** SOU-5 (M1-07)

`leaderboards.html:113` marks the nav item "Bảng xếp hạng" with
`aria-current="page"`, and `leaderboards.html:143` marks the selected game chip
in the segmented control with `aria-current="page"` as well. Both are live in the
same document.

ARIA does not forbid this, and the segmented items genuinely are links to
separate routes, so neither use is wrong in isolation. But a screen-reader user
hears "current page" announced for two different things in one document, which is
ambiguous. `aria-current="true"` on one of the two, or `aria-current="page"`
reserved for the most specific route, would read more cleanly. Not a WCAG 2.1 AA
failure.

---

### A-11 · Advisory · The skip link's focus ring is clipped at the viewport corner

- **Page:** all eight · **State:** skip link focused · **Breakpoint:** both ·
  **Theme:** both
- **Fix owner:** M1-04 component library (`components.css` §4)

`.skip-link` is pinned flush to the top-left of the header with
`inset-block-start: 0; inset-inline-start: 0` and `border-start-start-radius: 0`
(`components.css:200-222`). The focus ring is `3px` at `outline-offset: 2px`
(`base.css:160-164`), so it is drawn 2–5 px *outside* the element — which at the
top-left corner of the viewport is off-screen on two of the four sides.

The ring remains visible on the right and bottom edges, so WCAG 2.1 SC 2.4.7
(Focus Visible) is still satisfied and this is **not** an AA failure. It is
recorded because the first thing a keyboard user ever focuses on this site shows
a deliberately-designed indicator at half its intended area. A few pixels of
inset, or a negative offset on this one control, would fix it.

---

### A-12 · Advisory · Checkbox hit target is 20 px against the project's own 44 px rule

- **Page:** Cookie settings, Privacy · **State:** all · **Breakpoint:** both ·
  **Theme:** both
- **Fix owner:** M1-04 component library (`components.css` §13a)

`.choice input` is sized `var(--icon-md)` = 20 × 20 px (`components.css:1010-1017`).
The `.choice` row reserves `min-block-size: var(--target-min)` = 44 px, and the
associated `<label for>` is clickable, so the effective target is 44 px tall and
as wide as the label text — "Cần thiết" is a short word.

WCAG 2.1 level AA has **no** target-size criterion (2.5.5 is AAA in 2.1; the
24 px AA minimum arrives in WCAG 2.2), so this is not a failure of the standard
this audit is measured against. It is recorded because `tokens.css:265` declares
`--target-min: 44px` to be the *"minimum interactive target, every breakpoint"*,
and the raw checkbox does not meet the project's own rule.

---

## 9. Verdict against the M1 exit criterion

> **M1 exit criterion: "contrast and keyboard checks pass".**

### FAIL

Stated plainly, and with its parts separated so the Board can see what is solid:

| Component of the criterion | Verdict |
| --- | --- |
| **Contrast** — every pair measured, every pair against AA | **PASS** |
| **Keyboard traversal** — reachability, visible focus, tab order, no trap, skip link | **PASS** (one state's focus ring names the wrong control, A-13) |
| **Not colour alone** | **PASS** |
| **Semantics** — landmarks, heading order, labels, table headers | **PASS** (one mockup-sheet defect, A-09) |
| **Live regions** for loading and error | **FAIL** — A-03 |
| **Focus handling** for the modal and the consent banner | **FAIL** — A-02 |

The criterion is a conjunction, so two failing parts make the whole criterion
fail. This is **not** a verdict against the design. The palette is sound, the
component library is unusually disciplined, the contrast work is genuinely done,
and the keyboard model — one tab stop per card in every state but one, native
radios for the theme toggle, links rather than a JS tab widget, `showModal()`
rather than hand-rolled focus trapping, a consent banner that takes focus
without pre-selecting an answer — is better than most shipped products.

What fails is four Major findings across three specific, local things — A-01 and
A-13 are the pointer and keyboard halves of a single wrong assumption in one
component.

### What turns this into a PASS

Three fixes clear all four Major findings — A-01 and A-13 are one defect seen
from two input methods and are cleared together. None of the three touches a
token, a breakpoint or a layout:

1. **A-02** — add `tabindex="-1"` to `<main id="main">` in all six page docs.
   One attribute, six files.
2. **A-03** — move `role="status"` and its text out of the `aria-busy` block into
   a persistent sibling live region. One pattern change in `components.md` §4.3
   plus the copies in each page.
3. **A-01 and A-13** — stop treating the whole card as the control when the card
   holds a second one. Three rules in `components.css`, gated on one condition:
   the stretched hit area at `:862`, the card-level focus ring at `:872`, and
   the ring suppression at `:878`. Fixing only `:862` leaves A-13 shipped.

The Minor and Advisory findings should be raised as their own Tasks and are not
gating. **A-06** should be done before the Board relies on any published ratio,
and whoever does it needs an environment that can run a script — this one could
not.

No fixes were applied by this Task. Per SOU-8's rules, each finding names the
Task that owns the file; GAM-21 raises the fix Tasks.

---

## 10. Files audited

`docs/design/prototypes/` — `home.html`, `play.html`, `leaderboards.html`,
`account.html`, `privacy.html`, `cookie-settings.html`, `about.html`,
`terms.html`, `components.html`, `wireframes/*.html`

`docs/design/prototypes/assets/` — `tokens.css`, `base.css`, `components.css`,
`ads.css`, `home.css`, `play.css`, `leaderboards.css`, `account.css`,
`consent.css`

`docs/design/` — `DESIGN.md`, `components.md`, `ad-slots.md`, `wireframes.md`,
`pages/home.md`, `pages/play.md`, `pages/leaderboards.md`, `pages/account.md`,
`pages/privacy-consent.md`, `pages/about-terms.md`

# Privacy — `/privacy` · Cookie banner and consent settings

**Status:** Normative for the privacy page's layout and states, for the consent
banner's content and focus behaviour, and for the consent categories. Milestone
M1 (Design), Task SOU-7 (M1-09). Owner: `ui-designer`, through
`project-manager`. Last updated: 2026-11-05.

The mockups are [`../prototypes/privacy.html`](../prototypes/privacy.html) and
[`../prototypes/cookie-settings.html`](../prototypes/cookie-settings.html), and
are static HTML: open one in a browser and it works. No server, no build step,
no dependency, no network request, **no script of any kind**.

It is downstream of five documents and contradicts none of them, with **one
conflict that is raised rather than resolved** — see §6.1.

| Source | What it fixes | What this document does |
| --- | --- | --- |
| [`../../requirements.md`](../../requirements.md) §2, §4, §5 | that the page and the banner exist; the stored fields; equal-weight consent; "no ad script before consent" | builds exactly those, and nothing beyond them |
| [`../DESIGN.md`](../DESIGN.md) (M1-01) | colour, type, spacing, the reading measure, breakpoints, the **measured contrast table** | designs inside the measured pairs, inventing no value |
| [`../ad-slots.md`](../ad-slots.md) (M1-02) §3 | one header slot above 768 px, none below | places exactly that on the privacy page, and none on the consent surfaces |
| [`../wireframes.md`](../wireframes.md) (M1-03) §4.5, §4.6 | the seven sections, the `#cookies` anchor, the three equal actions, "no analytics category" | makes those regions high-fidelity; moves none, drops none, reorders none |
| [`../components.md`](../components.md) (M1-04) §5.11, §5.13, §5.16, §4 | the dialog, the **banner shell**, the long-form template, the three state patterns | reuses them; **adds no shared component** |

> **A numbering correction, recorded so it stops propagating.**
> [`about-terms.md`](about-terms.md) §1 and §10 call the privacy page "M1-07".
> M1-07 is the leaderboards page (SOU-5); Privacy is **M1-09** (SOU-7). The
> handoff in that document's §10 is still addressed to this Task and is acted
> on here; only the label was wrong. That file belongs to SOU-3's write set, so
> this Task does not edit it.

---

## 1. Scope — and what is deliberately absent

In scope: the privacy page at both design widths in both themes; the consent
banner's content, its two states and its focus behaviour; the consent settings
screen in its two homes; the categories and their plain-language explanations;
every state; the keyboard order; and the contrast of every pair used.

**Deliberately absent, and a defect if a later Task reads one into these files:**

- **The legal text itself.** It comes from **OQ-039** and must cover Decree
  13/2023, and GDPR if EU players are expected (`wireframes.md` §4.5). The prose
  in `privacy.html` is **placeholder written to exercise the layout**, and every
  render carries a `notice--warning` saying so. **Nothing in this Task's files
  is legal advice or a commitment by the studio.**
  The one exception is named in §5: the *table of what is stored* is not
  placeholder — it is `requirements.md` §4.1 in the player's words, and it is
  load-bearing.
- **Any consent-management platform, SDK, tag, pixel, script or third-party
  request.** This is a design, not an integration. There is no script at all in
  either prototype, inline or otherwise — see §9.
- **A new shared component**, colour, type step, spacing step, radius or
  breakpoint. Also no thirteenth icon: `components.md` §5.1 fixes the set at
  twelve and there is deliberately no "cookie" glyph.
- **Any framework, build step, runtime dependency or package manifest.**
- **The account page.** It is M1-08's and runs in parallel. §5 states the
  agreement the two pages must keep, from the source both of them read.

---

## 2. Three surfaces, one design

| Surface | Where it lives | What it is |
| --- | --- | --- |
| **Privacy policy** | `/privacy` — a route | The long-form document template, seven numbered sections. |
| **Cookie banner** | last in `<body>`, on every page, first visit only — **a surface, not a route** | One explanation, one "learn more" link, three equal actions. |
| **Consent settings** | **two homes, one design**: section 6 of `/privacy` (the `#cookies` anchor) **and** the modal dialog the banner opens | Two categories, two explanations, Save. |

**Why the settings have two homes and one design.** `requirements.md` §2 puts
the persistent settings link at `/privacy#cookies`, and `wireframes.md` §4.6
makes the dialog what the banner opens. Both are therefore required, and
drawing the category list twice is the reliable way to make the two disagree
with each other. The prototypes use the same markup in both places; the only
differences are the ones the container forces:

| | Inline, `/privacy#cookies` | In the dialog |
| --- | --- | --- |
| Sits on | `bg` | `surface` |
| Heading | section 6's `h2` | the dialog's own `h2` |
| Actions | *Lưu lựa chọn* only | *Hủy* then *Lưu lựa chọn* |
| Way out | navigating away | Esc · Hủy · the close button |

A page section does not need a Cancel, because leaving it is already the way
out. A dialog does, because it has taken over the screen.

---

## 3. The real page markup

The mockup sheets stack four renders in one document, so their chrome is
demoted out of the landmark roles and their headings sit one level deeper
(`components.md` §2.3 fixes that convention). **This is the markup of the real
page**, with the real landmarks and the real heading levels, so M2 can lift it
directly. The chrome — skip link, header, nav, theme toggle, ad slot, footer —
is identical to [`about-terms.md`](about-terms.md) §3 and is elided here.

```html
<main id="main" class="shell shell--text main">
  <h1>Chính sách quyền riêng tư</h1>
  <p class="doc__updated">Cập nhật lần cuối: …</p>

  <nav class="card" aria-label="Mục lục chính sách">
    <ol class="doc__toc-list">…seven entries…</ol>
  </nav>

  <section class="doc-section" id="…-1">
    <h2 class="doc-section__title">1. Chúng tôi lưu gì</h2>
    …the table of §5…
  </section>
  …

  <!-- Section 6 is the settings screen, not a paragraph about it. -->
  <section class="doc-section" id="cookies">
    <h2 class="doc-section__title">6. Cookie</h2>
    <div class="notice notice--info">…only two categories, and why…</div>
    <form> <!-- the sheet omits this element; see the note below -->
      <fieldset>
        <legend class="consent-fieldset__legend">Nhóm cookie</legend>
        <ul class="consent-cats">
          <li class="consent-cat">
            <div class="choice">
              <input id="cookie-essential" type="checkbox" checked disabled
                     aria-describedby="cookie-essential-desc">
              <label for="cookie-essential">Cần thiết</label>
              <span class="consent-cat__always">Luôn bật</span>
            </div>
            <p class="consent-cat__desc" id="cookie-essential-desc">…</p>
          </li>
          <li class="consent-cat">
            <div class="choice">
              <input id="cookie-ads" type="checkbox"
                     aria-describedby="cookie-ads-desc">
              <label for="cookie-ads">Quảng cáo</label>
            </div>
            <p class="consent-cat__desc" id="cookie-ads-desc">…</p>
          </li>
        </ul>
      </fieldset>
      <div class="consent-form__actions">
        <button class="btn btn--primary" type="submit">Lưu lựa chọn</button>
      </div>
    </form>
  </section>
</main>

<!-- Last in <body>, after the footer. Not modal. The site's one sticky box. -->
<aside class="consent" tabindex="-1" aria-labelledby="consent-title">
  <h2 class="u-visually-hidden" id="consent-title">Lựa chọn cookie</h2>
  <p class="consent__text">…one explanation… <a class="link" href="/privacy#cookies">Tìm hiểu thêm</a></p>
  <div class="consent__actions">
    <button class="btn btn--secondary" type="button">Chấp nhận</button>
    <button class="btn btn--secondary" type="button">Từ chối</button>
    <a class="btn btn--secondary" href="/privacy#cookies">Cài đặt</a>
  </div>
</aside>
```

**Heading levels on the real page:** exactly one `<h1>`, then one `<h2>` per
numbered section. The dialog's title is an `<h2>` too, because a dialog starts
its own outline (`components.md` §2.3). No level is skipped.

**The `<form>` element is in the product and not on the sheet.** A bare `<form>`
with no `action` submits to the current URL on Enter, which on a mockup means a
stray keystroke reloads the sheet and loses the forced states a reviewer is
looking at. The sheets therefore use the fieldset and the buttons without the
form wrapper, and this block is the markup to lift. Same reason
`components.html` §5.10 renders its fields without one.

**The banner's accessible name.** `components.md` §2.3 sketches the shell as
`<aside class="consent" aria-label="Cookie">`. This Task keeps the element and
replaces the name with a visually hidden `<h2>` wired by `aria-labelledby`,
because focus lands on this region (§8.1) and "Cookie, complementary" is a
weaker thing to hear at that moment than "Lựa chọn cookie, complementary". The
shell decides structure; the name is content, and content is this Task's.

---

## 4. The categories — why two, and why these two

### 4.1 The list

| Category | Default | Can it be turned off? | What it actually covers |
| --- | --- | --- | --- |
| **Cần thiết** (strictly necessary) | on | **no**, and it says so | the session; the consent choice itself; the light/dark theme the player picked |
| **Quảng cáo** (advertising) | **off** | yes | cookies an ad network sets for ads on the website page |

**There is no third category, and that is the design.** `requirements.md` §4.2
is explicit — no analytics, no telemetry, no advertising identifier beyond what
the slot itself requires, no third-party script — and `wireframes.md` §4.6 says
it in so many words: *"There is no analytics category, because the site collects
none. Do not add a switch for something that does not exist."* A toggle for a
thing that is not there is not a courtesy; it is a claim that the thing exists.

So that the absence reads as deliberate rather than as an omission, both
surfaces carry a `notice--info` that says it out loud: *"Chỉ có hai nhóm.
Soucean Game không dùng công cụ phân tích và không có trình theo dõi nào, nên
không có nút bật/tắt cho những thứ đó."*

### 4.2 Why the theme choice is inside "Cần thiết" rather than a third toggle

Light-or-dark is stored only because the player asked for it, it never leaves
the browser, and it identifies nobody. Giving it its own category would turn one
honest sentence into a third switch that nothing depends on. It is therefore
named in the Cần thiết explanation — not hidden, not promoted.

### 4.3 Why "Quảng cáo" exists before any ad does

No ad script ships until M6. The category is still the right design today
because `requirements.md` §5.3 fixes the rule *now*: "ad scripts load only after
consent", and consent that is collected after the fact is not consent. The copy
is written so it is true in both worlds — *"Cookie quảng cáo chỉ đặt khi bạn
đồng ý"* — and never claims that ads are currently running.

### 4.4 Why a checkbox and not a switch

The Task brief says "toggles"; this design uses a native
`<input type="checkbox">`. Three reasons, in order of weight:

1. **There is no switch component to use.** `DESIGN.md`'s inventory names a
   `toggle`, but M1-04 designed the theme control as two real radios and drew no
   switch. `components.md` §7.1 forbids a page Task from adding a variant to the
   shared library, so inventing one here is out of bounds.
2. **A switch implies immediate effect; this form has a Save.**
   `wireframes.md` §4.6 gives the dialog Save and Cancel. A switch that does not
   take effect until you press Save is a switch that lies about what it did.
3. **The native control already keeps the promises.** Space toggles it, it is
   announced "checked / not checked" with no ARIA at all, and it needs no
   script. `components.html` §5.10 has in fact already drawn exactly these two
   checkboxes — *"Thiết yếu (luôn bật)"* and *"Quảng cáo"* — so this is the
   library's own anticipation of this screen, not a departure from it.

### 4.5 Why "Cần thiết" is `disabled` rather than `aria-disabled`

`components.md` §5.5 prefers `aria-disabled="true"` on an enabled control where
a page needs it to stay reachable. That rule is about **buttons**, and it does
not transfer here. An `aria-disabled` checkbox is still operable in HTML: the
browser will tick it, and only script can refuse — script this design does not
have. A control that moves and then silently snaps back is worse than one that
says plainly it cannot be changed.

So the control carries `disabled`, and **the fact lives in text**: the word
"Luôn bật" beside the label, and the sentence *"Không có nhóm này thì trang
không chạy được, nên không tắt được."* in the description, which is wired to the
control by `aria-describedby`. A browse-mode reader meets all of it; a Tab pass
skips the control, which is correct, because there is nothing there to do.

### 4.6 No dark pattern — the checklist this design is held to

| Pattern | Where this design stands |
| --- | --- |
| Reject smaller, quieter or further away than Accept | All three actions are the same `.btn--secondary`, same size, same row, same order on every surface. Neither is filled while the other is bare. |
| Reject buried one level deeper | It is on the banner itself, beside Accept. |
| A pre-ticked optional category | None. The only ticked box is Cần thiết, and it says "Luôn bật". |
| Accept is one click, reject is five | Both are one click, and both lead to the same confirmation and the same way back (`cookie-settings.html` §2). |
| Focus pre-placed on Accept | No. Focus lands on the banner **region**, never on an action — §8.1. |
| Confirm-shaming copy | None. "Từ chối" is a verb, not an apology. |
| The banner nags on every page until you accept | It appears once, and it is dismissed by **any** of the three actions, including Settings. |

---

## 5. What is collected — and the agreement with M1-08

The privacy policy's section 1 and the account page's "Dữ liệu của tôi" block
must not contradict each other. Both derive from one source, and that is how
they are kept in agreement: **`requirements.md` §4.1 is the list, and neither
page may add to it.**

| In the policy's table | Field in `requirements.md` §4.1 |
| --- | --- |
| Mã người chơi | `players.id` |
| Tên hiển thị | `players.display_name` |
| Ngày tạo | `players.created_at` |
| Điểm theo tuần | `scores.score`, `.game_id`, `.week` |

The remaining `scores` columns — `time_survived_ms`, `seed`, `game_version`,
`created_at` — are named in the paragraph under the table as the technical
values the server uses to check that a score is plausible. They are listed, not
hidden; they are simply not what a player means by "my data".

**Not stored**, stated in the policy as plainly as `requirements.md` §4.1 states
it: no copy of the player's email, no IP address in any table, no telemetry of
any kind.

**The agreement, in one line each way.** The account wireframe
(`wireframes.md` §4.4, `04-account.html`) already summarises the same set as
*"tên hiển thị, ngày tạo, điểm theo tuần"*. The policy's table is that summary
plus the player id, with a reason and a retention column against each row. The
two are consistent today.

> **For M1-08 (the account page), which runs in parallel with this Task.**
> If the account page's "what is stored" block says anything this table does
> not, one of the two is wrong and `requirements.md` §4.1 decides which. Adding
> a field to that list is a Board decision, not a page decision. This Task has
> not seen M1-08's prototype — see §10.

**Retention.** The policy says "until you delete it" and names no fixed clock,
because `requirements.md` §4.1 names none and §8 open question 3 (whether weekly
boards are pruned after N weeks) is still a Board decision. **If the Board
closes question 3, section 3 of the policy is the sentence that changes.**

---

## 6. States

### 6.1 The conflict, raised and not resolved

`wireframes.md` §4.5 and `components.md` §4.4 both say the privacy page is
**static** — no loading state, no empty state, page-level 404/500 only — and
`components.md` §4.4 gives the cookie surface an empty state of "—".
**SOU-7's acceptance criteria require empty, loading and error states for the
privacy page and the settings screen.**

This Task drew what can honestly be drawn, and says plainly which states are
reachable and which are not. Drawing a state and claiming it is live are
different things, and only the first is being done here. This is the same
treatment [`about-terms.md`](about-terms.md) §5.1 gave the identical conflict on
the sibling pages.

### 6.2 The privacy page — all three drawn

| State | Reachable today? | When it becomes reachable | Copy |
| --- | --- | --- | --- |
| **Empty** | **Yes, and currently true.** The legal text comes from OQ-039 and does not exist yet. | Whenever the page exists but the policy is unpublished. | "Chính sách chưa được công bố" · "Chúng tôi đang hoàn thiện văn bản. Mời bạn quay lại sau." · *Về trang chủ* |
| **Loading** | **No.** The page is static, built at build time. | From M4, *if* the document is served from the backend rather than inlined. | hidden "Đang tải chính sách quyền riêng tư…" + skeleton lines |
| **Error** | **Yes.** 404 and 500 at page level. | Now. A wrong URL is enough. | "Lỗi 404" · "Không tìm thấy trang" · *Về trang chủ* |

All three are the canonical M1-04 blocks at `.state--reserve-md`, so switching
between any two moves nothing. The 404 is drawn **in the context of a full
page** — chrome intact — because that is how a reader meets it, and its action
is *Về trang chủ* rather than *Thử lại*: reloading a URL that does not exist
produces the same 404. `about-terms.md` §5.3 records the same exception for the
same template.

### 6.3 The settings screen — four fills, and one state that does not exist

| State | Drawn as | Reachable today? |
| --- | --- | --- |
| **Loading · saving** | the Save button reads "Đang lưu…" and is `disabled`; Cancel is disabled too | the canonical fill from `components.md` §4.4 |
| **Loading · reading the stored choice** | `.state--loading .state--reserve-sm` replacing the category list, `role="status"`, `aria-busy="true"` | **M4+**, when the choice is read from the backend. On a browser-only store the read is instantaneous and this block never paints. |
| **Error · save failed** | `notice--error` with `role="alert"` inside the dialog — the canonical fill from `components.md` §4.4 | yes, from the moment a choice can be saved |
| **Error · could not read the stored choice** | `.state--error .state--reserve-sm` · *Thử lại*, `role="status"` | **M4+**, same condition as the read |
| **Empty** | **does not exist** — see below | — |

**Why there is no empty state, as a claim rather than an omission.** The
category list is defined by the site, not produced by data: it always has
exactly two entries. A settings screen with no categories would be a **defect,
not a state**. And an empty block would replace the very controls the player
opened the screen to use, which is the opposite of what an empty state is for.
The nearest real thing is *no choice saved yet*, and that is the default fill
already drawn in `cookie-settings.html` §3. `components.md` §4.4 independently
records the cookie surface's empty state as "—", and `components.md` §5.3
establishes the convention this follows: *"Where a state does not exist, it is
named and its absence is justified — an omitted state and an impossible state
are different claims, and only the second one is being made here."*

**For the reviewer and the Board.** If the Board wants a literal `.state--empty`
block on the settings screen anyway, it is an addition of one block to two
files and a sentence here; nothing else is affected. If the Board prefers the
upstream reading instead, the privacy page's **loading** state is the one to
delete — it is the only one of the three with no path to being reached today.

### 6.4 Two failure directions, stated on purpose

Every failure in this design falls towards **less** data, never more:

- The stored choice cannot be read → the screen behaves as "Cần thiết only", and
  says so; it does not assume consent.
- A save fails → the previous choice stands, the notice says the previous choice
  stands, and no advertising cookie is set in the meantime.
- Save is `disabled` while the current choice is unknown, because saving a
  state you could not read is a blind overwrite.
- Script is unavailable → the banner does not appear at all (§8.5), and the
  default with no recorded consent is "Cần thiết only".

---

## 7. Contrast

**Neither surface introduces a new foreground/background pair.** That is a
design constraint this Task accepted up front rather than an outcome: nothing in
`consent.css` sets a background, so the category rows and their explanations
inherit `bg` inline and `surface` in the dialog, and both of those pairs were
measured by `DESIGN.md`. Every ratio below is therefore **quoted with its
source**; §10 records that this Task recomputed none.

| Where it is used | Foreground | Background | Light | Dark | Needs | Source | |
| --- | --- | --- | --- | --- | --- | --- | --- |
| Body text, every section heading, the `h1`, the data table | `text` | `bg` | 14.64 | 14.19 | 4.5 | DESIGN.md | PASS |
| Banner text, dialog text, header and footer text, contents card | `text` | `surface` | 15.58 | 12.66 | 4.5 | DESIGN.md | PASS |
| Last-updated line; category explanation **in the page** | `text-muted` | `bg` | 6.25 | 7.65 | 4.5 | DESIGN.md | PASS |
| Category explanation **in the dialog**; "Luôn bật"; footer fine print | `text-muted` | `surface` | 6.65 | 6.82 | 4.5 | DESIGN.md | PASS |
| Link in running text; secondary button label on the page | `primary` | `bg` | 5.69 | 8.66 | 4.5 | DESIGN.md | PASS |
| The three banner actions; "Tìm hiểu thêm"; contents links; dialog Cancel | `primary` | `surface` | 6.06 | 7.72 | 4.5 | DESIGN.md | PASS |
| Hovered link on the page | `primary-hover` | `bg` | 7.98 | 10.25 | 4.5 | components.md §3.4 | PASS |
| Hovered banner action, hovered contents link | `primary-hover` | `surface` | 8.49 | 9.14 | 4.5 | components.md §3.4 | PASS |
| "Lưu lựa chọn" label | `on-primary` | `primary` | 6.11 | 8.15 | 4.5 | DESIGN.md | PASS |
| "Lưu lựa chọn" hovered; the checkbox's own tick on its `accent-color` fill | `on-primary` | `primary-hover` | 8.57 | 9.65 | 4.5 | DESIGN.md | PASS |
| Error state, error notice title and icon, the word "Lỗi" | `error` | `bg` | 6.30 | 7.45 | 4.5 | DESIGN.md | PASS |
| Save-failed notice inside the dialog | `error` | `bg` (the notice supplies its own fill) | 6.30 | 7.45 | 4.5 | DESIGN.md | PASS |
| Success notice after a choice is saved | `success` | `bg` (own fill) | 5.75 | 8.63 | 4.5 | DESIGN.md | PASS |
| Placeholder-text warning notice | `warning` | `bg` (own fill) | 6.08 | 8.09 | 4.5 | DESIGN.md | PASS |
| Info notice ("only two categories") | `primary` | `bg` (own fill) | 5.69 | 8.66 | 4.5 | DESIGN.md | PASS |
| Focus ring on the page | `focus` | `bg` | 5.65 | 8.90 | 3 | DESIGN.md | PASS |
| Focus ring on the banner, the dialog, the contents card | `focus` | `surface` | 6.01 | 7.94 | 3 | DESIGN.md | PASS |
| Banner edge, dialog edge, fieldset edge, checkbox edge, state block edge, secondary button edge | `border-strong` | `surface` | 3.70 | 3.19 | 3 | DESIGN.md | PASS |
| The same edges where they sit on the page | `border-strong` | `bg` | 3.48 | 3.57 | 3 | DESIGN.md | PASS |
| Text on the ad slot's fill | `text` | `sunken` | 13.24 | 15.26 | 4.5 | DESIGN.md | PASS |

**Why the notices supply their own `bg` fill even inside the dialog.**
`DESIGN.md` measures `success` and `warning` against `bg` **only**, and
`components.md` §2.1 makes the rule explicit: `surface` does not carry them,
because those pairs were never measured. The notice shell therefore carries a
`bg` fill in every context. That is what makes the 5.75 / 8.63 and 6.08 / 8.09
above the real ratios rather than approximations of them.

**Two pairs below 3:1, on purpose.**

| Pair | Light | Dark | Where, and why it is exempt |
| --- | --- | --- | --- |
| `border` on `bg` | 1.32 | 1.49 | The skeleton bar — no information, `aria-hidden`, the signal is the live-region text beside it (WCAG 1.4.11 exempts pure decoration). Also the divider between the two category rows: the two blocks are already separated by their labels and their spacing, so the line carries nothing. |
| `surface` on `bg` | 1.06 | 1.12 | Surface separation is carried by the border and the radius, never by the fill. The banner is delimited from the page by its `border-strong` edge (3.48 / 3.57), not by its tone. |

**Unresolved, and not this Task's to fix.** `ads.css` sets `.ad-slot__label` to
`text-muted` on `sunken` — a pair `DESIGN.md` does not publish, against a rule
(`components.md` §2.1) that says `sunken` carries only `text`. This page cannot
restyle `.ad-slot` (`ad-slots.md` §11) and this Task had no measuring tool, so
no ratio is claimed. It is already raised as an M1-02 item in
[`home.md`](home.md) §9.3 and [`about-terms.md`](about-terms.md) §6; this is the
third page carrying the slot and the item is unchanged.

---

## 8. Accessibility

### 8.1 The banner takes focus — but not onto a button

When the banner appears, focus moves to **the banner region itself**
(`tabindex="-1"` on the `<aside>`), never to "Chấp nhận".

- **No answer is pre-selected.** Putting focus on Accept hands the user a reply
  before they have read the question; one reflex Enter is a consent. That is
  precisely the favouritism the equal-weight requirement exists to prevent, and
  it would be worse than a visual imbalance because it is invisible.
- **The whole region is announced.** The region's name is "Lựa chọn cookie"
  (§3), so a screen reader reads the name, then the explanation, then meets the
  three actions in order.

### 8.2 It does not trap the keyboard

The banner is **not modal**. Tab leaves it into the page behind and cycles
normally; the page behind stays readable and operable (`components.md` §5.13).

The **dialog** is modal, and there Tab cycling inside is the *required*
behaviour, not a violation: WCAG 2.1.2 requires the user can **get out** by
keyboard, and Esc, Cancel and the close button all do that
(`components.md` §5.11). Native `<dialog>.showModal()` supplies the cycle, the
inert background, Esc and the top layer, so the product writes no
focus-management code of its own.

### 8.3 Where focus returns

| Action | Focus goes to | Why |
| --- | --- | --- |
| Banner appears | the banner region | announced whole; no answer pre-selected |
| Chấp nhận · Từ chối | wherever focus was before the banner took it; if the banner appeared on first paint there is no "before", so the top of `#main` (`tabindex="-1"`) — the same place the skip link lands | the reader returns to what they were reading; nobody is thrown back to the top of the document |
| Cài đặt (from the banner) | into the dialog, by `showModal()` | native behaviour |
| Esc · Hủy · close | back to the control that opened the dialog | the `<dialog>` contract |
| Lưu (in a dialog opened from the banner) | dialog closes, banner goes too, focus to the top of `#main`, and a `role="status"` `notice--success` confirms | the opener no longer exists, so returning focus to it would return it to nothing |
| Cài đặt cookie (footer link) | in-page navigation to `/privacy#cookies`; if script upgrades the link to open the dialog, focus returns to that footer link | the link is still on the page, so it is the right target |

The confirmation is `role="status"` and not `role="alert"`: the user's own
action **succeeded**, and good news can wait for a pause
(`components.md` §4.3).

### 8.4 It does not obscure the footer, and it does not obscure focus

Two mechanisms, and only the second is CSS this Task writes.

1. **The banner is `sticky`, not `fixed`** — `components.css` §15 chose that,
   and this is the consequence. A sticky box keeps its place in the flow, so at
   the end of the document it sits **after** the footer, not over it: scroll to
   the bottom and every footer link is above it, visible and clickable. A
   `fixed` banner would cover them at every scroll position including the last,
   which is the arrangement "permanently obscures" describes.
2. **`scroll-padding-block-end` on the scroll container** (`consent.css` §1),
   set to the banner's own height at the mobile band, summed from the very
   tokens `components.css` §15 uses to build it — `lg` padding twice, two lines
   of `body-sm`, the `md` gap, three 44 px actions and the two `sm` gaps. Above
   the switch the actions sit in a row and the banner is shorter, so the figure
   is a comfortable floor. When focus moves to something the banner would float
   over mid-page, the browser scrolls it clear. WCAG 2.2 *Focus Not Obscured*.

### 8.5 With no script

The banner **inherently needs script**: with nowhere to record the answer there
is no right to ask the question. So when script is unavailable the banner **does
not appear** — and that is the safe direction, because the default with no
recorded consent is "Cần thiết only" and no ad script runs.

The settings must therefore be reachable without script, and they are: they are
a real **section** of a real **page**, not only a dialog. That is also why the
banner's third action is an **anchor** to `/privacy#cookies` rather than a bare
button — a destination with a real URL should be a link, so it can be opened in
a new tab and bookmarked, and script only upgrades it to open the dialog in
place. All three actions keep the identical `.btn--secondary` geometry, so
equal weight is untouched by the element choice.

### 8.6 Keyboard order on the privacy page

DOM order = tab order = reading order, at both widths. No `order` property and
no positive `tabindex` anywhere.

1. `skip-link` → `#main`, first in the document and **before** the header ad.
2. Wordmark; nav disclosure (below 768 px only); three nav links; two theme
   radios.
3. The seven contents entries, in order 1→7 — `columns: 2` changes the visual
   arrangement, never the DOM.
4. Links in the body, in the order they appear in the sentence.
5. Section 6: the "Quảng cáo" checkbox, then *Lưu lựa chọn*. The "Cần thiết"
   checkbox is `disabled` and is not a tab stop — §4.5 explains why, and the
   word "Luôn bật" says so on screen.
6. Four footer links, each a 44 px target.
7. The banner, last, because it is last in `<body>`.

Every link is underlined by default, so colour is never the only signal. The
current page in the footer carries `aria-current="page"`, so the visible mark
and the announced state cannot disagree.

---

## 9. How to verify

No build, no server, no dependency: open
[`../prototypes/privacy.html`](../prototypes/privacy.html) and
[`../prototypes/cookie-settings.html`](../prototypes/cookie-settings.html).

### 9.1 Five checks a reviewer can run

From the repository root. All five must return **nothing**.

```sh
# 1. No script, no iframe, no font file, no network reference of any kind.
#    (<link rel="stylesheet" href="..."> is relative and deliberately not
#    matched — the pattern catches a remote reference, not a local one.)
grep -nEi '<script|<iframe|@font-face|src=|https?:' \
  docs/design/prototypes/privacy.html \
  docs/design/prototypes/cookie-settings.html

# 2. No colour literal anywhere in the stylesheet.
grep -nE '#[0-9a-fA-F]{3,8}|rgba?\(|hsla?\(' \
  docs/design/prototypes/assets/consent.css

# 3. No inline style attribute.
grep -n 'style="' docs/design/prototypes/privacy.html \
  docs/design/prototypes/cookie-settings.html

# 4. No breakpoint other than M1-01's switch.
grep -nE '@media' docs/design/prototypes/assets/consent.css | grep -v '768px'

# 5. No consent-management platform, tag manager, SDK or ad tag — by name.
grep -nEi 'cookiebot|onetrust|cookieyes|usercentrics|quantcast|iubenda|termly|klaro|osano|gtag|googletag|adsbygoogle|dataLayer|__tcfapi' \
  docs/design/prototypes/privacy.html \
  docs/design/prototypes/cookie-settings.html \
  docs/design/prototypes/assets/consent.css
```

Check 5 is this Task's own addition. Checks 1–4 are the set
[`about-terms.md`](about-terms.md) §8.1 established; check 5 exists because
"no CMP" is an acceptance criterion of *this* Task specifically, and a criterion
that cannot be checked mechanically tends to be checked by assertion.

**Why these commands live here and not in the sheets.** Checks 1 and 5 search
for an `src` attribute, an `http` prefix and a list of vendor names *in those
files*. Printed inside one, the command would match itself and the check would
fail on its own documentation. A markdown file is outside the scanned set, which
is the only place the commands stay true.

### 9.2 Structural checks this Task ran

Mechanically verified in this environment, and a reviewer can repeat them:

| Check | Result |
| --- | --- |
| Checks 1–3 above | return nothing |
| `<div>` / `</div>` balance | 103 / 103 in `privacy.html`, 148 / 148 in `cookie-settings.html` |
| `<section>` / `</section>` balance | 42 / 42 and 19 / 19 |
| Duplicate `id` attributes | none in either file |
| Every `<use href="#…">` resolves to a `<symbol>` defined in the same document | 8 of 8 in each file; no icon is referenced that is not defined, and none is defined that is not used |

### 9.3 By eye

1. **Four renders per surface.** Light/dark × 375/1280 for the page, for the
   banner and for the dialog.
2. **Greyscale the banner.** The three actions must be **indistinguishable from
   each other**. If one stands out, the design has failed the one requirement it
   exists to meet.
3. **Count the categories.** Two. If an "Analytics" switch is on screen, it is a
   defect.
4. **Count the pre-ticked boxes.** One — "Cần thiết" — and it says "Luôn bật".
5. **The measure.** At 1280 px the text column stops at `70ch`, and the headings
   stop there too, not just the paragraphs. Header, ad slot and footer still run
   edge to edge.
6. **Tab through a banner render.** Page content → footer → banner. The banner
   is last in `<body>`, so it never pushes ahead of the page's own content.
7. **Look for a covered footer link.** There is none: the banner is after the
   footer in the flow.
8. **Click a contents entry.** The section heading lands clear of the top edge.
9. **Nothing animates.** Set `prefers-reduced-motion: reduce` and nothing
   changes, because nothing moved.
10. **Measure the zero-shift claim.** In the Console on either sheet:
    `[...document.querySelectorAll('.state')].map(e => e.getBoundingClientRect().height)`
    — within one view, every number must be the same.

---

## 10. What this Task did not verify

Recorded plainly, because an unverified claim presented as verified is worse
than a gap.

- **No rendered visual check.** No browser was available in this environment, so
  **the sheets have not been opened and looked at.** Markup and stylesheet are
  checked statically and structurally (§9.1, §9.2), but *how they look* — and
  in particular whether the two category rows read as two distinct choices at
  375 px in Vietnamese — is for the `qa-engineer` review.
- **No contrast was measured in this Task.** Every ratio in §7 is quoted with
  its source. A measurement was attempted and the environment did not permit
  running the arithmetic, so none is claimed as newly computed. This matches
  `components.md` §8.1 and `about-terms.md` §9.
- **The banner's focus behaviour is specified, not demonstrated.** These sheets
  contain **no script**, by choice (§1), so focus-on-appear, focus-return and
  the dialog's Esc are described in §8 and drawn in their resting states — they
  are not executed. The modal contract itself was demonstrated once, on
  `components.html` §8's real `<dialog>`. Proving *this* banner's behaviour is
  the `qa-engineer` pass and M4's Playwright suite.
- **No screen reader was run.** The roles, names and `aria-describedby` wiring
  in §3 and §8 are assertions a screen-reader pass must confirm, not claims this
  Task is making.
- **M1-08's prototype has not been seen.** The account page runs in parallel
  with this Task. §5 states the agreement and the source that settles it; it
  does not claim to have compared the two files.
- **The legal text is not reviewed, because it is not written.** OQ-039 owns it.
  Nothing in these files is a policy or a commitment.
- **The Vietnamese font check remains open**, as `components.md` §8.3 left it.
- **`google-design-md` and the taste skill were not used.** Neither is enabled
  for this agent, and no uninstalled tool was substituted for either. This
  matches `components.md` §8.4 and `about-terms.md` §9.

---

## 11. Handoff and open items

### 11.1 To `qa-engineer` (reviewer)

The named review scope is the banner's focus handling and the settings
controls. The three judgement calls worth disagreeing with are:

1. **Focus lands on the banner region, not on a button** (§8.1). The
   alternative — focus on the first action — is common and, this design argues,
   wrong.
2. **"Cần thiết" is `disabled`, not `aria-disabled`** (§4.5), which trades a tab
   stop for honesty about what the control can do.
3. **The settings screen has no empty state** (§6.3), which is stated as a claim
   about the screen rather than left as a gap.

### 11.2 For the Board

1. **The settings screen's missing empty state** (§6.3) — the only acceptance
   criterion this Task did not satisfy literally. The fix either way is small
   and is spelled out there.
2. **Retention, open question 3** (§5). Section 3 of the policy is the sentence
   that changes when it closes.
3. **OQ-038, a certified consent tool for EU/UK visitors.** If one is adopted it
   replaces the *mechanism*, not the layout: the structure, the equal weighting,
   the two categories and "no script before consent" hold either way
   (`wireframes.md` §4.6). **A vendor banner that pre-ticks a category, or that
   adds an analytics toggle this site has no use for, is not an acceptable
   substitute for this design.**

### 11.3 To M2 (the stack)

- §3 is the markup to lift, including the `<form>` the sheets omit. The `id`
  slugs are yours, with one exception: **`#cookies` is fixed**, because
  `requirements.md` §2 publishes `/privacy#cookies` as the settings address.
- **The long-form template's CSS now exists in two files.** `consent.css` §2
  restates `home.css` §3's four rules, from the same tokens, because the
  template lives in another Task's write set and linking it would couple the
  privacy policy to the home page's stylesheet. The two files are never loaded
  together. When the stack lands, the template belongs in one place — that
  consolidation is M2's, and it is a move, not a redesign.
- The banner needs exactly three behaviours in script: record the answer, hide
  the banner, move focus per §8.3. Nothing else.

### 11.4 To M4 (tests)

Every claim in §8 is a Playwright assertion: focus lands on the banner region
and not on an action; Tab leaves the banner; Esc closes the dialog and returns
focus to its opener; the footer's last link is reachable and not covered with
the banner present; no network request is made before a choice is recorded; and
§9.3's zero-shift check over `.state` heights.

### 11.5 To whoever writes OQ-039

The template holds **seven** numbered sections, and the contents list is sized
to that count. Longer sections are fine; an eighth top-level section means the
contents list grows. **Section 6 is not prose** — it is the settings screen, and
its text must not be replaced with a paragraph that merely describes cookies.
And section 1's table is the one piece of this page that is not yours to
rewrite: it is `requirements.md` §4.1, and it must keep agreeing with the
account page.

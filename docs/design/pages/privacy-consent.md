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
  The one exception is named in §5: the *table of what is stored*, the email
  paragraph beside it and the *rights* section are not placeholder — they are
  `requirements.md` §4.1 and the Board's decision of 2026-11-05 in the player's
  words, and they are load-bearing.
- **Any consent-management platform, SDK, tag, pixel, script or third-party
  request.** This is a design, not an integration. There is no script at all in
  either prototype, inline or otherwise — see §9.
- **A new shared component**, colour, type step, spacing step, radius or
  breakpoint. Also no thirteenth icon: `components.md` §5.1 fixes the set at
  twelve and there is deliberately no "cookie" glyph.
- **Any framework, build step, runtime dependency or package manifest.**
- **The account page.** It is M1-08's; it has since merged, and it is still not
  edited from here. §5 states the agreement the two pages must keep, and §5.1
  shows the two texts side by side.

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
| After Save | the section stays; the confirmation appears **under the button** and focus does not move | the dialog closes; the confirmation greets the reader at the top of `main`, where focus lands |

A page section does not need a Cancel, because leaving it is already the way
out. A dialog does, because it has taken over the screen. And the confirmation
goes where the reader is, which is a different place in each — §6.3.

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
      <!-- Present and EMPTY at rest. A role="status" announces what is put
           INTO it; one that is itself inserted may be announced late or not
           at all. After a successful save the notice below is placed here,
           and focus does not move. -->
      <div class="consent-form__status" role="status"></div>
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
page may add to it** — *except* where the Board has decided otherwise in the
open, which it did on 2026-11-05 for the email. §5.1 below is that one
exception, named rather than slipped in.

| In the policy's table | Field in `requirements.md` §4.1 — or the decision that added it |
| --- | --- |
| Mã người chơi | `players.id` |
| Tên hiển thị | `players.display_name` |
| Ngày tạo | `players.created_at` |
| Điểm theo tuần | `scores.score`, `.game_id`, `.week` |
| Địa chỉ email — chỉ khi bạn tự liên kết | **no `players` column.** The sign-in system holds it. Added by the Board decision of 2026-11-05 — §5.1 |

The remaining `scores` columns — `time_survived_ms`, `seed`, `game_version`,
`created_at` — are named in the paragraph under the table as the technical
values the server uses to check that a score is plausible. They are listed, not
hidden; they are simply not what a player means by "my data".

**Not stored**, stated in the policy as plainly as `requirements.md` §4.1 states
it: no IP address in any table, no telemetry of any kind. The third item on that
list used to be the email, and it is not any more — §5.1.

**The agreement, in one line each way.** The account wireframe
(`wireframes.md` §4.4, `04-account.html`) already summarises the same set as
*"tên hiển thị, ngày tạo, điểm theo tuần"*. The policy's table is that summary
plus the player id, with a reason and a retention column against each row. The
two are consistent today.

> **For M1-08 (the account page).** Written while the two ran in parallel:
> *if the account page's "what is stored" block says anything this table does
> not, one of the two is wrong and `requirements.md` §4.1 decides which. Adding
> a field to that list is a Board decision, not a page decision.*
>
> **M1-08 has since merged** (PR #12, `main` at 9e56dd0) and its text was read
> for this round. The two agree on all four shared rows. The fifth row — the
> email — is exactly the case the paragraph above reserved for the Board, and
> the Board decided it: §5.1. The account page is not changed by this Task and
> does not need to be, for the reason §5.1 gives; whether it *wants* the
> sign-in sentence too is its owner's call, raised in §11.2.

**Retention.** The policy says "until you delete it" and names no fixed clock,
because `requirements.md` §4.1 names none and §8 open question 3 (whether weekly
boards are pruned after N weeks) is still a Board decision. **If the Board
closes question 3, section 3 of the policy is the sentence that changes.**

### 5.1 The email — the Board decided it, and this is the sentence it bought

**Board Operator, 2026-11-05, on SOU-6 ("Đồng ý cả hai"), recorded on this
Task:** an email is stored **only if the player links one**, **only for
sign-in**, is **never shown publicly**, and is **deleted with the player's
data**. The broad form — "we do not keep your email" — is explicitly not to be
written.

That decision closes [`account.md`](account.md) §14.1, which raised the problem
and refused to settle it alone: `requirements.md` §4.1 still carries *"No copy
of the player's email. The auth provider holds it, and only if the player links
an email"*, a sentence written before **ADR-021** made the studio's own Laravel
backend the auth provider. Read in 2026 it promises more than the facts support.
**`requirements.md` is not in this Task's write set**, so its line is untouched
here and is handed on in §11.2; the Board's decision is the governing text and
this page follows it, not the stale sentence.

What section 1 of the policy now says, in full:

> **Về địa chỉ email.** Chúng tôi chỉ lưu email nếu chính bạn liên kết một địa
> chỉ, và chỉ dùng nó để bạn đăng nhập. Nó nằm ở hệ thống đăng nhập chứ không
> nằm trong hồ sơ của bạn, không bao giờ hiển thị công khai — bảng xếp hạng và
> hồ sơ không in email ra — và nó bị xóa cùng với dữ liệu của bạn khi bạn xóa.
> Không liên kết thì chúng tôi không có địa chỉ email nào của bạn.

It is also a **row in the table**, not only a paragraph, because the table's
caption claims to list everything stored about a player and a conditional field
left out of it would make that caption false. The row names the condition in the
cell — *"Địa chỉ email — chỉ khi bạn tự liên kết"* — so the table cannot be read
as saying every player has one.

**Why this does not contradict the account page**, which is merged and approved.
`account.html` says *"Hồ sơ của bạn không có bản sao địa chỉ email nào"* — a
claim about the **profile record**, and still true: the policy says in the same
breath that the address lives in the sign-in system and that the profile and the
boards never print it. One page makes the narrow claim about itself, the other
states where the address actually is. Neither makes the broad claim the Board
forbade. [`account.md`](account.md) §6.3 ("No email address is printed, even
when linked") is the same fact from the other side.

**The cross-reference sentence, and why it is worded the way it is.** The last
line of policy section 1 points the reader at the account page. Before the
Board's decision the two lists were identical and the sentence said so —
*"Trang Tài khoản liệt kê đúng những mục này"*. Adding the email row made that
claim false: the policy's table now has five rows and the account page's has
four, because the email is not a profile field. A player who followed the link
to check would find a row missing and would be right to distrust both pages. So
the sentence no longer claims the two lists are the same one:

> Trang **Tài khoản** liệt kê những trường trong hồ sơ của bạn — bốn dòng đầu
> của bảng trên. Email không nằm trong bảng đó vì nó ở hệ thống đăng nhập, như
> đoạn trên đã nói.

It states the relationship that is actually true (the account page is the
profile; the policy's table is the profile **plus** the address the sign-in
system holds), and it gives the reason in the same breath, so the missing row
reads as the design's answer rather than as a gap. **Anyone adding a row to
either table must check this sentence**: it is the only place in the four
renders that counts rows across two pages, and the acceptance criterion it
serves is "the privacy policy's statement of what is collected matches the
account page — the two must not contradict each other".

### 5.2 The right to a copy, beside the right to delete

**Same Board decision:** the product keeps *"Tải bản sao dữ liệu của tôi"* on the
account page, and the policy's rights section must list the right to **get a
copy** of one's own data next to the right to delete it.

That closes [`account.md`](account.md) §14.7, which drew the button and asked
whether the product promises an export at all. It also **supersedes
`wireframes.md` §4.5**, which routed this section to *delete* only — the one
place where this page now deliberately says more than an inherited design
document does, by a decision made above both.

Section 5 of the policy, in full:

> Bạn xem được mọi thứ đã lưu về mình, **tải một bản sao dữ liệu của mình**, sửa
> tên hiển thị, và xóa tất cả. Bốn việc đó nằm ở Tài khoản → Dữ liệu của tôi:
> nút *Tải bản sao dữ liệu của tôi* đứng ngay cạnh nút xóa, nên muốn giữ một bản
> trước khi xóa thì không phải đi hỏi ai.

"Ba việc" became "bốn việc" and the copy is named with the **same label the
button carries** on the account page, so a player who reads the right goes
looking for a control with exactly that wording and finds it. What M4 inherits
from this is in §11.4.

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
| **Saved · inline (the page)** | `notice--success` inside `.consent-form__status`, the `role="status"` region that sits **after** the Save button inside section 6 and is present-but-empty at rest | yes, from the moment a choice can be saved. Drawn in `privacy.html` §1, dark 375 (ads on) and light 1280 (essential only) |
| **Saved · in the dialog** | the dialog closes, the banner goes with it, and a `role="status"` `notice--success` greets the reader at the top of `main` | yes. Drawn in `cookie-settings.html` §2, in all three of its renders — light 375 after *Từ chối*, dark 375 after *Chấp nhận*, light 1280 after *Từ chối* |
| **Loading · saving** | the Save button reads "Đang lưu…" and is `disabled`; Cancel is disabled too | the canonical fill from `components.md` §4.4 |
| **Loading · reading the stored choice** | `.state--loading .state--reserve-sm` replacing the category list, `role="status"`, `aria-busy="true"` | **M4+**, when the choice is read from the backend. On a browser-only store the read is instantaneous and this block never paints. |
| **Error · save failed** | `notice--error` with `role="alert"` inside the dialog — the canonical fill from `components.md` §4.4 | yes, from the moment a choice can be saved |
| **Error · could not read the stored choice** | `.state--error .state--reserve-sm` · *Thử lại*, `role="status"` | **M4+**, same condition as the read |
| **Empty** | **does not exist** — see below | — |

**The two "saved" rows are one state in two places, and the placement is the
difference.** In the dialog, the surface the player was looking at **ceases to
exist** on Save, so the confirmation belongs to the page left behind, at the top
of `main`, which is also where focus goes. Inline, nothing closes and nobody
moves: the reader is mid-document at section 6 with the rest of the policy below
them, and a notice at the top of `main` would appear somewhere they are not
looking — above the fold they left, possibly off-screen. So the inline
confirmation is drawn **directly under the Save button**, the next thing in both
the visual and the reading order, and focus is **not** moved (§8.3). Reusing the
dialog's answer here would have been the wrong answer wearing the right class
name.

**Three consequences worth stating, because they are what make it work.**

1. **The region exists at rest.** `role="status"` announces what is inserted
   *into* a live region; a status element that is itself inserted may be
   announced late or not at all. So the empty `<div class="consent-form__status"
   role="status"></div>` is part of section 6's resting markup, and only the
   `.notice` inside it comes and goes. Two of the four renders draw it empty for
   exactly that reason.
2. **Nothing above the button moves.** The notice is a later sibling of
   `.consent-form__actions`, and the margin lives on the notice rather than on
   the container (`consent.css` §3), so the resting region has no height at all.
   The page grows downward by the height of the notice; the button under the
   player's finger or focus ring does not shift.
3. **It is not a tab stop.** The region takes no `tabindex` and holds no
   control, so the keyboard order of section 6 is identical before and after a
   save (`privacy.html` §3.2).

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
| Success notice after a choice is saved — at the top of `main` after the dialog closes, and inside `.consent-form__status` in the inline section | `success` | `bg` (own fill) | 5.75 | 8.63 | 4.5 | DESIGN.md | PASS |
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
| **Lưu (in the inline section at `/privacy#cookies`)** | **nowhere — focus stays on the "Lưu lựa chọn" button it is already on.** The confirmation is announced by the `role="status"` region directly below that button, and nothing above the button moves | nothing closed and nothing was replaced, so there is nobody to move. Sending focus into the notice would take a keyboard user *past* the button they must be able to press again after changing their mind; sending it to the top of `main` would throw a reader who is at section 6 back to the top of the document |
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

Counted as **occurrences**, not as matching lines: these sheets put three or
four tags on one line, so a line count would hide exactly the kind of mistake
this check exists to catch.

| Check | Result |
| --- | --- |
| Checks 1–5 above | return nothing |
| `<div` / `</div>` balance | **111 / 111** in `privacy.html` (103 / 103 before the review round; the eight new ones are the four `.consent-form__status` regions and the two success notices), 148 / 148 in `cookie-settings.html` |
| `<section` / `</section>` balance | 42 / 42 and 19 / 19 — unchanged, because the review round added no `<section>` |
| Duplicate `id` attributes | none in either file. **No `id` was added, removed or renamed in the review round**, so this is the first round's result, undisturbed |
| Every `<use href="#…">` resolves to a `<symbol>` defined in the same document | **9 of 9** in `privacy.html` (`i-success` joined the copied set when the inline confirmation was drawn), 8 of 8 in `cookie-settings.html`. No icon is referenced that is not defined, and none is defined that is not used — the unused-symbol direction matters, because a copied icon set drifts by accumulating icons nobody draws |

**How the counts were taken, so a reviewer can reproduce them rather than trust
them.** No `grep -c` here: that counts matching *lines*. Each pattern is turned
into a newline and the lines are counted, and the file's own line count is
subtracted —
`sed 's/<div/\n/g' FILE | wc -l` minus `wc -l FILE`. The closing tags are
counted the same way on the token `div` and the openers subtracted; in
`cookie-settings.html` that total is one higher than twice the openers, and the
extra one is the escaped `&lt;div class="dialog"&gt;` inside the prose of §3,
which is text, not a tag.

### 9.3 By eye

1. **Four renders per surface.** Light/dark × 375/1280 for the page, for the
   banner and for the dialog.
2. **Greyscale the banner.** The three actions must be **indistinguishable from
   each other**. If one stands out, the design has failed the one requirement it
   exists to meet.
3. **Count the categories.** Two. If an "Analytics" switch is on screen, it is a
   defect.
4. **Count the pre-ticked boxes.** One — "Cần thiết" — and it says "Luôn bật".
5. **Press Save in your head, inline.** On `privacy.html`, put the light 375
   render beside the dark 375 one: everything from the Save button upward must
   sit at the same height in both, and the confirmation must be the only thing
   that differs, below the button. If anything above the button moves, the
   placement argument in §6.3 is wrong and the notice is in the wrong place.
6. **Read the two confirmations against the two checkbox states.** Dark 375 has
   "Quảng cáo" ticked and says *cần thiết và quảng cáo*; light 1280 has it
   unticked and says *chỉ cookie cần thiết*. A confirmation that does not match
   the boxes above it is worse than none.
7. **The measure.** At 1280 px the text column stops at `70ch`, and the headings
   stop there too, not just the paragraphs. Header, ad slot and footer still run
   edge to edge.
8. **Tab through a banner render.** Page content → footer → banner. The banner
   is last in `<body>`, so it never pushes ahead of the page's own content.
9. **Look for a covered footer link.** There is none: the banner is after the
   footer in the flow.
10. **Click a contents entry.** The section heading lands clear of the top edge.
11. **Nothing animates.** Set `prefers-reduced-motion: reduce` and nothing
    changes, because nothing moved.
12. **Measure the zero-shift claim.** In the Console on either sheet:
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
- **M1-08 has now been read, not opened.** The account page merged while this
  Task was in review (`main` at 9e56dd0), and for the review round its text was
  read directly — `account.md` §8.1, §6.3, §14.1 and §14.7, and the data block
  in `account.html`. §5, §5.1 and §5.2 compare the two in words. The prototype
  still has **not been rendered**, for the same reason nothing else here has:
  there is no browser in this environment.
- **The inline confirmation is drawn, not exercised.** `.consent-form__status`
  is shown filled in two renders and empty in two, and the live-region claim —
  that a `role="status"` present before the insertion announces it — is a
  statement about how assistive technology behaves, **not something this Task
  observed**. It is a screen-reader assertion for the `qa-engineer` pass and a
  Playwright assertion for M4 (§11.4). The same goes for the "nothing above the
  button moves" claim: it follows from the markup order and from where the
  margin is written, and it has not been measured on a rendered page.
- **The legal text is not reviewed, because it is not written.** OQ-039 owns it.
  Nothing in these files is a policy or a commitment.
- **The Vietnamese font check remains open**, as `components.md` §8.3 left it.
- **`google-design-md` and the taste skill were not used.** Neither is enabled
  for this agent, and no uninstalled tool was substituted for either. This
  matches `components.md` §8.4 and `about-terms.md` §9.

---

## 11. Handoff and open items

### 11.0 What the review round changed

`qa-engineer` reviewed PR #13 and requested changes; the Board Operator
separately recorded two decisions this page had to match. Four things changed in
the first round, and a fifth in the second — nothing else did.

| Came from | What changed | Where |
| --- | --- | --- |
| QA finding 1 — the inline settings screen had no designed outcome for Save | `.consent-form__status`, a `role="status"` region present-but-empty inside section 6, filled with a `notice--success` under the Save button; focus deliberately does not move | `privacy.html` (4 renders), `consent.css` §3, §3 markup, §6.3, §8.3, §9.3 |
| QA finding 2 — two captions described an ad slot that is not in `cookie-settings.html` | both captions rewritten to say what their render actually shows. The same false claim was also sitting in the **"Quảng cáo" category description** in both files — "the slot keeps its place" is not true below 768 px, where `ad-slots.md` gives a non-play page no slot at all — so that sentence became "no ad is loaded and the layout does not change", which is true at both widths | `cookie-settings.html`, `privacy.html` |
| Board decision 1 — the email | §1 gains an email row and a paragraph; the broad "we do not keep your email" is gone | `privacy.html` (4 renders), §5.1 |
| Board decision 2 — the data export | §5 "Quyền của bạn" lists the right to a copy beside the right to delete, under the account page's own button label | `privacy.html` (4 renders), §5.2 |
| QA second round — the closing sentence of policy §1 still claimed the Account page lists *exactly* the rows above, which decision 1 made false (five rows here, four there) | the sentence now says the Account page lists the **profile fields** — the first four rows — and gives the reason the email is not among them; the wording and the rule for changing it are recorded in §5.1 | `privacy.html` (4 renders), §5.1 |

One piece of bookkeeping came with them: §9.2's structural figures were
**recounted from scratch** and the counting method is now written down beside
them. The first round's figures were right; the method had not been recorded,
and a count nobody can reproduce is a claim rather than a check.

The QA review also **agreed with** the three judgement calls listed in §11.1 and
with the no-empty-state argument in §6.3. Neither of those is reopened here, and
no other design decision in this document changed.

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
2. **`requirements.md` §4.1 still says "No copy of the player's email."** The
   decision of 2026-11-05 governs this page and §5.1 follows it, but the
   requirements file is **not in this Task's write set** and was not touched.
   Until its owner updates that line, the repository holds two sentences about
   the email and only one of them is current — and the stale one is in the file
   every later Task treats as the source. **Ask:** have the owner of
   `requirements.md` replace that bullet with the Board's wording, or record
   that the file is superseded on this point. `account.md` §14.1 and §14.7 can
   both be closed at the same time; their question has been answered.
3. **Retention, open question 3** (§5). Section 3 of the policy is the sentence
   that changes when it closes.
4. **OQ-038, a certified consent tool for EU/UK visitors.** If one is adopted it
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

Three more arrive with the review round:

- **Saving inline does not move focus.** After Save in section 6,
  `document.activeElement` is still the Save button, and the notice inside
  `.consent-form__status` is present. A test that only asserts the notice would
  pass on a design that yanked focus to it.
- **Nothing above the button moves.** Record the Save button's
  `getBoundingClientRect().top` before and after the save: the same number.
- **The export endpoint behind "Tải bản sao dữ liệu của tôi"** (§5.2) is now a
  promise the policy makes, so it is in scope — and `AGENTS.md` §4 marks it
  security-sensitive, because an endpoint that hands a player a file of their
  own data is an endpoint that can hand it to somebody else. Deletion must
  remove the linked email with everything else (§5.1), and that is a test, not
  a sentence.

### 11.5 To whoever writes OQ-039

The template holds **seven** numbered sections, and the contents list is sized
to that count. Longer sections are fine; an eighth top-level section means the
contents list grows. **Section 6 is not prose** — it is the settings screen, and
its text must not be replaced with a paragraph that merely describes cookies.
And section 1's table is the one piece of this page that is not yours to
rewrite: it is `requirements.md` §4.1 plus the Board's email decision (§5.1),
and it must keep agreeing with the account page.

**Two sentences in particular are decisions, not drafting.** The email
paragraph in section 1 (§5.1) and the right to a copy in section 5 (§5.2) are
the Board's words, made on 2026-11-05. Legal drafting may make them more
precise; it may not make them broader, narrower or vaguer. In particular "we do
not keep your email", in any phrasing, is the one sentence this page is
forbidden to contain.

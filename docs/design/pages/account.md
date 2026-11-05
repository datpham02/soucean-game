# Account and profile page — `/account`

**Status:** Normative for the account page's layout, forms, linking states and
delete flow. Milestone M1 (Design), Task SOU-6 (M1-08). Owner: `ui-designer`,
through `project-manager`. Last updated: 2026-11-05.

The mockup is [`../prototypes/account.html`](../prototypes/account.html) and is
static HTML: open it in a browser and it works. No server, no build step, no
dependency, no network request.

This is the page where the studio's **"minimal data" promise stops being a
sentence in a policy and becomes a thing the player can see and act on**. It
shows exactly what is held, in the player's own words, and it puts the button
that deletes all of it one action away from the top of the page.

It is downstream of five documents and contradicts none of them:

| Source | What it fixes | What this document does |
| --- | --- | --- |
| [`../../requirements.md`](../../requirements.md) §2, §3, §4, §5 | the page's content, player identity, the complete field list, the allowed ad positions | builds the page to them, and prints §4.1's field list on the page itself |
| [`../DESIGN.md`](../DESIGN.md) (M1-01) | colour, type, spacing, shape, motion, the breakpoints, the measured contrast table | designs inside the measured pairs, inventing no value |
| [`../ad-slots.md`](../ad-slots.md) (M1-02) | the slot inventory, the sizes, the bands, no-sticky | places the one slot this page has, exactly as specified |
| [`../wireframes.md`](../wireframes.md) (M1-03) §4.4 | the regions, the reading order, where each state goes, "delete is first-class" | makes those regions high-fidelity; moves none, drops none, reorders none |
| [`../components.md`](../components.md) (M1-04) | every component on the page, the three state patterns, the destructive-button decision | reuses them; **adds no shared component and restyles none** |

---

## 1. Scope — and what is deliberately absent

In scope: the page's composition at both design widths, in both themes; the
display-name form with its validation and its two kinds of error; both linking
methods in both their states including unlinking; the whole delete flow from
entry point to result; the honest summary of what the account holds; the
signed-out, loading and error states; the one ad slot; the reading order and
focus management; and the contrast of every pair used.

**Deliberately absent, and a defect if a later Task reads one into this page:**

- **Any auth provider, SDK, script, button widget or third-party request.**
  SOU-6 says so in as many words: *design the screens only*. "Liên kết Google"
  is a `<button>` with a text label and nothing behind it. There is no Google
  script, no OAuth redirect, no provider button asset, no branded mark, and no
  licence to record — which is also why there is no Google logo anywhere on the
  page.
- **The backend.** There is no backend before M4. Nothing here designs a
  session, a token, a magic-link TTL, a moderation service, a deletion job or a
  rate limit. Every value in the mockup is placeholder data.
- **The identity mechanism.** `requirements.md` §3 fixes the *behaviour* —
  anonymous first, linking keeps the same player id — and this page is designed
  to it. How the id is issued is M4's and `AGENTS.md` §4's.
- **The final routes.** `requirements.md` §8 open question 4 leaves route
  strings to M2. Nothing here depends on the exact string.
- **Any ad network, ad tag, pixel or tracker.** The one slot is an empty
  placeholder box.
- **A new shared component.** Nothing is added to `components.css` and nothing
  in it is restyled. §14 names the compositions this page makes on top of
  library components and reports them rather than promoting them.
- **A new colour, type step, spacing step, radius or breakpoint.** This page
  introduces none, and §11 shows that every pair it uses was already measured.
- **The privacy policy's legal text.** That is M1-09's. §8 and §14.1 state
  exactly what this page claims, what it refuses to claim, and the one sentence
  the two documents must agree on before either ships.
- **Any framework, build step, runtime dependency, package manifest, analytics
  or font file.**

---

## 2. The page, in one paragraph

Title, then three links straight to the three sections, then the three sections
themselves: **Hồ sơ**, **Đăng nhập**, **Dữ liệu của tôi**. The page is one
column at both widths, capped at the reading measure, because every region on
it is a form or a short piece of prose and a text input stretched across 1200 px
is an input whose label and whose error message are a monitor apart. The page
has exactly one filled button — **Lưu** — and "Xóa dữ liệu của tôi" is
deliberately not it: a destructive action is *dangerous*, not *important*, and
making it the loudest thing on the page invites the mis-click the confirmation
exists to prevent. The data summary sits in the open, as an ordinary table on
the page background, because the point of this page is that there is nothing to
hide behind a disclosure.

---

## 3. The real page markup

The mockup sheet stacks several renders in one document, so its chrome is
demoted out of the landmark roles and its headings sit lower (`components.md`
§2.3). **The real page uses the elements and the real heading levels.** This is
the shape for M2 to lift; it is not a demotion to be undone.

```html
<body>
  <a class="skip-link" href="#main">Tới nội dung chính</a>

  <header class="site-header">              <!-- banner -->
    <div class="shell site-header__bar">…wordmark, theme toggle…</div>
    <nav class="shell" aria-label="Chính">…</nav>   <!-- navigation -->
    <div class="shell">…header ad slot, AFTER the skip link…</div>
  </header>

  <main class="shell main" id="main" tabindex="-1">
    <h1 class="ac-title">Tài khoản</h1>
    <p class="ac-lede ac-measure">…one sentence…</p>

    <nav class="ac-toc" aria-label="Mục trong trang">
      <ul class="ac-toc__list">
        <li><a class="link" href="#ho-so">Hồ sơ</a></li>
        <li><a class="link" href="#dang-nhap">Đăng nhập</a></li>
        <li><a class="link" href="#du-lieu">Dữ liệu của tôi</a></li>
      </ul>
    </nav>

    <section class="ac-section" id="ho-so" aria-labelledby="ho-so-h">
      <h2 class="ac-section__title" id="ho-so-h">Hồ sơ</h2>
      <!-- .error-summary goes HERE, above the form, when a submit fails -->
      <form class="ac-profile__form" method="post" action="…">
        <div class="field ac-profile__field">
          <label class="field__label" for="display-name">
            Tên hiển thị <span class="field__opt">· bắt buộc</span>
          </label>
          <input id="display-name" name="display_name" type="text"
                 autocomplete="nickname" aria-describedby="display-name-help">
          <!-- when invalid: aria-invalid="true" and the error id added to
               aria-describedby, error element BEFORE the help element -->
          <p class="field__help" id="display-name-help">3–20 ký tự. …</p>
        </div>
        <div class="ac-profile__actions">
          <button class="btn btn--primary" type="submit">Lưu</button>
        </div>
      </form>
    </section>

    <section class="ac-section" id="dang-nhap" aria-labelledby="dang-nhap-h">
      <h2 class="ac-section__title" id="dang-nhap-h">Đăng nhập</h2>
      <p class="ac-identity ac-measure">…identity STATE, never a player id…</p>
      <div class="ac-links">
        <section class="card ac-link" aria-labelledby="g-h">
          <div class="ac-link__head">
            <h3 class="ac-link__title" id="g-h">Google</h3>
            <span class="badge">Chưa liên kết</span>
          </div>
          <p class="ac-link__note">…</p>
          <div class="ac-link__actions">
            <button class="btn btn--secondary" type="button">Liên kết Google</button>
          </div>
        </section>
        <section class="card ac-link" aria-labelledby="e-h">…Email…</section>
      </div>
      <div class="ac-signout">
        <button class="btn btn--secondary" type="button">Đăng xuất</button>
      </div>
    </section>

    <section class="ac-section" id="du-lieu" aria-labelledby="du-lieu-h">
      <h2 class="ac-section__title" id="du-lieu-h">Dữ liệu của tôi</h2>
      <table class="ac-what">…what is held, and why…</table>
      <p class="ac-what__note">…the one retention rule…</p>
      <ul class="ac-notstored">…what is NOT held…</ul>
      <div class="ac-data__actions">
        <a class="btn btn--secondary" href="…">Tải bản sao dữ liệu của tôi</a>
        <button class="btn btn--destructive" type="button"
                aria-haspopup="dialog">…Xóa dữ liệu của tôi</button>
      </div>
    </section>
  </main>

  <footer class="site-footer">…</footer>      <!-- contentinfo -->

  <dialog class="dialog" id="confirm-delete" aria-labelledby="confirm-delete-h">
    <div class="dialog__head">
      <h2 class="dialog__title" id="confirm-delete-h">Xóa dữ liệu của tôi?</h2>
      <button class="btn btn--quiet btn--icon" type="button">…Đóng…</button>
    </div>
    <div class="dialog__body">…the list, then the irreversibility line…</div>
    <div class="dialog__actions">
      <button class="btn btn--quiet" type="button">Hủy</button>
      <button class="btn btn--destructive" type="button">…Xóa vĩnh viễn</button>
    </div>
  </dialog>

  <aside class="consent" aria-label="Cookie">…</aside>  <!-- last in body -->
</body>
```

Five things in that block are **contracts, not styling**, and a later Task that
changes one of them is changing the design:

1. **Exactly one `<h1>`**, then `<h2>` sections, then `<h3>` inside them. The
   dialog's title is an `<h2>` because a dialog starts its own outline
   (`components.md` §2.3).
2. **The in-page contents is directly under the `<h1>`**, and "Dữ liệu của tôi"
   is one of its three entries. That is one of the three ways `wireframes.md`
   §4.4 makes deletion first-class.
3. **The three sections are siblings.** "Dữ liệu của tôi" is not a sub-item of
   settings and not inside a collapsed "danger zone".
4. **The error summary is a sibling above the form**, not inside it, so it can
   take focus before the first field.
5. **The dialog is a real `<dialog>`**, opened with `showModal()`. §7.4 is why
   that matters and what it buys for free.

---

## 4. The views this sheet draws

| § on the sheet | What it draws | Renders |
| --- | --- | --- |
| 1 | the whole page, signed in and nothing linked, with the dialog drawn in place | 4 — light/dark × 375/1280 |
| 2 | the display-name field: view, editing, loading, saving, saved, too short, moderation refusal, submit failure | 4 |
| 3 | linking: unlinked, linked, unlinking the last method, sending, sent, invalid address, provider failure | 4 |
| 4 | the delete flow: entry point, confirmation, in progress, failure, result | 4 |
| 5 | signed out, and the profile-load failure | 4 |
| 6 | the field-by-field mapping from the page's table to `requirements.md` §4.1 | — (a table, not a render) |
| 7–10 | ad slots, keyboard, contrast, how to read the sheet | — |

Every group is drawn at **both** design widths in **both** themes. That is 20
renders of the page or its parts.

---

## 5. Display name — view, edit, validate

### 5.1 One field, one label, one visible rule

A real `<label>`, always, never placeholder-only. The required mark is the
**word** "bắt buộc" in a `.field__opt` span, not an asterisk — an asterisk alone
is not a label and is read as "star" by some screen readers (`components.md`
§5.10).

The help text states the rule *before* the player can break it: **"3–20 ký tự.
Tên được kiểm duyệt trước khi hiện trên bảng xếp hạng."** Both halves earn their
place. The length rule is the one a player can check themselves; the moderation
sentence is there so that a later refusal is not a surprise, because a rule a
player only learns by being refused is a rule the page hid from them.

**There is no `maxlength`.** A hard `maxlength` silently truncates a paste, so
the player loses characters without ever being told which rule they hit. The
length is validated and *explained* instead. **There is also no live character
counter**: it would be a second live region on a page that already has three,
and it announces on every keystroke, which is noise for the one user it is
supposed to help.

### 5.2 Two kinds of failure, two different treatments

This is the distinction `components.md` §4.4 draws for this region, and it is
worth stating why the two are not the same thing.

| Failure | Treatment | Why |
| --- | --- | --- |
| The value is wrong — too short, too long, refused by moderation | `.field__error` **under the control**, `aria-invalid="true"` on the control, the error's id added to `aria-describedby`. No live region. | The error belongs to that field and the player arrives at it by moving to the field, which is when it is useful. It is not new by then, so announcing it live would be announcing old news. |
| The save did not happen — the network, the server | `.error-summary` **above the form**, `role="alert"`, `tabindex="-1"`, and focus moved to it. One link per bad field. | Nothing about the value is wrong, so marking the field invalid would be a lie. The player just acted and is waiting, so `alert` interrupting is correct — that *is* the answer to what they asked. Moving focus puts a keyboard user at the explanation instead of leaving them on the submit button. |

In the markup the error element comes **before** the help element and both are
named in `aria-describedby`, in that order, so a screen reader reads the problem
first and the rule second.

**What the summary's link points at, on the sheet and on the real page.** On the
real page there is exactly one display-name field, so "one link per bad field"
has exactly one target and §3's markup is unambiguous. The sheet is a different
object: it draws the same form eight times, once per state, so the summary that
illustrates the failed-save state sits in a document where four other copies of
its own field already exist. Each render's summary therefore links to that
render's **first** field (`#n1`, `#n7`, `#n13`, `#n16`) — the link resolves, it
stays inside its own render, and it does not invent a ninth copy of the form to
own. **It is a property of the sheet, not of the design**, and M2 should read
§5.2's rule rather than copy the sheet's `href`.

### 5.3 Saving, and the `disabled` trap

While a save is in flight the button reads **"Đang lưu…"** and carries
`aria-disabled="true"`. It does **not** carry `disabled`.

`components.md` §4.4 says the button "is disabled" while saving, and
`components.md` §5.5 supplies the mechanism for exactly this case: *"where a
page needs the control to stay reachable while unavailable, use
`aria-disabled="true"` on an enabled button and explain why in the label."* This
page needs it, because the focus is **on that button** at the moment it becomes
unavailable — the player just pressed it. A real `disabled` attribute removes
the element from the tab order, which drops focus to `<body>` and loses a
keyboard user's place at the precise moment they are waiting for an answer.

The label carries the explanation, as §5.5 requires, so nothing about the state
depends on the dimming that `base.css`'s `:disabled` rule would have applied.
The product must no-op the activation while `aria-disabled` is set; that is a
one-line guard in M2 and is recorded in §17.

---

## 6. Linking — Google and email, linked and unlinked

### 6.1 One card per provider, four states each

Both cards are `.card`. Each carries the provider name as an `<h3>`, a `.badge`
with the link state, one sentence of explanation, and one action at the foot of
the card. The action is pushed to the foot (`margin-block-start: auto`) so that
two cards of different text lengths still line their buttons up.

| State | Google | Email |
| --- | --- | --- |
| not linked | badge "Chưa liên kết" · *Liên kết Google* | badge "Chưa liên kết" · an email `.field` · *Gửi liên kết đăng nhập* |
| in flight | — (the redirect leaves the page) | *Đang gửi…* with `aria-disabled` |
| sent | — | `notice--success`: open your inbox, the link expires in 15 minutes · *Gửi lại* |
| linked | badge "Đã liên kết" · *Bỏ liên kết Google* | badge "Đã liên kết" · *Bỏ liên kết email* |
| failed | `notice--error`, `role="alert"` · *Thử lại* | `notice--error`, `role="alert"` · *Thử lại* |

### 6.2 The link state is carried by a word, never by a colour

"Đã liên kết" and "Chưa liên kết" use **the same badge, in the same colour**.
That is deliberate and it is the opposite of a missed opportunity.

`DESIGN.md` makes Clay — `.badge`'s colour — a *mark*, never a meaning, and
forbids it on anything clickable. Giving "linked" a green badge and "not linked"
a red one would invent two meaning-colour pairs on a `surface` fill that
`DESIGN.md` has never measured (`success` and `warning` are measured against
`bg` only — `components.md` §2.1), and it would make colour the primary channel
for a status, which `components.md` §2.2 forbids outright. With both badges
identical, the colour carries nothing and the word carries everything. Convert
the sheet to greyscale and no information is lost, because none of it was ever
in the hue.

### 6.3 No email address is printed, even when linked

`requirements.md` §4.1 says the profile holds no copy of the player's email. A
page cannot display what it does not have, and this page does not pretend
otherwise: **"Đã liên kết" is the whole of what it knows**, and it is also the
whole of what a player needs in order to decide whether to unlink.

This is the same rule `wireframes.md` §4.4 applies to the player id: the page
shows identity **state**, never an identifier.

### 6.4 Unlinking, and the one case that needs a warning

Unlinking is reversible — the player can link again — so it is an ordinary
`.btn--secondary` and it gets **no confirmation dialog**. A confirmation on a
reversible action trains players to click through confirmations, which is
exactly what the delete flow needs them not to do.

There is one case where unlinking costs something that cannot be undone by
re-linking: **when it is the only linked method**. Then the card carries a
`notice--warning` stating the consequence and, just as importantly, stating what
is *not* a consequence: *"Bỏ liên kết sẽ khiến bạn không đăng nhập lại được
trên thiết bị khác. Điểm của bạn không bị xóa."* The first sentence is the cost;
the second answers the question a player actually has.

### 6.5 Signing out with nothing linked

An anonymous identity with no linked method has no way back in. Signing out of
it would strand the player's own scores — not delete them, which is worse,
because an orphaned score cannot even be cleaned up by its owner.

So on this page **"Đăng xuất" is present, reachable and unavailable** when
nothing is linked: `aria-disabled="true"` on an enabled button, with
`aria-describedby` pointing at a sentence that says why and what unlocks it.
The mechanism is `components.md` §5.5's, the same one §5.3 uses.

Three alternatives were considered and rejected:

- **Hide the button.** The player never learns that sign-out exists, or that
  linking is what enables it.
- **Show it and let it work.** The page would be offering an action whose main
  effect is to lose the player their own scores.
- **Show it and confirm with a dialog.** A second destructive-feeling dialog on
  a page that already has one, for an action that is not destructive — it does
  not delete anything — and that the page can simply not offer yet.

This is a design decision this Task took rather than inherited; §14.4 puts it
in front of the reviewer and the Board.

---

## 7. Delete my data — the whole flow

### 7.1 The entry point

`wireframes.md` §4.4 makes this first-class three ways and this page keeps all
three: it is one of three sibling `<h2>` sections; it is in the in-page contents
directly under the `<h1>`; and its label states plainly what it does.

The button is `.btn--destructive` — `components.md` §6.1's decision: the
secondary geometry wearing `error`, with the doubled inset edge shipping in the
**default** state so it reads as "not an ordinary button" before anyone hovers
it. It is never filled. The page's one filled button is "Lưu".

### 7.2 Distinguishable without colour

The destructive action carries **four** signals and only one of them is colour:

1. **Shape** — the doubled inset edge, present at rest, which no other button
   in the library has.
2. **An icon** — the trash glyph, which is never the only label.
3. **A verb that says what it does** — "Xóa dữ liệu của tôi", not "Tiếp tục".
4. **A confirmation step** in front of it.

Three of the four survive greyscale, a monochrome display and every form of
colour vision deficiency. §15.5 makes checking that a review step.

### 7.3 The four stages, and the fifth one an honest flow needs

| Stage | What the player sees |
| --- | --- |
| **Entry** | the destructive button in the "Dữ liệu của tôi" section |
| **Confirm** | the dialog: an itemised list of exactly what will be deleted, then one bold line — *"Không thể hoàn tác. Sau khi xóa, bạn sẽ được đăng xuất."* |
| **In progress** | both actions carry `aria-disabled`, the destructive one reads "Đang xóa…", a `role="status"` line reads "Đang xóa dữ liệu của bạn…", one sentence says "Đừng đóng tab này.", and **Esc is suppressed for exactly this stage** (§7.3.1) |
| **Result** | the dialog closes; a `notice--success` with `role="status"` and `tabindex="-1"` receives focus: the data is gone and you have been signed out |
| **Failure** | a `notice--error` with `role="alert"` **inside** the dialog, whose first sentence is *"Chưa xóa được gì cả. Dữ liệu của bạn vẫn còn nguyên."*, and a *Thử lại* action |

Two copy decisions worth defending:

- **The confirmation is a list, not a paragraph.** A player scanning a
  confirmation reads items and skips sentences, and the one thing this dialog
  must not do is let them skip *what* is being deleted.
- **The failure message leads with what did not happen.** When a destructive
  action fails, the player's first question is whether it half-succeeded.
  "Chưa xóa được gì cả" answers it in four words, before the page says anything
  about the network.

**Why the result notice takes focus.** The other two focus moves on this page
are handled for free — `showModal()` returns focus to the opener when the dialog
closes. This one cannot be: after a successful deletion the player is signed
out, the "Dữ liệu của tôi" section no longer has a delete button, and the
control that opened the dialog does not exist to return to. So the result notice
is the focus target, and it carries `tabindex="-1"` for that reason only.

### 7.3.1 Esc while the deletion is in flight

The in-progress row above marks **every** exit unavailable — the close button,
"Hủy" and the destructive button all carry `aria-disabled`. Esc is not an exit
the page can mark: a native `<dialog>` opened with `showModal()` closes on Esc
unless the product calls `preventDefault()` on the `cancel` event. Left alone it
would be the one remaining way out of an irreversible operation already running,
and it would land the player on an opener that a successful deletion is about to
remove — with no status line left to read and no result notice yet to take
focus. So this is a decision, not an omission:

> **While the request is in flight, the product calls `preventDefault()` on the
> dialog's `cancel` event. The moment the request resolves — either way — it
> stops.**

The stage is bounded in both directions, which is what keeps it on the right
side of WCAG 2.1.2:

- **Success** — the dialog closes itself and focus moves to the result notice
  (§7.3). The player never needed Esc.
- **Failure** — the error notice appears inside the dialog, and the close
  button, "Hủy" and Esc are all live again in the same frame.

The player is also told, while it is happening, what is happening: the
`role="status"` line and "Đừng đóng tab này." are on screen for the whole of it.
A short, announced, self-resolving wait is not a trap; an Esc that abandons a
delete mid-flight is a worse answer to the same keystroke.

**This buys one obligation for M4, recorded in §17: a request that never answers
must still land in the failure state.** A deletion left hanging forever is the
one thing that would turn a bounded wait into a real keyboard trap, so the
timeout is an accessibility requirement here, not a nicety.

`components.md` does not cover this case — its §5.11 describes the ordinary
dialog, which has no stage where Esc should be refused. Nothing above changes
the library's contract; it is one page's exception to it, written down so M2
does not have to guess, and offered to M1-04 if a second destructive flow ever
wants the same rule.

### 7.4 The dialog, and what this sheet deliberately does not duplicate

The product opens a real `<dialog>` with `showModal()`. That single call supplies
the focus cycle, the inert background, Esc, and the top layer — so **this page
ships no focus-management code of its own**.

> **"No keyboard trap" does not mean Tab must leave the dialog.** WCAG 2.1.2
> requires that the user can *get out* by keyboard. Esc, "Hủy" and the close
> button all do that, and all three return focus to the control that opened it.
> (The one stage where that is not true is the few seconds a deletion is in
> flight, when all four exits are withheld together and both outcomes give the
> keyboard back — §7.3.1 is the whole argument.)
> **Tab cycling inside an open modal is the required behaviour** (WCAG 2.4.3 and
> the ARIA dialog pattern), not the violation. `components.md` §5.11 spells this
> out because it is the single most often misread criterion on the list.

**The safe choice comes first in the tab order.** The first focusable element in
the dialog is the close button, then "Hủy", and the irreversible verb is last.
That is DOM order, not a CSS reordering, so it cannot drift.

On the sheet the dialog is drawn **in place**, as `<div class="dialog"
role="dialog">` — exactly the device `components.html` §14 uses for its static
copies, and not a closed `<dialog>` element forced visible with CSS. The
backdrop and the keyboard contract cannot be shown by any static picture: they
exist only while `showModal()` has the element in the top layer.
`components.html` already ships **one** real `<dialog>` behind a real button for
precisely that test, and that is where a reviewer checks Esc, the Tab cycle and
the return of focus. **This sheet does not duplicate it** — a second copy would
be a second `<script>`, on a page whose own rules allow none, to re-prove a
contract the library already proves.

---

## 8. What the account holds — and what it does not

### 8.1 The table is `requirements.md` §4.1, in the player's words

Every field in both tables of §4.1 appears, and nothing else does. The mapping
is printed on the sheet (§6 there) so a reviewer can check it line by line:

| Line on the page | Field | Table |
| --- | --- | --- |
| Tên hiển thị | `display_name` | `players` |
| Mã người chơi | `id` | `players` |
| Ngày tạo tài khoản | `created_at` | `players` |
| Điểm của bạn — trò chơi, tuần, điểm, thời gian sống sót, seed, phiên bản trò chơi | `player_id`, `game_id`, `week`, `score`, `time_survived_ms`, `seed`, `game_version`, `created_at` | `scores` |

And the three "not stored" lines, which are `requirements.md` §4.1's own
"explicitly not stored" list:

- Hồ sơ của bạn không có bản sao địa chỉ email nào.
- Không có địa chỉ IP trong bất kỳ bảng nào.
- Không đo đạc, không theo dõi, không phân tích hành vi.

### 8.2 Two columns, not three — a presentation choice, with nothing dropped

`requirements.md` §4.1 carries a retention column. Its value is the **same
string on every row**: until the player deletes. Repeating it four times would
spend a third of a 343 px table saying one thing four times, so the retention
rule is stated **once**, as a sentence under the table: *"Mọi dòng trong bảng
được giữ cho tới khi bạn xóa dữ liệu của mình. Không có thời hạn nào khác."*

Nothing is dropped and nothing is softened. If the Board ever answers
`requirements.md` §8 open question 3 by pruning weekly boards after N weeks,
that sentence stops being true and this page needs a retention column again.
That dependency is recorded here so the change is not missed.

### 8.3 The table sits on the page background, and that is a contrast decision

`base.css` draws every `<th>` in `text-muted`, and a table's row rule in
`border`. On `bg` both pairs are already measured — 6.25 / 7.65 and the
decorative 1.32 / 1.49. Inside a `.card` they would be pairs against `surface`
that nobody has measured. The same reasoning `leaderboards.css` §4 applies to
the boards.

The one override this page writes is on `.ac-what__cell`, the row header: base's
`<th>` styling is right for a column header and wrong for a row header, because
it would print the stored field names smaller and fainter than the explanations
beside them — inverting the emphasis of the one table on the site whose left
column is the point. The rule is written on the **page class the element
carries**, never on `th`, so `base.css` keeps exactly one owner of that element.
`text` on `bg` is 14.64 / 14.19.

### 8.4 The one sentence this page would not write

See **§14.1**. In short: `requirements.md` §4.1 says *"No copy of the player's
email. The auth provider holds it"* — and under ADR-021 the auth provider is the
studio's own Laravel backend. Read literally, the broad claim "we do not hold
your email" is not one this page can make. So the page makes the narrow claim it
is certain of — *"Hồ sơ của bạn không có bản sao địa chỉ email nào"* — and hands
the broad one to the Board and to M1-09.

---

## 9. States, roles and reserved heights

### 9.1 Which block each region uses

Normative, and it is `components.md` §4.4's row for this page, filled in.

| Region | Empty | Loading | Error |
| --- | --- | --- | --- |
| **Profile** · display name | — (a signed-in player always has a name) | `.state--loading` in the field's place, 1 skeleton bar, `aria-busy="true"` on that block and the "Đang tải tên hiển thị của bạn…" text in the separate polite live region beside it (`components.md` §4.3 note 3) | value invalid → `.field__error` + `aria-invalid`; save failed → `.error-summary` + `role="alert"` + focus |
| **Sign-in** · each provider card | — | the action reads "Đang gửi…" with `aria-disabled` | `notice--error` in the card, `role="alert"`, with *Thử lại* |
| **My data** · delete | — | the action reads "Đang xóa…" with `aria-disabled`, plus a `role="status"` line | `notice--error` **inside the dialog**, `role="alert"` |
| **The whole page** · no session | `.state--empty` at `.state--reserve-md`, with *Đăng nhập* | — | `.state--error` at the same reserve, `role="status"`, with *Thử lại* |

### 9.2 The signed-out fill — a fourth fill, not a fourth block

`components.md` §4.4 leaves the account row's "Empty" cell blank. SOU-6 asks for
a signed-out state. This Task fills that cell with the **empty** pattern, not a
new one, which is exactly the move `components.md` §4.4 already blessed for the
leaderboards' "your result": *"The fourth state is a fourth **fill**, not a
fourth block."*

It is the empty block and not the error block for a reason worth stating: **not
being signed in is not a fault.** Giving it the error colour and the word "Lỗi"
would be the page treating a perfectly ordinary condition — most visitors arrive
without a session — as a malfunction.

The reserve is `.state--reserve-md`, which is not a choice this page made:
`components.md` §4.1's own table lists that modifier against *"a full
leaderboard, **the account profile block**"*. The library already sized this.

**The "Dữ liệu của tôi" section stays visible when signed out.** The table of
what the site holds is a public fact about the product, not a reading of one
person's data, and hiding it behind a session would make the page's central
promise conditional on having an account. Only the delete button is absent,
because there is nothing yet to delete.

### 9.3 The one reserved height, and the one transition that is not zero-shift

`.ac-profile__state` sets `--state-reserve` to the block size of the **loaded**
field, computed from the same tokens the field's three parts read:

```
label      --text-label-md × --leading-label   = 14 × 1.4   = 19.6
gap        --space-xs                                       =  4
control    --target-min  (base.css's minimum control box)   = 44
gap        --space-xs                                       =  4
help       --text-body-sm × --leading-body-sm  = 14 × 1.55  = 21.7
                                                              -----
                                                              93.3 px
```

None of those five tokens changes at the switch, so **the reserve is the same
number in both bands** — one value, no band mirror needed for it.

The loading block is intrinsically **shorter**: `.state`'s padding
(2 × `--space-2xl` = 64) plus its two 1 px edges plus one `.skeleton__bar`
(`--space-md` = 12) is 78 px. So the reserve is what sets its height, which is
what a reserve is for, and **`loading → loaded` moves nothing**. That is the
path a player takes on every visit.

**What the reserve does not cover, stated plainly.** The submit-failure
`.error-summary` is an element *inserted above the form*, not a state swapped
inside it, so it pushes the form down. That is correct behaviour and not a
defect: the summary is the thing the player must read, focus is moved to it, and
reserving a permanent hole on every visit for an error that is almost always
absent would be the worse answer — it would make the page permanently taller to
avoid a shift that happens only when something has already gone wrong, and at
that moment the player's attention has been deliberately moved to the new
element anyway.

### 9.4 The ARIA roles, and why two of them are polite

Straight from `components.md` §4.3, applied:

| Situation on this page | Role | Why |
| --- | --- | --- |
| the name field is loading | `aria-busy="true"` on the block, and the status text in the persistent polite live region **beside** it — never both on one element (`components.md` §4.3 note 3) | a load the player did not ask for must not interrupt them, and a block that is replaced never clears its own busy flag |
| the profile failed to load in the background | `role="status"` | still polite — the failure is not more urgent than the sentence they are reading |
| the save failed / the link failed / the delete failed | `role="alert"` | the player just acted and is waiting; interrupting *is* the answer |
| the save succeeded / the link was sent / the delete finished | `role="status"` | good news can wait for a pause |
| the form failed validation on submit | `role="alert"` on `.error-summary`, focus moved to it | announced *and* reached |
| a field is invalid | `aria-invalid="true"` + `aria-describedby`, no live region | the error is not new by the time the player arrives at the field |

And the rule behind the table that M2 must not forget: **a live region has to be
in the DOM before the content is put into it.** Insert the empty
`role="status"` container with its region, then fill it. Creating the element
and its text in one step is the commonest reason a correctly marked-up status is
never announced.

---

## 10. Keyboard, focus and reading order

DOM order = Tab order = reading order, in **both** bands. The page has no
`order` property, no positive `tabindex`, and nothing repositioned by a media
query, so there is nowhere for the two to disagree.

| # | Tab stop | 375 px | 1280 px |
| --- | --- | --- | --- |
| 1 | `skip-link` → `#main` | yes | yes |
| 2 | wordmark | yes | yes |
| 3 | nav disclosure button | yes | — (hidden) |
| 4 | three nav links | when open | yes |
| 5 | theme toggle (one stop for the group; arrows move inside it) | yes | yes |
| 6 | the ad slot | **never** — it is not focusable in any band | |
| 7 | three in-page contents links | yes | yes |
| 8 | "Tên hiển thị", then "Lưu" | yes | yes |
| 9 | the Google card's action | yes | yes |
| 10 | "Địa chỉ email", then the Email card's action | yes | yes |
| 11 | "Đăng xuất" — **still a tab stop when unavailable**, because it carries `aria-disabled`, not `disabled` | yes | yes |
| 12 | the "what is held" table — **no tab stop**; Tab passes through it | yes | yes |
| 13 | "Tải bản sao dữ liệu của tôi" | yes | yes |
| 14 | "Xóa dữ liệu của tôi" | yes | yes |
| 15 | four footer links | yes | yes |

**While the dialog is open that order stops.** The page behind becomes inert and
Tab cycles inside the dialog: close → Hủy → Xóa vĩnh viễn → close. §7.4 is why
that is required rather than forbidden.

**And while a deletion is in flight, Esc stops too.** That one stage withholds
every exit at once — the three controls by `aria-disabled`, Esc by
`preventDefault()` on the dialog's `cancel` event — and gives them all back the
instant the request resolves: on success the dialog closes itself and focus
moves to the result notice, on failure the error notice appears inside the
dialog and close, "Hủy" and Esc are live again. Tab still cycles throughout, so
the dialog is never a place the keyboard cannot move within. §7.3.1 is the
argument and the obligation it puts on M4.

**Focus is moved exactly three times, and only three.**

1. **On a failed submit** → to `.error-summary` (`tabindex="-1"`,
   `role="alert"`), so it is both announced and reached.
2. **When the dialog closes** → back to the control that opened it. The browser
   does this; the page writes no code for it.
3. **After a successful deletion** → to the result notice, because the opener no
   longer exists (§7.3).

**Targets** are 44 × 44 px minimum at every width with at least 8 px between
adjacent ones — including the dialog's action row and the two actions of "Dữ
liệu của tôi", where the second is destructive and gets `--space-md` (12 px)
rather than the minimum.

---

## 11. Contrast

**This page introduces no new foreground/background pair.** Every pair it uses
was already measured — in `DESIGN.md` → "Measured contrast", or in
`components.md` §3.4 for the four `primary-hover` pairs. This Task measured
nothing, because it needed to measure nothing, and it invented no ratio.

| Where it is used on this page | Pair | Light | Dark | Needs | |
| --- | --- | --- | --- | --- | --- |
| Body copy, the data table's row headers, state titles | `text` / `bg` | 14.64 | 14.19 | 4.5 | PASS |
| Card text, dialog text, the text inside an input | `text` / `surface` | 15.58 | 12.66 | 4.5 | PASS |
| Help text, column headers, notes, state body | `text-muted` / `bg` | 6.25 | 7.65 | 4.5 | PASS |
| Card notes, the dialog's status line, footer | `text-muted` / `surface` | 6.65 | 6.82 | 4.5 | PASS |
| Links and secondary-button labels on the page | `primary` / `bg` | 5.69 | 8.66 | 4.5 | PASS |
| Secondary-button labels inside a card or the dialog | `primary` / `surface` | 6.06 | 7.72 | 4.5 | PASS |
| "Lưu" | `on-primary` / `primary` | 6.11 | 8.15 | 4.5 | PASS |
| "Lưu", hovered | `on-primary` / `primary-hover` | 8.57 | 9.65 | 4.5 | PASS |
| A hovered link or secondary button, on the page | `primary-hover` / `bg` | 7.98 | 10.25 | 4.5 | PASS |
| A hovered link or secondary button, on a card | `primary-hover` / `surface` | 8.49 | 9.14 | 4.5 | PASS |
| The two link-state badges | `secondary` / `surface` | 5.97 | 6.78 | 4.5 | PASS |
| "Đã lưu", "Đã gửi", "Đã xóa" notices | `success` / `bg` | 5.75 | 8.63 | 4.5 | PASS |
| The last-method unlink warning | `warning` / `bg` | 6.08 | 8.09 | 4.5 | PASS |
| Error notices, the error summary, the error state block | `error` / `bg` | 6.30 | 7.45 | 4.5 | PASS |
| Field error text, the destructive button's label | `error` / `surface` | 6.71 | 6.65 | 4.5 | PASS |
| The focus ring on the page | `focus` / `bg` | 5.65 | 8.90 | 3 | PASS |
| The focus ring on a card or in the dialog | `focus` / `surface` | 6.01 | 7.94 | 3 | PASS |
| Input and button edges on the page | `border-strong` / `bg` | 3.48 | 3.57 | 3 | PASS |
| Input, badge and notice edges on a card or the dialog | `border-strong` / `surface` | 3.70 | 3.19 | 3 | PASS |

Two pairs below 3:1, both recorded as **decorative** by `DESIGN.md` and repeated
in `components.md` §3.3:

| Pair | Light | Dark | Where, and why it is exempt |
| --- | --- | --- | --- |
| `border` / `bg` | 1.32 | 1.49 | the skeleton bar (`aria-hidden`, with the live-region text carrying the signal), the table's row rules, and the card edge |
| `surface` / `bg` | 1.06 | 1.12 | surface separation is carried by the border and the radius, never by the fill |

Three decisions that look visual and are not:

1. **The forms and the table sit on the page background, not in cards.** Help
   text is `text-muted` and field errors are `error`; on `bg` both are measured.
   Inside a card the row rule of the table would be `border` on `surface`, which
   nobody has measured.
2. **The destructive button supplies its own `surface` fill.** So its label is
   always `error` on `surface` (6.71 / 6.65) wherever the button sits, and its
   edge against the page is `error` on `bg` (6.30 / 7.45). Both clear their
   thresholds.
3. **The badge supplies its own `surface` fill.** Inside a card that fill
   matches the card, so what delimits the badge is its `border-strong` edge
   (3.70 / 3.19), not a fill difference — which is how every plate in this
   system works.

**Unavailable controls keep their contrast.** "Đăng xuất" when nothing is
linked, "Đang lưu…" and "Đang xóa…" all carry `aria-disabled`, not `disabled`,
so `base.css`'s `:disabled { opacity: 0.5 }` never applies to them. The reason
they cannot be used is in the words, not in the dimming. `components.md` §3.5's
exemption for genuinely inactive components is therefore not relied on anywhere
on this page.

---

## 12. Minimal data — what this page shows, and what it refuses to

- **No player id is printed**, anywhere, in any state. The page shows identity
  *state* (`wireframes.md` §4.4).
- **No email address is printed**, even when email is linked (§6.3).
- **No device, location, session or browser information** appears on the page or
  in the data table, because none of it is stored.
- **No third-party request of any kind** is made by the mockup: no script, no
  frame, no font file, no image, no provider SDK. §15.1's first check enforces
  it mechanically.
- **The page states what is *not* held as prominently as what is.** Three lines,
  directly under the table, in the same visual weight — not a footnote.
- **The export action is "take a copy", not "view in a modal".** A player who
  wants their data wants to keep it; a modal they have to screenshot is a worse
  answer to the same question. **This one is not settled here** — "Tải bản sao
  dữ liệu của tôi" promises more than any document this Task inherited, so it is
  an open item for the Board and for M1-09, not a design decision this page gets
  to take alone. **§14.7 is the whole of it.**

---

## 13. Ad slots

| Band | Slot | Size | Position |
| --- | --- | --- | --- |
| mobile · < 768 px | **none** | — | `ad-slots.md` §3: outside the play page the mobile band has no ad position at all |
| ≥ 768 px | `ad-header-leaderboard` | 728 × 90 | in the header, **after** the skip link |

This page has no game frame, so `ad-slots.md` §5's 24 px clearance rule — which
is measured *from the frame* — has nothing to measure against here. That is not
a relaxation: it means this page cannot violate that rule, and every other rule
applies intact. The slot is `position: static`, is not focusable, carries a
visible "Quảng cáo" label and a named landmark, and sits after the skip link so
one keystroke passes the whole banner.

`account.css` contains **no `.ad-slot` rule** outside the sheet's band mirror,
which restates `display` and nothing else. `ads.css` is the sole owner
(`ad-slots.md` §11).

**No ad slot sits inside the confirmation dialog or beside the delete button.**
No document forbids that specifically, because nobody expected to need to; it is
written down here so the next person who asks has an answer.
`requirements.md` §5.1 lists the permitted positions exhaustively and "next to a
destructive action" is not among them — any other position is a defect.

---

## 14. Open items, and the things that want a decision

### 14.1 "We do not keep your email" — for the Board and for M1-09

**This is one of two items on this Task that another document must agree with
before either ships — §14.7 is the other — and this page deliberately did not
settle either alone.**

`requirements.md` §4.1 says: *"No copy of the player's email. The auth provider
holds it, and only if the player links an email."* That sentence was written
when the backend provider was undecided. **ADR-021 settled it: the auth provider
is the studio's own Laravel backend.** So the sentence, read in 2026, can be
heard as a broader promise than the facts support — a player reading "we do not
keep your email" will not distinguish "not in the `players` table" from "not
held by this studio at all".

What this page does: it makes only the **narrow** claim, which is certainly true
— *"Hồ sơ của bạn không có bản sao địa chỉ email nào"* — and it never makes the
broad one.

What is needed, and from whom:

- **The Board** decides what the honest broad statement is, given that Laravel's
  own auth tables will hold a linked address.
- **M1-09 (privacy policy)** must use the same wording. SOU-6's acceptance
  criteria require the two not to contradict each other, and both Tasks are in
  the same release wave, so neither could read the other's text. **Both were
  written against `requirements.md` §4.1, which is the only shared source.** If
  M1-09 lands a broader claim than the one above, these two documents *will*
  contradict, and the fix belongs in whichever of them is wrong — not in a local
  patch here.

Until that is answered, nothing on this page is false. It is simply narrower
than the policy may turn out to be.

### 14.2 The display-name rules are this Task's proposal — for M2 and M4

"3–20 ký tự" and "moderated" are not in `requirements.md`, which lists
`display_name` as *"Shown on the leaderboards. Editable, moderated."* and says
nothing about length or character set. The page needed a concrete rule to design
the help text and the error messages around, so it proposes one and labels it as
a proposal here rather than letting a mockup quietly become a spec.

Open for whoever owns the field in M2/M4: the exact length bounds, whether a
display name must be unique, and what the moderation rule actually is. **The
design does not depend on the answers** — it depends only on there being a rule
that can be stated in one line of help text and one line of error text.

### 14.3 `aria-disabled` where `components.md` §4.4 says "disabled" — for the reviewer

`components.md` §4.4 describes the saving and deleting buttons as `disabled`.
This page uses `aria-disabled="true"` on an enabled button instead, in three
places: "Đang lưu…", "Đang xóa…" and the unavailable "Đăng xuất".

This is not a deviation from the library — `components.md` §5.5 supplies this
exact mechanism for this exact situation — but it *is* a different attribute
from the one §4.4's prose names, so it is flagged rather than left to be
noticed. §5.3 is the argument: focus is on the button at the moment it becomes
unavailable, and `disabled` would drop that focus to `<body>`.

It carries one obligation for M2, recorded in §17: the product must ignore
activation while `aria-disabled` is set, since the browser no longer does it.

### 14.4 Sign-out is unavailable with nothing linked — for the reviewer and the Board

§6.5 is a decision this Task took and did not inherit: an anonymous player with
no linked method cannot sign out, because doing so would strand their own
scores. Three alternatives and why each was rejected are in §6.5.

It deserves a second opinion because it is the one place where this page
withholds an action a player might expect to have.

### 14.5 The ad-slot label is still an unmeasured pair — for the Board

Re-raised unchanged from `home.md` §9.3, `play.md` §9.5 and `leaderboards.md`
§11.4. The pair belongs to `ads.css` and to M1-02; this Task invents no ratio for
it.

### 14.6 Design tooling was not available

`google-design-md` and the taste skill may be used only after GAM-19 passes and
the Board installs them (security-architecture §11). **Neither was installed in
this environment, so neither was used**, and no uninstalled tool was substituted
for either.

### 14.7 "Tải bản sao dữ liệu của tôi" — for the Board and for M1-09

**This page draws an action no document asked for, and it should not be read as
approved by having been drawn.** It is listed here rather than settled in §12,
where it first appeared as a design decision.

What the inherited documents actually say:

- `wireframes.md` §4.4 gives this region three things: *what is stored*, *view
  it*, and *Xóa dữ liệu của tôi*. A download is not among them.
- `requirements.md` says a player can **see** everything stored about them. The
  table in §8 already discharges that in full, on the page, with no request.
- `wireframes.md` §4.5 routes the privacy policy's "your rights" section to
  **delete** only.

So "take a copy" is a stronger promise than "see it": it implies an export
endpoint in M4 that nobody has approved, a format, and a decision about what a
copy of a player's data even contains. And because M1-09's rights section is
specified as delete-only, a privacy policy written to that spec will not mention
an export — which is exactly the contradiction SOU-6's acceptance criteria
forbid between these two documents.

**The case for keeping it**, so the Board is deciding against something: the
section is called "Dữ liệu của tôi" and holds the delete action; a player who
has just read what is held and is one button away from destroying it is the
player most likely to want to keep a copy first. Offering the copy beside the
deletion is what makes the deletion safe to offer.

What is needed, and from whom:

- **The Board** decides whether the product promises an export at all. This Task
  is not asking for removal and has not removed it; the button stays drawn so
  the decision is made against a real screen.
- **M1-09** must match whatever the Board decides. If the answer is no, the
  action comes out of this page — one `<a>` in §3, one tab stop in §10, one
  bullet in §12, and the sheet's eight copies. **Nothing else on the page
  depends on it**, which is the reason it was safe to draw before the answer.
- **M4** inherits a scope item if the answer is yes, and `AGENTS.md` §4 would
  mark that path security-sensitive: an endpoint that hands a player a file of
  their own data is an endpoint that can hand it to somebody else.

Until that is answered, the honest reading of this button is "proposed", exactly
as §14.2's display-name rules are proposed.

---

## 15. How to verify

No build, no server, no dependency: open
[`../prototypes/account.html`](../prototypes/account.html).

### 15.1 Five checks a reviewer can run

From the repository root. All five must return **nothing**.

```sh
# 1. No script, no embedded frame, no font file, no auth SDK, no network
#    reference of any kind. (<link rel="stylesheet" href="..."> is relative and
#    is deliberately not matched — the pattern catches a remote reference, not
#    a local one.)
grep -nEi '<script|<iframe|@font-face|src=|https?:' \
  docs/design/prototypes/account.html

# 2. No colour literal anywhere in the stylesheet.
grep -nE '#[0-9a-fA-F]{3,8}|rgba?\(|hsla?\(' \
  docs/design/prototypes/assets/account.css

# 3. No inline style attribute.
grep -n 'style="' docs/design/prototypes/account.html

# 4. No breakpoint other than M1-01's switch. This page has no side rail, so
#    1024px must not appear either.
grep -nE '@media' docs/design/prototypes/assets/account.css \
  | grep -v '768px'

# 5. Nothing on this page is lifted out of normal flow — the no-sticky rule,
#    checked for the ad slot and for everything else.
grep -nE '^[[:space:]]*(position:[[:space:]]*(sticky|fixed)|float:[[:space:]]*(left|right)|z-index:[[:space:]]*[0-9]|transform:[[:space:]]*(translate|scale|rotate|matrix|skew|perspective))' \
  docs/design/prototypes/assets/account.css
```

**Why these commands are here and not in `account.html`.** Check 1's pattern
searches for a source attribute and a protocol prefix *in that file*. Printed
inside it, the command would match itself and the check would fail on its own
documentation. A markdown file is outside the scanned set.

### 15.2 Every input has a real label

These two lists must be **identical apart from the attribute name** — 30 entries
each, in the same order:

```sh
# every label's target
grep -oE 'for="[^"]+"' docs/design/prototypes/account.html | sort

# every input's id
grep -oE '<input[^>]*id="[^"]+"' docs/design/prototypes/account.html \
  | grep -oE ' id="[^"]+"' | sort
```

One `<label for>` per text input, and no input without one. (The theme toggle's
radios use the library's wrapping-label form and are correctly absent from both
lists — a wrapping label needs no `for`.)

And this one must print nothing, which is how duplicate `id`s are ruled out —
the leading `(^|[[:space:]])` is load-bearing, because without it the pattern
also matches the tail of `aria-invalid="true"`:

```sh
grep -oE '(^|[[:space:]])id="[^"]+"' docs/design/prototypes/account.html \
  | sort | uniq -d
```

### 15.3 Measure the zero-shift claim

The sheet holds several renders, so scope the snippet to one view. Only fields
in their **valid** state are comparable: an invalid field carries an extra error
line and is legitimately taller, which is a change the player caused and not a
load transition.

```js
const v = document.querySelector('.mk-view--desktop');   // then --mobile
[...v.querySelectorAll('.ac-profile__field, .ac-profile__state')]
  .filter(e => !e.querySelector('.field__error'))
  .map(e => Math.round(e.getBoundingClientRect().height));
```

Expected: **93** for every entry, at both widths (§9.3's arithmetic). The
error-summary transition is excluded on purpose — §9.3 says why.

### 15.4 Confirm the ad inventory, per band

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
`static` in every `--desktop` view.

### 15.5 By eye

1. **Four renders of the full page**, and four for each group of state blocks.
2. **Greyscale the sheet**, then find: the delete button (shape + icon + verb
   survive), the two link badges (the word survives; the colour never carried
   anything), the error blocks (icon + the word "Lỗi" survive), and the empty
   block (which must still not look like an error).
3. **Tab through it.** The ring must always be visible, including on the
   unavailable "Đăng xuất", which is still a stop.
4. **Count the ad slots:** none at 375 px, exactly one at 1280 px.
5. **No horizontal scrollbar at 375 px.** Give the display-name field a 20 ×
   "ườ" value and the data table's first column a long field name, and check
   neither overflows — `overflow-wrap: anywhere` is the mechanism.
6. **Open `components.html` and press the live dialog's button**: Esc closes it,
   Tab cycles inside it, and focus returns to the opener. That is the keyboard
   contract this page relies on and does not re-prove (§7.4). **What that dialog
   cannot show is §7.3.1** — Esc refused while a deletion is in flight, and
   accepted again the moment it fails — because no static sheet and no
   script-free page has a request in flight to refuse it during. It is an M2
   obligation and an M4 test, listed in §17 as both.
7. **Turn on `prefers-reduced-motion: reduce`** and confirm nothing changes,
   because nothing on this page moves.

---

## 16. What this Task did not verify

Recorded plainly, because an unverified claim presented as verified is worse
than a gap.

- **No rendered visual check. No browser was available in this environment**, so
  **the sheet has not been opened and looked at.** The markup and stylesheet are
  checked statically — the five commands in §15.1 all return nothing; opening
  and closing tag counts balance exactly for all 35 non-void element types in
  the document; every
  `id` is unique; every `aria-labelledby`, `aria-describedby`, `aria-controls`
  and `for` target resolves to an `id` in the same document; every `href="#…"`
  resolves; every `<use>` resolves to a `<symbol>` defined in the same document
  and every defined symbol is used; and every class in the sheet resolves to a
  rule in one of the five stylesheets, apart from `.state--empty`, which is the
  library's own documented declaration-less semantic hook. **How it looks is for
  the `qa-engineer` review, not a claim this Task is making.**
- **The reserved height in §9.3 is derived, not browser-measured.** Every term
  is a token applied by a stylesheet, so the arithmetic is deterministic and a
  reviewer can add it up — but it is arithmetic over the CSS, not a
  `getBoundingClientRect()` reading. §15.3 turns it into a measurement, and that
  is a review step.
- **No contrast was re-measured.** Every ratio in §11 is quoted with its source.
  This Task computed none of them and had no tool to.
- **The keyboard order is designed, not exercised.** §10's table is what the DOM
  says. That a real browser agrees is a review step, and so is the claim that
  the live regions actually announce in a real screen reader — particularly the
  `role="alert"` inside the dialog, which is the one announcement on this page
  that has to compete with a modal being opened.
- **The dialog's focus behaviour is inherited, not demonstrated here.** This
  sheet draws the dialog statically and points at `components.html`'s live one
  (§7.4). If that live dialog does not actually return focus, this page is wrong
  too — and the fix belongs in M1-04. **§7.3.1 is further than that: it is a
  rule the library does not have and no page in this repository yet implements.**
  Nothing here demonstrates it; it is written as a requirement on M2 and M4 and
  nothing more.
- **The Vietnamese font check remains open**, as `components.md` §8.3 left it.
  No platform was tested here.
- **The long-display-name and long-field-name cases in §15.5 item 5 were
  reasoned about, not observed.**

---

## 17. Handoff

**To `qa-engineer` (the named reviewer).** SOU-6 asks you for an accessibility
review of the forms, the error messaging and the destructive-action flow. The
five things that most want your judgement:

1. **§5.3, §6.5 and §14.3** — `aria-disabled` on an enabled button in three
   places, where `components.md` §4.4's prose says "disabled". Is the
   focus-retention argument right, and is "Đang lưu…" a sufficient explanation
   in the label?
2. **§6.5 and §14.4** — withholding sign-out from a player with nothing linked.
   Is "reachable but unavailable, with the reason beside it" the right answer,
   or does a player read it as the page being broken?
3. **§5.2** — two kinds of failure with two different treatments, and the order
   of `aria-describedby` (error first, help second).
4. **§7** — the whole delete flow. Especially: is the confirmation list the
   right level of detail, and does the failure message lead with the right
   sentence? **§7.3.1 is the one to argue with**: suppressing Esc while the
   deletion is in flight is this Task's answer to "every exit is unavailable, so
   what does the key that is not a control do?", and it rests entirely on the
   stage being bounded at both ends.
5. **§9.2** — using the empty block for "not signed in". Does it read as a
   neutral state rather than a fault?

**To the Board.** Two need an answer before M1-09 and this page can both be
true. **§14.1** — the email claim, and what ADR-021 changed about it.
**§14.7** — whether "Tải bản sao dữ liệu của tôi" is a promise the product
makes at all; no inherited document asks for it, and M1-09's rights section is
specified as delete-only. Then §14.4 (sign-out), §14.5 (the ad-slot label's
unmeasured pair) and §14.6 (the design tooling was not installed, so it was not
used).

**To M1-09 (privacy and consent).** Your §1 "what is stored" section and this
page's table are two renderings of the same source, `requirements.md` §4.1.
**Read §8, §14.1 and §14.7 before writing yours.** The three "not stored" lines and the
single retention sentence are the exact claims this page makes; if yours differ,
one of the two is wrong and it needs fixing rather than reconciling. This page
links the player to the policy, and the policy's "your rights" section links
back here to "Xóa dữ liệu của tôi" (`wireframes.md` §4.5) — so the two are
reachable from each other in one step and must not disagree.

**To M1-04's owner.** Nothing is asked of you. This page adds no component and
restyles none. Two compositions are documented here rather than promoted:
`.ac-link` (a `.card` with a badge in its head and its action pinned to its
foot) and `.ac-what` (an ordinary table with one page class on its row headers).
If a second page ever needs either, that is when they become library business.

**To M2 (the stack).** §3 is the markup to lift. Five properties are contracts,
not styling: **one `<h1>` and no skipped level**, **the in-page contents under
the `<h1>` with "Dữ liệu của tôi" in it**, **the three sections as siblings**,
**the error summary above the form**, and **a real `<dialog>` opened with
`showModal()`**. Three obligations come with them:

- **Ignore activation while `aria-disabled="true"` is set** (§14.3). The browser
  no longer blocks it for you.
- **Call `preventDefault()` on the dialog's `cancel` event while a deletion is
  in flight, and stop the moment the request resolves either way** (§7.3.1).
  `aria-disabled` marks the three controls; only this marks Esc, and without it
  a keystroke abandons an irreversible operation already running.
- **Insert each live region before you fill it** (§9.4), or the announcement
  will not happen.

The route strings are yours; nothing here depends on them.

**To M4 (the backend).** This page assumes the server can answer five things:
the player's display name; whether each of the two methods is linked; a request
to change the name, which may be refused by moderation with a reason; a request
to send a magic link; and a request to delete everything, which either succeeds
completely or changes nothing. **"Partially deleted" is not a state this design
has a screen for**, and §7.3's failure copy promises the player it cannot
happen — so if the deletion path cannot be made all-or-nothing, that is a design
change, not an implementation detail. **The delete request must also always
answer**: it may succeed or fail, but it may not hang, because §7.3.1 withholds
Esc for as long as it is in flight and a request that never resolves would leave
a keyboard user shut inside the dialog. A timeout that lands in the failure
state satisfies it. `AGENTS.md` §4 marks every path involved as
security-sensitive.

**To M4 (tests).** Every claim in §9.4, §10 and §15 is a Playwright assertion:
the tab order, the visible ring on the `aria-disabled` button, the three focus
moves, the live-region roles, the dialog's Esc-and-return, **Esc refused while a
deletion is in flight and accepted again the moment it fails** (§7.3.1), the
per-band ad
inventory (0 at 375 px, 1 at 1280 px), `position: static` on the slot, the equal
heights of the loaded field and its loading block, and CLS = 0 at both widths.

**To M6 (ads).** This page carries one slot above 768 px and none below it.
Nothing here requests a creative, and nothing goes near the dialog.

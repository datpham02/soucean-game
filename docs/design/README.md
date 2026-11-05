# M1 design package — index and Board sign-off submission

**Status:** Submission for Board approval. Milestone M1 (Design), Task SOU-10
(M1-12). Owner: `ui-designer`, through `project-manager`. Reviewer:
`qa-engineer`. Last updated: 2026-11-05.

This is the front door to everything M1 produced. It does three things and
nothing else:

1. **indexes** the whole deliverable, with a working relative link and one line
   on what each piece contains (§1);
2. puts the **coverage** of the design in front of you as a table, with the gaps
   written down rather than implied (§2, §3);
3. states, for each **M1 exit criterion**, whether it is met and what the
   evidence is (§4) — and lists every finding from the audit and the feasibility
   review that is **still open** (§5), so the approval is given with full
   knowledge rather than by omission.

This Task **assembles**. It designs nothing, fixes nothing, and closes no
finding. The one exception is recorded in §8.

> **Start here if you are approving:** §4 (exit criteria), then §5 (what is
> still open), then open
> [`prototypes/index.html`](prototypes/index.html) from the filesystem and look
> at the pages.

**How to open any of it.** Every prototype is static HTML with relative links to
local stylesheets. Double-click the file, or open it from `file://`. No server,
no build step, no dependency, no network request — see §6.

**Language.** The documents in `docs/` are in English; the prototypes in
`prototypes/` are in Vietnamese, because they are the product's own interface and
the product is Vietnamese-first. `prototypes/index.html` follows the directory it
sits in and is in Vietnamese; this README is in English. That split is deliberate,
not an oversight.

---

## 1. The package

### 1.1 The entry points

| File | What it is |
| --- | --- |
| [`prototypes/index.html`](prototypes/index.html) | **The prototype entry point.** Every prototype, grouped by page, with the wireframe, the mockup sheet, the four full-page renders (375 and 1280 × light and dark) and the state sections each reachable by one link. Opens from the filesystem. |
| [`prototypes/wireframes/index.html`](prototypes/wireframes/index.html) | The wireframe-only index that M1-03 delivered: the eight low-fidelity sheets, the reading conventions, and what the sheets inherit rather than reinvent. |

### 1.2 The system and the specifications

| Document | What it contains |
| --- | --- |
| [`DESIGN.md`](DESIGN.md) | **The design system.** Machine-readable token frontmatter (both themes) followed by the prose: colours and their measured contrast ratios, the type scale at both widths, the spacing scale, elevation, shape, breakpoints (including the Board's 1024 px `wide` amendment), the component inventory, and the do's and don'ts. Task GAM-22 (M1-01). |
| [`ad-slots.md`](ad-slots.md) | **The ad-slot specification.** The five rules stated plainly, the slot inventory, the reference page geometry per band, the 24 px clearance arithmetic per slot, the no-overlap / no-sticky / no-shift guarantees, DOM order and accessibility, and the verification procedure. Task GAM-23 (M1-02). |
| [`wireframes.md`](wireframes.md) | **The wireframes.** How a sheet is built, the index, the regions and reading order of all eight pages, the play page checked against the ad-slot spec, and the accessibility decisions fixed at wireframe stage. Drawings in [`prototypes/wireframes/`](prototypes/wireframes/index.html). Task GAM-24 (M1-03). |
| [`components.md`](components.md) | **The component library.** The rules every component obeys, the contrast work, the three canonical state patterns (empty, loading, error), the component reference, and the open items it hands on. Sheet: [`prototypes/components.html`](prototypes/components.html). Task GAM-25 (M1-04). |

### 1.3 The page designs

Seven routed pages and the consent banner — the eight design surfaces named in
[`../requirements.md`](../requirements.md) §2.

| Page | Route | Design document | Wireframe | Mockup |
| --- | --- | --- | --- | --- |
| Home / game list | `/` | [`pages/home.md`](pages/home.md) — the grid, the game card and its manifest fields, the top-3 strip, all three states | [`01-home.html`](prototypes/wireframes/01-home.html) | [`home.html`](prototypes/home.html) |
| Play | `/games/<game_id>` | [`pages/play.md`](pages/play.md) — the 16:9 frame and its four displays, the controls and game-info block, the weekly board, the measured ad clearances in three bands | [`02-play.html`](prototypes/wireframes/02-play.html) | [`play.html`](prototypes/play.html) |
| Leaderboards | `/leaderboards` | [`pages/leaderboards.md`](pages/leaderboards.md) — this week and last week, the two selectors, your own rank and personal best, the off-page rank case | [`03-leaderboards.html`](prototypes/wireframes/03-leaderboards.html) | [`leaderboards.html`](prototypes/leaderboards.html) |
| Account and profile | `/account` | [`pages/account.md`](pages/account.md) — display name, the anonymous-id state, linking Google or email, get a copy, and the whole delete-my-data flow | [`04-account.html`](prototypes/wireframes/04-account.html) | [`account.html`](prototypes/account.html) |
| Privacy policy | `/privacy` | [`pages/privacy-consent.md`](pages/privacy-consent.md) — the policy page including the `#cookies` section, plus the consent surface below | [`05-privacy.html`](prototypes/wireframes/05-privacy.html) | [`privacy.html`](prototypes/privacy.html) |
| Cookie banner and settings | a surface, not a route | [`pages/privacy-consent.md`](pages/privacy-consent.md) — first visit, after a choice, the settings dialog, and why Reject carries equal weight | [`06-cookie-consent.html`](prototypes/wireframes/06-cookie-consent.html) | [`cookie-settings.html`](prototypes/cookie-settings.html) |
| About | `/about` | [`pages/about-terms.md`](pages/about-terms.md) — studio info and contact, the reading measure, the 404 case | [`07-about.html`](prototypes/wireframes/07-about.html) | [`about.html`](prototypes/about.html) |
| Terms | `/terms` | [`pages/about-terms.md`](pages/about-terms.md) — terms of use with its in-page table of contents | [`08-terms.html`](prototypes/wireframes/08-terms.html) | [`terms.html`](prototypes/terms.html) |

### 1.4 The two reviews

| Document | What it contains |
| --- | --- |
| [`accessibility-report.md`](accessibility-report.md) | **The accessibility audit (SOU-8, M1-10).** WCAG 2.1 AA. How the ratios and the keyboard traversal were produced and how to repeat them, what a static sheet cannot prove, the coverage of what was checked, the contrast and keyboard results, thirteen findings (A-01…A-13) with a fix owner on each, and the verdict against the M1 exit criterion. |
| [`feasibility-review.md`](feasibility-review.md) | **The feasibility review (SOU-9, M1-11).** An independent re-derivation of the ad clearances from source and breakpoint arithmetic, plus buildability with no framework, layout-stability (CLS) risk, token discipline, third-party requests, and the M1 boundary. Verdict: **GO**, no open findings. |

---

## 2. Coverage

Pages down the side; what M1 owed each page across the top.

**What a tick means.** `Wireframe` and `Mockup`: the sheet exists and is linked
above. `Empty` / `Loading` / `Error`: the state is designed and demonstrated on
the page's mockup sheet at least once. `Desktop` / `Mobile`: a full-page render
at 1280 px / 375 px. `Light` / `Dark`: a full-page render in that theme. The
desktop/mobile/light/dark columns are the **full-page** renders, which are
complete; the completeness of the finer **state × width × theme** cross-product
is a separate question and is answered in §3.

| Page | Wireframe | Mockup | Empty | Loading | Error | Desktop 1280 | Mobile 375 | Light | Dark |
| --- | :-: | :-: | :-: | :-: | :-: | :-: | :-: | :-: | :-: |
| Home | ✓ | ✓ | ✓ ᵃ | ✓ ᵃ | ✓ ᵃ | ✓ | ✓ | ✓ | ✓ |
| Play | ✓ ᵇ | ✓ | ✓ ᶜ | ✓ ᵃ | ✓ ᵃ | ✓ | ✓ | ✓ | ✓ |
| Leaderboards | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| Account | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| Privacy | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| Cookie banner + settings | ✓ | ✓ | **reason ᵈ** | ✓ ᵃ | ✓ ᵃ | ✓ | ✓ | ✓ | ✓ |
| About | ✓ | ✓ | ✓ ᵃ | ✓ ᵃ | ✓ ᵃ | ✓ | ✓ | ✓ | ✓ |
| Terms | ✓ | ✓ | ✓ ᵃ | ✓ ᵃ | ✓ ᵃ | ✓ | ✓ | ✓ | ✓ |

- **ᵃ** The state is designed and demonstrated, but **not in every one of the
  four width × theme cells**. The missing cells are listed page by page in §3 and
  are finding **A-08** (Minor, open). Nothing is claimed here that §3 does not
  repeat.
- **ᵇ** The play wireframe carries **three** views, not two: 375, 768 and 1280.
  The 768 px view is the compact-band check that `ad-slots.md` §11 requires.
- **ᶜ** **The game frame has no empty state, by design.** Its four displays are
  ready, loading, error and "JavaScript required" — a frame either mounts a game
  or it does not. The play page's empty state is demonstrated on the **weekly
  leaderboard** block instead ([`play.html` §5](prototypes/play.html#s5)), which
  is the only part of the page that can legitimately have nothing to show.
- **ᵈ** **The cookie settings screen has no empty state, by design, and the sheet
  says so rather than omitting it.** The cookie category list is defined by the
  site and always holds exactly two entries, so an empty settings screen would be
  a fault and not a state. Recorded in
  [`cookie-settings.html` §4](prototypes/cookie-settings.html#s4) and in
  [`components.md`](components.md) §4.4; the audit inspected the claim and
  accepted it (`accessibility-report.md` §3.2, "Not a gap").

**Not a page, but part of the deliverable.** The component library
([`components.md`](components.md) / [`components.html`](prototypes/components.html))
is where the three state patterns and every shared component are designed once,
in all four width × theme cells, for the eight pages above to fill in. It has no
wireframe row because it is not a page — it is what the wireframes' regions get
filled with.

---

## 3. The state × width × theme gap, in full

The acceptance criterion asked for empty / loading / error at **both** widths in
**both** themes. Three pages deliver the complete cross-product; five demonstrate
their state blocks in only some of the four cells. This is the audit's own table
(`accessibility-report.md` §3.2), reproduced so that the gap is visible on the
page the Board approves from, not one link away.

| Page / block | Demonstrated | Missing cells |
| --- | --- | --- |
| Leaderboards | all three states × all four cells, twice (own-rank block and main board) | — |
| Account | all four cells, §2–§5 | — |
| Privacy | all four cells | — |
| Home — grid | L375 empty · D375 error · L1280 loading · D1280 empty + error | no single cell shows all three |
| Home — top-3 strip | L375 empty + loading · D375 error | both 1280 cells |
| Play — frame | L375 all four · D375 all four · L1280 loading + error | dark 1280 |
| Play — weekly board | L375 · D375 · L1280 | dark 1280 |
| About | L375 all three · D375 all three · L1280 | dark 1280 |
| Terms | L375 all three · D375 all three | both 1280 cells |
| Cookie settings | L375 · D1280 | dark 375, light 1280 |

**How serious this is, stated by the auditor and not softened here:** it is a
*demonstration* gap, not a proven defect — state blocks read their colours from
tokens and their geometry is width-driven, so a missing cell is unlikely to hold
a surprise. It is nevertheless recorded as finding **A-08** (Minor, open),
because the criterion asked for the cross-product and the Board asked for gaps to
be visible rather than silent.

---

## 4. The M1 exit criteria

The criteria, verbatim from
[`../plans/soucean-game-plan.md`](../plans/soucean-game-plan.md) §M1:

> The Board approves the mockups. Contrast and keyboard checks pass. Every page
> of §2 has a design.

| # | Criterion | Status | Evidence |
| --- | --- | --- | --- |
| 1 | **Every page of `requirements.md` §2 has a design** | **Met** | §2 above. All eight design surfaces have a design document, a wireframe and a high-fidelity mockup; all eight render at both design widths in both themes (`accessibility-report.md` §3.1: 32 full-page renders, all present and all inspected). |
| 2 | **Contrast and keyboard checks pass** | **Met in substance; not re-verified on the record.** See §4.1. | `accessibility-report.md` §9 + the SOU-22 remediation, merged as commit `55deda2` (PR #18). |
| 3 | **The Board approves the mockups** | **Pending — this Task's own approval is it.** | This document plus [`prototypes/index.html`](prototypes/index.html). |

### 4.1 Criterion 2, in full, because it is the one that needs a decision

**Do not read this row as a plain pass.** The sequence is:

1. **SOU-8 published a FAIL.** `accessibility-report.md` §9 records: contrast
   **PASS** (every pair measured against AA, tightest 3.15:1 against a 3:1
   requirement, no colour has to change); keyboard traversal **PASS** with one
   exception (A-13); not-colour-alone **PASS**; semantics **PASS** with one
   mockup-sheet defect (A-09); live regions **FAIL** (A-03); focus handling
   **FAIL** (A-02). The criterion is a conjunction, so two failing parts failed
   the whole. Four Major findings: A-01, A-13, A-02, A-03.
2. **§9 named exactly three fixes** that clear all four, none of which touches a
   token, a breakpoint or a layout.
3. **SOU-22 applied all three** and is merged to `main` (commit `55deda2`,
   PR #18): the `:not(:has(.state .btn))` guard on the three
   `components.css` §11 rules (A-01 + A-13), `tabindex="-1"` on `<main id="main">`
   in all six page docs and all 48 sheet stand-ins (A-02), and the persistent
   sibling live region replacing `role="status"` inside the `aria-busy` block in
   all 38 loading blocks (A-03).
4. **The audit has not been re-issued.** `accessibility-report.md` is SOU-8's
   document and SOU-22 did not edit it, correctly — a fix Task does not rewrite
   the auditor's verdict. So the published verdict in this repository still reads
   **FAIL**, and no document records a re-verification of the four fixes.

**What that leaves for the Board.** Every defect the criterion failed on has a
merged fix, and the fixes match the remedy the auditor specified. What is missing
is the auditor saying so. The Board can either (a) accept the SOU-22 commit as
the evidence and approve, or (b) ask `qa-engineer` for a short re-verification
pass against A-01, A-13, A-02 and A-03 before approving. **This Task recommends
(b)**, because the one thing `accessibility-report.md` §2.3 is explicit about is
that a static sheet cannot prove announcement behaviour — and A-03 is precisely a
live-region announcement fix. It is a small ask, and it converts an inference
into a record.

Either way, **this Task does not mark criterion 2 passed on its own authority.**

---

## 5. Findings still open

Nothing below is closed here. Each line names the finding, its severity, and the
Task or file that owns the fix, exactly as the source document assigns it.

### 5.1 From the accessibility audit, SOU-8

**Major — remediated, awaiting re-verification** (see §4.1):

| ID | Finding | Fix owner | State |
| --- | --- | --- | --- |
| A-01 | The retry button inside a game card cannot be clicked | M1-04 (`components.css`), with SOU-3 | Fixed in SOU-22 (`55deda2`). Not re-verified by the auditor. |
| A-13 | In the error state the focus ring describes the wrong control | M1-04 (`components.css`), with SOU-3 | Fixed in SOU-22 (`55deda2`). Not re-verified by the auditor. |
| A-02 | `#main` cannot receive the focus the design returns to it | every page doc in `pages/`; the contract in M1-04 | Fixed in SOU-22 (`55deda2`). Not re-verified by the auditor. |
| A-03 | The loading live region is marked so that it will not announce | M1-04 (`components.md` §4.3 and the per-page copies) | Fixed in SOU-22 (`55deda2`). **Not re-verified, and not verifiable from a static sheet** — `accessibility-report.md` §2.3. |

**Minor — open, not gating:**

| ID | Finding | Fix owner |
| --- | --- | --- |
| A-04 | `aria-disabled` buttons look fully available | M1-04 (`components.css` §7, `components.md` §5.5) |
| A-05 | The dark-theme modal backdrop is a white veil | M1-04 (`components.css`) |
| A-06 | Published contrast figures disagree between documents | M1-01 (`DESIGN.md`, the source table) and each page Task for its own §Tương phản table |
| A-07 | The ad-slot label uses an unmeasured pair and breaks the surface rule | M1-02 (`ads.css`, `ad-slots.md`), plus `DESIGN.md` for the table |
| A-08 | State coverage is incomplete on five of eight pages (§3 above) | SOU-3 (home, about, terms), SOU-4 (play), SOU-7 (cookie settings) |
| A-09 | Fifteen heading-level skips in the account **mockup sheet** (the real page HTML is correct) | SOU-6 (M1-08) |

**Advisory — open, below the AA line and recorded as such:**

| ID | Finding | Fix owner |
| --- | --- | --- |
| A-10 | Two elements carry `aria-current="page"` at once on Leaderboards | SOU-5 (M1-07) |
| A-11 | The skip link's focus ring is clipped at the viewport corner | M1-04 (`components.css` §4) |
| A-12 | Checkbox hit target is 20 px against the project's own 44 px rule (WCAG 2.1 AA has no target-size criterion; 2.2 does) | M1-04 (`components.css` §13a) |

**One of these deserves the Board's attention before the others.** The auditor
singled out **A-06**: published contrast figures disagree between documents, and
*"A-06 should be done before the Board relies on any published ratio"*. The
measured ratios in the audit itself are independently recomputed and are sound;
it is the figures quoted in the page documents that disagree. The Board should
not take a ratio from a page document as authoritative until A-06 is closed.

**Nothing in SOU-8 names a fix Task number for the M1-04 and M1-01 owners** —
those findings name a file and a milestone task, not a SOU issue. `GAM-21` raises
the fix Tasks (`accessibility-report.md` §9); as of this submission the only one
raised and merged is SOU-22. **The nine Minor and Advisory findings above have no
Task yet.** That is itself something the Board is approving around, and it is
named here rather than left to be discovered.

### 5.2 From the feasibility review, SOU-9

**No open findings.** §7 of the review records the register as empty after
re-check, and §8 returns a **GO**. One trade-off is accepted and documented
rather than open: `play.css` allows mobile error-state overflow
(`.game-frame.state--error { min-block-size: min-content; }`) as an explicit M1
decision, recorded in the play-page source notes.

### 5.3 Open items that are not findings

These are decisions the specifications deliberately left to the Board or to a
later milestone. They are listed because the Board is signing off the documents
that carry them.

| Item | Where | Who it waits on |
| --- | --- | --- |
| **Mobile carries ads on the play page only.** If a mobile slot is wanted on home or leaderboards, `requirements.md` §5.1 has to name it first — the spec will not add a position that document does not allow. | `ad-slots.md` §11, open item 1 | Board |
| **The destructive-button token role.** | `components.md` §6.1, §9 item 1 | Board |
| **The Vietnamese font check.** The specimen is built (`components.html` §1, the pangram at caption and display size in both themes); the three-platform pass (one Windows, one Apple, one Android) was **not** run, because no browser was available in the authoring environment. Explicitly left open rather than claimed. | `components.md` §8.3, §9 item 2 | `qa-engineer` and M2 |
| **The segmented control vs the two-boards-at-once layout.** Not a conflict today; the first page Task that wants the control to replace a board must amend `wireframes.md` first. | `components.md` §6.3, §9 item 3 | the next page Task |
| **No rendered visual check of the component sheet.** The library was checked statically and structurally; it has not been opened in a browser by its author. Everything in `components.md` §8.2 is for review, not a claim. | `components.md` §8.2 | `qa-engineer` |
| **The ad-slot reference check page is not committed.** `ad-slots.md` §12 ships the runnable demonstration as a fenced block rather than as `prototypes/ad-slots-check.html`, because GAM-23 owned only two files. If the Board would rather have it tracked, say so and it will be added. | `ad-slots.md` §11 | Board |
| **1124 px as a possible future `wide` value.** `ad-slots.md` §4.5 notes that 1124 px would additionally give the in-content banner its full 728 px format in the wide band. Recorded as an improvement to consider, not an objection to the 1024 px decision the Board already made. | `ad-slots.md` §4.5, §11 item 2 | Board, later |

---

## 6. What M1 did not add, and how that was checked

**Confirmed: M1 added no framework, no build step, no runtime dependency and no
package manifest, and no analytics, tracker, ad tag or third-party request.**

The checks below were run in this repository from the repository root, with no
network access. They are written out so the reviewer and the Board can re-run
them rather than take this on trust. The counts are stated **as inherited** — at
`main` commit `cb5c4e9`, before this Task's two files — with this Task's own
contribution noted alongside, so that re-running them after the merge gives a
number you can predict rather than one that looks off by two.

**1. No manifest and no build configuration exists anywhere in the repository.**

```sh
git ls-files
```

The complete tracked file list is **51 files** (53 after this Task):
`.github/CODEOWNERS`, `.github/workflows/ci.yml`, `.gitignore`, `AGENTS.md`,
`CLAUDE.md`, `README.md`, and 45 files under `docs/`. There is **no**
`package.json`, lockfile, `composer.json`, `tsconfig.json`,
`vite`/`webpack`/`rollup`/`next`/`tailwind`/`postcss`/`babel` config, `Makefile`,
`Dockerfile` or `docker-compose` file — none of them, at any path. This is
`AGENTS.md` §1 holding: the stack is chosen in M2 and the repository has no
package manifest yet, deliberately. Everything M1 added is Markdown, HTML and
CSS, and the two files this Task adds are one of each.

**2. No network-capable construct in any prototype or stylesheet, with exactly
one known exception.**

```sh
grep -rniE '<script|<iframe|<img|@font-face|https?://|srcset=|\bsrc=|integrity=|crossorigin' \
  docs/design --include='*.html' --include='*.css'
```

**One hit**, and it is the documented one: `prototypes/components.html:1819`, the
inline `<script>` that opens and closes a real `<dialog>` so the keyboard
behaviour can be tested. It is a few lines of vanilla JavaScript, it loads
nothing, and `feasibility-review.md` §1 inspected and accepted it. Beyond that
line there is **no** `<script>`, `<iframe>`, `<img>`, `@font-face`, `src=`,
`srcset=`, `integrity=`, `crossorigin` or `http(s)://` URL in any of the **18**
prototype HTML files (19 with this Task's `index.html`, which adds no hit) or the
**10** stylesheets.

**3. No ad network, tag manager, analytics or font CDN, by name.**

```sh
grep -rniE 'doubleclick|googlesyndication|adsbygoogle|googletagmanager|gtag\(|gpt\.js|analytics|hotjar|segment\.(io|com)|mixpanel|facebook\.net|fbq\(|plausible|matomo|sentry|fonts\.googleapis|fonts\.gstatic|cdn\.|unpkg|jsdelivr' \
  docs/design --include='*.html' --include='*.css'
```

**No matches.** Not in markup, not in CSS, not in a comment.

**4. Every `url()` in every stylesheet is a local relative `@import`.**

```sh
grep -rn 'url(' docs/design/prototypes --include='*.html' --include='*.css'
```

22 hits, all of the form `@import url('tokens.css')` /
`'base.css'` / `'components.css'` / `'ads.css'` — same directory, no scheme, no
host. No `url()` in a `background`, no font file, nothing that resolves off the
filesystem.

**5. Every stylesheet reference in every prototype is relative and local.**

```sh
grep -rn 'rel="stylesheet"' docs/design/prototypes --include='*.html'
```

**47** `<link rel="stylesheet">` elements across the 18 HTML files (49 across 19
after this Task), every one resolving to `assets/*.css`, `../assets/*.css` or
`wireframe.css` — all tracked in this repository. No scheme, no host, nothing to
fetch.

**Corroboration, independently.** `accessibility-report.md` §3.3 and
`feasibility-review.md` §§1, 5 and 6 reached the same conclusion on their own
passes, by different routes: no analytics or trackers, no framework or runtime
dependency, no web-font CDN, no `fetch`/XHR/websocket/beacon, and no backend or
platform-contract dependency introduced anywhere in the M1 design set.

**The fonts question, explicitly, because it is the usual way a third-party
request sneaks into a design package.** There is no web font. `tokens.css` sets
`--font-sans` to a system stack (`system-ui`, `-apple-system`, `'Segoe UI'`,
`Roboto`, `'Noto Sans'`, …), so nothing is downloaded and there is no font
licence to record. The twelve icons are original SVG geometry drawn by M1-04 on a
24 × 24 grid and **copied** into each sheet rather than referenced across files —
a cross-document `<use href="other.html#id">` would be a request, so every `<use>`
is same-document. **No third-party UI asset or font is used anywhere in M1, so
there is no licence to record.**

---

## 7. How to review this package

For `qa-engineer`, whose job on this Task is to confirm the index is accurate and
that no page or state is missing from it:

1. Open [`prototypes/index.html`](prototypes/index.html) from the filesystem.
   Every link in it is relative; click through them and confirm each one lands
   where its label says. The DevTools Network panel should stay empty apart from
   the local file reads.
2. Check §1.3 against the page list in [`../requirements.md`](../requirements.md)
   §2 — seven routed pages plus the consent banner, and the table should have
   eight rows.
3. Check §2 against the sheets themselves: a tick must correspond to something
   you can see, and a blank must correspond to a written reason.
4. Check §3 against `accessibility-report.md` §3.2. It should be the same table.
5. Check §5 against `accessibility-report.md` §8 and `feasibility-review.md` §7.
   Thirteen findings, four remediated and nine open, and no finding quietly
   dropped.
6. Re-run the five checks in §6 and confirm the counts.

---

## 8. Scope of this Task, and the one thing it changed outside its two files

SOU-10 owns two files: this README and
[`prototypes/index.html`](prototypes/index.html). It assembles; it does not
redesign, and it does not fill a gap in another Task's files.

**The exception, which is on the record rather than quiet.** SOU-10's description
carries a carry-over instruction from the Board of 2026-11-04 to fix two
documentation defects in [`ad-slots.md`](ad-slots.md) §10 — a third file. That is
done in this Task's pull request as a separate commit, because the Board assigned
it here and the alternative was to block the milestone's exit submission on two
documentation lines. The two fixes are:

1. **§10 gains the 1023 px band-edge check.** The verification width table
   checked 375, 768, 900, 1000, 1024 and 1280, which left the **upper** edge of
   the compact band unchecked — 1000 px is the last width *tested*, but 1023 px is
   the last width *in the band*. 1023 is now a seventh row, with its slot
   measurements in the §10.2 table and its own entries in the band checks and the
   frame-geometry line.
2. **The DevTools line in §10.2 is corrected for the rail slot.** It said the
   frame-facing margin reads `24px` "on all five slots". That is wrong twice for
   `ad-rail-rectangle`: `ads.css` sets `margin-inline: 0` on that slot (§5.3), so
   its frame-facing margin reads `0px` and its 32 px clearance comes from the
   play grid's `column-gap`; and the slot exists only at **1024 px and above**, so
   it cannot be inspected at any compact-band width. At most three slots are on
   the page at once, so "all five" could never be observed in one pass either.

No other file outside the two this Task owns is touched.

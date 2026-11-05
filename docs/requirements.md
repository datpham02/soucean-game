# Requirements — Soucean Game

**Status:** Seed (M0), complete. The plan-derived sections are filled from
[`plans/soucean-game-plan.md`](plans/soucean-game-plan.md), which was moved into
this repository by task GAM-20 (deliverable 5). Where this document and the plan
disagree, the plan wins and this document is corrected. Owner: the Board, through
`project-manager`. Last updated: 2026-11-04.

Soucean Game is the studio's multi-game website: one site that lists the studio's
games and hosts them, and the backend behind it. It is a platform project under
ADR-014 of the framework repository `datpham02/game-studio`.

This document says **what** the platform must do. [`architecture.md`](architecture.md)
says **how**. Where the two disagree, this document wins and `architecture.md` is
corrected.

---

## 1. Scope

### In scope

- A public website that lists the studio's games and lets a visitor play one.
- Player identity, so progress and scores belong to someone.
- Leaderboards, granted server-side.
- The platform side of the game platform contract — see
  [`game-platform-contract.md`](game-platform-contract.md).
- Advertising slots on platform pages, within the limits in §5.

### Out of scope

- The games themselves. Each game is its own repository and its own project.
- Payments, until the Board has written a payments ADR and a task explicitly
  allows the code.
- Analytics and any third-party script, until a task and the cookie-consent
  design allow one. See §4.

---

## 2. Pages

From the plan, §2. Routes are **proposed** there; M2 confirms them. Items marked
**[GAM-16]** wait on the game platform contract.

| Page | Route | Content | Acceptance |
| --- | --- | --- | --- |
| Home / game list | `/` | A card per game, built from **each game's manifest** **[GAM-16]**: thumbnail, title, short description, controls, a "Play" button, and this week's top 3 | The list is generated from the manifests at build time. Adding a game is adding its manifest and building — no hand-edited list. |
| Play page | `/games/<game_id>` | The game frame (mounted or iframe **[GAM-16]**), title, controls, a weekly leaderboard beside or below it, and the ad slots of §5 | The game fills its container and letterboxes. The page never overlaps the frame. |
| Leaderboards | `/leaderboards`, `/leaderboards/<game_id>` | This week's board per game, last week's final board, and the player's own rank and personal best | The week starts **Monday 00:00 UTC+7** (GAM-16 recommends this; GAM-16 confirms it). |
| Account and profile | `/account` | Display name (editable, moderated), the anonymous id state, "Link Google or email" to keep scores across devices, sign out, and delete my data | Linking keeps the same player id. Delete removes the scores and the profile. |
| Privacy policy | `/privacy` | What is stored, why, for how long, who processes it (backend, hosting, ad network), the player's rights, and contact | Written with legal input (OQ-039). Covers Decree 13/2023, and GDPR if EU players are expected. |
| About | `/about` | Studio info and contact | Ad networks usually expect this page (OQ-038). |
| Terms | `/terms` | Terms of use | Ad networks usually expect this page (OQ-038). |

Plus one surface that is not a page of its own:

| Surface | Where | Content | Acceptance |
| --- | --- | --- | --- |
| Cookie consent | A banner on first visit, plus a `/privacy#cookies` settings link in the footer | Accept / Reject / Settings | **No ad script loads before consent.** Essential storage only (session, consent choice). "Reject" carries equal weight to "Accept". A certified consent tool may be required for EU/UK visitors (OQ-038). |

That is **seven routed pages and the consent banner** — the eight design surfaces
the M1 wireframe task (GAM-24) covers at desktop and mobile width.

Requirements that apply to **every** page, plan or no plan:

- Semantic HTML. Every interactive control is reachable and operable by keyboard,
  with a visible focus indicator.
- Text and essential UI meet WCAG 2.1 AA contrast.
- The page works without JavaScript for its primary content where that is
  reasonable (a game frame is the obvious exception).
- No layout shift caused by an ad slot: reserve the space.
- Every page a visitor can reach has a Playwright end-to-end test once the stack
  lands (M4 onward).

---

## 3. Player identity

- **No sign-up to play.** A visitor plays immediately; identity is created for
  them, not demanded of them.
- A player signs in before anything is stored against them.
- Session handling, sign-in and sign-out live in server-side code. Paths under
  `AGENTS.md` §4 are security-sensitive.
- A player can see everything stored about them, and can delete their account and
  the data listed in §4.

**Sign-in methods** (plan §3):

| Method | When | Note |
| --- | --- | --- |
| Anonymous sign-in | On first visit, automatically | The player gets an id without typing anything. Scores are tied to it. |
| Link **Google** | Optional, from `/account` | Keeps scores across devices. |
| Link **email (magic link)** | Optional, from `/account` | Same purpose; no password. |

Linking **keeps the same player id** — it upgrades the existing identity rather
than creating a second one, so no score is orphaned.

The backend provider is settled by ADR-021: Laravel issues the player token and
handles account linking. The behaviour above is what the Laravel backend must
deliver once M4 starts (§6).

---

## 4. Data rules

**Minimal by default.** Store only the fields listed in this section. A field that
is not listed here is not stored. Adding a field to this list is a Board decision,
requested through `project-manager`.

### 4.1 Fields

From the plan, §3 ("Data model sketch"). This is the **complete** list. The final
schema follows GAM-16; no table is created before M4 (§6).

**`players`**

| Field | Why it is needed | Retention |
| --- | --- | --- |
| `id` | The auth user id. Ties scores to a player. | Until the player deletes their account. |
| `display_name` | Shown on the leaderboards. Editable, moderated. | Until the player deletes their account. |
| `created_at` | Account age, for moderation and abuse handling. | Until the player deletes their account. |

**`scores`**

| Field | Why it is needed | Retention |
| --- | --- | --- |
| `player_id` | Whose score it is. | Until the player deletes their data. |
| `game_id` | Which game it belongs to. | Until the player deletes their data. |
| `week` | Which weekly board it counts for, and the weekly reset. | Until the player deletes their data. |
| `score` | The ranked value. | Until the player deletes their data. |
| `time_survived_ms` | Plausibility check in `submit_score` (a maximum score for the time survived). | Until the player deletes their data. |
| `seed` | Plausibility check: the run matches a real seed. | Until the player deletes their data. |
| `game_version` | Plausibility check, and invalidating boards after a balance change. | Until the player deletes their data. |
| `created_at` | Rate limiting and abuse handling. | Until the player deletes their data. |

Unique on `(player_id, game_id, week)`, keeping the best score. The weekly board
and the personal best are **views or functions** over these two tables, not
stored copies.

**Explicitly not stored** (plan §3):

- No copy of the player's email. The auth provider holds it, and only if the
  player links an email.
- No IP addresses in any table.
- No analytics or telemetry of any kind.

**Retention periods.** The plan ties retention to deletion by the player, not to
a fixed clock, and names no fixed period for any field. Whether a weekly board
is also pruned after N weeks is open question 3 (§8) and is a Board decision.
Nothing here may be read as permission to keep data after a deletion request.

### 4.2 Rules that do not depend on the plan

- **Server-side access rules are mandatory** for every player-data write and
  read path, and every rule is covered by an automated feature test. A rule
  without a test does not ship.
- **No tracking.** No analytics, no advertising identifiers beyond what the ad
  slot itself requires, no third-party script — unless a task **and** the
  cookie-consent design allow it, and the consent banner reflects it.
- **Cookies**: only what the session needs, until consent says otherwise.
- **Scores, rewards and entitlements are granted server-side**, after a
  server-side check. The client never grants one. A score submitted by a client
  is a request, not a fact.
- **No production player data** is read, exported or copied into a development
  environment, a test fixture, a log or a comment.
- **No secrets** in the repository, and no absolute machine paths.

---

## 5. Advertising rules

- Ad slots appear **only** in the positions this document names. Any other
  position is a defect.
- An ad **never** appears inside a game frame, and never overlaps one. The
  platform adapter gets an ad hook only for a game whose manifest declares an ad
  capability.
- A required minimum margin separates an ad slot from the game frame.
- An ad slot reserves its space so the page does not shift when it fills.
- An ad slot is not focusable ahead of the page's own content and does not trap
  keyboard focus.

### 5.1 Allowed positions

From the plan, §4. **Revenue comes from ads on the website page only, never
inside a game.** Any slot not listed here is a defect.

**Desktop**

| Slot | Position | Size |
| --- | --- | --- |
| Leaderboard banner | Top of the page, above the fold, in the header | 728×90 |
| Sidebar | Beside the game frame, in the right-hand column above the weekly board. **Not sticky.** | 300×250 |
| In-content banner | Below the game info, **never directly under the frame** | Fixed, reserved |

**Mobile**

| Slot | Position |
| --- | --- |
| Banner | Above the frame |
| Banner | After the game info |

No sidebar on mobile.

```text
┌──────────────────────────── header ───────────────────────────┐
│ [ad: leaderboard banner 728×90, top of page, above the fold]   │
├───────────────────────────────────────────────────────────────┤
│                                    ≥ 24 px     ┌────────────┐ │
│  ┌──────────── game frame ─────────┐  margin   │ ad: 300×250│ │
│  │                                 │◄────────►│ sidebar     │ │
│  │   game (letterboxed 16:9)        │           │ (sticky off)│ │
│  │                                 │           └────────────┘ │
│  └─────────────────────────────────┘           weekly board   │
│        ≥ 24 px margin below                                    │
│  controls · how to play · this week's board                    │
│ [ad: in-content banner, below the game info, never directly    │
│  under the frame]                                              │
└───────────────────────────────────────────────────────────────┘
Mobile: no sidebar. One banner above the frame, one after the game info.
```

### 5.2 The margin

**At least 24 px** of empty space between any ad and the game frame — on **every
side**, at **every breakpoint**. More is better; 24 px is the floor, not the
target. No ad is directly adjacent to the game's controls area.

### 5.3 Rules

- An ad never overlaps, covers or sits inside the frame. **No pop-ups, no
  interstitials, no sticky ad over the game.**
- While a game is playing the page does not reflow — ad slots reserve fixed
  sizes — so the frame never jumps.
- Clicks and keyboard input go to the game only while it has focus. This is a
  game-side guarantee **[GAM-16]**.
- Ad scripts load **only after consent**. With consent rejected, a slot shows
  nothing, or a house promotion for another game.
- Ad-network placement policies are checked in OQ-038 before the ads milestone
  (M6).

The ad-slot layout spec — clearance, fixed sizes, no sticky slots, mobile
stacking — is produced by `ui-designer` in task GAM-23 and must honour the
numbers above. Consult `ui-designer` on slot geometry before implementing one.

---

## 6. Environments

| Milestone | Backend | Who may act |
| --- | --- | --- |
| **M0 – M3** | none | agents, in this repository only |
| **M4 onward** | local Docker runtime only (`app`, `mysql`, `reverb`, `test`) | agents, in this repository only |
| **any** | **production** | the **Board Operator** only |

- No production credential is requested, read, stored or used by an agent — this
  covers database admin credentials, backend access tokens, hosting
  tokens, DNS, ad-network accounts and payment keys.
- No deploy to production, no migration against the production database, no
  production secret change, and no dev configuration pointed at production.
- Database changes are versioned migrations in this repository, never a manual
  change in an external dashboard.
- Production changes are requested through `project-manager` and performed by the
  Board Operator. See `docs/architecture/security-architecture.md` §10 in the
  framework repository.

---

## 7. Quality gates

A change is done when:

- the build, lint and unit tests pass;
- server-side access-rule feature tests pass for every protected player-data path it touches;
- a visible change has a Playwright end-to-end test;
- a change to a path in `AGENTS.md` §4 is marked `Security-sensitive: yes`, lists
  its paths, and has Board review in addition to `qa`;
- no field outside §4.1 is stored, and no ad slot sits outside §5.

---

## 8. Open questions

| # | Question | Owner |
| --- | --- | --- |
| 1 | ~~The page list, stored fields, ad positions and ad margin.~~ **Resolved** by the plan import (GAM-20 deliverable 5), 2026-11-04. | — |
| 2 | ~~Sign-in methods.~~ **Resolved** by plan §3: anonymous first, optional Google or email link. ADR-021 fixes the provider direction as Laravel-issued tokens. | — |
| 3 | Retention periods per field. The plan ties retention to player deletion and names no fixed period; whether weekly boards are pruned after N weeks is still open. | Board, via `project-manager` |
| 4 | Routes in §2 are the plan's **proposed** routes. M2 confirms them. | `web-engineer`, at M2 |
| 5 | The weekly reset boundary (Monday 00:00 UTC+7) is GAM-16's recommendation and GAM-16 confirms it. | `game-director`, via `project-manager` |

Upstream open questions the plan depends on — OQ-037 (hosting), OQ-038 (ads and
consent), OQ-039 (legal) — live in the
framework repository's
[`open-questions.md`](https://github.com/datpham02/game-studio/blob/main/docs/architecture/open-questions.md).

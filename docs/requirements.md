# Requirements — Soucean Game

**Status:** Seed (M0). Incomplete — the sections marked **Pending plan import**
below are filled from `docs/plans/soucean-game-plan.md` once that file is copied
into this repository (task GAM-20, deliverable 5). Owner: the Board, through
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

**Pending plan import.** The page list, the route for each page and the
acceptance criteria per page come from the approved plan. The M1 design tasks
treat the site as **eight pages** (task GAM-24, wireframes at desktop and mobile
width); confirm that count and the page names against the plan when it is
imported.

The structure below is the frame they drop into; it is not a substitute for the
plan.

| Page | Purpose | Status |
| --- | --- | --- |
| Home / game list | lists the studio's games | pending plan import |
| Game page | hosts one game in a frame, per the contract | pending plan import |
| Leaderboard | shows server-granted scores | pending plan import |
| Account / profile | the player's own data, and deletion | pending plan import |
| Legal: privacy, cookies, terms | required by §4 | pending plan import |

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

- A player signs in before anything is stored against them.
- Session handling, sign-in and sign-out live in server-side code. Paths under
  `AGENTS.md` §4 are security-sensitive.
- A player can see everything stored about them, and can delete their account and
  the data listed in §4.

Exact sign-in methods: **pending plan import.**

---

## 4. Data rules

**Minimal by default.** Store only the fields listed in this section. A field that
is not listed here is not stored. Adding a field to this list is a Board decision,
requested through `project-manager`.

### 4.1 Fields

**Pending plan import.** The approved plan names the stored fields. Until it is
imported, no field list is authoritative and no table may be created — which is
consistent with §6: there is no backend before M4.

The shape the list must take:

| Table | Field | Why it is needed | Retention |
| --- | --- | --- | --- |
| _(pending plan import)_ | | | |

### 4.2 Rules that do not depend on the plan

- **Row-level security is on** for every table that holds player data, and every
  policy is covered by an automated test. A table without a tested policy does
  not ship.
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

Allowed positions and the exact margin: **pending plan import.** The ad-slot
layout spec — clearance from the game frame, fixed sizes, no sticky slots, mobile
stacking — is produced by `ui-designer` in task GAM-23. Take the numbers from
that spec, and consult `ui-designer` on slot geometry before implementing one.

---

## 6. Environments

| Milestone | Backend | Who may act |
| --- | --- | --- |
| **M0 – M3** | none | agents, in this repository only |
| **M4 onward** | the **dev project only** | agents, via the provider CLI against dev |
| **any** | **production** | the **Board Operator** only |

- No production credential is requested, read, stored or used by an agent — this
  covers database admin and service-role keys, backend access tokens, hosting
  tokens, DNS, ad-network accounts and payment keys.
- No deploy to production, no migration against the production database, no
  production secret change, and no dev configuration pointed at production.
- Database changes are versioned migrations in this repository, never a manual
  change in a provider dashboard.
- Production changes are requested through `project-manager` and performed by the
  Board Operator. See `docs/architecture/security-architecture.md` §10 in the
  framework repository.

---

## 7. Quality gates

A change is done when:

- the build, lint and unit tests pass;
- row-level-security policy tests pass for every table it touches;
- a visible change has a Playwright end-to-end test;
- a change to a path in `AGENTS.md` §4 is marked `Security-sensitive: yes`, lists
  its paths, and has Board review in addition to `qa`;
- no field outside §4.1 is stored, and no ad slot sits outside §5.

---

## 8. Open questions

| # | Question | Owner |
| --- | --- | --- |
| 1 | The page list, stored fields, ad positions and ad margin — all **pending plan import** (GAM-20 deliverable 5). | Board |
| 2 | Sign-in methods. | Board, via `project-manager` |
| 3 | Retention periods per field. | Board, via `project-manager` |

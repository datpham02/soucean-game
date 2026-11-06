# Architecture — Soucean Game

**Status:** M2 frontend baseline chosen. The frontend stack is Astro with static
output, while ADR-021 already fixes the backend direction. Section §2 records the
current frontend decision and backend boundary. The
plan-derived sections are filled from
[`plans/soucean-game-plan.md`](plans/soucean-game-plan.md), moved into this
repository by task GAM-20 (deliverable 5). Owner: the Board, through
`project-manager`. Last updated: 2026-11-05.

This document says **how** the platform is built.
[`requirements.md`](requirements.md) says **what** it must do; where the two
disagree, `requirements.md` wins and this document is corrected.

---

## 1. Shape

Soucean Game is one web application with three concerns:

1. **The site** — pages that list the studio's games, host one in a frame, and
   show leaderboards and the player's own data.
2. **The platform adapter** — the platform side of the game platform contract.
   It is the only thing a game talks to. See
   [`game-platform-contract.md`](game-platform-contract.md).
3. **The backend** — identity, leaderboards and the data-access rules that keep a
   player's data to that player. It does not exist before M4 (§5).

Games are **not** part of this repository. Each game is a separate project with
its own repository, loaded through the contract.

---

## 2. Stack and runtime boundary

Milestone M2 chooses the frontend stack as Astro with static output.
This repository runs a static-first frontend with no client-side JavaScript by
default, and React islands only where future tasks explicitly require them.

### 2.1 Frontend decision (M2)

| Concern | Choice | Why |
| --- | --- | --- |
| Frontend framework | Astro | static-first pages, minimal runtime surface |
| Build output | static HTML/CSS (`dist/`) | no backend dependency in M2 |
| Runtime model | no client-side JavaScript by default | accessibility and performance baseline first |
| Lint/test baseline | build validation via `npm run lint` and `npm test` (both run Astro build in M2 baseline) | keeps CI contract active with approved task dependencies only |
| Interactive strategy (future) | React islands only where needed | constrains JS to explicitly interactive features |

### 2.2 Frontend recommendations from the plan (non-binding beyond chosen stack)

The plan, §3, recorded alternatives and future hosting/ads options. M2 selected
Astro for the current milestone and left hosting and ad-network choices as Board
decisions in later milestones.

| Layer | Recommendation | Alternative |
| --- | --- | --- |
| Frontend | TypeScript, static-first — e.g. **Astro** with small interactive islands, or Vite + React | Next.js (static export) |
| Identity in the browser | Player token flow integrated into frontend pages | — |
| Data access from the browser | Through the Laravel API only | — |
| Server logic | Laravel API endpoints and jobs for identity and score submission | — |
| Hosting (M6) | **Cloudflare Pages**, preview deploys per branch (`UNVERIFIED`, OQ-037) | GitHub Pages (no previews), Netlify, Vercel |
| Ads (M6) | Google AdSense, evaluated against alternatives (`UNVERIFIED`, OQ-038) | Ezoic, Media.net, game-portal networks |
| Tests | Unit and feature tests in Laravel, plus Playwright end-to-end | — |

The reasoning: static pages are fast, help SEO and ease ad approval, and the game
page needs only one interactive island.

### 2.3 Backend decision fixed by ADR-021 (2026-11-05)

| Concern | Choice | Why |
| --- | --- | --- |
| Backend runtime | One Laravel application | single security boundary for validation, policies and rate limits |
| Database | MySQL, reachable only by Laravel | browser never receives any database credential |
| Identity | Laravel-issued player token (for example Sanctum) | no sign-up to play; keeps one player id when linked |
| Realtime | Laravel Reverb | live leaderboards, lobbies, presence, chat and turn-based game updates |
| Schema changes | versioned migrations in this repository | no manual dashboard changes |
| End-to-end tests | Playwright | studio standard |
| Review gate | GitHub pull request + CI | `.github/workflows/ci.yml` |

### 2.4 Local runtime in this repository

The backend is local-first and Docker-based:

- `docker compose` defines `app`, `mysql`, `reverb` and `test`.
- `test` runs automated checks with SQLite in memory.
- Agents run tests with `docker compose run --rm test`.
- No production or preview environment exists yet; hosting is a later Board
  decision and must not be assumed in code or docs.

---

## 3. Data model and server enforcement

The tables and fields are listed in [`requirements.md`](requirements.md) §4.1 —
that list is authoritative and this section does not repeat it. The plan's sketch
is two tables:

- **`players`** — `id` (the auth user id), `display_name`, `created_at`.
- **`scores`** — `player_id`, `game_id`, `week`, `score`, `time_survived_ms`,
  `seed`, `game_version`, `created_at`. **Unique on
  `(player_id, game_id, week)`**, keeping the best score.
- The weekly board and the personal best are **views or functions** over these
  two tables, never stored copies that can drift.

The final schema follows GAM-16. Nothing is created before M4 (§5).

**Score submission.** A client never writes to `scores`. Submission goes through
the Laravel API, which applies *moderate* anti-cheat:

| Check | What it does |
| --- | --- |
| Maximum score for time survived | Rejects a score impossible for the run's duration |
| Rate limit | Caps submissions per player over time |
| Seed, week, duration, game version | Confirms the run is internally consistent and belongs to the week claimed |

Replay verification is explicitly **out of scope**. The plan accepts moderate
anti-cheat over a perfect one.

Rules the model must satisfy when it lands:

- Access rules run in Laravel validation, authorization policies and rate
  limiters. A request failing those checks never reaches a write.
- Every rule has an automated Laravel feature test. A rule without a test does
  not ship.
- Every schema change, including a policy change, is a numbered migration file
  committed here. Migrations are forward-only and reviewed.
- Database credentials stay server-side. The browser gets only the backend URL
  and its own player token.

---

## 4. Security posture

See `docs/architecture/security-architecture.md` §10 (production environments)
and §11 (supply chain) in the framework repository `datpham02/game-studio` —
those are authoritative and are not copied here.

What that means in this repository:

### 4.1 Trust boundary

The browser is untrusted. The platform adapter runs in the page alongside a game
frame, so:

- **a score, reward or entitlement is granted only by server-side code, after a
  server-side check.** A message from a game or a client is a request.
- messages crossing the game frame boundary are validated against the contract —
  origin checked, shape checked, unknown messages dropped.

### 4.2 Web security

| Risk | Control |
| --- | --- |
| XSS | no raw HTML injection; framework escaping; a Content-Security-Policy that names the game frame's origins explicitly |
| CSRF | same-site session cookies and a token on state-changing requests |
| Clickjacking | frame-ancestors in CSP |
| CORS | an explicit allow-list; no wildcard on any authenticated endpoint |
| Secrets | server-side only; nothing secret reaches the client bundle; nothing secret is committed |

### 4.3 Supply chain

- No runtime dependency before M2 (§2), and each one added after that is
  justified in a pull request.
- Lockfile committed; CI installs from the lockfile.
- CI workflows and `CODEOWNERS` are security-sensitive paths — see `AGENTS.md` §4
  and framework §11.

### 4.4 Security-sensitive changes

A change to a path in [`../AGENTS.md`](../AGENTS.md) §4 is marked
`Security-sensitive: yes` in its pull request, lists its paths, and needs Board
review in addition to `qa`.

---

## 5. Environments

| Milestone | Backend | Who may act |
| --- | --- | --- |
| **M0 – M3** | none at all | agents, in this repository only |
| **M4 onward** | local Docker runtime only (`app`, `mysql`, `reverb`, `test`) | agents, in this repository only |
| **any** | **production** | the **Board Operator** only |

From ADR-021 and the plan update:

- The backend in scope is local only in this repository until a hosting decision
  exists.
- Hosting is pending and provider-agnostic; no environment may rely on a specific
  hosting feature before that Board decision.
- Production remains separate and Board-operated only.

- Dev configuration never points at production.
- An agent never asks for, reads, stores or uses a production credential, and
  never reads or exports production player data.
- Production deploys, production migrations, production secrets, DNS, the ad
  network and payments are the Board Operator's, requested through
  `project-manager`.
- Configuration reaches the application through environment variables. `.env*` is
  git-ignored; a committed `.env.example` documents the variable **names** only,
  never a value.

---

## 6. Build and CI

[`.github/workflows/ci.yml`](../.github/workflows/ci.yml) installs, lints and
tests. Nothing else — no deploy step, no publish step, no secret. It skips a step
whose script does not exist yet, so it is green through M0–M1 and starts
enforcing as soon as the M2 stack lands.

A failing check is fixed or reported. It is never disabled, deleted or skipped to
make a build pass.

---

## 7. Repository layout

```
AGENTS.md                  the contract for everyone working here
CLAUDE.md                  points at AGENTS.md
.github/
  CODEOWNERS               review owners, covering the security-sensitive paths
  workflows/ci.yml         install, lint, test
docs/
  requirements.md          what the platform must do
  architecture.md          this file
  game-platform-contract.md  pointer to the framework contract
  plans/                   the approved plan and the M0 checklist
```

Application source layout for M2:

```
src/
  components/              shared shell components (header, footer, skip link)
  layouts/                 Astro page layouts
  pages/                   route entry points (starting with /about)
  styles/                  token/base/component style layers
public/                    static files, if required
```

---

## 8. Decisions

Record every architectural decision here, newest first: the decision, the date,
the alternatives considered and why they lost. Decisions that affect more than
this repository belong in the framework repository as an ADR, and are referenced
from here.

| Date | Decision | Where |
| --- | --- | --- |
| 2026-11-05 | Frontend stack chosen for M2: Astro static output, no client-side JavaScript by default, React islands only for future interactive needs | this document §2.1 |
| 2026-11-05 | ADR-021: backend direction is Laravel + MySQL, with Laravel as the only database client and Reverb for realtime | Board ADR-021 |
| 2026-11-04 | Design and build the website first; hosting, the production backend and the domain come last (M6) | plan §5, Board revision |
| 2026-11-04 | The approved plan and the M0 checklist live in this repository, not the framework | [`plans/`](plans/), task GAM-20 |
| 2026-11-04 | Soucean Game is a platform project | framework `docs/decisions/ADR-014-platform-project-type.md` |

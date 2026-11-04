# Architecture — Soucean Game

**Status:** Seed (M0). The stack is **not chosen** — that happens in M2. Sections
marked **Pending plan import** are filled from `docs/plans/soucean-game-plan.md`
once that file is copied into this repository (task GAM-20, deliverable 5).
Owner: the Board, through `project-manager`. Last updated: 2026-11-04.

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

## 2. Stack

**TBD in M2.**

The web framework, language runtime, build tool, test runner and linter are chosen
in milestone M2 and recorded here, with the reasoning, as the first entry in §8.

Until that entry exists:

- no runtime dependency is added;
- no backend, hosting, analytics or ad-network configuration is added;
- no framework is pinned in documentation, CI or tooling config.

Already fixed, independent of the framework choice:

| Concern | Choice | Why |
| --- | --- | --- |
| Database | Postgres, with row-level security | required by the data rules (`requirements.md` §4) |
| Schema changes | versioned migrations in this repository | no manual dashboard changes |
| End-to-end tests | Playwright | studio standard |
| Review gate | GitHub pull request + CI | `.github/workflows/ci.yml` |

---

## 3. Data model

**Pending plan import.** The tables and fields follow `requirements.md` §4.1,
which is itself pending the plan. Nothing is created before that list is
authoritative and before M4 (§5).

Rules the model must satisfy when it lands:

- Every table that holds player data has **row-level security enabled**, with
  policies that let a player reach their own rows and nothing else.
- Every policy has an automated test. A policy without a test does not ship.
- Every schema change, including a policy change, is a numbered migration file
  committed here. Migrations are forward-only and reviewed.
- Service-role access is used only by server-side code, never by the browser.

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
| **M4 onward** | the **dev project only** | agents, via the provider CLI against dev |
| **any** | **production** | the **Board Operator** only |

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

Application source layout is decided with the stack in M2.

---

## 8. Decisions

Record every architectural decision here, newest first: the decision, the date,
the alternatives considered and why they lost. Decisions that affect more than
this repository belong in the framework repository as an ADR, and are referenced
from here.

| Date | Decision | Where |
| --- | --- | --- |
| _(M2)_ | Web framework and toolchain | pending |
| 2026-11-04 | Soucean Game is a platform project | framework `docs/decisions/ADR-014-platform-project-type.md` |

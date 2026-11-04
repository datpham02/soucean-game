# AGENTS.md — soucean-game

**Status:** Seed (milestone M0). Owner: the Board, through `project-manager`.
Last updated: 2026-11-04.

Soucean Game is the studio's multi-game website: one site that lists the studio's
games and hosts them. It is a **platform project** in the sense of ADR-014 of the
framework repository `datpham02/game-studio`, which means it carries its own
`AGENTS.md`, `docs/requirements.md`, `docs/architecture.md` and a pointer to the
game platform contract.

This file is the single contract for everyone — human or agent — who changes this
repository. [`CLAUDE.md`](CLAUDE.md) only points here; there is one set of rules,
not two.

---

## 1. Stack

**TBD in M2.**

The web framework, and with it the language runtime, the build tool, the test
runner and the linter, is chosen in milestone M2 and recorded in
[`docs/architecture.md`](docs/architecture.md). Until that decision is written
down:

- add **no** runtime dependencies;
- add **no** backend, hosting, analytics or ad-network configuration;
- do not pin a framework in documentation, CI or tooling config, and do not
  "temporarily" scaffold one to try it out on a task branch that will be merged.

The repository therefore has no package manifest yet. That is deliberate.

---

## 2. Commands

The real commands arrive with the stack in M2. The names below are reserved now
so CI, docs and task descriptions can refer to them before they exist. Implement
them as `package.json` scripts, or the chosen stack's equivalent, when the stack
lands.

| Purpose | Command | Available from |
| --- | --- | --- |
| Install dependencies | `npm ci` | M2 |
| Lint | `npm run lint` | M2 |
| Unit tests | `npm test` | M2 |
| Production build | `npm run build` | M2 |
| End-to-end tests (Playwright) | `npm run test:e2e` | M4 |
| Row-level-security policy tests | `npm run test:rls` | M4 |
| Apply migrations — **dev project only** | `npm run db:migrate` | M4 |

CI ([`.github/workflows/ci.yml`](.github/workflows/ci.yml)) runs install, lint and
test, and nothing else. It skips a step that does not exist yet, so it stays green
through M0–M1 and begins enforcing the moment the scripts appear. Never disable,
delete or skip a failing check, test or policy to make a build pass — fix the
cause or report it.

Run the build and the tests before you ask for review, and put the results in the
pull-request comment.

---

## 3. Environments

This is the rule that matters most in this repository.

| Milestone | Backend | Who may act |
| --- | --- | --- |
| **M0 – M3** | **None.** There is no backend, no database and no hosting configuration. | agents, in this repository only |
| **M4 onward** | **Dev project only**, through the backend provider's CLI. | agents, against dev |
| **Any** | **Production** — database, hosting, DNS, ad network, payments. | **the Board Operator, and nobody else** |

Rules that do not change with the milestone:

- **Never** ask for, read, store or use a production credential. This covers
  database admin and service-role keys, backend access tokens, hosting tokens,
  DNS credentials, ad-network accounts and payment keys.
- **Never** deploy to production, apply a migration to the production database,
  change a production secret, or point dev configuration at production.
- **Never** read or export production player data.
- If you find a production credential — in your environment, in a file, or in a
  message — **stop and report it to `project-manager`.** Do not use it, and do
  not copy it anywhere, including into a comment or a log.
- Production changes are requested through `project-manager` and carried out by
  the Board Operator. See `docs/architecture/security-architecture.md` §10 in the
  framework repository.

---

## 4. Security-sensitive paths

A change that touches any of the paths below is **security-sensitive**. Such a
pull request must:

1. say `Security-sensitive: yes` in its description;
2. list the sensitive paths it touches;
3. get **Board review in addition to `qa`**.

Everything else says `Security-sensitive: no`.

> These paths follow the categories in `docs/architecture/security-architecture.md`
> §10.4 of the framework repository. Most of them do not exist yet — they are the
> reserved locations, so the rule is in force before the first line of code lands.
> Confirm the exact list against §10.4 when the stack is chosen in M2.

| Path (planned) | Why it is sensitive |
| --- | --- |
| `supabase/migrations/**`, `db/migrations/**` | schema and row-level-security policy changes |
| `**/policies/**.sql`, `**/*.policy.sql` | row-level-security policies |
| `src/server/auth/**`, `src/lib/auth/**`, `**/auth/**` | authentication and session handling |
| `supabase/functions/**`, `src/server/**` | server functions that grant scores, rewards or entitlements |
| `src/**/leaderboard/**`, `src/**/entitlements/**`, `src/**/rewards/**` | score, reward and entitlement grants |
| `**/payments/**`, `**/billing/**` | payment code |
| `.github/workflows/**`, `.github/CODEOWNERS` | CI and review configuration (supply chain, §11) |
| `**/middleware.*`, `**/*csp*`, `**/*cors*` | CSP, CORS and request middleware |
| `.env.example`, `**/config/secrets*` | secret configuration shape |
| `AGENTS.md`, this section | the rules themselves |

---

## 5. Rules of the repository

### 5.1 Data

- Store only the fields listed in [`docs/requirements.md`](docs/requirements.md).
  Minimal by default; if a field is not listed there, do not store it.
- No analytics, no tracking, no third-party scripts unless a task **and** the
  cookie-consent design allow them.
- Every table that holds player data has row-level security enabled, and its
  policies are covered by tests.
- Database changes are **versioned migrations**, committed to this repository.
  Never a manual change in a provider dashboard.

### 5.2 Scores, rewards and entitlements

Only server-side code, after a server-side check, may grant a score, a reward or
an entitlement. The client never grants anything — it asks, and the server
decides.

### 5.3 Ads

- Ad slots go only where [`docs/requirements.md`](docs/requirements.md) allows
  them, with the required margin from the game frame.
- **Never** inside or overlapping a game frame.
- Never add an ad hook to the platform adapter for a game that declares no ad
  capability in its manifest.

### 5.4 Games

Games live in their own repositories and are not changed from here. If a game
needs something, report it through `project-manager`.

### 5.5 The game platform contract

The contract is owned by the framework repository. Implement the platform side of
it **exactly as written** — see
[`docs/game-platform-contract.md`](docs/game-platform-contract.md). If it is
unclear or missing something, escalate to `project-manager`; do not extend it
here.

### 5.6 Secrets and paths

No secrets in the repository, ever — not in code, not in docs, not in a test
fixture, not in a commit message. No absolute machine paths either; everything is
repository-relative.

---

## 6. Working in this repository

- Work on the task branch; never commit to `main`, never merge, never force-push.
- Small, logical commits. Push the task branch and open a pull request linked to
  the task.
- Add automated tests for the behaviour you change: unit tests, row-level-security
  policy tests, and Playwright end-to-end tests where the change is visible on a
  page.
- Bring a branch up to date with `git fetch origin` then `git merge origin/main`.
  Do not rebase.
- Keep accessibility in the acceptance criteria, not as a follow-up: semantic
  HTML, keyboard reachability, visible focus, sufficient contrast. Consult
  `ui-designer` on layout, ad-slot margins and accessibility.

### Pull-request description

```
Security-sensitive: no        # or: yes — and list the paths below

## What changed
...

## Tests
build: …  lint: …  unit: …  e2e: …
```

---

## 7. Documents

| Document | What it holds |
| --- | --- |
| [`docs/requirements.md`](docs/requirements.md) | pages, data rules, ad rules, environments |
| [`docs/architecture.md`](docs/architecture.md) | structure, stack decision, data model, security posture |
| [`docs/game-platform-contract.md`](docs/game-platform-contract.md) | pointer to the framework contract |
| [`docs/plans/soucean-game-plan.md`](docs/plans/soucean-game-plan.md) | the approved plan: goals, pages, stack recommendations, ad slots, milestones |
| [`docs/plans/soucean-game-m0-setup.md`](docs/plans/soucean-game-m0-setup.md) | the M0 checklist of Board steps |

Authoritative sources that live in the framework repository `datpham02/game-studio`
(`main` branch) and are **not** copied here:

- `docs/decisions/ADR-014-platform-project-type.md` — what a platform repository must have
- `docs/architecture/security-architecture.md` §10 (production) and §11 (supply chain)
- `docs/design/game-platform-contract.md` — the contract itself

---

## 8. Escalation

To `project-manager`:

- a gap or a conflict in the game platform contract (the Board involves `game-director`);
- a privacy, data-retention or ad-placement question (for the Board);
- anything that needs a production change (for the Board Operator).

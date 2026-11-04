# soucean-game

Soucean Game: the studio's multi-game website — one site that lists the studio's
games and hosts them, and the backend behind it.

**Milestone M0 — repository seed.** There is no application code yet, and no
stack: the framework is chosen in M2. See [`AGENTS.md`](AGENTS.md) before you
change anything here.

| Document | What it holds |
| --- | --- |
| [`AGENTS.md`](AGENTS.md) | the contract for everyone working here: stack, commands, environments, security-sensitive paths |
| [`CLAUDE.md`](CLAUDE.md) | points at `AGENTS.md` |
| [`docs/requirements.md`](docs/requirements.md) | what the platform must do — pages, data rules, ad rules, environments |
| [`docs/architecture.md`](docs/architecture.md) | how it is built |
| [`docs/game-platform-contract.md`](docs/game-platform-contract.md) | pointer to the contract, which lives in the framework repository |
| [`docs/plans/soucean-game-plan.md`](docs/plans/soucean-game-plan.md) | the approved plan: goals, pages, stack recommendations, ad slots, milestones |
| [`docs/plans/soucean-game-m0-setup.md`](docs/plans/soucean-game-m0-setup.md) | the M0 checklist of Board steps |

Two rules worth knowing before you read any of it:

- **Production belongs to the Board Operator.** No backend at all in M0–M3; the
  dev project only from M4.
- **No runtime dependencies yet**, and no backend, hosting or analytics
  configuration.

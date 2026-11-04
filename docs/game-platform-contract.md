# Game platform contract

**Status:** Pointer. This repository does **not** define the contract and must not
redefine it. Last updated: 2026-11-04.

## Where the contract lives

The game platform contract is owned by the framework repository
**`datpham02/game-studio`**, branch `main`, at:

```
docs/design/game-platform-contract.md
```

That file is the single source of truth for how a game and the platform talk to
each other — the manifest a game publishes, the messages that cross the game
frame boundary, and the capabilities a game may declare (scores, saves, ads, and
so on).

As of 2026-11-04 that file is a placeholder. It is filled in by task **GAM-16**.
Until it has real content, there is no platform adapter to build.

## How to use it here

- Implement the **platform side** of the contract exactly as written. Do not add
  to it, do not relax it, and do not work around it locally.
- If the contract is unclear, contradicts itself, or is missing something you
  need, **escalate to `project-manager`** (who involves `game-director`). Do not
  extend it yourself, and do not encode a guess in this repository.
- Never copy the contract's text into this repository. A copy drifts. Link to it.
- Never add an ad hook to the adapter for a game whose manifest declares no ad
  capability. See [`requirements.md`](requirements.md) §5.
- Never grant a score, a reward or an entitlement on the client, whatever a
  contract message says. The server checks and the server grants. See
  [`architecture.md`](architecture.md) §4.1.

## Related

- [`requirements.md`](requirements.md) — what the platform must do
- [`architecture.md`](architecture.md) — how it is built, §1 and §4.1
- [`../AGENTS.md`](../AGENTS.md) §5.5 — the rule, in short

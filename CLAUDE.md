# CLAUDE.md

Read [`AGENTS.md`](AGENTS.md). It is the contract for this repository, and it
applies to Claude exactly as it applies to every other agent and to humans.

There is no second set of rules in this file. If you are about to do something
this file does not cover, `AGENTS.md` covers it.

Three things to know before you touch anything:

- **The stack is TBD in M2.** Add no runtime dependencies, and no backend,
  hosting or analytics configuration. See `AGENTS.md` §1.
- **Production belongs to the Board Operator.** No backend at all in M0–M3; the
  dev project only from M4. Never ask for, read or use a production credential.
  See `AGENTS.md` §3.
- **Mark security-sensitive pull requests.** `AGENTS.md` §4 lists the paths.

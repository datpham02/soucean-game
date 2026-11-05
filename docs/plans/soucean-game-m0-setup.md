# Soucean Game — M0 Setup (Board steps)

- **Status:** `PROPOSED` checklist for the Board Operator. Every step is a Board action (ADR-014 §6). Agents do not run these steps.
- **Plan:** [soucean-game-plan.md](soucean-game-plan.md) §5 (M0) and §7.
- **Supabase was dropped on 2026-11-05 (ADR-021): Laravel + MySQL.**
- **No backend, hosting or domain account in M0** (Board, 2026-11-04). Those steps are under "Later steps".
- Steps on external websites describe their UI as of writing (`UNVERIFIED`, OQ-037). Follow the provider's current screens if they differ.

Do the steps in order. After each step, tell the studio session what you did, but **never paste a key or password into chat, an issue or a file**.

## Step 0 — Two decisions

1. **Repository visibility.**
   - GitHub branch protection is not available on a private repository on the free plan. This is `VERIFIED`: the API returned "Upgrade to GitHub Pro or make this repository public" for `datpham02/game-studio` on 2026-11-04.
   - ADR-009 and security-architecture §3 rely on branch protection.
   - Recommendation: make the repository **public**, like `lamplight-vigil`. The repository holds no secrets anyway (ADR-010).
   - The alternative is a private repository with GitHub Pro.
2. **Domain (needed at M6).** Choose between:
   - a subdomain of a domain you already own, for example `game.<your-domain>`;
   - a new domain.

## Step 1 — GitHub repository (Board)

1. Create the repository. The command below is for a public repository; for a private one, use `--private` instead of `--public` and have GitHub Pro.

   ```bash
   gh repo create datpham02/soucean-game --public --description "Soucean Game: the studio's multi-game website" --add-readme
   ```

2. Protect `main` in the same way as `lamplight-vigil`: PR required, 1 approval, no force-push, no deletion. Admins can still bypass, so the Board can merge.

   ```bash
   gh api -X PUT repos/datpham02/soucean-game/branches/main/protection --input - <<'JSON'
   {"required_status_checks":null,"enforce_admins":false,"required_pull_request_reviews":{"required_approving_review_count":1},"restrictions":null,"allow_force_pushes":false,"allow_deletions":false}
   JSON
   ```

3. Clone the repository into the workspace, next to `lamplight-vigil`:

   ```bash
   git clone https://github.com/datpham02/soucean-game.git ~/game-studio-workspace/repos/soucean-game
   ```

4. Set the same credential helper as the game repositories (security-architecture §4):

   ```bash
   git -C ~/game-studio-workspace/repos/soucean-game config credential.helper '!gh auth git-credential'
   ```

## Step 2 — Paperclip project (Board)

1. In the Paperclip UI, create a project named **Soucean Game**:
   - workspace `repoUrl` = `https://github.com/datpham02/soucean-game.git`;
   - `repoRef` = `main`;
   - execution workspace mode: isolated `git_worktree`, as for Lamplight Vigil.
2. Copy the project ID. A studio session then opens a framework PR that changes the registry entry to `status: initializing` and adds `paperclip.projectId`.

## Step 3 — `web-engineer` agent (Board)

Follow [agent-onboarding.md](https://github.com/datpham02/game-studio/blob/main/workflows/agent-onboarding.md):

1. **Create the agent** from `agents/web-engineer/AGENTS.md`, using one of two routes:
   - a Paperclip hire in the UI;
   - an import of only that role (ADR-012), run from the framework repository:

     ```bash
     paperclipai company import . --agents web-engineer
     ```

     Whether a single-role import into the existing company works without touching the other agents is `UNVERIFIED`. Prefer the UI hire if you are unsure.
2. **Configure it** like `gameplay-programmer`:
   - adapter `claude_local`, engine `cli`;
   - the `workspace-write` profile (security-architecture §4), including the task-branch push and merge-from-main rules;
   - reports to `project-manager`.
3. **GitHub token:** a fine-grained token for **`datpham02/soucean-game` only**, with Contents read/write and Pull requests read/write. Bind it as `GH_TOKEN`.
4. **Backend credentials:** none at M0. Keep production credentials unbound to agents at every milestone.
5. Set a budget.
6. Run Paperclip's "Test Environment" for the agent.

## Step 4 — Design tools (producer reviews, Board approves)

M1 (Design) uses design skills from the registry. They are `candidate`s with **no security review yet**, so nobody installs them until this step is done:
1. Create a Paperclip issue for `producer`: run the security review of security-architecture §11.1 on `google-design-md` and on **one** design-taste skill, `impeccable` or `taste-skill`.
   - The review covers origin, install scripts, the full text of skills and hooks, network and telemetry, and advisories, against a pinned commit or version.
   - For `impeccable`, the review also confirms that live mode can be set to `chat` or `off`.
2. Read the result and approve or reject each tool.
3. Install the approved tools yourself, pinned (dependency-system §9), and record the commands.

## Step 5 — Seeding Task (after Steps 1 to 3)

When you say yes, a studio session creates the Paperclip issue "Seed soucean-game repository", assigned to `web-engineer`. The seed contains:
- `AGENTS.md`, `CLAUDE.md` and `docs/requirements.md` / `docs/architecture.md` from the plan;
- **the two plan files moved from the framework:** `docs/plans/soucean-game-plan.md` and this checklist, `docs/plans/soucean-game-m0-setup.md`, copied into the website repository's `docs/plans/` (Board decision 2026-11-04);
- `docs/game-platform-contract.md` as a pointer;
- `.gitignore`, a CI skeleton, lint and tests;
- `.github/CODEOWNERS` for the security-sensitive paths.

`qa` reviews the issue, and you merge. Right after that merge, a framework PR deletes both plan files from `game-studio`. It replaces their links in `docs/README.md` and `projects/project-registry.yaml` with a pointer to the website repository. M1 (Design) starts after the seed and Step 4.

## Later steps (not M0)

The Board decided on 2026-11-04 to design and build first and host last. The backend and hosting steps are kept below for when they are due:
- Laravel + MySQL + Reverb local stack: start of M4.
- Google OAuth client: M4.
- Hosting, domain and ad network: M6.
- Lawyer: before M6.
- Payments: deferred.

See the plan, §7.

### Backend local runtime (Board, start of M4)

1. Confirm the repository has a Docker Compose stack with services:
   - `app` (Laravel application with PHP-FPM);
   - `mysql` (database);
   - `reverb` (WebSocket runtime for realtime features);
   - `test` (automated test runner using SQLite in memory).
2. Confirm agent operation rule:
   - agents run tests with `docker compose run --rm test`;
   - agents do not start long-running containers in their runs.
3. Confirm anonymous identity is Laravel-issued and scoped to player actions:
   - token is created on first score submission;
   - linking Google or email keeps the same player id.
4. Keep all database credentials server-side only. The browser receives the backend URL and its own player token, never a database credential.

### Cloudflare Pages (Board, M6)

1. Create a Cloudflare account. In Workers & Pages, connect GitHub:
   - install the Cloudflare GitHub app for **`soucean-game` only**, not for all repositories;
   - create a Pages project from `datpham02/soucean-game`;
   - production branch `main`;
   - preview deploys for other branches.
2. Environment variables:
   - **Preview:** backend URL and non-secret frontend values only.
   - **Production:** set by the Board at M6 after hosting is chosen.
   - Never put a database credential or server-side secret into frontend variables.
3. Do not create a Cloudflare API token for agents (security-architecture §10.2).

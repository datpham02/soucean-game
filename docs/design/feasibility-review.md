# M1 Feasibility Review — Buildability and Ad-Rule Compliance

Issue: SOU-9  
Milestone: M1 (Design)  
Reviewer role: `web-engineer`  
Date: 2026-11-05

## Scope Reviewed

- Prototype pages: `docs/design/prototypes/*.html` and `docs/design/prototypes/wireframes/*.html`
- Prototype stylesheets: `docs/design/prototypes/assets/*.css`
- Requirements and ad-slot source rules: `docs/requirements.md`, `docs/design/ad-slots.md`, `docs/design/pages/play.md`
- This review is independent of the designer's narrative text and is based on source rules and layout arithmetic from the delivered prototypes.

## 1) Buildability With No Framework / No Runtime Dependency

Verdict: **Pass**

Evidence:
- Prototypes are static HTML with local relative stylesheet references.
- Stylesheets are plain CSS with local `@import` only (`tokens.css`, `base.css`, `components.css`, `ads.css`).
- No package manifest, no build config, no framework runtime hooks, no transpile requirement.
- One inline script exists in `docs/design/prototypes/components.html` for dialog keyboard-behavior demonstration; it is vanilla JS and guards on `typeof dlg.showModal !== 'function'`.

M2 implication:
- No hidden framework/runtime requirement discovered.

## 2) Ad Rules Re-Verified (Independent), Including Required Measurements

Verdict: **Pass**

Measurement method:
- Values below are re-derived from source declarations and breakpoint math (not copied from prior report prose).
- Rendered browser checks listed in `docs/design/pages/play.md` were not run in this run; numbers here are source-arithmetic verification.

### 2.1 Clearance >= 24 px from ad to game frame (recorded values)

| Slot | Band | Clearance to frame | Basis |
| --- | --- | ---: | --- |
| `ad-mobile-top` | mobile | **24 px** | `--space-ad-clearance` on slot margin below frame |
| `ad-header-leaderboard` | compact + wide | **>= 120 px** | slot margin + header/main/title spacing above frame |
| `ad-after-info-rectangle` | mobile | **>= 64 px** | frame->meta spacing + slot margin + meta block height |
| `ad-after-info-rectangle` | compact | **>= 72 px** | frame->meta spacing + slot margin + meta block height |
| `ad-incontent-banner` | compact | **>= 370 px** | >=72 baseline + 250 rectangle + (24 + 24) slot-to-slot spacing |
| `ad-incontent-banner` | wide | **>= 72 px** | frame->meta spacing + slot margin + meta block height |
| `ad-rail-rectangle` | wide | **32 px** | `column-gap: var(--space-2xl)` between frame and rail |

Minimum effective clearance remains **24 px**, and every measured case is at or above the floor.

### 2.2 No overlap / no ad inside frame

- In `play.html`, ad slots are sibling flow elements around the frame, not descendants of it.
- `ads.css` enforces normal-flow ad behavior (`position: static`, `float: none`, `z-index: auto`, `transform: none`).
- No absolute/fixed/sticky/layering behavior is present for ad slots.

### 2.3 No sticky/fixed ad over game

- No ad-slot rule enables sticky/fixed placement.
- Source contract in `ads.css` explicitly forbids those behaviors and prototypes do not override it.

### 2.4 Fixed slot sizes + frame does not shift when slot fills

- Slot block sizes are explicitly reserved by format dimensions.
- `flex: none` prevents shrink in flex-column contexts.
- Slot reservation is present before creative load; no ad-fill-driven frame jump path is introduced by prototype code.

### 2.5 Mobile inventory rule

- Mobile inventory (`<768`) is exactly: one banner above frame (`ad-mobile-top`) and one rectangle after game info (`ad-after-info-rectangle`), with no rail/sidebar slot.

### 2.6 Compact-band geometry re-check (768-1023)

Independent re-check for the band called out as fragile in play-page rules:

- Shell content width across compact band: **704 px -> 959 px**
- Game frame (16:9) across compact band: **704 x 396 -> 959 x 539**
- Leaderboard-format slots in compact band use `min(728px, 100%)`, so:
  - at 768: **704 px** inline size
  - reaches full 728 at viewport **792 px**
  - remains full 728 through 1023

This matches the compact-band constraint and does not require a framework/runtime mechanism.

## 3) Layout Stability (CLS Risk Review)

Verdict: **Pass**

Observed stability controls:
- No `<img>` tags in prototype and wireframe HTML, so no missing intrinsic image dimensions.
- No external web-font CDN usage; typography uses system stack in `tokens.css`.
- Ad slots are pre-reserved (`block-size` + `flex: none`).
- Game frame is pre-reserved by `aspect-ratio: 16 / 9`.
- State regions use reserved sizing patterns (`--state-reserve`) where applicable.

Accepted, already-documented trade-off:
- `play.css` allows mobile error-state overflow (`.game-frame.state--error { min-block-size: min-content; }`) as an explicit, documented M1 decision in play-page source notes.
- This is not a new open finding for this task and does not produce a new owner-task request here.

## 4) Token Discipline

Verdict: **Pass**

- Page stylesheets import shared token/component layers.
- Raw color literals are centralized in `tokens.css`; page stylesheets do not redefine raw color values.
- Page stylesheets are token-based for spacing/type values and do not redefine raw spacing/type scales.

## 5) No Third-Party / External Requests

Verdict: **Pass**

- No external `http(s)` stylesheet/script/image/iframe references detected in prototype and wireframe HTML.
- No analytics/tracker/ad-tag/auth-SDK/consent-SDK signatures found.
- No web-font CDN references detected.

## 6) M1 Boundary (No Backend / No Game-Contract Dependency in Design)

Verdict: **Pass**

- No network API calls (`fetch`, XHR, websocket, beacon) in prototype behavior.
- Forms use in-page mockup targets (`#...`) for static demonstrative flow.
- Game area remains a design placeholder/frame surface; no backend or platform-contract implementation dependency is introduced by this M1 design set.

## 7) Findings Register

No open findings remain after re-check.

## 8) Go / No-Go Verdict

**GO.**

Rationale:
- Every required feasibility criterion is satisfied using static-source implementation constraints.
- Ad rules hold with recorded per-slot clearances at required bands, including compact-band geometry.
- No hidden framework/runtime dependency, no third-party dependency, and no pre-M4 backend dependency was identified.

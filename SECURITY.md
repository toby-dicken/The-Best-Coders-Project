# Security Policy (The-Best-Coders-Project)

## Scope (Sprint 1 MVP)
This repository currently contains a static website (HTML/CSS) and may later include scripts and backend services.
For Sprint 1 we focus on secure collaboration, safe coding patterns, and preventing accidental exposure of secrets.

## Rules (Minimum Security Baseline)
1. Do not commit secrets (API keys, tokens, passwords, private keys, connection strings).
   - Use environment variables and keep example values in documentation only.
2. All changes should be made via Pull Requests (no direct commits to main where possible).
3. External scripts/CDNs must be justified in the PR description and reviewed.
4. If JavaScript is added, avoid unsafe DOM patterns (e.g., innerHTML with untrusted content).
5. Do not place sensitive data in URLs, HTML comments, or client-side storage.

## Reporting a Security Issue
- Create a GitHub Issue with the label `security` and include:
  - Steps to reproduce (if applicable)
  - Impact (what could be harmed/exposed)
  - Suggested fix or mitigation (if known)
- If the issue involves a secret that may already be exposed, notify the repo owner immediately so it can be rotated.

## Handling Secrets (If needed later)
- Use a `.env` file locally and keep it out of git (ensure `.env` is ignored).
- Commit a `.env.example` if configuration is needed, but never real credentials.

## What We Prioritise Before Demo
High priority items to fix before the Sprint 1 demo:
- Any exposed secrets or credentials
- Obvious XSS / unsafe input handling (if scripts/forms are added)
- Unreviewed third-party dependencies that introduce risk

# Security Checklist (Sprint 2 / MMP)
## Authentication & Sessions (SSM)
- [ ] Unauthenticated users are blocked from protected pages (e.g., main page redirects to login).
- [ ] Session ID is regenerated on successful login (session fixation mitigation).
- [ ] Logout fully clears session data and redirects to login.
- [ ] Session cookies are hardened (HttpOnly, SameSite; Secure when HTTPS).
- [ ] Idle timeout is enforced (auto logout after inactivity).

## CSRF Protection
- [ ] Login form includes CSRF token + server verifies it.
- [ ] Registration form includes CSRF token + server verifies it.
- [ ] Daily check-in (or any state-changing POST) includes CSRF token + server verifies it.

## SQL Injection & Database Safety
- [ ] All DB queries with user input use prepared statements (no string concatenation).
- [ ] No hardcoded DB credentials in any PHP file (use db.php + config.php pattern).
- [ ] Sensitive config (config.php / secrets) is not committed (gitignored).
- [ ] Database errors are not shown to users (generic message + server-side logging).

## XSS / Front-end Safety
- [ ] No unsafe DOM injection (avoid innerHTML for dynamic content; prefer textContent).
- [ ] User-controlled output is escaped (PHP: htmlspecialchars).

## Quick Tests Performed (Evidence)
- [ ] Test 1: Incognito access to main page redirects to login (screenshot captured).
- [ ] Test 2: Login with valid account works + session persists (screenshot captured).
- [ ] Test 3: Logout works and blocks return to main via back button (screenshot captured).
- [ ] Test 4: CSRF rejection test (submit without token or with invalid token) (note captured).
- [ ] Test 5: Repo keyword scan done: "pass", "admin", "display_errors", "DB_PASS" (note captured).

# Issue Analysis and Fix

## Root Causes Identified

### Issue 1: Session Cookie Not Being Set (CRITICAL)
**Problem**: Laravel's `StartSession` middleware is not setting the session cookie in response headers.

**Evidence from logs**:
- `"total_cookies":0` - No cookies in response
- `"SESSION COOKIE NOT FOUND IN RESPONSE"` - Cookie missing
- `"session_started":false` - Session is closed by the time we check

**Why it happens**:
1. Laravel's `StartSession` middleware should set the cookie, but it's not happening
2. By the time our debug middleware runs, the session has been closed
3. Our manual cookie set code checked `Session::isStarted()` which was `false`, so it never ran

### Issue 2: Domain Mismatch
**Problem**: Request is to `www.heslb.go.tz` but cookie domain is `heslb.go.tz` (without www).

**Evidence**:
- Request host: `"host":"www.heslb.go.tz"`
- Cookie domain: `"cookie_domain":"heslb.go.tz"`

**Why it matters**:
- Browsers may reject cookies if the domain doesn't match the request host
- Cookie with domain `heslb.go.tz` won't work for `www.heslb.go.tz` unless we use `.heslb.go.tz` (with leading dot)

## Fixes Applied

### Fix 1: Changed Session Check Logic
**Before**:
```php
if (!$this->hasCookie($cookies, $sessionCookieName) && Session::isStarted()) {
    // This never ran because session was closed
}
```

**After**:
```php
$sessionId = Session::getId();
if (!$this->hasCookie($cookies, $sessionCookieName) && !empty($sessionId)) {
    // Now runs even if session is closed, as long as we have a session ID
}
```

**Result**: Manual cookie set will now run because we check for session ID instead of session status.

### Fix 2: Domain Mismatch Handling
**Added logic to**:
1. Detect when request is to a subdomain (e.g., `www.heslb.go.tz`) but domain is base domain (`heslb.go.tz`)
2. Automatically convert to `.heslb.go.tz` (with leading dot) to work for all subdomains
3. Handle cases where no domain is configured but request is to www subdomain

**Result**: Cookie domain will work correctly for both `heslb.go.tz` and `www.heslb.go.tz`.

## Expected Behavior After Fix

### Logs to Look For:

1. **Cookie Set Attempt**:
   ```
   === ATTEMPTING TO MANUALLY SET SESSION COOKIE ===
   ```

2. **Domain Fix** (if applicable):
   ```
   === FIXING DOMAIN FOR SUBDOMAIN ===
   ```

3. **Cookie Set Success**:
   ```
   === MANUAL COOKIE SET SUCCESS ===
   ```

4. **Cookie Details**:
   ```
   === MANUAL COOKIE DETAILS ===
   ```

### What Should Happen:

1. ✅ Session cookie will be manually set if Laravel doesn't set it
2. ✅ Domain will be fixed to work with www subdomain
3. ✅ Cookie will appear in browser DevTools
4. ✅ Sessions will persist across requests

## Testing

After deploying, check logs for:
- `=== MANUAL COOKIE SET SUCCESS ===` - Confirms cookie was set
- `total_cookies_after` should be > 0
- Cookie should appear in browser DevTools under Cookies

## Why Laravel Isn't Setting the Cookie

The root cause of why Laravel's `StartSession` middleware isn't setting the cookie is still unknown, but our workaround ensures cookies are set regardless. Possible reasons:

1. **Session handler issue** - The file session handler might not be triggering cookie set
2. **Middleware order** - Something might be interfering
3. **Response type** - Some response types might not support cookies
4. **Server configuration** - PHP or web server settings might be preventing cookie headers

The manual cookie set ensures functionality regardless of the root cause.

## Next Steps

1. **Deploy the fix** - The updated middleware should now set cookies
2. **Monitor logs** - Check for "MANUAL COOKIE SET SUCCESS" messages
3. **Test in browser** - Verify cookies appear in DevTools
4. **Investigate root cause** (optional) - Once working, investigate why Laravel isn't setting it automatically


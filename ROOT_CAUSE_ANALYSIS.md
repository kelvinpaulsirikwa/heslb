# Root Cause Analysis: Session Cookie Not Being Set

## Problem Identified

From the logs, the critical issue is:

```
"total_cookies":0
"SESSION COOKIE NOT FOUND IN RESPONSE"
```

**The session is being created** (we see `session_id` and `session_started:true`), **but Laravel's `StartSession` middleware is NOT setting the cookie in the response headers.**

## Why This Happens

Laravel's `StartSession` middleware should automatically set the session cookie when:
1. A session is started
2. The session has data (which it does - we see `_token`)
3. The response is being prepared

However, the cookie is not being added. Possible reasons:

### 1. Domain Mismatch Issue
- **Request host**: `www.heslb.go.tz`
- **Cookie domain**: `.heslb.go.tz`
- While the leading dot SHOULD make it work for subdomains, there might be a browser or Laravel validation issue

### 2. Laravel Session Handler Issue
- The session is being saved to files (`/data/heslbgo/private/storage/framework/sessions`)
- But the cookie isn't being set in the response
- This suggests Laravel's cookie setting mechanism isn't being triggered

### 3. Middleware Order Issue
- `StartSession` runs before our debug middleware
- But the cookie should be set by the time we check
- Something might be preventing the cookie from being added

## Solution Implemented

### 1. Manual Cookie Setting (Workaround)
Added logic in `SessionDebugMiddleware` to manually set the session cookie if Laravel doesn't set it automatically.

This ensures:
- The cookie is always set
- We can see in logs if manual setting was needed
- The cookie uses the correct configuration

### 2. Enhanced Logging
Added logging to track:
- Cookies before/after session save
- Manual cookie set attempts
- Cookie details after setting

## Next Steps

1. **Deploy the updated code** - The manual cookie set should fix the immediate issue
2. **Check logs** - Look for "MANUAL COOKIE SET SUCCESS" messages
3. **Test** - Verify cookies are now being set and stored
4. **Investigate root cause** - Once working, we can investigate why Laravel isn't setting it automatically

## Potential Root Cause Fixes

If manual setting works but you want Laravel to set it automatically, try:

### Fix 1: Remove Leading Dot from Domain
```env
SESSION_DOMAIN=heslb.go.tz
```
Instead of `.heslb.go.tz`

### Fix 2: Ensure Session is Saved
Laravel sets the cookie when the session is saved. Make sure:
- Session driver is working (file driver is configured)
- Storage path is writable
- Session save is being called

### Fix 3: Check for Middleware Conflicts
Something might be removing cookies. Check:
- `EncryptCookies` middleware
- `SecureCookies` middleware
- Any custom middleware that modifies responses

## Testing

After deploying, check logs for:
- `=== MANUAL COOKIE SET SUCCESS ===` - Cookie was manually set
- `=== MANUAL COOKIE DETAILS ===` - Cookie parameters
- `total_cookies` should be > 0 after manual set

If you see "MANUAL COOKIE SET SUCCESS", the workaround is working and cookies should now be stored in the browser.


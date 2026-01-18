# Root Cause: 419 Error and Cookie Not Persisting

## The Problem

**Cookies are being set but browsers are rejecting them**, causing:
1. No session cookie in subsequent requests
2. New session created on each request
3. CSRF token mismatch (419 error)

## Root Cause

**Cookie domain exactly matches hostname** - Browsers reject cookies when:
- Cookie domain: `www.heslb.go.tz`
- Request host: `www.heslb.go.tz`
- **These match exactly** → Browser rejects per RFC 6265

## Evidence from Logs

### Cookie Being Set:
```
"MANUAL COOKIE SET SUCCESS"
"cookie_domain":"www.heslb.go.tz"
"cookie_string":"heslb_session=...; domain=www.heslb.go.tz; ..."
```

### Cookie NOT Coming Back:
```
"all_cookies":[]
"session_cookie_exists":false
```

### 419 Error:
```
"CSRF TOKEN MISMATCH"
"session_token":"V1Ng7kkXLEWOFvfPHQ2HCOqhdYczfYfQ18ROhjeQ"
"request_token":"lfVNJOtNytAgXRYpgXqPp4gWOREHuszZeUlMMjUq"
"has_session_cookie":false
```

**What happens:**
1. GET `/login` → Creates session A with token X → Sets cookie
2. Browser rejects cookie (domain matches hostname exactly)
3. POST `/login` → No cookie → Creates NEW session B with token Y
4. Form submits token X (from session A) but session B has token Y
5. **419 Error!**

## The Fix

When cookie domain exactly matches request hostname, use `null` domain (browser will use exact host match).

This has been implemented in `SessionDebugMiddleware.php`.

## Expected Behavior After Fix

- Cookie domain will be `null` when it matches hostname exactly
- Browser will accept and store the cookie
- Cookie will be sent back in subsequent requests
- Same session will be used → CSRF tokens will match
- **419 error will be resolved**

## Testing

After deploying, check logs for:
- `=== DOMAIN MATCHES HOSTNAME: USING NULL ===`
- Cookies should start persisting
- No more 419 errors


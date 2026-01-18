# Cookie Debugging Summary

## Issues Identified & Fixed

### 1. ✅ SameSite Cookie Setting Too Restrictive
- **Problem**: `same_site` was hardcoded to `'strict'` in `config/session.php`
- **Impact**: Browsers may reject cookies with `SameSite=Strict` in certain scenarios (redirects, cross-site navigation)
- **Fix**: Changed to `env('SESSION_SAME_SITE', 'lax')` - defaults to 'lax' which is more permissive
- **Action Required**: Add to `.env`: `SESSION_SAME_SITE=lax` (or `none` if needed)

### 2. ⚠️ Domain with Leading Dot
- **Problem**: Your `.env` has `SESSION_DOMAIN=.heslb.go.tz` (with leading dot)
- **Impact**: Some browsers and cookie parsers may reject cookies with leading dots
- **Recommendation**: Try changing to `SESSION_DOMAIN=heslb.go.tz` (without leading dot)
- **Note**: Leading dot makes cookie available to subdomains, but modern browsers handle this differently

### 3. ✅ SecureCookies Middleware Logic Improved
- **Problem**: Middleware only processed cookies if request was detected as secure OR in local environment
- **Impact**: In production behind a proxy, if request wasn't detected as secure, cookies wouldn't be processed
- **Fix**: Now also processes cookies in production if `APP_URL` starts with `https://`
- **Result**: Cookies will be processed even if proxy doesn't forward secure flag correctly

## Debugging System Added

### Files Created/Modified

1. **`app/Http/Middleware/SessionDebugMiddleware.php`** (NEW)
   - Comprehensive logging for every request
   - Logs session config, request details, cookies, and session status

2. **`app/Http/Middleware/SecureCookies.php`** (MODIFIED)
   - Added detailed logging for cookie processing
   - Improved logic to handle proxy scenarios

3. **`app/Providers/AppServiceProvider.php`** (MODIFIED)
   - Added boot-time session configuration logging

4. **`config/session.php`** (MODIFIED)
   - Changed `same_site` to use environment variable
   - Added comment about domain leading dot issue

5. **`app/Http/Kernel.php`** (MODIFIED)
   - Added `SessionDebugMiddleware` to web middleware group

## What the Logs Will Show

### On Every Request, You'll See:

1. **Session Configuration**
   - All session settings from config
   - Environment variables
   - PHP session status

2. **Request Details**
   - HTTPS detection
   - Headers (X-Forwarded-*)
   - Server variables
   - User agent

3. **Cookies in Request**
   - All cookies sent by browser
   - Whether session cookie exists
   - Cookie values

4. **Cookies in Response**
   - All cookies being sent
   - Session cookie details (if present)
   - Cookie parameters (domain, path, secure, httpOnly, sameSite)

5. **Session Status**
   - Session ID
   - Whether session started
   - All session data

## Quick Checklist

After deploying, check logs for:

- [ ] Is session cookie being created? (Look for "*** SESSION COOKIE ***")
- [ ] Is cookie domain correct? (Should match your domain)
- [ ] Is cookie secure flag true? (Required for HTTPS)
- [ ] Is request detected as secure? (Check "REQUEST DETAILS")
- [ ] Is SecureCookies middleware processing? (Check for "Processing cookies" or "NOT PROCESSING")
- [ ] Any errors or warnings?

## Recommended .env Changes

```env
# Change same_site to lax (more permissive than strict)
SESSION_SAME_SITE=lax

# Try without leading dot if cookies still don't work
SESSION_DOMAIN=heslb.go.tz

# Ensure these are set correctly
SESSION_SECURE_COOKIE=true
SESSION_DRIVER=file
SESSION_LIFETIME=120
```

## Next Steps

1. **Deploy** all changes to live server
2. **Update .env** with recommended changes
3. **Clear config cache**: `php artisan config:clear`
4. **Make a test request** to your site
5. **Check logs immediately**: `tail -f storage/logs/laravel-$(date +%Y-%m-%d).log`
6. **Look for**:
   - Session cookie creation
   - Cookie parameters
   - Any warnings or errors

## Common Issues & Solutions

### Issue: Session cookie not in response
**Check**: 
- Is SecureCookies middleware processing?
- Is request detected as secure?
- Are there any errors in logs?

### Issue: Cookie domain mismatch
**Solution**: 
- Remove leading dot from `SESSION_DOMAIN`
- Ensure domain matches exactly (no www vs non-www mismatch)

### Issue: SameSite=Strict blocking cookies
**Solution**: 
- Change to `SESSION_SAME_SITE=lax` or `none`
- `none` requires `Secure=true`

### Issue: Request not detected as secure
**Solution**: 
- Check `TrustProxies` middleware
- Verify proxy sends `X-Forwarded-Proto: https`
- Check `APP_URL` is set to `https://heslb.go.tz`

## Disabling Debug Logging

Once issue is resolved, you can disable debug middleware by commenting it out in `app/Http/Kernel.php`:

```php
// \App\Http\Middleware\SessionDebugMiddleware::class, // Debug session and cookies
```

Or set `LOG_LEVEL=error` in `.env` to reduce log verbosity.


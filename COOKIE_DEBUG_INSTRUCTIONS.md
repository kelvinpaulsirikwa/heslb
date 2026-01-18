# Cookie Debugging Instructions

## Overview
Comprehensive debugging has been added to track why cookies aren't being stored on the live server.

## What Was Added

### 1. SessionDebugMiddleware
- **Location**: `app/Http/Middleware/SessionDebugMiddleware.php`
- **Purpose**: Logs comprehensive information about sessions and cookies on every request
- **Logs**:
  - Session configuration
  - Request details (HTTPS status, headers, etc.)
  - Existing cookies in request
  - Response cookies being sent
  - Session status

### 2. Enhanced SecureCookies Middleware
- **Location**: `app/Http/Middleware/SecureCookies.php`
- **Changes**: Added detailed logging for cookie processing
- **Logs**: Before/after state of each cookie being processed

### 3. Boot-time Session Configuration Logging
- **Location**: `app/Providers/AppServiceProvider.php`
- **Purpose**: Logs session configuration when application boots
- **Logs**: All session config values and environment variables

### 4. Session Config Changes
- **Location**: `config/session.php`
- **Changes**: 
  - Changed `same_site` from hardcoded `'strict'` to `env('SESSION_SAME_SITE', 'lax')`
  - Added note about domain leading dot issue

## How to Use

### 1. Check Logs
All debug logs are written to: `storage/logs/laravel-YYYY-MM-DD.log`

### 2. View Logs on Server
```bash
# SSH into your server
tail -f storage/logs/laravel-$(date +%Y-%m-%d).log

# Or view last 100 lines
tail -n 100 storage/logs/laravel-$(date +%Y-%m-%d).log

# Search for session debug entries
grep "SESSION" storage/logs/laravel-$(date +%Y-%m-%d).log
```

### 3. What to Look For

#### Session Cookie Not Being Set
Look for:
```
=== SESSION COOKIE NOT FOUND IN RESPONSE ===
```

#### Cookie Configuration Issues
Check:
```
=== SESSION CONFIGURATION ===
```
Verify:
- `cookie_secure` should be `true` for HTTPS
- `cookie_domain` should match your domain (check for leading dot issue)
- `cookie_same_site` should be `lax` or `none` (not `strict`)

#### Request Not Detected as Secure
Check:
```
=== REQUEST DETAILS ===
```
Look for:
- `secure` should be `true`
- `is_https` should be `true`
- `forwarded_proto` should be `https` (if behind proxy)

#### SecureCookies Middleware Not Processing
Check:
```
=== SecureCookies: NOT PROCESSING COOKIES ===
```
This means the middleware condition failed.

## Potential Issues Found

### 1. SameSite='strict' (FIXED)
- **Issue**: Was hardcoded to `'strict'` which is very restrictive
- **Fix**: Changed to use `env('SESSION_SAME_SITE', 'lax')`
- **Action**: Add to `.env`: `SESSION_SAME_SITE=lax` or `SESSION_SAME_SITE=none`

### 2. Domain with Leading Dot
- **Issue**: `.heslb.go.tz` with leading dot may cause browser rejection
- **Current**: `.heslb.go.tz` in your `.env`
- **Try**: Change to `heslb.go.tz` (without leading dot) in `.env`

### 3. SecureCookies Middleware Condition
- **Issue**: Only processes if `$request->secure() || app()->environment('local')`
- **Problem**: If request isn't detected as secure (proxy issue), cookies won't be processed
- **Check logs**: Look for "NOT PROCESSING COOKIES" messages

### 4. TrustProxies Configuration
- **Current**: `protected $proxies = '*'` (trusts all proxies)
- **Check**: Ensure your server/proxy is sending correct `X-Forwarded-*` headers

## Recommended .env Changes

Add these to your `.env` file:

```env
# Session SameSite (lax is recommended, none requires secure=true)
SESSION_SAME_SITE=lax

# Try without leading dot if cookies still don't work
SESSION_DOMAIN=heslb.go.tz
```

## Next Steps

1. **Deploy the changes** to your live server
2. **Clear config cache**: `php artisan config:clear`
3. **Make a request** to your site
4. **Check the logs** immediately after
5. **Look for**:
   - Is the session cookie being created?
   - What are the cookie parameters?
   - Is the request detected as secure?
   - Are there any errors?

## Disabling Debug Middleware (After Debugging)

Once you've identified the issue, you can disable the debug middleware by commenting it out in `app/Http/Kernel.php`:

```php
// \App\Http\Middleware\SessionDebugMiddleware::class, // Debug session and cookies
```

Or remove it entirely if not needed.


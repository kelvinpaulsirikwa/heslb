# Session Lifetime Update

## Change Made
Session lifetime has been updated from **2 hours (120 minutes)** to **24 hours (1440 minutes)**.

## What Was Changed
- `config/session.php`: Default lifetime changed from 120 to 1440 minutes

## Action Required
Update your `.env` file on the live server:

```env
SESSION_LIFETIME=1440
```

## After Updating .env
1. Clear config cache: `php artisan config:clear`
2. Restart your web server/PHP-FPM if needed

## Verification
After updating, check logs to confirm:
- `"session_lifetime":"1440"` in session configuration logs
- Cookie expiration should be 24 hours from when it was set


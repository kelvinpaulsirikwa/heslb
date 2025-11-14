# PowerShell script to update PHP configuration
# Run this script as Administrator

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "PHP Configuration Update Script" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

$phpIniPath = "C:\php-8.4.10\php.ini"
$backupPath = "C:\php-8.4.10\php.ini.backup"

# Check if php.ini exists
if (-not (Test-Path $phpIniPath)) {
    Write-Host "ERROR: PHP configuration file not found at: $phpIniPath" -ForegroundColor Red
    Write-Host "Please check your PHP installation path." -ForegroundColor Red
    exit 1
}

Write-Host "Found PHP configuration file: $phpIniPath" -ForegroundColor Green
Write-Host ""

# Create backup
Write-Host "Creating backup..." -ForegroundColor Yellow
Copy-Item $phpIniPath $backupPath -Force
Write-Host "Backup created: $backupPath" -ForegroundColor Green
Write-Host ""

# Read the current configuration
$content = Get-Content $phpIniPath

# Define the changes to make
$changes = @{
    'upload_max_filesize = 2M' = 'upload_max_filesize = 64M'
    'post_max_size = 8M' = 'post_max_size = 64M'
    'max_execution_time = 30' = 'max_execution_time = 300'
    'memory_limit = 128M' = 'memory_limit = 256M'
    ';upload_max_filesize = 2M' = 'upload_max_filesize = 64M'
    ';post_max_size = 8M' = 'post_max_size = 64M'
    ';max_execution_time = 30' = 'max_execution_time = 300'
    ';memory_limit = 128M' = 'memory_limit = 256M'
}

Write-Host "Applying configuration changes..." -ForegroundColor Yellow

# Apply changes
$updated = $false
$newContent = @()

foreach ($line in $content) {
    $originalLine = $line
    $lineChanged = $false
    
    foreach ($oldValue in $changes.Keys) {
        if ($line -match [regex]::Escape($oldValue)) {
            $line = $line -replace [regex]::Escape($oldValue), $changes[$oldValue]
            $lineChanged = $true
            $updated = $true
            Write-Host "Updated: $originalLine -> $line" -ForegroundColor Green
            break
        }
    }
    
    $newContent += $line
}

if ($updated) {
    # Write the updated content back to the file
    $newContent | Set-Content $phpIniPath -Encoding UTF8
    Write-Host ""
    Write-Host "Configuration updated successfully!" -ForegroundColor Green
    Write-Host ""
    Write-Host "========================================" -ForegroundColor Cyan
    Write-Host "NEXT STEPS" -ForegroundColor Cyan
    Write-Host "========================================" -ForegroundColor Cyan
    Write-Host "1. Restart your web server (Apache/Nginx)" -ForegroundColor White
    Write-Host "2. Or restart your PHP development server" -ForegroundColor White
    Write-Host "3. Test the upload functionality" -ForegroundColor White
    Write-Host ""
    Write-Host "To verify the changes, run:" -ForegroundColor Yellow
    Write-Host "php -r \"echo 'upload_max_filesize: ' . ini_get('upload_max_filesize') . PHP_EOL; echo 'post_max_size: ' . ini_get('post_max_size') . PHP_EOL;\"" -ForegroundColor White
} else {
    Write-Host "No changes were needed or found." -ForegroundColor Yellow
    Write-Host "The configuration might already be correct or use different values." -ForegroundColor Yellow
}

Write-Host ""
Write-Host "Press any key to continue..." -ForegroundColor Gray
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")

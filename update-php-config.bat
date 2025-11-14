@echo off
echo ========================================
echo PHP Configuration Update Script
echo ========================================
echo.
echo This script will help you update your PHP configuration
echo to support 10MB+ file uploads.
echo.
echo Your PHP configuration file is located at:
echo C:\php-8.4.10\php.ini
echo.
echo ========================================
echo BACKUP RECOMMENDATION
echo ========================================
echo Before making changes, please backup your current php.ini:
echo.
echo 1. Copy C:\php-8.4.10\php.ini to C:\php-8.4.10\php.ini.backup
echo.
echo ========================================
echo REQUIRED CHANGES
echo ========================================
echo You need to find and update these lines in your php.ini file:
echo.
echo FIND:    upload_max_filesize = 2M
echo REPLACE: upload_max_filesize = 64M
echo.
echo FIND:    post_max_size = 8M
echo REPLACE: post_max_size = 64M
echo.
echo FIND:    max_execution_time = 30
echo REPLACE: max_execution_time = 300
echo.
echo FIND:    memory_limit = 128M
echo REPLACE: memory_limit = 256M
echo.
echo ========================================
echo STEPS TO FOLLOW
echo ========================================
echo 1. Open C:\php-8.4.10\php.ini in a text editor (as Administrator)
echo 2. Search for each setting above and update the values
echo 3. Save the file
echo 4. Restart your web server (Apache/Nginx) or PHP development server
echo 5. Test the upload functionality
echo.
echo ========================================
echo ALTERNATIVE: MANUAL EDIT
echo ========================================
echo If you prefer, you can manually edit the file:
echo.
echo 1. Press Windows + R
echo 2. Type: notepad C:\php-8.4.10\php.ini
echo 3. Press Ctrl + H to open Find and Replace
echo 4. Replace each setting as shown above
echo 5. Save and restart your server
echo.
pause


<?php
// PHP Configuration Verification Script
// Run this after updating your php.ini file

echo "========================================\n";
echo "PHP Configuration Verification\n";
echo "========================================\n\n";

$required_settings = [
    'upload_max_filesize' => '64M',
    'post_max_size' => '64M',
    'max_execution_time' => '300',
    'memory_limit' => '256M'
];

echo "Current PHP Settings:\n";
echo "---------------------\n";

$all_good = true;

foreach ($required_settings as $setting => $required_value) {
    $current_value = ini_get($setting);
    $status = ($current_value >= $required_value) ? '✅ OK' : '❌ NEEDS UPDATE';
    
    if ($current_value < $required_value) {
        $all_good = false;
    }
    
    echo sprintf("%-20s: %-10s (Required: %s) %s\n", 
        $setting, 
        $current_value, 
        $required_value, 
        $status
    );
}

echo "\n========================================\n";

if ($all_good) {
    echo "🎉 SUCCESS: All settings are configured correctly!\n";
    echo "You can now upload files up to 100MB+ without issues.\n";
} else {
    echo "❌ ISSUE: Some settings need to be updated.\n";
    echo "\nTo fix this:\n";
    echo "1. Edit C:\\php-8.4.10\\php.ini\n";
    echo "2. Update the values shown above\n";
    echo "3. Restart your web server\n";
    echo "4. Run this script again to verify\n";
}

echo "========================================\n";
echo "PHP Version: " . PHP_VERSION . "\n";
echo "Configuration File: " . php_ini_loaded_file() . "\n";
echo "========================================\n";
?>


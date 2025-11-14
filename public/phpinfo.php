<?php
// Simple PHP configuration checker
// Access this file at: http://your-domain/phpinfo.php
// Delete this file after checking your settings for security

echo "<h2>PHP Configuration Check</h2>";
echo "<p>This file shows your current PHP settings for file uploads.</p>";
echo "<p><strong>Delete this file after checking for security reasons.</strong></p>";

echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
echo "<tr><th style='padding: 8px; background: #f0f0f0;'>Setting</th><th style='padding: 8px; background: #f0f0f0;'>Current Value</th><th style='padding: 8px; background: #f0f0f0;'>Recommended</th></tr>";

$settings = [
    'upload_max_filesize' => '64M',
    'post_max_size' => '64M',
    'max_execution_time' => '300',
    'max_input_time' => '300',
    'memory_limit' => '256M'
];

foreach ($settings as $setting => $recommended) {
    $current = ini_get($setting);
    $status = ($current >= $recommended) ? '✅ OK' : '❌ Needs Update';
    
    echo "<tr>";
    echo "<td style='padding: 8px;'><strong>$setting</strong></td>";
    echo "<td style='padding: 8px;'>$current</td>";
    echo "<td style='padding: 8px;'>$recommended ($status)</td>";
    echo "</tr>";
}

echo "</table>";

echo "<h3>Instructions:</h3>";
echo "<ol>";
echo "<li>If any setting shows 'Needs Update', you need to modify your php.ini file</li>";
echo "<li>See PHP_CONFIGURATION_GUIDE.md for detailed instructions</li>";
echo "<li>After making changes, restart your web server</li>";
echo "<li>Delete this file for security</li>";
echo "</ol>";
?>

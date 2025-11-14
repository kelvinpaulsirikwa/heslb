<?php
// Quick PHP configuration test
echo "<h2>Current PHP Upload Settings:</h2>";
echo "<p><strong>upload_max_filesize:</strong> " . ini_get('upload_max_filesize') . "</p>";
echo "<p><strong>post_max_size:</strong> " . ini_get('post_max_size') . "</p>";
echo "<p><strong>max_execution_time:</strong> " . ini_get('max_execution_time') . "</p>";
echo "<p><strong>memory_limit:</strong> " . ini_get('memory_limit') . "</p>";

echo "<h3>Recommended Settings for 5MB uploads:</h3>";
echo "<p>upload_max_filesize = 5M (or higher)</p>";
echo "<p>post_max_size = 10M (or higher)</p>";
echo "<p>max_execution_time = 300</p>";
echo "<p>memory_limit = 256M</p>";

echo "<p><strong>Note:</strong> Delete this file after checking for security reasons.</p>";
?>

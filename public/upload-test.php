<?php
// Upload Test Script - Delete after testing for security
// Access at: http://your-domain/upload-test.php

echo "<h2>File Upload Configuration Test</h2>";
echo "<p><strong>Delete this file after testing for security reasons.</strong></p>";

echo "<h3>Current PHP Settings:</h3>";
echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
echo "<tr><th style='padding: 8px; background: #f0f0f0;'>Setting</th><th style='padding: 8px; background: #f0f0f0;'>Current Value</th><th style='padding: 8px; background: #f0f0f0;'>Status</th></tr>";

$settings = [
    'upload_max_filesize' => '64M',
    'post_max_size' => '64M',
    'max_execution_time' => '300',
    'max_input_time' => '300',
    'memory_limit' => '256M',
    'max_file_uploads' => '20'
];

foreach ($settings as $setting => $recommended) {
    $current = ini_get($setting);
    $status = ($current >= $recommended) ? '✅ OK' : '❌ Needs Update';
    
    echo "<tr>";
    echo "<td style='padding: 8px;'><strong>$setting</strong></td>";
    echo "<td style='padding: 8px;'>$current</td>";
    echo "<td style='padding: 8px;'>$status</td>";
    echo "</tr>";
}

echo "</table>";

echo "<h3>Upload Test Form:</h3>";
echo "<form method='post' enctype='multipart/form-data'>";
echo "<p>Select a file to test upload (up to 10MB):</p>";
echo "<input type='file' name='test_file' accept='.pdf,.doc,.docx' required>";
echo "<br><br>";
echo "<input type='submit' name='test_upload' value='Test Upload'>";
echo "</form>";

if (isset($_POST['test_upload']) && isset($_FILES['test_file'])) {
    $file = $_FILES['test_file'];
    
    echo "<h3>Upload Test Results:</h3>";
    echo "<p><strong>File Name:</strong> " . $file['name'] . "</p>";
    echo "<p><strong>File Size:</strong> " . number_format($file['size'] / 1024 / 1024, 2) . " MB</p>";
    echo "<p><strong>File Type:</strong> " . $file['type'] . "</p>";
    echo "<p><strong>Upload Status:</strong> ";
    
    if ($file['error'] === UPLOAD_ERR_OK) {
        echo "✅ Upload Successful!</p>";
        echo "<p><strong>Note:</strong> File was not actually saved - this is just a test.</p>";
    } else {
        echo "❌ Upload Failed</p>";
        echo "<p><strong>Error Code:</strong> " . $file['error'] . "</p>";
        
        $error_messages = [
            UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize',
            UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE',
            UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
            UPLOAD_ERR_NO_FILE => 'No file was uploaded',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
            UPLOAD_ERR_EXTENSION => 'File upload stopped by extension'
        ];
        
        if (isset($error_messages[$file['error']])) {
            echo "<p><strong>Error Message:</strong> " . $error_messages[$file['error']] . "</p>";
        }
    }
}

echo "<h3>Instructions:</h3>";
echo "<ol>";
echo "<li>Check if all settings show '✅ OK'</li>";
echo "<li>Try uploading a PDF file up to 10MB</li>";
echo "<li>If upload fails, check your server's php.ini file</li>";
echo "<li>Delete this file after testing for security</li>";
echo "</ol>";
?>

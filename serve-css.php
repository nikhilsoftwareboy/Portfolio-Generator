<?php
// Disable caching for this file
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
header('Content-Type: text/css');

// Get the CSS file path
$css_file = 'css/style.css';

// Output the CSS content with a timestamp comment
echo "/* Version: " . time() . " */\n";
echo file_get_contents($css_file);
?> 
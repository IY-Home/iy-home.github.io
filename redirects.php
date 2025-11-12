<?php
// redirects.php
header('Content-Type: text/html; charset=utf-8');

// Get the app name from the query parameter
$appName = isset($_GET['appname']) ? $_GET['appname'] : '';

// Security: Sanitize the input
$appName = preg_replace('/[^a-z0-9]/', '', strtolower($appName));

// Define all apps and their redirect URLs
$apps = [
    'filemanager' => '/apps/filemanager/index.php',
    'photos' => '/apps/gallery/photos.php',
    'servermanager' => '/admin/server-manager.php',
    'settings' => '/settings/general.php',
    'aboutserver' => '/info/about.php',
];

// Check if the requested app exists and redirect
if (array_key_exists($appName, $apps)) {
    header('Location: ' . $apps[$appName]);
} else {
    // If no matching app found, redirect to home page or show error
    header('Location: /index.php?error=app_not_found&requested=' . urlencode($appName));
}
exit();
?> 
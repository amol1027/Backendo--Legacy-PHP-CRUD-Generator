<?php
// Backendo: Legacy PHP CRUD Generator
// Main entry point for the application

// Start session for form data persistence
session_start();

// Include configuration and helper functions
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Define the current step (default to step 1)
$current_step = isset($_GET['step']) ? (int)$_GET['step'] : 1;

// Process form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission based on current step
    switch ($current_step) {
        case 1: // Project Setup
            $_SESSION['project'] = [
                'name' => sanitize_input($_POST['project_name']),
                'author' => sanitize_input($_POST['author_name']),
                'base_url' => sanitize_input($_POST['base_url'])
            ];
            // Move to next step
            header('Location: index.php?step=2');
            exit;
            
        case 2: // Define Tables
            // Process table definitions (handled by AJAX in main.js)
            // This is just a fallback for non-JS browsers
            if (isset($_POST['tables_json'])) {
                $_SESSION['tables'] = json_decode($_POST['tables_json'], true);
            }
            header('Location: index.php?step=3');
            exit;
            
        case 3: // Authentication Options
            $_SESSION['auth'] = [
                'enabled' => isset($_POST['add_login']),
                'roles' => isset($_POST['roles']) ? $_POST['roles'] : ['admin']
            ];
            header('Location: index.php?step=4');
            exit;
            
        case 4: // Select Features
            $_SESSION['features'] = [
                'crud' => isset($_POST['feature_crud']),
                'export_csv' => isset($_POST['feature_export']),
                'search' => isset($_POST['feature_search']),
                'pagination' => isset($_POST['feature_pagination']),
                'api' => isset($_POST['feature_api'])
            ];
            header('Location: index.php?step=5');
            exit;
            
        case 5: // Output Options
            $_SESSION['output'] = [
                'download' => isset($_POST['output_download']),
                'deploy' => isset($_POST['output_deploy']),
                'github' => isset($_POST['output_github'])
            ];
            
            // Set project name for file naming
            $_SESSION['project_name'] = $_SESSION['project']['name'];
            
            // Redirect to export script
            header('Location: generator/export_simple.php');
            exit;
    }
}

// Include header
require_once 'templates/header.php';

// Check if this is a download page request or generate action
if (isset($_GET['page']) && $_GET['page'] === 'download') {
    require_once 'templates/pages/download.php';
} elseif (isset($_GET['action']) && $_GET['action'] === 'generate') {
    // Process output options if provided
    if (isset($_POST['output_options'])) {
        $_SESSION['output_options'] = json_decode($_POST['output_options'], true);
    }
    
    // Redirect to export script
    header('Location: generator/export_simple.php');
    exit;
} else {
    // Include step template based on current step
    switch ($current_step) {
        case 1:
            require_once 'templates/pages/step1_project_setup.php';
            break;
        case 2:
            require_once 'templates/pages/step2_define_tables.php';
            break;
        case 3:
            require_once 'templates/pages/step3_auth_options.php';
            break;
        case 4:
            require_once 'templates/pages/step4_select_features.php';
            break;
        case 5:
            require_once 'templates/pages/step5_generate.php';
            break;
        default:
            require_once 'templates/pages/step1_project_setup.php';
            break;
    }
}

// Include footer
require_once 'templates/footer.php';
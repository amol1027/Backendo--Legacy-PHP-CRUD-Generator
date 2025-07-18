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
                'soft_delete' => isset($_POST['feature_soft_delete'])
            ];
            header('Location: index.php?step=5');
            exit;
            
        case 5: // Generate Project
            // This will be handled by the generator scripts
            // Redirect to the download page after generation
            header('Location: generator/export.php');
            exit;
    }
}

// Include header
include 'templates/header.php';

// Include the appropriate step template
switch ($current_step) {
    case 1:
        include 'templates/pages/step1_project_setup.php';
        break;
    case 2:
        include 'templates/pages/step2_define_tables.php';
        break;
    case 3:
        include 'templates/pages/step3_auth_options.php';
        break;
    case 4:
        include 'templates/pages/step4_select_features.php';
        break;
    case 5:
        include 'templates/pages/step5_generate.php';
        break;
    default:
        // Invalid step, redirect to step 1
        header('Location: index.php?step=1');
        exit;
}

// Include footer
include 'templates/footer.php';
?>
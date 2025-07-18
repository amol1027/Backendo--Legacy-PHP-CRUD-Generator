<?php
// Test script to verify project generation and download functionality

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start session
session_start();

// Set up test session data
$_SESSION['project_name'] = 'TestProject';
$_SESSION['project'] = [
    'name' => 'TestProject',
    'author' => 'Test User',
    'base_url' => 'localhost/testproject'
];

// Create a simple test table
$_SESSION['tables'] = [
    [
        'name' => 'users',
        'fields' => [
            [
                'name' => 'id',
                'type' => 'int',
                'length' => 11,
                'default' => '',
                'nullable' => false,
                'constraints' => ['primary']
            ],
            [
                'name' => 'name',
                'type' => 'varchar',
                'length' => 255,
                'default' => '',
                'nullable' => false,
                'constraints' => []
            ],
            [
                'name' => 'email',
                'type' => 'varchar',
                'length' => 255,
                'default' => '',
                'nullable' => false,
                'constraints' => ['unique']
            ]
        ]
    ]
];

// Set authentication options
$_SESSION['auth'] = [
    'enabled' => true,
    'roles' => ['admin', 'user']
];

// Set features
$_SESSION['features'] = [
    'crud' => true,
    'export_csv' => true,
    'search' => true,
    'soft_delete' => false,
    'tailwind' => false,
    'bootstrap' => true
];

// Set output options
$_POST['output_options'] = json_encode([
    'include_readme' => true,
    'include_sample_data' => true,
    'minify_output' => false
]);

// Instead of redirecting, let's create a simple form that submits to the export.php script
?>
<!DOCTYPE html>
<html>
<head>
    <title>Test Project Generation</title>
    <link rel="stylesheet" href="assets/css/main.css">
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h2>Test Project Generation</h2>
            </div>
            <div class="card-body">
                <p>Session data has been set up for a test project. Click the button below to generate the project.</p>
                
                <pre><?php print_r($_SESSION); ?></pre>
                
                <form action="generator/export.php" method="post">
                    <input type="hidden" name="output_options" value='<?php echo json_encode(["include_readme" => true, "include_sample_data" => true, "minify_output" => false]); ?>'>
                    <button type="submit" class="btn btn-primary">Generate Project</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
<?php
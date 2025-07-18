<?php
/**
 * Backendo: Legacy PHP CRUD Generator
 * Export Generator
 */

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Create a log file for debugging
function log_debug($message) {
    $log_file = __DIR__ . '/../debug.log';
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($log_file, "[$timestamp] $message\n", FILE_APPEND);
}

log_debug('Export script started');

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

log_debug('Session status: ' . session_status());

// Check if session data exists
if (!isset($_SESSION['project_name']) || !isset($_SESSION['tables']) || empty($_SESSION['tables'])) {
    log_debug('Session data missing, redirecting to index.php');
    header('Location: index.php');
    exit;
}

log_debug('Session data found, proceeding with export');
log_debug('Project name: ' . $_SESSION['project_name']);
log_debug('Tables count: ' . count($_SESSION['tables']));


// Create temporary directory for project files
$temp_dir = __DIR__ . '/../temp/' . time();
if (!file_exists($temp_dir)) {
    mkdir($temp_dir, 0777, true);
}

// Create project directory structure
mkdir($temp_dir . '/config', 0777, true);
mkdir($temp_dir . '/models', 0777, true);
mkdir($temp_dir . '/controllers', 0777, true);
mkdir($temp_dir . '/views', 0777, true);
mkdir($temp_dir . '/assets/css', 0777, true);
mkdir($temp_dir . '/assets/js', 0777, true);
mkdir($temp_dir . '/assets/img', 0777, true);
mkdir($temp_dir . '/includes', 0777, true);

// Include required files
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once 'model.php';
require_once 'controller.php';
require_once 'view.php';

// Generate config.php
$config_content = "<?php\n";
$config_content .= "// Project: {$_SESSION['project_name']}\n";
$config_content .= "// Author: {$_SESSION['author']}\n";
$config_content .= "// Generated: " . date('Y-m-d H:i:s') . "\n\n";
$config_content .= "// Database configuration\n";
$config_content .= "define('DB_HOST', 'localhost');\n";
$config_content .= "define('DB_USER', 'root');\n";
$config_content .= "define('DB_PASS', '');\n";
$config_content .= "define('DB_NAME', '{$_SESSION['project_name']}');\n\n";
$config_content .= "// Base URL\n";
$config_content .= "define('BASE_URL', '{$_SESSION['base_url']}');\n";

// Add session timeout if authentication is enabled
if (isset($_SESSION['auth']) && $_SESSION['auth']['enabled']) {
    $config_content .= "\n// Authentication settings\n";
    $config_content .= "define('SESSION_TIMEOUT', 1800); // 30 minutes\n";
}

file_put_contents($temp_dir . '/config/config.php', $config_content);

// Generate db.php
$db_content = "<?php\n";
$db_content .= "require_once 'config.php';\n\n";
$db_content .= "/**\n";
$db_content .= " * Get database connection\n";
$db_content .= " * @return PDO Database connection\n";
$db_content .= " */\n";
$db_content .= "function db_connect() {\n";
$db_content .= "    try {\n";
$db_content .= "        \$dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';\n";
$db_content .= "        \$options = [\n";
$db_content .= "            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,\n";
$db_content .= "            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,\n";
$db_content .= "            PDO::ATTR_EMULATE_PREPARES => false,\n";
$db_content .= "        ];\n";
$db_content .= "        return new PDO(\$dsn, DB_USER, DB_PASS, \$options);\n";
$db_content .= "    } catch (PDOException \$e) {\n";
$db_content .= "        die('Database connection failed: ' . \$e->getMessage());\n";
$db_content .= "    }\n";
$db_content .= "}\n\n";
$db_content .= "/**\n";
$db_content .= " * Execute a database query\n";
$db_content .= " * @param PDO \$db Database connection\n";
$db_content .= " * @param string \$sql SQL query\n";
$db_content .= " * @param array \$params Parameters for prepared statement\n";
$db_content .= " * @return PDOStatement Query result\n";
$db_content .= " */\n";
$db_content .= "function db_query(\$db, \$sql, \$params = []) {\n";
$db_content .= "    \$stmt = \$db->prepare(\$sql);\n";
$db_content .= "    \$stmt->execute(\$params);\n";
$db_content .= "    return \$stmt;\n";
$db_content .= "}\n";

file_put_contents($temp_dir . '/config/db.php', $db_content);

// Generate functions.php
$functions_content = "<?php\n";
$functions_content .= "/**\n";
$functions_content .= " * Sanitize user input\n";
$functions_content .= " * @param string \$input User input\n";
$functions_content .= " * @return string Sanitized input\n";
$functions_content .= " */\n";
$functions_content .= "function sanitize_input(\$input) {\n";
$functions_content .= "    return htmlspecialchars(trim(\$input), ENT_QUOTES, 'UTF-8');\n";
$functions_content .= "}\n\n";

$functions_content .= "/**\n";
$functions_content .= " * Generate a random string\n";
$functions_content .= " * @param int \$length Length of the string\n";
$functions_content .= " * @return string Random string\n";
$functions_content .= " */\n";
$functions_content .= "function generate_random_string(\$length = 10) {\n";
$functions_content .= "    \$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';\n";
$functions_content .= "    \$random_string = '';\n";
$functions_content .= "    for (\$i = 0; \$i < \$length; \$i++) {\n";
$functions_content .= "        \$random_string .= \$characters[rand(0, strlen(\$characters) - 1)];\n";
$functions_content .= "    }\n";
$functions_content .= "    return \$random_string;\n";
$functions_content .= "}\n";

file_put_contents($temp_dir . '/config/functions.php', $functions_content);

// Generate header.php
$header_content = "<?php\n";
$header_content .= "// Start session if not already started\n";
$header_content .= "if (session_status() === PHP_SESSION_NONE) {\n";
$header_content .= "    session_start();\n";
$header_content .= "}\n\n";
$header_content .= "// Check session timeout if user is logged in\n";
if (isset($_SESSION['auth']) && $_SESSION['auth']['enabled']) {
    $header_content .= "if (isset(\$_SESSION['user_id']) && isset(\$_SESSION['last_activity'])) {\n";
    $header_content .= "    if (time() - \$_SESSION['last_activity'] > SESSION_TIMEOUT) {\n";
    $header_content .= "        // Session expired, log out user\n";
    $header_content .= "        session_unset();\n";
    $header_content .= "        session_destroy();\n";
    $header_content .= "        header('Location: index.php?page=auth&action=login&error=session_timeout');\n";
    $header_content .= "        exit;\n";
    $header_content .= "    }\n";
    $header_content .= "    // Update last activity time\n";
    $header_content .= "    \$_SESSION['last_activity'] = time();\n";
    $header_content .= "}\n\n";
}

$header_content .= "// Include required files\n";
$header_content .= "require_once 'config/config.php';\n";
$header_content .= "require_once 'config/db.php';\n";
$header_content .= "require_once 'config/functions.php';\n";
$header_content .= "?>\n";
$header_content .= "<!DOCTYPE html>\n";
$header_content .= "<html lang=\"en\">\n";
$header_content .= "<head>\n";
$header_content .= "    <meta charset=\"UTF-8\">\n";
$header_content .= "    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\n";
$header_content .= "    <title><?php echo '{$_SESSION['project_name']}'; ?></title>\n";

// Add CSS based on selected framework
if (isset($_SESSION['features']['tailwind']) && $_SESSION['features']['tailwind']) {
    $header_content .= "    <script src=\"https://cdn.tailwindcss.com\"></script>\n";
} else {
    $header_content .= "    <link href=\"https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css\" rel=\"stylesheet\">\n";
}

$header_content .= "    <link rel=\"stylesheet\" href=\"assets/css/style.css\">\n";
$header_content .= "</head>\n";

// Add body class based on selected framework
if (isset($_SESSION['features']['tailwind']) && $_SESSION['features']['tailwind']) {
    $header_content .= "<body class=\"bg-gray-100 min-h-screen\">\n";
    $header_content .= "    <header class=\"bg-white shadow-md\">\n";
    $header_content .= "        <nav class=\"container mx-auto px-4 py-4 flex justify-between items-center\">\n";
    $header_content .= "            <a href=\"index.php\" class=\"text-xl font-bold text-gray-800\"><?php echo '{$_SESSION['project_name']}'; ?></a>\n";
    $header_content .= "            <div class=\"space-x-4\">\n";
    
    // Add authentication links if enabled
    if (isset($_SESSION['auth']) && $_SESSION['auth']['enabled']) {
        $header_content .= "                <?php if (isset(\$_SESSION['user_id'])): ?>\n";
        $header_content .= "                    <a href=\"index.php?page=profile\" class=\"text-gray-600 hover:text-gray-900\">Profile</a>\n";
        $header_content .= "                    <a href=\"index.php?page=auth&action=logout\" class=\"text-gray-600 hover:text-gray-900\">Logout</a>\n";
        $header_content .= "                <?php else: ?>\n";
        $header_content .= "                    <a href=\"index.php?page=auth&action=login\" class=\"text-gray-600 hover:text-gray-900\">Login</a>\n";
        $header_content .= "                    <a href=\"index.php?page=auth&action=register\" class=\"text-gray-600 hover:text-gray-900\">Register</a>\n";
        $header_content .= "                <?php endif; ?>\n";
    }
    
    $header_content .= "            </div>\n";
    $header_content .= "        </nav>\n";
    $header_content .= "    </header>\n";
    $header_content .= "    <main class=\"container mx-auto px-4 py-8\">\n";
} else {
    $header_content .= "<body>\n";
    $header_content .= "    <header>\n";
    $header_content .= "        <nav class=\"navbar navbar-expand-lg navbar-light bg-light\">\n";
    $header_content .= "            <div class=\"container\">\n";
    $header_content .= "                <a class=\"navbar-brand\" href=\"index.php\"><?php echo '{$_SESSION['project_name']}'; ?></a>\n";
    $header_content .= "                <button class=\"navbar-toggler\" type=\"button\" data-bs-toggle=\"collapse\" data-bs-target=\"#navbarNav\" aria-controls=\"navbarNav\" aria-expanded=\"false\" aria-label=\"Toggle navigation\">\n";
    $header_content .= "                    <span class=\"navbar-toggler-icon\"></span>\n";
    $header_content .= "                </button>\n";
    $header_content .= "                <div class=\"collapse navbar-collapse\" id=\"navbarNav\">\n";
    $header_content .= "                    <ul class=\"navbar-nav ms-auto\">\n";
    
    // Add authentication links if enabled
    if (isset($_SESSION['auth']) && $_SESSION['auth']['enabled']) {
        $header_content .= "                        <?php if (isset(\$_SESSION['user_id'])): ?>\n";
        $header_content .= "                            <li class=\"nav-item\">\n";
        $header_content .= "                                <a class=\"nav-link\" href=\"index.php?page=profile\">Profile</a>\n";
        $header_content .= "                            </li>\n";
        $header_content .= "                            <li class=\"nav-item\">\n";
        $header_content .= "                                <a class=\"nav-link\" href=\"index.php?page=auth&action=logout\">Logout</a>\n";
        $header_content .= "                            </li>\n";
        $header_content .= "                        <?php else: ?>\n";
        $header_content .= "                            <li class=\"nav-item\">\n";
        $header_content .= "                                <a class=\"nav-link\" href=\"index.php?page=auth&action=login\">Login</a>\n";
        $header_content .= "                            </li>\n";
        $header_content .= "                            <li class=\"nav-item\">\n";
        $header_content .= "                                <a class=\"nav-link\" href=\"index.php?page=auth&action=register\">Register</a>\n";
        $header_content .= "                            </li>\n";
        $header_content .= "                        <?php endif; ?>\n";
    }
    
    $header_content .= "                    </ul>\n";
    $header_content .= "                </div>\n";
    $header_content .= "            </div>\n";
    $header_content .= "        </nav>\n";
    $header_content .= "    </header>\n";
    $header_content .= "    <main class=\"container py-4\">\n";
}

file_put_contents($temp_dir . '/views/header.php', $header_content);

// Generate footer.php
$footer_content = "    </main>\n";

// Add footer based on selected framework
if (isset($_SESSION['features']['tailwind']) && $_SESSION['features']['tailwind']) {
    $footer_content .= "    <footer class=\"bg-white shadow-inner py-6 mt-8\">\n";
    $footer_content .= "        <div class=\"container mx-auto px-4 text-center text-gray-600\">\n";
    $footer_content .= "            <p>&copy; <?php echo date('Y'); ?> {$_SESSION['project_name']} - Generated by Backendo</p>\n";
    $footer_content .= "        </div>\n";
    $footer_content .= "    </footer>\n";
} else {
    $footer_content .= "    <footer class=\"bg-light py-4 mt-5\">\n";
    $footer_content .= "        <div class=\"container text-center text-muted\">\n";
    $footer_content .= "            <p>&copy; <?php echo date('Y'); ?> {$_SESSION['project_name']} - Generated by Backendo</p>\n";
    $footer_content .= "        </div>\n";
    $footer_content .= "    </footer>\n";
}

// Add JavaScript based on selected framework
if (!isset($_SESSION['features']['tailwind']) || !$_SESSION['features']['tailwind']) {
    $footer_content .= "    <script src=\"https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js\"></script>\n";
}

$footer_content .= "    <script src=\"assets/js/main.js\"></script>\n";
$footer_content .= "</body>\n";
$footer_content .= "</html>\n";

file_put_contents($temp_dir . '/views/footer.php', $footer_content);

// Generate style.css
$css_content = "/* Custom styles for {$_SESSION['project_name']} */\n";
$css_content .= "body {\n";
$css_content .= "    min-height: 100vh;\n";
$css_content .= "}\n";

file_put_contents($temp_dir . '/assets/css/style.css', $css_content);

// Generate main.js
$js_content = "// Custom JavaScript for {$_SESSION['project_name']}\n";
$js_content .= "document.addEventListener('DOMContentLoaded', function() {\n";
$js_content .= "    // Initialize any JavaScript functionality here\n";
$js_content .= "});\n";

file_put_contents($temp_dir . '/assets/js/main.js', $js_content);

// Generate index.php (router)
$index_content = "<?php\n";
$index_content .= "// Include header\n";
$index_content .= "require_once 'views/header.php';\n\n";
$index_content .= "// Simple router\n";
$index_content .= "\$page = isset(\$_GET['page']) ? sanitize_input(\$_GET['page']) : 'home';\n";
$index_content .= "\$action = isset(\$_GET['action']) ? sanitize_input(\$_GET['action']) : 'index';\n";
$index_content .= "\$id = isset(\$_GET['id']) ? (int)\$_GET['id'] : null;\n\n";

// Add authentication check if enabled
if (isset($_SESSION['auth']) && $_SESSION['auth']['enabled']) {
    $index_content .= "// Check if page requires authentication\n";
    $index_content .= "\$public_pages = ['home', 'auth'];\n";
    $index_content .= "if (!in_array(\$page, \$public_pages) && !isset(\$_SESSION['user_id'])) {\n";
    $index_content .= "    // Redirect to login page\n";
    $index_content .= "    header('Location: index.php?page=auth&action=login&error=auth_required');\n";
    $index_content .= "    exit;\n";
    $index_content .= "}\n\n";
}

$index_content .= "// Route to appropriate controller\n";
$index_content .= "switch (\$page) {\n";

// Add home page route
$index_content .= "    case 'home':\n";
$index_content .= "        include 'views/home.php';\n";
$index_content .= "        break;\n\n";

// Profile page is now handled by the AuthController

// Add authentication routes if enabled
if (isset($_SESSION['auth']) && $_SESSION['auth']['enabled']) {
    $index_content .= "    case 'auth':\n";
    $index_content .= "        require_once 'controllers/AuthController.php';\n";
    $index_content .= "        \$controller = new AuthController();\n";
    $index_content .= "        switch (\$action) {\n";
    $index_content .= "            case 'login':\n";
    $index_content .= "                \$controller->login();\n";
    $index_content .= "                break;\n";
    $index_content .= "            case 'login_process':\n";
    $index_content .= "                \$controller->loginProcess();\n";
    $index_content .= "                break;\n";
    $index_content .= "            case 'logout':\n";
    $index_content .= "                \$controller->logout();\n";
    $index_content .= "                break;\n";
    $index_content .= "            case 'register':\n";
    $index_content .= "                \$controller->register();\n";
    $index_content .= "                break;\n";
    $index_content .= "            case 'register_store':\n";
    $index_content .= "                \$controller->registerStore();\n";
    $index_content .= "                break;\n";
    $index_content .= "            case 'profile':\n";
    $index_content .= "                \$controller->profile();\n";
    $index_content .= "                break;\n";
    $index_content .= "            case 'update_profile':\n";
    $index_content .= "                \$controller->updateProfile();\n";
    $index_content .= "                break;\n";
    $index_content .= "            case 'forgot_password':\n";
    $index_content .= "                \$controller->forgotPassword();\n";
    $index_content .= "                break;\n";
    $index_content .= "            case 'forgot_password_process':\n";
    $index_content .= "                \$controller->forgotPasswordProcess();\n";
    $index_content .= "                break;\n";
    $index_content .= "            case 'reset_password':\n";
    $index_content .= "                \$controller->resetPassword();\n";
    $index_content .= "                break;\n";
    $index_content .= "            case 'reset_password_process':\n";
    $index_content .= "                \$controller->resetPasswordProcess();\n";
    $index_content .= "                break;\n";
    $index_content .= "            default:\n";
    $index_content .= "                include 'views/404.php';\n";
    $index_content .= "                break;\n";
    $index_content .= "        }\n";
    $index_content .= "        break;\n\n";
}

// Add routes for each table
foreach ($_SESSION['tables'] as $table) {
    $table_name = sanitize_input($table['name']);
    $controller_name = to_title_case($table_name) . 'Controller';
    
    $index_content .= "    case '{$table_name}':\n";
    $index_content .= "        require_once 'controllers/{$controller_name}.php';\n";
    $index_content .= "        \$controller = new {$controller_name}();\n";
    $index_content .= "        switch (\$action) {\n";
    $index_content .= "            case 'index':\n";
    $index_content .= "                \$controller->index();\n";
    $index_content .= "                break;\n";
    $index_content .= "            case 'show':\n";
    $index_content .= "                \$controller->show(\$id);\n";
    $index_content .= "                break;\n";
    $index_content .= "            case 'create':\n";
    $index_content .= "                \$controller->create();\n";
    $index_content .= "                break;\n";
    $index_content .= "            case 'store':\n";
    $index_content .= "                \$controller->store();\n";
    $index_content .= "                break;\n";
    $index_content .= "            case 'edit':\n";
    $index_content .= "                \$controller->edit(\$id);\n";
    $index_content .= "                break;\n";
    $index_content .= "            case 'update':\n";
    $index_content .= "                \$controller->update(\$id);\n";
    $index_content .= "                break;\n";
    $index_content .= "            case 'delete':\n";
    $index_content .= "                \$controller->delete(\$id);\n";
    $index_content .= "                break;\n";
    
    // Add export to CSV if enabled
    if (isset($_SESSION['features']['export_csv']) && $_SESSION['features']['export_csv']) {
        $index_content .= "            case 'export_csv':\n";
        $index_content .= "                \$controller->exportCsv();\n";
        $index_content .= "                break;\n";
    }
    
    $index_content .= "            default:\n";
    $index_content .= "                include 'views/404.php';\n";
    $index_content .= "                break;\n";
    $index_content .= "        }\n";
    $index_content .= "        break;\n\n";
}

// Add default route
$index_content .= "    default:\n";
$index_content .= "        include 'views/404.php';\n";
$index_content .= "        break;\n";
$index_content .= "}\n\n";
$index_content .= "// Include footer\n";
$index_content .= "require_once 'views/footer.php';\n";

file_put_contents($temp_dir . '/index.php', $index_content);

// Generate home.php view
$home_content = "<div class=\"";
if (isset($_SESSION['features']['tailwind']) && $_SESSION['features']['tailwind']) {
    $home_content .= "text-center py-10\">\n";
    $home_content .= "    <h1 class=\"text-4xl font-bold mb-6\">{$_SESSION['project_name']}</h1>\n";
    $home_content .= "    <p class=\"text-xl mb-8\">Welcome to your generated PHP application!</p>\n";
    
    // Add login/register buttons if authentication is enabled
    if (isset($_SESSION['auth']) && $_SESSION['auth']['enabled']) {
        $home_content .= "    <?php if (!isset(\$_SESSION['user_id'])): ?>\n";
        $home_content .= "    <div class=\"flex justify-center space-x-4 mb-10\">\n";
        $home_content .= "        <a href=\"index.php?page=auth&action=login\" class=\"bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded\">Login</a>\n";
        $home_content .= "        <a href=\"index.php?page=auth&action=register\" class=\"bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded\">Register</a>\n";
        $home_content .= "    </div>\n";
        $home_content .= "    <?php endif; ?>\n";
    }
    
    $home_content .= "    <div class=\"grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-6xl mx-auto\">\n";
} else {
    $home_content .= "text-center py-5\">\n";
    $home_content .= "    <h1 class=\"display-4 mb-4\">{$_SESSION['project_name']}</h1>\n";
    $home_content .= "    <p class=\"lead mb-5\">Welcome to your generated PHP application!</p>\n";
    
    // Add login/register buttons if authentication is enabled
    if (isset($_SESSION['auth']) && $_SESSION['auth']['enabled']) {
        $home_content .= "    <?php if (!isset(\$_SESSION['user_id'])): ?>\n";
        $home_content .= "    <div class=\"d-flex justify-content-center gap-3 mb-5\">\n";
        $home_content .= "        <a href=\"index.php?page=auth&action=login\" class=\"btn btn-primary\">Login</a>\n";
        $home_content .= "        <a href=\"index.php?page=auth&action=register\" class=\"btn btn-success\">Register</a>\n";
        $home_content .= "    </div>\n";
        $home_content .= "    <?php endif; ?>\n";
    }
    
    $home_content .= "    <div class=\"row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4\">\n";
}

// Add cards for each table
foreach ($_SESSION['tables'] as $table) {
    $table_name = sanitize_input($table['name']);
    $title_case = to_title_case($table_name);
    
    if (isset($_SESSION['features']['tailwind']) && $_SESSION['features']['tailwind']) {
        $home_content .= "        <div class=\"p-6 bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow\">\n";
        $home_content .= "            <h2 class=\"text-2xl font-bold mb-4\">{$title_case}</h2>\n";
        $home_content .= "            <p class=\"mb-4\">Manage your {$table_name} data</p>\n";
        $home_content .= "            <a href=\"index.php?page={$table_name}\" class=\"inline-block bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded\">View {$title_case}</a>\n";
        $home_content .= "        </div>\n";
    } else {
        $home_content .= "        <div class=\"col\">\n";
        $home_content .= "            <div class=\"card h-100\">\n";
        $home_content .= "                <div class=\"card-body\">\n";
        $home_content .= "                    <h5 class=\"card-title\">{$title_case}</h5>\n";
        $home_content .= "                    <p class=\"card-text\">Manage your {$table_name} data</p>\n";
        $home_content .= "                </div>\n";
        $home_content .= "                <div class=\"card-footer\">\n";
        $home_content .= "                    <a href=\"index.php?page={$table_name}\" class=\"btn btn-primary\">View {$title_case}</a>\n";
        $home_content .= "                </div>\n";
        $home_content .= "            </div>\n";
        $home_content .= "        </div>\n";
    }
}

$home_content .= "    </div>\n";
$home_content .= "</div>\n";

file_put_contents($temp_dir . '/views/home.php', $home_content);

// Generate 404.php view
if (isset($_SESSION['features']['tailwind']) && $_SESSION['features']['tailwind']) {
    $not_found_content = "<div class=\"flex flex-col items-center justify-center py-12\">\n";
    $not_found_content .= "    <h1 class=\"text-6xl font-bold text-gray-800 mb-4\">404</h1>\n";
    $not_found_content .= "    <p class=\"text-2xl text-gray-600 mb-8\">Page not found</p>\n";
    $not_found_content .= "    <a href=\"index.php\" class=\"bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded\">Go Home</a>\n";
    $not_found_content .= "</div>\n";
} else {
    $not_found_content = "<div class=\"text-center py-5\">\n";
    $not_found_content .= "    <h1 class=\"display-1 fw-bold\">404</h1>\n";
    $not_found_content .= "    <p class=\"fs-3 mb-5\">Page not found</p>\n";
    $not_found_content .= "    <a href=\"index.php\" class=\"btn btn-primary\">Go Home</a>\n";
    $not_found_content .= "</div>\n";
}

file_put_contents($temp_dir . '/views/404.php', $not_found_content);

// Generate schema.sql
$schema_content = "-- Database schema for {$_SESSION['project_name']}\n";
$schema_content .= "-- Generated: " . date('Y-m-d H:i:s') . "\n\n";
$schema_content .= "CREATE DATABASE IF NOT EXISTS `{$_SESSION['project_name']}` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;\n\n";
$schema_content .= "USE `{$_SESSION['project_name']}`;\n\n";

// Add users table if authentication is enabled
if (isset($_SESSION['auth']) && $_SESSION['auth']['enabled']) {
    $schema_content .= "-- Users table\n";
    $schema_content .= "CREATE TABLE `users` (\n";
    $schema_content .= "  `id` int(11) NOT NULL AUTO_INCREMENT,\n";
    $schema_content .= "  `username` varchar(50) NOT NULL,\n";
    $schema_content .= "  `email` varchar(100) NOT NULL,\n";
    $schema_content .= "  `password` varchar(255) NOT NULL,\n";
    $schema_content .= "  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,\n";
    $schema_content .= "  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,\n";
    $schema_content .= "  `reset_token` varchar(100) DEFAULT NULL,\n";
    $schema_content .= "  `reset_token_expires` datetime DEFAULT NULL,\n";
    $schema_content .= "  `remember_token` varchar(100) DEFAULT NULL,\n";
    $schema_content .= "  PRIMARY KEY (`id`),\n";
    $schema_content .= "  UNIQUE KEY `username` (`username`),\n";
    $schema_content .= "  UNIQUE KEY `email` (`email`)\n";
    $schema_content .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n\n";
}

// Generate tables for each defined table
foreach ($_SESSION['tables'] as $table) {
    $table_name = sanitize_input($table['name']);
    $schema_content .= "-- {$table_name} table\n";
    $schema_content .= "CREATE TABLE `{$table_name}` (\n";
    
    // Add fields
    $fields = [];
    foreach ($table['fields'] as $field) {
        $field_name = sanitize_input($field['name']);
        $field_type = sanitize_input($field['type']);
        $field_def = "  `{$field_name}` {$field_type}";
        
        // Add length/values if specified
        if (isset($field['length']) && !empty($field['length'])) {
            $field_def .= "({$field['length']})";
        } elseif ($field_type === 'enum' && isset($field['options']) && is_array($field['options'])) {
            $options = array_map(function($option) {
                return "'" . sanitize_input($option) . "'";
            }, $field['options']);
            $field_def .= "(" . implode(',', $options) . ")";
        }
        
        // Add nullable
        if (isset($field['nullable']) && !$field['nullable']) {
            $field_def .= " NOT NULL";
        } else {
            $field_def .= " DEFAULT NULL";
        }
        
        // Add auto increment
        if (isset($field['auto_increment']) && $field['auto_increment']) {
            $field_def .= " AUTO_INCREMENT";
        }
        
        $fields[] = $field_def;
    }
    
    // Add audit trail fields if enabled
    if (isset($_SESSION['features']['audit']) && $_SESSION['features']['audit']) {
        $fields[] = "  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP";
        $fields[] = "  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP";
        $fields[] = "  `created_by` int(11) DEFAULT NULL";
        $fields[] = "  `updated_by` int(11) DEFAULT NULL";
    }
    
    // Add soft delete field if enabled
    if (isset($_SESSION['features']['soft_delete']) && $_SESSION['features']['soft_delete']) {
        $fields[] = "  `deleted_at` timestamp NULL DEFAULT NULL";
    }
    
    // Add constraints
    $constraints = [];
    foreach ($table['fields'] as $field) {
        if (isset($field['constraints']) && is_array($field['constraints'])) {
            $field_name = sanitize_input($field['name']);
            
            // Add primary key
            if (in_array('primary', $field['constraints'])) {
                $constraints[] = "  PRIMARY KEY (`{$field_name}`)";
            }
            
            // Add unique key
            if (in_array('unique', $field['constraints'])) {
                $constraints[] = "  UNIQUE KEY `{$field_name}` (`{$field_name}`)";
            }
        }
    }
    
    // Add foreign keys
    foreach ($table['fields'] as $field) {
        if (isset($field['foreign_key']) && !empty($field['foreign_key'])) {
            $field_name = sanitize_input($field['name']);
            $foreign_table = sanitize_input($field['foreign_key']['table']);
            $foreign_field = sanitize_input($field['foreign_key']['field']);
            $constraints[] = "  FOREIGN KEY (`{$field_name}`) REFERENCES `{$foreign_table}` (`{$foreign_field}`)";
        }
    }
    
    // Combine fields and constraints
    $schema_content .= implode(",\n", array_merge($fields, $constraints));
    $schema_content .= "\n) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n\n";
}

file_put_contents($temp_dir . '/schema.sql', $schema_content);

// Generate models, controllers, and views for each table
foreach ($_SESSION['tables'] as $table) {
    $table_name = sanitize_input($table['name']);
    $class_name = to_singular(to_title_case($table_name));
    $controller_name = to_title_case($table_name) . 'Controller';
    
    // Create directory for views
    mkdir($temp_dir . '/views/' . $table_name, 0777, true);
    
    // Generate model
    $model_code = generate_model($table);
    file_put_contents($temp_dir . '/models/' . $class_name . '.php', $model_code);
    
    // Generate controller
    $controller_code = generate_controller($table);
    file_put_contents($temp_dir . '/controllers/' . $controller_name . '.php', $controller_code);
    
    // Generate views
    $index_view = generate_index_view($table);
    file_put_contents($temp_dir . '/views/' . $table_name . '/index.php', $index_view);
    
    $show_view = generate_show_view($table);
    file_put_contents($temp_dir . '/views/' . $table_name . '/show.php', $show_view);
    
    $create_view = generate_create_view($table);
    file_put_contents($temp_dir . '/views/' . $table_name . '/create.php', $create_view);
    
    $edit_view = generate_edit_view($table);
    file_put_contents($temp_dir . '/views/' . $table_name . '/edit.php', $edit_view);
}

// Generate authentication files if enabled
if (isset($_SESSION['auth']) && $_SESSION['auth']['enabled']) {
    // Generate User model
    $user_model = generate_user_model();
    file_put_contents($temp_dir . '/models/User.php', $user_model);
    
    // Generate AuthController
    $auth_controller = generate_auth_controller();
    file_put_contents($temp_dir . '/controllers/AuthController.php', $auth_controller);
    
    // Create directory for auth views
    mkdir($temp_dir . '/views/auth', 0777, true);
    
    // Generate login view
    $login_view = generate_login_view();
    file_put_contents($temp_dir . '/views/auth/login.php', $login_view);
    
    // Generate register view
    $register_view = generate_register_view();
    file_put_contents($temp_dir . '/views/auth/register.php', $register_view);
    
    // Generate profile view
    $profile_view = generate_profile_view();
    file_put_contents($temp_dir . '/views/auth/profile.php', $profile_view);
    
    // Generate forgot password view
    $forgot_password_view = generate_forgot_password_view();
    file_put_contents($temp_dir . '/views/auth/forgot_password.php', $forgot_password_view);
    
    // Generate reset password view
    $reset_password_view = generate_reset_password_view();
    file_put_contents($temp_dir . '/views/auth/reset_password.php', $reset_password_view);
}

// Generate README.md
if (isset($_SESSION['features']['readme']) && $_SESSION['features']['readme']) {
    $readme_content = "# {$_SESSION['project_name']}\n\n";
    $readme_content .= "Generated by Backendo: Legacy PHP CRUD Generator\n\n";
    
    // Add project information
    $readme_content .= "## Project Information\n\n";
    $readme_content .= "- **Project Name:** {$_SESSION['project_name']}\n";
    $readme_content .= "- **Author:** {$_SESSION['author']}\n";
    $readme_content .= "- **Generated:** " . date('Y-m-d H:i:s') . "\n\n";
    
    // Add features
    $readme_content .= "## Features\n\n";
    
    if (isset($_SESSION['auth']) && $_SESSION['auth']['enabled']) {
        $readme_content .= "- Authentication (Login, Register, Profile)\n";
    }
    
    if (isset($_SESSION['features']['search']) && $_SESSION['features']['search']) {
        $readme_content .= "- Search functionality\n";
    }
    
    if (isset($_SESSION['features']['pagination']) && $_SESSION['features']['pagination']) {
        $readme_content .= "- Pagination\n";
    }
    
    if (isset($_SESSION['features']['export_csv']) && $_SESSION['features']['export_csv']) {
        $readme_content .= "- Export to CSV\n";
    }
    
    if (isset($_SESSION['features']['audit']) && $_SESSION['features']['audit']) {
        $readme_content .= "- Audit trail (created_at, updated_at, created_by, updated_by)\n";
    }
    
    if (isset($_SESSION['features']['soft_delete']) && $_SESSION['features']['soft_delete']) {
        $readme_content .= "- Soft delete\n";
    }
    
    if (isset($_SESSION['features']['tailwind']) && $_SESSION['features']['tailwind']) {
        $readme_content .= "- Tailwind CSS\n";
    } else {
        $readme_content .= "- Bootstrap 5\n";
    }
    
    // Add tables
    $readme_content .= "\n## Database Tables\n\n";
    foreach ($_SESSION['tables'] as $table) {
        $table_name = sanitize_input($table['name']);
        $readme_content .= "### {$table_name}\n\n";
        $readme_content .= "| Field | Type | Constraints |\n";
        $readme_content .= "|-------|------|------------|\n";
        
        foreach ($table['fields'] as $field) {
            $field_name = sanitize_input($field['name']);
            $field_type = sanitize_input($field['type']);
            
            // Add length/values if specified
            if (isset($field['length']) && !empty($field['length'])) {
                $field_type .= "({$field['length']})";
            } elseif ($field_type === 'enum' && isset($field['options']) && is_array($field['options'])) {
                $options = implode(',', array_map('sanitize_input', $field['options']));
                $field_type .= "({$options})";
            }
            
            // Add constraints
            $constraints = [];
            if (isset($field['constraints']) && is_array($field['constraints'])) {
                foreach ($field['constraints'] as $constraint) {
                    $constraints[] = $constraint;
                }
            }
            
            if (isset($field['nullable']) && !$field['nullable']) {
                $constraints[] = 'NOT NULL';
            }
            
            if (isset($field['auto_increment']) && $field['auto_increment']) {
                $constraints[] = 'AUTO_INCREMENT';
            }
            
            if (isset($field['foreign_key']) && !empty($field['foreign_key'])) {
                $foreign_table = sanitize_input($field['foreign_key']['table']);
                $foreign_field = sanitize_input($field['foreign_key']['field']);
                $constraints[] = "FOREIGN KEY -> {$foreign_table}({$foreign_field})";
            }
            
            $constraints_str = implode(', ', $constraints);
            $readme_content .= "| {$field_name} | {$field_type} | {$constraints_str} |\n";
        }
        
        $readme_content .= "\n";
    }
    
    // Add installation instructions
    $readme_content .= "## Installation\n\n";
    $readme_content .= "1. Clone or download this repository\n";
    $readme_content .= "2. Import the `schema.sql` file into your MySQL database\n";
    $readme_content .= "3. Update the database configuration in `config/config.php`\n";
    $readme_content .= "4. Place the files on your PHP server\n";
    $readme_content .= "5. Access the application through your web browser\n\n";
    
    // Add license
    $readme_content .= "## License\n\n";
    $readme_content .= "This project is licensed under the MIT License.\n";
    
    file_put_contents($temp_dir . '/README.md', $readme_content);
}

// Create ZIP archive
log_debug('Creating ZIP archive');
$zip_file = __DIR__ . '/../output/' . $_SESSION['project_name'] . '_' . time() . '.zip';
log_debug('ZIP file path: ' . $zip_file);

// Make sure output directory exists
if (!file_exists(__DIR__ . '/../output')) {
    log_debug('Creating output directory');
    $result = mkdir(__DIR__ . '/../output', 0777, true);
    log_debug('Output directory creation result: ' . ($result ? 'success' : 'failed'));
    
    // Write to a direct error file for debugging
    file_put_contents(__DIR__ . '/../export_error.log', date('Y-m-d H:i:s') . ' - Output directory creation result: ' . ($result ? 'success' : 'failed') . "\n", FILE_APPEND);
}

// Write session data to error log for debugging
file_put_contents(__DIR__ . '/../export_error.log', date('Y-m-d H:i:s') . ' - Session data: ' . print_r($_SESSION, true) . "\n", FILE_APPEND);

// Function to create a ZIP file without ZipArchive
function create_zip_alternative($source, $destination) {
    global $log_debug;
    log_debug('Using alternative ZIP creation method');
    
    // Create empty file
    $f = fopen($destination, 'w');
    fclose($f);
    
    // Get real path for source directory
    $source = realpath($source);
    log_debug('Source directory: ' . $source);
    
    try {
        // Initialize archive object
        log_debug('Initializing PharData');
        $zip = new PharData($destination);
        
        // Create recursive directory iterator
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($source),
            RecursiveIteratorIterator::LEAVES_ONLY
        );
        
        $file_count = 0;
        foreach ($files as $name => $file) {
            // Skip directories (they would be added automatically)
            if (!$file->isDir()) {
                // Get real and relative path for current file
                $file_path = $file->getRealPath();
                $relative_path = substr($file_path, strlen($source) + 1);
                
                // Add current file to archive
                $zip->addFile($file_path, $relative_path);
                $file_count++;
            }
        }
        
        log_debug('Added ' . $file_count . ' files to archive');
        return true;
    } catch (Exception $e) {
        log_debug('Error creating ZIP with PharData: ' . $e->getMessage());
        return false;
    }
}

// Check if ZipArchive is available
log_debug('Checking if ZipArchive is available: ' . (class_exists('ZipArchive') ? 'Yes' : 'No'));
file_put_contents(__DIR__ . '/../export_error.log', date('Y-m-d H:i:s') . ' - ZipArchive available: ' . (class_exists('ZipArchive') ? 'Yes' : 'No') . "\n", FILE_APPEND);

if (class_exists('ZipArchive')) {
    log_debug('Using ZipArchive');
    file_put_contents(__DIR__ . '/../export_error.log', date('Y-m-d H:i:s') . ' - Using ZipArchive' . "\n", FILE_APPEND);
    
    $zip = new ZipArchive();
    $result = $zip->open($zip_file, ZipArchive::CREATE | ZipArchive::OVERWRITE);
    log_debug('ZipArchive open result: ' . $result);
    file_put_contents(__DIR__ . '/../export_error.log', date('Y-m-d H:i:s') . ' - ZipArchive open result: ' . $result . "\n", FILE_APPEND);
    
    if ($result === TRUE) {
    // Add files recursively
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($temp_dir),
        RecursiveIteratorIterator::LEAVES_ONLY
    );
    
    foreach ($files as $name => $file) {
        if (!$file->isDir()) {
            $file_path = $file->getRealPath();
            $relative_path = substr($file_path, strlen($temp_dir) + 1);
            $zip->addFile($file_path, $relative_path);
        }
    }
    
        $zip->close();
        
        // Clean up temporary directory
        function deleteDir($dir) {
            if (!file_exists($dir)) {
                return true;
            }
            
            if (!is_dir($dir)) {
                return unlink($dir);
            }
            
            foreach (scandir($dir) as $item) {
                if ($item == '.' || $item == '..') {
                    continue;
                }
                
                if (!deleteDir($dir . DIRECTORY_SEPARATOR . $item)) {
                    return false;
                }
            }
            
            return rmdir($dir);
        }
        
        deleteDir($temp_dir);
        
        // Redirect to download page
        header('Location: ../index.php?page=download&file=' . basename($zip_file));
        exit;
    } else {
        echo "Failed to create ZIP archive.";
        exit;
    }
} else {
    // Use alternative method with PharData if available
    log_debug('ZipArchive not available, checking for PharData');
    file_put_contents(__DIR__ . '/../export_error.log', date('Y-m-d H:i:s') . ' - ZipArchive not available, checking for PharData' . "\n", FILE_APPEND);
    
    $phardata_available = class_exists('PharData');
    log_debug('PharData available: ' . ($phardata_available ? 'Yes' : 'No'));
    file_put_contents(__DIR__ . '/../export_error.log', date('Y-m-d H:i:s') . ' - PharData available: ' . ($phardata_available ? 'Yes' : 'No') . "\n", FILE_APPEND);
    
    if (class_exists('PharData')) {
        try {
            log_debug('Attempting to create ZIP with PharData');
            file_put_contents(__DIR__ . '/../export_error.log', date('Y-m-d H:i:s') . ' - Attempting to create ZIP with PharData' . "\n", FILE_APPEND);
            
            if (create_zip_alternative($temp_dir, $zip_file)) {
                log_debug('ZIP creation with PharData successful');
                file_put_contents(__DIR__ . '/../export_error.log', date('Y-m-d H:i:s') . ' - ZIP creation with PharData successful' . "\n", FILE_APPEND);
                
                // Clean up temporary directory
                log_debug('Cleaning up temporary directory');
                deleteDir($temp_dir);
                
                // Redirect to download page
                log_debug('Redirecting to download page with file: ' . basename($zip_file));
                file_put_contents(__DIR__ . '/../export_error.log', date('Y-m-d H:i:s') . ' - Redirecting to download page with file: ' . basename($zip_file) . "\n", FILE_APPEND);
                
                header('Location: ../index.php?page=download&file=' . basename($zip_file));
                exit;
            } else {
                log_debug('Failed to create ZIP archive using PharData');
                file_put_contents(__DIR__ . '/../export_error.log', date('Y-m-d H:i:s') . ' - Failed to create ZIP archive using PharData' . "\n", FILE_APPEND);
                
                echo "Failed to create ZIP archive using alternative method.";
                exit;
            }
        } catch (Exception $e) {
            log_debug('Error creating ZIP archive: ' . $e->getMessage());
            file_put_contents(__DIR__ . '/../export_error.log', date('Y-m-d H:i:s') . ' - Error creating ZIP archive: ' . $e->getMessage() . "\n", FILE_APPEND);
            
            echo "Error creating ZIP archive: " . $e->getMessage();
            exit;
        }
    } else {
        // If neither ZipArchive nor PharData is available
        log_debug('Neither ZipArchive nor PharData is available');
        file_put_contents(__DIR__ . '/../export_error.log', date('Y-m-d H:i:s') . ' - Neither ZipArchive nor PharData is available' . "\n", FILE_APPEND);
        
        echo "ZIP functionality is not available on this server. Please install the ZIP extension.";
        exit;
    }
}

/**
 * Generate User model
 * 
 * @return string The PHP model class code
 */
function generate_user_model() {
    $model_code = "<?php\n";
    $model_code .= "/**\n";
    $model_code .= " * User Model\n";
    $model_code .= " * Generated by Backendo: Legacy PHP CRUD Generator\n";
    $model_code .= " */\n\n";
    
    // Include database connection
    $model_code .= "require_once 'config/db.php';\n\n";
    
    // Class definition
    $model_code .= "class User {\n";
    
    // Properties
    $model_code .= "    public \$id;\n";
    $model_code .= "    public \$username;\n";
    $model_code .= "    public \$email;\n";
    $model_code .= "    public \$password;\n";
    $model_code .= "    public \$created_at;\n";
    $model_code .= "    public \$updated_at;\n";
    $model_code .= "    public \$reset_token;\n";
    $model_code .= "    public \$reset_token_expires;\n";
    $model_code .= "    public \$remember_token;\n\n";
    
    // Constructor
    $model_code .= "    /**\n";
    $model_code .= "     * Constructor\n";
    $model_code .= "     */\n";
    $model_code .= "    public function __construct(\$data = null) {\n";
    $model_code .= "        if (\$data) {\n";
    $model_code .= "            foreach (\$data as \$key => \$value) {\n";
    $model_code .= "                if (property_exists(\$this, \$key)) {\n";
    $model_code .= "                    \$this->\$key = \$value;\n";
    $model_code .= "                }\n";
    $model_code .= "            }\n";
    $model_code .= "        }\n";
    $model_code .= "    }\n\n";
    
    // Find by ID method
    $model_code .= "    /**\n";
    $model_code .= "     * Find a user by ID\n";
    $model_code .= "     * \n";
    $model_code .= "     * @param int \$id The user ID\n";
    $model_code .= "     * @return User|null The found user or null\n";
    $model_code .= "     */\n";
    $model_code .= "    public static function findById(\$id) {\n";
    $model_code .= "        \$db = db_connect();\n";
    $model_code .= "        \$sql = \"SELECT * FROM users WHERE id = ?\";\n";
    $model_code .= "        \$stmt = db_query(\$db, \$sql, [\$id]);\n";
    $model_code .= "        \$row = \$stmt->fetch();\n";
    $model_code .= "        return \$row ? new User(\$row) : null;\n";
    $model_code .= "    }\n\n";
    
    // Find by username method
    $model_code .= "    /**\n";
    $model_code .= "     * Find a user by username\n";
    $model_code .= "     * \n";
    $model_code .= "     * @param string \$username The username\n";
    $model_code .= "     * @return User|null The found user or null\n";
    $model_code .= "     */\n";
    $model_code .= "    public static function findByUsername(\$username) {\n";
    $model_code .= "        \$db = db_connect();\n";
    $model_code .= "        \$sql = \"SELECT * FROM users WHERE username = ?\";\n";
    $model_code .= "        \$stmt = db_query(\$db, \$sql, [\$username]);\n";
    $model_code .= "        \$row = \$stmt->fetch();\n";
    $model_code .= "        return \$row ? new User(\$row) : null;\n";
    $model_code .= "    }\n\n";
    
    // Find by email method
    $model_code .= "    /**\n";
    $model_code .= "     * Find a user by email\n";
    $model_code .= "     * \n";
    $model_code .= "     * @param string \$email The email\n";
    $model_code .= "     * @return User|null The found user or null\n";
    $model_code .= "     */\n";
    $model_code .= "    public static function findByEmail(\$email) {\n";
    $model_code .= "        \$db = db_connect();\n";
    $model_code .= "        \$sql = \"SELECT * FROM users WHERE email = ?\";\n";
    $model_code .= "        \$stmt = db_query(\$db, \$sql, [\$email]);\n";
    $model_code .= "        \$row = \$stmt->fetch();\n";
    $model_code .= "        return \$row ? new User(\$row) : null;\n";
    $model_code .= "    }\n\n";
    
    // Find by reset token method
    $model_code .= "    /**\n";
    $model_code .= "     * Find a user by reset token\n";
    $model_code .= "     * \n";
    $model_code .= "     * @param string \$token The reset token\n";
    $model_code .= "     * @return User|null The found user or null\n";
    $model_code .= "     */\n";
    $model_code .= "    public static function findByResetToken(\$token) {\n";
    $model_code .= "        \$db = db_connect();\n";
    $model_code .= "        \$sql = \"SELECT * FROM users WHERE reset_token = ? AND reset_token_expires > NOW()\";\n";
    $model_code .= "        \$stmt = db_query(\$db, \$sql, [\$token]);\n";
    $model_code .= "        \$row = \$stmt->fetch();\n";
    $model_code .= "        return \$row ? new User(\$row) : null;\n";
    $model_code .= "    }\n\n";
    
    // Find by remember token method
    $model_code .= "    /**\n";
    $model_code .= "     * Find a user by remember token\n";
    $model_code .= "     * \n";
    $model_code .= "     * @param string \$token The remember token\n";
    $model_code .= "     * @return User|null The found user or null\n";
    $model_code .= "     */\n";
    $model_code .= "    public static function findByRememberToken(\$token) {\n";
    $model_code .= "        \$db = db_connect();\n";
    $model_code .= "        \$sql = \"SELECT * FROM users WHERE remember_token = ?\";\n";
    $model_code .= "        \$stmt = db_query(\$db, \$sql, [\$token]);\n";
    $model_code .= "        \$row = \$stmt->fetch();\n";
    $model_code .= "        return \$row ? new User(\$row) : null;\n";
    $model_code .= "    }\n\n";
    
    // Create method
    $model_code .= "    /**\n";
    $model_code .= "     * Create a new user\n";
    $model_code .= "     * \n";
    $model_code .= "     * @return bool True if successful, false otherwise\n";
    $model_code .= "     */\n";
    $model_code .= "    public function create() {\n";
    $model_code .= "        \$db = db_connect();\n";
    $model_code .= "        \$this->created_at = date('Y-m-d H:i:s');\n";
    $model_code .= "        \$this->updated_at = date('Y-m-d H:i:s');\n";
    $model_code .= "        \$sql = \"INSERT INTO users (username, email, password, created_at, updated_at) VALUES (?, ?, ?, ?, ?)\";\n";
    $model_code .= "        \$stmt = db_query(\$db, \$sql, [\$this->username, \$this->email, \$this->password, \$this->created_at, \$this->updated_at]);\n";
    $model_code .= "        if (\$stmt->rowCount() > 0) {\n";
    $model_code .= "            \$this->id = \$db->lastInsertId();\n";
    $model_code .= "            return true;\n";
    $model_code .= "        }\n";
    $model_code .= "        return false;\n";
    $model_code .= "    }\n\n";
    
    // Update method
    $model_code .= "    /**\n";
    $model_code .= "     * Update an existing user\n";
    $model_code .= "     * \n";
    $model_code .= "     * @return bool True if successful, false otherwise\n";
    $model_code .= "     */\n";
    $model_code .= "    public function update() {\n";
    $model_code .= "        \$db = db_connect();\n";
    $model_code .= "        \$this->updated_at = date('Y-m-d H:i:s');\n";
    $model_code .= "        \$sql = \"UPDATE users SET username = ?, email = ?, password = ?, updated_at = ?, reset_token = ?, reset_token_expires = ?, remember_token = ? WHERE id = ?\";\n";
    $model_code .= "        \$stmt = db_query(\$db, \$sql, [\n";
    $model_code .= "            \$this->username, \n";
    $model_code .= "            \$this->email, \n";
    $model_code .= "            \$this->password, \n";
    $model_code .= "            \$this->updated_at, \n";
    $model_code .= "            \$this->reset_token, \n";
    $model_code .= "            \$this->reset_token_expires, \n";
    $model_code .= "            \$this->remember_token, \n";
    $model_code .= "            \$this->id\n";
    $model_code .= "        ]);\n";
    $model_code .= "        return \$stmt->rowCount() > 0;\n";
    $model_code .= "    }\n\n";
    
    // Set reset token method
    $model_code .= "    /**\n";
    $model_code .= "     * Set password reset token\n";
    $model_code .= "     * \n";
    $model_code .= "     * @return bool True if successful, false otherwise\n";
    $model_code .= "     */\n";
    $model_code .= "    public function setResetToken() {\n";
    $model_code .= "        \$token = bin2hex(random_bytes(32));\n";
    $model_code .= "        \$expires = date('Y-m-d H:i:s', time() + 3600); // 1 hour from now\n";
    $model_code .= "        \n";
    $model_code .= "        \$db = db_connect();\n";
    $model_code .= "        \$sql = \"UPDATE users SET reset_token = ?, reset_token_expires = ? WHERE id = ?\";\n";
    $model_code .= "        \$stmt = db_query(\$db, \$sql, [\$token, \$expires, \$this->id]);\n";
    $model_code .= "        \n";
    $model_code .= "        if (\$stmt->rowCount() > 0) {\n";
    $model_code .= "            \$this->reset_token = \$token;\n";
    $model_code .= "            \$this->reset_token_expires = \$expires;\n";
    $model_code .= "            return true;\n";
    $model_code .= "        }\n";
    $model_code .= "        return false;\n";
    $model_code .= "    }\n\n";
    
    // Clear reset token method
    $model_code .= "    /**\n";
    $model_code .= "     * Clear password reset token\n";
    $model_code .= "     * \n";
    $model_code .= "     * @return bool True if successful, false otherwise\n";
    $model_code .= "     */\n";
    $model_code .= "    public function clearResetToken() {\n";
    $model_code .= "        \$db = db_connect();\n";
    $model_code .= "        \$sql = \"UPDATE users SET reset_token = NULL, reset_token_expires = NULL WHERE id = ?\";\n";
    $model_code .= "        \$stmt = db_query(\$db, \$sql, [\$this->id]);\n";
    $model_code .= "        \n";
    $model_code .= "        if (\$stmt->rowCount() > 0) {\n";
    $model_code .= "            \$this->reset_token = null;\n";
    $model_code .= "            \$this->reset_token_expires = null;\n";
    $model_code .= "            return true;\n";
    $model_code .= "        }\n";
    $model_code .= "        return false;\n";
    $model_code .= "    }\n\n";
    
    // Set remember token method
    $model_code .= "    /**\n";
    $model_code .= "     * Set remember token for persistent login\n";
    $model_code .= "     * \n";
    $model_code .= "     * @return bool True if successful, false otherwise\n";
    $model_code .= "     */\n";
    $model_code .= "    public function setRememberToken() {\n";
    $model_code .= "        \$token = bin2hex(random_bytes(32));\n";
    $model_code .= "        \n";
    $model_code .= "        \$db = db_connect();\n";
    $model_code .= "        \$sql = \"UPDATE users SET remember_token = ? WHERE id = ?\";\n";
    $model_code .= "        \$stmt = db_query(\$db, \$sql, [\$token, \$this->id]);\n";
    $model_code .= "        \n";
    $model_code .= "        if (\$stmt->rowCount() > 0) {\n";
    $model_code .= "            \$this->remember_token = \$token;\n";
    $model_code .= "            return true;\n";
    $model_code .= "        }\n";
    $model_code .= "        return false;\n";
    $model_code .= "    }\n\n";
    
    // Clear remember token method
    $model_code .= "    /**\n";
    $model_code .= "     * Clear remember token\n";
    $model_code .= "     * \n";
    $model_code .= "     * @return bool True if successful, false otherwise\n";
    $model_code .= "     */\n";
    $model_code .= "    public function clearRememberToken() {\n";
    $model_code .= "        \$db = db_connect();\n";
    $model_code .= "        \$sql = \"UPDATE users SET remember_token = NULL WHERE id = ?\";\n";
    $model_code .= "        \$stmt = db_query(\$db, \$sql, [\$this->id]);\n";
    $model_code .= "        \n";
    $model_code .= "        if (\$stmt->rowCount() > 0) {\n";
    $model_code .= "            \$this->remember_token = null;\n";
    $model_code .= "            return true;\n";
    $model_code .= "        }\n";
    $model_code .= "        return false;\n";
    $model_code .= "    }\n";
    
    $model_code .= "}\n";
    
    return $model_code;
}

/**
 * Generate Auth Controller
 * 
 * @return string The PHP controller class code
 */
function generate_auth_controller() {
    $controller_code = "<?php\n";
    $controller_code .= "/**\n";
    $controller_code .= " * Auth Controller\n";
    $controller_code .= " * Generated by Backendo: Legacy PHP CRUD Generator\n";
    $controller_code .= " */\n\n";
    
    // Include required files
    $controller_code .= "require_once 'models/User.php';\n";
    $controller_code .= "require_once 'config/config.php';\n";
    $controller_code .= "require_once 'config/functions.php';\n\n";
    
    // Class definition
    $controller_code .= "class AuthController {\n";
    
    // Register display method
    $controller_code .= "    /**\n";
    $controller_code .= "     * Display registration form\n";
    $controller_code .= "     */\n";
    $controller_code .= "    public function register() {\n";
    $controller_code .= "        require_once 'views/auth/register.php';\n";
    $controller_code .= "    }\n\n";
    
    // Register store method
    $controller_code .= "    /**\n";
    $controller_code .= "     * Process registration form\n";
    $controller_code .= "     */\n";
    $controller_code .= "    public function registerStore() {\n";
    $controller_code .= "        // Validate input\n";
    $controller_code .= "        \$username = sanitize_input(\$_POST['username']);\n";
    $controller_code .= "        \$email = sanitize_input(\$_POST['email']);\n";
    $controller_code .= "        \$password = \$_POST['password'];\n";
    $controller_code .= "        \$confirm_password = \$_POST['confirm_password'];\n";
    $controller_code .= "        \$errors = [];\n";
    $controller_code .= "        \n";
    $controller_code .= "        // Validate username\n";
    $controller_code .= "        if (empty(\$username)) {\n";
    $controller_code .= "            \$errors[] = 'Username is required';\n";
    $controller_code .= "        } elseif (User::findByUsername(\$username)) {\n";
    $controller_code .= "            \$errors[] = 'Username already exists';\n";
    $controller_code .= "        }\n";
    $controller_code .= "        \n";
    $controller_code .= "        // Validate email\n";
    $controller_code .= "        if (empty(\$email)) {\n";
    $controller_code .= "            \$errors[] = 'Email is required';\n";
    $controller_code .= "        } elseif (!filter_var(\$email, FILTER_VALIDATE_EMAIL)) {\n";
    $controller_code .= "            \$errors[] = 'Invalid email format';\n";
    $controller_code .= "        } elseif (User::findByEmail(\$email)) {\n";
    $controller_code .= "            \$errors[] = 'Email already exists';\n";
    $controller_code .= "        }\n";
    $controller_code .= "        \n";
    $controller_code .= "        // Validate password\n";
    $controller_code .= "        if (empty(\$password)) {\n";
    $controller_code .= "            \$errors[] = 'Password is required';\n";
    $controller_code .= "        } elseif (strlen(\$password) < 8) {\n";
    $controller_code .= "            \$errors[] = 'Password must be at least 8 characters';\n";
    $controller_code .= "        } elseif (\$password !== \$confirm_password) {\n";
    $controller_code .= "            \$errors[] = 'Passwords do not match';\n";
    $controller_code .= "        }\n";
    $controller_code .= "        \n";
    $controller_code .= "        // If there are errors, redirect back with errors\n";
    $controller_code .= "        if (!empty(\$errors)) {\n";
    $controller_code .= "            \$_SESSION['errors'] = \$errors;\n";
    $controller_code .= "            \$_SESSION['old_input'] = [\n";
    $controller_code .= "                'username' => \$username,\n";
    $controller_code .= "                'email' => \$email\n";
    $controller_code .= "            ];\n";
    $controller_code .= "            header('Location: index.php?page=register');\n";
    $controller_code .= "            exit;\n";
    $controller_code .= "        }\n";
    $controller_code .= "        \n";
    $controller_code .= "        // Create user\n";
    $controller_code .= "        \$user = new User();\n";
    $controller_code .= "        \$user->username = \$username;\n";
    $controller_code .= "        \$user->email = \$email;\n";
    $controller_code .= "        \$user->password = password_hash(\$password, PASSWORD_DEFAULT);\n";
    $controller_code .= "        \n";
    $controller_code .= "        if (\$user->create()) {\n";
    $controller_code .= "            \$_SESSION['success'] = 'Registration successful! You can now log in.';\n";
    $controller_code .= "            header('Location: index.php?page=login');\n";
    $controller_code .= "            exit;\n";
    $controller_code .= "        } else {\n";
    $controller_code .= "            \$_SESSION['errors'] = ['Registration failed. Please try again.'];\n";
    $controller_code .= "            header('Location: index.php?page=register');\n";
    $controller_code .= "            exit;\n";
    $controller_code .= "        }\n";
    $controller_code .= "    }\n\n";
    
    // Login display method
    $controller_code .= "    /**\n";
    $controller_code .= "     * Display login form\n";
    $controller_code .= "     */\n";
    $controller_code .= "    public function login() {\n";
    $controller_code .= "        require_once 'views/auth/login.php';\n";
    $controller_code .= "    }\n\n";
    
    // Login process method
    $controller_code .= "    /**\n";
    $controller_code .= "     * Process login form\n";
    $controller_code .= "     */\n";
    $controller_code .= "    public function loginProcess() {\n";
    $controller_code .= "        // Validate input\n";
    $controller_code .= "        \$username = sanitize_input(\$_POST['username']);\n";
    $controller_code .= "        \$password = \$_POST['password'];\n";
    $controller_code .= "        \$remember_me = isset(\$_POST['remember_me']);\n";
    $controller_code .= "        \$errors = [];\n";
    $controller_code .= "        \n";
    $controller_code .= "        // Find user by username\n";
    $controller_code .= "        \$user = User::findByUsername(\$username);\n";
    $controller_code .= "        \n";
    $controller_code .= "        // Verify credentials\n";
    $controller_code .= "        if (!\$user || !password_verify(\$password, \$user->password)) {\n";
    $controller_code .= "            \$_SESSION['errors'] = ['Invalid username or password'];\n";
    $controller_code .= "            \$_SESSION['old_input'] = ['username' => \$username];\n";
    $controller_code .= "            header('Location: index.php?page=login');\n";
    $controller_code .= "            exit;\n";
    $controller_code .= "        }\n";
    $controller_code .= "        \n";
    $controller_code .= "        // Set session\n";
    $controller_code .= "        \$_SESSION['user_id'] = \$user->id;\n";
    $controller_code .= "        \$_SESSION['username'] = \$user->username;\n";
    $controller_code .= "        \$_SESSION['last_activity'] = time();\n";
    $controller_code .= "        \n";
    $controller_code .= "        // Set remember me cookie if requested\n";
    $controller_code .= "        if (\$remember_me) {\n";
    $controller_code .= "            \$user->setRememberToken();\n";
    $controller_code .= "            setcookie('remember_token', \$user->remember_token, time() + 30 * 24 * 60 * 60, '/'); // 30 days\n";
    $controller_code .= "        }\n";
    $controller_code .= "        \n";
    $controller_code .= "        \$_SESSION['success'] = 'Login successful!';\n";
    $controller_code .= "        header('Location: index.php');\n";
    $controller_code .= "        exit;\n";
    $controller_code .= "    }\n\n";
    
    // Logout method
    $controller_code .= "    /**\n";
    $controller_code .= "     * Process logout\n";
    $controller_code .= "     */\n";
    $controller_code .= "    public function logout() {\n";
    $controller_code .= "        // Clear remember token if exists\n";
    $controller_code .= "        if (isset(\$_SESSION['user_id'])) {\n";
    $controller_code .= "            \$user = User::findById(\$_SESSION['user_id']);\n";
    $controller_code .= "            if (\$user) {\n";
    $controller_code .= "                \$user->clearRememberToken();\n";
    $controller_code .= "            }\n";
    $controller_code .= "        }\n";
    $controller_code .= "        \n";
    $controller_code .= "        // Clear session\n";
    $controller_code .= "        session_unset();\n";
    $controller_code .= "        session_destroy();\n";
    $controller_code .= "        \n";
    $controller_code .= "        // Clear remember me cookie\n";
    $controller_code .= "        setcookie('remember_token', '', time() - 3600, '/');\n";
    $controller_code .= "        \n";
    $controller_code .= "        header('Location: index.php');\n";
    $controller_code .= "        exit;\n";
    $controller_code .= "    }\n\n";
    
    // Profile display method
    $controller_code .= "    /**\n";
    $controller_code .= "     * Display user profile\n";
    $controller_code .= "     */\n";
    $controller_code .= "    public function profile() {\n";
    $controller_code .= "        // Check if user is logged in\n";
    $controller_code .= "        if (!isset(\$_SESSION['user_id'])) {\n";
    $controller_code .= "            header('Location: index.php?page=login');\n";
    $controller_code .= "            exit;\n";
    $controller_code .= "        }\n";
    $controller_code .= "        \n";
    $controller_code .= "        \$user = User::findById(\$_SESSION['user_id']);\n";
    $controller_code .= "        if (!\$user) {\n";
    $controller_code .= "            \$_SESSION['errors'] = ['User not found'];\n";
    $controller_code .= "            header('Location: index.php');\n";
    $controller_code .= "            exit;\n";
    $controller_code .= "        }\n";
    $controller_code .= "        \n";
    $controller_code .= "        require_once 'views/auth/profile.php';\n";
    $controller_code .= "    }\n\n";
    
    // Profile update method
    $controller_code .= "    /**\n";
    $controller_code .= "     * Update user profile\n";
    $controller_code .= "     */\n";
    $controller_code .= "    public function updateProfile() {\n";
    $controller_code .= "        // Check if user is logged in\n";
    $controller_code .= "        if (!isset(\$_SESSION['user_id'])) {\n";
    $controller_code .= "            header('Location: index.php?page=login');\n";
    $controller_code .= "            exit;\n";
    $controller_code .= "        }\n";
    $controller_code .= "        \n";
    $controller_code .= "        \$user = User::findById(\$_SESSION['user_id']);\n";
    $controller_code .= "        if (!\$user) {\n";
    $controller_code .= "            \$_SESSION['errors'] = ['User not found'];\n";
    $controller_code .= "            header('Location: index.php');\n";
    $controller_code .= "            exit;\n";
    $controller_code .= "        }\n";
    $controller_code .= "        \n";
    $controller_code .= "        // Validate input\n";
    $controller_code .= "        \$username = sanitize_input(\$_POST['username']);\n";
    $controller_code .= "        \$email = sanitize_input(\$_POST['email']);\n";
    $controller_code .= "        \$current_password = \$_POST['current_password'];\n";
    $controller_code .= "        \$new_password = \$_POST['new_password'];\n";
    $controller_code .= "        \$confirm_password = \$_POST['confirm_password'];\n";
    $controller_code .= "        \$errors = [];\n";
    $controller_code .= "        \n";
    $controller_code .= "        // Validate username\n";
    $controller_code .= "        if (empty(\$username)) {\n";
    $controller_code .= "            \$errors[] = 'Username is required';\n";
    $controller_code .= "        } elseif (\$username !== \$user->username) {\n";
    $controller_code .= "            \$existing_user = User::findByUsername(\$username);\n";
    $controller_code .= "            if (\$existing_user && \$existing_user->id !== \$user->id) {\n";
    $controller_code .= "                \$errors[] = 'Username already exists';\n";
    $controller_code .= "            }\n";
    $controller_code .= "        }\n";
    $controller_code .= "        \n";
    $controller_code .= "        // Validate email\n";
    $controller_code .= "        if (empty(\$email)) {\n";
    $controller_code .= "            \$errors[] = 'Email is required';\n";
    $controller_code .= "        } elseif (!filter_var(\$email, FILTER_VALIDATE_EMAIL)) {\n";
    $controller_code .= "            \$errors[] = 'Invalid email format';\n";
    $controller_code .= "        } elseif (\$email !== \$user->email) {\n";
    $controller_code .= "            \$existing_user = User::findByEmail(\$email);\n";
    $controller_code .= "            if (\$existing_user && \$existing_user->id !== \$user->id) {\n";
    $controller_code .= "                \$errors[] = 'Email already exists';\n";
    $controller_code .= "            }\n";
    $controller_code .= "        }\n";
    $controller_code .= "        \n";
    $controller_code .= "        // Check if password change is requested\n";
    $controller_code .= "        if (!empty(\$current_password) || !empty(\$new_password) || !empty(\$confirm_password)) {\n";
    $controller_code .= "            // Validate current password\n";
    $controller_code .= "            if (empty(\$current_password) || !password_verify(\$current_password, \$user->password)) {\n";
    $controller_code .= "                \$errors[] = 'Current password is incorrect';\n";
    $controller_code .= "            }\n";
    $controller_code .= "            \n";
    $controller_code .= "            // Validate new password\n";
    $controller_code .= "            if (empty(\$new_password)) {\n";
    $controller_code .= "                \$errors[] = 'New password is required';\n";
    $controller_code .= "            } elseif (strlen(\$new_password) < 8) {\n";
    $controller_code .= "                \$errors[] = 'New password must be at least 8 characters';\n";
    $controller_code .= "            } elseif (\$new_password !== \$confirm_password) {\n";
    $controller_code .= "                \$errors[] = 'New passwords do not match';\n";
    $controller_code .= "            }\n";
    $controller_code .= "        }\n";
    $controller_code .= "        \n";
    $controller_code .= "        // If there are errors, redirect back with errors\n";
    $controller_code .= "        if (!empty(\$errors)) {\n";
    $controller_code .= "            \$_SESSION['errors'] = \$errors;\n";
    $controller_code .= "            header('Location: index.php?page=profile');\n";
    $controller_code .= "            exit;\n";
    $controller_code .= "        }\n";
    $controller_code .= "        \n";
    $controller_code .= "        // Update user\n";
    $controller_code .= "        \$user->username = \$username;\n";
    $controller_code .= "        \$user->email = \$email;\n";
    $controller_code .= "        \n";
    $controller_code .= "        // Update password if requested\n";
    $controller_code .= "        if (!empty(\$new_password)) {\n";
    $controller_code .= "            \$user->password = password_hash(\$new_password, PASSWORD_DEFAULT);\n";
    $controller_code .= "        }\n";
    $controller_code .= "        \n";
    $controller_code .= "        if (\$user->update()) {\n";
    $controller_code .= "            \$_SESSION['username'] = \$user->username; // Update session username\n";
    $controller_code .= "            \$_SESSION['success'] = 'Profile updated successfully!';\n";
    $controller_code .= "        } else {\n";
    $controller_code .= "            \$_SESSION['errors'] = ['Failed to update profile. Please try again.'];\n";
    $controller_code .= "        }\n";
    $controller_code .= "        \n";
    $controller_code .= "        header('Location: index.php?page=profile');\n";
    $controller_code .= "        exit;\n";
    $controller_code .= "    }\n\n";
    
    // Forgot password display method
    $controller_code .= "    /**\n";
    $controller_code .= "     * Display forgot password form\n";
    $controller_code .= "     */\n";
    $controller_code .= "    public function forgotPassword() {\n";
    $controller_code .= "        require_once 'views/auth/forgot_password.php';\n";
    $controller_code .= "    }\n\n";
    
    // Forgot password process method
    $controller_code .= "    /**\n";
    $controller_code .= "     * Process forgot password form\n";
    $controller_code .= "     */\n";
    $controller_code .= "    public function forgotPasswordProcess() {\n";
    $controller_code .= "        // Validate input\n";
    $controller_code .= "        \$email = sanitize_input(\$_POST['email']);\n";
    $controller_code .= "        \n";
    $controller_code .= "        if (empty(\$email) || !filter_var(\$email, FILTER_VALIDATE_EMAIL)) {\n";
    $controller_code .= "            \$_SESSION['errors'] = ['Please enter a valid email address'];\n";
    $controller_code .= "            \$_SESSION['old_input'] = ['email' => \$email];\n";
    $controller_code .= "            header('Location: index.php?page=forgot_password');\n";
    $controller_code .= "            exit;\n";
    $controller_code .= "        }\n";
    $controller_code .= "        \n";
    $controller_code .= "        // Find user by email\n";
    $controller_code .= "        \$user = User::findByEmail(\$email);\n";
    $controller_code .= "        \n";
    $controller_code .= "        // Always show success message even if email doesn't exist (security best practice)\n";
    $controller_code .= "        \$_SESSION['success'] = 'If your email exists in our system, you will receive a password reset link shortly.';\n";
    $controller_code .= "        \n";
    $controller_code .= "        if (\$user) {\n";
    $controller_code .= "            // Generate and set reset token\n";
    $controller_code .= "            \$user->setResetToken();\n";
    $controller_code .= "            \n";
    $controller_code .= "            // In a real application, you would send an email with the reset link\n";
    $controller_code .= "            // For this generator, we'll just display the link\n";
    $controller_code .= "            \$reset_url = BASE_URL . 'index.php?page=reset_password&token=' . \$user->reset_token;\n";
    $controller_code .= "            \$_SESSION['reset_link'] = \$reset_url;\n";
    $controller_code .= "        }\n";
    $controller_code .= "        \n";
    $controller_code .= "        header('Location: index.php?page=forgot_password');\n";
    $controller_code .= "        exit;\n";
    $controller_code .= "    }\n\n";
    
    // Reset password display method
    $controller_code .= "    /**\n";
    $controller_code .= "     * Display reset password form\n";
    $controller_code .= "     */\n";
    $controller_code .= "    public function resetPassword() {\n";
    $controller_code .= "        // Check if token is provided\n";
    $controller_code .= "        if (!isset(\$_GET['token']) || empty(\$_GET['token'])) {\n";
    $controller_code .= "            \$_SESSION['errors'] = ['Invalid or expired password reset link'];\n";
    $controller_code .= "            header('Location: index.php?page=login');\n";
    $controller_code .= "            exit;\n";
    $controller_code .= "        }\n";
    $controller_code .= "        \n";
    $controller_code .= "        \$token = sanitize_input(\$_GET['token']);\n";
    $controller_code .= "        \n";
    $controller_code .= "        // Find user by reset token\n";
    $controller_code .= "        \$user = User::findByResetToken(\$token);\n";
    $controller_code .= "        \n";
    $controller_code .= "        if (!\$user) {\n";
    $controller_code .= "            \$_SESSION['errors'] = ['Invalid or expired password reset link'];\n";
    $controller_code .= "            header('Location: index.php?page=login');\n";
    $controller_code .= "            exit;\n";
    $controller_code .= "        }\n";
    $controller_code .= "        \n";
    $controller_code .= "        require_once 'views/auth/reset_password.php';\n";
    $controller_code .= "    }\n\n";
    
    // Reset password process method
    $controller_code .= "    /**\n";
    $controller_code .= "     * Process reset password form\n";
    $controller_code .= "     */\n";
    $controller_code .= "    public function resetPasswordProcess() {\n";
    $controller_code .= "        // Check if token is provided\n";
    $controller_code .= "        if (!isset(\$_POST['token']) || empty(\$_POST['token'])) {\n";
    $controller_code .= "            \$_SESSION['errors'] = ['Invalid or expired password reset link'];\n";
    $controller_code .= "            header('Location: index.php?page=login');\n";
    $controller_code .= "            exit;\n";
    $controller_code .= "        }\n";
    $controller_code .= "        \n";
    $controller_code .= "        \$token = sanitize_input(\$_POST['token']);\n";
    $controller_code .= "        \$password = \$_POST['password'];\n";
    $controller_code .= "        \$confirm_password = \$_POST['confirm_password'];\n";
    $controller_code .= "        \$errors = [];\n";
    $controller_code .= "        \n";
    $controller_code .= "        // Find user by reset token\n";
    $controller_code .= "        \$user = User::findByResetToken(\$token);\n";
    $controller_code .= "        \n";
    $controller_code .= "        if (!\$user) {\n";
    $controller_code .= "            \$_SESSION['errors'] = ['Invalid or expired password reset link'];\n";
    $controller_code .= "            header('Location: index.php?page=login');\n";
    $controller_code .= "            exit;\n";
    $controller_code .= "        }\n";
    $controller_code .= "        \n";
    $controller_code .= "        // Validate password\n";
    $controller_code .= "        if (empty(\$password)) {\n";
    $controller_code .= "            \$errors[] = 'Password is required';\n";
    $controller_code .= "        } elseif (strlen(\$password) < 8) {\n";
    $controller_code .= "            \$errors[] = 'Password must be at least 8 characters';\n";
    $controller_code .= "        } elseif (\$password !== \$confirm_password) {\n";
    $controller_code .= "            \$errors[] = 'Passwords do not match';\n";
    $controller_code .= "        }\n";
    $controller_code .= "        \n";
    $controller_code .= "        // If there are errors, redirect back with errors\n";
    $controller_code .= "        if (!empty(\$errors)) {\n";
    $controller_code .= "            \$_SESSION['errors'] = \$errors;\n";
    $controller_code .= "            header('Location: index.php?page=reset_password&token=' . \$token);\n";
    $controller_code .= "            exit;\n";
    $controller_code .= "        }\n";
    $controller_code .= "        \n";
    $controller_code .= "        // Update password and clear reset token\n";
    $controller_code .= "        \$user->password = password_hash(\$password, PASSWORD_DEFAULT);\n";
    $controller_code .= "        \$user->update();\n";
    $controller_code .= "        \$user->clearResetToken();\n";
    $controller_code .= "        \n";
    $controller_code .= "        \$_SESSION['success'] = 'Password has been reset successfully! You can now log in with your new password.';\n";
    $controller_code .= "        header('Location: index.php?page=login');\n";
    $controller_code .= "        exit;\n";
    $controller_code .= "    }\n";
    
    $controller_code .= "}\n";
    
    return $controller_code;
}
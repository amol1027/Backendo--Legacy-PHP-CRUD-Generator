<?php
/**
 * Backendo: Legacy PHP CRUD Generator
 * View Generator
 */

// Include required files
require_once '../includes/config.php';
require_once '../includes/functions.php';

/**
 * Generate index view for a table
 * 
 * @param array $table The table definition
 * @return string The PHP view code
 */
function generate_index_view($table) {
    $table_name = sanitize_input($table['name']);
    $singular = to_singular($table_name);
    $title_case = to_title_case($table_name);
    
    $view_code = "<?php require_once 'includes/header.php'; ?>\n\n";
    $view_code .= "<div class=\"container mt-4\">\n";
    $view_code .= "    <div class=\"d-flex justify-content-between align-items-center mb-4\">\n";
    $view_code .= "        <h1>{$title_case}</h1>\n";
    $view_code .= "        <a href=\"index.php?page={$table_name}&action=create\" class=\"btn btn-primary\">\n";
    $view_code .= "            <i class=\"fas fa-plus\"></i> Add New\n";
    $view_code .= "        </a>\n";
    $view_code .= "    </div>\n\n";
    
    // Add alert messages
    $view_code .= "    <?php if (isset(\$_GET['success'])): ?>\n";
    $view_code .= "    <div class=\"alert alert-success alert-dismissible fade show\" role=\"alert\">\n";
    $view_code .= "        <?php\n";
    $view_code .= "        switch (\$_GET['success']) {\n";
    $view_code .= "            case 'created':\n";
    $view_code .= "                echo 'Record created successfully.';\n";
    $view_code .= "                break;\n";
    $view_code .= "            case 'updated':\n";
    $view_code .= "                echo 'Record updated successfully.';\n";
    $view_code .= "                break;\n";
    $view_code .= "            case 'deleted':\n";
    $view_code .= "                echo 'Record deleted successfully.';\n";
    $view_code .= "                break;\n";
    $view_code .= "            default:\n";
    $view_code .= "                echo 'Operation completed successfully.';\n";
    $view_code .= "        }\n";
    $view_code .= "        ?>\n";
    $view_code .= "        <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\" aria-label=\"Close\"></button>\n";
    $view_code .= "    </div>\n";
    $view_code .= "    <?php endif; ?>\n\n";
    
    $view_code .= "    <?php if (isset(\$_GET['error'])): ?>\n";
    $view_code .= "    <div class=\"alert alert-danger alert-dismissible fade show\" role=\"alert\">\n";
    $view_code .= "        <?php\n";
    $view_code .= "        switch (\$_GET['error']) {\n";
    $view_code .= "            case 'not_found':\n";
    $view_code .= "                echo 'Record not found.';\n";
    $view_code .= "                break;\n";
    $view_code .= "            case 'save_failed':\n";
    $view_code .= "                echo 'Failed to save record.';\n";
    $view_code .= "                break;\n";
    $view_code .= "            case 'update_failed':\n";
    $view_code .= "                echo 'Failed to update record.';\n";
    $view_code .= "                break;\n";
    $view_code .= "            case 'delete_failed':\n";
    $view_code .= "                echo 'Failed to delete record.';\n";
    $view_code .= "                break;\n";
    $view_code .= "            default:\n";
    $view_code .= "                echo 'An error occurred.';\n";
    $view_code .= "        }\n";
    $view_code .= "        ?>\n";
    $view_code .= "        <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\" aria-label=\"Close\"></button>\n";
    $view_code .= "    </div>\n";
    $view_code .= "    <?php endif; ?>\n\n";
    
    // Add search form if enabled
    if (isset($_SESSION['features']['search']) && $_SESSION['features']['search']) {
        $view_code .= "    <div class=\"card mb-4\">\n";
        $view_code .= "        <div class=\"card-body\">\n";
        $view_code .= "            <form method=\"get\" action=\"index.php\" class=\"row g-3\">\n";
        $view_code .= "                <input type=\"hidden\" name=\"page\" value=\"{$table_name}\">\n";
        $view_code .= "                <div class=\"col-md-10\">\n";
        $view_code .= "                    <input type=\"text\" class=\"form-control\" name=\"search\" placeholder=\"Search...\" value=\"<?php echo isset(\$_GET['search']) ? htmlspecialchars(\$_GET['search']) : ''; ?>\">\n";
        $view_code .= "                </div>\n";
        $view_code .= "                <div class=\"col-md-2\">\n";
        $view_code .= "                    <button type=\"submit\" class=\"btn btn-primary w-100\">Search</button>\n";
        $view_code .= "                </div>\n";
        $view_code .= "            </form>\n";
        $view_code .= "        </div>\n";
        $view_code .= "    </div>\n\n";
    }
    
    // Add export button if enabled
    if (isset($_SESSION['features']['export_csv']) && $_SESSION['features']['export_csv']) {
        $view_code .= "    <div class=\"mb-3\">\n";
        $view_code .= "        <a href=\"index.php?page={$table_name}&action=export_csv\" class=\"btn btn-outline-secondary\">\n";
        $view_code .= "            <i class=\"fas fa-file-export\"></i> Export to CSV\n";
        $view_code .= "        </a>\n";
        $view_code .= "    </div>\n\n";
    }
    
    // Table
    $view_code .= "    <div class=\"table-responsive\">\n";
    $view_code .= "        <table class=\"table table-striped table-hover\">\n";
    $view_code .= "            <thead>\n";
    $view_code .= "                <tr>\n";
    
    // Table headers
    foreach ($table['fields'] as $field) {
        $field_name = sanitize_input($field['name']);
        $field_label = to_title_case(str_replace('_', ' ', $field_name));
        $view_code .= "                    <th>{$field_label}</th>\n";
    }
    
    $view_code .= "                    <th>Actions</th>\n";
    $view_code .= "                </tr>\n";
    $view_code .= "            </thead>\n";
    $view_code .= "            <tbody>\n";
    $view_code .= "                <?php if (empty(\${$table_name})): ?>\n";
    $view_code .= "                <tr>\n";
    $view_code .= "                    <td colspan=\"" . (count($table['fields']) + 1) . "\" class=\"text-center\">No records found.</td>\n";
    $view_code .= "                </tr>\n";
    $view_code .= "                <?php else: ?>\n";
    $view_code .= "                <?php foreach (\${$table_name} as \${$singular}): ?>\n";
    $view_code .= "                <tr>\n";
    
    // Table cells
    foreach ($table['fields'] as $field) {
        $field_name = sanitize_input($field['name']);
        
        // Format display based on field type
        if ($field['type'] === 'boolean') {
            $view_code .= "                    <td><?php echo \${$singular}->{$field_name} ? 'Yes' : 'No'; ?></td>\n";
        } elseif ($field['type'] === 'text') {
            $view_code .= "                    <td><?php echo substr(htmlspecialchars(\${$singular}->{$field_name}), 0, 50) . (strlen(\${$singular}->{$field_name}) > 50 ? '...' : ''); ?></td>\n";
        } else {
            $view_code .= "                    <td><?php echo htmlspecialchars(\${$singular}->{$field_name}); ?></td>\n";
        }
    }
    
    // Action buttons
    $view_code .= "                    <td>\n";
    $view_code .= "                        <div class=\"btn-group\" role=\"group\" aria-label=\"Actions\">\n";
    
    // Determine primary key field
    $primary_key = 'id'; // Default primary key
    foreach ($table['fields'] as $field) {
        if (isset($field['constraints']) && in_array('primary', $field['constraints'])) {
            $primary_key = $field['name'];
            break;
        }
    }
    
    $view_code .= "                            <a href=\"index.php?page={$table_name}&action=show&id=<?php echo \${$singular}->{$primary_key}; ?>\" class=\"btn btn-sm btn-info\" title=\"View\">\n";
    $view_code .= "                                <i class=\"fas fa-eye\"></i>\n";
    $view_code .= "                            </a>\n";
    $view_code .= "                            <a href=\"index.php?page={$table_name}&action=edit&id=<?php echo \${$singular}->{$primary_key}; ?>\" class=\"btn btn-sm btn-warning\" title=\"Edit\">\n";
    $view_code .= "                                <i class=\"fas fa-edit\"></i>\n";
    $view_code .= "                            </a>\n";
    $view_code .= "                            <a href=\"#\" class=\"btn btn-sm btn-danger\" title=\"Delete\" onclick=\"if(confirm('Are you sure you want to delete this record?')) { window.location.href='index.php?page={$table_name}&action=delete&id=<?php echo \${$singular}->{$primary_key}; ?>'; } return false;\">\n";
    $view_code .= "                                <i class=\"fas fa-trash\"></i>\n";
    $view_code .= "                            </a>\n";
    $view_code .= "                        </div>\n";
    $view_code .= "                    </td>\n";
    $view_code .= "                </tr>\n";
    $view_code .= "                <?php endforeach; ?>\n";
    $view_code .= "                <?php endif; ?>\n";
    $view_code .= "            </tbody>\n";
    $view_code .= "        </table>\n";
    $view_code .= "    </div>\n\n";
    
    // Add pagination if enabled
    if (isset($_SESSION['features']['pagination']) && $_SESSION['features']['pagination']) {
        $view_code .= "    <?php if (isset(\$total_pages) && \$total_pages > 1): ?>\n";
        $view_code .= "    <nav aria-label=\"Page navigation\">\n";
        $view_code .= "        <ul class=\"pagination justify-content-center\">\n";
        $view_code .= "            <li class=\"page-item <?php echo \$page <= 1 ? 'disabled' : ''; ?>\">\n";
        $view_code .= "                <a class=\"page-link\" href=\"index.php?page={$table_name}&<?php echo isset(\$_GET['search']) ? 'search=' . urlencode(\$_GET['search']) . '&' : ''; ?>page=<?php echo \$page - 1; ?>\" aria-label=\"Previous\">\n";
        $view_code .= "                    <span aria-hidden=\"true\">&laquo;</span>\n";
        $view_code .= "                </a>\n";
        $view_code .= "            </li>\n";
        $view_code .= "            <?php for (\$i = 1; \$i <= \$total_pages; \$i++): ?>\n";
        $view_code .= "            <li class=\"page-item <?php echo \$page == \$i ? 'active' : ''; ?>\">\n";
        $view_code .= "                <a class=\"page-link\" href=\"index.php?page={$table_name}&<?php echo isset(\$_GET['search']) ? 'search=' . urlencode(\$_GET['search']) . '&' : ''; ?>page=<?php echo \$i; ?>\"><?php echo \$i; ?></a>\n";
        $view_code .= "            </li>\n";
        $view_code .= "            <?php endfor; ?>\n";
        $view_code .= "            <li class=\"page-item <?php echo \$page >= \$total_pages ? 'disabled' : ''; ?>\">\n";
        $view_code .= "                <a class=\"page-link\" href=\"index.php?page={$table_name}&<?php echo isset(\$_GET['search']) ? 'search=' . urlencode(\$_GET['search']) . '&' : ''; ?>page=<?php echo \$page + 1; ?>\" aria-label=\"Next\">\n";
        $view_code .= "                    <span aria-hidden=\"true\">&raquo;</span>\n";
        $view_code .= "                </a>\n";
        $view_code .= "            </li>\n";
        $view_code .= "        </ul>\n";
        $view_code .= "    </nav>\n";
        $view_code .= "    <?php endif; ?>\n\n";
    }
    
    $view_code .= "</div>\n\n";
    $view_code .= "<?php require_once 'includes/footer.php'; ?>\n";
    
    return $view_code;
}

/**
 * Generate show view for a table
 * 
 * @param array $table The table definition
 * @return string The PHP view code
 */
function generate_show_view($table) {
    $table_name = sanitize_input($table['name']);
    $singular = to_singular($table_name);
    $title_case = to_title_case($singular);
    
    $view_code = "<?php require_once 'includes/header.php'; ?>\n\n";
    $view_code .= "<div class=\"container mt-4\">\n";
    $view_code .= "    <div class=\"d-flex justify-content-between align-items-center mb-4\">\n";
    $view_code .= "        <h1>View {$title_case}</h1>\n";
    $view_code .= "        <div>\n";
    
    // Determine primary key field
    $primary_key = 'id'; // Default primary key
    foreach ($table['fields'] as $field) {
        if (isset($field['constraints']) && in_array('primary', $field['constraints'])) {
            $primary_key = $field['name'];
            break;
        }
    }
    
    $view_code .= "            <a href=\"index.php?page={$table_name}&action=edit&id=<?php echo \${$singular}->{$primary_key}; ?>\" class=\"btn btn-warning\">\n";
    $view_code .= "                <i class=\"fas fa-edit\"></i> Edit\n";
    $view_code .= "            </a>\n";
    $view_code .= "            <a href=\"index.php?page={$table_name}\" class=\"btn btn-secondary\">\n";
    $view_code .= "                <i class=\"fas fa-arrow-left\"></i> Back to List\n";
    $view_code .= "            </a>\n";
    $view_code .= "        </div>\n";
    $view_code .= "    </div>\n\n";
    
    $view_code .= "    <div class=\"card\">\n";
    $view_code .= "        <div class=\"card-body\">\n";
    $view_code .= "            <table class=\"table table-bordered\">\n";
    $view_code .= "                <tbody>\n";
    
    // Display all fields
    foreach ($table['fields'] as $field) {
        $field_name = sanitize_input($field['name']);
        $field_label = to_title_case(str_replace('_', ' ', $field_name));
        
        $view_code .= "                    <tr>\n";
        $view_code .= "                        <th width=\"200\">{$field_label}</th>\n";
        
        // Format display based on field type
        if ($field['type'] === 'boolean') {
            $view_code .= "                        <td><?php echo \${$singular}->{$field_name} ? 'Yes' : 'No'; ?></td>\n";
        } elseif ($field['type'] === 'text') {
            $view_code .= "                        <td><pre class=\"mb-0\"><?php echo htmlspecialchars(\${$singular}->{$field_name}); ?></pre></td>\n";
        } else {
            $view_code .= "                        <td><?php echo htmlspecialchars(\${$singular}->{$field_name}); ?></td>\n";
        }
        
        $view_code .= "                    </tr>\n";
    }
    
    // Add audit trail fields if enabled
    if (isset($_SESSION['features']['audit']) && $_SESSION['features']['audit']) {
        $view_code .= "                    <tr>\n";
        $view_code .= "                        <th>Created At</th>\n";
        $view_code .= "                        <td><?php echo \${$singular}->created_at; ?></td>\n";
        $view_code .= "                    </tr>\n";
        $view_code .= "                    <tr>\n";
        $view_code .= "                        <th>Updated At</th>\n";
        $view_code .= "                        <td><?php echo \${$singular}->updated_at; ?></td>\n";
        $view_code .= "                    </tr>\n";
    }
    
    $view_code .= "                </tbody>\n";
    $view_code .= "            </table>\n";
    $view_code .= "        </div>\n";
    $view_code .= "    </div>\n";
    $view_code .= "</div>\n\n";
    $view_code .= "<?php require_once 'includes/footer.php'; ?>\n";
    
    return $view_code;
}

/**
 * Generate create view for a table
 * 
 * @param array $table The table definition
 * @return string The PHP view code
 */
function generate_create_view($table) {
    $table_name = sanitize_input($table['name']);
    $singular = to_singular($table_name);
    $title_case = to_title_case($singular);
    
    $view_code = "<?php require_once 'includes/header.php'; ?>\n\n";
    $view_code .= "<div class=\"container mt-4\">\n";
    $view_code .= "    <div class=\"d-flex justify-content-between align-items-center mb-4\">\n";
    $view_code .= "        <h1>Create {$title_case}</h1>\n";
    $view_code .= "        <a href=\"index.php?page={$table_name}\" class=\"btn btn-secondary\">\n";
    $view_code .= "            <i class=\"fas fa-arrow-left\"></i> Back to List\n";
    $view_code .= "        </a>\n";
    $view_code .= "    </div>\n\n";
    
    // Add error message
    $view_code .= "    <?php if (isset(\$_GET['error'])): ?>\n";
    $view_code .= "    <div class=\"alert alert-danger alert-dismissible fade show\" role=\"alert\">\n";
    $view_code .= "        <?php\n";
    $view_code .= "        switch (\$_GET['error']) {\n";
    $view_code .= "            case 'empty_form':\n";
    $view_code .= "                echo 'Please fill out the form.';\n";
    $view_code .= "                break;\n";
    $view_code .= "            case 'save_failed':\n";
    $view_code .= "                echo 'Failed to save record.';\n";
    $view_code .= "                break;\n";
    $view_code .= "            default:\n";
    $view_code .= "                echo 'An error occurred.';\n";
    $view_code .= "        }\n";
    $view_code .= "        ?>\n";
    $view_code .= "        <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\" aria-label=\"Close\"></button>\n";
    $view_code .= "    </div>\n";
    $view_code .= "    <?php endif; ?>\n\n";
    
    $view_code .= "    <div class=\"card\">\n";
    $view_code .= "        <div class=\"card-body\">\n";
    $view_code .= "            <form method=\"post\" action=\"index.php?page={$table_name}&action=store\">\n";
    
    // Form fields
    foreach ($table['fields'] as $field) {
        $field_name = sanitize_input($field['name']);
        $field_label = to_title_case(str_replace('_', ' ', $field_name));
        
        // Skip auto-increment primary key
        if (isset($field['constraints']) && in_array('primary', $field['constraints']) && isset($field['auto_increment']) && $field['auto_increment']) {
            continue;
        }
        
        $view_code .= "                <div class=\"mb-3\">\n";
        $view_code .= "                    <label for=\"{$field_name}\" class=\"form-label\">{$field_label}</label>\n";
        
        // Generate appropriate input based on field type
        $input_type = get_input_type($field['type']);
        
        if ($field['type'] === 'text') {
            $view_code .= "                    <textarea class=\"form-control\" id=\"{$field_name}\" name=\"{$field_name}\" rows=\"5\"><?php echo isset(\$_POST['{$field_name}']) ? htmlspecialchars(\$_POST['{$field_name}']) : ''; ?></textarea>\n";
        } elseif ($field['type'] === 'boolean') {
            $view_code .= "                    <select class=\"form-select\" id=\"{$field_name}\" name=\"{$field_name}\">\n";
            $view_code .= "                        <option value=\"1\" <?php echo isset(\$_POST['{$field_name}']) && \$_POST['{$field_name}'] == '1' ? 'selected' : ''; ?>>Yes</option>\n";
            $view_code .= "                        <option value=\"0\" <?php echo isset(\$_POST['{$field_name}']) && \$_POST['{$field_name}'] == '0' ? 'selected' : ''; ?>>No</option>\n";
            $view_code .= "                    </select>\n";
        } elseif ($field['type'] === 'enum' && isset($field['options']) && is_array($field['options'])) {
            $view_code .= "                    <select class=\"form-select\" id=\"{$field_name}\" name=\"{$field_name}\">\n";
            foreach ($field['options'] as $option) {
                $view_code .= "                        <option value=\"{$option}\" <?php echo isset(\$_POST['{$field_name}']) && \$_POST['{$field_name}'] == '{$option}' ? 'selected' : ''; ?>>{$option}</option>\n";
            }
            $view_code .= "                    </select>\n";
        } else {
            $view_code .= "                    <input type=\"{$input_type}\" class=\"form-control\" id=\"{$field_name}\" name=\"{$field_name}\" value=\"<?php echo isset(\$_POST['{$field_name}']) ? htmlspecialchars(\$_POST['{$field_name}']) : ''; ?>\"";
            
            // Add required attribute if not nullable
            if (!$field['nullable']) {
                $view_code .= " required";
            }
            
            $view_code .= ">
                </div>
";
        }
    }
    
    $view_code .= "                <div class=\"d-grid gap-2 d-md-flex justify-content-md-end\">
";
    $view_code .= "                    <button type=\"submit\" class=\"btn btn-primary\">
";
    $view_code .= "                        <i class=\"fas fa-save\"></i> Save
";
    $view_code .= "                    </button>
";
    $view_code .= "                </div>
";
    $view_code .= "            </form>
";
    $view_code .= "        </div>
";
    $view_code .= "    </div>
";
    $view_code .= "</div>

";
    $view_code .= "<?php require_once 'includes/footer.php'; ?>
";
    
    return $view_code;
}

/**
 * Generate edit view for a table
 * 
 * @param array $table The table definition
 * @return string The PHP view code
 */
function generate_edit_view($table) {
    $table_name = sanitize_input($table['name']);
    $singular = to_singular($table_name);
    $title_case = to_title_case($singular);
    
    $view_code = "<?php require_once 'includes/header.php'; ?>

";
    $view_code .= "<div class=\"container mt-4\">
";
    $view_code .= "    <div class=\"d-flex justify-content-between align-items-center mb-4\">
";
    $view_code .= "        <h1>Edit {$title_case}</h1>
";
    $view_code .= "        <a href=\"index.php?page={$table_name}\" class=\"btn btn-secondary\">
";
    $view_code .= "            <i class=\"fas fa-arrow-left\"></i> Back to List
";
    $view_code .= "        </a>
";
    $view_code .= "    </div>

";
    
    // Add error message
    $view_code .= "    <?php if (isset(\$_GET['error'])): ?>
";
    $view_code .= "    <div class=\"alert alert-danger alert-dismissible fade show\" role=\"alert\">
";
    $view_code .= "        <?php
";
    $view_code .= "        switch (\$_GET['error']) {
";
    $view_code .= "            case 'not_found':
";
    $view_code .= "                echo 'Record not found.';
";
    $view_code .= "                break;
";
    $view_code .= "            case 'empty_form':
";
    $view_code .= "                echo 'Please fill out the form.';
";
    $view_code .= "                break;
";
    $view_code .= "            case 'update_failed':
";
    $view_code .= "                echo 'Failed to update record.';
";
    $view_code .= "                break;
";
    $view_code .= "            default:
";
    $view_code .= "                echo 'An error occurred.';
";
    $view_code .= "        }
";
    $view_code .= "        ?>
";
    $view_code .= "        <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\" aria-label=\"Close\"></button>
";
    $view_code .= "    </div>
";
    $view_code .= "    <?php endif; ?>

";
    
    // Find primary key
    $primary_key = 'id'; // Default primary key
    foreach ($table['fields'] as $field) {
        if (isset($field['constraints']) && in_array('primary', $field['constraints'])) {
            $primary_key = $field['name'];
            break;
        }
    }
    
    $view_code .= "    <div class=\"card\">
";
    $view_code .= "        <div class=\"card-body\">
";
    $view_code .= "            <form method=\"post\" action=\"index.php?page={$table_name}&action=update&id=<?php echo \${$singular}->{$primary_key}; ?>\">
";
    
    foreach ($table['fields'] as $field) {
        $field_name = sanitize_input($field['name']);
        $field_label = to_title_case(str_replace('_', ' ', $field_name));
        
        // Skip auto-increment primary key
        if (isset($field['constraints']) && in_array('primary', $field['constraints']) && isset($field['auto_increment']) && $field['auto_increment']) {
            continue;
        }
        
        $view_code .= "                <div class=\"mb-3\">
";
        $view_code .= "                    <label for=\"{$field_name}\" class=\"form-label\">{$field_label}</label>
";
        
        $input_type = get_input_type($field['type']);
        
        if ($field['type'] === 'text') {
            $view_code .= "                    <textarea class=\"form-control\" id=\"{$field_name}\" name=\"{$field_name}\" rows=\"5\"><?php echo htmlspecialchars(\${$singular}->{$field_name}); ?></textarea>
";
        } elseif ($field['type'] === 'boolean') {
            $view_code .= "                    <select class=\"form-select\" id=\"{$field_name}\" name=\"{$field_name}\">
";
            $view_code .= "                        <option value=\"1\" <?php echo \${$singular}->{$field_name} ? 'selected' : ''; ?>>Yes</option>
";
            $view_code .= "                        <option value=\"0\" <?php echo !\${$singular}->{$field_name} ? 'selected' : ''; ?>>No</option>
";
            $view_code .= "                    </select>
";
        } elseif ($field['type'] === 'enum' && isset($field['options']) && is_array($field['options'])) {
            $view_code .= "                    <select class=\"form-select\" id=\"{$field_name}\" name=\"{$field_name}\">
";
            foreach ($field['options'] as $option) {
                $view_code .= "                        <option value=\"{$option}\" <?php echo \${$singular}->{$field_name} == '{$option}' ? 'selected' : ''; ?>>{$option}</option>
";
            }
            $view_code .= "                    </select>
";
        } else {
            $view_code .= "                    <input type=\"{$input_type}\" class=\"form-control\" id=\"{$field_name}\" name=\"{$field_name}\" value=\"<?php echo htmlspecialchars(\${$singular}->{$field_name}); ?>\"";
            
            // Add required attribute if not nullable
            if (!$field['nullable']) {
                $view_code .= " required";
            }
            
            $view_code .= ">
";
        }
        
        $view_code .= "                </div>
";
    }
    
    $view_code .= "                <div class=\"d-grid gap-2 d-md-flex justify-content-md-end\">
";
    $view_code .= "                    <button type=\"submit\" class=\"btn btn-primary\">
";
    $view_code .= "                        <i class=\"fas fa-save\"></i> Update
";
    $view_code .= "                    </button>
";
    $view_code .= "                </div>
";
    $view_code .= "            </form>
";
    $view_code .= "        </div>
";
    $view_code .= "    </div>
";
    $view_code .= "</div>

";
    $view_code .= "<?php require_once 'includes/footer.php'; ?>
";
    
    return $view_code;
}

/**
 * Get HTML input type based on field type
 * 
 * @param string $field_type Database field type
 * @return string HTML input type
 */
function get_input_type($field_type) {
    switch ($field_type) {
        case 'int':
        case 'integer':
        case 'smallint':
        case 'tinyint':
        case 'mediumint':
        case 'bigint':
            return 'number';
        case 'decimal':
        case 'float':
        case 'double':
            return 'number';
        case 'date':
            return 'date';
        case 'time':
            return 'time';
        case 'datetime':
        case 'timestamp':
            return 'datetime-local';
        case 'email':
            return 'email';
        case 'url':
            return 'url';
        case 'password':
            return 'password';
        default:
            return 'text';
    }
}

/**
 * Generate login view
 * 
 * @return string The PHP view code
 */
function generate_login_view() {
    $view_code = "<?php require_once 'includes/header.php'; ?>\n\n";
    $view_code .= "<div class=\"container mt-5\">\n";
    $view_code .= "    <div class=\"row justify-content-center\">\n";
    $view_code .= "        <div class=\"col-md-6\">\n";
    $view_code .= "            <div class=\"card\">\n";
    $view_code .= "                <div class=\"card-header\">\n";
    $view_code .= "                    <h3 class=\"mb-0\">Login</h3>\n";
    $view_code .= "                </div>\n";
    $view_code .= "                <div class=\"card-body\">\n";
    
    // Add success and error messages
    $view_code .= "                    <?php if (isset(\$_SESSION['success'])): ?>\n";
    $view_code .= "                    <div class=\"alert alert-success alert-dismissible fade show\" role=\"alert\">\n";
    $view_code .= "                        <?php echo \$_SESSION['success']; ?>\n";
    $view_code .= "                        <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\" aria-label=\"Close\"></button>\n";
    $view_code .= "                    </div>\n";
    $view_code .= "                    <?php unset(\$_SESSION['success']); ?>\n";
    $view_code .= "                    <?php endif; ?>\n\n";
    
    $view_code .= "                    <?php if (isset(\$_SESSION['errors'])): ?>\n";
    $view_code .= "                    <div class=\"alert alert-danger alert-dismissible fade show\" role=\"alert\">\n";
    $view_code .= "                        <ul class=\"mb-0\">\n";
    $view_code .= "                            <?php foreach (\$_SESSION['errors'] as \$error): ?>\n";
    $view_code .= "                            <li><?php echo \$error; ?></li>\n";
    $view_code .= "                            <?php endforeach; ?>\n";
    $view_code .= "                        </ul>\n";
    $view_code .= "                        <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\" aria-label=\"Close\"></button>\n";
    $view_code .= "                    </div>\n";
    $view_code .= "                    <?php unset(\$_SESSION['errors']); ?>\n";
    $view_code .= "                    <?php endif; ?>\n\n";
    
    // Login form
    $view_code .= "                    <form action=\"index.php?page=auth&action=login_process\" method=\"post\">\n";
    $view_code .= "                        <div class=\"mb-3\">\n";
    $view_code .= "                            <label for=\"username\" class=\"form-label\">Username</label>\n";
    $view_code .= "                            <input type=\"text\" class=\"form-control\" id=\"username\" name=\"username\" value=\"<?php echo isset(\$_SESSION['old_input']['username']) ? htmlspecialchars(\$_SESSION['old_input']['username']) : ''; ?>\" required>\n";
    $view_code .= "                        </div>\n";
    $view_code .= "                        <div class=\"mb-3\">\n";
    $view_code .= "                            <label for=\"password\" class=\"form-label\">Password</label>\n";
    $view_code .= "                            <input type=\"password\" class=\"form-control\" id=\"password\" name=\"password\" required>\n";
    $view_code .= "                        </div>\n";
    $view_code .= "                        <div class=\"mb-3 form-check\">\n";
    $view_code .= "                            <input type=\"checkbox\" class=\"form-check-input\" id=\"remember_me\" name=\"remember_me\">\n";
    $view_code .= "                            <label class=\"form-check-label\" for=\"remember_me\">Remember me</label>\n";
    $view_code .= "                        </div>\n";
    $view_code .= "                        <div class=\"d-grid gap-2\">\n";
    $view_code .= "                            <button type=\"submit\" class=\"btn btn-primary\">Login</button>\n";
    $view_code .= "                        </div>\n";
    $view_code .= "                    </form>\n";
    $view_code .= "                    <div class=\"mt-3 text-center\">\n";
    $view_code .= "                        <a href=\"index.php?page=auth&action=forgot_password\" class=\"text-decoration-none\">Forgot Password?</a>\n";
    $view_code .= "                    </div>\n";
    $view_code .= "                    <hr>\n";
    $view_code .= "                    <div class=\"text-center\">\n";
    $view_code .= "                        <p>Don't have an account? <a href=\"index.php?page=auth&action=register\" class=\"text-decoration-none\">Register</a></p>\n";
    $view_code .= "                    </div>\n";
    $view_code .= "                </div>\n";
    $view_code .= "            </div>\n";
    $view_code .= "        </div>\n";
    $view_code .= "    </div>\n";
    $view_code .= "</div>\n\n";
    $view_code .= "<?php require_once 'includes/footer.php'; ?>\n";
    
    return $view_code;
}

/**
 * Generate register view
 * 
 * @return string The PHP view code
 */
function generate_register_view() {
    $view_code = "<?php require_once 'includes/header.php'; ?>\n\n";
    $view_code .= "<div class=\"container mt-5\">\n";
    $view_code .= "    <div class=\"row justify-content-center\">\n";
    $view_code .= "        <div class=\"col-md-6\">\n";
    $view_code .= "            <div class=\"card\">\n";
    $view_code .= "                <div class=\"card-header\">\n";
    $view_code .= "                    <h3 class=\"mb-0\">Register</h3>\n";
    $view_code .= "                </div>\n";
    $view_code .= "                <div class=\"card-body\">\n";
    
    // Add error messages
    $view_code .= "                    <?php if (isset(\$_SESSION['errors'])): ?>\n";
    $view_code .= "                    <div class=\"alert alert-danger alert-dismissible fade show\" role=\"alert\">\n";
    $view_code .= "                        <ul class=\"mb-0\">\n";
    $view_code .= "                            <?php foreach (\$_SESSION['errors'] as \$error): ?>\n";
    $view_code .= "                            <li><?php echo \$error; ?></li>\n";
    $view_code .= "                            <?php endforeach; ?>\n";
    $view_code .= "                        </ul>\n";
    $view_code .= "                        <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\" aria-label=\"Close\"></button>\n";
    $view_code .= "                    </div>\n";
    $view_code .= "                    <?php unset(\$_SESSION['errors']); ?>\n";
    $view_code .= "                    <?php endif; ?>\n\n";
    
    // Register form
    $view_code .= "                    <form action=\"index.php?page=auth&action=register_store\" method=\"post\">\n";
    $view_code .= "                        <div class=\"mb-3\">\n";
    $view_code .= "                            <label for=\"username\" class=\"form-label\">Username</label>\n";
    $view_code .= "                            <input type=\"text\" class=\"form-control\" id=\"username\" name=\"username\" value=\"<?php echo isset(\$_SESSION['old_input']['username']) ? htmlspecialchars(\$_SESSION['old_input']['username']) : ''; ?>\" required>\n";
    $view_code .= "                        </div>\n";
    $view_code .= "                        <div class=\"mb-3\">\n";
    $view_code .= "                            <label for=\"email\" class=\"form-label\">Email</label>\n";
    $view_code .= "                            <input type=\"email\" class=\"form-control\" id=\"email\" name=\"email\" value=\"<?php echo isset(\$_SESSION['old_input']['email']) ? htmlspecialchars(\$_SESSION['old_input']['email']) : ''; ?>\" required>\n";
    $view_code .= "                        </div>\n";
    $view_code .= "                        <div class=\"mb-3\">\n";
    $view_code .= "                            <label for=\"password\" class=\"form-label\">Password</label>\n";
    $view_code .= "                            <input type=\"password\" class=\"form-control\" id=\"password\" name=\"password\" required>\n";
    $view_code .= "                        </div>\n";
    $view_code .= "                        <div class=\"mb-3\">\n";
    $view_code .= "                            <label for=\"password_confirm\" class=\"form-label\">Confirm Password</label>\n";
    $view_code .= "                            <input type=\"password\" class=\"form-control\" id=\"password_confirm\" name=\"password_confirm\" required>\n";
    $view_code .= "                        </div>\n";
    $view_code .= "                        <div class=\"d-grid gap-2\">\n";
    $view_code .= "                            <button type=\"submit\" class=\"btn btn-primary\">Register</button>\n";
    $view_code .= "                        </div>\n";
    $view_code .= "                    </form>\n";
    $view_code .= "                    <hr>\n";
    $view_code .= "                    <div class=\"text-center\">\n";
    $view_code .= "                        <p>Already have an account? <a href=\"index.php?page=auth&action=login\" class=\"text-decoration-none\">Login</a></p>\n";
    $view_code .= "                    </div>\n";
    $view_code .= "                </div>\n";
    $view_code .= "            </div>\n";
    $view_code .= "        </div>\n";
    $view_code .= "    </div>\n";
    $view_code .= "</div>\n\n";
    $view_code .= "<?php require_once 'includes/footer.php'; ?>\n";
    
    return $view_code;
}

/**
 * Generate profile view
 * 
 * @return string The PHP view code
 */
function generate_profile_view() {
    $view_code = "<?php require_once 'includes/header.php'; ?>\n\n";
    $view_code .= "<div class=\"container mt-5\">\n";
    $view_code .= "    <div class=\"row justify-content-center\">\n";
    $view_code .= "        <div class=\"col-md-8\">\n";
    $view_code .= "            <div class=\"card\">\n";
    $view_code .= "                <div class=\"card-header\">\n";
    $view_code .= "                    <h3 class=\"mb-0\">My Profile</h3>\n";
    $view_code .= "                </div>\n";
    $view_code .= "                <div class=\"card-body\">\n";
    
    // Add success and error messages
    $view_code .= "                    <?php if (isset(\$_SESSION['success'])): ?>\n";
    $view_code .= "                    <div class=\"alert alert-success alert-dismissible fade show\" role=\"alert\">\n";
    $view_code .= "                        <?php echo \$_SESSION['success']; ?>\n";
    $view_code .= "                        <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\" aria-label=\"Close\"></button>\n";
    $view_code .= "                    </div>\n";
    $view_code .= "                    <?php unset(\$_SESSION['success']); ?>\n";
    $view_code .= "                    <?php endif; ?>\n\n";
    
    $view_code .= "                    <?php if (isset(\$_SESSION['errors'])): ?>\n";
    $view_code .= "                    <div class=\"alert alert-danger alert-dismissible fade show\" role=\"alert\">\n";
    $view_code .= "                        <ul class=\"mb-0\">\n";
    $view_code .= "                            <?php foreach (\$_SESSION['errors'] as \$error): ?>\n";
    $view_code .= "                            <li><?php echo \$error; ?></li>\n";
    $view_code .= "                            <?php endforeach; ?>\n";
    $view_code .= "                        </ul>\n";
    $view_code .= "                        <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\" aria-label=\"Close\"></button>\n";
    $view_code .= "                    </div>\n";
    $view_code .= "                    <?php unset(\$_SESSION['errors']); ?>\n";
    $view_code .= "                    <?php endif; ?>\n\n";
    
    // Profile form
    $view_code .= "                    <form action=\"index.php?page=auth&action=update_profile\" method=\"post\">\n";
    $view_code .= "                        <div class=\"mb-3\">\n";
    $view_code .= "                            <label for=\"username\" class=\"form-label\">Username</label>\n";
    $view_code .= "                            <input type=\"text\" class=\"form-control\" id=\"username\" name=\"username\" value=\"<?php echo htmlspecialchars(\$user->username); ?>\" required>\n";
    $view_code .= "                        </div>\n";
    $view_code .= "                        <div class=\"mb-3\">\n";
    $view_code .= "                            <label for=\"email\" class=\"form-label\">Email</label>\n";
    $view_code .= "                            <input type=\"email\" class=\"form-control\" id=\"email\" name=\"email\" value=\"<?php echo htmlspecialchars(\$user->email); ?>\" required>\n";
    $view_code .= "                        </div>\n";
    $view_code .= "                        <div class=\"mb-3\">\n";
    $view_code .= "                            <label for=\"current_password\" class=\"form-label\">Current Password</label>\n";
    $view_code .= "                            <input type=\"password\" class=\"form-control\" id=\"current_password\" name=\"current_password\" required>\n";
    $view_code .= "                            <div class=\"form-text\">Enter your current password to confirm changes</div>\n";
    $view_code .= "                        </div>\n";
    $view_code .= "                        <div class=\"mb-3\">\n";
    $view_code .= "                            <label for=\"new_password\" class=\"form-label\">New Password</label>\n";
    $view_code .= "                            <input type=\"password\" class=\"form-control\" id=\"new_password\" name=\"new_password\">\n";
    $view_code .= "                            <div class=\"form-text\">Leave blank if you don't want to change your password</div>\n";
    $view_code .= "                        </div>\n";
    $view_code .= "                        <div class=\"mb-3\">\n";
    $view_code .= "                            <label for=\"new_password_confirm\" class=\"form-label\">Confirm New Password</label>\n";
    $view_code .= "                            <input type=\"password\" class=\"form-control\" id=\"new_password_confirm\" name=\"new_password_confirm\">\n";
    $view_code .= "                        </div>\n";
    $view_code .= "                        <div class=\"d-grid gap-2\">\n";
    $view_code .= "                            <button type=\"submit\" class=\"btn btn-primary\">Update Profile</button>\n";
    $view_code .= "                        </div>\n";
    $view_code .= "                    </form>\n";
    $view_code .= "                </div>\n";
    $view_code .= "            </div>\n";
    $view_code .= "        </div>\n";
    $view_code .= "    </div>\n";
    $view_code .= "</div>\n\n";
    $view_code .= "<?php require_once 'includes/footer.php'; ?>\n";
    
    return $view_code;
}

/**
 * Generate forgot password view
 * 
 * @return string The PHP view code
 */
function generate_forgot_password_view() {
    $view_code = "<?php require_once 'includes/header.php'; ?>\n\n";
    $view_code .= "<div class=\"container mt-5\">\n";
    $view_code .= "    <div class=\"row justify-content-center\">\n";
    $view_code .= "        <div class=\"col-md-6\">\n";
    $view_code .= "            <div class=\"card\">\n";
    $view_code .= "                <div class=\"card-header\">\n";
    $view_code .= "                    <h3 class=\"mb-0\">Forgot Password</h3>\n";
    $view_code .= "                </div>\n";
    $view_code .= "                <div class=\"card-body\">\n";
    
    // Add success and error messages
    $view_code .= "                    <?php if (isset(\$_SESSION['success'])): ?>\n";
    $view_code .= "                    <div class=\"alert alert-success alert-dismissible fade show\" role=\"alert\">\n";
    $view_code .= "                        <?php echo \$_SESSION['success']; ?>\n";
    $view_code .= "                        <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\" aria-label=\"Close\"></button>\n";
    $view_code .= "                    </div>\n";
    $view_code .= "                    <?php unset(\$_SESSION['success']); ?>\n";
    $view_code .= "                    <?php endif; ?>\n\n";
    
    $view_code .= "                    <?php if (isset(\$_SESSION['errors'])): ?>\n";
    $view_code .= "                    <div class=\"alert alert-danger alert-dismissible fade show\" role=\"alert\">\n";
    $view_code .= "                        <ul class=\"mb-0\">\n";
    $view_code .= "                            <?php foreach (\$_SESSION['errors'] as \$error): ?>\n";
    $view_code .= "                            <li><?php echo \$error; ?></li>\n";
    $view_code .= "                            <?php endforeach; ?>\n";
    $view_code .= "                        </ul>\n";
    $view_code .= "                        <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\" aria-label=\"Close\"></button>\n";
    $view_code .= "                    </div>\n";
    $view_code .= "                    <?php unset(\$_SESSION['errors']); ?>\n";
    $view_code .= "                    <?php endif; ?>\n\n";
    
    // Forgot password form
    $view_code .= "                    <p class=\"mb-3\">Enter your email address and we'll send you a link to reset your password.</p>\n";
    $view_code .= "                    <form action=\"index.php?page=auth&action=forgot_password_process\" method=\"post\">\n";
    $view_code .= "                        <div class=\"mb-3\">\n";
    $view_code .= "                            <label for=\"email\" class=\"form-label\">Email</label>\n";
    $view_code .= "                            <input type=\"email\" class=\"form-control\" id=\"email\" name=\"email\" value=\"<?php echo isset(\$_SESSION['old_input']['email']) ? htmlspecialchars(\$_SESSION['old_input']['email']) : ''; ?>\" required>\n";
    $view_code .= "                        </div>\n";
    $view_code .= "                        <div class=\"d-grid gap-2\">\n";
    $view_code .= "                            <button type=\"submit\" class=\"btn btn-primary\">Send Reset Link</button>\n";
    $view_code .= "                        </div>\n";
    $view_code .= "                    </form>\n";
    $view_code .= "                    <hr>\n";
    $view_code .= "                    <div class=\"text-center\">\n";
    $view_code .= "                        <p>Remember your password? <a href=\"index.php?page=auth&action=login\" class=\"text-decoration-none\">Login</a></p>\n";
    $view_code .= "                    </div>\n";
    $view_code .= "                </div>\n";
    $view_code .= "            </div>\n";
    $view_code .= "        </div>\n";
    $view_code .= "    </div>\n";
    $view_code .= "</div>\n\n";
    $view_code .= "<?php require_once 'includes/footer.php'; ?>\n";
    
    return $view_code;
}

/**
 * Generate reset password view
 * 
 * @return string The PHP view code
 */
function generate_reset_password_view() {
    $view_code = "<?php require_once 'includes/header.php'; ?>\n\n";
    $view_code .= "<div class=\"container mt-5\">\n";
    $view_code .= "    <div class=\"row justify-content-center\">\n";
    $view_code .= "        <div class=\"col-md-6\">\n";
    $view_code .= "            <div class=\"card\">\n";
    $view_code .= "                <div class=\"card-header\">\n";
    $view_code .= "                    <h3 class=\"mb-0\">Reset Password</h3>\n";
    $view_code .= "                </div>\n";
    $view_code .= "                <div class=\"card-body\">\n";
    
    // Add error messages
    $view_code .= "                    <?php if (isset(\$_SESSION['errors'])): ?>\n";
    $view_code .= "                    <div class=\"alert alert-danger alert-dismissible fade show\" role=\"alert\">\n";
    $view_code .= "                        <ul class=\"mb-0\">\n";
    $view_code .= "                            <?php foreach (\$_SESSION['errors'] as \$error): ?>\n";
    $view_code .= "                            <li><?php echo \$error; ?></li>\n";
    $view_code .= "                            <?php endforeach; ?>\n";
    $view_code .= "                        </ul>\n";
    $view_code .= "                        <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\" aria-label=\"Close\"></button>\n";
    $view_code .= "                    </div>\n";
    $view_code .= "                    <?php unset(\$_SESSION['errors']); ?>\n";
    $view_code .= "                    <?php endif; ?>\n\n";
    
    // Reset password form
    $view_code .= "                    <form action=\"index.php?page=auth&action=reset_password_process\" method=\"post\">\n";
    $view_code .= "                        <input type=\"hidden\" name=\"token\" value=\"<?php echo htmlspecialchars(\$token); ?>\">\n";
    $view_code .= "                        <div class=\"mb-3\">\n";
    $view_code .= "                            <label for=\"password\" class=\"form-label\">New Password</label>\n";
    $view_code .= "                            <input type=\"password\" class=\"form-control\" id=\"password\" name=\"password\" required>\n";
    $view_code .= "                        </div>\n";
    $view_code .= "                        <div class=\"mb-3\">\n";
    $view_code .= "                            <label for=\"password_confirm\" class=\"form-label\">Confirm New Password</label>\n";
    $view_code .= "                            <input type=\"password\" class=\"form-control\" id=\"password_confirm\" name=\"password_confirm\" required>\n";
    $view_code .= "                        </div>\n";
    $view_code .= "                        <div class=\"d-grid gap-2\">\n";
    $view_code .= "                            <button type=\"submit\" class=\"btn btn-primary\">Reset Password</button>\n";
    $view_code .= "                        </div>\n";
    $view_code .= "                    </form>\n";
    $view_code .= "                </div>\n";
    $view_code .= "            </div>\n";
    $view_code .= "        </div>\n";
    $view_code .= "    </div>\n";
    $view_code .= "</div>\n\n";
    $view_code .= "<?php require_once 'includes/footer.php'; ?>\n";
    
    return $view_code;
}
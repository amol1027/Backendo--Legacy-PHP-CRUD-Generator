<?php
// Get the file parameter from the URL
$file = isset($_GET['file']) ? sanitize_input($_GET['file']) : '';
$file_path = 'output/' . $file;

// Check if the file exists
if (!empty($file) && file_exists($file_path)) {
    $download_url = 'output/' . $file;
} else {
    $download_url = '';
}
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Download Your Project</h4>
            </div>
            <div class="card-body text-center">
                <?php if (!empty($download_url)): ?>
                    <div class="mb-4">
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                        <h3 class="mt-3">Project Generated Successfully!</h3>
                        <p class="lead">Your PHP CRUD application has been generated and is ready for download.</p>
                        <p><strong>Note:</strong> You will need to create a database before importing the SQL file included in the project.</p>
                    </div>
                    
                    <div class="mb-4">
                        <a href="<?php echo $download_url; ?>" class="btn btn-primary btn-lg" download>
                            <i class="bi bi-download"></i> Download Project
                        </a>
                    </div>
                    
                    <div class="alert alert-info">
                        <h5><i class="bi bi-info-circle"></i> Next Steps</h5>
                        <ol class="text-start">
                            <li>Extract the ZIP file to your web server directory</li>
                            <li>Create a new database in your MySQL server</li>
                            <li>Import the included SQL file into your newly created database</li>
                            <li>Update the database connection settings in <code>config/db.php</code> with your database credentials</li>
                            <li>Access your application through the browser</li>
                        </ol>
                    </div>
ke a db.sql file to                     
                    <div class="alert alert-warning">
                        <h5><i class="bi bi-exclamation-triangle"></i> Troubleshooting</h5>
                        <p class="text-start">If you encounter issues with project generation, please check the following:</p>
                        <ol class="text-start">
                            <li>
                                <strong>Enable ZIP Extension in XAMPP:</strong>
                                <ul>
                                    <li>Open <code>C:\xampp\php\php.ini</code></li>
                                    <li>Find the line <code>;extension=zip</code> and remove the semicolon to uncomment it</li>
                                    <li>Save the file and restart Apache</li>
                                </ul>
                            </li>
                            <li>
                                <strong>Check File Permissions:</strong>
                                <ul>
                                    <li>Make sure the <code>output</code> and <code>temp</code> directories have write permissions</li>
                                </ul>
                            </li>
                        </ol>
                    </div>
                <?php else: ?>
                    <div class="mb-4">
                        <i class="bi bi-exclamation-triangle-fill text-warning" style="font-size: 4rem;"></i>
                        <h3 class="mt-3">File Not Found</h3>
                        <p class="lead">The requested file could not be found. Please try generating your project again.</p>
                    </div>
                    
                    <div class="mb-4">
                        <a href="index.php?step=5" class="btn btn-primary">
                            <i class="bi bi-arrow-left"></i> Back to Generator
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
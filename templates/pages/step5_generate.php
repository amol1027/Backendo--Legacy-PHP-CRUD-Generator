<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Step 5: Generate Project</h4>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                    <h3 class="mt-3">Ready to Generate!</h3>
                    <p class="lead">Your project configuration is complete. Click the button below to generate your PHP CRUD application.</p>
                </div>
                
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Project Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Project Name:</strong> <?php echo isset($_SESSION['project']['name']) ? htmlspecialchars($_SESSION['project']['name']) : 'Not set'; ?></p>
                                <p><strong>Author:</strong> <?php echo isset($_SESSION['project']['author']) ? htmlspecialchars($_SESSION['project']['author']) : 'Not set'; ?></p>
                                <p><strong>Base URL:</strong> http://<?php echo isset($_SESSION['project']['base_url']) ? htmlspecialchars($_SESSION['project']['base_url']) : 'localhost'; ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Tables:</strong> <?php echo isset($_SESSION['tables']) ? count($_SESSION['tables']) : 0; ?></p>
                                <p><strong>Authentication:</strong> <?php echo isset($_SESSION['auth']['enabled']) && $_SESSION['auth']['enabled'] ? 'Enabled' : 'Disabled'; ?></p>
                                <p><strong>Selected Features:</strong> 
                                    <?php 
                                    if (isset($_SESSION['features'])) {
                                        $enabled_features = array_filter($_SESSION['features']);
                                        echo count($enabled_features);
                                    } else {
                                        echo '0';
                                    }
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Output Options</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="include_readme" name="include_readme" checked>
                                <label class="form-check-label" for="include_readme">
                                    Include README.md with installation instructions
                                </label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="include_sample_data" name="include_sample_data" checked>
                                <label class="form-check-label" for="include_sample_data">
                                    Include sample data in SQL file
                                </label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="minify_output" name="minify_output">
                                <label class="form-check-label" for="minify_output">
                                    Minify output files (reduces file size)
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <form action="index.php?step=5&action=generate" method="post" id="generate-form">
                    <input type="hidden" name="output_options" id="output-options" value="">
                    
                    <div class="d-flex justify-content-between mt-4">
                        <a href="index.php?step=4" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Previous
                        </a>
                        <button type="submit" class="btn btn-success btn-lg" id="generate-btn">
                            <i class="bi bi-lightning-charge"></i> Generate Project
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Handle form submission
        $('#generate-form').on('submit', function() {
            // Show loading spinner
            showSpinner('Generating your project. This may take a moment...');
            
            // Collect output options
            const outputOptions = {
                include_readme: $('#include_readme').is(':checked'),
                include_sample_data: $('#include_sample_data').is(':checked'),
                minify_output: $('#minify_output').is(':checked')
            };
            
            $('#output-options').val(JSON.stringify(outputOptions));
            
            // Allow form submission
            return true;
        });
    });
</script>
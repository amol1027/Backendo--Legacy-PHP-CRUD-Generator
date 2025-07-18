<?php
// Get project data from session if available
$project_name = isset($_SESSION['project']['name']) ? $_SESSION['project']['name'] : '';
$author_name = isset($_SESSION['project']['author']) ? $_SESSION['project']['author'] : '';
$base_url = isset($_SESSION['project']['base_url']) ? $_SESSION['project']['base_url'] : '';
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Step 1: Project Setup</h4>
            </div>
            <div class="card-body">
                <form action="index.php?step=1" method="post" class="needs-validation" novalidate>
                    <div class="mb-3">
                        <label for="project_name" class="form-label">Project Name</label>
                        <input type="text" class="form-control" id="project_name" name="project_name" value="<?php echo $project_name; ?>" required>
                        <div class="form-text">This will be used for folder names and database naming.</div>
                        <div class="invalid-feedback">Please enter a project name.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="author_name" class="form-label">Author Name</label>
                        <input type="text" class="form-control" id="author_name" name="author_name" value="<?php echo $author_name; ?>">
                        <div class="form-text">Will be included in generated file headers.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="base_url" class="form-label">Base URL</label>
                        <div class="input-group">
                            <span class="input-group-text">http://</span>
                            <input type="text" class="form-control" id="base_url" name="base_url" value="<?php echo $base_url; ?>" placeholder="localhost/myproject">
                        </div>
                        <div class="form-text">The base URL where your project will be hosted.</div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="index.php" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Next <i class="bi bi-arrow-right"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
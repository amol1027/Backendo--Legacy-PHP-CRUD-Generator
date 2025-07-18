<?php
// Get features data from session if available
$features = isset($_SESSION['features']) ? $_SESSION['features'] : [
    'crud' => true,
    'export_csv' => false,
    'search' => false,
    'soft_delete' => false
];
?>

<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Step 4: Select Features</h4>
            </div>
            <div class="card-body">
                <p class="lead">Choose the features you want to include in your generated project.</p>
                
                <form action="index.php?step=4" method="post">
                    <div class="row mb-4">
                        <div class="col-md-6 col-lg-3 mb-4">
                            <div class="card feature-card h-100">
                                <div class="card-body text-center">
                                    <div class="feature-icon">
                                        <i class="bi bi-table"></i>
                                    </div>
                                    <h5>CRUD Operations</h5>
                                    <p class="text-muted">Create, Read, Update, Delete functionality for all tables.</p>
                                    <div class="form-check form-switch d-flex justify-content-center">
                                        <input class="form-check-input" type="checkbox" id="feature_crud" name="feature_crud" <?php echo isset($features['crud']) && $features['crud'] ? 'checked' : ''; ?>>
                                        <label class="form-check-label ms-2" for="feature_crud">Include</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 col-lg-3 mb-4">
                            <div class="card feature-card h-100">
                                <div class="card-body text-center">
                                    <div class="feature-icon">
                                        <i class="bi bi-file-earmark-spreadsheet"></i>
                                    </div>
                                    <h5>Export to CSV</h5>
                                    <p class="text-muted">Add functionality to export table data to CSV files.</p>
                                    <div class="form-check form-switch d-flex justify-content-center">
                                        <input class="form-check-input" type="checkbox" id="feature_export" name="feature_export" <?php echo isset($features['export_csv']) && $features['export_csv'] ? 'checked' : ''; ?>>
                                        <label class="form-check-label ms-2" for="feature_export">Include</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 col-lg-3 mb-4">
                            <div class="card feature-card h-100">
                                <div class="card-body text-center">
                                    <div class="feature-icon">
                                        <i class="bi bi-search"></i>
                                    </div>
                                    <h5>Search Functionality</h5>
                                    <p class="text-muted">Add search bars to filter data in tables.</p>
                                    <div class="form-check form-switch d-flex justify-content-center">
                                        <input class="form-check-input" type="checkbox" id="feature_search" name="feature_search" <?php echo isset($features['search']) && $features['search'] ? 'checked' : ''; ?>>
                                        <label class="form-check-label ms-2" for="feature_search">Include</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 col-lg-3 mb-4">
                            <div class="card feature-card h-100">
                                <div class="card-body text-center">
                                    <div class="feature-icon">
                                        <i class="bi bi-trash"></i>
                                    </div>
                                    <h5>Soft Delete</h5>
                                    <p class="text-muted">Mark records as deleted instead of permanent deletion.</p>
                                    <div class="form-check form-switch d-flex justify-content-center">
                                        <input class="form-check-input" type="checkbox" id="feature_soft_delete" name="feature_soft_delete" <?php echo isset($features['soft_delete']) && $features['soft_delete'] ? 'checked' : ''; ?>>
                                        <label class="form-check-label ms-2" for="feature_soft_delete">Include</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6 col-lg-3 mb-4">
                            <div class="card feature-card h-100">
                                <div class="card-body text-center">
                                    <div class="feature-icon">
                                        <i class="bi bi-sort-numeric-down"></i>
                                    </div>
                                    <h5>Pagination</h5>
                                    <p class="text-muted">Add pagination to data tables for better performance.</p>
                                    <div class="form-check form-switch d-flex justify-content-center">
                                        <input class="form-check-input" type="checkbox" id="feature_pagination" name="feature_pagination" <?php echo isset($features['pagination']) && $features['pagination'] ? 'checked' : ''; ?>>
                                        <label class="form-check-label ms-2" for="feature_pagination">Include</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 col-lg-3 mb-4">
                            <div class="card feature-card h-100">
                                <div class="card-body text-center">
                                    <div class="feature-icon">
                                        <i class="bi bi-filter-square"></i>
                                    </div>
                                    <h5>Filtering</h5>
                                    <p class="text-muted">Add filters to data tables for better data exploration.</p>
                                    <div class="form-check form-switch d-flex justify-content-center">
                                        <input class="form-check-input" type="checkbox" id="feature_filtering" name="feature_filtering" <?php echo isset($features['filtering']) && $features['filtering'] ? 'checked' : ''; ?>>
                                        <label class="form-check-label ms-2" for="feature_filtering">Include</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 col-lg-3 mb-4">
                            <div class="card feature-card h-100">
                                <div class="card-body text-center">
                                    <div class="feature-icon">
                                        <i class="bi bi-clock-history"></i>
                                    </div>
                                    <h5>Audit Trail</h5>
                                    <p class="text-muted">Track changes to records with timestamps and user info.</p>
                                    <div class="form-check form-switch d-flex justify-content-center">
                                        <input class="form-check-input" type="checkbox" id="feature_audit" name="feature_audit" <?php echo isset($features['audit']) && $features['audit'] ? 'checked' : ''; ?>>
                                        <label class="form-check-label ms-2" for="feature_audit">Include</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 col-lg-3 mb-4">
                            <div class="card feature-card h-100">
                                <div class="card-body text-center">
                                    <div class="feature-icon">
                                        <i class="bi bi-wind"></i>
                                    </div>
                                    <h5>Tailwind CSS</h5>
                                    <p class="text-muted">Use Tailwind CSS for styling instead of Bootstrap.</p>
                                    <div class="form-check form-switch d-flex justify-content-center">
                                        <input class="form-check-input" type="checkbox" id="feature_tailwind" name="feature_tailwind" <?php echo isset($features['tailwind']) && $features['tailwind'] ? 'checked' : ''; ?>>
                                        <label class="form-check-label ms-2" for="feature_tailwind">Include</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between mt-4">
                        <a href="index.php?step=3" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Previous
                        </a>
                        <button type="submit" class="btn btn-primary">
                            Next <i class="bi bi-arrow-right"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
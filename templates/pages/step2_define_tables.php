<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Step 2: Define Tables</h4>
            </div>
            <div class="card-body">
                <p class="lead">Design your database tables and relationships.</p>
                
                <form action="index.php?step=2" method="post" id="tables-form">
                    <input type="hidden" name="tables_json" id="tables-json" value="">
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <button type="button" id="add-table" class="btn btn-success">
                                <i class="bi bi-plus-circle"></i> Add Table
                            </button>
                        </div>
                        <div class="col-md-6 text-end">
                            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#importModal">
                                <i class="bi bi-upload"></i> Import Schema
                            </button>
                        </div>
                    </div>
                    
                    <div id="table-designer">
                        <div id="tables-container" class="mb-4"></div>
                        
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">Relationship Diagram</h5>
                            </div>
                            <div class="card-body">
                                <div id="relationship-diagram" class="relationship-diagram"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between mt-4">
                        <a href="index.php?step=1" class="btn btn-outline-secondary">
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

<!-- Import Schema Modal -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">Import Schema</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="schemaFile" class="form-label">Upload SQL File</label>
                    <input class="form-control" type="file" id="schemaFile" accept=".sql">
                </div>
                <div class="mb-3">
                    <label for="schemaText" class="form-label">Or Paste SQL Schema</label>
                    <textarea class="form-control" id="schemaText" rows="10" placeholder="CREATE TABLE..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="importSchema">Import</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Initialize with existing tables data if available
    $(document).ready(function() {
        <?php if (isset($_SESSION['tables']) && !empty($_SESSION['tables'])): ?>
        const savedTables = <?php echo json_encode($_SESSION['tables']); ?>;
        if (savedTables && savedTables.length) {
            // This will be handled by the table designer initialization
            $('#tables-json').val(JSON.stringify(savedTables));
        }
        <?php endif; ?>
    });
</script>
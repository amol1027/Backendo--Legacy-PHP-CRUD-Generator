/**
 * Backendo: Legacy PHP CRUD Generator
 * Main JavaScript
 */

$(document).ready(function() {
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Table Designer Functionality
    if ($('#table-designer').length) {
        initTableDesigner();
    }

    // Form validation
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
});

/**
 * Initialize the table designer functionality
 */
function initTableDesigner() {
    let tables = [];
    let currentTableId = 1;
    let currentFieldId = 1;

    // Add table button
    $('#add-table').click(function() {
        const tableId = currentTableId++;
        const table = {
            id: tableId,
            name: 'table_' + tableId,
            fields: []
        };
        tables.push(table);
        renderTable(table);
        updateTablesJson();
    });

    // Event delegation for dynamic elements
    $('#tables-container').on('click', '.add-field', function() {
        const tableId = $(this).data('table-id');
        const table = tables.find(t => t.id === tableId);
        if (table) {
            const fieldId = currentFieldId++;
            const field = {
                id: fieldId,
                name: 'field_' + fieldId,
                type: 'varchar',
                length: 255,
                default: '',
                nullable: true,
                constraints: []
            };
            table.fields.push(field);
            renderField(table, field);
            updateTablesJson();
        }
    });

    // Remove table
    $('#tables-container').on('click', '.remove-table', function() {
        const tableId = $(this).data('table-id');
        tables = tables.filter(t => t.id !== tableId);
        $(`#table-${tableId}`).remove();
        updateTablesJson();
        updateRelationshipDiagram();
    });

    // Remove field
    $('#tables-container').on('click', '.remove-field', function() {
        const tableId = $(this).data('table-id');
        const fieldId = $(this).data('field-id');
        const table = tables.find(t => t.id === tableId);
        if (table) {
            table.fields = table.fields.filter(f => f.id !== fieldId);
            $(`#field-${fieldId}`).remove();
            updateTablesJson();
            updateRelationshipDiagram();
        }
    });

    // Update table name
    $('#tables-container').on('change', '.table-name', function() {
        const tableId = $(this).data('table-id');
        const table = tables.find(t => t.id === tableId);
        if (table) {
            table.name = $(this).val();
            updateTablesJson();
            updateRelationshipDiagram();
        }
    });

    // Update field properties
    $('#tables-container').on('change', '.field-property', function() {
        const tableId = $(this).data('table-id');
        const fieldId = $(this).data('field-id');
        const property = $(this).data('property');
        const table = tables.find(t => t.id === tableId);
        if (table) {
            const field = table.fields.find(f => f.id === fieldId);
            if (field) {
                if (property === 'constraints') {
                    field.constraints = [];
                    $(this).closest('.field-row').find('.field-constraint:checked').each(function() {
                        field.constraints.push($(this).val());
                    });
                } else if (property === 'nullable') {
                    field[property] = $(this).is(':checked');
                } else {
                    field[property] = $(this).val();
                }
                updateTablesJson();
                updateRelationshipDiagram();
            }
        }
    });

    // Add initial table
    $('#add-table').click();

    /**
     * Render a table in the designer
     */
    function renderTable(table) {
        const tableHtml = `
            <div id="table-${table.id}" class="card table-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="input-group">
                        <span class="input-group-text">Table Name</span>
                        <input type="text" class="form-control table-name" data-table-id="${table.id}" value="${table.name}" required>
                    </div>
                    <button type="button" class="btn btn-danger btn-sm remove-table" data-table-id="${table.id}">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
                <div class="card-body">
                    <div class="fields-container" id="fields-${table.id}"></div>
                    <button type="button" class="btn btn-outline-primary btn-sm mt-3 add-field" data-table-id="${table.id}">
                        <i class="bi bi-plus-circle"></i> Add Field
                    </button>
                </div>
            </div>
        `;
        $('#tables-container').append(tableHtml);
    }

    /**
     * Render a field in a table
     */
    function renderField(table, field) {
        const fieldHtml = `
            <div id="field-${field.id}" class="field-row">
                <div class="row g-2">
                    <div class="col-md-3">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">Name</span>
                            <input type="text" class="form-control field-property" data-table-id="${table.id}" data-field-id="${field.id}" data-property="name" value="${field.name}" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">Type</span>
                            <select class="form-select field-property" data-table-id="${table.id}" data-field-id="${field.id}" data-property="type">
                                <option value="int" ${field.type === 'int' ? 'selected' : ''}>Integer</option>
                                <option value="varchar" ${field.type === 'varchar' ? 'selected' : ''}>Text (short)</option>
                                <option value="text" ${field.type === 'text' ? 'selected' : ''}>Text (long)</option>
                                <option value="date" ${field.type === 'date' ? 'selected' : ''}>Date</option>
                                <option value="datetime" ${field.type === 'datetime' ? 'selected' : ''}>Date & Time</option>
                                <option value="decimal" ${field.type === 'decimal' ? 'selected' : ''}>Decimal</option>
                                <option value="boolean" ${field.type === 'boolean' ? 'selected' : ''}>Boolean</option>
                                <option value="enum" ${field.type === 'enum' ? 'selected' : ''}>Dropdown</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">Length</span>
                            <input type="number" class="form-control field-property" data-table-id="${table.id}" data-field-id="${field.id}" data-property="length" value="${field.length}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">Default</span>
                            <input type="text" class="form-control field-property" data-table-id="${table.id}" data-field-id="${field.id}" data-property="default" value="${field.default}">
                        </div>
                    </div>
                    <div class="col-md-2 d-flex align-items-center justify-content-end">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input field-property" type="checkbox" id="nullable-${field.id}" data-table-id="${table.id}" data-field-id="${field.id}" data-property="nullable" ${field.nullable ? 'checked' : ''}>
                            <label class="form-check-label" for="nullable-${field.id}">Nullable</label>
                        </div>
                        <button type="button" class="btn btn-danger btn-sm ms-2 remove-field" data-table-id="${table.id}" data-field-id="${field.id}">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12">
                        <div class="constraints-container">
                            <small class="text-muted">Constraints:</small>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input field-constraint field-property" type="checkbox" id="pk-${field.id}" value="primary" data-table-id="${table.id}" data-field-id="${field.id}" data-property="constraints" ${field.constraints.includes('primary') ? 'checked' : ''}>
                                <label class="form-check-label" for="pk-${field.id}">Primary Key</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input field-constraint field-property" type="checkbox" id="unique-${field.id}" value="unique" data-table-id="${table.id}" data-field-id="${field.id}" data-property="constraints" ${field.constraints.includes('unique') ? 'checked' : ''}>
                                <label class="form-check-label" for="unique-${field.id}">Unique</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input field-constraint field-property" type="checkbox" id="index-${field.id}" value="index" data-table-id="${table.id}" data-field-id="${field.id}" data-property="constraints" ${field.constraints.includes('index') ? 'checked' : ''}>
                                <label class="form-check-label" for="index-${field.id}">Index</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input field-constraint field-property" type="checkbox" id="fk-${field.id}" value="foreign" data-table-id="${table.id}" data-field-id="${field.id}" data-property="constraints" ${field.constraints.includes('foreign') ? 'checked' : ''}>
                                <label class="form-check-label" for="fk-${field.id}">Foreign Key</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        $(`#fields-${table.id}`).append(fieldHtml);
    }

    /**
     * Update the hidden input with the tables JSON
     */
    function updateTablesJson() {
        $('#tables-json').val(JSON.stringify(tables));
    }

    /**
     * Update the relationship diagram
     */
    function updateRelationshipDiagram() {
        // This would be implemented with a library like jsPlumb or mxGraph
        // For now, we'll just show a placeholder
        const diagramHtml = `<div class="text-center p-5">
            <p><i class="bi bi-diagram-3 display-1 text-muted"></i></p>
            <p class="text-muted">Relationship diagram will be displayed here</p>
            <p class="text-muted small">Tables: ${tables.length}, Fields: ${tables.reduce((sum, table) => sum + table.fields.length, 0)}</p>
        </div>`;
        $('#relationship-diagram').html(diagramHtml);
    }
}

/**
 * Show loading spinner
 */
function showSpinner(message = 'Processing...') {
    const spinnerHtml = `
        <div class="spinner-overlay" id="loading-spinner">
            <div class="spinner-container">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-3">${message}</p>
            </div>
        </div>
    `;
    $('body').append(spinnerHtml);
}

/**
 * Hide loading spinner
 */
function hideSpinner() {
    $('#loading-spinner').remove();
}
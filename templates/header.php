<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Backendo - Legacy PHP CRUD Generator</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/main.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-database-gear"></i> Backendo
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?step=1">New Project</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <?php
        // Get current step for progress bar
        $current_step = isset($_GET['step']) ? (int)$_GET['step'] : 1;
        $steps = [
            1 => 'Project Setup',
            2 => 'Define Tables',
            3 => 'Authentication',
            4 => 'Features',
            5 => 'Generate'
        ];
        
        // Only show progress bar if we're in a step
        if ($current_step >= 1 && $current_step <= 5):
        ?>
        <div class="progress-container mb-4">
            <div class="progress">
                <div class="progress-bar" role="progressbar" style="width: <?php echo ($current_step / 5) * 100; ?>%"></div>
            </div>
            <div class="step-indicators">
                <?php foreach ($steps as $step_num => $step_name): ?>
                <div class="step-indicator <?php echo $step_num <= $current_step ? 'active' : ''; ?>">
                    <div class="step-number"><?php echo $step_num; ?></div>
                    <div class="step-name"><?php echo $step_name; ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
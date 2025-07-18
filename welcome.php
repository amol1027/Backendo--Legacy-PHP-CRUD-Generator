<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP CRUD Generator</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .welcome-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
        }
        .hero {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            padding: 3rem;
            margin-top: 3rem;
            text-align: center;
        }
        .hero h1 {
            color: #0d6efd;
            margin-bottom: 1.5rem;
        }
        .features {
            margin-top: 3rem;
        }
        .feature-card {
            height: 100%;
            transition: transform 0.3s;
        }
        .feature-card:hover {
            transform: translateY(-5px);
        }
        .feature-icon {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: #0d6efd;
        }
    </style>
</head>
<body>
    <div class="welcome-container">
        <div class="hero">
            <h1>PHP CRUD Generator</h1>
            <p class="lead">Create complete PHP applications with just a few clicks</p>
            <p class="mb-4">Generate database-driven PHP applications with authentication, CRUD operations, and more.</p>
            <a href="index.php" class="btn btn-primary btn-lg">Start Building</a>
            <a href="import_db.php" class="btn btn-outline-secondary btn-lg ms-2">Import Database</a>
        </div>

        <div class="features">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card feature-card">
                        <div class="card-body text-center">
                            <div class="feature-icon">🚀</div>
                            <h5 class="card-title">Quick Setup</h5>
                            <p class="card-text">Define your database schema and generate a complete application in minutes.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card">
                        <div class="card-body text-center">
                            <div class="feature-icon">🔒</div>
                            <h5 class="card-title">Authentication</h5>
                            <p class="card-text">Built-in user authentication with login, registration, and password reset.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card">
                        <div class="card-body text-center">
                            <div class="feature-icon">⚙️</div>
                            <h5 class="card-title">Customizable</h5>
                            <p class="card-text">Choose the features you need and customize your application.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <footer class="mt-5 text-center text-muted">
            <p>PHP CRUD Generator - A tool for rapidly developing PHP applications</p>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
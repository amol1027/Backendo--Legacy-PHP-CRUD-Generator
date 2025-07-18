# PHP CRUD Generator

A web-based tool for automatically generating PHP CRUD applications from database schema definitions.

## Features

- Generate complete PHP CRUD applications with a few clicks
- Customizable project settings (project name, author, etc.)
- Define database tables and fields through an intuitive interface
- Authentication system generation with login, registration, and password reset
- Responsive Bootstrap-based UI
- RESTful API generation option
- Downloadable as a ZIP file ready for deployment

## Requirements

- PHP 7.4 or higher
- MySQL/MariaDB database
- Web server (Apache, Nginx, etc.)
- PHP ZIP extension enabled

## Installation

1. Clone or download this repository to your web server directory
2. Make sure the `output` and `temp` directories have write permissions
3. Enable the PHP ZIP extension in your php.ini file
4. Import the database schema by visiting `import_db.php` in your browser
5. Access the application through your web browser

## Enabling ZIP Extension in XAMPP

1. Open `C:\xampp\php\php.ini`
2. Find the line `;extension=zip` and remove the semicolon to uncomment it
3. Save the file and restart Apache

## Alternative Export Method

If you encounter issues with the ZIP extension, the application includes an alternative export method using PowerShell's `Compress-Archive` command on Windows systems.

## Usage

1. Start by entering your project details (name, author, etc.)
2. Define your database tables and fields
3. Configure authentication options if needed
4. Select additional features
5. Generate your project
6. Download the generated ZIP file
7. Extract and deploy to your web server

## Project Structure

The generated project follows a Model-View-Controller (MVC) architecture:

```
project/
├── assets/
│   ├── css/
│   ├── js/
│   └── img/
├── config/
│   └── db.php
├── controllers/
│   ├── HomeController.php
│   ├── AuthController.php
│   └── [TableName]Controller.php
├── includes/
│   └── functions.php
├── models/
│   ├── User.php
│   └── [TableName].php
├── views/
│   ├── layouts/
│   │   ├── header.php
│   │   └── footer.php
│   ├── home/
│   │   └── index.php
│   ├── auth/
│   │   ├── login.php
│   │   └── register.php
│   └── [table_name]/
│       ├── index.php
│       ├── create.php
│       ├── edit.php
│       └── view.php
└── index.php
```

## Troubleshooting

If you encounter issues with project generation:

1. Check that the PHP ZIP extension is enabled
2. Verify that the `output` and `temp` directories have write permissions
3. Check the `debug.log` and `export_error.log` files for error messages

## License

MIT License
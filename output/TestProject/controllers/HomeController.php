<?php
/**
 * Home Controller
 */

class HomeController {
    /**
     * Display the home page
     */
    public function index() {
        // Load the view
        require_once 'views/layouts/header.php';
        require_once 'views/home/index.php';
        require_once 'views/layouts/footer.php';
    }
}

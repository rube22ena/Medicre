<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Require that a user is logged in.
 */
function requireLogin() {
    if (empty($_SESSION['user_id'])) {
        header('Location: /MedicreProject/Medicre/public/login.php');
        exit;
    }
}

/**
 * Require that a user has one of the allowed roles.
 * Accepts a string (e.g. 'admin') or an array (e.g. ['admin','doctor']).
 */
function requireRole($roles) {
    requireLogin();

    // If a single string is passed, wrap it into an array
    if (!is_array($roles)) {
        $roles = [$roles];
    }

    if (empty($_SESSION['role']) || !in_array($_SESSION['role'], $roles, true)) {
        http_response_code(403);
        die('Forbidden');
    }
}
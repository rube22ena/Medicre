<!-- <?php
require_once '../includes/db-connect.php';
require_once '../includes/auth.php';
requireRole('admin');

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];

    // Prepare and execute delete query
    $stmt = $pdo->prepare("DELETE FROM user WHERE user_id = ?");
    $stmt->execute([$id]);

    // Redirect back to admin dashboard
    header("Location: admin-dashboard.php");
    exit;
}
?> -->
<?php
require_once '../includes/db-connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);

    $stmt = $pdo->prepare("UPDATE user 
                           SET password_hash=?, reset_token=NULL, token_expiry=NULL 
                           WHERE email=?");
    $stmt->execute([$new_password, $email]);

   header("Location: ../public/login.php?reset=success");
exit;

}
?>
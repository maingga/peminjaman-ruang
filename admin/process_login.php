<?php
session_start();
require_once '../inc/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit;
}

if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $_SESSION['error'] = '❌ CSRF token tidak valid.';
    header("Location: login.php");
    exit;
}

$email = $_POST['email'];
$password = $_POST['password'];

$stmt = $pdo->prepare("SELECT * FROM admins WHERE email = :email LIMIT 1");
$stmt->execute(['email' => $email]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

if ($admin && password_verify($password, $admin['password'])) {
    // Simpan data admin ke dalam session
    $_SESSION['admin'] = [
        'id' => $admin['id'],
        'name' => $admin['name'],
        'email' => $admin['email']
    ];
    header("Location: dashboard.php");
    exit;
} else {
    $_SESSION['error'] = '⚠️ Email atau password salah.';
    header("Location: login.php");
    exit;
}

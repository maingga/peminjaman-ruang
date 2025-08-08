<?php
session_start();
require_once '../inc/db.php';

if (!isset($_SESSION['admin'])) {
  header("Location: login.php");
  exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  $_SESSION['flash_error'] = "ID admin tidak valid.";
  header('Location: pengguna.php');
  exit;
}

$id = (int) $_GET['id'];

// Opsional: jangan reset password sendiri
if ($id === $_SESSION['admin']['id']) {
    $_SESSION['flash_error'] = "Anda tidak dapat mereset password admin yang sedang login.";
    header('Location: pengguna.php');
    exit;
}

// Cek keberadaan admin
$stmt = $pdo->prepare("SELECT id FROM admins WHERE id = ?");
$stmt->execute([$id]);
$adminExists = $stmt->fetch();

if (!$adminExists) {
    $_SESSION['flash_error'] = "Admin tidak ditemukan.";
    header('Location: pengguna.php');
    exit;
}

$newPassHash = password_hash("123456", PASSWORD_BCRYPT);

$stmt = $pdo->prepare("UPDATE admins SET password = ? WHERE id = ?");
$success = $stmt->execute([$newPassHash, $id]);

if ($success) {
    $_SESSION['flash_success'] = "Password admin berhasil direset menjadi '123456'.";
} else {
    $_SESSION['flash_error'] = "Gagal mereset password admin.";
}

header('Location: pengguna.php');
exit;

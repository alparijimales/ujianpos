<?php
require 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) ($_POST['id'] ?? 0);

    $stmt = $pdo->prepare('DELETE FROM sales WHERE id = ?');
    $stmt->execute([$id]);

    if ($stmt->rowCount() > 0) {
        $_SESSION['pesan'] = 'Data Sales berhasil dihapus.';
        $_SESSION['tipe']  = 'success';
    } else {
        $_SESSION['pesan'] = 'Data Sales tidak ditemukan.';
        $_SESSION['tipe']  = 'danger';
    }
}

header('Location: index.php');
exit;

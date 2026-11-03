<?php
require 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) ($_POST['id'] ?? 0);

    $stmt = $pdo->prepare('DELETE FROM Supliers WHERE id = ?');
    $stmt->execute([$id]);

    if ($stmt->rowCount() > 0) {
        $_SESSION['pesan'] = 'Data Supplier berhasil dihapus (beserta data sales terkait jika ada - Cascade).';
        $_SESSION['tipe']  = 'success';
    } else {
        $_SESSION['pesan'] = 'Data Supplier tidak ditemukan.';
        $_SESSION['tipe']  = 'danger';
    }
}

header('Location: index.php');
exit;

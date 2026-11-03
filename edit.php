<?php
require 'connect.php';

$id = (int) ($_GET['id'] ?? 0);

$stmt = $pdo->prepare('SELECT * FROM Supliers WHERE id = ?');
$stmt->execute([$id]);
$data = $stmt->fetch();

if (!$data) {
    $_SESSION['pesan'] = 'Data Supplier tidak ditemukan.';
    $_SESSION['tipe']  = 'danger';
    header('Location: index.php');
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($data as $kolom => $nilai) {
        $data[$kolom] = trim($_POST[$kolom] ?? '');
    }

    if ($data['name'] === '') {
        $errors[] = 'Nama supplier wajib diisi.';
    }

    if (count($errors) === 0) {
        $sql = 'UPDATE Supliers SET name = :name, address = :address, phone = :phone, email = :email WHERE id = :id';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name'    => $data['name'],
            ':address' => $data['address'],
            ':phone'   => $data['phone'],
            ':email'   => $data['email'],
            ':id'      => $id,
        ]);

        $_SESSION['pesan'] = 'Data Supplier berhasil diperbarui.';
        $_SESSION['tipe']  = 'success';
        header('Location: index.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit Supplier</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.php">Sistem POS</a>
    <div class="navbar-nav ms-auto flex-row gap-3">
      <a class="nav-link text-white active fw-bold" href="index.php">Supplier</a>
      <a class="nav-link text-white" href="sales/index.php">Sales</a>
    </div>
  </div>
</nav>

<div class="container" style="max-width: 600px;">
  <?php if (isset($_SESSION['pesan'])): ?>
    <div class="alert alert-<?= $_SESSION['tipe'] ?? 'primary' ?> alert-dismissible fade show">
      <?= e($_SESSION['pesan']) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['pesan'], $_SESSION['tipe']); ?>
  <?php endif; ?>

  <div class="card shadow-sm">
    <div class="card-header bg-warning text-dark">
      <h5 class="mb-0">Edit Data Supplier #<?= e($id) ?></h5>
    </div>
    <div class="card-body">
      <?php if ($errors): ?>
        <div class="alert alert-danger">
          <ul class="mb-0">
            <?php foreach ($errors as $pesan): ?>
              <li><?= e($pesan) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <form method="post">
        <div class="mb-3">
          <label class="form-label fw-semibold">Nama Supplier <span class="text-danger">*</span></label>
          <input type="text" name="name" class="form-control" value="<?= e($data['name']) ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Alamat</label>
          <textarea name="address" class="form-control" rows="3"><?= e($data['address']) ?></textarea>
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">No HP</label>
          <input type="text" name="phone" class="form-control" value="<?= e($data['phone']) ?>">
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Email</label>
          <input type="email" name="email" class="form-control" value="<?= e($data['email']) ?>">
        </div>

        <div class="d-flex gap-2">
          <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
          <a href="index.php" class="btn btn-secondary">Batal</a>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

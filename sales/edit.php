<?php
require 'connect.php';

$id = (int) ($_GET['id'] ?? 0);

$stmt = $pdo->prepare('SELECT * FROM sales WHERE id = ?');
$stmt->execute([$id]);
$data = $stmt->fetch();

if (!$data) {
    $_SESSION['pesan'] = 'Data Sales tidak ditemukan.';
    $_SESSION['tipe']  = 'danger';
    header('Location: index.php');
    exit;
}

// Ambil daftar supplier dari tabel Supliers
$stmt_suppliers = $pdo->query('SELECT id, name FROM Supliers ORDER BY name ASC');
$suppliers = $stmt_suppliers->fetchAll();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data['supplier_id'] = trim($_POST['supplier_id'] ?? '');
    $data['total']       = trim($_POST['total'] ?? '');

    if ($data['supplier_id'] === '') {
        $errors[] = 'Supplier wajib dipilih.';
    } else {
        $cek_sup = $pdo->prepare('SELECT COUNT(*) FROM Supliers WHERE id = ?');
        $cek_sup->execute([$data['supplier_id']]);
        if ($cek_sup->fetchColumn() == 0) {
            $errors[] = 'Supplier yang dipilih tidak valid.';
        }
    }

    if ($data['total'] === '') {
        $errors[] = 'Total penjualan wajib diisi.';
    } elseif (!is_numeric($data['total']) || $data['total'] < 0) {
        $errors[] = 'Total harus berupa angka dan tidak boleh negatif.';
    }

    if (count($errors) === 0) {
        $sql = 'UPDATE sales SET supplier_id = :supplier_id, total = :total WHERE id = :id';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':supplier_id' => $data['supplier_id'],
            ':total'       => $data['total'],
            ':id'          => $id,
        ]);

        $_SESSION['pesan'] = 'Data Sales berhasil diperbarui.';
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
  <title>Edit Sales</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.php">Sistem POS</a>
    <div class="navbar-nav ms-auto flex-row gap-3">
      <a class="nav-link text-white" href="../supplier/index.php">Supplier</a>
      <a class="nav-link text-white active fw-bold" href="index.php">Sales</a>
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
      <h5 class="mb-0">Edit Data Sales #<?= e($id) ?></h5>
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
          <label class="form-label fw-semibold">Supplier <span class="text-danger">*</span></label>
          <select name="supplier_id" class="form-select" required>
            <option value="">-- Pilih Supplier --</option>
            <?php foreach ($suppliers as $sup): ?>
              <option value="<?= $sup['id'] ?>" <?= $data['supplier_id'] == $sup['id'] ? 'selected' : '' ?>>
                ID <?= e($sup['id']) ?> - <?= e($sup['name']) ?>
              </option>
            <?php endforeach; ?>
          </select>
          <div class="form-text">Relasi dengan tabel Supliers (Cascade Update/Delete).</div>
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Total Penjualan <span class="text-danger">*</span></label>
          <div class="input-group">
            <span class="input-group-text">Rp</span>
            <input type="number" name="total" class="form-control" value="<?= e($data['total']) ?>" placeholder="Contoh: 150000" min="0" required>
          </div>
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

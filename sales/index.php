<?php
require 'connect.php';

// Ambil data sales beserta nama supplier dari tabel Supliers
$stmt = $pdo->query('
    SELECT sales.*, Supliers.name AS supplier_name 
    FROM sales 
    LEFT JOIN Supliers ON sales.supplier_id = Supliers.id 
    ORDER BY sales.id DESC
');
$daftar = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Data Sales</title>
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

<div class="container">
  <?php if (isset($_SESSION['pesan'])): ?>
    <div class="alert alert-<?= $_SESSION['tipe'] ?? 'primary' ?> alert-dismissible fade show">
      <?= e($_SESSION['pesan']) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['pesan'], $_SESSION['tipe']); ?>
  <?php endif; ?>

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Daftar Transaksi Sales</h4>
    <a href="add.php" class="btn btn-primary">+ Tambah Sales</a>
  </div>

  <div class="card shadow-sm">
    <div class="table-responsive">
      <table class="table table-striped table-hover mb-0">
        <thead class="table-primary">
          <tr>
            <th style="width: 60px;">No</th>
            <th>ID Sales</th>
            <th>Supplier</th>
            <th>Total Penjualan</th>
            <th style="width: 160px;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if (count($daftar) === 0): ?>
            <tr>
              <td colspan="5" class="text-center text-muted py-4">Belum ada data transaksi sales.</td>
            </tr>
          <?php endif; ?>

          <?php foreach ($daftar as $no => $s): ?>
            <tr>
              <td><?= $no + 1 ?></td>
              <td><span class="badge bg-secondary">#<?= e($s['id']) ?></span></td>
              <td>
                <strong><?= e($s['supplier_name'] ?? 'Supplier telah dihapus') ?></strong>
                <br>
                <small class="text-muted">ID Supplier: <?= e($s['supplier_id']) ?></small>
              </td>
              <td>Rp <?= number_format((float)($s['total'] ?? 0), 0, ',', '.') ?></td>
              <td>
                <a href="edit.php?id=<?= $s['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                <button type="button" class="btn btn-sm btn-danger"
                  data-bs-toggle="modal" data-bs-target="#modalHapus"
                  data-id="<?= $s['id'] ?>" data-nama="ID Sales #<?= e($s['id']) ?> (Supplier: <?= e($s['supplier_name'] ?? $s['supplier_id']) ?>)">Hapus</button>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Modal Konfirmasi Hapus -->
  <div class="modal fade" id="modalHapus" tabindex="-1">
    <div class="modal-dialog">
      <form method="post" action="delete.php" class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Konfirmasi Hapus Sales</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          Yakin ingin menghapus data <strong id="namaHapus"></strong>?
          <input type="hidden" name="id" id="idHapus">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-danger">Ya, Hapus</button>
        </div>
      </form>
    </div>
  </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  var modalHapus = document.getElementById('modalHapus');
  if (modalHapus) {
    modalHapus.addEventListener('show.bs.modal', function(event) {
      var tombol = event.relatedTarget;
      document.getElementById('idHapus').value = tombol.getAttribute('data-id');
      document.getElementById('namaHapus').textContent = tombol.getAttribute('data-nama');
    });
  }
</script>
</body>
</html>


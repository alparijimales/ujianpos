<?php
require 'connect.php';

$stmt   = $pdo->query('SELECT * FROM Supliers ORDER BY id DESC');
$daftar = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Data Supplier</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.php">Sistem POS</a>
    <div class="navbar-nav ms-auto flex-row gap-3">
      <a class="nav-link text-white active fw-bold" href="index.php">Supplier</a>
      <a class="nav-link text-white" href="../sales/index.php">Sales</a>
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
    <h4 class="mb-0">Daftar Supplier</h4>
    <a href="add.php" class="btn btn-primary">+ Tambah Supplier</a>
  </div>

  <div class="card shadow-sm">
    <div class="table-responsive">
      <table class="table table-striped table-hover mb-0">
        <thead class="table-primary">
          <tr>
            <th style="width: 60px;">No</th>
            <th>ID</th>
            <th>Nama Supplier</th>
            <th>Alamat</th>
            <th>No HP</th>
            <th>Email</th>
            <th style="width: 160px;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if (count($daftar) === 0): ?>
            <tr>
              <td colspan="7" class="text-center text-muted py-4">Belum ada data supplier.</td>
            </tr>
          <?php endif; ?>

          <?php foreach ($daftar as $no => $s): ?>
            <tr>
              <td><?= $no + 1 ?></td>
              <td><span class="badge bg-secondary">#<?= e($s['id']) ?></span></td>
              <td><strong><?= e($s['name']) ?></strong></td>
              <td><?= e($s['address']) ?></td>
              <td><?= e($s['phone']) ?></td>
              <td><?= e($s['email']) ?></td>
              <td>
                <a href="edit.php?id=<?= $s['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                <button type="button" class="btn btn-sm btn-danger"
                  data-bs-toggle="modal" data-bs-target="#modalHapus"
                  data-id="<?= $s['id'] ?>" data-nama="<?= e($s['name']) ?>">Hapus</button>
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
          <h5 class="modal-title">Konfirmasi Hapus Supplier</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <p>Yakin ingin menghapus supplier <strong id="namaHapus"></strong>?</p>
          <div class="alert alert-warning py-2 mb-0 small">
            <strong>Catatan:</strong> Karena terdapat relasi foreign key <em>CASCADE</em>, menghapus supplier ini juga akan otomatis menghapus seluruh riwayat transaksi sales miliknya.
          </div>
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


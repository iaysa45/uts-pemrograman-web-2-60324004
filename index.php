<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Kategori - UTS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <?php
    require_once 'config/database.php';

    // Query data kategori 
    $stmt = $conn->prepare("SELECT * FROM kategori ORDER BY id_kategori DESC");
    $stmt->execute();
    $result = $stmt->get_result();
    ?>

    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Daftar Kategori Buku</h2>
            <a href="create.php" class="btn btn-primary">Tambah Kategori</a>
        </div>

        <?php
            // Tampilkan pesan success/error
            if (isset($_GET['success'])) {
                echo '<div class="alert alert-success alert-dismissible fade show">';
                echo '<i class="bi bi-check-circle"></i> ' . htmlspecialchars($_GET['success']);
                echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
                echo '</div>';
            }
            
            if (isset($_GET['error'])) {
                echo '<div class="alert alert-danger alert-dismissible fade show">';
                echo '<i class="bi bi-x-circle"></i> ' . htmlspecialchars($_GET['error']);
                echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
                echo '</div>';
            }
        ?>

        <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Daftar Kategori</h5>
        </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th width="50">No</th>
                            <th width="100">Kode</th>
                            <th>Nama Kategori</th>
                            <th>Deskripsi</th>
                            <th width="100">Status</th>
                            <th width="150">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0):
                            $no = 1; 
                            while ($kategori = $result->fetch_assoc()):
                        ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($kategori['kode_kategori']) ?></td>
                                <td><?= htmlspecialchars($kategori['nama_kategori']) ?></td>
                                <td><?= htmlspecialchars($kategori['deskripsi']) ?></td>

                                <td>
                                    <?php if ($kategori['status'] === 'Aktif'): ?>
                                        <span class="badge bg-success">Aktif</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Nonaktif</span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <a href="edit2.php?id=<?= $kategori['id_kategori'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <button onclick="confirmDelete(<?= $kategori['id_kategori'] ?>)" class="btn btn-danger btn-sm">Hapus</button>
                                </td>
                            </tr>
                        <?php
                            endwhile;
                        else:
                        ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted">Belum ada data kategori.</td>
                            </tr>
                        <?php endif; ?>

                        <?php
                        $stmt->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<script>
// Konfirmasi sebelum menghapus data kategori
function confirmDelete(id) {
    if (confirm('Yakin ingin menghapus kategori ini?')) {
        window.location.href = 'delete.php?id=' + id;
    }
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
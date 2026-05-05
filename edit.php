<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kategori - UTS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php
    require_once 'config/database.php';
    session_start();

    $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

    if ($id <= 0) {
        $_SESSION['pesan_error'] = 'ID Kategori tidak valid.';
        header('Location: index.php');
        exit;
    }

    $cekData = $conn->prepare("SELECT * FROM kategori WHERE id_kategori = ?");
    $cekData->bind_param("i", $id);
    $cekData->execute();
    $result = $cekData->get_result();

    if ($result->num_rows === 0) {
        $_SESSION['pesan_error'] = 'Kategori tidak ditemukan.';
        header('Location: index.php');
        exit;
    }

    $data = $result->fetch_assoc();
    $cekData->close();

    $errors = [];
    $kode = $data['kode_kategori'];
    $nama = $data['nama_kategori'];
    $deskripsi = $data['deskripsi'];
    $status = $data['status'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $kode = trim(htmlspecialchars($_POST['kode_kategori'] ?? ''));
        $nama = trim(htmlspecialchars($_POST['nama_kategori'] ?? ''));
        $deskripsi = trim(htmlspecialchars($_POST['deskripsi'] ?? ''));
        $status = trim(htmlspecialchars($_POST['status'] ?? ''));

        if($kode === ''){
            $errors['kode'] = 'Kode Kategori wajib diisi';
        }elseif (strlen($kode) < 4 ||strlen($kode) > 10) {
            $errors['kode'] = 'Kode harus antara 4-10 karakter';
        }elseif (!preg_match('/^KAT/i', $kode)) {
            $errors['kode'] ='Kode kategori harus diawali dengan "KAT".';
        }else {
        $cekDuplikat = $conn->prepare(
            "SELECT id_kategori FROM kategori WHERE kode_kategori = ? AND id_kategori != ?"
        );
        $cekDuplikat->bind_param("si", $kode, $id);
        $cekDuplikat->execute();
        $cekDuplikat->store_result();
        if ($cekDuplikat->num_rows > 0) {
            $errors[] = 'Kode kategori sudah digunakan oleh data lain.';
        }
        $cekDuplikat->close();
    }

    if ($nama === '') {
        $errors['nama'] = 'Nama kategori wajib diisi.';
    } elseif (strlen($nama) <3) {
        $errors['nama'] = 'Nama kategori minimal 3 karakter';
    }elseif(strlen($nama) >50) {
        $errors['nama'] = 'Nama kategori maksimal 50 kareakter';
    }
    
    if ($deskripsi !== '' && strlen($deskripsi) >200) {
        $errors['deskripsi'] = 'Deskripsi maksimal 200 karakter';
    }

    if (!in_array($status, ['Aktif', 'Nonaktif'])) {
        $errors['status'] = 'Status tidak valid';
    }

    if (empty($errors)) {
        $stmt_update = $conn->prepare(
            "UPDATE kategori SET kode_kategori = ?, nama_kategori = ?, deskripsi = ?, status = ? WHERE id_kategori = ?"
        );
        $stmt_update->bind_param('ssssi', $kode, $nama, $deskripsi, $status, $id);

        if ($stmt_update->execute()) {
            $_SESSION['pesan_sukses'] = "Kategori \"$nama\" berhasil diperbarui";
            header('Location: index.php');
            exit;
        } else {
            $errors['db'] = 'Gagal memperbarui data. Silahkan coba lagi';
        }
        $stmt_update->close();
        
    }
    }
    ?>

    <div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                 <div class="card-header bg-warning">
                    <h4 class="mb-0">Edit Kategori</h4>
                </div>
                <div class="card-body">

                    <?php if (isset($errors['db'])): ?>
                        <div class="alert alert-danger"><?= $errors['db'] ?></div>
                    <?php endif; ?>

                     <form method="POST">

                        <div class="mb-3">
                            <label for="kode_kategori" class="form-label fw-semibold">
                                Kode Kategori <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control"
                                   id="kode_kategori"
                                   name="kode_kategori"
                                   value="<?= htmlspecialchars($kode) ?>"
                                   maxlength="10"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label for="nama_kategori" class="form-label fw-semibold">
                                Nama Kategori <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control"
                                   id="nama_kategori"
                                   name="nama_kategori"
                                   value="<?= htmlspecialchars($nama) ?>"
                                   maxlength="50"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi" class="form-label fw-semibold">Deskripsi</label>
                            <textarea class="form-control"
                                      id="deskripsi"
                                      name="deskripsi"
                                      rows="3"
                                      maxlength="200"><?= htmlspecialchars($deskripsi) ?></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status"
                                           id="aktif" value="Aktif"
                                           <?= $status === 'Aktif' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="aktif">Aktif</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status"
                                           id="nonaktif" value="Nonaktif"
                                           <?= $status === 'Nonaktif' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="nonaktif">Nonaktif</label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-warning">Update</button>
                            <a href="index.php" class="btn btn-secondary">Kembali</a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
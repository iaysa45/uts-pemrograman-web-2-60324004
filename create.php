<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kategori - UTS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php
    require_once 'config/database.php';
    session_start();

    $errors = [];
    $kode = '';
    $nama = '';
    $deskripsi = '';
    $status   = 'Aktif'; // Default status: Aktif

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $kode = trim(htmlspecialchars($_POST['kode_kategori'] ?? ''));
        $nama = trim(htmlspecialchars($_POST['nama_kategori'] ?? ''));
        $deskripsi = trim(htmlspecialchars($_POST['deskripsi'] ?? ''));
        $status = trim(htmlspecialchars($_POST['status'] ?? ''));

        if ($kode === '') {
            $errors['kode'] = 'Kode kategori wajib diisi.';
        } elseif (strlen($kode) < 4 || strlen($kode) >10) {
            $errors['kode'] ='Kode kategori harus antara 4-10 karakter.';
        } elseif (!preg_match('/^KAT/i', $kode)) {
            $errors['kode'] ='Kode kategori harus diawali "KAT".';
        }else {
            //cek dupliaksi kode ke database
            $cek = $conn->prepare("SELECT id_kategori FROM kategori WHERE kode_kategori = ?");
            $cek->bind_param('s', $kode);
            $cek->execute();
            $cek->store_result();

            if ($cek->num_rows > 0) {
                $errors['kode'] = 'Kode kategori sudah digunakan, gunakan kode lain.';
            }
            $cek->close();
        }

        if (nama === '') {
        $errors['nama'] = 'Nama kategori wajib diisi.';
        }elseif (strlen($nama) < 3) {
            $errors['nama'] = 'Nama kategori minimal 3 karakter.';
        } elseif (strlen($nama) > 50) {
            $errors['nama'] = 'Nama kategori maksimal 50 karakter.';
        }

        if ($deskripsi !== '' && strlen($deskripsi) >200) {
            $errors['deskripsi'] = 'Deskripsi maksimal 200 karakter';
        }

        $status_valid = ['Aktif', 'Nonaktif'];
        if (!in_array($status, $status_valid)) {
            $errors['status'] = 'Status tidak valid.';
        }

        if (empty($errors)) {
            $stmt = $conn->prepare(
                "INSERT INTO kategori (kode_kategori, nama_kategori, deskripsi, status) VALUES (?, ?, ?, ?)"
            );
            $stmt->bind_param('ssss', $kode, $nama, $deskripsi, $status);

            if ($stmt->execute()) {
                $_SESSION['pesan_sukses'] = "Kategori \"$nama\" berhasil ditambahkan.";
                header('Location: index.php');
                exit;
            } else {
                $errors['db'] = 'Gagal menyimpan data. Silahkan coba lagi.';
            }
            $stmt->close();
            
        }
    }
    ?>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Tambah Kategori Baru</h4>
                    </div>
                    <div class="card-body">
                    <?php if (isset($errors['db'])): ?>
                        <div class="alert alert-danger"><?= $errors['db']?></div>
                    <?php endif; ?>

                    <form method="POST">
                         <div class=""mb-3>
                            <lebel for="kode_kategori" class="form-label fw-semibold">
                                Kode Kategori <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                    class="form-control"
                                    id="kode_kategori"
                                    name="kode_kategori"
                                    value="<?= htmlspecialchars($kode) ?>"
                                    placeholder="Contoh: KAT-001"
                                    maxlength="10"
                                    required>
                                <div class="form-text">Format: KAT-xxx (4-10 Karakter)</div>
                         </div>

                         <div class="mb-3">
                            <label for="nama_kategori" class="form-label fw-semibold">
                                Nama Kategori <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                    class="form-control"
                                    id="nama_kategori"
                                    name="nama_kategori"
                                    values="<?= htmlspecialchars($nama) ?>"
                                    placeholder="Masukkan nama kategori"
                                    maxlength="50"
                                    required>
                                <div class="form-text">3-50 karakter</div>
                         </div>

                         <div class="mb-3">
                            <label for="deskripsi" class="form-label fw-semibold">Deskripsi</label>
                            <textarea class="form-control"
                                        id="deskripsi"
                                        name="deskripsi"
                                        rows="3"
                                        placeholder="Keterangan singkat (Opsional)"
                                        maxlength="200"><?= htmlspecialchars($deskripsi) ?></textarea>
                                    <div class="form-text">Maks. 200 kararkter</div>
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
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="index.php" class="btn btn-secondary">Kembali</a>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
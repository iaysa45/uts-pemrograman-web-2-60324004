<?php
require_once 'config/database.php';
session_start();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['pesan_error'] = 'ID kategori tidak valid.';
    header('Location: index.php');
    exit;
}

$id = (int) $_GET['id'];

if ($id <= 0) {
    $_SESSION['pesan_error'] = 'ID kategori tidak valid.';
    header('Location: index.php');
    exit;
}

$stmt_cek = $conn->prepare("SELECT id_kategori, nama_kategori FROM kategori WHERE id_kategori = ?");
$stmt_cek->bind_param('i', $id);
$stmt_cek->execute();
$result = $stmt_cek->get_result();

if ($result->num_rows === 0) {
    $_SESSION['pesan_error'] = 'Kategori tidak ditemukan atau sudah dihapus.';
    header('Location: index.php');
    exit;
}

// Ambil nama kategori untuk pesan sukses
$data = $result->fetch_assoc();
$nama_kategori = $data['nama_kategori'];
$stmt_cek->close();

$stmt_delete = $conn->prepare("DELETE FROM kategori WHERE id_kategori = ?");
$stmt_delete->bind_param('i', $id);
$stmt_delete->execute();

if ($stmt_delete->affected_rows > 0) {
    $_SESSION['pesan_sukses'] = "Kategori \"$nama_kategori\" berhasil dihapus.";
} else {
    $_SESSION['pesan_error'] = 'Gagal menghapus kategori. Silakan coba lagi.';
}

$stmt_delete->close();

header('Location: index.php');
exit;
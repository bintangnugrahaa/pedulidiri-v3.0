<?php

/**
 * Mulai atau lanjutkan sesi untuk pengelolaan sesi pengguna.
 */
session_start();

/**
 * Mendapatkan id catatan dari permintaan GET.
 * @var string $id_catatan ID catatan perjalanan yang diterima dari permintaan GET.
 */
$id_catatan = $_GET['id'];

/**
 * Mendapatkan NIK pengguna dari sesi yang aktif.
 * @var string $nik NIK pengguna yang disimpan dalam sesi.
 */
$nik = $_SESSION['nik'];

/**
 * Path ke file yang menyimpan catatan perjalanan.
 * @var string $file Path ke file yang menyimpan catatan perjalanan.
 */
$file = "../database/catatan_perjalanan.txt";

/**
 * Membaca data catatan perjalanan dari file teks.
 * Setiap baris data dalam file dibaca dan dipecah menjadi array menggunakan pemisah '|'.
 * @var array $db Array yang berisi data catatan perjalanan dari file.
 */
$db = file($file, FILE_IGNORE_NEW_LINES);

/**
 * Loop melalui setiap baris data dalam file untuk mencari ID catatan yang sesuai.
 * Jika ID catatan ditemukan dan sesuai dengan NIK pengguna yang sedang aktif,
 * baris data yang sesuai dihapus dari file.
 */
$no = 0;
foreach ($db as $value) {
    $pd = explode("|", $value);
    $no++;
    if ($id_catatan == $pd['0']) {
        if ($nik == $pd['1']) {
            $line = $no - 1;
        }
    }
}

/**
 * Hapus baris data yang sesuai dari file catatan perjalanan.
 */
$file_db = file($file);
unset($file_db[$line]);
file_put_contents($file, implode("", $file_db));

/**
 * Siapkan respons JSON untuk memberitahu bahwa catatan perjalanan berhasil dihapus.
 */
$response = [
    'status'     => 'success',
    'msg'        => 'Catatan perjalanan berhasil dihapus',
    'redirect'   => 'catatan-perjalanan',
];

/**
 * Ubah respons yang disiapkan menjadi format JSON menggunakan json_encode().
 * Kemudian, kirim respons JSON kembali sebagai respons dari permintaan.
 */
echo json_encode($response);

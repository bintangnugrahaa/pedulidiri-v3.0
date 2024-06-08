<?php

/**
 * Mulai atau lanjutkan sesi untuk pengelolaan sesi pengguna.
 */
session_start();

/**
 * Menunda eksekusi skrip selama 3 detik untuk simulasi proses login yang lambat.
 */
sleep(3);

/**
 * Mendapatkan NIK dan nama pengguna dari permintaan POST.
 * @var string $nik NIK pengguna yang diterima dari permintaan POST.
 * @var string $nama Nama pengguna yang diterima dari permintaan POST.
 */
$nik = $_POST['nik'];
$nama = $_POST['nama'];

/**
 * Menggabungkan NIK dan nama pengguna menjadi satu string.
 * @var string $data Data pengguna yang disiapkan untuk dibandingkan dengan data dalam file konfigurasi.
 */
$data = $nik . "|" . $nama;

/**
 * Path ke file yang menyimpan data konfigurasi pengguna.
 * @var string $file Path ke file yang menyimpan data konfigurasi pengguna.
 */
$file = "../database/config.txt";

/**
 * Membaca data konfigurasi pengguna dari file teks.
 * Setiap baris data dalam file dibaca dan disimpan dalam array.
 * @var array $db Array yang berisi data konfigurasi pengguna dari file.
 */
$db = file($file, FILE_IGNORE_NEW_LINES);

/**
 * Memeriksa apakah data pengguna yang diberikan ada dalam file konfigurasi.
 * Jika ada, set sesi untuk pengguna dan kirim respons JSON dengan status sukses.
 * Jika tidak, kirim respons JSON dengan status gagal.
 */
if (in_array($data, $db)) {
    $_SESSION['nik']        = $nik;
    $_SESSION['nama']       = $nama;
    $_SESSION['IsLogged']   = true;

    $response = [
        'status'    => 'success',
        'msg'       => 'Login Berhasil!'
    ];
} else {
    $response = [
        'status'    => 'failed',
        'msg'       => 'Login Gagal!'
    ];
}

/**
 * Ubah respons yang disiapkan menjadi format JSON menggunakan json_encode().
 * Kemudian, kirim respons JSON kembali sebagai respons dari permintaan.
 */
echo json_encode($response);

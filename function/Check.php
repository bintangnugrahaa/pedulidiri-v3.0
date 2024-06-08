<?php

/**
 * Mulai atau lanjutkan sesi untuk pengelolaan sesi pengguna.
 */
session_start();

/**
 * Menerima NIK dari permintaan POST untuk diproses.
 * @var string $nik NIK pengguna yang diterima dari permintaan POST.
 */
$nik = $_POST['nik'];

/**
 * Baca konfigurasi database dari file teks.
 * Data pengguna disimpan dalam file teks yang disebut config.txt.
 * Setiap baris data dalam file dibaca dan dipecah menjadi array menggunakan pemisah '|'.
 * @var array $db Array yang berisi data pengguna dari file konfigurasi.
 */
$db = file("../database/config.txt", FILE_IGNORE_NEW_LINES);

/**
 * Loop melalui setiap baris data dalam file konfigurasi untuk mencocokkan NIK pengguna.
 * Jika NIK yang diterima cocok dengan NIK yang ada dalam data, variabel $cek diset dan nama pengguna ditempatkan dalam variabel $nama.
 */
foreach ($db as $data) {
    $pd = explode("|", $data);

    if ($nik == $pd['0']) {
        $cek = true;
        $nama = $pd['1'];
    }
}

/**
 * Siapkan respons JSON berdasarkan hasil pencocokan NIK.
 * Jika NIK ditemukan, respons berhasil disiapkan dengan status 'success' dan nama pengguna.
 * Jika NIK tidak ditemukan, respons gagal disiapkan dengan pesan yang sesuai.
 */
if (isset($cek)) {
    $response = [
        'status'    => 'success',
        'nama'      => $nama
    ];
} else {
    $response = [
        'status'   => 'failed',
        'msg'      => 'NIK yang kamu masukan tidak terdaftar'
    ];
}

/**
 * Ubah respons yang disiapkan menjadi format JSON menggunakan json_encode().
 * Kemudian, kirim respons JSON kembali sebagai respons dari permintaan.
 */
echo json_encode($response);

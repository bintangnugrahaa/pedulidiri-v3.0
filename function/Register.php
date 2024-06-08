<?php

/**
 * Menunda eksekusi skrip selama 3 detik untuk simulasi proses pendaftaran yang lambat.
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
 * Loop melalui setiap baris data dalam file untuk memeriksa apakah NIK pengguna sudah terdaftar.
 * Jika NIK sudah ada dalam file, kirim respons JSON dengan status gagal.
 * Jika NIK belum terdaftar, tambahkan data pengguna baru ke file dan kirim respons JSON dengan status sukses.
 */
foreach ($db as $data) {
    $pd = explode("|", $data);
    if ($nik == $pd['0']) {
        $cek = true;
    }
}

if (isset($cek)) {
    $response = [
        'status'    => 'failed',
        'msg'       => 'NIK yang Anda gunakan telah terdaftar',
    ];
} else {
    /**
     * Menyiapkan data pengguna baru untuk ditambahkan ke file.
     * Kemudian, menambahkan data pengguna baru ke file konfigurasi.
     */
    $data = $nik . "|" . $nama . "\n";
    $db = fopen($file, 'a');
    fwrite($db, $data);
    fclose($db);

    $response = [
        'status'    => 'success',
        'msg'       => 'Pendaftaran berhasil, Silahkan login'
    ];
}

/**
 * Ubah respons yang disiapkan menjadi format JSON menggunakan json_encode().
 * Kemudian, kirim respons JSON kembali sebagai respons dari permintaan.
 */
echo json_encode($response);

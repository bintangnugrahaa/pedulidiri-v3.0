<?php

/**
 * Mulai atau lanjutkan sesi untuk pengelolaan sesi pengguna.
 */
session_start();

/**
 * Mendapatkan NIK pengguna dari sesi yang aktif.
 * @var string $nik NIK pengguna yang disimpan dalam sesi.
 */
$nik = $_SESSION['nik'];

/**
 * Mendapatkan data yang dikirim melalui permintaan POST.
 * @var string $tanggal Tanggal perjalanan yang dikirim melalui permintaan POST.
 * @var string $jam Jam perjalanan yang dikirim melalui permintaan POST.
 * @var string $lokasi Lokasi perjalanan yang dikirim melalui permintaan POST.
 * @var string $suhu Suhu yang dikirim melalui permintaan POST.
 */
$tanggal = $_POST['tanggal'];
$jam = $_POST['jam'];
$lokasi = $_POST['lokasi'];
$suhu = $_POST['suhu'];

/**
 * Path ke file yang menyimpan catatan perjalanan.
 * @var string $file Path ke file yang menyimpan catatan perjalanan.
 */
$file = "../database/catatan_perjalanan.txt";

/**
 * Buka file catatan perjalanan untuk ditulis (mode "a" untuk menambahkan data ke akhir file).
 * @var resource $fh Penanganan file untuk file catatan perjalanan.
 */
$fh = fopen($file, "a");

/**
 * Baca data catatan perjalanan dari file teks.
 * Setiap baris data dalam file dibaca dan dipecah menjadi array menggunakan pemisah '|'.
 * @var array $db Array yang berisi data catatan perjalanan dari file.
 */
$db = file($file, FILE_IGNORE_NEW_LINES);

/**
 * Inisialisasi variabel ID untuk catatan baru.
 */
$id = 1;

/**
 * Loop melalui setiap baris data dalam file untuk mencari ID catatan baru.
 * ID catatan baru dihitung sebagai ID terakhir ditambah satu.
 */
foreach ($db as $value) {
    $pd = explode("|", $value);
    $id = $pd['0'] + 1;

    if ($nik == $pd['1']) {
        if ($tanggal == $pd['2'] && $jam == $pd['3']) {
            $validation = true;
            $msg = 'Tidak dapat menyimpan catatan, Tanggal dan Jam yang kamu masukkan sudah ada di database!';
            break;
        }
        if ($lokasi == $pd['4'] && $suhu == $pd['5']) {
            $validation = true;
            $msg = 'Tidak dapat menyimpan catatan, Catatan ini sudah ada di database!';
            break;
        }
    }
}

/**
 * Jika validasi gagal, tambahkan catatan baru ke file catatan perjalanan.
 * Jika validasi berhasil, kirim respons JSON dengan pesan kesalahan.
 */
if (isset($validation)) {
    $response = [
        'status'    => 'failed',
        'msg'       => $msg
    ];
} else {
    /**
     * Format data catatan baru sesuai dengan format yang diharapkan dalam file.
     * Kemudian, tulis data catatan baru ke akhir file catatan perjalanan.
     */
    $data = $id . "|" . $nik . "|" . $tanggal . "|" . $jam . "|" . $lokasi . "|" . $suhu . "\n";
    fwrite($fh, $data);
    fclose($fh);

    $response = [
        'status'    => 'success',
        'msg'       => 'Catatan berhasil ditambahkan!',
        'redirect'  => 'catatan-perjalanan'
    ];
}

/**
 * Ubah respons yang disiapkan menjadi format JSON menggunakan json_encode().
 * Kemudian, kirim respons JSON kembali sebagai respons dari permintaan.
 */
echo json_encode($response);

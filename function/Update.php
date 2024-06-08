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
 * @var string $id_catatan ID catatan perjalanan yang dikirim melalui permintaan POST.
 * @var string $tanggal Tanggal perjalanan yang dikirim melalui permintaan POST.
 * @var string $jam Jam perjalanan yang dikirim melalui permintaan POST.
 * @var string $lokasi Lokasi perjalanan yang dikirim melalui permintaan POST.
 * @var string $suhu Suhu yang dikirim melalui permintaan POST.
 */
$id_catatan = $_POST['id_catatan'];
$tanggal = $_POST['tanggal'];
$jam = $_POST['jam'];
$lokasi = $_POST['lokasi'];
$suhu = $_POST['suhu'];

/**
 * Format data catatan perjalanan baru.
 * @var string $format Format data catatan perjalanan baru.
 */
$format = $id_catatan . "|" . $nik . "|" . $tanggal . "|" . $jam . "|" . $lokasi . "|" . $suhu;

/**
 * Path ke file yang menyimpan catatan perjalanan.
 * @var string $file Path ke file yang menyimpan catatan perjalanan.
 */
$file = "../database/catatan_perjalanan.txt";

/**
 * Membaca data catatan perjalanan dari file teks.
 * Setiap baris data dalam file dibaca dan disimpan dalam array.
 * @var array $db Array yang berisi data catatan perjalanan dari file.
 */
$db = file($file, FILE_IGNORE_NEW_LINES);

/**
 * Mendapatkan seluruh isi file sebagai string.
 * @var string $files Isi file catatan perjalanan.
 */
$files = file_get_contents($file);

/**
 * Loop melalui setiap baris data dalam file untuk mencari ID catatan yang sesuai.
 * Jika ID catatan ditemukan, baris data yang sesuai diganti dengan data baru.
 * Data baru kemudian ditulis kembali ke file catatan perjalanan.
 */
foreach ($db as $value) {
    $pd = explode("|", $value);
    if ($id_catatan == $pd['0']) {
        $dataOld = ["$pd[0]|$pd[1]|$pd[2]|$pd[3]|$pd[4]|$pd[5]"];
        $dataNew = ["$format"];

        $replace = str_replace($dataOld, $dataNew, $files);

        file_put_contents($file, $replace);
    }
}

/**
 * Siapkan respons JSON untuk memberitahu bahwa catatan perjalanan berhasil diubah.
 */
$response = [
    'status'    => 'success',
    'msg'       => 'Catatan perjalanan berhasil diubah',
    'redirect'  => 'catatan-perjalanan'
];

/**
 * Ubah respons yang disiapkan menjadi format JSON menggunakan json_encode().
 * Kemudian, kirim respons JSON kembali sebagai respons dari permintaan.
 */
echo json_encode($response);

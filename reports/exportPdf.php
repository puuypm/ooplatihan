<?php
require_once "../config/+koneksi.php";
require_once "../models/database.php";
require_once "../models/m_barang.php";

$conn = new Database($host, $user, $pass, $database);
$brg = new Barang($conn);

require_once __DIR__ . '/../assets/vendor/autoload.php';

$mpdf = new \Mpdf\Mpdf();
$html = '<h1>Data Barang</h1>
<table border="1">
    <tr>
        <th>No</th>
        <th>Nama Barang</th>
        <th>Harga Barang</th>
        <th>Stock Barang</th>
        <th>Gambar Barang</th>
    </tr>';
$no = 1;
if (isset($_POST["cetak_barang"])) {
    $tgl_a = $_POST['tgl_a'];
    $tgl_b = $_POST['tgl_b'];
    $tampil = $brg->tampil_brg($tgl_a, $tgl_b);
} else {
    $tampil = $brg->tampil();
}

while ($data = $tampil->fetch_object()) :
    $html .= '
    <tr>
        <td>' . $no++ . '</td>
        <td>' . $data->nama_barang . '</td>
        <td>' . $data->harga_barang . '</td>
        <td>' . $data->stock_barang . '</td>
        <td>
        <img src="../assets/img/barang/' . $data->gbr_barang . '" width = "70" height = "auto">
        </td>
    </tr>';
endwhile;

$html .= '</table>
';
$mpdf->WriteHTML($html);
$mpdf->Output();

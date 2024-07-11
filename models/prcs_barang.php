<?php
require_once "../config/+koneksi.php";
require_once "../models/database.php";
require_once "../models/m_barang.php";

$conn = new Database($host, $user, $pass, $database);
$brg = new Barang($conn);

$id = filter_input(INPUT_POST, "idedt", FILTER_SANITIZE_NUMBER_INT);
$nama_barang = filter_input(INPUT_POST, "nama_barang", FILTER_SANITIZE_SPECIAL_CHARS);
$harga_barang = filter_input(INPUT_POST, "harga_barang", FILTER_SANITIZE_NUMBER_FLOAT);
$stock_barang = filter_input(INPUT_POST, "stock_barang", FILTER_SANITIZE_NUMBER_INT);

$pict = $_FILES['gbr_barang']['name'];
$gbr_barang = '';

if (!empty($pict)) {
    $old_data = $brg->tampil($id)->fetch_assoc();
    $old_gbr_barang = $old_data['gbr_barang'];

    $extensi = explode(".", $pict);
    $gbr_barang = "brg-" . round(microtime(true)) . "." . end($extensi);
    $sumber = $_FILES['gbr_barang']['tmp_name'];
    $upload_path = "../assets/img/barang/" . $gbr_barang;

    if (move_uploaded_file($sumber, $upload_path)) {
        if ($old_gbr_barang && file_exists("../assets/img/barang/" . $old_gbr_barang)) {
            unlink("../assets/img/barang/" . $old_gbr_barang);
        }
        $brg->edit("UPDATE tb_barang SET nama_barang = ?, harga_barang = ?, stock_barang = ?, gbr_barang = ? WHERE id_barang = ?", [
            $nama_barang,
            $harga_barang,
            $stock_barang,
            $gbr_barang,
            $id
        ]);
        echo "<script>window.location = '?page=barang'</script>";
        exit;
    } else {
        echo "Gagal mengunggah gambar.";
        exit;
    }
} else {
    $brg->edit("UPDATE tb_barang SET nama_barang = ?, harga_barang = ?, stock_barang = ? WHERE id_barang = ?", [
        $nama_barang,
        $harga_barang,
        $stock_barang,
        $id
    ]);
    echo "<script>window.location = '?page=barang'</script>";
    exit;
}

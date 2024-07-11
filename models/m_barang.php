<?php
class Barang
{
    private $mysqli;

    function __construct($conn)
    {
        $this->mysqli = $conn;
    }
    public function tampil($id = null)
    {
        $db = $this->mysqli->conn;
        $sql = "SELECT * FROM tb_barang";
        if ($id != null) {
            $sql .= " WHERE id_barang = ?";
        }
        $stmt = $db->prepare($sql);
        if ($id != null) {
            $stmt->bind_param("i", $id);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;
    }

    public function tampil_brg($tgl1, $tgl2)
    {
        $db = $this->mysqli->conn;
        $sql = "SELECT * FROM tb_barang WHERE tgl_publish BETWEEN ? AND ?";

        $stmt = $db->prepare($sql);
        if ($stmt === false) {
            die("Stetmentnya error" . $db->error);
        }
        $stmt->bind_param("ss", $tgl1, $tgl2);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result;
    }


    public function tambah($nama_barang, $harga_barang, $stock_barang, $gbr_barang)
    {
        $db = $this->mysqli->conn;
        $stmt = $db->prepare("INSERT INTO tb_barang (nama_barang, harga_barang, stock_barang, gbr_barang, tgl_publish) VALUES (?,?,?,?,now())");
        if ($stmt === false) {
            die("Stetmentnya error" . $db->error);
        }
        $stmt->bind_param("sdis", $nama_barang, $harga_barang, $stock_barang, $gbr_barang);
        $result = $stmt->execute();
        if ($result === false) {
            die("INSERTNYA ERROR BROOO" . $stmt->error);
        }
        $stmt->close();
        return $result;
    }

    public function edit($sql, $params)
    {
        $db = $this->mysqli->conn;
        $stmt = $db->prepare($sql);

        if ($stmt === false) {
            die("PREPARE STATEMENT ERROR BRO" . $db->error);
        }
        $type = str_repeat('s', count($params));
        $stmt->bind_param($type, ...$params);
        $result = $stmt->execute();
        if ($result === false) {
            die("Update error" . $stmt->error);
        }
        $stmt->close();
        return $result;
    }

    public function hapus($id)
    {
        $db = $this->mysqli->conn;

        $stmt = $db->prepare("DELETE FROM tb_barang WHERE id_barang = ?");
        if ($stmt === false) {
            die("Prepare statementnya error" . $db->error);
        }
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        if ($result === false) {
            die("Delete error" . $stmt->error);
        }
        $stmt->close();
        return $result;
    }
}

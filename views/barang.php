<?php
require_once "models/m_barang.php";
$brg = new Barang($conn);


if (@$_GET['act'] == '') {
?>

  <div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">Data Barang</h1>
    <!--11.  -->
    <div class="row">
      <div class="col-lg-12">
        <div class="table-responsive">
          <table class="table table-bordered table-hover table-striped">
            <!--12.Buat Database nya db_latihan.sql -->
            <thead>
              <tr>
                <th>No.</th>
                <th>Nama Barang</th>
                <th>Harga Barang</th>
                <th>Stock Barang</th>
                <th>Gambar Barang</th>
                <th>Opsi</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $no = 1;
              $tampil = $brg->tampil();

              while ($isi = $tampil->fetch_object()) :
              ?>
                <tr class="text-center">
                  <td><?= $no++ ?></td>
                  <td><?= $isi->nama_barang ?></td>
                  <td><?= $isi->harga_barang ?></td>
                  <td><?= $isi->stock_barang ?></td>
                  <td><img src="assets/img/barang/<?= $isi->gbr_barang ?>" width="80" alt=""></td>
                  <td>
                    <a id="edit_barang" data-toggle="modal" data-target="#edit" data-idedt="<?= $isi->id_barang ?>" data-nama="<?= $isi->nama_barang ?>" data-harga="<?= $isi->harga_barang ?>" data-stc="<?= $isi->stock_barang ?>" data-gbr="<?= $isi->gbr_barang ?>">
                      <button class="btn btn-info btn-sm"><i class="fa fa-edit"></i> Edit</button></a>
                    <a href="?page=barang&act=del&id=<?= base64_encode($isi->id_barang) ?>" onclick="return confirm('Apakah ingin menghapus data ini ?')">
                      <button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button></a>
                  </td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#tambah"><i class="fa fa-plus"></i> Tambah Data</button>
        <a class="btn btn-default" data-toggle="modal" data-target="#cetakpdf" style="margin-bottom: 5px"><i class="fa fa-print"></i> Print PDF</a>
        <!-- Modal Tambah -->
        <div id="tambah" class="modal fade" role="dialog">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title">Tambah Data Barang</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>
              <form method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                  <div class="form-group">
                    <label for="nama_barang" class="form-label">Nama Barang</label>
                    <input type="text" name="nama_barang" class="form-control" id="nama_barang" required>
                  </div>
                  <div class="form-group">
                    <label for="harga_barang" class="form-label">Harga Barang</label>
                    <input type="number" name="harga_barang" class="form-control" id="harga_barang" required>
                  </div>
                  <div class="form-group">
                    <label for="stock_barang" class="form-label">Stock Barang</label>
                    <input type="text" name="stock_barang" class="form-control" id="stock_barang" required>
                  </div>
                  <div class="form-group">
                    <label for="gbr_barang" class="form-label">Gambar Barang</label>
                    <input type="file" name="gbr_barang" class="form-control" id="gbr_barang" accept=".png, .jpg, .jpeg, .gif, .svg, .webp" required>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="reset" class="btn btn-danger">Reset</button>
                  <button type="submit" class="btn btn-primary" name="Tambah">Tambah</button>
                </div>
              </form>
              <?php
              if (isset($_POST['Tambah'])) {
                $nama_barang =  filter_input(INPUT_POST, "nama_barang", FILTER_SANITIZE_SPECIAL_CHARS);
                $harga_barang = filter_input(INPUT_POST, "harga_barang", FILTER_SANITIZE_NUMBER_FLOAT);
                $stock_barang = filter_input(INPUT_POST, "stock_barang", FILTER_SANITIZE_NUMBER_INT);

                if (isset($_FILES['gbr_barang']) && $_FILES['gbr_barang']['error'] === UPLOAD_ERR_OK) {
                  $file_name = $_FILES['gbr_barang']['name'];
                  $file_tmp = $_FILES['gbr_barang']['tmp_name'];
                  $file_size = $_FILES['gbr_barang']['size'];
                  $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                  $allowed_ext = array("png", "jpg", "jpeg", "gif", "svg", "webp");

                  if (in_array($file_ext, $allowed_ext)) {
                    $gbr_barang = "brg-" . round(microtime(true)) . "." . $file_ext;
                    $upload_path = "assets/img/barang/" . $gbr_barang;

                    if (move_uploaded_file($file_tmp, $upload_path)) {
                      $result = $brg->tambah($nama_barang, $harga_barang, $stock_barang, $gbr_barang);
                      if ($result) {
                        echo "<script>alert('Data Barang Berhasil Ditambah!');</script>";
                        echo "<script>window.location = '?page=barang';</script>";
                        exit();
                      } else {
                        echo "<script>alert('Data Barang Gagal Ditambah!');</script>";
                        echo "<script>window.location = '?page=barang';</script>";
                        exit();
                      }
                    } else {
                      echo "<script>alert('Data Mengunggah Gambar!');</script>";
                      echo "<script>window.location = '?page=barang';</script>";
                      exit();
                    }
                  } else {
                    echo "<script>alert('Tipe File yang di unggah tidak di izinkan!');</script>";
                    echo "<script>window.location = '?page=barang';</script>";
                    exit();
                  }
                } else {
                  echo "<script>alert('Data Terjadi Kesalahan saat mengungah file!');</script>";
                  echo "<script>window.location = '?page=barang';</script>";
                  exit();
                }
              }
              ?>
            </div>
          </div>
        </div>
        <!-- Modal Edit -->
        <div id="edit" class="modal fade" role="dialog">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title">Edit Data Barang</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>
              <form id="formEdit" enctype="multipart/form-data">
                <div class="modal-body" id="modalBody_">
                  <div class="form-group">
                    <input type="hidden" id="idedt" name="idedt">
                    <label for="nama_barang" class="form-label">Nama Barang</label>
                    <input type="text" name="nama_barang" class="form-control" id="nama_barang" required>
                  </div>
                  <div class="form-group">
                    <label for="harga_barang" class="form-label">Harga Barang</label>
                    <input type="number" name="harga_barang" class="form-control" id="harga_barang" required>
                  </div>
                  <div class="form-group">
                    <label for="stock_barang" class="form-label">Stock Barang</label>
                    <input type="text" name="stock_barang" class="form-control" id="stock_barang" required>
                  </div>
                  <div class="form-group">
                    <label for="gbr_barang" class="form-label">Gambar Barang</label>
                    <div class="my-2">
                      <img src="" alt="" width="80" id="pict">
                    </div>
                    <input type="file" name="gbr_barang" class="form-control" id="gbr_barang" accept=".png, .jpg, .jpeg, .gif, .svg, .webp">
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="submit" class="btn btn-primary" name="edit">Edit</button>
                </div>
              </form>
            </div>
          </div>
        </div>
        <script src="assets/vendor/jquery/jquery.min.js" type="text/javascript"></script>
        <script>
          $(document).on("click", "#edit_barang", function() {
            var idbarang = $(this).data('idedt');
            var nama = $(this).data('nama');
            var hargabarang = $(this).data('harga');
            var stockbarang = $(this).data('stock');
            var gbrbarang = $(this).data('gbr');

            $("#modalBody_ #idedt").val(idbarang);
            $("#modalBody_ #nama_barang").val(nama);
            $("#modalBody_ #harga_barang").val(hargabarang);
            $("#modalBody_ #stock_barang").val(stockbarang);
            $("#modalBody_ #pict").attr("src", "assets/img/barang/" + gbrbarang);
          })
          // process :
          $(document).ready(function(e) {
            $("#formEdit").on("submit", (function(e) {
              e.preventDefault();
              $.ajax({
                url: 'models/prcs_barang.php',
                type: 'POST',
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function(msg) {
                  $('.table').html(msg);
                }
              })
            }))
          })
        </script>

        <div id="cetakpdf" class="modal fade" role="dialog">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title">Cetak PDF</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>
              <div class="modal-body">
                <form action="reports/exportPdf.php" method="POST" target="_blank">
                  <table>
                    <tr>
                      <td>
                        <div class="form-group">Dari Tanggal</div>
                      </td>
                      <td align="center" width=5%>
                        <div class="form-group">:</div>
                      </td>
                      <td>
                        <div class="form-group">
                          <input type="date" class="form-control" name="tgl_a" required>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <div class="form-group">Sampai Tanggal</div>
                      </td>
                      <td align="center" width=5%>
                        <div class="form-group">:</div>
                      </td>
                      <td>
                        <div class="form-group">
                          <input type="date" class="form-control" name="tgl_b" required>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td></td>
                      <td></td>
                      <td>
                        <input type="submit" name="cetak_barang" class="btn btn-primary btn-sm" value="Print per-periode">
                      </td>
                    </tr>
                  </table>
                </form>
              </div>
              <div class="modal-footer">
                <a href="reports/exportPdf.php" target="_blank" class="btn btn-primary btn-sm">Cetak Semua Data</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php
} else if (@$_GET['act'] == 'del') {
  $iddecode = base64_decode($_GET['id']);
  echo "id nya :" . $iddecode;
  $gbr_awal = $brg->tampil($iddecode)->fetch_object()->gbr_barang;

  unlink("assets/img/barang/" . $gbr_awal);
  $brg->hapus($iddecode);

  echo "<script>window.location = '?page=barang';</script>";
  exit();
}
?>
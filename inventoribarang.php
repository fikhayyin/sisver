<?php
//cek session
if (empty($_SESSION['admin'])) {
    $_SESSION['err'] = '<center>Anda harus login terlebih dahulu!</center>';
    header("Location: ./");
    die();
} else {

    if ($_SESSION['admin'] != 1 and $_SESSION['admin'] != 3) {
        echo '<script language="javascript">
                    window.alert("ERROR! Anda tidak memiliki hak akses untuk membuka halaman ini");
                    window.location.href="./logout.php";
                  </script>';
    } else {

        if (isset($_REQUEST['act'])) {
            $act = $_REQUEST['act'];
            switch ($act) {
                case 'tbh':
                    include "tambahbarang.php";
                    break;
                case 'edit':
                    include "editbarang.php";
                    break;
                case 'del':
                    include "hapusbarang.php";
                    break;
            }
        } else {

            $query = mysqli_query($config, "SELECT barang FROM pengaturan");
            list($barang) = mysqli_fetch_array($query);

            //pagging
            $limit = $barang;
            $pg = @$_GET['pg'];
            if (empty($pg)) {
                $curr = 0;
                $pg = 1;
            } else {
                $curr = ($pg - 1) * $limit;
            } ?>

            <!-- Row Start -->
            <div class="row">
                <!-- Secondary Nav START -->
                <div class="col s12">
                    <div class="z-depth-1">
                        <nav class="secondary-nav">
                            <div class="nav-wrapper blue-grey darken-1">
                                <div class="col m7">
                                    <ul class="left">
                                        <li class="waves-effect waves-light hide-on-small-only"><a href="?page=ibm" class="judul"><i class="material-icons">local_shipping</i>Barang Masuk</a></li>
                                        <li class="waves-effect waves-light">
                                            <a href="?page=ibm&act=tbh"><i class="material-icons md-24">add_circle</i> Tambah Barang</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col m5 hide-on-med-and-down">
                                    <form method="post" action="?page=ibm">
                                        <div class="input-field round-in-box">
                                            <input id="search" type="search" name="cari" placeholder="Ketik dan tekan enter mencari data..." required>
                                            <label for="search"><i class="material-icons md-dark">search</i></label>
                                            <input type="submit" name="submit" class="hidden">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </nav>
                    </div>
                </div>
                <!-- Secondary Nav END -->
            </div>
            <!-- Row END -->

            <?php
            if (isset($_SESSION['succAdd'])) {
                $succAdd = $_SESSION['succAdd'];
                <?= '<div id="alert-message" class="row">
                                <div class="col m12">
                                    <div class="card green lighten-5">
                                        <div class="card-content notif">
                                            <span class="card-title green-text"><i class="material-icons md-36">done</i> ' . $succAdd . '</span>
                                        </div>
                                    </div>
                                </div>
                            </div>' ?>;
                unset($_SESSION['succAdd']);
            }
            if (isset($_SESSION['succEdit'])) {
                $succEdit = $_SESSION['succEdit'];
                <?= '<div id="alert-message" class="row">
                                <div class="col m12">
                                    <div class="card green lighten-5">
                                        <div class="card-content notif">
                                            <span class="card-title green-text"><i class="material-icons md-36">done</i> ' . $succEdit . '</span>
                                        </div>
                                    </div>
                                </div>
                            </div>' ?>;
                unset($_SESSION['succEdit']);
            }
            if (isset($_SESSION['succDel'])) {
                $succDel = $_SESSION['succDel'];
                <?= '<div id="alert-message" class="row">
                                <div class="col m12">
                                    <div class="card green lighten-5">
                                        <div class="card-content notif">
                                            <span class="card-title green-text"><i class="material-icons md-36">done</i> ' . $succDel . '</span>
                                        </div>
                                    </div>
                                </div>
                            </div>' ?>;
                unset($_SESSION['succDel']);
            }
            ?>

            <!-- Row form Start -->
            <div class="row jarak-form">

    <?php
            if (isset($_REQUEST['submit'])) {
                $cari = mysqli_real_escape_string($config, $_REQUEST['cari']);
                <?= '
                        <div class="col s12" style="margin-top: -18px;">
                            <div class="card blue lighten-5">
                                <div class="card-content">
                                <p class="description">Hasil pencarian untuk kata kunci <strong>"' . stripslashes($cari) . '"</strong><span class="right"><a href="?page=ibm"><i class="material-icons md-36" style="color: #333;">clear</i></a></span></p>
                                </div>
                            </div>
                        </div>

                        <div class="col m12" id="colres">
                        <table class="bordered" id="tbl">
                            <thead class="blue lighten-4" id="head">
                                <tr>
                                <th width="5%">Nomor</th>
                                <th width="15%">Tanggal Penerimaan</th>
                                <th width="8%">Merk</th>
                                <th width="7%">Tipe</th>
                                <th width="15%">Nama Equipment<br/></th>
                                <th width="15%">Serial Number</th>
                                <th width="17%">Lokasi Barang</th>
                                <th width="18%">Tindakan <span class="right"><i class="material-icons" style="color: #333;">settings</i></span></th>
                                </tr>
                            </thead>
                            <tbody>' ?>;

                //script untuk mencari data
                $query = mysqli_query($config, "SELECT * FROM barang WHERE nama_brg LIKE '%$cari%' ORDER by id_brg DESC LIMIT 15");
                if (mysqli_num_rows($query) > 0) {
                    $no = 1;
                    $no_barang = 1;
                    while ($row = mysqli_fetch_array($query)) {
                        <?= '
                        <tr>
                        <td>' . $no_barang . '</td>
                        <td>' . indoDate($row['tgl_brg']) . '</td>
                        <td>' . $row['merk_brg'] . '</td>
                        <td>' . $row['tipe'] . '</td>
                        <td>' . substr($row['nama_brg'], 0, 200) . '<br/><strong></strong>' ?>;

                        if (!empty($row[''])) {
                            <?= ' <strong><a href="?page=gsm&act=fsm&id_brg=' . $row['id_brg'] . '">' . $row[''] . '</a></strong>' ?>;
                        } else {
                            <?= '<em></em>' ?>;
                        }
                        <?= '</td>
                        <td>' . $row['jumlah_brg'] . '</td>
                        <td>' . $row['lokasi_brg'] . '</td>
                        <td>' ?>;

                        if ($_SESSION['id_user'] != $row['id_user'] and $_SESSION['id_user'] != 1 and $_SESSION['id_user'] != 2) {
                            <?= '<a class="btn small yellow darken-3 waves-effect waves-light" href="?page=ctk&id_brg=' . $row['id_brg'] . '" target="_blank">
                                            <i class="material-icons">print</i> PRINT</a>' ?>;
                        } else {
                            <?= '<a class="btn small blue waves-effect waves-light" href="?page=ibm&act=edit&id_brg=' . $row['id_brg'] . '">
                                                <i class="material-icons">edit</i> EDIT</a>
                                            <a class="btn small deep-orange waves-effect waves-light" href="?page=ibm&act=del&id_brg=' . $row['id_brg'] . '">
                                                <i class="material-icons">delete</i> DEL</a>' ?>;
                        }
                        <?= '
                                        </td>
                                    </tr>' ?>;
                        $no_barang++;
                    }
                } else {
                    <?= '<tr><td colspan="5"><center><p class="tbh">Tidak ada data yang ditemukan</p></center></td></tr>' ?>;
                }
                <?= '</tbody></table><br/><br/>
                        </div>
                    </div>
                    <!-- Row form END -->' ?>;
            } else {

                <?= '
                        <div class="col m12" id="colres">
                            <table class="bordered" id="tbl">
                                <thead class="blue lighten-4" id="head">
                                    <tr>
                                        <th width="5%">Nomor</th>
                                        <th width="15%">Tanggal Penerimaan</th>
                                        <th width="8%">Merk</th>
                                        <th width="7%">Tipe</th>
                                        <th width="15%">Nama Equipment<br/></th>
                                        <th width="15%">Serial Number</th>
                                        <th width="17%">Lokasi Barang</th>
                                        <th width="18%">Tindakan <span class="right tooltipped" data-position="left" data-tooltip="Atur jumlah data yang ditampilkan"><a class="modal-trigger" href="#modal"><i class="material-icons" style="color: #333;">settings</i></a></span></th>

                                            <div id="modal" class="modal">
                                                <div class="modal-content white">
                                                    <h5>Jumlah data yang ditampilkan per halaman</h5>' ?>;
                $query = mysqli_query($config, "SELECT id_atur,barang FROM pengaturan");
                list($id_atur, $barang) = mysqli_fetch_array($query);
                <?= '
                                                    <div class="row">
                                                        <form method="post" action="">
                                                            <div class="input-field col s12">
                                                                <input type="hidden" value="' . $id_atur . '" name="id_atur">
                                                                <div class="input-field col s1" style="float: left;">
                                                                    <i class="material-icons prefix md-prefix">looks_one</i>
                                                                </div>
                                                                <div class="input-field col s11 right" style="margin: -5px 0 20px;">
                                                                    <select class="browser-default validate" name="barang" required>
                                                                        <option value="' . $barang . '">' . $barang . '</option>
                                                                        <option value="5">5</option>
                                                                        <option value="10">10</option>
                                                                        <option value="20">20</option>
                                                                        <option value="50">50</option>
                                                                        <option value="100">100</option>
                                                                    </select>
                                                                </div>
                                                                <div class="modal-footer white">
                                                                    <button type="submit" class="modal-action waves-effect waves-green btn-flat" name="simpan">Simpan</button>' ?>;
                if (isset($_REQUEST['simpan'])) {
                    $id_atur = "1";
                    $barang = $_REQUEST['barang'];
                    $id_user = $_SESSION['id_user'];

                    $query = mysqli_query($config, "UPDATE pengaturan SET barang='$barang',id_user='$id_user' WHERE id_atur='$id_atur'");
                    if ($query == true) {
                        header("Location: ./admin.php?page=ibm");
                        die();
                    }
                }
                <?= '
                                                                    <a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat">Batal</a>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                    </tr>
                                </thead>
                                <tbody>' ?>;

                //script untuk menampilkan data
                $query = mysqli_query($config, "SELECT * FROM barang ORDER by id_brg DESC LIMIT $curr, $limit");
                if (mysqli_num_rows($query) > 0) {
                    $no = 1;
                    $no_barang = 1;
                    while ($row = mysqli_fetch_array($query)) {
                        <?= '
                                      <tr>
                                        <td>' . $no_barang . '</td>
                                        <td>' . indoDate($row['tgl_brg']) . '</td>
                                        <td>' . $row['merk_brg'] . '</td>
                                        <td>' . $row['tipe'] . '</td>
                                        <td>' . substr($row['nama_brg'], 0, 200) . '<br/><strong></strong>' ?>;

                        if (!empty($row[''])) {
                            <?= ' <strong><a href="?page=gsm&act=fsm&id_brg=' . $row['id_brg'] . '">' . $row[''] . '</a></strong>' ?>;
                        } else {
                            <?= '<em></em>' ?>;
                        }
                        <?= '</td>
                                        <td>' . $row['jumlah_brg'] . '</td>
                                        <td>' . $row['lokasi_brg'] . '</td>
                                        <td>' ?>;

                        if ($_SESSION['id_user'] != $row['id_user'] and $_SESSION['id_user'] != 1 and $_SESSION['id_user'] != 2) {
                            echo '<a class="btn small yellow darken-3 waves-effect waves-light" href="?page=ctk&id_brg=' . $row['id_brg'] . '" target="_blank">
                                                <i class="material-icons">print</i> PRINT</a>';
                        } else {
                            <?= '<a class="btn small blue waves-effect waves-light" href="?page=ibm&act=edit&id_brg=' . $row['id_brg'] . '">
                                                    <i class="material-icons">edit</i> EDIT</a>
                                                <a class="btn small deep-orange waves-effect waves-light" href="?page=ibm&act=del&id_brg=' . $row['id_brg'] . '">
                                                    <i class="material-icons">delete</i> DEL</a>' ?>;
                        }
                        <?= '
                                        </td>
                                    </tr>' ?>;
                        $no_barang++;
                    }
                } else {
                    <?= '<tr><td colspan="5"><center><p class="tbh">Tidak ada barang untuk ditampilkan. <u><a href="?page=ibm&act=tbh">Tambah barang baru</a></u></p></center></td></tr>' ?>;
                }
                <?= '</tbody></table>
                        </div>
                    </div>
                    <!-- Row form END -->' ?>;

                $query = mysqli_query($config, "SELECT * FROM barang");
                $cdata = mysqli_num_rows($query);
                $cpg = ceil($cdata / $limit);

                <?= '<br/><!-- Pagination START -->
                          <ul class="pagination">' ?>;

                if ($cdata > $limit) {

                    //first and previous pagging
                    if ($pg > 1) {
                        $prev = $pg - 1;
                        <?= '<li><a href="?page=ibm&pg=1"><i class="material-icons md-48">first_page</i></a></li>
                                  <li><a href="?page=ibm&pg=' . $prev . '"><i class="material-icons md-48">chevron_left</i></a></li>' ?>;
                    } else {
                        <?= '<li class="disabled"><a href="#"><i class="material-icons md-48">first_page</i></a></li>
                                  <li class="disabled"><a href="#"><i class="material-icons md-48">chevron_left</i></a></li>' ?>;
                    }

                    //perulangan pagging
                    for ($i = 1; $i <= $cpg; $i++) {
                        if ((($i >= $pg - 3) && ($i <= $pg + 3)) || ($i == 1) || ($i == $cpg)) {
                            if ($i == $pg) echo '<li class="active waves-effect waves-dark"><a href="?page=ibm&pg=' . $i . '"> ' . $i . ' </a></li>';
                            else echo '<li class="waves-effect waves-dark"><a href="?page=ibm&pg=' . $i . '"> ' . $i . ' </a></li>';
                        }
                    }

                    //last and next pagging
                    if ($pg < $cpg) {
                        $next = $pg + 1;
                        <?= '<li><a href="?page=ibm&pg=' . $next . '"><i class="material-icons md-48">chevron_right</i></a></li>
                                  <li><a href="?page=ibm&pg=' . $cpg . '"><i class="material-icons md-48">last_page</i></a></li>' ?>;
                    } else {
                        <?= '<li class="disabled"><a href="#"><i class="material-icons md-48">chevron_right</i></a></li>
                                  <li class="disabled"><a href="#"><i class="material-icons md-48">last_page</i></a></li>' ?>;
                    }
                    <?= '
                        </ul>' ?>;
                } else {
                    echo '';
                }
            }
        }
    }
}
    ?>

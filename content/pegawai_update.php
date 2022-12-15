<?php 
  if(!defined('INDEX')) die ("");

  $foto = $_FILES['foto']['name'];
  $lokasi = $_FILES['foto']['tmp_name'];
  $tipefile = $_FILES['foto']['type'];
  $ukuranfile = $_FILES['foto']['size'];

  $error = "";
  if($foto == "") {
    $query = mysqli_query($con, "UPDATE pekerja SET 
      nama_pegawai = '$_POST[nama]',
      jenis_kelamin = '$_POST[jk]',
      tgl_lahir = '$_POST[tanggal]',
      id_jabatan = '$_POST[jabatan]',
      keterangan = '$_POST[keterangan]' WHERE id_pegawai = '$_POST[id]'");
  }
  else {
    if($tipefile != "image/jpeg" AND $tipefile != "image/jpg" AND $tipefile != "image/png") {
      $error = "Tipe file tidak didukung!";
    }
    else if ($ukuranfile > 1000000) {
      echo $ukuranfile;
      $error = 'Ukuran file terlalu besar (lebih dari 1MB)!';
    }
    else {
      $query = mysqli_query($con, "SELECT * FROM pekerja WHERE id_pegawai = '$_POST[id]'");
      $data = mysqli_fetch_array($query);
      if (file_exists("images/$data[foto]"))
        unlink("images/$data[foto]");
      move_uploaded_file($lokasi, "images/".$foto);
      $query = mysqli_query($con, "UPDATE pekerja SET 
        foto = '$foto',
        nama_pegawai = '$_POST[nama]',
        jenis_kelamin = '$_POST[jk]',
        tgl_lahir = '$_POST[tanggal]',
        id_jabatan = '$_POST[jabatan]',
        keterangan = '$_POST[keterangan]' WHERE id_pegawai = '$_POST[id]'");
    }
  }

  if ($error != "") {
    echo $error;
    echo "<meta http-equiv='refresh' content='2;url=?hal=pegawai_edit&id=$_POST[id]'>";
  }
  else if ($query) {
    echo "Data Berhasil Disimpan!";
    echo "<meta http-equiv='refresh' content='2;url=?hal=pegawai'>";
  }
  else {
    echo 'Tidak Dapat Menyimpan Data!<br/>';
    echo mysqli_error($con);
  }
<?php

require_once 'koneksi.php';

// Ini function buat menampilkan data
if(function_exists($_GET['function'])){
    $_GET['function']();
}

// Menampilkan datanya

function tampilData(){
    global $koneksi;

    $masuk = mysqli_query($koneksi, "SELECT * FROM mahasiswi");
    while($data = mysqli_fetch_object($masuk)){
        $mahasiswi[] = $data;
    }

    $respon = array(
        'status' => 'true',
        'pesan' => 'Sukses untuk menampilkan data',
        'mahasiswi' => $mahasiswi
    );

    header('Content-type: application/json');
    print json_encode($respon);

    // json encode buat ubah bentuk array jadi json
}


// Untuk menambahkan Data menggunakan method post

function tambahMhs(){
    global $koneksi;

    $isi = array(
        'nama_mhs' => '',
        'alamat' => '',
        'no_telp' => ''
    );

    // Buat komper dua atau lebih key di dalam array
    $cek = count(array_intersect_key($_POST, $isi));

    if($cek == count($isi)){

        $nama_mhs = $_POST['nama_mhs'];
        $alamat = $_POST['alamat'];
        $no_telp = $_POST['no_telp'];

        $hasil = mysqli_query($koneksi, "INSERT INTO mahasiswi values('', '$nama_mhs', '$alamat', '$no_telp')");

        if($hasil){
            return pesan(1, "Berhasil menambahkan data $nama_mhs");
        }else{
            return pesan(2, "Gagal menambahkan data");
        }
    }else{
        return pesan(2, "Gagal juga");
    }
}

function pesan($status, $pesan){
    $respon = array(
        'status' => $status,
        'pesan' => $pesan
    );

    header('Content-type: application/json');
    print json_encode($respon);
}

// Mengedit data pada API php native

function editMhs(){
    global $koneksi;

    if(!empty($_GET['id'])){
        $id = $_GET['id'];
    }

    $isi = array(
        'nama_mhs' => "",
        'alamat' => "",
        'no_telp' => ""
    );

    $cek = count(array_intersect_key($_POST, $isi));

    if($cek == count($isi)){
        // Ambil semua isi dari form yang akan dimasukkan ke database

        $nama_mhs = $_POST['nama_mhs'];
        $alamat = $_POST['alamat'];
        $no_telp = $_POST['no_telp'];

        $hasil = mysqli_query($koneksi, "UPDATE mahasiswi set id='$id', nama_mhs='$nama_mhs', alamat='$alamat', no_telp='$no_telp' where id='$id'");

        if($hasil){
            return message(1, "Update data $nama_mhs berhasil");
        }else{
            return message(2, "Gagal untuk update data");
        }
    }else{
        return message(2, "Gagal uy");
    }
}

function message($status, $message){
    $respon = array(
        'status' => $status,
        'message' => $message
    );

    
    header('Content-type: application/json');
    print json_encode($respon);
}

// Menghapus Data
function hapusMhs(){
    global $koneksi;

    if(!empty($_GET['id'])){
        $id = $_GET['id'];
    }

    $hasil = mysqli_query($koneksi, "DELETE FROM mahasiswi where id='$id'");

    if($hasil){
        return data(1, "Berhasil hapus data");
    }else{
        return data(2, "Gagal hapus");
    }
}

function data($status, $data){
    $respon = array(
        'status' => $status,
        'data' => $data
    );

    
    header('Content-type: application/json');
    print json_encode($respon);
}

// Menampilkan Detail Data

function detailMhs(){
    global $koneksi;

    if(!empty($_GET['id'])){
        $id = $_GET['id'];
    }

    $hasil = $koneksi->query("SELECT * FROM mahasiswi where id='$id'");

    while($data = mysqli_fetch_object($hasil)){
        $detail[] = $data;
    }

    if($detail){
        $respon = array(
            'status' => true,
            'pesan' => "Detail data telah muncul",
            'user' => $detail
        );
    }else{
        return data(0, "Datanya ngak muncul");
    }

    header('Content-type: application/json');
    print json_encode($respon);

}

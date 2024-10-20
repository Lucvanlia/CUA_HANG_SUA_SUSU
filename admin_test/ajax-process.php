<?php
include"ketnoi/conndb.php";

if (isset($_POST['id_hd']) && isset($_POST['trang_thai'])) {
    $id_hd = (int)$_POST['id_hd'];
    $trang_thai = (int)$_POST['trang_thai'];

    $sql = "UPDATE hoadon SET TrangThai = $trang_thai WHERE id_hd = $id_hd";
    if (mysqli_query($link, $sql)) {
        echo 'success';
    } else {
        echo 'error';
    }
}
?>

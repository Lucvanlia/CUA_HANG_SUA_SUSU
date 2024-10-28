<?php
include "admin_test/ketnoi/conndb.php";
$targetDir = "uploads/"; // Thư mục lưu ảnh
foreach ($_FILES['file']['name'] as $key => $name) {
    $targetFilePath = $targetDir . basename($name);
    if (move_uploaded_file($_FILES['file']['tmp_name'][$key], $targetFilePath)) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false]);
    }
}

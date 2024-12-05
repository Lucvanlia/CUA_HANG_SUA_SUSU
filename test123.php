<?php
include "admin_test/ketnoi/conndb.php";

$product_id = 132; // ID sản phẩm cần chỉnh sửa
$product_description = ''; // Biến lưu nội dung mô tả sản phẩm
$msg = '';

if (isset($_REQUEST['submit'])) {
    // Lấy nội dung chỉnh sửa từ form
    $content = mysqli_real_escape_string($link, $_REQUEST['content']);
    
    // Cập nhật nội dung vào cơ sở dữ liệu
    $update_query = mysqli_query($link, "UPDATE sanpham SET Mota_sp = '$content' WHERE id_sp = $product_id");
    
    if ($update_query) {
        $msg = "Product description updated successfully!";
    } else {
        $msg = "Error: " . mysqli_error($link);
    }
} else {
    // Truy vấn để lấy mô tả sản phẩm từ cơ sở dữ liệu
    $query = mysqli_query($link, "SELECT Mota_sp FROM sanpham WHERE id_sp = $product_id");
    if ($row = mysqli_fetch_assoc($query)) {
        $product_description = $row['Mota_sp']; // Lấy mô tả
    } else {
        $msg = "Product not found!";
    }
}
?>
<html>
<head>
    <title>Edit Product Description</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
</head>
<style>
.box {
    width: 100%;
    max-width: 600px;
    background-color: #f9f9f9;
    border: 1px solid #ccc;
    border-radius: 5px;
    padding: 16px;
    margin: 0 auto;
}
.ck-editor__editable[role="textbox"] {
    min-height: 300px;
}
.error {
    color: red;
}
</style>
<body>
<div class="container">
    <h3 align="center">Edit Product Description</h3>
    <br>
    <div class="box">
        <form method="post">
            <div class="form-group">
                <!-- Textarea sẽ chứa mô tả sản phẩm -->
                <textarea id="content" name="content" class="form-control"><?php echo htmlspecialchars($product_description); ?></textarea>
            </div>
            <div class="form-group">
                <input type="submit" name="submit" value="Update" class="btn btn-primary">
            </div>
        </form>
        <div class="error"><?php if (!empty($msg)) { echo $msg; } ?></div>
    </div>
</div>

<script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
<script>
    // Khởi tạo CKEditor
    ClassicEditor
        .create(document.querySelector('#content'), {
            ckfinder: {
                uploadUrl: 'fileupload.php'
            }
        })
        .then(editor => {
            console.log('CKEditor is ready.');
        })
        .catch(error => {
            console.error(error);
        });
</script>
</body>
</html>

<?php
include('ketnoi/conndb.php');

// Kiểm tra xem có ID sản phẩm trong yêu cầu GET không
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$result = mysqli_query($link, "SELECT * FROM dmsp WHERE id_sp = $id");

if ($result && mysqli_num_rows($result) > 0) {
    $product = mysqli_fetch_array($result);
} else {
    echo "Sản phẩm không tồn tại!";
    exit;
}
// Chuyển đổi JSON hình chi tiết thành mảng
// Nếu dữ liệu không phải JSON, chuyển nó thành mảng từ chuỗi phân tách dấu phẩy
if (json_decode($product['Hinh_ChiTiet'], true) === null) {
    $hinhChiTiet = explode(',', $product['Hinh_ChiTiet']);  // Tách chuỗi thành mảng
} else {
    $hinhChiTiet = json_decode($product['Hinh_ChiTiet'], true);  // Nếu là JSON hợp lệ
}

var_dump($hinhChiTiet);  // Kiểm tra kết quả

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết sản phẩm</title>
</head>

<body>
    <div class="card p-4">
        <h4>Chi tiết sản phẩm</h4>

        <div class="form-group">
            <label for="Tensp">Tên sản phẩm:</label>
            <input type="text" class="form-control" id="Tensp" name="Tensp"
                value="<?php echo isset($product['Tensp']) ? htmlspecialchars($product['Tensp']) : ''; ?>" disabled>
        </div>

        <div class="form-group">
            <label for="HinhAnh_ChiTiet">Hình ảnh chi tiết:</label>
            <div id="hinhChiTietList">
                <?php foreach ($hinhChiTiet as $hinh): ?>
                    <div class="detail-image-item" data-image="<?php echo $hinh; ?>">
                        <img src="modul/uploads/<?php echo $hinh; ?>" alt="Hình chi tiết" style="width: 100px;">
                        <button type="button" class="btn btn-danger btn-sm delete-image" data-image="<?php echo $hinh; ?>">Xóa</button>
                    </div>
                <?php endforeach; ?>
            </div>
            <div id="dropzone" class="dropzone"></div>
            <input type="hidden" name="HinhAnh_ChiTiet" id="HinhAnh_ChiTiet" value='<?php echo json_encode($hinhChiTiet); ?>'>
        </div>

    </div>
</body>

</html>
<style>
    #hinhChiTietList {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        /* Xếp các cột động */
        gap: 10px;
        /* Khoảng cách giữa các hình ảnh */
    }

    .detail-image-item {
        text-align: center;
        /* Căn giữa ảnh và nút */
    }

    /* Ẩn nút xóa mặc định */
    .detail-image-item {
        position: relative;
        display: inline-block;
    }

    .delete-image {
        display: none;
        /* Ẩn nút xóa */
        position: absolute;
        top: 5px;
        right: 5px;
        z-index: 10;
    }

    /* Hiển thị nút xóa khi hover vào hình ảnh */
    .detail-image-item:hover .delete-image {
        display: block;
        /* Hiển thị nút xóa khi hover */
    }
</style>

<script>
    $(document).ready(function() {
        // Xử lý xóa ảnh chi tiết
        $(document).on("click", ".delete-image", function() {
            var imageName = $(this).data("image");
            var id_sp = $("input[name='id_sp']").val();
            var imageItem = $(this).closest(".detail-image-item");

            $.ajax({
                url: "", // Gửi đến chính trang (chitiet.php)
                type: "POST",
                data: {
                    action: 'delete_image',
                    image: imageName,
                    id_sp: id_sp
                },
                success: function(response) {
                    console.log(response); // Hiển thị thông tin phản hồi
                    var result = JSON.parse(response); // Parse JSON nếu cần
                    if (result.status === 'success') {
                        imageItem.remove(); // Xóa ảnh khỏi giao diện
                        alert("Hình ảnh đã được xóa thành công!");
                    } else {
                        alert("Lỗi khi xóa hình ảnh: " + result.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText); // Hiển thị lỗi chi tiết từ server
                    alert("Đã xảy ra lỗi trong quá trình xóa.");
                }
            });

        });
    });
</script>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'delete_image') {
    $imageName = mysqli_real_escape_string($link, $_POST['image']);
    $id_sp = (int)$_POST['id_sp'];

    // Lấy danh sách hình ảnh chi tiết từ database
    $result = mysqli_query($link, "SELECT Hinh_ChiTiet FROM dmsp WHERE id_sp = $id_sp");
    if (!$result) {
        echo json_encode(['status' => 'error', 'message' => 'Lỗi khi truy vấn cơ sở dữ liệu']);
        exit;
    }

    $product = mysqli_fetch_assoc($result);
    $Hinh_ChiTiet = json_decode($product['Hinh_ChiTiet'], true) ?? [];

    // Kiểm tra xem hình ảnh có tồn tại trong mảng không
    if (!in_array($imageName, $Hinh_ChiTiet)) {
        echo json_encode(['status' => 'error', 'message' => 'Hình ảnh không tồn tại']);
        exit;
    }

    // Xóa tên ảnh khỏi danh sách mảng
    $Hinh_ChiTiet = array_filter($Hinh_ChiTiet, function ($img) use ($imageName) {
        return $img !== $imageName;
    });

    // Cập nhật lại dữ liệu mảng hình ảnh vào cơ sở dữ liệu
    $hinh_chi_tiet_str = json_encode(array_values($Hinh_ChiTiet));
    $update_sql = "UPDATE dmsp SET Hinh_ChiTiet = '$hinh_chi_tiet_str' WHERE id_sp = $id_sp";
    if (!mysqli_query($link, $update_sql)) {
        echo json_encode(['status' => 'error', 'message' => 'Lỗi khi cập nhật cơ sở dữ liệu']);
        exit;
    }

    // Xóa file ảnh vật lý
    $filePath = "modul/uploads/$imageName";
    if (file_exists($filePath)) {
        if (!unlink($filePath)) {
            echo json_encode(['status' => 'error', 'message' => 'Lỗi khi xóa file ảnh']);
            exit;
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'File ảnh không tồn tại']);
        exit;
    }

    echo json_encode(['status' => 'success', 'message' => 'Hình ảnh đã được xóa']);
    exit;
}
?>
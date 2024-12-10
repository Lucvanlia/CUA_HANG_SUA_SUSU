<?php 
include "../../ketnoi/conndb.php"; // Kết nối cơ sở dữ liệu

// Xử lý việc thêm bài viết khi form được submit
if (isset($_POST['submit'])) {
    // Lấy dữ liệu từ form
    $title = mysqli_real_escape_string($link, $_POST['title']);
    $loaitintuc = mysqli_real_escape_string($link, $_POST['loaitintuc']);
    $noidung = mysqli_real_escape_string($link, $_POST['noidung']);

    // Lấy mảng sản phẩm từ form và chuyển thành chuỗi ngăn cách bởi dấu phẩy
    $sanpham = isset($_POST['sanpham']) ? $_POST['sanpham'] : [];  // Nếu không có sản phẩm, mặc định là mảng rỗng
    $tag_sp = implode(',', $sanpham);  // Chuyển mảng thành chuỗi ngăn cách bởi dấu phẩy

    // Xử lý upload hình ảnh
    $target_dir = "../../uploads/";  // Thư mục lưu hình ảnh
    $imageFileType = strtolower(pathinfo($_FILES["hinhnen"]["name"], PATHINFO_EXTENSION));
    $new_image_name = uniqid('image_', true) . '.' . $imageFileType;  // Tạo tên ngẫu nhiên cho hình ảnh
    $target_file = $target_dir . $new_image_name;  // Đường dẫn đầy đủ đến tệp hình ảnh    

    // Kiểm tra nếu tệp là hình ảnh hợp lệ
    $check = getimagesize($_FILES["hinhnen"]["tmp_name"]);
    if ($check !== false) {
        // Kiểm tra nếu hình ảnh đã tồn tại
        if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
        } else {
            // Kiểm tra kích thước tệp
            if ($_FILES["hinhnen"]["size"] > 20000000) {
                echo "Sorry, your file is too large.";
            } else {
                // Kiểm tra loại tệp hình ảnh
                if ($imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "png" && $imageFileType != "gif") {
                    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                } else {
                    // Di chuyển tệp hình ảnh vào thư mục uploads
                    if (move_uploaded_file($_FILES["hinhnen"]["tmp_name"], $target_file)) {
                        // Lưu thông tin bài viết vào cơ sở dữ liệu
                        $sql = "INSERT INTO TinTuc (id_ltt, title, tag_sp, NoiDung, Hinh_Nen,HoatDong) 
                                VALUES ('$loaitintuc', '$title', '$tag_sp', '$noidung', '$new_image_name',0)";
                        if (mysqli_query($link, $sql)) {
                            echo "Bài viết đã được thêm thành công!";
                            header("location:http://localhost/doan_php/admin_test/index.php?action=quanlybaiviet&query=them");
                        } else {
                            echo "Error: " . $sql . "<br>" . mysqli_error($link);
                        }
                    } else {
                        echo "Sorry, there was an error uploading your file.";
                    }
                }
            }
        }
    } else {
        echo "File is not an image.";
    }
}

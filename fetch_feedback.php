<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<style>
    .feedback-item {
    border-radius: 8px; /* Bo tròn các góc của khung */
}

.avatar {
    font-size: 20px; /* Kích thước chữ trong avatar */
    font-weight: bold; /* Làm cho chữ đậm */
    text-align: center; /* Canh giữa chữ trong avatar */
}
.star-rating {
    margin: 5px 0;
}

.star-rating i {
    font-size: 20px; /* Kích thước biểu tượng sao */
}
</style>
<?php
include "admin_test/ketnoi/conndb.php";
date_default_timezone_set('Asia/Ho_Chi_Minh');
$sql = "SELECT f.*, u.Ten_KH as Ten_KH ,u.profile_pic as hinh
        FROM product_feedback f JOIN khachhang u ON f.id_kh = u.id_kh JOIN dmsp ON f.id_sp = dmsp.id_sp
        ORDER BY f.created_at DESC";
$result = mysqli_query($link, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Chuyển đổi timestamp thành ngày tháng tiếng Việt
        $timestamp = $row['created_at'];
        $formattedDate = date("H:i:s \\n\g\à\y d \\t\h\á\\n\g m \\n\ă\m Y", $timestamp);
        
        // Số sao từ cơ sở dữ liệu
        $starRating = intval($row['rating']); // Giả sử bạn có một cột 'rating' trong bảng feedback

        // Hiển thị thông tin người dùng, đánh giá và ngày tháng
        echo '<div class="feedback-item border p-3 mb-3 position-relative" style="background-color: #7fad39; color: white">';
        echo '<div class="d-flex align-items-start">';

        // Hiển thị hình đại diện
        echo '<div class="avatar me-3" style="width: 75px; height: 75px; border-radius: 50%; background-color: #7fad39; color: white;display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">';
        echo '<img src = "' . $row['hinh'] . '"  class="avatar me-3" style="width: 75px; height: 75px; border-radius: 50%; background-color: #7fad39; color: white;display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">'; // Hiển thị ký tự đầu tiên của tên khách hàng
        echo '</div>';

        // Thông tin tên và ngày tháng
        echo '<div class="flex-grow-1 ">';
        echo '<h6 class="d-flex justify-content-between">';
        echo '<span style="color: white" >' . $row['Ten_KH'] . '</span>';
        echo '<span  style="color: white">' . $formattedDate . '</span>';
        echo '</h6>';

        // Hiển thị đánh giá sao
        echo '<div class="star-rating" style="color: white>';
        for ($i = 0; $i <= 5; $i++) {
            if ($i <= $starRating) {
                echo '<i class="fas fa-star text-warning"></i>'; // Sao vàng cho đánh giá
            } else {
                echo '<i class="far fa-star text-infor "></i>'; // Sao xám cho không đánh giá
            }
        }
        echo '</div>';

        // Hiển thị nội dung bình luận
        echo '<p style=" color: white">' . $row['comment'] . '</p>';
        
        // Hiển thị hình ảnh (nếu có)
        $images = explode(',', $row['images']); // Tách chuỗi thành mảng
        if (!empty($row['images'])) { // Kiểm tra xem chuỗi hình ảnh có rỗng không
            echo '<div class="feedback-images d-flex flex-wrap">';
            foreach ($images as $image) {
                if (!empty($image)) { // Kiểm tra xem từng hình ảnh có rỗng không
                    echo '<a data-fancybox="gallery" href="' . htmlspecialchars($image) . '">
                            <img src="' . htmlspecialchars($image) . '" alt="feedback image" class="img-thumbnail" style="width:100px; height:100px; object-fit:cover; margin-right: 5px; margin-bottom: 5px;">
                          </a>';
                }
            }
            echo '</div>';
        } else {
            // Khi không có hình ảnh, hiển thị thông điệp
            echo '<div class="feedback-images d-flex flex-wrap"></div>';
        }

        echo '</div>'; // Kết thúc flex-grow-1
        echo '</div>'; // Kết thúc d-flex align-items-start
        echo '</div>'; // Kết thúc feedback-item
    }
} else {
    echo 'Chưa có đánh giá nào.';
}
?>

<?php
include "../../ketnoi/conndb.php";

$data = isset($_POST) ? $_POST : [];
$action = isset($data['action']) ? $data['action'] : '';
switch ($action) {
    case 'search':
        // Search for products based on the keyword
        $keyword = mysqli_real_escape_string($link, $data['keyword']);
        $query = "SELECT id_sp, Ten_sp, Hinh_Nen FROM SanPham WHERE Ten_sp LIKE '%$keyword%'";
        $result = mysqli_query($link, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<div class="col-md-6">
                    <div class="card mb-5">
                        <img src="../../uploads/sanpham/' . htmlspecialchars($row['Hinh_Nen']) . '" class="card-img-top" alt="Hình sản phẩm">
                        <div class="card-body">
                            <h6 class="card-title">' . htmlspecialchars($row['Ten_sp']) . '</h6>
                            <button type="button" class="btn btn-primary btn-sm add-product" data-id="' . $row['id_sp'] . '">Thêm</button>
                        </div>
                    </div>
                </div>';
        }
        break;

    case 'add':
        // Add related product
        $id_tt = intval($data['id_tt']);
        $id_sp = intval($data['id_sp']);

        $query = "SELECT tag_sp FROM TinTuc WHERE id_tt = $id_tt";
        $result = mysqli_query($link, $query);
        if ($result && $row = mysqli_fetch_assoc($result)) {
            $currentTags = explode(',', $row['tag_sp']);
            if (!in_array($id_sp, $currentTags)) {
                $currentTags[] = $id_sp;
                $updatedTags = implode(',', $currentTags);
                $updateQuery = "UPDATE TinTuc SET tag_sp = '$updatedTags' WHERE id_tt = $id_tt";
                mysqli_query($link, $updateQuery);
            }
        }
        break;

    case 'remove':
        // Remove related product
        $id_tt = intval($data['id_tt']);
        $id_sp = intval($data['id_sp']);

        $query = "SELECT tag_sp FROM TinTuc WHERE id_tt = $id_tt";
        $result = mysqli_query($link, $query);
        if ($result && $row = mysqli_fetch_assoc($result)) {
            $currentTags = explode(',', $row['tag_sp']);
            if (($key = array_search($id_sp, $currentTags)) !== false) {
                unset($currentTags[$key]);
                $updatedTags = implode(',', $currentTags);
                $updateQuery = "UPDATE TinTuc SET tag_sp = '$updatedTags' WHERE id_tt = $id_tt";
                mysqli_query($link, $updateQuery);
            }
        }
        break;

    case 'update':
        // Update post content
        $id_tt = intval($data['id_tt']);
        $content = mysqli_real_escape_string($link, $data['content']);
        $loaitintuc = intval($data['loaitintuc']);
        
        $updateQuery = "UPDATE TinTuc SET NoiDung = '$content', id_ltt = $loaitintuc WHERE id_tt = $id_tt";
        mysqli_query($link, $updateQuery);

        // Handle image upload if exists
        if (isset($_FILES['hinhnen']) && $_FILES['hinhnen']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['hinhnen']['tmp_name'];
            $fileName = time() . '_' . $_FILES['hinhnen']['name'];
            $uploadPath = '../../uploads/' . $fileName;
            if (move_uploaded_file($fileTmpPath, $uploadPath)) {
                $imageUpdateQuery = "UPDATE TinTuc SET Hinh_Nen = '$fileName' WHERE id_tt = $id_tt";
                mysqli_query($link, $imageUpdateQuery);
            }
        }
        break;

    default:
        echo "Invalid action!";
}
?>

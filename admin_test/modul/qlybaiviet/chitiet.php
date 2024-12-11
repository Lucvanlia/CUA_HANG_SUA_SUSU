<?php
include "../../ketnoi/conndb.php";

$id_tt = isset($_GET['id']) ? intval($_GET['id']) : 0;
$query = "SELECT tt.*, ltt.Ten_ltt FROM TinTuc tt 
          LEFT JOIN LoaiTinTuc ltt ON tt.id_ltt = ltt.id_ltt 
          WHERE tt.id_tt = $id_tt";
$result = mysqli_query($link, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $baiviet = mysqli_fetch_assoc($result);
    $tag_sp = $baiviet['tag_sp'];
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Chi Tiết Bài Viết</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    </head>

    <body>
        <div class="container mt-5">
            <form id="update-form">
                <div class="form-group">
                    <label for="title">Tiêu đề bài viết</label>
                    <input type="text" name="title" class="form-control" value="  <?php echo htmlspecialchars($baiviet['Title']); ?>">
                </div>
                <h4 class="mt-4">Quản lý sản phẩm liên quan</h4>
            <div class="row">
                <!-- Sản phẩm liên quan -->
                <div class="col-md-6">
                    <h5>Sản phẩm hiện tại</h5>
                    <div class="row">
                        <?php
                        if (!empty($tag_sp)) {
                            $productQuery = "SELECT id_sp, Ten_sp FROM SanPham WHERE id_sp IN ($tag_sp)";
                            $productResult = mysqli_query($link, $productQuery);
                            while ($product = mysqli_fetch_assoc($productResult)) {
                        ?>
                                <div class="col-md-12" id="product-<?php echo $product['id_sp']; ?>">
                                    <div class="card mb-3">
                                        <div class="card-body d-flex justify-content-between align-items-center">
                                            <h6 class="card-title mb-0"><?php echo htmlspecialchars($product['Ten_sp']); ?></h6>
                                            <button type="button" class="btn btn-danger btn-sm remove-product" data-id="<?php echo $product['id_sp']; ?>">Xóa</button>
                                        </div>
                                    </div>
                                </div>
                        <?php }
                        } else {
                            echo '<p>Không có sản phẩm liên quan.</p>';
                        } ?>
                    </div>
                </div>

                <!-- Thêm sản phẩm liên quan -->
                <div class="col-md-6">
                    <h5>Thêm sản phẩm</h5>
                    <div class="form-group">
                        <input type="text" id="search-product" class="form-control mb-3" placeholder="Nhập tên sản phẩm">
                    </div>
                    <div id="search-result" class="row"></div>
                </div>
            </div>
                <div class="form-group">
                    <label for="noidung">Nội dung</label>
                    <textarea rows="10" name="noidung" id="content" class="form-control">
                <?php echo htmlspecialchars($baiviet['NoiDung']); ?>
            </textarea>
                </div>
                <button type="button" class="btn btn-primary mt-3" id="save-content">Lưu nội dung</button>
            </form>

        


            <script>
                // Tìm kiếm sản phẩm
                $(document).on('keyup', '#search-product', function() {
                    let keyword = $(this).val();
                    $.ajax({
                        url: '../../modul/qlybaiviet/capnhat.php',
                        method: 'POST',
                        data: {
                            action: 'search',
                            keyword: keyword
                        },
                        success: function(data) {
                            $('#search-result').html(data);
                        }
                    });
                });

                // Thêm sản phẩm liên quan
                $(document).on('click', '.add-product', function() {
                    let productId = $(this).data('id');
                    let postId = <?php echo $id_tt; ?>;
                    $.ajax({
                        url: '../../modul/qlybaiviet/capnhat.php',
                        method: 'POST',
                        data: {
                            action: 'add',
                            id_tt: postId,
                            id_sp: productId
                        },
                        success: function() {
                            location.reload();
                        }
                    });
                });

                // Xóa sản phẩm liên quan
                $(document).on('click', '.remove-product', function() {
                    let productId = $(this).data('id');
                    let postId = <?php echo $id_tt; ?>;
                    $.ajax({
                        url: '../../modul/qlybaiviet/capnhat.php',
                        method: 'POST',
                        data: {
                            action: 'remove',
                            id_tt: postId,
                            id_sp: productId
                        },
                        success: function() {
                            $('#product-' + productId).remove();
                        }
                    });
                });

                // Lưu nội dung bài viết
                $(document).on('click', '#save-content', function() {
                    let content = $('#content').val();
                    let postId = <?php echo $id_tt; ?>;
                    $.ajax({
                        url: '../../modul/qlybaiviet/capnhat.php',
                        method: 'POST',
                        data: {
                            action: 'update-content',
                            id_tt: postId,
                            content: content
                        },
                        success: function() {
                            alert('Nội dung đã được lưu!');
                        }
                    });
                });
            </script>

    </body>

    </html>
<?php } else {
    echo '<div class="alert alert-danger">Bài viết không tồn tại.</div>';
} ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
<script>
    ClassicEditor.create(document.querySelector('#content')).catch(error => console.error(error));
</script>
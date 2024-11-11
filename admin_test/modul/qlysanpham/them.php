<?php
include('../../ketnoi/conndb.php');
//=======================SQL===================
$sql_xuatxu = "SELECT * FROM xuatxu";
$sql_hang = "SELECT * FROM hang ";
$sql_loai = "SELECT * FROM loai";
//===================kq=====================
$result_xuatxu = mysqli_query($link, $sql_xuatxu);
$result_hang = mysqli_query($link, $sql_hang);
$result_loai = mysqli_query($link, $sql_loai);
//====================================================

?>
<div class="container-fluid mt-5  pr-5 pl-5 col-md-12">

    <form id="add-product" style="padding-right: -20px;">

        <div class="form-group">
            <label for="ten_sp">Tên Sản Phẩm:</label>
            <input type="text" id="ten_sp" name="ten_sp" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="xuatxu">Xuất Xứ:</label>
            <select id="xuatxu" name="xuatxu" class="form-control" required>
                <?php while ($row = mysqli_fetch_assoc($result_xuatxu)) : ?>
                    <option value="<?php echo $row['id_xuatxu']; ?>"><?php echo $row['tenxuatxu']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="hang">Hãng:</label>
            <select id="hang" name="hang" class="form-control" required>
                <?php while ($row = mysqli_fetch_assoc($result_hang)) : ?>
                    <option value="<?php echo $row['id_hang']; ?>"><?php echo $row['tenhang']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="loai">Loại:</label>
            <select id="loai" name="loai" class="form-control" required>
                <?php while ($row = mysqli_fetch_assoc($result_loai)) : ?>
                    <option value="<?php echo $row['id_loai']; ?>"><?php echo $row['tenloai']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="soluong">Số Lượng:</label>
            <input type="text" id="soluong" name="soluong" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="gia">Giá:</label>
            <input type="text" id="gia" name="gia" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="mo_ta">Mô Tả:</label>
            <textarea id="mo_ta" name="mo_ta" class="form-control"></textarea>
        </div>

        <div class="form-group">
            <label for="image">Tải Lên Hình Ảnh:</label>
            <input type="file" id="image" name="hinh_nen" class="form-control" accept="image/*" required>
            <img id="preview-image" src="" class="img-fluid" alt="Preview" style="max-width: 100px; margin-top: 10px; display: none;" />
        </div>

        <div class="row py-2">
            <div class="col-lg-12 col-md-6 col-sm-12">
                <textarea id="rating-description" class="form-control " placeholder="Nhập mô tả đánh giá"></textarea>
            </div>
        </div>
        <div class="row py-2">
            <div class="col-lg-6 col-md-6 col-sm-12">
                <button type="button" class="site-btn btn btn-success btn-sm" id="submit-product">Gửi đánh giá</button>
            </div>
        </div>
    </form>
    <!-- Dropzone cho phần tải lên nhiều ảnh -->
    <div class="row py-2">
        <div class="col-lg-12 col-md-12">
            <form action="upload_images.php" class="dropzone" id="dropzoneArea"></form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
<link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css"
/>
<script>
    Dropzone.autoDiscover = false;

    $(document).ready(function() {
        $(document).ready(function() {
            $('#gia').on('input', function(e) {
                // Lấy giá trị hiện tại của ô input và loại bỏ dấu chấm
                let value = $(this).val().replace(/\./g, "");

                // Kiểm tra nếu không phải là số hoặc là số âm, xóa ký tự không hợp lệ
                if (!/^\d+$/.test(value)) {
                    value = value.replace(/\D/g, ""); // Loại bỏ các ký tự không phải số
                }

                // Định dạng lại với dấu chấm hàng nghìn
                value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");

                // Gán lại giá trị đã định dạng vào ô input
                $(this).val(value);
            });

            // Ngăn người dùng nhập ký tự không phải số và dấu âm
            $('#gia').on('keypress', function(e) {
                // Ngăn nhập ký tự không phải số
                if (!/[0-9]/.test(String.fromCharCode(e.which))) {
                    e.preventDefault();
                }
            });
        });


        $(document).ready(function() {
            $('#soluong').on('input', function(e) {
                // Lấy giá trị hiện tại của ô input và loại bỏ dấu chấm
                let value = $(this).val().replace(/\./g, "");

                // Kiểm tra nếu không phải là số hoặc là số âm, xóa ký tự không hợp lệ
                if (!/^\d+$/.test(value)) {
                    value = value.replace(/\D/g, ""); // Loại bỏ các ký tự không phải số
                }

                // Định dạng lại với dấu chấm hàng nghìn
                value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");

                // Gán lại giá trị đã định dạng vào ô input
                $(this).val(value);
            });

            // Ngăn người dùng nhập ký tự không phải số và dấu âm
            $('#gia').on('keypress', function(e) {
                // Ngăn nhập ký tự không phải số
                if (!/[0-9]/.test(String.fromCharCode(e.which))) {
                    e.preventDefault();
                }
            });
        });

        // Hiển thị ảnh đại diện trước khi tải lên
        $('#image').change(function(event) {
            const input = event.target;
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = (e) => $('#preview-image').attr('src', e.target.result).show();
                reader.readAsDataURL(input.files[0]);
            }
        });

        // Khởi tạo Dropzone
        if (Dropzone.instances.length > 0) Dropzone.instances.forEach(dz => dz.destroy());

        const myDropzone = new Dropzone("#dropzoneArea", {
            url: "comment-process.php",
            paramName: "hinh_chi_tiet[]", // Đặt tên cho tệp tải lên là "hinh_chi_tiet"
            autoProcessQueue: false,
            uploadMultiple: true,
            maxFiles: 10,
            acceptedFiles: "image/*",
            addRemoveLinks: true,
            parallelUploads: 10,
            init: function() {
                const dropzone = this;

                // Sự kiện khi thêm file
                dropzone.on("addedfile", (file) => {
                    console.log("File added: ", file);
                });

                // Bắt sự kiện click nút gửi
                $('#submit-product').on('click', function(e) {
                    e.preventDefault();

                    // Sử dụng FormData để lấy tất cả dữ liệu trong form
                    const formData = new FormData(document.getElementById("add-product"));

                    // Thêm các tệp từ Dropzone vào FormData
                    dropzone.getAcceptedFiles().forEach(file => {
                        formData.append("hinh_chi_tiet[]", file); // Đảm bảo thêm [] để nhận nhiều tệp
                    });

                    // Gửi dữ liệu đến server
                    fetch('comment-process.php', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // alert('Đánh giá đã được gửi thành công!');
                                Fancybox.show([{
                                    src: `<div style="padding: 20px; text-align: center;">
                                    <h3>Thông báo</h3>
                                    <p><strong>Sản phẩm đã thêm thành công</strong></p>
                                    <button onclick="Fancybox.close();" class="btn btn-primary mt-2">Đóng</button>
                                  </div>`,
                                    type: "html",
                                }], );
                                document.getElementById("add-product").reset();
                                dropzone.removeAllFiles(); // Xóa tất cả các tệp trong Dropzone
                                document.getElementById("preview-image").style.display = "none";
                            } else {
                                alert('Lỗi: ' + data.error);
                            }
                        })
                        .catch(error => alert('Lỗi server: ' + error));
                });

                // Xử lý thành công tải lên nhiều tệp
                dropzone.on("successmultiple", (files, response) => {
                    const data = JSON.parse(response);
                    if (data.success) {
                        // alert('Ảnh và đánh giá đã được lưu thành công!');
                        Fancybox.show([{
                            src: `<div style="padding: 20px; text-align: center;">
                                    <h3>Thông báo</h3>
                                    <p><strong>Ảnh và đánh giá đã được lưu thành công!</strong></p>
                                    <button onclick="Fancybox.close();" class="btn btn-primary mt-2">Đóng</button>
                                  </div>`,
                            type: "html",
                        }], );
                        document.getElementById("add-product").reset();
                        dropzone.removeAllFiles(); // Xóa tất cả các tệp trong Dropzone
                        document.getElementById("preview-image").style.display = "none";
                    } else {
                        alert('Lỗi khi tải ảnh: ' + data.error);
                    }
                });

                dropzone.on("errormultiple", () => alert('Lỗi khi tải ảnh!'));
            }
        });
    });
</script>
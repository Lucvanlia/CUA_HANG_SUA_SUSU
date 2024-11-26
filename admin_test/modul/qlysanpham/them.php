<!-- Fancybox JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0.30/dist/fancybox.css" />

<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0.30/dist/fancybox.umd.js"></script>
<div class="container mt-4">
    <h2>Thêm Sản Phẩm</h2>
    <form id="formSanPham" enctype="multipart/form-data">
        <!-- Tên sản phẩm -->
        <input type="hidden" name="action" value="add_product">
        <div class="mb-3">
            <label class="form-label">Tên Sản Phẩm</label>
            <input type="text" class="form-control" name="Ten_sp" placeholder="Nhập tên sản phẩm" required>
        </div>

        <!-- Danh mục, Xuất xứ, Nhà cung cấp -->
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Danh Mục</label>
                <select class="form-select" name="id_dm" required>
                    <option value="">Chọn danh mục</option>
                    <!-- Dữ liệu danh mục sẽ được load động -->
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Xuất Xứ</label>
                <select class="form-select" name="id_xx" required>
                    <option value="">Chọn xuất xứ</option>
                    <!-- Dữ liệu xuất xứ sẽ được load động -->
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Nhà Cung Cấp</label>
                <select class="form-select" name="id_ncc" required>
                    <option value="">Chọn nhà cung cấp</option>
                    <!-- Dữ liệu nhà cung cấp sẽ được load động -->
                </select>
            </div>
        </div>

        <!-- Kích thước size và giá -->
        <div class="mb-3 mt-4" id="sizeContainer">
            <label class="form-label">Kích Thước (Size)</label>
            <div id="sizeRows"></div>
            <button type="button" class="btn btn-secondary mt-2" id="addSizeButton">Thêm Dòng Size</button>
        </div>
        <!-- Mô tả sản phẩm -->
        <div class=" col-lg-12">
            <div class="main-container">
                <textarea id="editor" name="MoTa_sp">
            </textarea>
            </div>
        </div>


        <!-- Hình nền -->
        <div class="mb-3">
            <label class="form-label">Hình Nền</label>
            <input type="file" class="form-control" name="Hinh_Nen" accept="image/*" id="imageUpload" required>
            <img id="imagePreview" src="#" alt="Hình nền" style="display: none; margin-top: 10px; max-width: 200px;">
        </div>
        <div class="mb-3">
            <label class="form-label">Hình Chi Tiết</label>
            <div id="imagePreviewContainer">
                <!-- Khu vực hiển thị hình ảnh preview -->
            </div>
            <input type="file" id="files" name="files[]" multiple accept="image/*">
            <p id="fileCount">Đã chọn 0 tệp</p>

        </div>

        <!-- <div class="row py-2">
            <div class="col-lg-12 col-md-12">
                <form action="upload_images.php" class="dropzone" id="dropzoneArea"></form>
            </div>
        </div> -->
        <!-- Submit -->
        <button type="submit" class="btn btn-primary" id="btnAdd"> Thêm Sản Phẩm</button>
    </form>
    <!-- Dropzone cho phần tải lên nhiều ảnh -->
    <!-- <div class="row py-2">
        <div class="col-lg-12 col-md-12">
            <form action="upload_images.php" class="dropzone" id="dropzoneArea"></form>
        </div>
    </div> -->
</div>

<!-- A friendly reminder to run on a server, remove this during the integration. -->
<style>
    .image-preview {
        display: inline-block;
        position: relative;
        margin: 5px;
        border: 1px solid #ddd;
        border-radius: 4px;
        overflow: hidden;
        width: 100px;
        height: 100px;
    }

    .image-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .delete-btn {
        position: absolute;
        top: 5px;
        right: 5px;
        background-color: rgba(255, 0, 0, 0.7);
        color: white;
        border: none;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        cursor: pointer;
        font-size: 14px;
        line-height: 20px;
        text-align: center;
    }
</style>
<script>
    // Gửi form qua AJAX
    $(document).ready(function() {
        // Load dữ liệu danh mục, xuất xứ, nhà cung cấp
        function loadSelectData() {
            $.ajax({
                url: "ajax-process/sanpham.php",
                type: "POST",
                data: {
                    action: "load_select_data"
                },
                dataType: "json",
                success: function(data) {
                    if (data.status === "success") {
                        $('select[name="id_dm"]').html(data.danhmuc);
                        $('select[name="id_xx"]').html(data.xuatxu);
                        $('select[name="id_ncc"]').html(data.nhacungcap);
                    }
                },
            });
        }
        loadSelectData();

        // Thêm dòng size
        $("#addSizeButton").on("click", function() {
            const newRow = `
            <div class="row g-3 align-items-center mb-2 size-row">
                <div class="col-md-4">
                    <select class="form-select parent-dv" name="sizes[parent_dv][]" required>
                        <option value="">Chọn kích thước chính</option>
                        <!-- Dữ liệu kích thước chính được load từ server -->
                        <option value="1">Size Chính 1</option>
                        <option value="2">Size Chính 2</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select child-dv" name="sizes[child_dv][]" disabled required>
                        <option value="">Chọn kích thước con</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control gia-ban" name="sizes[GiaBan][]" placeholder="Giá bán" required>
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control SoLuong" name="sizes[SoLuong][]" placeholder="Số lượng" required>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-danger btn-sm removeSizeButton">Xóa</button>
                </div>
            </div>`;

            // Thêm dòng mới vào container
            $("#sizeRows").append(newRow);

            // Áp dụng định dạng số cho giá bán
            $(".gia-ban").on('input', function() {
                let value = this.value.replace(/\D/g, ''); // Chỉ giữ lại số
                value = Number(value).toLocaleString(); // Định dạng số với dấu phân tách nghìn
                this.value = value;
            });

            // Sau khi thêm dòng mới, bạn có thể tải các kích thước chính (nếu có)
            loadMainSizes(); // Nếu cần gọi thêm chức năng load các kích thước chính
        });

        // Áp dụng sự kiện xóa cho mỗi dòng
        $(document).on('click', '.removeSizeButton', function() {
            $(this).closest('.size-row').remove();
        });

        // Chặn người dùng nhập chữ vào trường gia-ban (chỉ cho phép số và ký tự hợp lệ)
        $(document).on('keydown', '.gia-ban', function(event) {
            // Chỉ cho phép nhập các phím: số, dấu phân cách hàng nghìn, và dấu xóa (backspace)
            const validKeys = [
                8, // Backspace
                9, // Tab
                13, // Enter
                27, // Escape
                37, // Arrow left
                39, // Arrow right
                46, // Delete
                48, 49, 50, 51, 52, 53, 54, 55, 56, 57, // Số từ 0 đến 9
                190, // Dấu phân cách thập phân
                188 // Dấu phân cách nghìn (dấu phẩy)
            ];

            if (!validKeys.includes(event.keyCode)) {
                event.preventDefault(); // Chặn các phím không hợp lệ
            }
        });

        // Xóa dòng size
        $(document).on("click", ".removeSizeButton", function() {
            $(this).closest(".size-row").remove();
        });

        // Xử lý hình nền preview
        $("#imageUpload").change(function() {
            const reader = new FileReader();
            reader.onload = function(e) {
                $("#imagePreview").attr("src", e.target.result).show();
            };
            reader.readAsDataURL(this.files[0]);
        });

        // Dropzone cho hình chi tiết
        const myDropzone = new Dropzone("#dropzone", {
            url: "ajax-process/sanpham.php?action=upload_images",
            maxFilesize: 2, // MB
            acceptedFiles: "image/*",
            addRemoveLinks: true,
        });

        // Gửi form
        $("#formSanPham").on("submit", function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            // Append Dropzone files vào formData
            myDropzone.files.forEach((file) => {
                formData.append("Hinh_ChiTiet[]", file);
            });

            $.ajax({
                url: "ajax-process/sanpham.php",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(response) {
                    if (response.status === "success") {
                        Fancybox.show([{
                            src: `
                        <div style="padding: 20px; text-align: center;">
                            <div style="font-size: 50px; color: green; margin-bottom: 15px;">
                                <img src="img/verified.gif" width="50" height="50">
                            </div>
                            <h3>Thông báo</h3>
                            <p>Trạng thái: <strong>${response.message}</strong></p>
                            <button onclick="Fancybox.close();" class="btn btn-primary mt-2">Đóng</button>
                        </div>`,
                            type: "html",
                        }]);
                        // Reset form và hình ảnh
                        $('#formSanPham')[0].reset(); // Reset form
                        $('#imagePreview').hide(); // Ẩn preview ảnh
                        $('#files').val(''); // Reset input file hình ảnh chi tiết
                        updatePreview(); // Cập nhật lại giao diện
                        // Nếu bạn có phần preview cho ảnh nền, reset ảnh nền (nếu có)
                        $('#Hinh_Nen').val(''); // Reset input file hình nền nếu có
                        myDropzone.removeAllFiles();
                    } else {
                        Fancybox.show([{
                            src: `
                        <div style="padding: 20px; text-align: center;">
                            <div style="font-size: 50px; color: red; margin-bottom: 15px;">
                                <img src="img/delivery.gif" width="50" height="50">
                            </div>
                            <h3>Lỗi</h3>
                            <p>${response.message}</p>
                            <button onclick="Fancybox.close();" class="btn btn-primary mt-2">Đóng</button>
                        </div>`,
                            type: "html",
                        }]);
                    }
                },
            });
        });
    }); // Tải danh sách kích thước chính
    function loadMainSizes() {
        $.ajax({
            url: 'ajax-process/sanpham.php', // URL xử lý
            type: 'POST',
            data: {
                action: 'load_main_sizes'
            }, // Gửi dữ liệu để lấy kích thước chính
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    // Lặp qua từng select kích thước chính và thêm các options
                    $(".parent-dv").each(function() {
                        const $parentSelect = $(this);
                        $parentSelect.empty(); // Xóa các options cũ
                        $parentSelect.append('<option value="">Chọn kích thước chính</option>'); // Thêm option mặc định
                        response.data.forEach(function(size) {
                            $parentSelect.append(`<option value="${size.id_dv}">${size.Ten_dv}</option>`);
                        });
                    });
                } else {
                    alert('Không thể tải danh sách kích thước chính');
                }
            },
            error: function() {
                alert('Lỗi khi tải dữ liệu kích thước chính');
            }
        });
    }

    // Khi chọn kích thước chính, tải các kích thước con
    $(document).on('change', '.parent-dv', function() {
        const parentId = $(this).val(); // Lấy ID của kích thước chính
        const $sizeRow = $(this).closest('.size-row'); // Tìm dòng kích thước

        if (parentId) {
            $.ajax({
                url: 'ajax-process/sanpham.php', // URL xử lý
                type: 'POST',
                data: {
                    action: 'load_sizes',
                    parent_id: parentId
                }, // Gửi ID kích thước chính để lấy con
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        const $childSelect = $sizeRow.find('.child-dv');
                        $childSelect.empty(); // Xóa các option cũ
                        $childSelect.append('<option value="">Chọn kích thước con</option>'); // Option mặc định

                        response.data.forEach(function(size) {
                            $childSelect.append(`<option value="${size.id_dv}">${size.Ten_dv}</option>`);
                        });

                        $childSelect.prop('disabled', false); // Bật dropdown kích thước con
                    } else {
                        alert('Không thể tải kích thước con');
                    }
                },
                error: function() {
                    alert('Lỗi khi tải dữ liệu kích thước con');
                }
            });
        } else {
            // Nếu không có kích thước chính, disable kích thước con
            $sizeRow.find('.child-dv').empty().prop('disabled', true).append('<option value="">Chọn kích thước con</option>');
        }
    });
    // Mảng lưu trữ các tệp đã chọn
    let selectedFiles = [];

    // Xử lý khi chọn file
    document.getElementById("files").addEventListener("change", function(event) {
        const files = event.target.files;

        // Thêm file mới vào mảng
        Array.from(files).forEach((file) => {
            if (!selectedFiles.some(f => f.name === file.name)) {
                selectedFiles.push(file); // Tránh thêm file trùng lặp
            }
        });

        // Cập nhật giao diện preview
        updatePreview();
    });

    // Hàm cập nhật giao diện preview
    function updatePreview() {
        const previewContainer = document.getElementById("imagePreviewContainer");
        const fileCount = document.getElementById("fileCount");

        // Xóa tất cả preview cũ
        previewContainer.innerHTML = "";

        // Hiển thị các hình ảnh trong mảng
        selectedFiles.forEach((file, index) => {
            if (file.type.startsWith("image/")) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    const previewDiv = document.createElement("div");
                    previewDiv.classList.add("image-preview");

                    // Tạo phần tử hình ảnh
                    const img = document.createElement("img");
                    img.src = e.target.result;
                    img.alt = file.name;

                    // Tạo nút xóa
                    const deleteBtn = document.createElement("button");
                    deleteBtn.classList.add("delete-btn");
                    deleteBtn.innerText = "x";

                    // Thêm sự kiện xóa
                    deleteBtn.addEventListener("click", function() {
                        selectedFiles.splice(index, 1); // Xóa file khỏi mảng
                        updatePreview(); // Cập nhật lại giao diện
                    });

                    // Thêm hình và nút xóa vào phần tử chứa
                    previewDiv.appendChild(img);
                    previewDiv.appendChild(deleteBtn);

                    // Thêm phần tử chứa vào container
                    previewContainer.appendChild(previewDiv);
                };

                reader.readAsDataURL(file);
            }
        });

        // Cập nhật số lượng tệp đã chọn
        fileCount.innerText = `Đã chọn ${selectedFiles.length} tệp`;
    }
    $('#btnAdd').on('click', function(e) {
        e.preventDefault();

        // Lấy dữ liệu từ form và chuẩn bị gửi bằng FormData
        const formData = new FormData($('#formSanPham')[0]);

        // Thêm ảnh chi tiết vào formData
        const files = document.getElementById('files').files;
        for (let i = 0; i < files.length; i++) {
            formData.append('files[]', files[i]);
        }

        $.ajax({
            url: 'ajax-process/sanpham.php',
            type: 'POST',
            data: formData,
            processData: false, // Không xử lý dữ liệu
            contentType: false, // Không thiết lập content type
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    Fancybox.show([{
                        src: `
                    <div style="padding: 20px; text-align: center;">
                        <div style="font-size: 50px; color: green; margin-bottom: 15px;">
                            <img src="img/verified.gif" width="50" height="50">
                        </div>
                        <h3>Thông báo</h3>
                        <p>Trạng thái: <strong>Bạn đã thêm sản phẩm thành công</strong></p>
                        <button onclick="location.reload();" class="btn btn-primary mt-2">Đóng</button>
                    </div>`,
                        type: "html",
                    }]);

                    $('#formSanPham')[0].reset(); // Reset form
                    $('#imagePreview').hide(); // Ẩn preview ảnh
                } else {
                    Fancybox.show([{
                        src: `
                    <div style="padding: 20px; text-align: center;">
                        <div style="font-size: 50px; color: red; margin-bottom: 15px;">
                            <img src="img/delivery.gif" width="50" height="50">
                        </div>
                        <h3>Thông báo</h3>
                        <p>Trạng thái: <strong>${response.message}</strong></p>
                        <button onclick="Fancybox.close();" class="btn btn-primary mt-2">Đóng</button>
                    </div>`,
                        type: "html",
                    }]);
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                alert('Đã có lỗi xảy ra!');
            }
        });
    });
</script>
<!-- Thêm jQuery nếu chưa có -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Thêm thư viện maskMoney -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.1.1/jquery.maskMoney.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/dropzone/dist/dropzone.css">
<script src="https://cdn.jsdelivr.net/npm/dropzone/dist/dropzone.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/3.2.7/js/froala_editor.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/3.2.7/css/froala_editor.min.css" rel="stylesheet">
<script>
    new FroalaEditor('#editor');
</script>
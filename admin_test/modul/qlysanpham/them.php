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
                <p>Hello from CKEditor 5!</p>
            </textarea>
            </div>
        </div>


        <!-- Hình nền -->
        <div class="mb-3">
            <label class="form-label">Hình Nền</label>
            <input type="file" class="form-control" name="Hinh_Nen" accept="image/*" id="imageUpload" required>
            <img id="imagePreview" src="#" alt="Hình nền" style="display: none; margin-top: 10px; max-width: 200px;">
        </div>

        <!-- <div class="row py-2">
            <div class="col-lg-12 col-md-12">
                <form action="upload_images.php" class="dropzone" id="dropzoneArea"></form>
            </div>
        </div> -->
        <!-- Submit -->
        <button type="submit" class="btn btn-primary" id="btnAdd"> Thêm Sản Phẩm</button>
    </form>

    <div class="row py-2">
        <div class="col-lg-12 col-md-12">
            <form class="dropzone" id="dropzoneArea">
                <div class="dz-default dz-message">
                    <span>Kéo và thả tệp vào đây hoặc nhấp để tải lên</span>
                </div>
            </form>
        </div>
    </div>

    <!-- Dropzone cho phần tải lên nhiều ảnh -->
    <!-- <div class="row py-2">
        <div class="col-lg-12 col-md-12">
            <form action="upload_images.php" class="dropzone" id="dropzoneArea"></form>
        </div>
    </div> -->
</div>

<!-- A friendly reminder to run on a server, remove this during the integration. -->

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
                        $("#formSanPham")[0].reset();
                        $("#imagePreview").hide();
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
</script>
<!-- Thêm jQuery nếu chưa có -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Thêm thư viện maskMoney -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.1.1/jquery.maskMoney.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/dropzone/dist/dropzone.css">
<script src="https://cdn.jsdelivr.net/npm/dropzone/dist/dropzone.min.js"></script>
<link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/43.3.1/ckeditor5.css">
<script type="importmap">
    {
                "imports": {
                    "ckeditor5": "https://cdn.ckeditor.com/ckeditor5/43.3.1/ckeditor5.js",
                    "ckeditor5/": "https://cdn.ckeditor.com/ckeditor5/43.3.1/"
                }
            }
        </script>
<script type="module">
    import {
        ClassicEditor,
        Essentials,
        Paragraph,
        Bold,
        Italic,
        Image,
        Font
    } from 'ckeditor5';

    ClassicEditor
        .create(document.querySelector('#editor'), {
            plugins: [Essentials, Paragraph, Bold, Italic, Font],
            toolbar: [
                'undo', 'redo', '|', 'bold', 'italic', '|',
                'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor'
            ]
        })
        .then(editor => {
            window.editor = editor;
        })
        .catch(error => {
            console.error(error);
        });
</script>
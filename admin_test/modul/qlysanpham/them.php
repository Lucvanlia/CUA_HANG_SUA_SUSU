<?php
include('ketnoi/conndb.php');
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

<div class="contaier mt-5">

</div>
  <form id="rating-form" style="padding-right: -20px;">
                <input type="hidden" id="username" value="' . $_SESSION['id_user'] . '">
                <input type="hidden" id="product_id" value="' .$_SESSION['sp-details'] . '">
                <div class="row ">
                <div class="stars col-lg-4 col-md-6 col-sm-12">
                    <input class="star star-5" id="star-5" type="radio" name="star" value="5" />
                    <label class="star star-5" for="star-5"></label>
                    <input class="star star-4" id="star-4" type="radio" name="star" value="4" />
                    <label class="star star-4" for="star-4"></label>
                    <input class="star star-3" id="star-3" type="radio" name="star" value="3" />
                    <label class="star star-3" for="star-3"></label>
                    <input class="star star-2" id="star-2" type="radio" name="star" value="2" />
                    <label class="star star-2" for="star-2"></label>
                    <input class="star star-1" id="star-1" type="radio" name="star" value="1" />
                    <label class="star star-1" for="star-1"></label>
                </div>
            </div>

                <div class="row py-2">
                    <div class="col-lg-12 col-md-6 col-sm-12">
                        <textarea id="rating-description" class="form-control" placeholder="Nhập mô tả đánh giá"></textarea>
                    </div>
                </div>
                <div class="row py-2">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <button type="button" class="site-btn"id="submit-rating">Gửi đánh giá</button>
                    </div>
                </div>
            </form>
                                    <!-- Dropzone cho phần tải lên nhiều ảnh -->
                                    <div class="row py-2">
                                        <div class="col-lg-4 col-md-12">
                                            <form action="upload_images.php" class="dropzone" id="dropzoneArea"></form>
                                        </div>
                                    </div>
                            
                                </div>
                            </div>
                            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
Dropzone.autoDiscover = false;

$(document).ready(function() {
    Dropzone.autoDiscover = false;

    if (Dropzone.instances.length > 0) {
        Dropzone.instances.forEach(function(dropzone) {
            dropzone.destroy();
        });
    }

    var myDropzone = new Dropzone("#dropzoneArea", {
        url: "comment-process.php",
        autoProcessQueue: false,
        uploadMultiple: true,
        maxFiles: 10,
        acceptedFiles: "image/*",
        addRemoveLinks: true,
        parallelUploads: 10,
        init: function() {
            var dropzone = this;
            $('#submit-rating').on('click', function(e) {
                e.preventDefault();
                var product_id = $('#product_id').val();
                var user_id = $('#username').val();
                var star = $('input[name="star"]:checked').val();
                var description = $('#rating-description').val();

                if (!star) {
                    alert('Vui lòng chọn số sao đánh giá!');
                    return;
                }
                if (!description) {
                    alert('Vui lòng nhập mô tả đánh giá!');
                    return;
                }

                myDropzone.options.params = {
                    user_id: user_id,
                    star: star,
                    description: description
                };

                if (dropzone.getQueuedFiles().length > 0) {
                    dropzone.processQueue();
                } else {
                    // Không có ảnh thì gửi đánh giá trực tiếp
                    $.ajax({
                        url: 'comment-process.php',
                        type: 'POST',
                        data: {
                            user_id: user_id,
                            star: star,
                            description: description
                        },
                        success: function(response) {
                            console.log('Phản hồi từ server:', response);
                            try {
                                var jsonResponse = typeof response === 'string' ? JSON.parse(response) : response;
                                if (jsonResponse.success) {
                                    alert('Đánh giá đã được gửi thành công!');
                                    loadFeedback();
                                } else {
                                    alert('Đánh giá không được lưu: ' + jsonResponse.error);
                                }
                            } catch (e) {
                                console.error('Lỗi JSON:', e);
                            }
                        },
                        error: function(xhr, status, error) {
                            alert('Lỗi server: ' + error);
                        }
                    });
                }
            });

            dropzone.on("successmultiple", function(files, response) {
                console.log('Phản hồi từ server khi tải nhiều ảnh:', response);
                try {
                    var jsonResponse = typeof response === 'string' ? JSON.parse(response) : response;
                    if (jsonResponse.success) {
                        alert('Ảnh đã được tải lên và đánh giá thành công!');
                        loadFeedback();
                    } else {
                        alert('Có lỗi trong quá trình tải ảnh: ' + jsonResponse.error);
                    }
                } catch (e) {
                    console.error('Lỗi JSON:', e);
                    alert('Phản hồi từ server khi tải nhiều ảnh không phải JSON hợp lệ.');
                }
            });

            dropzone.on("errormultiple", function(files, response) {
                alert('Lỗi khi tải ảnh!');
                console.log('Lỗi khi tải nhiều ảnh:', response);
            });
        }
    });
});

</script>
<style>
    div.stars {
        width: 185px;
        display: inline-block;
    }

    input.star {
        display: none;
    }

    label.star {
        float: right;
        padding: 10px;
        font-size: 36px;
        color: #444;
        transition: all .2s;
    }

    input.star:checked~label.star:before {
        content: '\f005';
        color: #FD4;
        transition: all .25s;
    }

    input.star-5:checked~label.star:before {
        color: #FE7;
        text-shadow: 0 0 20px #952;
    }

    input.star-1:checked~label.star:before {
        color: #F62;
    }

    label.star:hover {
        transform: rotate(-15deg) scale(1.3);
    }

    label.star:before {
        content: '\f006';
        font-family: FontAwesome;
    }
</style>
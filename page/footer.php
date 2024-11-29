
<script type="text/javascript">
    // Thêm vào giỏ hàng
 

    $(document).ready(function() {
    $(".cart-del-item").click(function(e) {
        e.preventDefault();
        var $button = $(this); // Lưu lại button được click
        var id = $button.closest("tr").find(".pid").val(); // Lấy id sản phẩm từ input ẩn

        $.ajax({
            url: 'ajax-process.php',
            method: 'post',
            data: {
                id: id, // Truyền id sản phẩm
                status: 'del-item' // Tình trạng xóa sản phẩm
            },
            success: function(data) {
                try {
                    var response = JSON.parse(data); // Parse dữ liệu trả về
                    if (response.status === "success") {
                        $button.closest("tr").remove(); // Xóa phần tử tr chứa sản phẩm

                        // Cập nhật tổng tiền mới trên giao diện
                        $("#tong-tien").text(response.total.toLocaleString('vi-VN') + " VNĐ");

                        // Nếu giỏ hàng trống, hiển thị thông báo
                        if (response.cartEmpty) {
                            $(".checkout__order__products").html("<p>Giỏ hàng của bạn hiện đang trống.</p>");
                        }
                    } else {
                        alert("Lỗi: " + response.message); // Hiển thị thông báo chi tiết từ máy chủ
                    }
                } catch (e) {
                    console.error("Error parsing response:", e);
                    alert("Đã xảy ra lỗi khi xử lý dữ liệu.");
                }
            },
            error: function(xhr, status, error) {
                console.error("Error: " + error);
                alert("Lỗi kết nối với máy chủ.");
            }
        });
    });
});


    // cập nhật số lượng từ input
    // $(document).ready(function() {
    //     // Khi số lượng thay đổi
    //     $('.quantity').on('input', function() {
    //         var quantity = $(this).val();
    //         var price = $(this).data('price');
    //         var id = $(this).data('id');
    //         var max = $(this).data('max');

    //         // Kiểm tra nếu số lượng vượt quá số lượng trong kho
    //         if (quantity > max) {
    //             alert('Số lượng sản phẩm vượt quá số lượng trong kho. Số lượng tối đa là ' + max);
    //             quantity = max; // Đặt số lượng bằng số lượng tối đa
    //             $(this).val(max); // Cập nhật giá trị trên input
    //         }

    //         var total = quantity * price;

    //         // Cập nhật thành tiền của sản phẩm
    //         $('#total-' + id).text(total.toLocaleString('vi-VN') + ' VNĐ');

    //         // Cập nhật tổng tiền của đơn hàng qua Ajax
    //         updateOrderTotal();
    //     });

    //     // function updateOrderTotal() {
    //     //     var data = [];

    //     //     // Lấy dữ liệu từ tất cả các sản phẩm
    //     //     $('.quantity').each(function() {
    //     //         var item = {
    //     //             id: $(this).data('id'),
    //     //             quantity: $(this).val(),
    //     //             price: $(this).data('price'),
    //     //             status: sl
    //     //         };
    //     //         data.push(item);
    //     //     });

    //     //     // Gửi Ajax đến máy chủ để cập nhật tổng tiền
    //     //     $.ajax({
    //     //         url: 'ajax-process.php', // Đường dẫn đến file xử lý PHP
    //     //         type: 'POST',
    //     //         data: {
    //     //             cart: data,
    //     //             status: update - cart
    //     //         },
    //     //         success: function(response) {
    //     //             // Cập nhật tổng tiền từ phản hồi của máy chủ
    //     //             $('#order-total').text(response + ' VNĐ');
    //     //         }
    //     //     });
    //     // }
    // });
    // $(document).ready(function() {
    //     $('.quantity').on('input', function() {
    //         var quantity = parseInt($(this).val());
    //         var price = parseInt($(this).data('price'));
    //         var id = $(this).data('id');
    //         var max;

    //         // Gửi Ajax để lấy maxStock từ server
    //         $.ajax({
    //             url: 'ajax-process.php',
    //             type: 'POST',
    //             data: {
    //                 id: id,
    //                 status: 'get-max-stock' // Sửa lỗi: Thêm dấu nháy đơn quanh 'get-max-stock'
    //             },
    //             dataType: 'json',
    //             success: function(response) {
    //                 max = response.maxStock;

    //                 if (quantity > max) {
    //                     alert('Số lượng sản phẩm vượt quá số lượng trong kho. Số lượng tối đa là ' + max);
    //                     quantity = max; // Đặt số lượng bằng số lượng tối đa
    //                     $('.quantity[data-id="' + id + '"]').val(max);
    //                 } else if (quantity < 1) {
    //                     quantity = 1; // Đảm bảo số lượng ít nhất là 1
    //                     $('.quantity[data-id="' + id + '"]').val(1);
    //                 }

    //                 var total = quantity * price;

    //                 // Cập nhật thành tiền của sản phẩm
    //                 $('#total-' + id).text(total.toLocaleString('vi-VN') + ' VNĐ');

    //                 // Gửi Ajax để cập nhật session số lượng và tổng tiền của đơn hàng
    //                 updateCartQuantity(id, quantity);
    //             }
    //         });
    //     });

    //     function updateCartQuantity(id, quantity) {
    //         $.ajax({
    //             url: 'ajax-process.php',
    //             type: 'POST',
    //             data: {
    //                 id: id,
    //                 quantity: quantity
    //             },
    //             success: function(response) {
    //                 $('#order-total').text(response + ' VNĐ');
    //             }
    //         });
    //     }
    // });

    // $(document).ready(function() {
    //     $('#frmPaying').on('submit', function(event) {
    //         event.preventDefault(); // Ngăn chặn submit mặc định

    //         var paymentMethod = $('input[name="payment_method"]:checked').val(); // Lấy phương thức thanh toán được chọn

    //         if (!paymentMethod) {
    //             alert("Vui lòng chọn phương thức thanh toán.");
    //             return; // Ngừng thực hiện nếu không chọn phương thức thanh toán
    //         }

    //         var formData = new FormData(this); // Lấy dữ liệu form

    //         $.ajax({
    //             url: 'ajax-process.php', // URL xử lý đơn hàng
    //             type: 'POST',
    //             data: formData,
    //             contentType: false,
    //             processData: false,
    //             success: function(response) {
    //                 if (response.trim() === "success_cod") {
    //                     alert("Đặt hàng COD thành công!");
    //                 } else if (response.trim() === "success_vnpay") {
    //                     window.location.href = "https://banhangviet-tmi.net/doan_php/index.php?action=cart&query=vnpay"; // Điều hướng sang trang VNPay return
    //                 } else if (response.trim() === "empty") {
    //                     alert("Vui lòng chọn phương thức thanh toán.");
    //                 } else {
    //                     alert("Chú ý: " + response);
    //                 }
    //             },
    //             error: function() {
    //                 alert("Có lỗi xảy ra, vui lòng thử lại!");
    //             }
    //         });
    //     });
    // });
</script>
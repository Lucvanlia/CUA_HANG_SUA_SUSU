<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" />

<ul>
    <li><a href="#"><i class="fa fa-heart"></i> <span>1</span></a></li>
    <li>
        <a data-fancybox data-type="ajax" id="cart-link" href="get_order_status.php">
            <i class="fa fa-shopping-bag"></i>
            <span id="order-count"></span>
        </a>
    </li>
</ul>

<script>
$(document).ready(function() {
    $('#cart-link').fancybox({
        type: 'ajax',
        smallBtn: true,
        afterShow: function(instance, current) {
            console.log('Nội dung đơn hàng đã được hiển thị.');
        }
    });
});
</script>

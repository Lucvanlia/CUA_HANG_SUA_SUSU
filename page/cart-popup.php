
    <!-- Checkout Section Begin -->
    <section class="checkout spad">
        <div class="container">
            <div class="checkout__form">
                        <div class="col-lg-12 col-md-6 col-">
                            <div class="checkout__order">
                                <h4>Đơn hàng của bạn</h4>
                                <div class="checkout__order__products">
                                    <form action="?action=mua&query=mua" method="post">
                                <table class="table table-hover " class="d-flex align-items-center">
                                    <thead>
                                        <tr>
                                        <th scope="col"  class="text-left">STT</th>
                                        <th scope="col"  class="text-left">Sản phẩm</th>
                                        <th scope="col"  class="text-center">Ảnh</th>
                                        <th scope="col" class="text-center">Giá</th>
                                        <th scope="col"  class="text-center">Số lượng</th>
                                        <th scope="col"  class="text-right">Thành tiền</th>
                                        <th scope="col"  class="text-right">Bỏ</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <style>
                                      
                                            .checkout__order
                                            {
                                                background-color: white !important;
                                            }
                                        </style>
                                        <tr>
                                            <th >1</th> 
                                            <th scope="row"  class="text-left">Rau xanh tươi rối của chúng</th>
                                            <td ><img class=" img-thumbnail" width="150" height="150" src="https://cdn.tgdd.vn/Products/Images/8785/273076/bhx/hanh-tay-202312261433144727.jpg" alt="" ></td>
                                            <td  class="text-right">1.000.000 VNĐ</td>
                                            <td class="text-center" width="10" height="10"><input type="text" value="" name="quantity" size="5"></td>
                                            <td class="text-right">12.000.000 VNĐ</td>
                                        </tr>
                                        <tr>
                                        <th >1</th> 
                                            <th scope="row"  class="text-left">Rau xanh tươi rối của chúng</th>
                                            <td ><img class=" img-thumbnail" width="150" height="150" src="https://cdn.tgdd.vn/Products/Images/8785/273076/bhx/hanh-tay-202312261433144727.jpg" alt="" ></td>
                                            <td  class="text-right">1.000.000 VNĐ</td>
                                            <td class="text-center" width="10" height="10"><input type="text" value="12" size="5"></td>
                                            <td class="text-right">12.000.000 VNĐ</td>
                                            <td ><a href="" class="btn btn-danger">Xóa</a> </td>
                                         </tr>
                                    </tbody>
                                    </table>
                                <div class="checkout__order__total">Tổng cộng<span>$750.99</span></div>
                                <div class="checkout__input__checkbox">
                                    <label for="acc-or">
                                        Create an account?
                                        <input type="checkbox" id="acc-or">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <p>Lorem ipsum dolor sit amet, consectetur adip elit, sed do eiusmod tempor incididunt
                                    ut labore et dolore magna aliqua.</p>
                                <div class="checkout__input__checkbox">
                                    <label for="payment">
                                        Check Payment
                                        <input type="checkbox" id="payment">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="checkout__input__checkbox">
                                    <label for="paypal">
                                        Paypal
                                        <input type="checkbox" id="paypal">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <button type="submit" class="site-btn">PLACE ORDER</button>
                            </div>
                            </form>
                        </div>
                    </div>
            </div>
        </div>
    </section>
    <!-- Checkout Section End -->
    <a href="?action=checkout&query=checkout">Đi tới thanh toan</a>
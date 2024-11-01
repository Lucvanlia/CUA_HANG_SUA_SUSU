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
<div class="form-container  ">
        <form method="post" action="modul/qlysanpham/xly.php" enctype="multipart/form-data">
            <h2 class="text-center"><strong>Bảng nhập sản phẩm</strong></h2>
            <div class="modal fade " id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl center">
                    <div class="modal-content justify-content-center">
                        <div class="modal-header">
                            <h1 class="modal-title fs-10" id="exampleModalLabel">Thêm sản phẩm</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div>
                                <div class="form-group  py-3    ">
                                    <label for="" class="col-form-label  "><strong>Tên sản phẩm:</strong></label>
                                    <input class="form-control  " type="text" name="txttensp" placeholder="Nhập tên chất liệu" width="300px" id="txttensp" required>
                                </div>
                            </div>
                            <div>
                                <div class="form-group  py-3    ">
                                    <label for="" class="form-label  "><strong>Chọn hãng:</strong></label>
                                    <select name="cmbh" class="dtform   form-select form-select-lg mb-3 ">
                                        <?php
                                        $i = 1 ;
                                        while ($rows = mysqli_fetch_array($result_hang)) {
                                        ?>
                                            <option value="<?= $rows['id_hang'] ?>" required> <?= $rows['tenhang'] ?></option>
                                        <?php
                                            $i = $i + 1;
                                        } //KT Else
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group  py-3    ">
                                    <label for="" class="form-label  "><strong>Chọn loại:</strong></label>
                                    <select name="cmbl" class="dtform   form-select form-select-lg mb-3 ">
                                        <?php
                                        while ($rows = mysqli_fetch_array($result_loai)) {
                                        ?>
                                            <option value="<?= $rows['id_loai'] ?>" required> <?= $rows['tenloai'] ?></option>
                                        <?php
                                            $i = $i + 1;
                                        } //KT Else
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group  py-3    ">
                                    <label for="" class="form-label  "><strong>Chọn xuất xứ:</strong></label>
                                    <select name="cmbxx" class="dtform   form-select form-select-lg mb-3 ">
                                        <?php
                                        while ($rows = mysqli_fetch_array($result_xuatxu)) {
                                        ?>
                                            <option value="<?= $rows['id_xuatxu'] ?>" required> <?= $rows['tenxuatxu'] ?></option>
                                        <?php
                                            $i = $i + 1;
                                        } //KT Else
                                        ?>
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="form-group mx-3">
                            <label for="exampleFormControlTextarea1">Nhập mô tả</label>
                            <textarea class="form-control editor" id="exampleFormControlTextarea1" name="txtmota" row="10" col="25" required></textarea>
                            <script>
                                CKEDITOR.replace('txtmota');
                            </script>
                            <label for="exampleFormControlTextarea1">Nhập hình sản phẩm</label>
                            <input class="form-control" type="file" id="exampleFormControlTextarea1" name="txthinh" required>
                        </div>
                        <div class="from-group d-flex justify-content-center ">
                            <input class="btn btn-primary mx-3 " type="submit" width="150px" height="150px" name="themsanpham" value="Thêm ">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </div>
    </div>

    </form>
<?php
    $sql_sua_sanpham="SELECT * FROM 
    dmsp  as sp 
    join xuatxu   as xx on sp.id_xuatxu = xx.id_xuatxu 
    join hang     as h  on sp.id_hang = h.id_hang       
    join diadiem  as dd on sp.id_diadiem = dd.id_diadiem            
    join loai     as l  on sp.id_loai = l.id_loai            
    join chatlieu as cl on sp.id_chatlieu = cl.id_chatlieu                     
    WHERE id_sp='$_GET[id]' LIMIT 1";
    $query_sua_sanpham=mysqli_query($link,$sql_sua_sanpham);
    $smt = mysqli_num_rows($query_sua_sanpham);
?>
<link rel="stylesheet" href="css/them.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<div class="register-photo card shadow">
        <div class="form-container" >
            <form method="post" action="modul/qlysanpham/xly.php?id=<?php echo $_GET['id'] ?>">
                <?php
                    while ($row = mysqli_fetch_array($query_sua_sanpham)){
                ?>
                <h2 class="text-center"><strong>Sửa thông tin sản phẩm</strong>.</h2>
                <div class="row">
                    <div class="col-md-4 form-group  d-flex justify-content-end">
                        <label for="" class="p-2 bd-highlight">Tên sản phẩm</label>
                    </div>
                    <div class="col-md-4 form-group d-flex justify-content-start">
                      <input class="col-form-label form-control   text-center p-2 bd-highlight " type="text" name="txtsp" value="<?php echo $row['Tensp']?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 form-group  d-flex justify-content-end">
                        <label for="" class="p-2 bd-highlight">Xuất xứ</label>
                    </div>
                    <div class="col-md-4 form-group d-flex justify-content-start">
                <select name="cmbxx" class="dtform   form-select form-select-lg mb-3 "  >
                      <option value="<?=$rows['id_xuatxu']?>" required > <?php echo $row['tenxuatxu']?></option>
                 </select>                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 form-group  d-flex justify-content-end">
                        <label for="" class="p-2 bd-highlight">Loại </label>
                    </div>
                    <div class="col-md-4 form-group d-flex justify-content-start">
                <select name="cmbxx" class="dtform   form-select form-select-lg mb-3 "  >
                      <option value="<?=$rows['id_loai']?>" required > <?php echo $row['tenloai']?></option>
                 </select>                   
                 </div>
                </div>
                <div class="row">
                    <div class="col-md-4 form-group  d-flex justify-content-end">
                        <label for="" class="p-2 bd-highlight">Hãng: </label>
                    </div>
                    <div class="col-md-4 form-group d-flex justify-content-start">
                <select name="cmbxx" class="dtform   form-select form-select-lg mb-3 "  >
                      <option value="<?=$rows['id_hang']?>" required > <?php echo $row['tenhang']?></option>
                 </select>                   
                 </div>
                </div>
                <div class="row">
                    <div class="col-md-4 form-group  d-flex justify-content-end">
                        <label for="" class="p-2 bd-highlight">Chất liệu </label>
                    </div>
                    <div class="col-md-4 form-group d-flex justify-content-start">
                <select name="cmbxx" class="dtform   form-select form-select-lg mb-3 "  >
                      <option value="<?=$rows['id_chatlieu']?>" required > <?php echo $row['TenChatLieu']?></option>
                 </select>                   
                 </div>
                </div>
                <div class="row">
                    <div class="col-md-4 form-group  d-flex justify-content-end">
                        <label for="" class="p-2 bd-highlight">Địa điểm </label>
                    </div>
                    <div class="col-md-4 form-group d-flex justify-content-start">
                <select name="cmbxx" class="dtform   form-select form-select-lg mb-3 "  >
                      <option value="<?=$rows['id_diadiem']?>" required > <?php echo $row['tendiadiem']?></option>
                 </select>                   
                 </div>
                </div>
                <div class="row">
                    <div class="col-md-4 form-group  d-flex justify-content-end">
                        <label for="" class="p-2 bd-highlight">Hình ảnh </label>
                    </div>
                    <div class="col-md-4 form-group d-flex justify-content-start">
                    <img src="modul/uploads/<?php echo $row['hinh']?>   " alt="" class="img-fluid img-thumbnail">                
                 </div>
                </div>
                <div class="row">
                    <div class="col-md-4 form-group  d-flex justify-content-end">
                        <label for="" class="p-2 bd-highlight">Mô tả </label>
                    </div>
                    <div class="col-md-4 form-group d-flex justify-content-start">
                    <textarea class="form-control" id="exampleFormControlTextarea1" name="txtmota"row="10" col="25" required><?php echo $row['MoTa']?></textarea>
                 </div>
                </div>
                <div class="row d-flex justify-content-center">
                        <div class="col-md-6">
                                            <div class="form-group d-flex justify-content-center  ">
                                            <input class="btn btn-primary " type="submit" name="Suasanpham" value="Sửa Loại giày"></input></div>
                        </div>
            </div>
                <div class="form-group btn btn-danger "><a class="btn btn-danger" href="index.php?action=quanlysanpham&query=them"><i class="fa-solid fa-delete-left "></i> &nbsp Trở lại</a></div>
                <?php
                    }
                ?> 
            </form>
    </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/js/bootstrap.bundle.min.js"></script>

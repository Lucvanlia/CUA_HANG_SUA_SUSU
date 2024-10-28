<?php 
        //========================================SQL==================================================
        $sql_all="SELECT * FROM 
          dmsp  as sp 
          join xuatxu   as xx on sp.id_xuatxu = xx.id_xuatxu 
          join hang     as h  on sp.id_hang = h.id_hang       
          join loai     as l  on sp.id_loai = l.id_loai            
        ";
        //========================================ressult==================================================
        $result_all=mysqli_query($link, $sql_all);
        //==========================================================================================
        $count_all=mysqli_num_rows($result_all);

?>
<style>
  .test{
    text-align: center; vertical-align: middle;
  }
</style>
<div class=" mb-4" id="DesignationTable" style="background-color:#fff">
                        <div class="card-header py-3" style="background-color:#fff">
                            <h6 class="m-0 font-weight-bold text-primary">Danh sách sản phẩm</h6>
                           
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                            <div class="rowd-flex align-items-center">
                            <div class="py-4 d-flex flex-row bd-highlight mb-3">
                                <div class="col-md-4 d-flex align-items-center">
                                <form method="post" action="index.php?action=timkiem&query=timkiem_sanpham" class="py-2">
                                <label for="">Tìm kiếm sản phẩm</label>
                                <input type="text" id="search_sanpham" name ="search"class="col-form-label"  placeholder="Tìm sản phẩm" autocomplete="off" value="<?php if(isset($_GET["search"])) {echo $_GET["search"];}?>">
                                <input type="button" value="Thêm" class="btn btn-primary " name="view_them"  data-bs-toggle="modal" data-bs-target="#exampleModal">
                                <input type="submit" value="Tìm" class="btn btn-primary " name="tim">
                            </form>
                                </div>
                                <div class="col-md-8 d-flex align-items-center d-flex justify-content-end">
                                   <input type="button" onclick='selects()' value="Select All" class=" btn btn-primary "/> 
                                   <input type="button" onclick='deSelect()' value="Deselect All" class="btn btn-warning "/>                                             
                                </div>     
                                  
                            </div>
                            </div>
                            <form action="modul/qlysanpham/xly.php" method="post">
                           <div class="row" >
                           <div class="col-md-3 py-3 d-flex flex-row bd-highlight-reverse mb-3">
                                   <input style="display:none" id="delete" name="delete" type="submit" value="Delete" class="btn btn-danger py-2  rounded-3 float-end">
                            </div> 
                           </div>
                            <td>Số lượng sản phẩm: <?php echo $count_all?></td>  
                                <table  class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                            <th>STT</th>
                                            <th>Tên sản phẩm</th>
                                            <th>Xuất xứ</th>
                                            <th>loại</th>
                                            <th>Hãng</th>
                                            <th>Chất liệu</th>
                                            <th>Địa điểm</th>
                                            <th>Hình</th>
                                            <th>Mô tả</th>
                                            <th>Chỉnh sửa </th>
                                            <th>Xóa</th>
                                        </tr>
                                        <tbody id="searchkq" >                            
                                    </thead>
                                    <tr>
                                        <?php
                                                if(mysqli_num_rows($result_all) > 0  )
                                               {
                                                $i =0;
                                                 while($row=mysqli_fetch_array($result_all))
                                                 {
                                                    $i =$i + 1 ;
                                                
                                        ?> 
                                        <tr >
                                            <td style="text-align: center; vertical-align: middle;"><?php echo $i?></td>
                                            <td style="text-align: center; vertical-align: middle;"><?php echo $row['Tensp']?></td>
                                            <td style="text-align: center; vertical-align: middle;"><?php echo $row['tenxuatxu']?></td>
                                            <td style="text-align: center; vertical-align: middle;"><?php echo $row['tenloai']?></td>
                                            <td style="text-align: center; vertical-align: middle;"><?php echo $row['tenhang']?></td>
                                            <td style="text-align: center; vertical-align: middle;"><?php echo $row['TenChatLieu']?></td>
                                            <td style="text-align: center; vertical-align: middle;"><?php echo $row['tendiadiem']?></td>
                                            <td><img src="modul/uploads/<?php echo $row['hinh']?>   " alt="" class="img-fluid img-thumbnail"></td>
                                            <td style="text-align: center; vertical-align: middle;"><?php echo $row['MoTa']?></td>
                                            <td><a href="?action=quanlysanpham&query=sua&id=<?php echo $row['id_sp'];?>" ><i class="fa-solid fa-pen-to-square"></i></a></td>
                                            <td><input name="ckcl[]" type="checkbox"  value="<?php echo $row['id_sp'];  ?>"class="Organization_Desg_Check_margin" id="OrganizationDesgCheckData1<?php echo $stt?>"></td>
                                        </tr>
                                        <?php 
                                                 }
                                                }
                                        ?>
                                    </tr>
                                    </tbody>
                                </table>
                                </form>
                            </div>
                        </div>
                    </div>
                                       
                </div>
                </div>
                <script type="text/javascript" ></script>
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
                

             

              
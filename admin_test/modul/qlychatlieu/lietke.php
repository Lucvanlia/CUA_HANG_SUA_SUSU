<?php 
		$sql2 = "SELECT * FROM chatlieu ";
		$result = mysqli_query($link, $sql2);
		$count=mysqli_num_rows($result);
?>
<div class=" mb-4" id="DesignationTable" style="background-color:#fff">
                        <div class="card-header py-3" style="background-color:#fff">
                            <h3 class="m-0 font-weight-bold text-primary">Danh sách chất liệu</h3>
                           
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                            <div class="rowd-flex align-items-center">
                            <div class="py-4 d-flex flex-row bd-highlight mb-3">
                                <div class="col-md-4 d-flex align-items-center">
                                <form method="post" action="index.php?action=timkiem&query=timkiem" class="py-2">
                                <label for="">Tìm kiếm sản phẩm</label>
                                <input type="text" id="search_chatlieu"name ="search"class="col-form-label"  placeholder="Tìm chất liệu" autocomplete="off" value="<?php if(isset($_GET["search"])) {echo $_GET["search"];}?>">
                                <input type="submit" value="Tìm" class="btn btn-primary " name="tim">
                                <input type="button" value="Thêm" class="btn btn-primary " name="view_them"  data-bs-toggle="modal" data-bs-target="#exampleModal">
                            </form>
                                </div>
                                <div class="col-md-8 d-flex align-items-center d-flex justify-content-end">
                                   <input type="button" onclick='selects()' value="Select All" class=" btn btn-primary "/> 
                                   <input type="button" onclick='deSelect()' value="Deselect All" class="btn btn-warning "/>                                             
                                </div>     
                                  
                            </div>
                            </div>
                            <form action="modul/qlychatlieu/xly.php" method="post">
                           <div class="row" >
                           <div class="col-md-3 py-3 d-flex flex-row bd-highlight-reverse mb-3">
                                   <input style="display:none" id="delete" name="delete" type="submit" value="Delete" class="btn btn-danger py-2  rounded-3 float-end">
                            </div> 
                           </div>
                            <td>Số lượng chất liệu: <?php echo $count?></td>  
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                            <th>STT</th>
                                            <th>Tên chất liệu</th>
                                            <th>Chỉnh sửa </th>
                                            <th>Xóa</th>
                                        </tr>
                                        <tbody id="searchkq" >
                                    <?php
                                        $stt  = 0;
                                        while ($row=mysqli_fetch_array($result)) 
                                        {
                                            $stt = $stt + 1;
                                        ?>                                      
                                    </thead>
                                 
                                        <tr>
                                            <td><?php echo $stt ?></td>
                                            <td><?php echo $row['TenChatLieu']; ?></td>
                                            <td><a href="?action=quanlychatlieu&query=sua&id=<?php echo $row['id_chatlieu'];?>" ><i class="fa-solid fa-pen-to-square"></i></a></td>
                                            <td class="<?php $stt?>"><input name="ckcl[]" type="checkbox"  value="<?php echo $row['id_chatlieu'];  ?> "class="Organization_Desg_Check_margin" id="OrganizationDesgCheckData1<?php echo $stt?>"></td>
                                        </tr>
                                        <?php	
                                                }//kt while 
                                                ?>
                                    </tbody>
                                    <?php     
                                    ?>
                                </table>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
                </div>
                <script type="text/javascript" ></script>
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
                

             

              
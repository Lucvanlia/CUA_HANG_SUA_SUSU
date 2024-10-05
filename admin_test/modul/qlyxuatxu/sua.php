<?php
    $sql_sua_xuatxu ="SELECT * FROM xuatxu WHERE id_xuatxu='$_GET[id]' LIMIT 1";
    $query_sua_xuatxu=mysqli_query($link,$sql_sua_xuatxu);
    $smt = mysqli_num_rows($query_sua_xuatxu);
?>
<link rel="stylesheet" href="css/them.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <div class="register-photo card shadow">
        <div class="form-container" >
            <form method="post" action="modul/qlyxuatxu/xly.php?id=<?php echo $_GET['id'] ?>">
                <?php
                    while ($row = mysqli_fetch_array($query_sua_xuatxu)){
                ?>
                <h2 class="text-center"><strong>Sửa thông tin xuất xứ</strong>.</h2>
                <div class="row d-flex justify-content-center">
                    <div class="col-md-6">
                    <div class="form-group d-flex justify-content-center ">
                        <input class="col-form-label form-control rounded-pill text-center  " type="text" name="xuatxu" value="<?php echo $row['tenxuatxu']?>">
                    </div>
                    </div>
                </div>
                <div class="row d-flex justify-content-center">
                        <div class="col-md-6">
                                            <div class="form-group d-flex justify-content-center  ">
                                            <input class="btn btn-primary " type="submit" name="Suaxuatxu" value="Sửa xuất xứ"></input></div>
                        </div>
            </div>
                <div class="form-group btn btn-danger "><a class="btn btn-danger" href="index.php?action=quanlyxuatxu&query=them"><i class="fa-solid fa-delete-left "></i> &nbsp Trở lại</a></div>
                <?php
                    }
                ?> 
            </form>
    </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/js/bootstrap.bundle.min.js"></script>

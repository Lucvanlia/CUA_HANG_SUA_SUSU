<?php
    include ('../../ketnoi/conndb.php');
    
        if(isset($_POST['themsanpham']))
    {
        $tensp = $_POST['txttensp'];
        $xuatxu = $_POST['cmbxx'];
        $hang = $_POST['cmbh'];
        $chatlieu = $_POST['cmbcl'];
        $diadiem = $_POST['cmbdd'];
        $loai = $_POST['cmbl'];
        $hinhanh= $_FILES['txthinh']['name'];
        $hinhanh_tmp= $_FILES['txthinh']['tmp_name'];
        $mota= $_POST['txtmota'];
        $querycheck = mysqli_query($link , "SELECT * FROM dmsp WHERE Tensp = '$tensp'");
        if(mysqli_num_rows($querycheck) > 0)
        {          
            include"loi.php";   
        }
        else {
            $sql_them = "INSERT INTO `dmsp` ( Tensp,id_hang,id_xuatxu,id_diadiem,id_chatlieu,id_loai,Mota,hinh)
              VALUES ( '".$tensp."' , '$hang','$xuatxu' ,'$diadiem','$chatlieu','$loai','".$mota."'  ,'".$hinhanh."'  )";
            mysqli_query($link,$sql_them);
            header('location:../../index.php?action=quanlysanpham&query=them');
            move_uploaded_file($hinhanh_tmp,'../uploads/'.$hinhanh);

        }
        echo '
            <script>
                document.getElementById("txtxuatxu").focus();
            </script>

        ';
        
    }
    elseif(isset($_POST['Suasanpham']))
    {

        $tensp = $_POST['txtsp'];
        $sql_sua = "UPDATE  `dmsp` SET Tensp='".$tensp."' WHERE id_sp = '$_GET[id]'";
        mysqli_query($link,$sql_sua);
        header('location:../../index.php?action=quanlysanpham&query=them');
        /* 
               $xuatxu = $_POST['cmbxx'];
        $hang = $_POST['cmbh'];
        $chatlieu = $_POST['cmbcl'];
        $diadiem = $_POST['cmbdd'];
        $loai = $_POST['cmbl'];
        $mota=$_POST['txtmota'];
        //$hinhanh= $_FILES['txthinh']['name'];
      //  $hinhanh_tmp= $_FILES['txthinh']['tmp_name'];
      id_xuatxu='".$xuatxu."';
        id_hang='".$hang."';
        id_chatlieu='".$chatlieu."';
        id_diadiem='".$diadiem."';
        id_loai='".$loai."';
        id_mota='".$mota."';  */
    }

     // Check if delete button active, start this 
     elseif(isset($_POST['delete']))
     {
 
        $smt_check = array();
        $smt_check = count($_POST['ckcl']);
        echo"$hinhanh";
         for($i=0;$i< $smt_check;$i++){
             $del_id = $_POST['ckcl'][$i];
             $sql_chon="SELECT * FROM dmsp WHERE id_sp = '".$del_id."'";
             $query=mysqli_query($link,$sql_chon);
             while($row = mysqli_fetch_array($query))
             {
                    unlink('../uploads/'.$row['hinh']);
             }
             $sql = "DELETE FROM dmsp WHERE id_sp ='".$del_id."'";
             $result = mysqli_query($link, $sql);
         }

         // if successful redirect to delete_multiple.php 
         if($result){           
            header('location:../../index.php?action=quanlysanpham&query=them');
        }
        }
        //tim kiem 
        elseif(isset($_POST['name']))
        {
            $name = $_POST['name'];
            $sql_timkiem = "SELECT TOP 50 * FROM xuatxu WHERE xuatxu.Tenxuatxu like '%".$name."%'";
            $query_timkiem = mysqli_query($link,$sql_timkiem);
            $data = '';
            $stt  = 0;
            while ($row=mysqli_fetch_array($query_timkiem)) 
            {
                $stt = $stt + 1;
                $data ="
                <tr>
                <td>  $stt</td>
                <td>".$row['tenxuatxu']."</td>
                <td><a href='?action=quanlyxuatxu&query=sua&id=".$row['id_xuatxu']."' ><i class='fa-solid fa-pen-to-square'></i></a></td>
                <td class='echo $stt'><input name='ckcl[]' type='checkbox'  value='".$row['id_xuatxu']."'class='Organization_Desg_Check_margin' id='OrganizationDesgCheckData1  $stt '></td>
            </tr>
            ";
            }
            echo $data;
            
             mysqli_close($link);
        }
         
?>
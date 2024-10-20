<?php
    include ('../../ketnoi/conndb.php');
    
        if(isset($_POST['themloai']))
    {  $codejs = $_POST['themloai'];
        $tenloai = $_POST['loai'];
        $querycheck = mysqli_query($link , "SELECT * FROM loai WHERE tenloai = '$tenloai'");
        if(mysqli_num_rows($querycheck) > 0)
        {          
            include"loi.php";
        }
        else {
            $sql_them = "INSERT INTO `loai` ( `tenloai`) VALUES ('".$tenloai."')";
            mysqli_query($link,$sql_them);
            header('location:../../index.php?action=quanlyloai&query=them');
        }
        echo '
            <script>
            document.getElementById("txtloai").focus();
            </script>


        ';
        
    }
    elseif(isset($_POST['Sualoai']))
    {
        $tenloai = $_POST['loai'];
        $sql_sua = "UPDATE  `loai` SET tenloai='".$tenloai."' WHERE id_loai = '$_GET[id]'";
        mysqli_query($link,$sql_sua);
        header('location:../../index.php?action=quanlyloai&query=them');
    }

     // Check if delete button active, start this 
     elseif(isset($_POST['delete']))
     {
        
        $smt_check = array();
        $smt_check = count($_POST['ckcl']);
         for($i=0;$i< $smt_check;$i++){
             $del_id = $_POST['ckcl'][$i];
             $sql = "DELETE FROM loai WHERE id_loai ='".$del_id."'";
             $result = mysqli_query($link, $sql);
         }
         // if successful redirect to delete_multiple.php 
         if($result){
            header('location:../../index.php?action=quanlyloai&query=them');
        }
        }
        //tim kiem 
        elseif(isset($_POST['name']))
        {
            $name = $_POST['name'];
            $sql_timkiem = "SELECT * FROM loai WHERE loai.Tenloai like '%".$name."%'";
            $query_timkiem = mysqli_query($link,$sql_timkiem);
            $data = '';
            $stt  = 0;
            while ($row=mysqli_fetch_array($query_timkiem)) 
            {
                $stt = $stt + 1;
                $data ="
                <tr>
                <td>  $stt</td>
                <td>".$row['tenloai']."</td>
                <td><a href='?action=quanlyloai&query=sua&id=".$row['id_loai']."' ><i class='fa-solid fa-pen-to-square'></i></a></td>
                <td class='echo $stt'><input name='ckcl[]' type='checkbox'  value='".$row['id_loai']."'class='Organization_Desg_Check_margin' id='OrganizationDesgCheckData1  $stt '></td>
            </tr>
            ";
            }
            echo $data;
            
             mysqli_close($link);
        }
         
?>
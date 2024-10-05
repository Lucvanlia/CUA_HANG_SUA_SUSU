<?php
    include ('../../ketnoi/conndb.php');
    
        if(isset($_POST['themchatlieu']))
    {  $codejs = $_POST['themchatlieu'];
        $tenchatlieu = $_POST['chatlieu'];
        $querycheck = mysqli_query($link , "SELECT * FROM chatlieu WHERE TenChatLieu = '$tenchatlieu'");
        if(mysqli_num_rows($querycheck) > 0)
        {          
            include"loi.php";
        }
        else {
            $sql_them = "INSERT INTO `chatlieu` ( `TenChatLieu`) VALUES ('".$tenchatlieu."')";
            mysqli_query($link,$sql_them);
            header('location:../../index.php?action=quanlychatlieu&query=them');
        }
        echo '
            <script>
            document.getElementById("txtchatlieu").focus();
            </script>


        ';
        
    }
    elseif(isset($_POST['Suachatlieu']))
    {
        $tenchatlieu = $_POST['chatlieu'];
        $sql_sua = "UPDATE  `chatlieu` SET TenChatLieu='".$tenchatlieu."' WHERE id_chatlieu = '$_GET[id]'";
        mysqli_query($link,$sql_sua);
        header('location:../../index.php?action=quanlychatlieu&query=them');
    }

     // Check if delete button active, start this 
     elseif(isset($_POST['delete']))
     {
        
        $smt_check = array();
        $smt_check = count($_POST['ckcl']);
         for($i=0;$i< $smt_check;$i++){
             $del_id = $_POST['ckcl'][$i];
             $sql = "DELETE FROM chatlieu WHERE id_chatlieu ='".$del_id."'";
             $result = mysqli_query($link, $sql);
         }
         // if successful redirect to delete_multiple.php 
         if($result){
            header('location:../../index.php?action=quanlychatlieu&query=them');
        }
        }
        //tim kiem 
        elseif(isset($_POST['name']))
        {
            $name = $_POST['name'];
            $sql_timkiem = "SELECT  * FROM chatlieu  WHERE chatlieu.TenChatLieu  like '%".$name."%'  limit 3 " ;
            $result_timkiem = mysqli_query($link,$sql_timkiem);
            $stt  = 0;
            $data='';
            if(mysqli_num_rows($result_timkiem) > 0 )
            {
                while ($row=mysqli_fetch_array($result_timkiem)) 
            {
                $stt = $stt + 1;
                $data ="
                <tr>
                <td>  $stt</td>
                <td>".$row['TenChatLieu']."</td>
                <td><a href='?action=quanlychatlieu&query=sua&id=".$row['id_chatlieu']."' ><i class='fa-solid fa-pen-to-square'></i></a></td>
                <td class='echo $stt'><input name='ckcl[]' type='checkbox'  value='".$row['id_chatlieu']."'class='Organization_Desg_Check_margin' id='OrganizationDesgCheckData1  $stt '></td>
            </tr>
            ";
            echo $data;
            }
            }else {
                while ($row=mysqli_fetch_array($result_timkiem)) 
                {
                    $stt = $stt + 1;
                    $data ="
                    <tr>
                    <td>  $stt</td>
                    <td>Không tồn tại nội dung tìm</td>
                    <td><a href='?action=quanlychatlieu&query=sua&id=".$row['id_chatlieu']."' ><i class='fa-solid fa-pen-to-square'></i></a></td>
                    <td class='echo $stt'><input name='ckcl[]' type='checkbox'  value='".$row['id_chatlieu']."'class='Organization_Desg_Check_margin' id='OrganizationDesgCheckData1  $stt '></td>
                </tr>
                ";
                echo $data;
            }
            
             mysqli_close($link);
        }
    }
         
?>
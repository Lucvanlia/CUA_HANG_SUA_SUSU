<?php
    include ('../../ketnoi/conndb.php');
    
        if(isset($_POST['themxuatxu']))
    {  $codejs = $_POST['themxuatxu'];
        $tenxuatxu = $_POST['xuatxu'];
        $querycheck = mysqli_query($link , "SELECT * FROM xuatxu WHERE tenxuatxu = '$tenxuatxu'");
        if(mysqli_num_rows($querycheck) > 0)
        {          
            include"loi.php";
        }
        else {
            $sql_them = "INSERT INTO `xuatxu` ( `tenxuatxu`) VALUES ('".$tenxuatxu."')";
            mysqli_query($link,$sql_them);
            header('location:../../index.php?action=quanlyxuatxu&query=them');
        }
        echo '
            <script>
            document.getElementById("txtxuatxu").focus();
            </script>


        ';
        
    }
    elseif(isset($_POST['Suaxuatxu']))
    {
        $tenxuatxu = $_POST['xuatxu'];
        $sql_sua = "UPDATE  `xuatxu` SET tenxuatxu='".$tenxuatxu."' WHERE id_xuatxu = '$_GET[id]'";
        mysqli_query($link,$sql_sua);
        header('location:../../index.php?action=quanlyxuatxu&query=them');
    }

     // Check if delete button active, start this 
     elseif(isset($_POST['delete']))
     {
        
        $smt_check = array();
        $smt_check = count($_POST['ckcl']);
         for($i=0;$i< $smt_check;$i++){
             $del_id = $_POST['ckcl'][$i];
             $sql = "DELETE FROM xuatxu WHERE id_xuatxu ='".$del_id."'";
             $result = mysqli_query($link, $sql);
         }
         // if successful redirect to delete_multiple.php 
         if($result){
            header('location:../../index.php?action=quanlyxuatxu&query=them');
        }
        }
        //tim kiem 
        elseif(isset($_POST['name']))
        {
            $name = $_POST['name'];
            $sql_timkiem = "SELECT * FROM xuatxu WHERE xuatxu.Tenxuatxu like '%".$name."%'";
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
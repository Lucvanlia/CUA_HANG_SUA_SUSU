<?php
    include ('../../ketnoi/conndb.php');
    
        if(isset($_POST['themhang']))
    {  $codejs = $_POST['themhang'];
        $tenhang = $_POST['hang'];
        $querycheck = mysqli_query($link , "SELECT * FROM hang WHERE Tenhang = '$tenhang'");
        if(mysqli_num_rows($querycheck) > 0)
        {          
            include"loi.php";
        }
        else {
            $sql_them = "INSERT INTO `hang` ( `Tenhang`) VALUES ('".$tenhang."')";
            mysqli_query($link,$sql_them);
            header('location:../../index.php?action=quanlyhang&query=them');
        }
        echo '
            <script>
            document.getElementById("txthang").focus();
            </script>


        ';
        
    }
    elseif(isset($_POST['Suahang']))
    {
        $tenhang = $_POST['hang'];
        $sql_sua = "UPDATE  `hang` SET Tenhang='".$tenhang."' WHERE id_hang = '$_GET[id]'";
        mysqli_query($link,$sql_sua);
        header('location:../../index.php?action=quanlyhang&query=them');
    }

     // Check if delete button active, start this 
     elseif(isset($_POST['delete']))
     {
        
        $smt_check = array();
        $smt_check = count($_POST['ckcl']);
         for($i=0;$i< $smt_check;$i++){
             $del_id = $_POST['ckcl'][$i];
             $sql = "DELETE FROM hang WHERE id_hang ='".$del_id."'";
             $result = mysqli_query($link, $sql);
         }
         // if successful redirect to delete_multiple.php 
         if($result){
            header('location:../../index.php?action=quanlyhang&query=them');
        }
        }
        //tim kiem 
        elseif(isset($_POST['name']))
        {
            $name = $_POST['name'];
            $sql_timkiem = "SELECT * FROM hang WHERE hang.tenhang like '%".$name."%'";
            $query_timkiem = mysqli_query($link,$sql_timkiem);
            $data = '';
            $stt  = 0;
            while ($row=mysqli_fetch_array($query_timkiem)) 
            {
                $stt = $stt + 1;
                $data ="
                <tr>
                <td>  $stt</td>
                <td>".$row['tenhang']."</td>
                <td><a href='?action=quanlyhang&query=sua&id=".$row['id_hang']."' ><i class='fa-solid fa-pen-to-square'></i></a></td>
                <td class='echo $stt'><input name='ckcl[]' type='checkbox'  value='".$row['id_hang']."'class='Organization_Desg_Check_margin' id='OrganizationDesgCheckData1  $stt '></td>
            </tr>
            ";
            }
            echo $data;
            
             mysqli_close($link);
        }
         
?>
<?php
    include ('../../ketnoi/conndb.php');
    
        if(isset($_POST['themdiadiem']))
    {  $codejs = $_POST['themdiadiem'];
        $tendiadiem = $_POST['diadiem'];
        $querycheck = mysqli_query($link , "SELECT * FROM diadiem WHERE tendiadiem = '$tendiadiem'");
        if(mysqli_num_rows($querycheck) > 0)
        {          
            include"loi.php";
        }
        else {
            $sql_them = "INSERT INTO `diadiem` ( `tendiadiem`) VALUES ('".$tendiadiem."')";
            mysqli_query($link,$sql_them);
            header('location:../../index.php?action=quanlydiadiem&query=them');
        }
        echo '
            <script>
            document.getElementById("txtdiadiem").focus();
            </script>


        ';
        
    }
    elseif(isset($_POST['Suadiadiem']))
    {
        $tendiadiem = $_POST['diadiem'];
        $sql_sua = "UPDATE  `diadiem` SET tendiadiem='".$tendiadiem."' WHERE id_diadiem = '$_GET[id]'";
        mysqli_query($link,$sql_sua);
        header('location:../../index.php?action=quanlydiadiem&query=them');
    }

     // Check if delete button active, start this 
     elseif(isset($_POST['delete']))
     {
        
        $smt_check = array();
        $smt_check = count($_POST['ckcl']);
         for($i=0;$i< $smt_check;$i++){
             $del_id = $_POST['ckcl'][$i];
             $sql = "DELETE FROM diadiem WHERE id_diadiem ='".$del_id."'";
             $result = mysqli_query($link, $sql);
         }
         // if successful redirect to delete_multiple.php 
         if($result){
            header('location:../../index.php?action=quanlydiadiem&query=them');
        }
        }
        //tim kiem 
        elseif(isset($_POST['name']))
        {
            $name = $_POST['name'];
            $sql_timkiem = "SELECT * FROM diadiem WHERE diadiem.tendiadiem like '%".$name."%'";
            $query_timkiem = mysqli_query($link,$sql_timkiem);
            $data = '';
            $stt  = 0;
            while ($row=mysqli_fetch_array($query_timkiem)) 
            {
                $stt = $stt + 1;
                $data ="
                <tr>
                <td>  $stt</td>
                <td>".$row['tendiadiem']."</td>
                <td><a href='?action=quanlydiadiem&query=sua&id=".$row['id_diadiem']."' ><i class='fa-solid fa-pen-to-square'></i></a></td>
                <td class='echo $stt'><input name='ckcl[]' type='checkbox'  value='".$row['id_diadiem']."'class='Organization_Desg_Check_margin' id='OrganizationDesgCheckData1  $stt '></td>
            </tr>
            ";
            }
            echo $data;
            
             mysqli_close($link);
        }
         
?>
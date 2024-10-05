<?PHP
	//MO VA KNCSDL
	$link = mysqli_connect("localhost", "root", "", "banhangviet");
	//Xac lop font chu
	mysqli_set_charset($link, "UTF8");
	//Kiem tra viec KN Thanh Cong Khong
	//---------Kiem Tra Ket Noi -----------  
//    if (mysqli_connect_errno())
//    {
//    	echo "Ket Noi Khong Thanh Cong " . mysqli_connect_error();
//    }
/*
	if($link)
		echo "Ket Noi Thanh Cong !" ;
	else
		echo "Ket Noi Khong Thanh Cong !" ;
		exit;
*/
	if(!$link)
	{
		echo "Ket Noi Khong Thanh Cong !" ;
		exit;
	}
?>
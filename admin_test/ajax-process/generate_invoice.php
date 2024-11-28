<?php
require_once('../fpdf186/fpdf.php');
require_once('../tfpdf/tfpdf.php');
include "../ketnoi/conndb.php"; // Kết nối cơ sở dữ liệu

// Khởi tạo đối tượng tFPDF
$pdf = new tFPDF();
$pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf', true); // Thêm font
$pdf->SetFont('DejaVu', '', 14);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy id_hdb từ POST và kiểm tra
    $id_hdb = intval($_POST['id_hdb']);
    if (empty($id_hdb)) {
        echo "<script>alert('Không nhận id')</script>";
        exit();
    }

    // Truy vấn thông tin hóa đơn và khách hàng
    $orderQuery = $link->prepare("SELECT HDB.id_hdb, HDB.created_at, HDB.TrangThai, HDB.ThanhToan, 
                                kh.Ten_kh, kh.Email_kh, kh.SDT_kh 
                                FROM HDB 
                                JOIN Khachhang kh ON HDB.id_kh = kh.id_kh 
                                WHERE HDB.id_hdb = ?");
    $orderQuery->bind_param("i", $id_hdb);
    $orderQuery->execute();
    $orderResult = $orderQuery->get_result();
    if ($orderResult->num_rows === 0) {
        die("Không tìm thấy hóa đơn.");
    }
    $order = $orderResult->fetch_assoc();

    // Truy vấn chi tiết hóa đơn
    $itemsQuery = $link->prepare("SELECT CT_HDB.SoLuong, CT_HDB.DonGia, CT_HDB.ThanhTien, 
                                  SanPham.Ten_sp, DonVi.Ten_dv 
                                  FROM CT_HDB 
                                  JOIN SanPham ON CT_HDB.id_sp = SanPham.id_sp 
                                  JOIN DonVi ON CT_HDB.id_dv = DonVi.id_dv 
                                  WHERE CT_HDB.id_hdb = ?");
    $itemsQuery->bind_param("i", $id_hdb);
    $itemsQuery->execute();
    $itemsResult = $itemsQuery->get_result();

    // Thêm thông tin hóa đơn vào PDF
    $pdf->AddPage();

    // Chèn hình ảnh cửa hàng vào tiêu đề
    $pdf->Image('../uploads/a1-1-350x250.jpg', 10, 6, 30); // Đường dẫn, tọa độ X, Y, chiều rộng (cao tự động theo tỷ lệ)
    
    // Thêm tên cửa hàng
    $pdf->SetFont('DejaVu', '', 16);
    $pdf->Cell(0, 10, 'Cửa Hàng XYZ', 0, 1, 'C');
    $pdf->SetFont('DejaVu', '', 14);
    $pdf->Cell(0, 10, 'Hóa Đơn Mua Hàng', 0, 1, 'C');
    $pdf->Ln(20);

    // Thông tin khách hàng và hóa đơn
    $pdf->Cell(95, 10, 'Tên khách hàng: ' . $order['Ten_kh'], 0, 0);
    $pdf->Cell(95, 10, 'Email: ' . $order['Email_kh'], 0, 1);
    $pdf->Cell(95, 10, 'Số điện thoại: ' . $order['SDT_kh'], 0, 1);
    $pdf->Cell(95, 10, 'Ngày tạo: ' . $order['created_at'], 0, 1);
    $pdf->Cell(95, 10, 'Trạng thái: ' . ($order['TrangThai'] == 1 ? 'Đang vận chuyển' : ($order['TrangThai'] == 0 ? 'Đã nhận hàng' : 'Chưa nhận hàng')), 0, 1);
    $pdf->Cell(95, 10, 'Thanh toán: ' . ($order['ThanhToan'] == 0 ? 'Đã thanh toán tiền mặt' : ($order['ThanhToan'] == 1 ? 'Đã thanh toán Chuyển khoản' :'Chưa thanh toán')), 0, 1);
    $pdf->Ln(5);

    // Thêm bảng chi tiết sản phẩm vào PDF
    $pdf->SetFont('DejaVu', '', 14);

    // Thêm nền cho hàng tiêu đề
    $pdf->SetFillColor(200, 220, 255); // Màu nền tiêu đề
    $pdf->Cell(15, 10, 'STT', 1, 0, 'C', true);
    $pdf->Cell(60, 10, 'Tên sản phẩm', 1, 0, 'C', true);
    $pdf->Cell(40, 10, 'Kích thước', 1, 0, 'C', true);
    $pdf->Cell(30, 10, 'Số lượng', 1, 0, 'C', true);
    $pdf->Cell(30, 10, 'Đơn giá', 1, 0, 'C', true);
    $pdf->Cell(30, 10, 'Thành tiền', 1, 1, 'C', true);

    // Hiển thị thông tin từng sản phẩm trong hóa đơn
   // Hiển thị thông tin từng sản phẩm trong hóa đơn
   $index = 1;
   while ($item = $itemsResult->fetch_assoc()) {
       $startX = $pdf->GetX();
       $startY = $pdf->GetY();
       $cellHeight = 10; // Chiều cao mặc định của dòng
   
       // Tính chiều cao thực tế của dòng dựa trên MultiCell
       $pdf->SetFont('DejaVu', '', 14);
       $pdf->SetXY($startX + 15, $startY); // Di chuyển đến vị trí của cột tên sản phẩm
       $pdf->MultiCell(60, $cellHeight, $item['Ten_sp'], 0, 'L', false);
       $currentY = $pdf->GetY();
       $rowHeight = max($currentY - $startY, $cellHeight); // Đảm bảo chiều cao dòng không nhỏ hơn mặc định
   
       // Quay lại vị trí ban đầu và vẽ các ô còn lại
       $pdf->SetXY($startX, $startY);
   
       // STT
       $pdf->Cell(15, $rowHeight, $index++, 1, 0, 'C');
   
       // Tên sản phẩm (vẽ khung nhưng không in thêm nội dung)
       $pdf->SetXY($startX + 15, $startY); // Đảm bảo không ghi đè
       $pdf->Cell(60, $rowHeight, '', 1, 0, 'L');
   
       // Kích thước
       $pdf->SetXY($startX + 75, $startY);
       $pdf->Cell(40, $rowHeight, $item['Ten_dv'], 1, 0, 'C');
   
       // Số lượng
       $pdf->SetXY($startX + 115, $startY);
       $pdf->Cell(30, $rowHeight, $item['SoLuong'], 1, 0, 'C');
   
       // Đơn giá
       $pdf->SetXY($startX + 145, $startY);
       $pdf->Cell(30, $rowHeight, number_format($item['DonGia'], 0, ',', '.') . ' VND', 1, 0, 'C');
   
       // Thành tiền
       $pdf->SetXY($startX + 175, $startY);
       $pdf->Cell(30, $rowHeight, number_format($item['ThanhTien'], 0, ',', '.') . ' VND', 1, 1, 'C');
   }
   

    // Tính tổng tiền
    $totalQuery = $link->prepare("SELECT SUM(ThanhTien) as total_amount FROM CT_HDB WHERE id_hdb = ?");
    $totalQuery->bind_param("i", $id_hdb);
    $totalQuery->execute();
    $totalResult = $totalQuery->get_result();
    $totalAmount = $totalResult->fetch_assoc()['total_amount'];

    // Thêm tổng tiền vào PDF
    $pdf->Cell(175, 10, 'Tổng tiền:', 1, 0, 'R');
    $pdf->Cell(30, 10, number_format($totalAmount, 0, ',', '.') . ' VND', 1, 1, 'C');

    // Xuất PDF
    $pdf->Output('D', 'hoadonban_' . $id_hdb . '.pdf'); // Tải xuống file PDF
}
?>

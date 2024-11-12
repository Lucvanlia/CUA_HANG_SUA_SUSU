<?php
header('Content-Type: application/json'); // Đặt header JSON

// Kết nối cơ sở dữ liệu
$conn = new mysqli('localhost', 'root', '', 'banhangviet');
if ($conn->connect_error) {
    die(json_encode(["error" => "Kết nối thất bại: " . $conn->connect_error]));
}

// Xác định số bài viết trên mỗi trang và trang hiện tại
if(isset($_POST['page']))
{

$posts_per_page = 4;
$page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
$offset = ($page - 1) * $posts_per_page;

// Truy vấn bài viết
$sql = "SELECT * FROM tintuc ORDER BY created_at DESC LIMIT $offset, $posts_per_page";
$result = $conn->query($sql);

if (!$result) {
    die(json_encode(["error" => "Lỗi truy vấn: " . $conn->error]));
}

$posts = [];
while ($row = $result->fetch_assoc()) {
    $posts[] = $row;
}

// Tính tổng số trang
$total_posts_result = $conn->query("SELECT COUNT(*) as total FROM tintuc");
$total_posts = $total_posts_result ? $total_posts_result->fetch_assoc()['total'] : 0;
$total_pages = ceil($total_posts / $posts_per_page);

// Trả về dữ liệu dạng JSON
echo json_encode([
    'posts' => $posts,
    'total_pages' => $total_pages,
]);
exit;
}

if(isset($_POST['query']))
{
    $query = isset($_POST['query']) ? $_POST['query'] : '';

    $sql = "SELECT * FROM tintuc WHERE Title LIKE ? OR NoiDung LIKE ?";
    $stmt = $conn->prepare($sql);
    $searchQuery = '%' . $query . '%';
    $stmt->bind_param("ss", $searchQuery, $searchQuery);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $posts = [];
    while ($row = $result->fetch_assoc()) {
        $posts[] = $row;
    }
    
    echo json_encode(['posts' => $posts]);
    exit;
}
?>

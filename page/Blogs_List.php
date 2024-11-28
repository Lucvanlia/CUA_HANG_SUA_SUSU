<section class="blog spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-5">
                <div class="blog__sidebar">
                    <div class="blog__sidebar__search">
                        <form id="search-form" action="#">
                            <input type="text" id="search-input" placeholder="Search...">
                        </form>
                    </div>

                    <?php
                    // Kết nối đến cơ sở dữ liệu
                    $link = new mysqli('localhost', 'root', '', 'banhangviet');
                    if ($link->connect_error) {
                        die("Kết nối thất bại: " . $link->connect_error);
                    }

                    // Lấy tất cả các loại tin tức
                    $sql = "SELECT id_ltt, Ten_ltt, id_pr FROM loaitintuc";
                    $result = $link->query($sql);

                    $categories = [];
                    while ($row = $result->fetch_assoc()) {
                        $categories[] = $row;
                    }

                    // Hàm đệ quy để xây dựng mảng phân cấp
                    function buildTree($categories, $parent_id = 0)
                    {
                        $branch = [];
                        foreach ($categories as $category) {
                            if ($category['id_pr'] == $parent_id) {
                                $children = buildTree($categories, $category['id_ltt']);
                                if ($children) {
                                    $category['children'] = $children;
                                }
                                $branch[] = $category;
                            }
                        }
                        return $branch;
                    }

                    // Xây dựng cây phân cấp
                    $category_tree = buildTree($categories);

                    // Hàm hiển thị menu với CSS và JS
                    function showMenu($category_tree)
                    {
                        echo '<ul class="menu">';
                        foreach ($category_tree as $category) {
                            echo '<li>';
                            echo '<a href="?action=blog&query=all&id=' . $category['id_ltt'] . '">' . $category['Ten_ltt'] . '</a>';
                            // Kiểm tra nếu có danh mục con
                            if (!empty($category['children'])) {
                                echo '<button class="toggle-btn">+</button>';
                                echo '<ul class="submenu">';
                                showMenu($category['children']);
                                echo '</ul>';
                            }
                            echo '</li>';
                        }
                        echo '</ul>';
                    }
                    ?>

                    <!-- HTML Menu -->
                    <div class="blog__sidebar__item">
                        <h4>Thể loại</h4>
                        <ul>
                            <li><a href="?action=blog&query=all">Tất cả</a></li>
                            <?php showMenu($category_tree); ?>
                        </ul>
                    </div>
                    <div class="blog__sidebar__item">
                        <h4>Tin tức gần đây </h4>
                        <div class="blog__sidebar__recent">
                            <a href="#" class="blog__sidebar__recent__item">
                                <div class="blog__sidebar__recent__item__pic">
                                    <img src="img/blog/sidebar/sr-1.jpg" alt="" class="img-thumbnail">
                                </div>
                                <div class="blog__sidebar__recent__item__text">
                                    <h6>Dâu đầu mùa<br> Giá rẻ bất ngờ</h6>
                                    <span>24 tháng 5 năm 2024</span>
                                </div>
                            </a>
                            <a href="#" class="blog__sidebar__recent__item">
                                <div class="blog__sidebar__recent__item__pic">
                                    <img src="img/blog/sidebar/sr-2.jpg" alt="">
                                </div>
                                <div class="blog__sidebar__recent__item__text">
                                    <h6>Mẹo mua rau tươi ở <br> Cửa hàng hoặc chợ</h6>
                                    <span>24 tháng 5 năm 2024</span>
                                </div>
                            </a>
                            <a href="#" class="blog__sidebar__recent__item">
                                <div class="blog__sidebar__recent__item__pic">
                                    <img src="img/blog/sidebar/sr-3.jpg" alt="">
                                </div>
                                <div class="blog__sidebar__recent__item__text">
                                    <h6>Ăn trái cây mỗi ngày giúp<br>duy trì sức khỏe</h6>
                                    <span>24 tháng 5 năm 2024</span>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="blog__sidebar__item">
                        <h4>Top tìm kiếm</h4>
                        <div class="blog__sidebar__item__tags">
                            <a href="#">Táo</a>
                            <a href="#">Sức khỏe</a>
                            <a href="#">Rau tươi</a>
                            <a href="#">Thịt cá</a>
                            <a href="#">Giò heo</a>
                            <a href="#">Cá tươi</a>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            // Kết nối đến cơ sở dữ liệu$
            // Xác định loại bài viết dựa trên tham số URL
            $id = isset($_GET['id']) ? (int)$_GET['id'] : null;

            // Truy vấn bài viết dựa trên `id` hoặc lấy tất cả bài viết nếu không có `id`
            if ($id) {
                $sql = "SELECT * FROM tintuc WHERE id_sp = ?";
                $stmt = $link->prepare($sql);
                $stmt->bind_param("i", $id);
            } else {
                $sql = "SELECT * FROM tintuc";
                $stmt = $link->prepare($sql);
            }

            $stmt->execute();
            $result = $stmt->get_result();
            ?>

            <!-- HTML Hiển thị bài viết -->
            <div class="col-lg-8 col-md-7">
                <div id="blog-container" class="row">
                    <!-- AJAX sẽ tải nội dung bài viết vào đây -->
                </div>

                <!-- Khu vực phân trang -->
                <div class="col-lg-12">
                    <div class="product__pagination blog__pagination" id="pagination">
                        <!-- Nút phân trang sẽ được tạo động bằng JavaScript -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<style>
    body a {
        text-decoration: none;
    }

    .menu,
    .submenu {
        list-style-type: none;
        padding: 0;
        margin: 0;
    }

    .menu>li {
        padding: 8px;
        position: relative;
    }

    .menu>li>a {
        color: #333;
    }

    .submenu {
        max-height: 0;
        /* Ẩn các submenu mặc định */
        overflow: hidden;
        transition: max-height 0.3s ease;
        /* Thêm chuyển tiếp mượt mà */
        padding-left: 15px;
    }

    .toggle-btn {
        background: none;
        border: none;
        cursor: pointer;
        color: #333;
        font-weight: bold;
        margin-left: 5px;
    }
</style>
<!-- Related Blog Section End --><!-- Bao gồm Bootstrap CSS và JS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const toggleButtons = document.querySelectorAll(".toggle-btn");

        toggleButtons.forEach(button => {
            button.addEventListener("click", function() {
                const submenu = this.nextElementSibling;

                // Kiểm tra trạng thái mở/đóng của submenu
                if (submenu.style.maxHeight && submenu.style.maxHeight !== "0px") {
                    submenu.style.maxHeight = "0";
                    this.textContent = "+"; // Đổi nút thành dấu "+"
                } else {
                    submenu.style.maxHeight = submenu.scrollHeight + "px"; // Mở rộng tới chiều cao tự nhiên
                    this.textContent = "-"; // Đổi nút thành dấu "-"
                }
            });
        });
    });
    document.addEventListener("DOMContentLoaded", function() {
        function loadPosts(page = 1) {
            fetch("fetch_blog.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: `page=${page}`
                })
                .then(response => response.json())
                .then(data => {
                    const blogContainer = document.getElementById("blog-container");
                    const pagination = document.getElementById("pagination");

                    blogContainer.innerHTML = "";
                    pagination.innerHTML = "";

                    // Hiển thị bài viết
                    data.posts.forEach(post => {
                        const postHTML = `
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <div class="blog__item">
                            <div class="blog__item__pic">
                                <img src="admin_test/modul/uploads/${post.HinhAnh}" alt="${post.Title}" class="img-pic"> 
                            </div>
                            <div class="blog__item__text">
                                <ul>
                                    <li><i class="fa fa-calendar-o"></i> ${new Date(post.created_at).toLocaleDateString()}</li>
                                    <li><i class="fa fa-comment-o"></i> 100+</li>
                                </ul>
                                <h5><a href="?action=blog&query=detail&id_tt=${post.id_tt}">${post.Title}</a></h5>
                                <p>${post.NoiDung.substring(0, 50)}...</p>
                                <a href="?action=blog&query=detail&id_tt=${post.id_tt}" class="blog__btn">Xem ngay <span class="arrow_right"></span></a>
                            </div>
                        </div>
                    </div>
                `;
                        blogContainer.insertAdjacentHTML("beforeend", postHTML);
                    });

                    // Hiển thị nút "Previous" với icon
                    if (page > 1) {
                        pagination.insertAdjacentHTML("beforeend", `<a href="#" class="page-link" data-page="${page - 1}"><i class="fa fa-arrow-left"></i></a>`);
                    }

                    // Hiển thị các số trang
                    const total_pages = data.total_pages;
                    const max_display_pages = 10;
                    let start_page = Math.max(1, page - 1);
                    let end_page = Math.min(total_pages, start_page + max_display_pages - 1);

                    if (end_page - start_page < max_display_pages - 1) {
                        start_page = Math.max(1, end_page - max_display_pages + 1);
                    }

                    for (let i = start_page; i <= end_page; i++) {
                        const pageHTML = `<a href="#" class="page-link ${i === page ? 'active' : ''}" data-page="${i}">${i}</a>`;
                        pagination.insertAdjacentHTML("beforeend", pageHTML);
                    }

                    // Hiển thị nút "Next" với icon
                    if (page < total_pages) {
                        pagination.insertAdjacentHTML("beforeend", `<a href="#" class="page-link" data-page="${page + 1}"><i class="fa fa-arrow-right"></i></a>`);
                    }
                })
                .catch(error => console.error("Lỗi khi tải bài viết:", error));
        }

        document.getElementById("pagination").addEventListener("click", function(e) {
            if (e.target.classList.contains("page-link")) {
                e.preventDefault();
                const page = parseInt(e.target.getAttribute("data-page"));
                loadPosts(page);
            }
        });

        loadPosts();
    });
    document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.getElementById("search-input");
        const blogContainer = document.getElementById("blog-container");

        searchInput.addEventListener("input", function() {
            const query = searchInput.value.trim();

            fetch("fetch_blog.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: `query=${encodeURIComponent(query)}`
                })
                .then(response => response.json())
                .then(data => {
                    blogContainer.innerHTML = "";

                    // Hiển thị bài viết dựa trên kết quả tìm kiếm
                    if (data.posts.length > 0) {
                        data.posts.forEach(post => {
                            const postHTML = `
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="blog__item">
                                <div class="blog__item__pic">
                                    <img src="admin_test/modul/uploads/${post.HinhAnh}" alt="${post.Title}" class="img-pic">
                                </div>
                                <div class="blog__item__text">
                                    <ul>
                                        <li><i class="fa fa-calendar-o"></i> ${new Date(post.created_at).toLocaleDateString()}</li>
                                        <li><i class="fa fa-comment-o"></i> 100+</li>
                                    </ul>
                                    <h5><a href="?action=blog&query=detail&id_tt=${post.id_tt}">${post.Title}</a></h5>
                                    <p>${post.NoiDung.substring(0, 50)}...</p>
                                    <a href="?action=blog&query=detail&id_tt=${post.id_tt}" class="blog__btn">Xem ngay <span class="arrow_right"></span></a>
                                </div>
                            </div>
                        </div>
                    `;
                            blogContainer.insertAdjacentHTML("beforeend", postHTML);
                        });
                    } else {
                        blogContainer.innerHTML = "<p>Không có bài viết nào phù hợp với từ khóa.</p>";
                    }
                })
                .catch(error => console.error("Lỗi khi tìm kiếm bài viết:", error));
        });
    });
    $(document).ready(function () {
    $('#search-input').on('input', function () {
        const query = $(this).val().trim();

        $.ajax({
            url: 'fetch_blog.php',
            type: 'POST',
            data: { query: query },
            dataType: 'json',
            success: function (data) {
                const blogContainer = $('#blog-container');
                blogContainer.empty();

                if (data.posts.length > 0) {
                    $.each(data.posts, function (index, post) {
                        const postHTML = `
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="blog__item">
                                    <div class="blog__item__pic">
                                        <img src="admin_test/modul/uploads/${post.HinhAnh}" alt="${post.Title}" class="img-pic">
                                    </div>
                                    <div class="blog__item__text">
                                        <ul>
                                            <li><i class="fa fa-calendar-o"></i> ${new Date(post.created_at).toLocaleDateString()}</li>
                                            <li><i class="fa fa-comment-o"></i> 100+</li>
                                        </ul>
                                        <h5><a href="?action=blog&query=detail&id_tt=${post.id_tt}">${post.Title}</a></h5>
                                        <p>${post.NoiDung.substring(0, 50)}...</p>
                                        <a href="?action=blog&query=detail&id_tt=${post.id_tt}" class="blog__btn">Xem ngay <span class="arrow_right"></span></a>
                                    </div>
                                </div>
                            </div>
                        `;
                        blogContainer.append(postHTML);
                    });
                } else {
                    blogContainer.html("<p>Không có bài viết nào phù hợp với từ khóa.</p>");
                }
            },
            error: function (xhr, status, error) {
                console.error("Lỗi khi tìm kiếm bài viết:", error);
            }
        });
    });
});

</script>
<?php


?>
<?php

$sql_tintuc = mysqli_query($link, "SELECT * from tintuc limit 3 
    ");
?>

<section class="from-blog spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title from-blog__title">
                    <h2></h2>
                </div>
            </div>
        </div>
        <div class="row">
            <?php

            while ($row_tintuc = mysqli_fetch_assoc($sql_tintuc)) {


            ?>
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <div class="blog__item">
                        <div class="blog__item__pic">
                            <img src="admin_test/modul/uploads/<?= $row_tintuc['HinhAnh'] ?>" alt="">
                        </div>
                        <div class="blog__item__text">
                            <ul>
                                <li><i class="fa fa-calendar-o"></i> <?= $row_tintuc['created_at'] ?></li>
                                <li><i class="fa fa-comment-o"></i> 5</li>
                            </ul>
                            <h5><a href="?action=blog&query=detail&id=<?= $row_tintuc['id_tt']?>"><?= $row_tintuc['Title'] ?></a></h5>
                            <p> <?php
                                $noidung = explode(' ', $row_tintuc['NoiDung']);
                                if (count($noidung) > 50) {
                                    $noidung = array_slice($noidung, 0, 50);
                                    $noidung = implode(' ', $noidung) . '...';
                                } else {
                                    $noidung = $row_tintuc['NoiDung'];
                                }
                                echo $noidung;
                                ?></p>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
</section>
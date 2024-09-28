<?php
include_once 'dbConnection.php';
session_start();
if (!(isset($_SESSION['email']))) {
    header("location:dang_nhap.php");
} else {
    $name = $_SESSION['name'];
    $email = $_SESSION['email'];

    include_once 'dbConnection.php';
} ?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <title>BookSaw - Free Book Store HTML CSS Template</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="author" content="">
    <meta name="keywords" content="">
    <meta name="description" content="">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">

    <link rel="stylesheet" type="text/css" href="css/normalize.css">
    <link rel="stylesheet" type="text/css" href="icomoon/icomoon.css">
    <link rel="stylesheet" type="text/css" href="css/vendor.css">
    <link rel="stylesheet" type="text/css" href="style.css">

</head>

<body data-bs-spy="scroll" data-bs-target="#header" tabindex="0">

    <div id="header-wrap">

        <div class="top-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <div class="social-links">
                            <ul>
                                <li>
                                    <a href="#"><i class="icon icon-facebook"></i></a>
                                </li>
                                <li>
                                    <a href="#"><i class="icon icon-twitter"></i></a>
                                </li>
                                <li>
                                    <a href="#"><i class="icon icon-youtube-play"></i></a>
                                </li>
                                <li>
                                    <a href="#"><i class="icon icon-behance-square"></i></a>
                                </li>
                            </ul>
                        </div><!--social-links-->
                    </div>
                    <div class="col-md-6">
                        <div class="right-element">


                            <?php
                            echo '<span class="pull-right top title1" ><span class="log1"><i class="fa-solid fa-user"></i>&nbsp;&nbsp;&nbsp;&nbsp;Chào b?n,</span> <a href="index.html" class="log log1">' . $name . '</a>&nbsp;|&nbsp;<a href="logout.php?q=account.php" class="log"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span>&nbsp;Ðang xu?t</button></a></span>';
                            ?>


                            <div class="action-menu">

                                <div class="search-bar">
                                    <a href="#" class="search-button search-toggle" data-selector="#header-wrap">
                                        <i class="icon icon-search"></i>
                                    </a>
                                    <form role="search" method="get" class="search-box">
                                        <input class="search-field text search-input" placeholder="Search"
                                            type="search">
                                    </form>
                                </div>
                            </div>

                        </div><!--top-right-->
                    </div>

                </div>
            </div>
        </div><!--top-content-->

        <header id="header">
            <div class="container-fluid">
                <div class="row">

                    <div class="col-md-2">
                        <div class="main-logo">
                            <a href="index.php">
                                <h2>LibFlow</h2>
                            </a>
                        </div>

                    </div>

                    <div class="col-md-10">

                        <nav id="navbar">
                            <div class="main-menu stellarnav">
                                <ul class="menu-list">
                                    <li class="menu-item active"><a href="#home">Trang ch?</a></li>
                                    <li class="menu-item has-sub">
                                        <a href="#pages" class="nav-link">Sách</a>

                                        <ul>

                                            <li><a href="index.html">Mượn sách</a></li>
                                            <li><a href="index.html">Trả sách</a></li>
                                            <li><a href="index.html">Sách điện tử (ebook)</a></li>

                                        </ul>

                                    </li>
                                    <li class="menu-item"><a href="#featured-books" class="nav-link">Mục yêu thích tôi</a></li>
                                    <li class="menu-item"><a href="#popular-books" class="nav-link">SÁCH NÓI</a></li>
                                    <li class="menu-item"><a href="#special-offer" class="nav-link">gợi ý đọc sách nên đọc bằng AI</a></li>

                                    <li class="menu-item"><a href="#download-app" class="nav-link">dóng góp</a></li>
                                </ul>

                                <div class="hamburger">
                                    <span class="bar"></span>
                                    <span class="bar"></span>
                                    <span class="bar"></span>
                                </div>

                            </div>
                        </nav>

                    </div>

                </div>
            </div>
        </header>

    </div><!--header-wrap-->


    <!-- Form mượn sách -->
    <main id="main" class="main">
        <div class="pdf-container">
            <canvas id="pdf-render"></canvas>
        </div>

        <div class="controls">
            <button id="prev-page">Trang trước</button>
            <span id="page-info">Trang <span id="page-num"></span> / <span id="page-count"></span></span>
            <button id="next-page">Trang sau</button>
        </div>
        <div class="zoom-controls">
        <button id="zoom-in">Phóng to</button>
        <button id="zoom-out">Thu nhỏ</button>
    </div>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>
    </main><!-- End #main -->
    <script>
        // URL của tệp PDF
        const url = 'Tin Hoc 11 THUD.pdf';

        // Khởi tạo PDFJS
let pdfDoc = null,
    pageNum = 1,
    pageIsRendering = false,
    pageNumIsPending = null;

let scale = 1.5;  // Độ phóng to/thu nhỏ trang PDF ban đầu
const canvas = document.getElementById('pdf-render'),
      ctx = canvas.getContext('2d');

// Render trang PDF
const renderPage = num => {
    pageIsRendering = true;

    // Lấy trang
    pdfDoc.getPage(num).then(page => {
        const viewport = page.getViewport({ scale });
        canvas.height = viewport.height;
        canvas.width = viewport.width;

        const renderCtx = {
            canvasContext: ctx,
            viewport
        };

        page.render(renderCtx).promise.then(() => {
            pageIsRendering = false;

            if (pageNumIsPending !== null) {
                renderPage(pageNumIsPending);
                pageNumIsPending = null;
            }
        });

        // Cập nhật số trang
        document.getElementById('page-num').textContent = num;
    });
};

// Kiểm tra số trang đang chờ
const queueRenderPage = num => {
    if (pageIsRendering) {
        pageNumIsPending = num;
    } else {
        renderPage(num);
    }
};

// Hiển thị trang trước
const showPrevPage = () => {
    if (pageNum <= 1) {
        return;
    }
    pageNum--;
    queueRenderPage(pageNum);
};

// Hiển thị trang sau
const showNextPage = () => {
    if (pageNum >= pdfDoc.numPages) {
        return;
    }
    pageNum++;
    queueRenderPage(pageNum);
};

// Phóng to PDF
const zoomIn = () => {
    scale += 0.2;  // Tăng scale
    queueRenderPage(pageNum);  // Render lại trang hiện tại
};

// Thu nhỏ PDF
const zoomOut = () => {
    if (scale > 0.4) {  // Đảm bảo không thu nhỏ quá mức
        scale -= 0.2;  // Giảm scale
        queueRenderPage(pageNum);  // Render lại trang hiện tại
    }
};

// Lấy tệp PDF
pdfjsLib.getDocument(url).promise.then(pdfDoc_ => {
    pdfDoc = pdfDoc_;
    document.getElementById('page-count').textContent = pdfDoc.numPages;

    renderPage(pageNum);
});

// Các sự kiện nút điều hướng trang
document.getElementById('prev-page').addEventListener('click', showPrevPage);
document.getElementById('next-page').addEventListener('click', showNextPage);

// Các sự kiện nút phóng to/thu nhỏ
document.getElementById('zoom-in').addEventListener('click', zoomIn);
document.getElementById('zoom-out').addEventListener('click', zoomOut);
    </script>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        h1 {
            text-align: center;
        }
        #pdf-render {
            display: block;
            margin: 20px auto;
            border: 1px solid black;
        }
        .controls {
            text-align: center;
            margin-top: 20px;
        }
        .controls button {
            padding: 10px 20px;
            margin: 0 10px;
            font-size: 16px;
        }
        .zoom-controls {
            text-align: center;
            margin-top: 10px;
        }
    </style>

    <footer id="footer">
        <div class="container">
            <div class="row">

                <div class="col-md-4">

                    <div class="footer-item">
                        <div class="company-brand">
                            <h2>LibFlow</h2>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sagittis sed ptibus liberolectus
                                nonet psryroin. Amet sed lorem posuere sit iaculis amet, ac urna. Adipiscing fames
                                semper erat ac in suspendisse iaculis.</p>
                        </div>
                    </div>

                </div>

                <div class="col-md-2">

                    <div class="footer-menu">
                        <h5>About Us</h5>
                        <ul class="menu-list">
                            <li class="menu-item">
                                <a href="#">vision</a>
                            </li>
                            <li class="menu-item">
                                <a href="#">articles </a>
                            </li>
                            <li class="menu-item">
                                <a href="#">careers</a>
                            </li>
                            <li class="menu-item">
                                <a href="#">service terms</a>
                            </li>
                            <li class="menu-item">
                                <a href="#">donate</a>
                            </li>
                        </ul>
                    </div>

                </div>
                <div class="col-md-2">

                    <div class="footer-menu">
                        <h5>Discover</h5>
                        <ul class="menu-list">
                            <li class="menu-item">
                                <a href="#">Home</a>
                            </li>
                            <li class="menu-item">
                                <a href="#">Books</a>
                            </li>
                            <li class="menu-item">
                                <a href="#">Authors</a>
                            </li>
                            <li class="menu-item">
                                <a href="#">Subjects</a>
                            </li>
                            <li class="menu-item">
                                <a href="#">Advanced Search</a>
                            </li>
                        </ul>
                    </div>

                </div>
                <div class="col-md-2">

                    <div class="footer-menu">
                        <h5>My account</h5>
                        <ul class="menu-list">
                            <li class="menu-item">
                                <a href="#">Sign In</a>
                            </li>
                            <li class="menu-item">
                                <a href="#">View Cart</a>
                            </li>
                            <li class="menu-item">
                                <a href="#">My Wishtlist</a>
                            </li>
                            <li class="menu-item">
                                <a href="#">Track My Order</a>
                            </li>
                        </ul>
                    </div>

                </div>
                <div class="col-md-2">

                    <div class="footer-menu">
                        <h5>Help</h5>
                        <ul class="menu-list">
                            <li class="menu-item">
                                <a href="#">Help center</a>
                            </li>
                            <li class="menu-item">
                                <a href="#">Report a problem</a>
                            </li>
                            <li class="menu-item">
                                <a href="#">Suggesting edits</a>
                            </li>
                            <li class="menu-item">
                                <a href="#">Contact us</a>
                            </li>
                        </ul>
                    </div>

                </div>

            </div>
            <!-- / row -->

        </div>
    </footer>

    <div id="footer-bottom">
        <div class="container">
            <div class="row">
                <div class="col-md-12">

                    <div class="copyright">
                        <div class="row">

                            <div class="col-md-6">
                                <p>© 2022 All rights reserved. Free HTML Template by <a
                                        href="https://www.templatesjungle.com/" target="_blank">TemplatesJungle</a></p>
                            </div>

                            <div class="col-md-6">
                                <div class="social-links align-right">
                                    <ul>
                                        <li>
                                            <a href="#"><i class="icon icon-facebook"></i></a>
                                        </li>
                                        <li>
                                            <a href="#"><i class="icon icon-twitter"></i></a>
                                        </li>
                                        <li>
                                            <a href="#"><i class="icon icon-youtube-play"></i></a>
                                        </li>
                                        <li>
                                            <a href="#"><i class="icon icon-behance-square"></i></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                        </div>
                    </div><!--grid-->

                </div><!--footer-bottom-content-->
            </div>
        </div>
    </div>

    <script src="js/jquery-1.11.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm"
        crossorigin="anonymous"></script>
    <script src="js/plugins.js"></script>
    <script src="js/script.js"></script>

</body>

</html>
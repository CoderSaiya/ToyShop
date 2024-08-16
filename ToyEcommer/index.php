<?php
require_once("./config/product.config.php");
$count  = 0;

if (isset($_COOKIE['username'])) {
  $username = $_COOKIE['username'];

  $stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $stmt->bind_result($user_id);
  $stmt->fetch();
  $stmt->close();

  $countSql = "SELECT COUNT(*) as count FROM cart WHERE user_id = ?";
  $stmt = $conn->prepare($countSql);
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $stmt->bind_result($count);
  $stmt->fetch();
  $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <!-- Boxicons -->
  <link
    href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css"
    rel="stylesheet" />
  <!-- Glide js -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Glide.js/3.4.1/css/glide.core.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Glide.js/3.4.1/css/glide.theme.css">
  <!-- Custom StyleSheet -->
  <link rel="stylesheet" href="./css/style.css" />
  <title>Toyshop Lập trình web</title>
</head>

<body>
  <!-- Header -->
  <header class="header" id="header">
    <!-- Top Nav -->
    <div class="top-nav">
      <div class="container d-flex">
        <p>Đặt hàng online qua: (+84) 090.090.090</p>
        <ul class="d-flex">
          <li><a href="#">Về chúng tôi</a></li>
          <li><a href="#">FAQ</a></li>
          <li><a href="#">Liện hệ</a></li>
        </ul>
      </div>
    </div>
    <div class="navigation">
      <div class="nav-center container d-flex">
        <a href="/" class="logo">
          <img src="./images/main-logo.png" alt="">
        </a>

        <ul class="nav-list d-flex">
          <li class="nav-item">
            <a href="/" class="nav-link">Trang chủ</a>
          </li>
          <li class="nav-item">
            <a href="./product.php" class="nav-link">Sản phẩm</a>
          </li>
          <li class="nav-item">
            <a href="#terms" class="nav-link">Điều khoản</a>
          </li>
          <li class="nav-item">
            <a href="#about" class="nav-link">Về chúng tôi</a>
          </li>
          <li class="nav-item">
            <a href="#contact" class="nav-link">Liên hệ</a>
          </li>
          <li class="icons d-flex">
            <a href="./profile.php" class="icon" id="user-icon">
              <i class="bx bx-user"></i>
            </a>
            <div class="icon">
              <i class="bx bx-search"></i>
            </div>
            <div class="icon">
              <i class="bx bx-heart"></i>
              <span class="d-flex">0</span>
            </div>
            <a onclick="handelLoadCart()" class="icon">
              <i class="bx bx-cart"></i>
              <span class="d-flex"><?php echo $count ?></span>
            </a>
          </li>
        </ul>

        <div class="icons d-flex">
          <div class="icon" id="user-icon">
            <a id="user-link" href="./profile.php">
              <i class="bx bx-user"></i>
            </a>
            <div id="user-menu" class="user-menu">
              <ul>
                <li><a href="./profile.php">Chỉnh sửa thông tin</a></li>
                <li><a onclick="handleLogout()">Đăng xuất</a></li>
              </ul>
            </div>
          </div>
          <div class="icon">
            <i class="bx bx-search"></i>
          </div>
          <div class="icon">
            <i class="bx bx-heart"></i>
            <span class="d-flex">0</span>
          </div>
          <a onclick="handelLoadCart()" class="icon">
            <i class="bx bx-cart"></i>
            <span class="d-flex"><?php echo $count ?></span>
          </a>
        </div>

        <div class="hamburger">
          <i class="bx bx-menu-alt-left"></i>
        </div>
      </div>
    </div>

    <div class="hero">
      <div class="glide" id="glide_1">
        <div class="glide__track" data-glide-el="track">
          <ul class="glide__slides">
            <li class="glide__slide">
              <div class="center">
                <div class="left">
                  <span class="">Sản phẩm HOT 2024</span>
                  <h1 class="">SẢN PHẨM MỚI</h1>
                  <p>kkkkkkkkkkkkkkkkkkkkkkkkk</p>
                  <a href="#" class="hero-btn">ĐI ĐẾN SHOP</a>
                </div>
                <div class="right">
                  <img class="img1" src="./images/hero-item1.png" alt="">
                </div>
              </div>
            </li>
            <li class="glide__slide">
              <div class="center">
                <div class="left">
                  <span>Sản phẩm HOT 2024</span>
                  <h1>SẢN PHẨM MUA NHIỀU NHẤT</h1>
                  <p>kkkkkkkkkkkkkkkkkkkkkkkkk</p>
                  <a href="#" class="hero-btn">ĐI ĐẾN SHOP</a>
                </div>
                <div class="right">
                  <img class="img2" src="./images/hero-item2.png" alt="">
                </div>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </header>

  <!-- Categories Section -->
  <section class="section category">
    <div class="cat-center">
      <div class="cat">
        <img src="./images/cat-item3.png" alt="" />
        <div>
          <p>ĐIỀU KHIỂN TỪ XA</p>
        </div>
      </div>
      <div class="cat">
        <img src="./images/cat-item2.png" alt="" />
        <div>
          <p>XẾP HÌNH</p>
        </div>
      </div>
      <div class="cat">
        <img src="./images/cat-item1.png" alt="" />
        <div>
          <p>LEGO</p>
        </div>
      </div>
    </div>
  </section>

  <!-- New Arrivals -->
  <section class="section new-arrival">
    <div class="title">
      <h1>SẢN PHẨM MỚI</h1>
      <p>Tất cả sản phẩm mới đăng</p>
    </div>

    <div class="product-center">
      <?php foreach ($newList as $product) : ?>
        <div class="product-item">
          <div class="overlay">
            <a href="productDetails.html" class="product-thumb">
              <img src="./images/<?php echo $product['image_url'] ?>" alt="" />
            </a>
            <span class="discount"><?php $random = random_int(1, 80);
                                    echo $random; ?>%</span>
          </div>
          <div class="product-info">
            <span><?php echo $product['category']; ?></span>
            <a href="productDetails.php?id=<?php echo $product['product_id']; ?>"><?php echo $product['name']; ?></a>
            <h4><?php echo number_format(((100 - $random) / 100 * $product['price']), 0, ",", ".") ?> đ</h4>
          </div>
          <ul class="icons">
            <li><i class="bx bx-heart"></i></li>
            <li><i class="bx bx-search"></i></li>
            <li onclick="addToCart(<?= $product['product_id'] ?>)"><i class="bx bx-cart"></i></li>
          </ul>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- Contact -->
  <section class="section contact">
    <div class="row">
      <div class="col">
        <h2>Hỗ trợ</h2>
        <p>Bạn có thể liên hệ với chúng tôi bất cứ lúc nào trong ngày, chúng tôi sẽ phục vụ bạn 24/7</p>
        <a href="" class="btn btn-1">Liên hệ</a>
      </div>
      <div class="col">
        <form action="">
          <div>
            <input type="email" placeholder="Địa chỉ email">
            <a href="">Gửi</a>
          </div>
        </form>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="footer">
    <div class="row">
      <div class="col d-flex">
        <h4>THÔNG TIN</h4>
        <a href="">Về chúng tôi</a>
        <a href="">Liên hệ</a>
        <a href="">Điều khoản & dịch vụ</a>
        <a href="">Vận chuyển</a>
      </div>
      <div class="col d-flex">
        <h4>LIÊN KẾT</h4>
        <a href="">Cửa hàng trực tuyến</a>
        <a href="">Dịch vụ khách hàng</a>
        <a href="">Khuyến mãi</a>
        <a href="">Top bán chạy</a>
      </div>
      <div class="col d-flex">
        <span><i class='bx bxl-facebook-square'></i></span>
        <span><i class='bx bxl-instagram-alt'></i></span>
        <span><i class='bx bxl-github'></i></span>
        <span><i class='bx bxl-twitter'></i></span>
        <span><i class='bx bxl-pinterest'></i></span>
      </div>
    </div>
  </footer>


  <!-- PopUp -->
  <div class="popup hide-popup">
    <div class="popup-content">
      <div class="popup-close">
        <i class='bx bx-x'></i>
      </div>
      <div class="popup-left">
        <div class="popup-img-container">
          <img class="popup-img" src="./images/popup.jpg" alt="popup">
        </div>
      </div>
      <div class="popup-right">
        <div class="right-content">
          <h1>Lãy mã giảm giá <span>50%</span> cho toàn sản phẩm</h1>
          <p>Đăng ký nhận bản tin của chúng tôi và tiết kiệm 30% cho lần mua hàng tiếp theo của bạn. Không có thư rác, chúng tôi cam kết!
          </p>
          <form action="#">
            <input type="email" placeholder="Nhập email của bạn..." class="popup-form">
            <a href="#">Đăng ký</a>
          </form>
        </div>
      </div>
    </div>
  </div>

</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Glide.js/3.4.1/glide.min.js"></script>
<script src="./js/slider.js"></script>
<script src="./js/index.js"></script>

</html>
<?php
require_once("./config/product.config.php");
$countCart  = 0;

if (isset($_COOKIE['username'])) {
    $username = $_COOKIE['username'];

    // $stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ?");
    // $stmt->bind_param("s", $username);
    // $stmt->execute();
    // $stmt->bind_result($user_id);
    // $stmt->fetch();
    // $stmt->close();

    $countSql = "SELECT COUNT(*) as count FROM cart WHERE user_id = ?";
    $stmt = $conn->prepare($countSql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($countCart);
    $stmt->fetch();
    $stmt->close();

    $address = [];
    $userInfoSql = "SELECT * FROM users A JOIN address B ON A.user_id = B.user_id WHERE username = '$username'";
    $result = $conn->query($userInfoSql);
    $row = $result->fetch_assoc();
    $fName = $row['first_name'];
    $lName = $row['last_name'];
    $phone = $row['phone'];
    $email = $row['email'];
    $gender = $row['gender'];
    $birthday = $row['birthday'];
    $pob_id = $row['pob'];
    $address[] = $row;
    while ($row = $result->fetch_assoc()) {
        $address[] = $row;
    }

    $pob_options = [
        1 => 'Hồ Chí Minh',
        2 => 'Hà Nội',
        3 => 'Khánh Hòa',
        4 => 'Đà Nẵng'
    ];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin cá nhân</title>
    <!-- Boxicons -->
    <link
        href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css"
        rel="stylesheet" />
    <!-- Glide js -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Glide.js/3.4.1/css/glide.core.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Glide.js/3.4.1/css/glide.theme.css">
    <!-- Custom StyleSheet -->
    <link rel="stylesheet" href="./css/style.css" />
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
                            <span class="d-flex"><?php echo $countCart ?></span>
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
                        <span class="d-flex"><?php echo $countCart ?></span>
                    </a>
                </div>

                <div class="hamburger">
                    <i class="bx bx-menu-alt-left"></i>
                </div>
            </div>
        </div>
    </header>

    <!-- User Info -->
    <div class="user-info-container">
        <div class="user-info-sidebar">
            <ul>
                <li><a href="#basic-info" class="active">Thông tin cơ bản</a></li>
                <li><a href="#address">Sổ địa chỉ</a></li>
                <li><a href="#orders">Đơn hàng gần đây</a></li>
            </ul>
        </div>
        <div class="user-info-content">
            <section id="basic-info">
                <h2>Thông tin cơ bản</h2>
                <form action="" onsubmit="event.preventDefault(); saveUserInfo();">
                    <!-- load -->
                    <div id="loadingPopup" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: rgba(0, 0, 0, 0.8); padding: 20px; border-radius: 5px; color: #fff; text-align: center; z-index: 1000;">
                        <p>Đang tải...</p>
                        <div class="loader"></div>
                    </div>

                    <div class="info-row">
                        <div class="profile-detail">
                            <div class="user-info">
                                <div class="field-input-name">
                                    <label for="user-info-fname">Họ và tên đệm*</label>
                                    <input type="text" name="user-info-fname" id="user-info-fname" class="input-control" value="<?= $fName ?>">
                                </div>
                                <div class="field-input-name">
                                    <label for="user-info-lname">Tên*</label>
                                    <input type="text" name="user-info-lname" id="user-info-lname" class="input-control" value="<?= $lName ?>">
                                </div>
                            </div>
                            <div class="user-info">
                                <div class="field-input-phone">
                                    <label for="user-info-position">Số điện thoại</label><br>
                                    <input type="text" name="user-info-phone" id="user-info-phone" class="input-control" value="<?= $phone ?>">
                                </div>
                            </div>
                            <div class="user-info">
                                <div class="field-input-email">
                                    <label for="user-info-position">Email</label><br>
                                    <input type="text" name="user-info-email" id="user-info-email" class="input-control" value="<?= $email ?>">
                                </div>
                            </div>
                            <div class="user-info">
                                <div class="field-input-gender">
                                    <label for="user-info-position">Giới tính</label><br>
                                    <div>
                                        <p>Nam</p>
                                        <input type="radio" name="user-info-gender" id="user-info-gender" value="Nam" <?php if ($gender == 'Nam') echo 'checked'; ?> class="input-control">
                                    </div>

                                    <div>
                                        <p>Nữ</p>
                                        <input type="radio" name="user-info-gender" id="user-info-gender" value="Nữ" <?php if ($gender == 'Nữ') echo 'checked'; ?> class="input-control">
                                    </div>

                                </div>
                            </div>
                            <div class="user-info">
                                <div class="field-input-birthday">
                                    <label for="user-info-position">Ngày sinh</label><br>
                                    <input type="date" name="user-info-birthday" id="user-info-birthday" class="input-control" value="<?php echo htmlspecialchars($birthday); ?>">
                                </div>

                                <div class="field-input-pob">
                                    <label for="user-info-position">Nơi sinh</label><br>
                                    <select name="user-info-pob" id="user-info-pob"
                                        class="input-control" style="width: 100%;">
                                        <?php foreach ($pob_options as $id => $name): ?>
                                            <option value="<?php echo $id; ?>"
                                                <?php if ($pob_id == $id) echo 'selected'; ?>>
                                                <?php echo htmlspecialchars($name); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="user-info">
                                <div class="field-input-checkpw">
                                    <label for="user-info-checkpw">Đặt lại mật khẩu</label>
                                    <input type="checkbox" name="user-info-checkpw" id="user-info-checkpw" class="input-control">
                                </div>
                            </div>

                            <div class="user-pass-container">
                                <div class="user-info">
                                    <div class="field-input-old-pass">
                                        <label for="user-info-position">Mật khẩu cũ</label><br>
                                        <input type="password" name="user-info-old-pass" id="user-info-old-pass" class="input-control">
                                    </div>
                                </div>

                                <div class="user-info">
                                    <div class="field-input-new-pass">
                                        <label for="user-info-position">Mật khẩu Mới</label><br>
                                        <input type="password" name="user-info-new-pass" id="user-info-new-pass" class="input-control">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="button commit-change">
                        <button type="submit">Lưu thay đổi</button>
                    </div>
                </form>
            </section>
            <section id="address">
                <h2>Sổ địa chỉ</h2>
                <button onclick="addAddress()">Thêm địa chỉ mới</button>
                <?php foreach ($address as $item): ?>
                    <div class="address-item">
                        <div class="address-info">
                            <?= $item['street'] . ', ' . $item['ward'] . ', ' . $item['district'] . ', ' . $item['city'] . ', ' . $item['nation'] ?>
                        </div>
                        <div class="address-action">
                            <a href="" class="btn-update">Chỉnh sửa</a>
                            <a href="" class="btn-del">Xóa</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </section>

            <section id="orders">
                <h2>Đơn hàng gần đây</h2>
                <p>Danh sách các đơn hàng gần đây của người dùng.</p>
                <p>Danh sách các đơn hàng gần đây của người dùng.</p>
                <p>Danh sách các đơn hàng gần đây của người dùng.</p>
                <p>Danh sách các đơn hàng gần đây của người dùng.</p>
                <p>Danh sách các đơn hàng gần đây của người dùng.</p>
                <p>Danh sách các đơn hàng gần đây của người dùng.</p>
                <p>Danh sách các đơn hàng gần đây của người dùng.</p>
                <p>Danh sách các đơn hàng gần đây của người dùng.</p>
                <p>Danh sách các đơn hàng gần đây của người dùng.</p>
                <p>Danh sách các đơn hàng gần đây của người dùng.</p>
                <p>Danh sách các đơn hàng gần đây của người dùng.</p>
                <p>Danh sách các đơn hàng gần đây của người dùng.</p>
                <p>Danh sách các đơn hàng gần đây của người dùng.</p>
                <p>Danh sách các đơn hàng gần đây của người dùng.</p>
                <p>Danh sách các đơn hàng gần đây của người dùng.</p>
                <p>Danh sách các đơn hàng gần đây của người dùng.</p>
                <p>Danh sách các đơn hàng gần đây của người dùng.</p>
                <p>Danh sách các đơn hàng gần đây của người dùng.</p>
                <p>Danh sách các đơn hàng gần đây của người dùng.</p>
                <p>Danh sách các đơn hàng gần đây của người dùng.</p>
                <p>Danh sách các đơn hàng gần đây của người dùng.</p>
                <p>Danh sách các đơn hàng gần đây của người dùng.</p>
                <p>Danh sách các đơn hàng gần đây của người dùng.</p>
                <p>Danh sách các đơn hàng gần đây của người dùng.</p>
                <p>Danh sách các đơn hàng gần đây của người dùng.</p>
                <p>Danh sách các đơn hàng gần đây của người dùng.</p>
                <p>Danh sách các đơn hàng gần đây của người dùng.</p>
            </section>
        </div>
    </div>

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


</body>
<script src="./js/index.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Glide.js/3.4.1/glide.min.js"></script>
<script src="./js/slider.js"></script>

</html>
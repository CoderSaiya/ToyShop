<?php
require_once("./config/product.config.php");
if (!isset($_COOKIE['username'])) {
    die("Không tìm thấy cookie đăng nhập. Vui lòng đăng nhập lại.");
}

$username = $_COOKIE['username'];

$userInfoSql = "SELECT * FROM users A JOIN address B ON A.user_id = B.user_id WHERE username = '$username'";
$result = $conn->query($userInfoSql);

if ($result && $result->num_rows > 0) {
    $userInfo = $result->fetch_assoc();
    $user_id = $userInfo['user_id'];

    $countSql = "SELECT COUNT(*) as count FROM cart WHERE user_id = $user_id";
    $result = $conn->query($countSql);
    if ($result) {
        $row = $result->fetch_assoc();
        $countCart = $row['count'];
    } else {
        die("Lỗi khi đếm giỏ hàng: " . $conn->error);
    }

    // Fetch cart items for this user
    $cartSql = "SELECT * FROM cart A JOIN products B ON A.product_id = B.product_id WHERE A.user_id = $user_id";
    $result = $conn->query($cartSql);
    $productList = [];

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $productList[] = $row;
        }
    } else {
        die("Lỗi khi lấy sản phẩm trong giỏ hàng: " . $conn->error);
    }
} else {
    die("Không tìm thấy người dùng.");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link
        href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css"
        rel="stylesheet" />

    <link
        href="./css/style.css"
        rel="stylesheet" />
    <style>
        *,
        *:after,
        *:before {
            box-sizing: border-box;
        }

        ul {
            padding-left: 10px;
        }

        body {
            font-family: "Josefin Sans", sans-serif;
            color: #0a0a0a;
            line-height: 1.4;
        }

        a {
            color: #000;
        }

        .content {
            z-index: 9999;
        }

        .secure,
        .backBtn {
            display: flex;
        }

        .secure span,
        .backBtn span {
            margin-left: 5px;
        }

        .backBtn {
            margin-top: 20px;
        }

        .secure {
            color: #afb5c0;
            align-items: flex-end;
        }

        .secure .icon {
            font-size: 20px;
            line-height: 20px;
        }

        .logo {
            font-size: 20px;
            font-weight: bold;
            display: flex;
            justify-content: center;
            align-items: flex-end;
        }

        .logo .icon {
            font-size: 32px;
            line-height: 32px;
            margin-right: 5px;
        }

        img {
            width: 100%;
            border-radius: 8px 0 0 8px;
        }

        .details {
            max-width: 1000px;
            min-height: 300px;
            margin-left: auto;
            margin-right: auto;
            margin-top: 20px;
            background: #fff;
            border-radius: 8px;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .details__item {
            display: flex;
        }

        .details__user {
            background: #f6f9fc;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #dadada;
        }

        .item__quantity {
            position: absolute;
            right: 50px;
            top: 30px;
            font-size: 20px;
        }

        .item__image {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .item__image .iphone {
            margin-top: 0px;
            margin-left: -100px;
            width: 200px;
        }

        .item__details {
            padding: 30px;
        }

        .item__title {
            font-size: 28px;
            font-weight: 600;
        }

        .item__price {
            font-size: 22px;
            color: #bec3cb;
        }

        .icon {
            font-size: 16px;
            vertical-align: middle;
        }

        header {
            background-color: #f6f9fc;
            min-height: 500px;
            background-image: url("../images/pattern.png");
        }

        .navigation {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 50px 0 80px 0;
            color: #246eea;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 700;
        }

        .container {
            width: 960px;
            margin: 0 auto;
        }

        .shadow {
            box-shadow: 0 15px 35px rgba(50, 50, 93, 0.1), 0 5px 15px rgba(0, 0, 0, 0.07);
        }

        .row {
            display: flex;
        }

        .txt {
            border-color: #e1e8ee;
            width: 100%;
        }

        .input {
            border-radius: 5px;
            border-style: solid;
            border-width: 2px;
            height: 48px;
            padding-left: 15px;
            font-weight: 600;
            font-size: 14px;
            color: #5e6977;
        }

        input[type="text"] {
            display: initial;
            padding: 15px;
        }

        .text-validated {
            border-color: #7dc855;
            background-image: url("../images/icon-tick.png");
            background-repeat: no-repeat;
            background-position: right 18px center;
        }

        .ddl {
            border-color: #f0f4f7;
            background-color: #f0f4f7;
            width: 100px;
            margin-right: 10px;
        }

        .title {
            font-size: 14px;
            padding-bottom: 8px;
            margin-bottom: 0px;
        }

        .field {
            padding-top: 15px;
            padding-right: 30px;
            width: 50%;
        }

        input[type=number]::-webkit-outer-spin-button,
        input[type=number]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .field.small {
            width: auto;
        }

        .notification {
            text-align: center;
            font-size: 28px;
            font-weight: 600;
            display: flex;
            justify-content: center;
        }

        .notification .icon {
            font-size: 28px;
            color: #7dc855;
            line-height: 28px;
            margin-right: 10px;
        }

        .actions {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 60px 0 40px 0;
        }

        .payment {
            padding: 20px 0 0 0;
        }

        .payment__title {
            margin: 40px 0 20px 0;
            font-size: 18px;
            display: flex;
            text-align: left;
            font-weight: bold;
        }

        .payment__title .icon {
            margin-right: 10px;
        }

        .btn {
            font-family: "Josefin Sans", sans-serif;
            border-radius: 8px;
            border: 0;
            letter-spacing: 1px;
            color: #fff;
            padding: 20px 60px;
            white-space: nowrap;
            font-size: 16px;
            line-height: 1;
            text-transform: uppercase;
            transition: all 0.15s ease;
            text-decoration: none;
            background-color: #6cbe02;
        }

        .btn .icon {
            margin-left: 10px;
            font-size: 20px;
        }

        .btn:hover {
            -webkit-transform: translateY(-1px);
            transform: translateY(-1px);
            background: #6cbe02;
        }

        .btn.action__back {
            background: transparent;
            color: #a0a0a0;
        }

        .payment__types {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .payment__info {
            display: flex;
        }

        .payment__cc {
            flex: 60%;
        }

        .payment__shipping {
            flex: 40%;
        }

        .shipping__info {
            background: #f6f9fc;
            padding: 10px;
            border-radius: 8px;
        }

        .payment__type {
            display: flex;
            border: 2px solid #d9d9d9;
            border-radius: 8px;
            padding: 20px 40px;
            width: 100%;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            color: #d9d9d9;
            transition: all 0.15s ease;
            gap: 20px
        }

        .payment__type i {
            object-fit: contain;
            width: 40px;
        }

        .payment__type:hover {
            -webkit-transform: translateY(-1px);
            transform: translateY(-1px);
        }

        .payment__type.active {
            color: #0a0a0a;
            background: #f6fcf7;
            border-color: #7dc855;
        }

        .payment__type .icon {
            font-size: 32px;
            margin-right: 10px;
        }

        .payment__type+.payment__type {
            margin-left: 10px;
        }

        .icon-xl {
            font-size: 48px;
            line-height: 48px;
        }

        .content {
            display: block;
        }

        .thankyou {
            display: block;
        }

        .thankyou .details {
            opacity: 1;
            justify-content: center;
            align-items: center;
        }

        .thankyou .details h3 {
            font-weight: 600;
        }

        .thankyou .details__item {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .thankyou .details__item .icon-xl {
            font-size: 32px;
            color: #7dc855;
            line-height: 32px;
        }

        .loading {
            position: relative;
        }

        .loading:after {
            -webkit-animation: spinAround .5s infinite linear;
            animation: spinAround .5s infinite linear;
            border: 4px solid #7dc855;
            border-radius: 290486px;
            border-right-color: transparent;
            border-top-color: transparent;
            content: "";
            height: 5em;
            width: 5em;
            position: absolute;
        }

        .loading .details__item {
            opacity: 0;
        }

        @-webkit-keyframes spinAround {
            from {
                -webkit-transform: rotate(0);
                transform: rotate(0);
            }

            to {
                -webkit-transform: rotate(359deg);
                transform: rotate(359deg);
            }
        }

        @keyframes spinAround {
            from {
                -webkit-transform: rotate(0);
                transform: rotate(0);
            }

            to {
                -webkit-transform: rotate(359deg);
                transform: rotate(359deg);
            }
        }
    </style>
</head>

<body>
    <form method="POST" action="./config/checkout.php">
        <section class="content">

            <div class="container">

            </div>
            <div class="details shadow">
                <table>
                    <tr>
                        <th style="border-radius: 8px 0 0 0;">Sản phẩm</th>
                        <th>Số Lượng</th>
                        <th style="border-radius: 0 8px 0 0;">Giá</th>
                    </tr>
                    <?php
                    $subSum = 0;
                    $tax = 20000;
                    foreach ($productList as $product) : ?>
                        <tr>
                            <td>
                                <div class="cart-info">
                                    <img src="./images/<?php echo htmlspecialchars($product['image_url'], ENT_QUOTES, 'UTF-8'); ?>" alt="" />
                                    <div>
                                        <p><?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?></p>
                                        <span style="no-style">Đơn giá: <?php echo number_format($product['price'], 0, ",", "."); ?> đ</span><br />
                                    </div>
                                </div>
                            </td>
                            <td>
                                <p style="display: inline-block; width: 100%; text-align: center; justify-content: center"><?php echo htmlspecialchars($product['quantity'], ENT_QUOTES, 'UTF-8'); ?></p>
                            </td>
                            <td><?php echo number_format($product['quantity'] * $product['price'], 0, ",", "."); ?> đ</td>
                        </tr>
                    <?php $subSum += $product['quantity'] * $product['price'];
                    endforeach;
                    $sum = $subSum + $tax ?>
                </table>

                <table>
                    <tr>
                        <td><b>Tổng tiền hàng</b></td>
                        <td><?php echo number_format($subSum, 0, ",", ".") ?> đ</td>
                    </tr>
                    <tr>
                        <td><b>Phí vận chuyển</b></td>
                        <td><?php echo number_format($tax, 0, ",", ".") ?> đ</td>
                    </tr>
                    <tr>
                        <td><b>Thành tiền</b></td>
                        <td><?php echo number_format($sum, 0, ",", ".") ?> đ</td>
                    </tr>
                </table>
                <!-- Hidden input -->
                <input type="hidden" id="paymentMethod" name="payment_method" value="stripe">
                <input type="hidden" name="totalsum" value="<?php echo $sum; ?>">
                <input type="hidden" name="productList" value="<?php echo htmlspecialchars(json_encode($productList), ENT_QUOTES, 'UTF-8'); ?>">

            </div>
            <div class="discount"></div>

            <div class="container">
                <div class="payment">
                    <div class="payment__title">
                        Phương thức thanh toán
                    </div>
                    <div class="payment__types">
                        <div class="payment__type payment__type--cc active" data-method="stripe">
                            <i class='bx bxs-credit-card'></i></i>Thẻ Visa
                        </div>
                        <div class="payment__type payment__type--paypal" data-method="cod">
                            <i class='bx bx-money'></i>Thanh toán khi nhận hàng
                        </div>
                    </div>

                    <div class="payment__info">
                        <!-- <div class="payment__cc">
                        <div class="payment__title">
                            <i class="icon icon-user"></i>Thông tin cá nhân
                        </div>
                        <form>
                            <div class="form__cc">
                                <div class="row">
                                    <div class="field">
                                        <div class="title">Số thẻ
                                        </div>
                                        <input type="number" class="input txt text-validated" placeholder="9999 9999 9999 9999" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="field small">
                                        <div class="title">Ngày hết hạn
                                        </div>
                                        <select class="input ddl">
                                            <option selected>01</option>
                                            <option>02</option>
                                            <option>03</option>
                                            <option>04</option>
                                            <option>05</option>
                                            <option>06</option>
                                            <option>07</option>
                                            <option>08</option>
                                            <option>09</option>
                                            <option>10</option>
                                            <option>11</option>
                                            <option>12</option>
                                        </select>
                                        <select class="input ddl">
                                            <option>01</option>
                                            <option>02</option>
                                            <option>03</option>
                                            <option>04</option>
                                            <option>05</option>
                                            <option>06</option>
                                            <option>07</option>
                                            <option>08</option>
                                            <option>09</option>
                                            <option>10</option>
                                            <option>11</option>
                                            <option>12</option>
                                            <option>13</option>
                                            <option>14</option>
                                            <option>15</option>
                                            <option selected>16</option>
                                            <option>17</option>
                                            <option>18</option>
                                            <option>19</option>
                                            <option>20</option>
                                            <option>21</option>
                                            <option>22</option>
                                            <option>23</option>
                                            <option>24</option>
                                            <option>25</option>
                                            <option>26</option>
                                            <option>27</option>
                                            <option>28</option>
                                            <option>29</option>
                                            <option>30</option>
                                            <option>31</option>
                                        </select>
                                    </div>
                                    <div class="field small">
                                        <div class="title">Mã CVV

                                        </div>
                                        <input type="text" class="input txt" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="field">
                                        <div class="title">Tên chủ thẻ
                                        </div>
                                        <input type="text" class="input txt" />
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div> -->
                        <div class="payment__shipping">
                            <div class="payment__title">
                                <i class="icon icon-plane"></i> Thông tin vận chuyển
                            </div>
                            <div class="details__user">
                                <div class="user__name"><b>Họ và tên:</b> <?= $userInfo['first_name'] . ' ' . $userInfo['last_name'] ?>
                                    <br><b>Số điện thoại:</b> <?= $userInfo['phone'] ?>
                                </div>
                                <div class="user__address"><b>Địa chỉ nhận hàng:</b> <?= $userInfo['street'] . ', ' . $userInfo['ward'] . ', ' . $userInfo['district'] . ', ' . $userInfo['city'] . ', ' . $userInfo['nation'] ?>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="actions">

                    <button type="submit" href="" class="btn action__submit">Thanh toán
                        <i class="icon icon-arrow-right-circle"></i>
                    </button>
                    <a href="/" class="backBtn">Trở về</a>

                </div>
        </section>
    </form>
    </div>

</body>
<script src="./js/index.js"></script>

</html>
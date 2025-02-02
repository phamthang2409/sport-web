<?php
//Quan ly view va model lien quan: trang chu, lien he, gioi thieu
// Goi duoc: view (giao dien html, css, js)
// mode (csdl)
if (isset($_GET['act'])) {
    switch ($_GET['act']) {
        case 'home':

            include_once 'model/product.php';

            $dsSP = get_Product();

            $dsSPHot = get_ProductHot();
            include_once 'view/template_header.php';
            include_once 'view/view_home.php';
            include_once 'view/template_footer.php';

            break;


        case 'cart':
            include_once 'model/shoppingcart.php';
            $MaTK = $_SESSION['user']['MaTK'];
            $GioHang = user_hasCart($MaTK);

            if ($GioHang) { //nếu đúng thì giỏ hàng có và hiển thị sản phẩm trong giỏ hàng
                $ctGioHang = shopping_getCart($MaTK);
            } else {
                $ctGioHang = [];
            }

            include_once 'view/template_header_cart.php';
            include_once 'view/page_cart.php';
            include_once 'view/template_footer.php';
            break;

        case 'pay':
            include_once 'model/order.php';

            if (!isset($_SESSION['user'])) {
                // Nếu chưa đăng nhập, chuyển hướng về trang đăng nhập hoặc hiển thị thông báo
                $_SESSION['thongbao'] = "Vui lòng đăng nhập để thực hiện thanh toán.";
                header('Location: index.php?mod=user&act=login');
                exit();
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selectedOrders'])) {

                $selectedOrders = $_POST['selectedOrders'];
                $paymentMethod = $_POST['paymentMethod'];

                foreach ($selectedOrders as $orderID) {
                    processPayment($orderID, $paymentMethod);
                }

                $_SESSION['thongbao'] = "Đã thanh toán thành công!";
                header('Location: index.php?mod=page&act=pay');
                exit();
            }

            $MaTK = $_SESSION['user']['MaTK'];
            $orders = get_Order($MaTK);

            include_once 'view/template_header.php';
            include_once 'view/pay.php';
            include_once 'view/template_footer.php';
            break;

        case 'order_detail':
            include_once 'model/order.php';
            // Thêm vào controller để lấy thông tin đơn hàng chi tiết
            $MaTK = $_SESSION['user']['MaTK'];

            $orderDetails = get_orderDetail($MaTK);

            // Truyền dữ liệu sang view
            include_once 'view/template_header.php';
            include_once 'view/order_detail.php';
            include_once 'view/template_footer.php';

            break;
        default:
            # code...
            break;
    }
}
?>
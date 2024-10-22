<?php
require_once 'dbConfig.php';
require_once 'models.php';

if (isset($_POST['placeOrderBtn'])) {
    $firstName    = trim($_POST['firstName']);
    $lastName     = trim($_POST['lastName']);
    $email        = trim($_POST['email']);
    $phone        = trim($_POST['phone']);
    $address      = trim($_POST['address']);
    $shopId       = trim($_POST['shopId']);
    $orderDetails = $_POST['orderDetails'];
    $quantities   = $_POST['quantities'];
    $orderDate    = date('Y-m-d H:i:s');

    if (!empty($firstName) && !empty($lastName) && !empty($email) && 
        !empty($phone) && !empty($address) && !empty($shopId) && 
        !empty($orderDetails) && !empty($quantities)) {
        
        insertCustomer($pdo, $firstName, $lastName, $email, $phone, $address);
        $customerId = $pdo->lastInsertId();
        
        $orderDetailsFormatted = [];
        foreach ($orderDetails as $index => $detail) {
            $orderDetailsFormatted[] = $detail . ' x' . $quantities[$index];
        }
        $orderDetailsString = implode(', ', $orderDetailsFormatted);

        $query = insertOrder($pdo, $shopId, $customerId, $orderDetailsString, $orderDate);

        if ($query) {
            header("Location: ../index.php");
        } else {
            echo "Order submission failed";
        }
    } else {
        echo "Make sure that no fields are empty";
    }
}

if (isset($_POST['editOrderBtn'])) {
    $orderId      = $_GET['order_id'];
    $shopId       = trim($_POST['shopId']);
    $customerId   = trim($_POST['customerId']);
    $email        = trim($_POST['email']);
    $phone        = trim($_POST['phone']);
    $address      = trim($_POST['address']);
    $orderDetails = $_POST['orderDetails'];
    $quantities   = $_POST['quantities'];
    $orderDate    = date('Y-m-d H:i:s');

    if (!empty($orderId) && !empty($shopId) && !empty($customerId) && 
        !empty($email) && !empty($phone) && !empty($address) && 
        !empty($orderDetails) && !empty($quantities)) {
        
        $orderDetailsFormatted = [];
        foreach ($orderDetails as $index => $detail) {
            $orderDetailsFormatted[] = $detail . ' x' . $quantities[$index];
        }
        $orderDetailsString = implode(', ', $orderDetailsFormatted);

        $query = updateOrder($pdo, $orderId, $shopId, $customerId, $orderDetailsString, $orderDate);

        if ($query) {
            header("Location: ../index.php");
        } else {
            echo "Update failed";
        }
    } else {
        echo "Make sure that no fields are empty";
    }
}

if (isset($_POST['deleteOrderBtn'])) {
    $query = deleteOrder($pdo, $_GET['order_id']);

    if ($query) {
        header("Location: ../index.php");
    } else {
        echo "Deletion failed";
    }
}
?>
<?php
require_once 'dbConfig.php';

function insertCustomer($pdo, $first_name, $last_name, $email, $phone, $address) {
    $sql = "INSERT INTO customers (first_name, last_name, email, phone, address) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$first_name, $last_name, $email, $phone, $address]);
}

function insertOrder($pdo, $shop_id, $customer_id, $order_details, $order_date) {
    $sql = "INSERT INTO orders (shop_id, customer_id, order_details, order_date) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$shop_id, $customer_id, $order_details, $order_date]);
}

function getAllOrders($pdo) {
    $sql = "SELECT orders.*, customers.first_name, customers.last_name, customers.email, customers.phone, customers.address, coffee_shops.shop_id, coffee_shops.shop_name, coffee_shops.location 
            FROM orders 
            JOIN customers ON orders.customer_id = customers.customer_id 
            JOIN coffee_shops ON orders.shop_id = coffee_shops.shop_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
}

function getAllShops($pdo) {
    $sql = "SELECT shop_id, shop_name, location FROM coffee_shops";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
}

function getOrderById($pdo, $order_id) {
    $sql = "SELECT orders.*, customers.first_name, customers.last_name, customers.email, customers.phone, customers.address FROM orders JOIN customers ON orders.customer_id = customers.customer_id WHERE order_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$order_id]);
    return $stmt->fetch();
}

function updateOrder($pdo, $order_id, $shop_id, $customer_id, $order_details, $order_date) {
    $sql = "UPDATE orders SET shop_id = ?, customer_id = ?, order_details = ?, order_date = ? WHERE order_id = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$shop_id, $customer_id, $order_details, $order_date, $order_id]);
}

function deleteOrder($pdo, $order_id) {
    $sql = "DELETE FROM orders WHERE order_id = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$order_id]);
}

function getAllCustomers($pdo) {
    $sql = "SELECT customer_id, first_name, last_name FROM customers";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
}
?>
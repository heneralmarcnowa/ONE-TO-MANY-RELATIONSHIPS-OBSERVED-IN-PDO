<?php
require_once 'core/dbConfig.php';
require_once 'core/models.php';

$getOrderById = getOrderById($pdo, $_GET['order_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Order</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Are you sure you want to delete this order?</h1>
        <div class="order-details">
            <p><b>Customer Name:</b> <?php echo htmlspecialchars($getOrderById['first_name'] . ' ' . $getOrderById['last_name']); ?></p>
            <p><b>Order Details:</b> <?php echo htmlspecialchars($getOrderById['order_details']); ?></p>
            <p><b>Order Date:</b> <?php echo htmlspecialchars($getOrderById['order_date']); ?></p>
        </div>
        <div class="button-container">
            <form action="core/handleForms.php?order_id=<?php echo $_GET['order_id']; ?>" method="POST" class="inline-form">
                <button type="submit" name="deleteOrderBtn" class="delete-button">Delete</button>
            </form>
            <a href="index.php" class="back-button">Back</a>
        </div>
    </div>
</body>
</html>
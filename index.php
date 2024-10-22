<?php
require_once 'core/dbConfig.php';
require_once 'core/models.php';
date_default_timezone_set('Asia/Manila');
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
            header("Location: index.php");
        } else {
            echo "Order submission failed";
        }
    } else {
        echo "Make sure that no fields are empty";
    }
}

$orders = getAllOrders($pdo);
$shops  = getAllShops($pdo);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order System  (CRUD only)</title>
    <link rel="stylesheet" href="style.css">
    <script>
        function addOrderDetail() {
            const container = document.getElementById('orderDetailsContainer');
            const orderDetail = document.createElement('div');
            orderDetail.className = 'order-detail';
            orderDetail.innerHTML = `
                <select name="orderDetails[]" required>
                    <option value="" disabled selected>Select Coffee</option>
                    <optgroup label="Hot Coffee">
                        <option value="Hot Espresso: ₱80.00">Espresso: ₱80.00</option>
                        <option value="Hot Double Espresso: ₱95.00">Double Espresso: ₱95.00</option>
                        <option value="Hot Latte: ₱95.00">Latte: ₱95.00</option>
                        <option value="Hot Cappuccino: ₱95.00">Cappuccino: ₱95.00</option>
                        <option value="Hot Macchiato: ₱100.00">Macchiato: ₱100.00</option>
                    </optgroup>
                    <optgroup label="Cold Coffee">
                        <option value="Cold Espresso: ₱90.00">Espresso: ₱90.00</option>
                        <option value="Cold Double Espresso: ₱105.00">Double Espresso: ₱105.00</option>
                        <option value="Cold Latte: ₱105.00">Latte: ₱105.00</option>
                        <option value="Cold Cappuccino: ₱105.00">Cappuccino: ₱105.00</option>
                        <option value="Cold Macchiato: ₱105.00">Macchiato: ₱105.00</option>
                    </optgroup>
                    <optgroup label="Non-Coffee">
                        <option value="Hot Chocolate: ₱80.00">Hot Chocolate: ₱80.00</option>
                        <option value="Matcha: ₱95.00">Matcha: ₱95.00</option>
                        <option value="Milkshake: ₱95.00">Milkshake: ₱95.00</option>
                        <option value="Smoothie: ₱95.00">Smoothie: ₱95.00</option>
                    </optgroup>
                </select>
                <input type="number" name="quantities[]" min="1" placeholder="Quantity" required>
                <button type="button" class="remove-button" onclick="removeOrderDetail(this)">Remove</button>
            `;
            container.appendChild(orderDetail);
            updateRemoveButtons();
        }

        function removeOrderDetail(button) {
            const orderDetail = button.parentElement;
            orderDetail.remove();
            updateRemoveButtons();
        }

        function updateRemoveButtons() {
            const orderDetails = document.querySelectorAll('.order-detail');
            const removeButtons = document.querySelectorAll('.remove-button');
            removeButtons.forEach(button => button.style.display = (orderDetails.length > 1) ? 'inline-block' : 'none');
        }

        document.addEventListener('DOMContentLoaded', function() {
            updateRemoveButtons();
        });
    </script>
</head>
<body>
    <div class="container">
        <h1>Coffee Ordering System(CRUD)</h1>
        <p>Place your order below:</p>
        <form action="index.php" method="POST">
            <label for="firstName">First Name</label>
            <input type="text" id="firstName" name="firstName" required>

            <label for="lastName">Last Name</label>
            <input type="text" id="lastName" name="lastName" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>

            <label for="phone">Phone</label>
            <input type="tel" id="phone" name="phone" required>
            
            <label for="address">Address</label>
            <input type="text" id="address" name="address" required>

            <label for="shopId">Shop Location</label>
            <select id="shopId" name="shopId" required>
                <?php foreach ($shops as $shop): ?>
                    <option value="<?= htmlspecialchars($shop['shop_id']) ?>"><?= htmlspecialchars($shop['location']) ?></option>
                <?php endforeach; ?>
            </select>

            <label for="orderDetails">Order Details</label>
            <div id="orderDetailsContainer">
                <div class="order-detail">
                    <select name="orderDetails[]" required>
                        <option value="" disabled selected>Select Coffee</option>
                        <optgroup label="Hot Coffee">
                            <option value="Hot Espresso: ₱80.00">Espresso: ₱80.00</option>
                            <option value="Hot Double Espresso: ₱95.00">Double Espresso: ₱95.00</option>
                            <option value="Hot Latte: ₱95.00">Latte: ₱95.00</option>
                            <option value="Hot Cappuccino: ₱95.00">Cappuccino: ₱95.00</option>
                            <option value="Hot Macchiato: ₱100.00">Macchiato: ₱100.00</option>
                        </optgroup>
                        <optgroup label="Cold Coffee">
                            <option value="Cold Espresso: ₱90.00">Espresso: ₱90.00</option>
                            <option value="Cold Double Espresso: ₱105.00">Double Espresso: ₱105.00</option>
                            <option value="Cold Latte: ₱105.00">Latte: ₱105.00</option>
                            <option value="Cold Cappuccino: ₱105.00">Cappuccino: ₱105.00</option>
                            <option value="Cold Macchiato: ₱105.00">Macchiato: ₱105.00</option>
                        </optgroup>
                        <optgroup label="Non-Coffee">
                            <option value="Hot Chocolate: ₱80.00">Hot Chocolate: ₱80.00</option>
                            <option value="Matcha: ₱95.00">Matcha: ₱95.00</option>
                            <option value="Milkshake: ₱95.00">Milkshake: ₱95.00</option>
                            <option value="Smoothie: ₱95.00">Smoothie: ₱95.00</option>
                        </optgroup>
                    </select>
                    <input type="number" name="quantities[]" min="1" placeholder="Quantity" required>
                    <button type="button" class="remove-button" onclick="removeOrderDetail(this)">Remove</button>
                </div>
            </div>
            <button type="button" onclick="addOrderDetail()">Add More</button>
            <button type="submit" name="placeOrderBtn">Place Order</button>
        </form>
    </div>

    <div class="table-container">
        <h2>Orders</h2>
        <table>
            <tr>
                <th>Order ID</th>
                <th>Shop ID</th>
                <th>Location</th>
                <th>Customer Name</th>
                <th>Address</th>
                <th>Order Details</th>
                <th>Order Date</th>
                <th>Action</th>
            </tr>
            <?php if (!empty($orders)): ?>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?= htmlspecialchars($order['order_id']) ?></td>
                        <td><?= htmlspecialchars($order['shop_id']) ?></td>
                        <td><?= htmlspecialchars($order['location']) ?></td>
                        <td><?= htmlspecialchars($order['first_name'] . ' ' . $order['last_name']) ?></td>
                        <td><?= htmlspecialchars($order['address']) ?></td>
                        <td><?= htmlspecialchars($order['order_details']) ?></td>
                        <td><?= htmlspecialchars($order['order_date']) ?></td>
                        <td>
                            <a href="editOrder.php?order_id=<?= htmlspecialchars($order['order_id']) ?>" style="color: #007bff; text-decoration: none;">Edit</a> |
                            <a href="deleteOrder.php?order_id=<?= htmlspecialchars($order['order_id']) ?>" style="color: #dc3545; text-decoration: none;" onclick="return confirm('Are you sure you want to delete this order?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" style="text-align: center;">No orders found.</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>
</body>
</html>
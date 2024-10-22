<?php
require_once 'core/dbConfig.php';
require_once 'core/models.php';

$getOrderById = getOrderById($pdo, $_GET['order_id']);
$shops = getAllShops($pdo);
$customers = getAllCustomers($pdo);

$orderDetails = explode(', ', $getOrderById['order_details']);
$parsedOrderDetails = [];
foreach ($orderDetails as $detail) {
    preg_match('/(.+): (.+) x(\d+)/', $detail, $matches);
    if ($matches) {
        $parsedOrderDetails[] = [
            'item' => $matches[1] . ': ' . $matches[2],
            'quantity' => $matches[3]
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Order</title>
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
        }

        function removeOrderDetail(button) {
            const orderDetail = button.parentElement;
            orderDetail.remove();
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Edit Order</h1>
        <form action="core/handleForms.php?order_id=<?php echo $_GET['order_id']; ?>" method="POST">
            <div class="form-group">
                <label for="shopId">Shop</label>
                <select name="shopId" id="shopId" required>
                    <?php foreach ($shops as $shop): ?>
                        <option value="<?php echo htmlspecialchars($shop['shop_id']); ?>" 
                            <?php echo ($shop['shop_id'] == $getOrderById['shop_id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($shop['location']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="customerId">Customer Name</label>
                <select name="customerId" id="customerId" required>
                    <?php foreach ($customers as $customer): ?>
                        <option value="<?php echo htmlspecialchars($customer['customer_id']); ?>" 
                            <?php echo ($customer['customer_id'] == $getOrderById['customer_id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($customer['first_name'] . ' ' . $customer['last_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" 
                    value="<?php echo htmlspecialchars($getOrderById['email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="tel" id="phone" name="phone" 
                    value="<?php echo htmlspecialchars($getOrderById['phone']); ?>" required>
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" id="address" name="address" 
                    value="<?php echo htmlspecialchars($getOrderById['address']); ?>" required>
            </div>
            <div class="form-group">
                <label for="orderDetails">Order Details</label>
                <div id="orderDetailsContainer">
                    <?php foreach ($parsedOrderDetails as $detail): ?>
                        <div class="order-detail">
                            <select name="orderDetails[]" required>
                                <option value="" disabled>Select Coffee</option>
                                <optgroup label="Hot Coffee">
                                    <option value="Hot Espresso: ₱80.00" 
                                        <?php echo ($detail['item'] == 'Hot Espresso: ₱80.00') ? 'selected' : ''; ?>>
                                        Espresso: ₱80.00
                                    </option>
                                    <option value="Hot Double Espresso: ₱95.00" 
                                        <?php echo ($detail['item'] == 'Hot Double Espresso: ₱95.00') ? 'selected' : ''; ?>>
                                        Double Espresso: ₱95.00
                                    </option>
                                    <option value="Hot Latte: ₱95.00" 
                                        <?php echo ($detail['item'] == 'Hot Latte: ₱95.00') ? 'selected' : ''; ?>>
                                        Latte: ₱95.00
                                    </option>
                                    <option value="Hot Cappuccino: ₱95.00" 
                                        <?php echo ($detail['item'] == 'Hot Cappuccino: ₱95.00') ? 'selected' : ''; ?>>
                                        Cappuccino: ₱95.00
                                    </option>
                                    <option value="Hot Macchiato: ₱100.00" 
                                        <?php echo ($detail['item'] == 'Hot Macchiato: ₱100.00') ? 'selected' : ''; ?>>
                                        Macchiato: ₱100.00
                                    </option>
                                </optgroup>
                                <optgroup label="Cold Coffee">
                                    <option value="Cold Espresso: ₱90.00" 
                                        <?php echo ($detail['item'] == 'Cold Espresso: ₱90.00') ? 'selected' : ''; ?>>
                                        Espresso: ₱90.00
                                    </option>
                                    <option value="Cold Double Espresso: ₱105.00" 
                                        <?php echo ($detail['item'] == 'Cold Double Espresso: ₱105.00') ? 'selected' : ''; ?>>
                                        Double Espresso: ₱105.00
                                    </option>
                                    <option value="Cold Latte: ₱105.00" 
                                        <?php echo ($detail['item'] == 'Cold Latte: ₱105.00') ? 'selected' : ''; ?>>
                                        Latte: ₱105.00
                                    </option>
                                    <option value="Cold Cappuccino: ₱105.00" 
                                        <?php echo ($detail['item'] == 'Cold Cappuccino: ₱105.00') ? 'selected' : ''; ?>>
                                        Cappuccino: ₱105.00
                                    </option>
                                    <option value="Cold Macchiato: ₱105.00" 
                                        <?php echo ($detail['item'] == 'Cold Macchiato: ₱105.00') ? 'selected' : ''; ?>>
                                        Macchiato: ₱105.00
                                    </option>
                                </optgroup>
                                <optgroup label="Non-Coffee">
                                    <option value="Hot Chocolate: ₱80.00" 
                                        <?php echo ($detail['item'] == 'Hot Chocolate: ₱80.00') ? 'selected' : ''; ?>>
                                        Hot Chocolate: ₱80.00
                                    </option>
                                    <option value="Matcha: ₱95.00" 
                                        <?php echo ($detail['item'] == 'Matcha: ₱95.00') ? 'selected' : ''; ?>>
                                        Matcha: ₱95.00
                                    </option>
                                    <option value="Milkshake: ₱95.00" 
                                        <?php echo ($detail['item'] == 'Milkshake: ₱95.00') ? 'selected' : ''; ?>>
                                        Milkshake: ₱95.00
                                    </option>
                                    <option value="Smoothie: ₱95.00" 
                                        <?php echo ($detail['item'] == 'Smoothie: ₱95.00') ? 'selected' : ''; ?>>
                                        Smoothie: ₱95.00
                                    </option>
                                </optgroup>
                            </select>
                            <input type="number" name="quantities[]" min="1" 
                                value="<?php echo htmlspecialchars($detail['quantity']); ?>" required>
                            <button type="button" class="remove-button" 
                                onclick="removeOrderDetail(this)">Remove
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <button type="button" onclick="addOrderDetail()">Add</button>
            <button type="submit" name="editOrderBtn">Update</button>
            <a href="index.php" class="back-button">Back</a>
        </form>
    </div>
</body>
</html>

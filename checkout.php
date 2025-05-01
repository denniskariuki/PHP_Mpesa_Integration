<?php
require_once 'config.php';
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phoneNumber = $_POST['phone_number'];
    $amount = $_POST['amount'];

    // Validate input (basic validation for now)
    if (empty($phoneNumber) || empty($amount) || !is_numeric($amount) || $amount <= 0) {
        $error = "Please enter a valid phone number and amount.";
    } else {
        // Initiate M-Pesa checkout
        $response = initiateMpesaCheckout($phoneNumber, $amount);

        if ($response && isset($response['CheckoutRequestID'])) {
            // Save pending transaction to the database
            savePendingTransaction($phoneNumber, $amount, $response['MerchantRequestID'], $response['CheckoutRequestID']);
            $success = "M-Pesa checkout initiated. Please check your phone to enter your PIN.";
        } else {
            $error = "Failed to initiate M-Pesa checkout. Please try again.";
            if ($response && isset($response['errorMessage'])) {
                $error .= " Error: " . $response['errorMessage'];
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>M-Pesa Checkout</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f8;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            max-width: 90%;
        }
        h2 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }
        input[type=text], input[type=number], button {
            width: calc(100% - 20px);
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
        }
        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #0056b3;
        }
        .error {
            color: #dc3545;
            margin-top: 10px;
            font-size: 14px;
        }
        .success {
            color: #28a745;
            margin-top: 10px;
            font-size: 14px;
        }
        p {
            text-align: center;
            margin-top: 20px;
        }
        a {
            color: #007bff;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        a:hover {
            color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>M-Pesa Payment</h2>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <p class="success"><?php echo $success; ?></p>
        <?php endif; ?>
        <form method="post">
            <input type="text" name="phone_number" placeholder="Enter Phone Number (e.g., 2547XXXXXXXX)" required>
            <input type="number" name="amount" placeholder="Enter Amount" required>
            <button type="submit">Pay with M-Pesa</button>
        </form>
        <p><a href="transactions.php">View Transaction History</a></p>
    </div>
</body>
</html>
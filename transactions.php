<?php
require_once 'config.php';
require_once 'db_connect.php';

$limit = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Get total number of transactions
$totalTransactions = 0;
$sql = "SELECT COUNT(*) as total FROM transactions";
if ($stmt = $conn->prepare($sql)) {
    $stmt->execute();
    $stmt->bind_result($totalTransactions);
    $stmt->fetch();
    $stmt->close();
}
$totalPages = ceil($totalTransactions / $limit);

// Get transactions for current page
$transactions = [];
$sql = "SELECT * FROM transactions ORDER BY transaction_date DESC LIMIT ? OFFSET ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("ii", $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
    $transactions = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Transaction History</title>
    <style>
        /* Styles remain the same */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
        }
        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 1200px;
        }
        h2 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.05);
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #555;
        }
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        .pagination a, .pagination span {
            color: #007bff;
            padding: 8px 12px;
            text-decoration: none;
            border: 1px solid #ddd;
            margin: 0 5px;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }
        .pagination a:hover {
            background-color: #e9ecef;
        }
        .pagination .current {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }
        .pagination .disabled {
            color: #6c757d;
            pointer-events: none;
            background-color: #e9ecef;
            border-color: #ddd;
        }
        p {
            text-align: center;
            margin-top: 20px;
        }
        a.back-link {
            color: #007bff;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        a.back-link:hover {
            color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Transaction History</h2>
        <?php if (empty($transactions)): ?>
            <p>No transactions found.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Transaction ID</th>
                        <th>Merchant Request ID</th>
                        <th>Checkout Request ID</th>
                        <th>Phone Number</th>
                        <th>Amount</th>
                        <th>Transaction Date</th>
                        <th>Payment Status</th>
                        <th>M-Pesa Receipt</th>
                        <th>Transaction Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transactions as $transaction): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($transaction['transaction_id']); ?></td>
                            <td><?php echo htmlspecialchars($transaction['merchant_request_id']); ?></td>
                            <td><?php echo htmlspecialchars($transaction['checkout_request_id']); ?></td>
                            <td><?php echo htmlspecialchars($transaction['phone_number']); ?></td>
                            <td><?php echo htmlspecialchars($transaction['amount']); ?></td>
                            <td><?php echo htmlspecialchars($transaction['transaction_date']); ?></td>
                            <td><?php echo htmlspecialchars($transaction['payment_status']); ?></td>
                            <td><?php echo htmlspecialchars($transaction['mpesa_receipt_number']); ?></td>
                            <td><?php echo htmlspecialchars($transaction['transaction_date']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?page=<?php echo ($page - 1); ?>">Previous</a>
                <?php else: ?>
                    <span class="disabled">Previous</span>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?page=<?php echo $i; ?>" class="<?php echo ($i == $page) ? 'current' : ''; ?>"><?php echo $i; ?></a>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?php echo ($page + 1); ?>">Next</a>
                <?php else: ?>
                    <span class="disabled">Next</span>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        <p><a class="back-link" href="checkout.php">Go back to Checkout</a></p>
    </div>
</body>
</html>

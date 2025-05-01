<?php
require_once 'functions.php';

// Get the M-Pesa callback data
$callbackData = file_get_contents('php://input');
$responseData = json_decode($callbackData, true);

// Log the callback data
error_log("M-Pesa Callback Data: " . $callbackData);

$resultCode = $responseData['Body']['stkCallback']['ResultCode'];
$resultDesc = $responseData['Body']['stkCallback']['ResultDesc'];
$merchantRequestID = $responseData['Body']['stkCallback']['MerchantRequestID'];
$checkoutRequestID = $responseData['Body']['stkCallback']['CheckoutRequestID'];

$items = $responseData['Body']['stkCallback']['CallbackMetadata']['Item'] ?? [];

$amount = $phoneNumber = $receiptNumber = $transactionDate = null;

if ($resultCode == 0 && !empty($items)) {
    foreach ($items as $item) {
        switch ($item['Name']) {
            case 'Amount':
                $amount = $item['Value'];
                break;
            case 'MpesaReceiptNumber':
                $receiptNumber = $item['Value'];
                break;
            case 'PhoneNumber':
                $phoneNumber = $item['Value'];
                break;
            case 'TransactionDate':
                $transactionDate = $item['Value'];
                break;
        }
    }

    if ($receiptNumber !== null) {
        updateTransactionStatus($merchantRequestID, $checkoutRequestID, $receiptNumber, $receiptNumber, $amount, $phoneNumber, 'Successful');
    } else {
        error_log("MpesaReceiptNumber not found.");
        updateTransactionStatus($merchantRequestID, $checkoutRequestID, null, null, $amount, $phoneNumber, 'Successful (Receipt Missing)');
    }
} else {
    error_log("Transaction Failed: " . $resultDesc);
    updateTransactionStatus($merchantRequestID, $checkoutRequestID, null, null, null, null, 'Failed: ' . $resultDesc);
}

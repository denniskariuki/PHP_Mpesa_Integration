<?php
require_once 'config.php';
require_once 'db_connect.php';

function generateAccessToken() {
    $url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'Content-Type:application/json',
        'Authorization: Basic ' . base64_encode(CONSUMER_KEY . ':' . CONSUMER_SECRET)
    ]);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    curl_close($curl);

    $json = json_decode($response, true);
    if (isset($json['access_token'])) {
        return $json['access_token'];
    }

    error_log("Access token generation failed: " . $response);
    return null;
}

function initiateMpesaCheckout($phoneNumber, $amount) {
    $accessToken = generateAccessToken();
    if (!$accessToken) return null;

    $timestamp = date('YmdHis');
    $password = base64_encode(BUSINESS_SHORTCODE . PASSKEY . $timestamp);

    $payload = [
        'BusinessShortCode' => BUSINESS_SHORTCODE,
        'Password' => $password,
        'Timestamp' => $timestamp,
        'TransactionType' => 'CustomerPayBillOnline',
        'Amount' => $amount,
        'PartyA' => $phoneNumber,
        'PartyB' => BUSINESS_SHORTCODE,
        'PhoneNumber' => $phoneNumber,
        'CallBackURL' => CALLBACK_URL,
        'AccountReference' => 'Payment for order',
        'TransactionDesc' => 'M-Pesa Payment'
    ];

    $curl = curl_init('https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest');
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'Content-Type:application/json',
        'Authorization:Bearer ' . $accessToken
    ]);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payload));

    $response = curl_exec($curl);
    curl_close($curl);

    error_log("STK Request Payload: " . json_encode($payload));
    error_log("STK Response: " . $response);

    return json_decode($response, true);
}

function savePendingTransaction($phoneNumber, $amount, $merchantRequestID, $checkoutRequestID) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO transactions (phone_number, amount, merchant_request_id, checkout_request_id, transaction_date, payment_status) VALUES (?, ?, ?, ?, NOW(), 'Payment Successful')");
    $stmt->bind_param("sdss", $phoneNumber, $amount, $merchantRequestID, $checkoutRequestID);
    $stmt->execute();
    if ($stmt->errno) {
        error_log("DB Error on insert: " . $stmt->error);
    }
    $stmt->close();
}

function updateTransactionStatus($merchantRequestID, $checkoutRequestID, $transactionID, $receiptNumber, $amount, $phoneNumber, $status) {
    global $conn;
    $stmt = $conn->prepare("UPDATE transactions SET transaction_id = ?, mpesa_receipt_number = ?, amount = ?, phone_number = ?, payment_status = ?, transaction_date = NOW() WHERE merchant_request_id = ? AND checkout_request_id = ?");
    $stmt->bind_param("ssdssss", $transactionID, $receiptNumber, $amount, $phoneNumber, $status, $merchantRequestID, $checkoutRequestID);
    $stmt->execute();
    if ($stmt->errno) {
        error_log("DB Error on update: " . $stmt->error);
    }
    $stmt->close();
}

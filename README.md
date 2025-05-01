# PHP_Mpesa_Integration

# 💸 M-Pesa Integration System

A simple PHP-based M-Pesa integration system that logs and displays transactions in a paginated view. Ideal for developers working with the Safaricom Daraja API.

---

## 🚀 Project Overview

This project integrates the Safaricom M-Pesa Daraja API into a PHP application, allowing users to:

- Initiate STK Push payments.
- Handle M-Pesa callback responses.
- Store and view transaction history.
- Paginate transaction records neatly.

---

## 📁 Project Structure

```bash
mpesa_integration/
├── config.php                # Configuration constants (e.g., DB credentials, API keys)
├── db_connect.php           # Database connection logic
├── checkout.php             # STK Push initiation form
├── callback_url.php         # Endpoint to receive M-Pesa callbacks
├── transactions.php         # Transaction history display
├── logs/                    # For storing raw callback logs (ignored in Git)
├── README.md                # Project documentation
└── .gitignore               # To ignore unnecessary files


 Technologies Used
PHP (Procedural)

MySQL (with MySQLi)

HTML/CSS (for frontend styling)

Daraja API (Safaricom M-Pesa)

📦 Features
✅ Implemented
STK Push initiation via Daraja API.

Dynamic transaction history with pagination.

Sorting transactions by transaction_date.

Styled HTML table with responsive layout.

Error fallback when no records are found.

🛠️ In Progress / Upcoming
Saving TransactionID and MpesaReceiptNumber reliably during callbacks.

Improved logging and error tracking.

Security enhancements (input sanitization, token checks).



Sample Callback Data

{
  "Body": {
    "stkCallback": {
      "MerchantRequestID": "12345",
      "CheckoutRequestID": "67890",
      "ResultCode": 0,
      "ResultDesc": "The service request is processed successfully.",
      "CallbackMetadata": {
        "Item": [
          { "Name": "Amount", "Value": 100 },
          { "Name": "MpesaReceiptNumber", "Value": "NLJ7RT61KP" },
          { "Name": "TransactionDate", "Value": 20250430145522 },
          { "Name": "PhoneNumber", "Value": 254712345678 }
        ]
      }
    }
  }
}



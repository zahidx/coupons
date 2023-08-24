<!DOCTYPE html>
<html>
<head>
    <title>Coupon Validation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .coupon-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
            max-width: 400px;
            width: 100%;
        }

        h2 {
            color: #333;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 10px;
        }

        input[type="number"],
        input[type="text"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 100%;
            margin-bottom: 16px;
            font-size: 16px;
        }

        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }

        #message {
            margin-top: 10px;
            font-weight: bold;
        }

        .success {
            color: #28a745; 
        }

        .danger {
            color: #d9534f;
        }
    </style>
</head>
<body>
    <div class="coupon-container">
        <h2>Coupon Validation</h2>
        <label for="total_price">Total Price:</label>
        <input type="number" id="total_price" name="total_price" value="800" min="0" step="0.01">
        <label for="couponCode">Apply Promocode:</label>
        <input type="text" id="couponCode" name="couponCode">
        <button onclick="applyCoupon()">Apply</button>
        <button onclick="resetCoupon()">Reset</button>
        <div id="message"></div>
    </div>

    <script>
        function showMessage(message, className) {
            var messageDiv = document.getElementById("message");
            messageDiv.innerText = message;
            messageDiv.classList.remove("danger", "success");
            messageDiv.classList.add(className);
            messageDiv.style.display = "block";
            
            setTimeout(function() {
                messageDiv.style.display = "none";
            }, 2500); 
        }

        function applyCoupon() {
            var couponCode = document.getElementById("couponCode").value;
            var totalPrice = parseFloat(document.getElementById("total_price").value);

            // Send the coupon code to the server for validation
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "validate_coupon.php?code=" + couponCode, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.valid) {
                        var discount = response.discount;
                        var discountedPrice = totalPrice - (totalPrice * (discount / 100));
                        document.getElementById("total_price").value = discountedPrice.toFixed(2);
                        showMessage("Coupon applied successfully!", "success");
                    } else {
                        showMessage("Invalid coupon code.", "danger");
                    }
                }
            };
            xhr.send();
        }

        function resetCoupon() {
            // Send the reset request to the server
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "reset_coupon.php", true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        showMessage("Coupon reset successfully!", "success");
                    } else if (response.alreadyReset) {
                        showMessage("Promo code is already reset.", "danger");
                    } else {
                        showMessage("Failed to reset coupon.", "danger");
                    }
                }
            };
            xhr.send();
        }
    </script>
</body>
</html>

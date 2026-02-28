<!DOCTYPE html>
<html>
<head>
    <style>
        .email-wrapper {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            border: 1px solid #e1e1e1;
            border-radius: 8px;
            overflow: hidden;
        }
        .header {
            background-color: #7ab333; /* Matching the green from the screenshot */
            color: white;
            padding: 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px;
            background-color: #ffffff;
        }
        .otp-container {
            background-color: #f9f9f9;
            border: 2px dashed #7ab333;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
        }
        .otp-code {
            font-size: 36px;
            font-weight: bold;
            color: #7ab333;
            letter-spacing: 5px;
            margin: 0;
        }
        .footer {
            background-color: #f4f4f4;
            color: #777;
            padding: 15px;
            text-align: center;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="header">
            <h1>Plantsware</h1>
        </div>
        <div class="content">
            <p>Hello,</p>
            <p>You requested a login to your <strong>Plantsware</strong> account. Please use the following One-Time Password (OTP) to complete your verification:</p>
            
            <div class="otp-container">
                <p class="otp-code">{{ $otp }}</p>
            </div>
            
            <p>This code is valid for <strong>5 minutes</strong>. For your security, please do not share this code with anyone.</p>
            <p>If you did not request this code, you can safely ignore this email.</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Plantsware. All rights reserved.
        </div>
    </div>
</body>
</html>

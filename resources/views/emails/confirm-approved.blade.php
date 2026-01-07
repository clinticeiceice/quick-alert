<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reporter Approved</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f4f6f8;
            font-family: Arial, Helvetica, sans-serif;
            color: #333333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 24px;
        }
        .header {
            text-align: center;
            padding-bottom: 16px;
            border-bottom: 1px solid #e5e7eb;
        }
        .content {
            padding: 24px 0;
            line-height: 1.6;
        }
        .button {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 20px;
            background-color: #2563eb;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
        }
        .footer {
            margin-top: 32px;
            padding-top: 16px;
            border-top: 1px solid #e5e7eb;
            font-size: 12px;
            color: #6b7280;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Reporter Approval Confirmation</h2>
        </div>

        <div class="content">
            <p>Hello {{ $reporter->name ?? 'Reporter' }},</p>

            <p>
                We’re pleased to inform you that your reporter account has been
                <strong>approved</strong>.
            </p>

            <p>
                You can now log in and begin submitting reports through the platform.
            </p>

            @isset($loginUrl)
                <p style="text-align: center;">
                    <a href="{{ $loginUrl }}" class="button">
                        Log In to Your Account
                    </a>
                </p>
            @endisset

            <p>
                If you have any questions or need assistance, feel free to contact our support team.
            </p>

            <p>
                Thank you,<br>
                {{ config('app.name') }} Team
            </p>
        </div>

        <div class="footer">
            <p>
                © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>

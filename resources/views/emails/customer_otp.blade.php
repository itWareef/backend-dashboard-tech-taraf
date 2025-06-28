<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>رمز التحقق الخاص بك</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Cairo', 'Tahoma', Arial, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            direction: rtl;
            text-align: right;
            margin: 0;
            padding: 0;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .logo-header {
            padding: 20px;
            text-align: right;
            border-bottom: 1px solid #eee;
        }

        .logo-header img {
            max-height: 60px;
            width: auto;
        }

        .email-header {
            background-color: #3498db;
            color: white;
            padding: 25px;
            text-align: center;
        }

        .email-header h1 {
            margin: 0;
            font-size: 24px;
        }

        .email-body {
            padding: 30px;
        }

        .otp-display {
            background-color: #f1f9ff;
            border-radius: 10px;
            padding: 25px;
            margin: 25px 0;
            text-align: center;
        }

        .otp-code {
            font-size: 36px;
            font-weight: bold;
            color: #2c3e50;
            letter-spacing: 5px;
            margin: 15px 0;
        }

        .instructions {
            margin-bottom: 25px;
            line-height: 1.6;
            color: #555;
        }

        .note {
            font-size: 14px;
            color: #7f8c8d;
            margin-top: 25px;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }

        .email-footer {
            background-color: #f5f5f5;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #7f8c8d;
        }

        .btn {
            display: inline-block;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            padding: 12px 30px;
            border-radius: 6px;
            font-weight: bold;
            margin: 15px 0;
        }

        .illustration {
            text-align: center;
            margin: 20px 0;
        }

        .illustration img {
            max-width: 200px;
            height: auto;
        }
    </style>
</head>
<body>
<div class="email-container">
    <!-- شعار الشركة -->
    <div class="logo-header">
        <img src="{{ asset('images/Taraf-logo-01.png') }}" alt="شعار الشركة">
    </div>

    <div class="email-header">
        <h1>رمز التحقق الخاص بك</h1>
    </div>

    <div class="email-body">
        <div class="illustration">
            <!-- رمز توضيحي -->
            <svg width="120" height="120" viewBox="0 0 24 24" fill="none"
                 xmlns="http://www.w3.org/2000/svg">
                <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z"
                      stroke="#3498db" stroke-width="2"/>
                <path d="M12 8V12L15 15M21 12C21 13.1819 20.7672 14.3522 20.3149 15.4442C19.8626 16.5361 19.1997 17.5282 18.364 18.364C17.5282 19.1997 16.5361 19.8626 15.4442 20.3149C14.3522 20.7672 13.1819 21 12 21C10.8181 21 9.64778 20.7672 8.55585 20.3149C7.46392 19.8626 6.47177 19.1997 5.63604 18.364C4.80031 17.5282 4.13738 16.5361 3.68508 15.4442C3.23279 14.3522 3 13.1819 3 12C3 9.61305 3.94821 7.32387 5.63604 5.63604C7.32387 3.94821 9.61305 3 12 3C14.3869 3 16.6761 3.94821 18.364 5.63604C20.0518 7.32387 21 9.61305 21 12Z"
                      stroke="#3498db" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>

        <p class="instructions">مرحبًا،</p>
        <p class="instructions">لقد طلبت رمز تحقق لتأكيد بريدك الإلكتروني. استخدم الرمز التالي:</p>

        <div class="otp-display">
            <div class="otp-code">{{ $otp }}</div>
            <p>هذا الرمز صالح لمدة 5 دقائق فقط</p>
        </div>

        <p class="instructions">إذا لم تطلب هذا الرمز، يرجى تجاهل هذه الرسالة أو إبلاغنا فورًا.</p>

        <p class="note">لحماية حسابك، لا تشارك هذا الرمز مع أي شخص.</p>
    </div>

    <div class="email-footer">
        <p>© 2025 شركتك. جميع الحقوق محفوظة لشركة ترف للتسويق العقاري.</p>
    </div>
</div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Glow Haircare - Welcome</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            text-align: center;
            padding: 2rem;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 90%;
            animation: fadeIn 1s ease-in;
        }

        h1 {
            color: #d63384;
            margin-bottom: 1rem;
            font-size: 2.5rem;
        }

        p {
            color: #666;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .login-btn {
            display: inline-block;
            padding: 12px 30px;
            background: #d63384;
            color: white;
            text-decoration: none;
            border-radius: 30px;
            font-weight: bold;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: 1rem;
        }

        .login-btn:hover {
            background: #b02a6e;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(214, 51, 132, 0.3);
        }

        .signup-link {
            margin-top: 1.5rem;
            display: block;
            color: #666;
        }

        .signup-link a {
            color: #d63384;
            text-decoration: none;
            font-weight: bold;
        }

        .signup-link a:hover {
            text-decoration: underline;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .features {
            display: flex;
            justify-content: space-around;
            margin: 2rem 0;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .feature {
            flex: 1;
            min-width: 150px;
            padding: 1rem;
            background: rgba(214, 51, 132, 0.1);
            border-radius: 10px;
            transition: transform 0.3s ease;
        }

        .feature:hover {
            transform: scale(1.05);
        }

        .feature h3 {
            color: #d63384;
            margin-bottom: 0.5rem;
        }

        .feature p {
            font-size: 0.9rem;
            margin-bottom: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome to Glow Haircare</h1>
        <p>Your journey to beautiful, healthy hair starts here. Discover our premium hair care products and services.</p>
        
        <div class="features">
            <div class="feature">
                <h3>Premium Products</h3>
                <p>High-quality hair care solutions</p>
            </div>
            <div class="feature">
                <h3>Expert Advice</h3>
                <p>Professional hair care tips</p>
            </div>
            <div class="feature">
                <h3>Personalized Care</h3>
                <p>Customized solutions for you</p>
            </div>
        </div>

        <a href="login.php" class="login-btn">Login to Your Account</a>
        <div class="signup-link">
            Don't have an account? <a href="signup.php">Sign up here</a>
        </div>
    </div>
</body>
</html> 
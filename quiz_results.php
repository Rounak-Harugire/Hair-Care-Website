<?php
session_start();
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get quiz results from session
$quiz_results = $_SESSION['quiz_results'] ?? [];
$hair_type = $quiz_results['hair_type'] ?? '';
$hair_concern = $quiz_results['hair_concern'] ?? '';

// If no results in session, redirect back to quiz
if (empty($hair_type) || empty($hair_concern)) {
    header("Location: quiz.php");
    exit();
}

// Define product recommendations based on hair type and concerns
$recommendations = [
    'dry' => [
        'name' => 'Dry Hair',
        'products' => [
            [
                'name' => 'Hydrating Shampoo',
                'description' => 'Deeply moisturizing shampoo with natural oils',
                'image' => 'https://cdn.store-assets.com/s/251247/i/49510410.png',
                'price' => '$24.99'
            ],
            [
                'name' => 'Nourishing Hair Mask',
                'description' => 'Weekly treatment for intense hydration',
                'image' => 'https://tse1.mm.bing.net/th?id=OIP.2xI4HCOQ0K7_KfAQDuymLAHaHa&pid=Api&P=0&h=180',
                'price' => '$29.99'
            ]
        ]
    ],
    'oily' => [
        'name' => 'Oily Hair',
        'products' => [
            [
                'name' => 'Clarifying Shampoo',
                'description' => 'Gentle cleansing formula for oily scalp',
                'image' => 'https://www.kerastase.co.uk/dw/image/v2/AAQP_PRD/on/demandware.static/-/Sites-ker-master-catalog/en_GB/dwf0c0eb40/2024/KER_00303/ELIXIR_75ml_1.jpg',
                'price' => '$22.99'
            ],
            [
                'name' => 'Scalp Balancing Treatment',
                'description' => 'Regulates oil production and soothes scalp',
                'image' => 'https://images.heb.com/is/image/HEBGrocery/005863199-1',
                'price' => '$27.99'
            ]
        ]
    ],
    'normal' => [
        'name' => 'Normal Hair',
        'products' => [
            [
                'name' => 'Daily Care Shampoo',
                'description' => 'Balanced formula for everyday use',
                'image' => 'https://cdn.store-assets.com/s/251247/i/49510410.png',
                'price' => '$19.99'
            ],
            [
                'name' => 'Volume Boosting Spray',
                'description' => 'Lightweight formula for natural volume',
                'image' => 'https://images.heb.com/is/image/HEBGrocery/005863199-1',
                'price' => '$21.99'
            ]
        ]
    ]
];

// Get recommendations based on hair type
$recommendation = isset($recommendations[$hair_type]) ? $recommendations[$hair_type] : $recommendations['normal'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Personalized Recommendations - Glow Haircare</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
        }
        .results-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        .results-header {
            text-align: center;
            margin-bottom: 3rem;
            padding: 2rem;
            background: linear-gradient(135deg, #d63384 0%, #b02a6e 100%);
            color: white;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .results-header h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        .results-header p {
            font-size: 1.2rem;
            opacity: 0.9;
        }
        .product-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .product-card:hover {
            transform: translateY(-5px);
        }
        .product-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 1rem;
        }
        .product-title {
            color: #d63384;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        .product-price {
            color: #666;
            font-weight: 500;
            margin-bottom: 1rem;
        }
        .product-description {
            color: #666;
            margin-bottom: 1.5rem;
        }
        .btn-add-to-cart {
            background: #d63384;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
            width: 100%;
        }
        .btn-add-to-cart:hover {
            background: #b02a6e;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(214, 51, 132, 0.3);
        }
        .hair-type-badge {
            background: rgba(214, 51, 132, 0.1);
            color: #d63384;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 1rem;
        }
        .recommendation-section {
            margin-bottom: 3rem;
        }
        .section-title {
            color: #333;
            font-weight: 600;
            margin-bottom: 1.5rem;
            text-align: center;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">Glow Haircare</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="profile.php">Profile</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="results-container">
        <div class="results-header">
            <h1>Your Personalized Recommendations</h1>
            <p>Based on your hair type and concerns, we've selected these products just for you</p>
            <span class="hair-type-badge">
                <i class="fas fa-cut me-2"></i><?php echo htmlspecialchars($recommendation['name']); ?>
            </span>
        </div>

        <div class="recommendation-section">
            <h2 class="section-title">Recommended Products</h2>
            <div class="row">
                <?php foreach ($recommendation['products'] as $product): ?>
                <div class="col-md-6">
                    <div class="product-card">
                        <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-image">
                        <h3 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h3>
                        <div class="product-price"><?php echo htmlspecialchars($product['price']); ?></div>
                        <p class="product-description"><?php echo htmlspecialchars($product['description']); ?></p>
                        <button class="btn btn-add-to-cart">
                            <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="text-center mb-4">
            <a href="index.php" class="btn btn-outline-primary btn-lg">
                <i class="fas fa-arrow-left me-2"></i>Back to Home
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 
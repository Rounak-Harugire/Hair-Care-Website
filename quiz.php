<?php
session_start();
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Store quiz results in session
    $_SESSION['quiz_results'] = [
        'hair_type' => $_POST['hair_type'] ?? '',
        'hair_concern' => $_POST['hair_concern'] ?? ''
    ];
    
    // Ensure headers haven't been sent
    if (!headers_sent()) {
        header("Location: quiz_results.php");
        exit();
    } else {
        echo "<script>window.location.href='quiz_results.php';</script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hair Care Quiz - Glow Haircare</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', sans-serif;
        }
        .quiz-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .quiz-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .quiz-header h1 {
            color: #d63384;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        .quiz-header p {
            color: #666;
            font-size: 1.1rem;
        }
        .question-card {
            background: #fff;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            border: 2px solid #eee;
            transition: all 0.3s ease;
        }
        .question-card:hover {
            border-color: #d63384;
        }
        .question-title {
            color: #333;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        .option-label {
            display: block;
            padding: 1rem;
            margin-bottom: 0.5rem;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid #eee;
        }
        .option-label:hover {
            background: rgba(214, 51, 132, 0.05);
            border-color: #d63384;
        }
        .option-input {
            display: none;
        }
        .option-input:checked + .option-label {
            background: rgba(214, 51, 132, 0.1);
            border-color: #d63384;
            color: #d63384;
        }
        .btn-submit {
            background: #d63384;
            border: none;
            padding: 1rem 2rem;
            border-radius: 25px;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
            width: 100%;
            margin-top: 1rem;
        }
        .btn-submit:hover {
            background: #b02a6e;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(214, 51, 132, 0.3);
        }
        .progress-bar {
            height: 5px;
            background: #eee;
            border-radius: 5px;
            margin-bottom: 2rem;
            overflow: hidden;
        }
        .progress-fill {
            height: 100%;
            background: #d63384;
            width: 0%;
            transition: width 0.3s ease;
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

    <div class="quiz-container">
        <div class="quiz-header">
            <h1>Discover Your Perfect Hair Care Routine</h1>
            <p>Answer a few questions to get personalized product recommendations</p>
        </div>

        <div class="progress-bar">
            <div class="progress-fill" id="quizProgress"></div>
        </div>

        <form id="quizForm" method="POST" action="">
            <div class="question-card">
                <h3 class="question-title">What is your hair type?</h3>
                <div class="options">
                    <input type="radio" name="hair_type" value="dry" id="dry" class="option-input" required>
                    <label for="dry" class="option-label">Dry and Frizzy</label>

                    <input type="radio" name="hair_type" value="oily" id="oily" class="option-input">
                    <label for="oily" class="option-label">Oily and Greasy</label>

                    <input type="radio" name="hair_type" value="normal" id="normal" class="option-input">
                    <label for="normal" class="option-label">Normal and Balanced</label>
                </div>
            </div>

            <div class="question-card">
                <h3 class="question-title">What is your main hair concern?</h3>
                <div class="options">
                    <input type="radio" name="hair_concern" value="damage" id="damage" class="option-input" required>
                    <label for="damage" class="option-label">Damage and Breakage</label>

                    <input type="radio" name="hair_concern" value="volume" id="volume" class="option-input">
                    <label for="volume" class="option-label">Lack of Volume</label>

                    <input type="radio" name="hair_concern" value="growth" id="growth" class="option-input">
                    <label for="growth" class="option-label">Hair Growth</label>

                    <input type="radio" name="hair_concern" value="dandruff" id="dandruff" class="option-input">
                    <label for="dandruff" class="option-label">Dandruff</label>
                </div>
            </div>

            <button type="submit" class="btn btn-submit">
                <i class="fas fa-check-circle me-2"></i>Get My Recommendations
            </button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Update progress bar based on answered questions
        const inputs = document.querySelectorAll('.option-input');
        const progressBar = document.getElementById('quizProgress');
        
        inputs.forEach(input => {
            input.addEventListener('change', () => {
                const answered = document.querySelectorAll('.option-input:checked').length;
                const total = document.querySelectorAll('.option-input').length;
                const progress = (answered / total) * 100;
                progressBar.style.width = `${progress}%`;
            });
        });

        // Ensure form submission works
        document.getElementById('quizForm').addEventListener('submit', function(e) {
            // Validate that both questions are answered
            const hairType = document.querySelector('input[name="hair_type"]:checked');
            const hairConcern = document.querySelector('input[name="hair_concern"]:checked');
            
            if (!hairType || !hairConcern) {
                e.preventDefault();
                alert('Please answer all questions before submitting.');
            }
        });
    </script>
</body>
</html> 
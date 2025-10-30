<?php
session_start();
require_once 'db.php';

// Session timeout (30 minutes)
$timeout = 1800; // 30 minutes in seconds
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout)) {
    session_unset();
    session_destroy();
    header("Location: login.php?timeout=1");
    exit();
}
$_SESSION['last_activity'] = time();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Initialize variables
$appointment_count = 0;
$error_message = '';
$success_message = '';

// Get user's appointment count
if ($conn) {
    $count_stmt = $conn->prepare("SELECT COUNT(*) as total FROM appointments WHERE user_id = ?");
    if ($count_stmt) {
        $count_stmt->bind_param("i", $_SESSION['user_id']);
        $count_stmt->execute();
        $result = $count_stmt->get_result();
        if ($result) {
            $appointment_count = $result->fetch_assoc()['total'];
        }
        $count_stmt->close();
    }
}

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    
    if ($name && $email) {
        $update_stmt = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
        if ($update_stmt) {
            $update_stmt->bind_param("ssi", $name, $email, $_SESSION['user_id']);
            if ($update_stmt->execute()) {
                $_SESSION['user_name'] = $name;
                $_SESSION['user_email'] = $email;
                $success_message = "Profile updated successfully!";
            } else {
                $error_message = "Error updating profile. Please try again.";
            }
            $update_stmt->close();
        }
    }
}

// Handle logout
if (isset($_GET['logout'])) {
    // Clear all session variables
    $_SESSION = array();
    
    // Destroy the session
    session_destroy();
    
    // Redirect to home page
    header("Location: index.php");
    exit();
}

// Fetch user data
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Glow Haircare</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Auto-hide messages after 5 seconds
        $(document).ready(function() {
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 5000);

            // Handle profile edit
            $('.edit-btn').click(function() {
                $('.form-control').prop('readonly', false);
                $(this).html('<i class="fas fa-save me-2"></i>Save Changes');
                $(this).removeClass('edit-btn').addClass('save-btn');
            });

            // Handle form submission
            $('body').on('click', '.save-btn', function() {
                $('#profile-form').submit();
            });
        });
    </script>
    <style>
        body {
            background: #f5f5f5;
            font-family: 'Poppins', sans-serif;
        }
        .profile-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        .profile-header {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            padding: 2rem;
            border-radius: 20px;
            margin-bottom: 2rem;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            position: relative;
            overflow: hidden;
        }
        .profile-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="40" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="2"/></svg>') repeat;
            opacity: 0.1;
        }
        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 3rem;
            color: #2c3e50;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border: 4px solid white;
        }
        .profile-stats {
            background: white;
            border-radius: 20px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: transform 0.3s ease;
            border: 1px solid #eee;
        }
        .profile-stats:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
        .stat-item {
            text-align: center;
            padding: 1rem;
            border-right: 1px solid #eee;
        }
        .stat-item:last-child {
            border-right: none;
        }
        .stat-number {
            font-size: 1.8rem;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }
        .stat-label {
            color: #7f8c8d;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .profile-section {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: transform 0.3s ease;
            border: 1px solid #eee;
        }
        .profile-section:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
        .section-title {
            color: #2c3e50;
            margin-bottom: 1.5rem;
            font-weight: 600;
            position: relative;
            padding-bottom: 0.5rem;
        }
        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background: #2c3e50;
        }
        .edit-btn {
            background: #2c3e50;
            border: none;
            padding: 0.5rem 1.5rem;
            border-radius: 25px;
            color: white;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        .edit-btn:hover {
            background: #34495e;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(44, 62, 80, 0.3);
        }
        .nav-pills .nav-link {
            color: #7f8c8d;
            padding: 0.8rem 1.5rem;
            border-radius: 10px;
            margin-bottom: 0.5rem;
            transition: all 0.3s ease;
            border: 1px solid #eee;
        }
        .nav-pills .nav-link:hover {
            background: rgba(44, 62, 80, 0.05);
            color: #2c3e50;
            border-color: #ddd;
        }
        .nav-pills .nav-link.active {
            background-color: #2c3e50;
            color: white;
            border-color: #2c3e50;
        }
        .form-control {
            border: 1px solid #eee;
            padding: 0.8rem;
            border-radius: 10px;
            transition: all 0.3s ease;
            background-color: #f9f9f9;
        }
        .form-control:focus {
            border-color: #2c3e50;
            box-shadow: 0 0 0 0.2rem rgba(44, 62, 80, 0.1);
            background-color: white;
        }
        .list-group-item {
            border: none;
            padding: 1rem;
            margin-bottom: 0.5rem;
            border-radius: 10px !important;
            transition: all 0.3s ease;
            background-color: #f9f9f9;
        }
        .list-group-item:hover {
            background: rgba(44, 62, 80, 0.05);
            transform: translateX(5px);
        }
        .navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-bottom: 1px solid #eee;
        }
        .navbar-brand {
            color: #2c3e50 !important;
            font-weight: 700;
        }
        .nav-link {
            color: #7f8c8d !important;
            font-weight: 500;
        }
        .nav-link:hover {
            color: #2c3e50 !important;
        }
        @media (max-width: 768px) {
            .profile-header {
                padding: 1.5rem;
            }
            .profile-avatar {
                width: 100px;
                height: 100px;
                font-size: 2.5rem;
            }
            .stat-number {
                font-size: 1.5rem;
            }
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
                        <a class="nav-link" href="quiz.php">Take Quiz</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="?logout=1">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="profile-container">
        <?php if ($error_message): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $error_message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if ($success_message): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $success_message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="profile-header text-center">
            <div class="profile-avatar">
                <i class="fas fa-user"></i>
            </div>
            <h2><?php echo htmlspecialchars($user['name']); ?></h2>
            <p class="mb-0"><?php echo htmlspecialchars($user['email']); ?></p>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="profile-stats">
                    <div class="row">
                        <div class="col-6 stat-item">
                            <div class="stat-number"><?php echo $appointment_count; ?></div>
                            <div class="stat-label">Appointments</div>
                        </div>
                        <div class="col-6 stat-item">
                            <div class="stat-number">0</div>
                            <div class="stat-label">Reviews</div>
                        </div>
                    </div>
                </div>

                <div class="profile-section">
                    <h4 class="section-title">Account Settings</h4>
                    <ul class="nav nav-pills flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="#"><i class="fas fa-user me-2"></i>Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="appointment.php"><i class="fas fa-calendar me-2"></i>Appointments</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#"><i class="fas fa-heart me-2"></i>Favorites</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#"><i class="fas fa-cog me-2"></i>Settings</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="col-md-8">
                <form id="profile-form" method="POST" action="">
                    <div class="profile-section">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="section-title mb-0">Personal Information</h4>
                            <button type="button" class="edit-btn"><i class="fas fa-edit me-2"></i>Edit Profile</button>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($_SESSION['user_name'] ?? ''); ?>" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($_SESSION['user_email'] ?? ''); ?>" readonly>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Member Since</label>
                                <input type="text" class="form-control" value="<?php echo date('F Y', strtotime($_SESSION['created_at'] ?? '')); ?>" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Account Status</label>
                                <input type="text" class="form-control" value="Active" readonly>
                            </div>
                        </div>
                        <input type="hidden" name="update_profile" value="1">
                    </div>
                </form>

                <div class="profile-section">
                    <h4 class="section-title">Recent Activity</h4>
                    <div class="list-group">
                        <?php if ($appointment_count > 0): ?>
                            <a href="#" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1">Latest Appointment</h5>
                                    <small class="text-muted">Recently</small>
                                </div>
                                <p class="mb-1">You have <?php echo $appointment_count; ?> appointment(s) scheduled</p>
                            </a>
                        <?php else: ?>
                            <div class="list-group-item">
                                <p class="mb-1 text-muted">No recent activity</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 
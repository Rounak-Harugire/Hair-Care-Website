<?php
session_start();
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$message = '';
$error = '';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $service_type = $_POST['service_type'] ?? '';
    $appointment_date = $_POST['appointment_date'] ?? '';
    $appointment_time = $_POST['appointment_time'] ?? '';

    // Validate inputs
    if (empty($name) || empty($email) || empty($phone) || empty($service_type) || empty($appointment_date) || empty($appointment_time)) {
        $error = "All fields are required";
    } else {
        // Insert appointment into database
        $stmt = $conn->prepare("INSERT INTO appointments (user_id, name, email, phone, service_type, appointment_date, appointment_time) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssss", $_SESSION['user_id'], $name, $email, $phone, $service_type, $appointment_date, $appointment_time);
        
        if ($stmt->execute()) {
            $message = "Appointment booked successfully!";
        } else {
            $error = "Error booking appointment. Please try again.";
        }
        $stmt->close();
    }
}

// Get appointment count
$count_stmt = $conn->prepare("SELECT COUNT(*) as total FROM appointments WHERE user_id = ?");
$count_stmt->bind_param("i", $_SESSION['user_id']);
$count_stmt->execute();
$result = $count_stmt->get_result();
$appointment_count = $result->fetch_assoc()['total'];
$count_stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment - Glow Hair Care</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
        }
        .appointment-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .appointment-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .appointment-count {
            background: #d63384;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 1rem;
        }
        .form-control:focus {
            border-color: #d63384;
            box-shadow: 0 0 0 0.2rem rgba(214, 51, 132, 0.25);
        }
        .btn-book {
            background: #d63384;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
            width: 100%;
        }
        .btn-book:hover {
            background: #b02a6e;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(214, 51, 132, 0.3);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">Glow Hair Care</a>
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

    <div class="appointment-container">
        <div class="appointment-header">
            <h1>Book Your Appointment</h1>
            <div class="appointment-count">
                <i class="fas fa-calendar-check me-2"></i>Total Appointments: <?php echo $appointment_count; ?>
            </div>
            <?php if ($message): ?>
                <div class="alert alert-success"><?php echo $message; ?></div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
        </div>

        <form method="POST" action="">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="tel" class="form-control" id="phone" name="phone" required>
                </div>
                <div class="col-md-6">
                    <label for="service_type" class="form-label">Service Type</label>
                    <select class="form-select" id="service_type" name="service_type" required>
                        <option value="">Select a service</option>
                        <option value="Hair Spa">Hair Spa</option>
                        <option value="Scalp Massage">Scalp Massage</option>
                        <option value="Organic Treatment">Organic Treatment</option>
                        <option value="Haircut">Haircut</option>
                        <option value="Coloring">Coloring</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="appointment_date" class="form-label">Appointment Date</label>
                    <input type="date" class="form-control" id="appointment_date" name="appointment_date" required>
                </div>
                <div class="col-md-6">
                    <label for="appointment_time" class="form-label">Appointment Time</label>
                    <input type="time" class="form-control" id="appointment_time" name="appointment_time" required>
                </div>
            </div>

            <button type="submit" class="btn btn-book">
                <i class="fas fa-calendar-plus me-2"></i>Book Appointment
            </button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Set minimum date to today
        document.getElementById('appointment_date').min = new Date().toISOString().split('T')[0];
        
        // Set time constraints (9 AM to 6 PM)
        document.getElementById('appointment_time').min = '09:00';
        document.getElementById('appointment_time').max = '18:00';
    </script>
</body>
</html> 
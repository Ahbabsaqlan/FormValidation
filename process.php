<?php
session_start();

function e($val) {
    return htmlspecialchars($val, ENT_QUOTES, 'UTF-8');
}

// Handle direct unauthorized access
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("<p style='text-align:center; color:red; font-weight:bold;'>Invalid access. <a href='index.php'>Go back</a></p>");
}

// Handle the final confirmation to insert data into DB
if (isset($_POST['finalConfirm'])) {
    $form = $_SESSION['form_data'];

    // Database connection
    $conn = new mysqli("localhost", "root", "", "myDataBase");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if email already exists
    $check = $conn->prepare("SELECT email FROM user WHERE email = ?");
    if (!$check) {
        die("Prepare failed: " . $conn->error);
    }

    $check->bind_param("s", $form['email']);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "<script>
                alert('This email is already registered. Please use a different one.');
                window.location.href = 'index.php';
              </script>";
        $check->close();
        $conn->close();
        exit;
    }
    $check->close();

    // Insert user
    $stmt = $conn->prepare("INSERT INTO user (userName, email, dob, gender, country, opinion, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("sssssss",
        $form['uname'],
        $form['email'],
        $form['dob'],
        $form['gender'],
        $form['country'],
        $form['opinion'],
        $form['password']
    );

    if ($stmt->execute()) {
        unset($_SESSION['form_data']);
        echo "<script>
                alert('Registration Completed Successfully!');
                window.location.href = 'index.php';
              </script>";
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}

// Handle Edit button
if (isset($_POST['edit'])) {
    $_SESSION['allow_edit'] = true;
    header("Location: index.php");
    exit;
}

// Save form data to session if coming from index.php
if (!isset($_SESSION['form_data']) && $_POST) {
    $_SESSION['form_data'] = $_POST;
}

$form = $_SESSION['form_data'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Confirm Your Details</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f5f5f7;
            margin: 0;
            padding: 40px 20px;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            max-width: 500px;
            width: 100%;
            padding: 30px 40px;
        }
        h2 {
            font-weight: 600;
            margin-bottom: 25px;
            text-align: center;
            color: #1d1d1f;
        }
        .confirm-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .confirm-table th,
        .confirm-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #e5e5ea;
            font-size: 15px;
            color: #1d1d1f;
        }
        .confirm-table th {
            width: 35%;
            font-weight: 600;
            color: #6e6e73;
            background-color: #f5f5f7;
            border-radius: 10px 0 0 10px;
        }
        .confirm-table td {
            background-color: #fff;
            border-radius: 0 10px 10px 0;
        }
        .buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }
        button {
            padding: 12px 20px;
            font-size: 15px;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            transition: background 0.3s ease;
            width: 48%;
        }
        .confirm-btn {
            background-color: #007aff;
            color: white;
        }
        .confirm-btn:hover {
            background-color: #005ecb;
        }
        .edit-btn {
            background-color: #e5e5ea;
            color: #1d1d1f;
        }
        .edit-btn:hover {
            background-color: #d1d1d6;
        }
    </style>
</head>
<body>

<div class="card">
    <h2>Confirm Your Information</h2>
    <table class="confirm-table">
        <tbody>
            <tr><th>Username</th><td><?= e($form['uname']); ?></td></tr>
            <tr><th>Email</th><td><?= e($form['email']); ?></td></tr>
            <tr><th>Date of Birth</th><td><?= e($form['dob']); ?></td></tr>
            <tr><th>Gender</th><td><?= e($form['gender']); ?></td></tr>
            <tr><th>Country</th><td><?= e($form['country']); ?></td></tr>
            <tr><th>Opinion</th><td><?= nl2br(e($form['opinion'])); ?></td></tr>
            <tr><th>Background Color</th><td><?= e($form['color']); ?></td></tr>
        </tbody>
    </table>

    <div class="buttons">
        <form method="post" style="width: 48%;">
            <button class="confirm-btn" type="submit" name="finalConfirm">✅ Confirm</button>
        </form>
        <form method="post" style="width: 48%;">
            <button class="edit-btn" type="submit" name="edit">🔁 Edit</button>
        </form>
    </div>
</div>

</body>
</html>

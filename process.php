<?php
session_start();

function e($val) {
    return htmlspecialchars($val, ENT_QUOTES, 'UTF-8');
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['finalConfirm'])) {
        $form = $_SESSION['form_data'];

        $conn = new mysqli("localhost", "root", "", "myDataBase");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $stmt = $conn->prepare("INSERT INTO user (userName, email, dob, gender, country, opinion, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
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
                    window.location.href = 'index.html';
                </script>";
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    } else {
        $_SESSION['form_data'] = $_POST;
    }
} else {
    die("<p style='text-align:center; color:red; font-weight:bold;'>Invalid access. <a href='index.html'>Go back</a></p>");
}

$form = $_SESSION['form_data'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Confirm Your Details</title>
<style>
  @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap');
  body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    background-color: #f5f5f7;
    margin: 0;
    padding: 40px 20px;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
  }
  .card {
    background-color: #fff;
    border-radius: 20px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    max-width: 500px;
    width: 100%;
    padding: 30px 40px;
  }
  h2 {
    font-weight: 600;
    color: #1d1d1f;
    margin-bottom: 25px;
    text-align: center;
  }
  .confirm-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 25px;
  }
  .confirm-table th,
  .confirm-table td {
    padding: 12px 15px;
    text-align: left;
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
  .color-box {
    display: inline-block;
    width: 24px;
    height: 24px;
    border-radius: 6px;
    border: 1px solid #ccc;
    vertical-align: middle;
    margin-right: 10px;
  }
  .buttons {
    margin-top: 30px;
    display: flex;
    justify-content: space-between;
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
      <tr><th>Username</th><td><?php echo e($form['uname']); ?></td></tr>
      <tr><th>Email</th><td><?php echo e($form['email']); ?></td></tr>
      <tr><th>Date of Birth</th><td><?php echo e($form['dob']); ?></td></tr>
      <tr><th>Gender</th><td><?php echo e($form['gender']); ?></td></tr>
      <tr><th>Country</th><td><?php echo e($form['country']); ?></td></tr>
      <tr><th>Opinion</th><td><?php echo nl2br(e($form['opinion'])); ?></td></tr>
      <tr><th>Favorite Color</th><td><span class="color-box" style="background-color: <?php echo e($form['color'] ?: '#fff'); ?>;"></span><?php echo e($form['color']); ?></td></tr>
    </tbody>
  </table>

  <div class="buttons">
    <form method="post" style="width: 48%;">
      <button class="confirm-btn" type="submit" name="finalConfirm">✅ Confirm</button>
    </form>
    <form action="index.html" method="get" style="width: 48%;">
      <button class="edit-btn" type="submit">🔁 Edit</button>
    </form>
  </div>
</div>

</body>
</html>

<?php
session_start();

// If user is already logged in, redirect to home page
if (isset($_SESSION['user'])) {
    header("Location: home.php");
    exit;
}

// Check if the form is being accessed via "Edit" action
if (isset($_SESSION['allow_edit']) && $_SESSION['allow_edit'] === true) {
    $formData = $_SESSION['form_data'] ?? [];
    unset($_SESSION['allow_edit']); // Use once and clear
} else {
    // If not allowed, clear the form data
    unset($_SESSION['form_data']);
    $formData = [];
}

$login_error = '';

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $conn = new mysqli("localhost", "root", "", "myDataBase");
    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }

    $uname = trim($_POST['lgUname']);
    $upass = trim($_POST['lgUpass']);

    $uname = mysqli_real_escape_string($conn, $uname);
    $upass = mysqli_real_escape_string($conn, $upass);

    $sql = "SELECT * FROM user WHERE email = '$uname'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows >= 1) {
        $user = $result->fetch_assoc();
        if (strcmp(trim($upass), trim($user['password'])) === 0) {
            $_SESSION['user'] = $user;
            echo "<script>window.location.href = 'home.php';</script>";
            exit;
        } else {
            $login_error = "Incorrect password.";
        }
    } else {
        $login_error = "User not found.";
    }

    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AQI Registration</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body>

<!-- Navigation bar -->
<nav class="navbar">
    <div class="nav-logo">
        <h1>Lab Practise</h1>
    </div>
    
    <div class="nav-search">
        <a href="#login-section">Login</a>
    </div>
</nav>

<!-- Main Content -->
<div class="main">

    <!-- Registration Form -->
    <div class="cls1">
        <h4>Register</h4>
        <form id="registrationForm" action="process.php" method="post">
        
            <div class="form-group" id="usernameGroup">
                <label for="username">Username</label>
                <input type="text" id="username" name="uname" value="<?= htmlspecialchars($formData['uname'] ?? '') ?>"/>
                <div class="error"></div>
            </div>

            <div class="form-group" id="emailGroup">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($formData['email'] ?? '') ?>"/>
                <div class="error"></div>
            </div>

            <div class="form-group" id="dobGroup">
                <label for="dob">Date of Birth</label>
                <input type="date" id="dob" name="dob" value="<?= htmlspecialchars($formData['dob'] ?? '') ?>"/>
                <div class="error"></div>
            </div>

            <div class="form-group" id="genderGroup">
                <label>Gender</label>
                <div class="radio-group">
                    <label><input type="radio" name="gender" value="Male" <?= ($formData['gender'] ?? '') === 'Male' ? 'checked' : '' ?> /> Male</label>
                    <label><input type="radio" name="gender" value="Female" <?= ($formData['gender'] ?? '') === 'Female' ? 'checked' : '' ?> /> Female</label>
                    <label><input type="radio" name="gender" value="Other" <?= ($formData['gender'] ?? '') === 'Other' ? 'checked' : '' ?> /> Other</label>
                </div>
                <div class="error"></div>
            </div>

            <div class="form-group" id="countryGroup">
                <label for="country">Country</label>
                <select id="country" name="country">
                    <option value="">Select country</option>
                    <?php foreach(['Bangladesh', 'India', 'Pakistan', 'Malaysia', 'Other'] as $country): ?>
                        <option value="<?= $country ?>" <?= ($formData['country'] ?? '') === $country ? 'selected' : '' ?>>
                            <?= $country ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="error"></div>
            </div>

            <div class="form-group" id="opinionGroup">
                <label for="opinion">Your Opinion</label>
                <textarea id="opinion" rows="2" name="opinion"><?= htmlspecialchars($formData['opinion'] ?? '') ?></textarea>
                <div class="error"></div>
            </div>

            <div class="form-group" id="passwordGroup">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" value="<?= htmlspecialchars($formData['password'] ?? '') ?>"/>
                <div class="error"></div>
            </div>

            <div class="form-group" id="confirmGroup">
                <label for="confirmPassword">Confirm Password</label>
                <input type="password" id="confirmPassword" name="confirmPassword" value="<?= htmlspecialchars($formData['confirmPassword'] ?? '') ?>"/>
                <div class="error"></div>
            </div>

            <div class="form-group" id="selectColorGroup">
                <label for="color">Select Background Color</label>
                <input type="color" id="color" name="color" value="<?= htmlspecialchars($formData['color'] ?? '#ffffff') ?>"/>
                <div class="error"></div>
            </div>

            <div class="form-group" id="termGroup">
                <input type="checkbox" id="term" name="term" <?= !empty($formData['term']) ? 'checked' : '' ?> />
                <label for="term">I agree to the terms and conditions</label>
                <div class="error termError"></div>
            </div>

            <button type="submit">Submit</button>
        </form>
    </div>

    <!-- AQI Index (unchanged) -->
    <div class="cls2">
    <div class="ranking-card">
    <header class="ranking-header">
      <h1>City ranking</h1>
      <p>Cities with high air pollution (AQI*)</p>
      <span class="info-icon">ℹ️</span>
    </header>
    <table class="ranking-table">
      <thead>
        <tr>
          <th>#</th>
          <th>MAJOR CITY</th>
          <th>US AQI*</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>1</td>
          <td>
            <img src="https://flagcdn.com/24x18/bd.png " alt="Bangladesh Flag" class="flag-icon">
            Dhaka, Bangladesh
          </td>
          <td><span class="aqi-value red">160</span></td>
        </tr>
        <tr>
          <td>2</td>
          <td>
            <img src="https://flagcdn.com/24x18/cl.png " alt="Chile Flag" class="flag-icon">
            Santiago, Chile
          </td>
          <td><span class="aqi-value red">159</span></td>
        </tr>
        <tr>
          <td>3</td>
          <td>
            <img src="https://flagcdn.com/24x18/ug.png " alt="Uganda Flag" class="flag-icon">
            Kampala, Uganda
          </td>
          <td><span class="aqi-value red">156</span></td>
        </tr>
        <tr>
          <td>4</td>
          <td>
            <img src="https://flagcdn.com/24x18/in.png " alt="India Flag" class="flag-icon">
            Delhi, India
          </td>
          <td><span class="aqi-value orange">131</span></td>
        </tr>
        <tr>
          <td>5</td>
          <td>
            <img src="https://flagcdn.com/24x18/ae.png " alt="United Arab Emirates Flag" class="flag-icon">
            Dubai, United Arab Emirates
          </td>
          <td><span class="aqi-value orange">124</span></td>
        </tr>
        <tr>
          <td>6</td>
          <td>
            <img src="https://flagcdn.com/24x18/cd.png " alt="Democratic Republic of the Congo Flag" class="flag-icon">
            Kinshasa, Democratic Republic of the Congo
          </td>
          <td><span class="aqi-value orange">122</span></td>
        </tr>
        <tr>
          <td>7</td>
          <td>
            <img src="https://flagcdn.com/24x18/cn.png " alt="China Flag" class="flag-icon">
            Beijing, China
          </td>
          <td><span class="aqi-value yellow">115</span></td>
        </tr>
        <tr>
          <td>8</td>
          <td>
            <img src="https://flagcdn.com/24x18/uz.png " alt="Uzbekistan Flag" class="flag-icon">
            Tashkent, Uzbekistan
          </td>
          <td><span class="aqi-value yellow">105</span></td>
        </tr>
        <tr>
          <td>9</td>
          <td>
            <img src="https://flagcdn.com/24x18/id.png " alt="Indonesia Flag" class="flag-icon">
            Batam, Indonesia
          </td>
          <td><span class="aqi-value yellow">103</span></td>
        </tr>
        <tr>
          <td>10</td>
          <td>
            <img src="https://flagcdn.com/24x18/id.png " alt="Indonesia Flag" class="flag-icon">
            Jakarta, Indonesia
          </td>
          <td><span class="aqi-value yellow">103</span></td>
        </tr>
      </tbody>
    </table>
  </div>
    </div>

    <!-- Login Section -->
    <div class="cls3" id="login-section">
        <div class="login-card">
			<div class="profile-placeholder">
        		<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
          			<path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0-6c-2.67 0-8 1.33-8 4v2h16v-2c0-2.67-5.33-4-8-4z"/>
        		</svg>
      		</div>

      <!-- Login Title -->
			<h2 class="login-title">LOGIN</h2>

			<?php if (!empty($login_error)): ?>
			<div class="error" style="color:red; margin-bottom:10px;"><?= htmlspecialchars($login_error) ?></div>
			<?php endif; ?>

			<form method="POST" action="" id="login-form">
				<input type="hidden" name="login" value="1">
				<div class="input-field lg-group">
					<input type="text" placeholder="Email" id="lgUname" name="lgUname" />
					<div class="error"></div>
				</div>
				<div class="input-field lg-group">
					<input type="password" placeholder="Password" id="lgUpass" name="lgUpass" />
					<div class="error"></div>
				</div>
				<div class="remember-me">
					<input type="checkbox" id="remember-me" checked />
					<label for="remember-me">Remember me</label>
				</div>
				<button class="login-button" type="submit" id="login-btn">LOGIN</button>
			</form>
			<!-- Forgot Password Link -->
			<p class="forgot-password">Forgot Username / Password?</p>
        </div>
    </div>

</div>

<script src="./script.js"></script>
</body>
</html>
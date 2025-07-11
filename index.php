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

if(isset($_POST['login']) && $_SESSION['user'] = $user) {
    // Clear the form data
    setcookie('bg', $_SESSION['color'], time() + 64000, '/'); // Clear selected cities cookie
    echo "<style>body { background-color: " . htmlspecialchars($_SESSION['color']) . "; }</style>";
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
            // echo "<script>window.location.href = 'home.php';</script>";
            header("Location: home.php");
            exit;
        } else {
            $login_error = "Incorrect password.";
        }
    } else {
        $login_error = "User not found.";
    }

    $conn->close();
}

// Create connection
$conn = new mysqli("localhost", "root", "", "myDataBase");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch cities
$sql = "SELECT city_name, country_code, aqi FROM cities ORDER BY city_name ASC";
$result = $conn->query($sql);

$cities = [];
while ($row = $result->fetch_assoc()) {
    $cities[] = $row;
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
        <a href="#login-section" class="nav-link"><h1>Login</h1></a>
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

        <table  class="rankingTable">
          <thead>
            <tr>
              <th>#</th>
              <th>MAJOR CITY</th>
              <th>US AQI*</th>
            </tr>
          </thead>
          <tbody id="tableBody">
            <?php if (empty($cities)): ?>
              <tr><td colspan="3">No cities found.</td></tr>
            <?php else: ?>
              <?php foreach ($cities as $index => $city): ?>
                <?php $opa=1-$index/10; ?>
                <?php $flagUrl = "https://flagcdn.com/24x18/" . strtolower($city['country_code']) . ".png";?>
                <tr>
                  <td><?= $index + 1 ?></td>

                  <td style="opacity:<?=$opa?>;"><img src="<?php echo $flagUrl; ?>" alt="<?php echo $city['city_name']; ?> Flag" class="flag-icon"><?= htmlspecialchars($city['city_name']) ?></td>
                  <td style="opacity:<?=$opa?>; filter: blur(3px);"><?= htmlspecialchars($city['aqi']) ?></td>
                </tr>
                <?php if ($index==19): ?>
                    <?php break; ?>
                <?php endif; ?>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>  
            <!-- <tr><td colspan="3" class="loading">Loading live AQI data...</td></tr> -->
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
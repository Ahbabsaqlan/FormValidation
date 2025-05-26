<?php
session_start();
$selectedCities = isset($_COOKIE['selectedCities']) ? json_decode($_COOKIE['selectedCities'], true) : [];
// Handle logout
if (isset($_GET['logout']) && $_GET['logout'] === 'true') {
    // Unset all session variables
    $_SESSION = [];

    // Destroy the session
    session_destroy();

    // Redirect to login page
    header("Location: index.php");
    exit;
}
if (isset($_POST['select'])){
    if (empty($selectedCities)){
        echo "<script>alert('Please select at least one city.');</script>";
    } else {
      header("Location: result.php");
    }
    
}

// Check if user is logged in
$user = $_SESSION['user'] ?? null;

if (!$user) {
    header("Location: index.php");
    exit;
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
<html>
<head>
    <link rel="stylesheet" href="./style.css">
    <title>Welcome</title>
</head>
<body>
    <nav class="navbar">
        <div class="nav-logo">
            <h1>Lab Practise</h1>
        </div>
        
        <div class="nav-search">
            <a href="#login-section" class="nav-link"><h1>Welcome, <?= htmlspecialchars($user['userName']) ?>!</h1></a>
        </div>
    </nav>
    <div class="ranking-card">
      <div class="tabil">
        <header class="ranking-header">
          <h1>City ranking</h1>
          <p>Cities with high air pollution (AQI*)</p>
          <span class="info-icon">ℹ️</span>
        </header>
        <p id="limitMessage" class="selection-limit" style="display:none;">⚠️ You can only select up to 10 countries.</p>
        <table class="ranking-table" id="rankingTable">
          <thead>
            <tr>
              <th>#</th>
              <th>MAJOR CITY</th>
              <th>US AQI*</th>
            </tr>
          </thead>
          <tbody id="cityList" >
            <tr><td colspan="3" class="loading">Loading live AQI data...</td></tr>
          </tbody>
        </table>
      </div>
        <div class="homebuttons">
          <form method="POST" style="width: 48%;">
              <button class="cntry-slt-btn" type="submit" name="select">✅ Submit</button>
          </form>
        </div>
    </div>
    
    <!-- Logout link -->
     
    <p><a href="home.php?logout=true">Logout</a></p>
    <script>const cities=<?= json_encode($cities)?> </script>
    <script src="./script.js"></script>
</body>
</html>
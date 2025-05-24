<?php
session_start();

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

// Check if user is logged in
$user = $_SESSION['user'] ?? null;

if (!$user) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="./style.css">
    <title>Welcome</title>
</head>
<body>
    <h1>Welcome, <?= htmlspecialchars($user['userName']) ?>!</h1>
    <h1>Country: <?= htmlspecialchars($user['country']) ?></h1>
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
        <td><input type="checkbox" value="Dhaka, Bangladesh"></td>
        <td>
          <img src="https://flagcdn.com/24x18/bd.png " alt="Bangladesh Flag" class="flag-icon">
          Dhaka, Bangladesh
        </td>
        <td><span class="aqi-value red">160</span></td>
      </tr>
      <tr>
        <td><input type="checkbox" value="Santiago, Chile"></td>
        <td>
          <img src="https://flagcdn.com/24x18/cl.png " alt="Chile Flag" class="flag-icon">
          Santiago, Chile
        </td>
        <td><span class="aqi-value red">159</span></td>
      </tr>
      <tr>
        <td><input type="checkbox" value="Kampala, Uganda"></td>
        <td>
          <img src="https://flagcdn.com/24x18/ug.png " alt="Uganda Flag" class="flag-icon">
          Kampala, Uganda
        </td>
        <td><span class="aqi-value red">156</span></td>
      </tr>
      <tr>
        <td><input type="checkbox" value="Delhi, India"></td>
        <td>
          <img src="https://flagcdn.com/24x18/in.png " alt="India Flag" class="flag-icon">
          Delhi, India
        </td>
        <td><span class="aqi-value orange">131</span></td>
      </tr>
      <tr>
        <td><input type="checkbox" value="Dubai, United Arab Emirates"></td>
        <td>
          <img src="https://flagcdn.com/24x18/ae.png " alt="United Arab Emirates Flag" class="flag-icon">
          Dubai, United Arab Emirates
        </td>
        <td><span class="aqi-value orange">124</span></td>
      </tr>
      <tr>
        <td><input type="checkbox" value="Kinshasa, Democratic Republic of the Congo"></td>
        <td>
          <img src="https://flagcdn.com/24x18/cd.png " alt="Democratic Republic of the Congo Flag" class="flag-icon">
          Kinshasa, Democratic Republic of the Congo
        </td>
        <td><span class="aqi-value orange">122</span></td>
      </tr>
      <tr>
        <td><input type="checkbox" value="Beijing, China"></td>
        <td>
          <img src="https://flagcdn.com/24x18/cn.png " alt="China Flag" class="flag-icon">
          Beijing, China
        </td>
        <td><span class="aqi-value yellow">115</span></td>
      </tr>
      <tr>
        <td><input type="checkbox" value="Tashkent, Uzbekistan"></td>
        <td>
          <img src="https://flagcdn.com/24x18/uz.png " alt="Uzbekistan Flag" class="flag-icon">
          Tashkent, Uzbekistan
        </td>
        <td><span class="aqi-value yellow">105</span></td>
      </tr>
      <tr>
        <td><input type="checkbox" value="Batam, Indonesia"></td>
        <td>
          <img src="https://flagcdn.com/24x18/id.png " alt="Indonesia Flag" class="flag-icon">
          Batam, Indonesia
        </td>
        <td><span class="aqi-value yellow">103</span></td>
      </tr>
      <tr>
        <td><input type="checkbox" value="Jakarta, Indonesia"></td>
        <td>
          <img src="https://flagcdn.com/24x18/id.png " alt="Indonesia Flag" class="flag-icon">
          Jakarta, Indonesia
        </td>
        <td><span class="aqi-value yellow">103</span></td>
      </tr>
    </tbody>
  </table>
</div>
    
    <!-- Logout link -->
    <p><a href="home.php?logout=true">Logout</a></p>
</body>
</html>
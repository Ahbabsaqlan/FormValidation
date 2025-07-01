<?php
// Get selected cities from cookie
$selectedCities = isset($_COOKIE['selectedCities']) ? json_decode($_COOKIE['selectedCities'], true) : [];

if (empty($selectedCities)) {
    echo "<p>No cities selected. <a href='home.php'>Go back</a></p>";
    exit;
}
session_start();
// Check if user is logged in
$user = $_SESSION['user'] ?? null;

if (!$user) {
    header("Location: index.php");
    exit;
}

if (isset($_GET['logout']) && $_GET['logout'] === 'true') {
    // Unset all session variables
    $_SESSION = [];

    // Destroy the session
    session_destroy();

    // Redirect to login page
    header("Location: index.php");
    exit;
}
echo "<style>body { background-color: " . htmlspecialchars($_SESSION['color']) . "; }</style>";
if (isset($_GET['reset']) && $_GET['reset'] === 'true') {
    // Unset all session variables
    
    // Redirect to login page
    header("Location: home.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Selected Cities</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body id="color">

    <nav class="navbar">
        <div class="nav-logo">
            <h1>Lab Practise</h1>
        </div>
        
        <div class="nav-search">
            <a href="#login-section" class="nav-link profile-btn"><h1>Welcome, <?= htmlspecialchars($user['userName']) ?>!</h1></a>
            <a href="?logout=true" class="nav-link logout-btn"><h1>Logout</h1></a>
        </div>
    </nav>


<div class="ranking-card" style="margin-top: 2rem; margin-buttom: 2rem; max-height: 85vh;">
    <div class="tabil">
    <header class="ranking-header">
        <h1>Live AQI Data</h1>
        <p>Cities you selected (live air quality)</p>
    </header>
    <p class="selects">You selected <?= count($selectedCities) ?> cities.</p>
    <table class="ranking-table">
        <thead>
            <tr>
                <th>#</th>
                <th>MAJOR CITY</th>
                <th>US AQI*</th>
            </tr>
        </thead>
        <tbody id="resultList">
            <tr><td colspan="3">Loading AQI data...</td></tr>
        </tbody>
    </table>
    </div>
    <div class="homebuttons" style="text-align:center;margin-top:20px;">
        <a href="home.php?reset=true"><button onclick="deleteCookie('selectedCities')" style="padding:10px 20px; background:#e74c3c; color:white; border:none; border-radius:5px; cursor:pointer;">🔄 Reset Selection</button></a>
    </div>
    
</div>


<!-- Embed selected cities -->
<script>
const selectedCities = <?= json_encode($selectedCities) ?>;
const token = "74e3cbb45a9f7b41ce166f935a40e6532ff44213"; // Your AQICN Token

function getCityOnly(fullName) {
    const parts = fullName.split(",");
    return parts[0].trim(); // Returns only the city name
}

async function getCountryCode(countryName) {
  const response = await fetch(`https://restcountries.com/v3.1/name/${countryName}`);
  const data = await response.json();
  return data[0]?.cca2 || "Unknown";
}

function deleteCookie(name) {
  // Set cookie with empty value and past expiration
  document.cookie = name + '=; Max-Age=-99999; path=/';
}


async function fetchAQIData(cities) {
    const tbody = document.getElementById("resultList");
    tbody.innerHTML = `<tr><td colspan="3" class="loading">Fetching live AQI data...</td></tr>`;

    const rows = [];

    for (const city of cities) {
        // Try to guess slug (you can improve this mapping later)
        const slug = getCityOnly(city).toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '');
        
        try {
            const res = await fetch(`https://api.waqi.info/feed/${slug}/?token=${token}`);
            const data = await res.json();

            const aqi = data.status === "ok" ? data.data.aqi : "N/A";
            const category = getCategoryClass(aqi);

            const country_code = await getCountryCode(city.split(', ')[1]?.toLowerCase() || '');
            console.log("City:", city, "Slug:", slug, "Country Code:", country_code);
            const flagUrl = `https://flagcdn.com/24x18/${country_code.toLowerCase()}.png`;

            const row = document.createElement("tr");
            row.innerHTML = `
                <td>${rows.length + 1}</td>
                <td><img src="${flagUrl}" alt="Flag" class="flag-icon">${city}</td>
                <td><span class="aqi-value ${category}">${aqi}</span></td>
            `;
            rows.push(row);
        } catch (e) {
            console.error("Failed to fetch AQI for:", city);
        }
    }

    tbody.innerHTML = "";
    rows.forEach(r => tbody.appendChild(r));
}

const btns = document.getElementsByClassName('profile-btn');
const logout_btns = document.getElementsByClassName('logout-btn');

for (let i = 0; i < btns.length; i++) {
  btns[i].addEventListener('click', function () {
    if (logout_btns[i]) {
      logout_btns[i].style.display = 'block';
      btns[i].style.display = 'none';
    }
  });
}

function getCategoryClass(aqi) {
    if (!aqi) return "";
    if (aqi >= 151) return "red";
    else if (aqi >= 101) return "orange";
    else return "yellow";
}

// Run on page load
window.onload = () => fetchAQIData(selectedCities);
</script>
</body>
</html>
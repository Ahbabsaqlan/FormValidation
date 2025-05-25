// Utility Functions
function showError(input, message) {
  const formGroup = input.closest('.form-group') || input.closest('.lg-group');
  formGroup.className = formGroup.classList.contains('form-group') ? 'form-group error' : 'lg-group error';
  const errorDiv = formGroup.querySelector('.error');
  if (errorDiv) {
    errorDiv.textContent = message;
    formGroup.style.marginBottom = '0px';
  }
}

function showSuccess(input) {
  const formGroup = input.closest('.form-group') || input.closest('.lg-group');
  formGroup.className = formGroup.classList.contains('form-group') ? 'form-group success' : 'lg-group success';
  const errorDiv = formGroup.querySelector('.error');
  if (errorDiv) {
    errorDiv.textContent = '';
    formGroup.style.marginBottom = '';
  }
}

function getGender() {
  const genderRadios = document.querySelectorAll('input[name="gender"]:checked');
  return genderRadios.length > 0 ? genderRadios[0].value : '';
}

// Registration Form Validation
const form = document.getElementById('registrationForm');
if (form) {
  form.addEventListener('submit', function (e) {
    e.preventDefault();
    if (validateRegistrationForm()) {
      this.submit();
    }
  });
}

function validateRegistrationForm() {
  let isValid = true;

  // Username
  const username = document.getElementById('username');
  if (!username.value.trim()) {
    showError(username, 'Username is required');
    isValid = false;
  } else if (username.value.length < 3) {
    showError(username, 'Username must be at least 3 characters');
    isValid = false;
  } else if (!/^[a-zA-Z ]+$/.test(username.value.trim())) {
    showError(username, 'Username can only contain letters');
    isValid = false;
  } else {
    showSuccess(username);
  }

  // Email
  const email = document.getElementById('email');
  const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailPattern.test(email.value.trim())) {
    showError(email, 'Valid email is required');
    isValid = false;
  } else {
    showSuccess(email);
  }

  // DOB
  const dob = document.getElementById('dob');
  if (!dob.value) {
    showError(dob, 'Date of birth is required');
    isValid = false;
  } else {
    const dobDate = new Date(dob.value);
    const today = new Date();
    const minAgeDate = new Date(today.getFullYear() - 18, today.getMonth(), today.getDate());

    if (dobDate > today) {
      showError(dob, 'Date of birth cannot be in the future');
      isValid = false;
    } else if (dobDate < new Date('1900-01-01')) {
      showError(dob, 'Date of birth is not valid');
      isValid = false;
    } else if (dobDate > minAgeDate) {
      showError(dob, 'You must be at least 18 years old');
      isValid = false;
    } else {
      showSuccess(dob);
    }
  }

  // Gender
  const genderGroup = document.getElementById('genderGroup');
  if (!getGender()) {
    showError(genderGroup, 'Select your gender');
    isValid = false;
  } else {
    showSuccess(genderGroup);
  }

  // Country
  const country = document.getElementById('country');
  if (!country.value) {
    showError(country, 'Select a country');
    isValid = false;
  } else {
    showSuccess(country);
  }

  // Opinion
  const opinion = document.getElementById('opinion');
  if (opinion.value.trim().length < 10) {
    showError(opinion, 'Please share at least 10 characters');
    isValid = false;
  } else {
    showSuccess(opinion);
  }

  // Password
  const password = document.getElementById('password');
  if (password.value.length < 6) {
    showError(password, 'Password must be at least 6 characters');
    isValid = false;
  } else {
    showSuccess(password);
  }

  // Confirm Password
  const confirmPassword = document.getElementById('confirmPassword');
  if (password.value !== confirmPassword.value || !confirmPassword.value.trim()) {
    showError(confirmPassword, 'Passwords must match');
    isValid = false;
  } else {
    showSuccess(confirmPassword);
  }

  // Background Color
  const color = document.getElementById('color');
  const defaultColor = '#ffffff';
  if (color.value === defaultColor) {
    showError(color, 'Select a background color');
    isValid = false;
  } else {
    showSuccess(color);
  }

  // Terms and Conditions
  const termsCheckbox = document.getElementById('term');
  if (!termsCheckbox.checked) {
    showError(termsCheckbox, 'You must accept the terms and conditions');
    isValid = false;
  } else {
    showSuccess(termsCheckbox);
  }

  return isValid;
}

// Login Form Validation
document.getElementById('login-form')?.addEventListener('submit', function (e) {
  e.preventDefault(); // Prevent default submit
  if (validateLoginForm()) {
      e.target.submit(); // Submit form if valid
  }
});

function validateLoginForm() {
  let isValid = true;

  const uname = document.getElementById('lgUname');
  if (!uname.value.trim()) {
    showError(uname, 'User Email is required');
    isValid = false;
  }  else {
    showSuccess(uname);
  }

  const upassword = document.getElementById('lgUpass');
  if (upassword.value.length < 6) {
    showError(upassword, 'Password must be at least 6 characters');
    isValid = false;
  } else {
    showSuccess(upassword);
  }

  return isValid;
}

// Optional: Change background color based on user selection
// const colorInput = document.getElementById('color');
// if (colorInput) {
//   colorInput.addEventListener('input', function () {
//     document.body.style.backgroundColor = colorInput.value;
//   });
// }


// Aqi index featching

const token = '74e3cbb45a9f7b41ce166f935a40e6532ff44213'; // Your AQICN.org token

  // List of major cities with manual mapping to AQICN slugs and country codes
  const cities = [
    { name: "Delhi, India", slug: "delhi", countryCode: "in" },
    { name: "Beijing, China", slug: "beijing", countryCode: "cn" },
    { name: "Los Angeles, USA", slug: "los-angeles", countryCode: "us" },
    { name: "Dhaka, Bangladesh", slug: "dhaka", countryCode: "bd" },
    { name: "Santiago, Chile", slug: "santiago", countryCode: "cl" },
    { name: "Kampala, Uganda", slug: "kampala", countryCode: "ug" },
    { name: "Mexico City, Mexico", slug: "mexico-city", countryCode: "mx" },
    { name: "Jakarta, Indonesia", slug: "jakarta", countryCode: "id" },
    { name: "Dubai, UAE", slug: "dubai", countryCode: "ae" },
    { name: "Karachi, Pakistan", slug: "karachi", countryCode: "pk" },
    { name: "Hanoi, Vietnam", slug: "hanoi", countryCode: "vn" },
    { name: "Cairo, Egypt", slug: "cairo", countryCode: "eg" },
    { name: "Mumbai, India", slug: "mumbai", countryCode: "in" },
    { name: "Tehran, Iran", slug: "tehran", countryCode: "ir" },
    { name: "Manila, Philippines", slug: "manila", countryCode: "ph" },
    { name: "Lima, Peru", slug: "lima", countryCode: "pe" },
    { name: "Nairobi, Kenya", slug: "nairobi", countryCode: "ke" },
    { name: "Seoul, South Korea", slug: "seoul", countryCode: "kr" },
    { name: "Moscow, Russia", slug: "moscow", countryCode: "ru" },
    { name: "Bangkok, Thailand", slug: "bangkok", countryCode: "th" }
  ];

  const tableBody = document.getElementById("tableBody");

  function getCategoryClass(aqi) {
    if (aqi >= 151) return "red";
    else if (aqi >= 101) return "orange";
    else return "yellow";
  }

  async function fetchAQIData() {
    try {
      const results = [];

      for (const city of cities) {
        const response = await fetch(`https://api.waqi.info/feed/ ${city.slug}/?token=${token}`);
        const data = await response.json();

        if (data.status === "ok") {
          const aqi = data.data.aqi;
          results.push({ ...city, aqi });
        } else {
          results.push({ ...city, aqi: null });
        }
      }

      // Sort by AQI descending (null values go last)
      results.sort((a, b) => (b.aqi || 0) - (a.aqi || 0));

      tableBody.innerHTML = ""; // Clear loading message

      results.forEach((city, index) => {
        let aqiDisplay = "N/A";
        let categoryClass = "";

        if (city.aqi !== null) {
          categoryClass = getCategoryClass(city.aqi);
          aqiDisplay = `<span class="aqi-value ${categoryClass}">${city.aqi}</span>`;
        }

        const flagUrl = `https://flagcdn.com/24x18/${city.countryCode.toLowerCase()}.png`;

        const row = document.createElement("tr");
        row.innerHTML = `
          <td>${index + 1}</td>
          <td><img src="${flagUrl}" alt="Flag" class="flag-icon">${city.name}</td>
          <td>${aqiDisplay}</td>
        `;
        tableBody.appendChild(row);
      });

    } catch (error) {
      tableBody.innerHTML = "<tr><td colspan='3' class='loading'>Failed to load data.</td></tr>";
      console.error("Error fetching AQI data:", error);
    }
  }

  fetchAQIData();
  // Refresh data every 10 minutes
  setInterval(fetchAQIData, 0.5 * 60 * 1000);

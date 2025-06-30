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

// Change background color based on user selection
const colorInput = document.getElementById('color');
if (colorInput) {
  colorInput.addEventListener('input', function () {
    document.body.style.backgroundColor = colorInput.value;
  });
}


// Aqi index featching
// function fetchAQIData(cities){
//     const tbody = document.getElementById("cityList");
//       tbody.innerHTML = "";
//       cities.forEach((city) => {
//         let index = Array.from(tbody.children).length;
//         const row = document.createElement("tr");

//         const flagUrl = `https://flagcdn.com/24x18/${city.country_code.toLowerCase()}.png`;

//         row.innerHTML = `
//           <td>${index + 1}</td>
//           <td><input type="checkbox" value="${city.city_name}" class="country-checkbox"></td>
//           <td><img src="${flagUrl}" alt="${city.city_name} Flag" class="flag-icon">${city.city_name}</td>
//         `;
//         tbody.appendChild(row);
//       });

//       // Enforce checkbox limit
//       const checkboxes = document.querySelectorAll('.country-checkbox');
//       checkboxes.forEach(cb => {
//         cb.addEventListener('change', () => {
//           const checked = document.querySelectorAll('.country-checkbox:checked');
//           if (checked.length > 10) {
//             cb.checked = false;
//             document.getElementById("limitMessage").style.display = 'block';
//             setTimeout(() => {
//               document.getElementById("limitMessage").style.display = 'none';
//             }, 3000);
//           } else {
//             document.getElementById("limitMessage").style.display = 'none';
//           }
//         });
//       });
// }
// fetchAQIData(cities);

// document.getElementsByClassName("country-checkbox").addEventListener("change", function () {
//   location.reload(); // Reloads the page when the selection changes
// });
function showtable(){
  const tbody = document.getElementById("tableBody");

  
  tbody.innerHTML = ""; // Clear loading message

  cities.forEach((city, index) => {
      const flagUrl = `https://flagcdn.com/24x18/${city.country_code.toLowerCase()}.png`;
      const row = document.createElement("tr");

      row.innerHTML = `
          <td>${index + 1}</td>
          <!--<td><input type="checkbox" value="${city.city_name}" class="country-checkbox"></td>-->
          <td><img src="${flagUrl}" alt="${city.city_name} Flag" class="flag-icon">${city.city_name}</td>
          <!--<td>${city.aqi || "N/A"}</td>-->
      `;
      tbody.appendChild(row);
  })
}

function fetchAQIData(cities) {
  const tbody = document.getElementById("cityList");
  tbody.innerHTML = ""; // Clear loading message

  cities.forEach((city, index) => {
      const flagUrl = `https://flagcdn.com/24x18/${city.country_code.toLowerCase()}.png`;
      const row = document.createElement("tr");

      row.innerHTML = `
          <td>${index + 1}</td>
          <td><input type="checkbox" value="${city.city_name}" class="country-checkbox"></td>
          <td><img src="${flagUrl}" alt="${city.city_name} Flag" class="flag-icon">${city.city_name}</td>
          <!--<td>${city.aqi || "N/A"}</td>-->
      `;
      tbody.appendChild(row);

      // Make whole row clickable
      row.style.cursor = "pointer";
      row.addEventListener('click', function (e) {
          // Prevent double-check if user clicks on checkbox directly
          if (e.target.type !== 'checkbox') {
              const checkbox = row.querySelector('.country-checkbox');
              checkbox.checked = !checkbox.checked;

              // Trigger change manually (for JS logic)
              checkbox.dispatchEvent(new Event('change'));

              // Highlight/Unhighlight
              if (checkbox.checked) {
                  row.classList.add('selected');
              } else {
                  row.classList.remove('selected');
              }
          }
      });
  });

  // Enforce checkbox limit + interactivity
  const checkboxes = document.querySelectorAll('.country-checkbox');
  const limitMessage = document.getElementById("limitMessage");

  checkboxes.forEach((cb) => {
      cb.addEventListener('change', () => {
          const checked = document.querySelectorAll('.country-checkbox:checked');

          // Highlight row
          const row = cb.closest('tr');
          if (cb.checked) {
              row.classList.add('selected');
          } else {
              row.classList.remove('selected');
          }

          // Enforce limit
          if (checked.length > 10) {
              cb.checked = false;
              row.classList.remove('selected');
              limitMessage.style.display = 'block';
              document.body.classList.add('limit-exceeded');
              setTimeout(() => {
                  limitMessage.style.display = 'none';
                  document.body.classList.remove('limit-exceeded');
                }, 3000);
          } else {
              limitMessage.style.display = 'none';
              document.body.classList.remove('limit-exceeded');

              // Save selections
              const allSelected = Array.from(document.querySelectorAll('.country-checkbox:checked')).map(cb => cb.value);
              saveSelectionsToCookie(allSelected);
          }

          // Scroll to top to show warning
          if (checked.length > 10) {
              limitMessage.scrollIntoView({ behavior: 'smooth' });
          }
      });
  });
}
    


function saveSelectionsToCookie(selectedCities) {
  // Set cookie for 1 day (or longer if needed)
  const expiration = new Date();
  expiration.setDate(expiration.getDate() + 1);
  
  document.cookie = `selectedCities=${encodeURIComponent(JSON.stringify(selectedCities))}; path=/; expires=${expiration.toUTCString()}`;
}



function getSelectedFromCookie() {
  const match = document.cookie.match(new RegExp('(?:^|;)\\s*selectedCities=([^;]*)'));
  return match ? JSON.parse(decodeURIComponent(match[1])) : [];
}

document.addEventListener("DOMContentLoaded", function () {
  const saved = getSelectedFromCookie();

  saved.forEach(cityName => {
      const checkbox = [...document.querySelectorAll(".country-checkbox")].find(cb => cb.value === cityName);
      if (checkbox) {
          checkbox.checked = true;
          checkbox.closest('tr').classList.add('selected');
      }
  });

  //fetchAQIData(phpCities);
});
  // Refresh data every 10 minutes
  //setInterval(fetchAQIData, 0.5 * 60 * 1000);

const form = document.getElementById('registrationForm');
    
      const username = document.getElementById('username');
      const email = document.getElementById('email');
      const dob = document.getElementById('dob');
      const country = document.getElementById('country');
      const opinion = document.getElementById('opinion');
      const password = document.getElementById('password');
      const confirmPassword = document.getElementById('confirmPassword');
      const color = document.getElementById('color');

    
      function showError(input, message) {
        const formGroup = input.closest('.form-group');
        formGroup.className = 'form-group error';
        formGroup.querySelector('.error').textContent = message;
        formGroup.style.marginBottom = '0px';
      }
    
      function showSuccess(input) {
        const formGroup = input.closest('.form-group');
        formGroup.className = 'form-group success';
        formGroup.querySelector('.error').textContent = '';
        formGroup.style.marginBottom = '10px';
      }
    
      function getGender() {
        const genderRadios = document.querySelectorAll('input[name="gender"]');
        for (const radio of genderRadios) {
          if (radio.checked) return radio.value;
        }
        return '';
      }
    
      function validateForm() {
        let isValid = true;
    
        // Username
        if (username.value.trim() === '') {
          showError(username, 'Username is required');
          isValid = false;
        }
        else if (username.value.length < 3) {
          showError(username, 'Username must be at least 3 characters');
          isValid = false;
        }
        else if (!/^[a-zA-Z ]+$/.test(username.value.trim())) {
          showError(username, 'Username can only contain letters');
          isValid = false;
        }
         else {
          showSuccess(username);
        }
    
        // Email
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(email.value.trim())) {
          showError(email, 'Valid email is required');
          isValid = false;
        } else {
          showSuccess(email);
        }
    
        // DOB
        if (dob.value === '') {
          showError(dob, 'Date of birth is required');
          isValid = false;
        }
        else if (new Date(dob.value) > new Date()) {
          showError(dob, 'Date of birth cannot be in the future');
          isValid = false;
        }
        else if (new Date(dob.value) < new Date('1900-01-01')) {
          showError(dob, 'Date of birth is not valid');
          isValid = false;
        }
        else if (new Date(dob.value) > new Date(new Date().setFullYear(new Date().getFullYear() - 18))) {
          showError(dob, 'You must be at least 18 years old');
          isValid = false;
        }
         else {
          showSuccess(dob);
        }
    
        // Gender
        const genderGroup = document.getElementById('genderGroup');
        if (!getGender()) {
          genderGroup.className = 'form-group error';
          genderGroup.querySelector('.error').textContent = 'Select your gender';
          genderGroup.style.marginBottom = '0px';
          isValid = false;
        } else {
          genderGroup.className = 'form-group success';
          genderGroup.querySelector('.error').textContent = '';
          genderGroup.style.marginBottom = '10px';
        }
    
        // Country
        if (country.value === '') {
          showError(country, 'Select a country');
          isValid = false;
        } else {
          showSuccess(country);
        }
    
        // Opinion
        if (opinion.value.trim().length < 10) {
          showError(opinion, 'Please share at least 10 characters');
          isValid = false;
        } else {
          showSuccess(opinion);
        }
    
        // Password
        if (password.value.length < 6) {
          showError(password, 'Password must be at least 6 characters');
          isValid = false;
        } else {
          showSuccess(password);
        }
    
        // Confirm Password
        if (password.value !== confirmPassword.value || confirmPassword.value === '') {
          showError(confirmPassword, 'Passwords must match');
          isValid = false;
        } else {
          showSuccess(confirmPassword);
        }

        // Background Color
        const defaultColor = '#000000';
        if (color.value==defaultColor) {
            showError(color, 'Select A Background Color');
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
    
      form.addEventListener('submit', function (e) {
        e.preventDefault();
        if (validateForm()) {
          form.submit();
          document.querySelectorAll('.form-group').forEach(g => g.className = 'form-group');
        }
      });


      // Change background color
      // const colorInput = document.getElementById('color');
      // const body = document.body;
      // colorInput.addEventListener('input', function () {
      //   body.style.backgroundColor = colorInput.value;
      // });
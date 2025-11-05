
// age validation
document.getElementById('dob').addEventListener('change', function () {
    const dob = new Date(this.value);
    const today = new Date();
    const ageWarning = document.getElementById('ageWarning');
    let age = today.getFullYear() - dob.getFullYear();
    const monthDifference = today.getMonth() - dob.getMonth();


    // Adjust age based on month and day
    if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < dob.getDate())) {
        age--;
    }

    // Check if age is less than 18 or greater than 65
    if (age < 18) {
        ageWarning.textContent = 'You must be at least 18 years old to register.';
        this.setCustomValidity('You must be at least 18 years old.');
    } else if (age > 65) {
        ageWarning.textContent = 'You must be 65 years old or younger to register.';
        this.setCustomValidity('You must be 65 years old or younger.');
    } else {
        ageWarning.textContent = ''; // Clear any warning
        this.setCustomValidity(''); // Allow form submission
    }
});




// username avalibilty check
document.addEventListener("DOMContentLoaded", function () {
    var usernameInput = document.getElementById("username");
    var takenSpan = document.getElementById("taken");
    var lastCheckedUsername = ""; // Store last checked username

    usernameInput.addEventListener("input", function () {
        var username = usernameInput.value.trim();

        if (username.length < 3) {
            takenSpan.innerHTML = ""; // Clear the message if input is too short
            return;
        }

        if (username === lastCheckedUsername) {
            return; // Prevent unnecessary duplicate AJAX requests
        }

        lastCheckedUsername = username; // Update last checked username
        checkUsername(username);
    });

    function checkUsername(username) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "check_username.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                takenSpan.innerHTML = xhr.responseText;
            }
        };

        xhr.send("username=" + encodeURIComponent(username));
    }
});

// email avalibilty check
document.addEventListener("DOMContentLoaded", function () {
    var emailInput = document.getElementById("email");
    var emailWarning2 = document.getElementById("emailWarning2");
    var lastCheckedEmail = ""; // Store last checked email

    emailInput.addEventListener("input", function () {
        var email = emailInput.value.trim();

        if (email.length < 5 || !validateEmail(email)) {
            emailWarning2.innerHTML = ""; // Clear warning if email is invalid
            return;
        }

        if (email === lastCheckedEmail) {
            return; // Prevent unnecessary duplicate AJAX requests
        }

        lastCheckedEmail = email; // Update last checked email
        checkEmail(email);
    });

    function checkEmail(email) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "check_email.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                emailWarning2.innerHTML = xhr.responseText;
            }
        };

        xhr.send("email=" + encodeURIComponent(email));
    }

    function validateEmail(email) {
        var regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    }
});



// Username Validation
document.getElementById('username').addEventListener('input', function () {
    const usernameWarning = document.getElementById('usernameWarning');
    const regex =
        /^[a-zA-Z][a-zA-Z0-9_]{2,14}$/; // Must start with a letter, 3-15 characters, letters, numbers, underscores

    if (!regex.test(this.value)) {
        usernameWarning.textContent =
            'Username must start with a letter, be 3-15 characters, and contain only letters, numbers, or underscores.';
        this.setCustomValidity('Invalid username format.');
    } else {
        usernameWarning.textContent = '';
        this.setCustomValidity('');
    }
});



// Email Validation
document.getElementById('email').addEventListener('input', function () {
    const emailWarning = document.getElementById('emailWarning');
    const regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/; // Standard email regex pattern

    if (!regex.test(this.value)) {
        emailWarning.textContent = 'Please enter a valid email address.';
        this.setCustomValidity('Invalid email format.');
    } else {
        emailWarning.textContent = '';
        this.setCustomValidity('');
    }
});

// Password Match Validation
document.getElementById('confirmPassword').addEventListener('input', function () {
    const password = document.getElementById('password').value;
    const confirmPassword = this.value;
    const passwordWarning = document.getElementById('passwordWarning');

    if (password !== confirmPassword) {
        passwordWarning.textContent = 'Passwords do not match!';
        this.setCustomValidity('Passwords do not match.');
    } else {
        passwordWarning.textContent = '';
        this.setCustomValidity('');
    }
});

// Contact Number Validation
document.getElementById('contact').addEventListener('input', function () {
    const contactWarning = document.getElementById('contactWarning');
    const regex = /^03\d{9}$/; // Pattern for "03XXXXXXXXX" (11 digits)

    if (!regex.test(this.value)) {
        contactWarning.textContent = 'Contact number must be 11 digits and start with "03".';
        this.setCustomValidity('Invalid contact number format.');
    } else {
        contactWarning.textContent = '';
        this.setCustomValidity('');
    }
});




/// Sync WhatsApp Number with Contact Number
// document.getElementById('whatsapp').addEventListener('change', function () {
//     const whatsappInput = document.getElementById('whatsapp');
//     const contactInput = document.getElementById('contact');

//     if (this.checked) {
//         whatsappInput.value = contactInput.value;
//         whatsappInput.readOnly = true;
//     } else {
//         whatsappInput.value = '';
//         whatsappInput.readOnly = false;
//     }
// });

// same whatsapp number
document.getElementById('same-whatsapp').addEventListener('change', function () {
    const whatsappInput = document.getElementById('whatsapp');
    const hiddenWhatsappInput = document.getElementById('hidden-whatsapp');
    const contactInput = document.getElementById('contact');

    if (this.checked) {
        whatsappInput.value = contactInput.value;
        hiddenWhatsappInput.value = contactInput.value;
        whatsappInput.disabled = true;
    } else {
        whatsappInput.value = '';
        hiddenWhatsappInput.value = '';
        whatsappInput.disabled = false;
    }
});
// Contact Number Validation
document.getElementById('whatsapp').addEventListener('input', function () {
    const whatsappWarning = document.getElementById('whatsappWarning');
    const regex = /^03\d{9}$/; // Pattern for "03XXXXXXXXX" (11 digits)

    if (!regex.test(this.value)) {
        whatsappWarning.textContent = 'WhatsApp number must be 11 digits and start with "03".';
        this.setCustomValidity('Invalid contact number format.');
    } else {
        whatsappWarning.textContent = '';
        this.setCustomValidity('');
    }
});


// Ensure Terms are Accepted
document.querySelector('form').addEventListener('submit', function (e) {
    const termsCheckbox = document.getElementById('terms');
    if (!termsCheckbox.checked) {
        alert('You must accept the Terms and Services to register.');
        e.preventDefault();
    }
});



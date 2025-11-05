document.addEventListener("DOMContentLoaded", function () {
    // Get the password field
    let password = document.getElementById("password");

    // Optionally, get the confirmPassword field (only in signup.php)
    let confirmPassword = document.getElementById("confirmPassword");

    // Get the eye icon element
    let eyeicon = document.getElementById("eyeicon");

    // Toggle visibility function
    function togglePassword() {
        // Check if the password input is of type "password"
        if (password.type === "password") {
            password.type = "text"; // Change password to text (visible)
            if (confirmPassword) {
                confirmPassword.type = "text"; // If confirmPassword exists, make it visible too
            }
            eyeicon.classList.remove("fa-eye-slash"); // Change icon to eye
            eyeicon.classList.add("fa-eye"); // Add the eye icon
        } else {
            password.type = "password"; // Change password to password (hidden)
            if (confirmPassword) {
                confirmPassword.type = "password"; // If confirmPassword exists, hide it as well
            }
            eyeicon.classList.remove("fa-eye"); // Remove the eye icon
            eyeicon.classList.add("fa-eye-slash"); // Add the eye slash icon
        }
    }

    // Add click event listener to eye icon for toggling visibility
    if (eyeicon) {
        eyeicon.addEventListener("click", togglePassword);
    }
});

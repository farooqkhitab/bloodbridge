// Logout Button
document.addEventListener("DOMContentLoaded", function () {
    let saveLifeBtn = document.getElementById("logout");
    let savedLifeModal = document.getElementById("logout-container");
    let cancelBtn = document.getElementById("cancel-btn3");

    // Open Modal with transition
    saveLifeBtn.addEventListener("click", function () {
        savedLifeModal.style.display = "block";
        // Use setTimeout to ensure the transition happens after display change
        setTimeout(() => {
            savedLifeModal.style.opacity = "1";
            savedLifeModal.style.transform = "translate(-50%, -50%) scale(1)";
        }, 10);
    });

    // Close Modal with transition
    cancelBtn.addEventListener("click", function () {
        savedLifeModal.style.opacity = "0";
        savedLifeModal.style.transform = "translate(-50%, -50%) scale(0.95)";
        // Wait for transition to complete before hiding
        setTimeout(() => {
            savedLifeModal.style.display = "none";
        }, 300); // Match this time with your transition duration
    });

    // Close Modal when clicking outside
    window.addEventListener("click", function (event) {
        if (event.target === savedLifeModal) {
            savedLifeModal.style.opacity = "0";
            savedLifeModal.style.transform = "translate(-50%, -50%) scale(0.95)";
            setTimeout(() => {
                savedLifeModal.style.display = "none";
            }, 300);
        }
    });
});
// ------

// Save life button functionality

document.addEventListener("DOMContentLoaded", function () {
    let saveLifeBtn = document.getElementById("save-life");
    let savedLifeModal = document.getElementById("savedLifeModal");
    let cancelBtn = document.getElementById("cancel-btn");

    // Open Modal with transition
    saveLifeBtn.addEventListener("click", function () {
        savedLifeModal.style.display = "block";
        // Use setTimeout to ensure the transition happens after display change
        setTimeout(() => {
            savedLifeModal.style.opacity = "1";
            savedLifeModal.style.transform = "translate(-50%, -50%) scale(1)";
        }, 10);
    });

    // Close Modal with transition
    cancelBtn.addEventListener("click", function () {
        savedLifeModal.style.opacity = "0";
        savedLifeModal.style.transform = "translate(-50%, -50%) scale(0.95)";
        // Wait for transition to complete before hiding
        setTimeout(() => {
            savedLifeModal.style.display = "none";
        }, 300); // Match this time with your transition duration
    });

    // Close Modal when clicking outside
    window.addEventListener("click", function (event) {
        if (event.target === savedLifeModal) {
            savedLifeModal.style.opacity = "0";
            savedLifeModal.style.transform = "translate(-50%, -50%) scale(0.95)";
            setTimeout(() => {
                savedLifeModal.style.display = "none";
            }, 300);
        }
    });
});
// -----------------------------------------------------------------

// Functionality for Ineligible button

document.addEventListener("DOMContentLoaded", function () {
    let saveLifeBtn = document.getElementById("ineligible");
    let savedLifeModal = document.getElementById("ineligible-container");
    let cancelBtn = document.getElementById("cancel-btn2");

    // Open Modal with transition
    saveLifeBtn.addEventListener("click", function () {
        savedLifeModal.style.display = "block";
        // Use setTimeout to ensure the transition happens after display change
        setTimeout(() => {
            savedLifeModal.style.opacity = "1";
            savedLifeModal.style.transform = "translate(-50%, -50%) scale(1)";
        }, 10);
    });

    // Close Modal with transition
    cancelBtn.addEventListener("click", function () {
        savedLifeModal.style.opacity = "0";
        savedLifeModal.style.transform = "translate(-50%, -50%) scale(0.95)";
        // Wait for transition to complete before hiding
        setTimeout(() => {
            savedLifeModal.style.display = "none";
        }, 300); // Match this time with your transition duration
    });

    // Close Modal when clicking outside
    window.addEventListener("click", function (event) {
        if (event.target === savedLifeModal) {
            savedLifeModal.style.opacity = "0";
            savedLifeModal.style.transform = "translate(-50%, -50%) scale(0.95)";
            setTimeout(() => {
                savedLifeModal.style.display = "none";
            }, 300);
        }
    });
});


// Functionality for Deactivate btn

document.addEventListener("DOMContentLoaded", function () {
    const deactivateBtn = document.getElementById("confirm-deactivate");
    const cancelBtn = document.getElementById("cancel-btn4");
    const modal = document.getElementById("deactivate-container");
    const passwordInput = document.getElementById("password");
    const errorText = document.getElementById("deactivate-error");

    // Open modal
    document.getElementById("deactivate").addEventListener("click", () => {
        modal.style.display = "block";
        setTimeout(() => {
            modal.style.opacity = "1";
            modal.style.transform = "translate(-50%, -50%) scale(1)";
        }, 10);
    });

    // Cancel modal
    cancelBtn.addEventListener("click", () => {
        modal.style.opacity = "0";
        modal.style.transform = "translate(-50%, -50%) scale(0.95)";
        setTimeout(() => {
            modal.style.display = "none";
        }, 300);
    });

    // Handle full AJAX deactivate
    deactivateBtn.addEventListener("click", () => {
        const password = passwordInput.value.trim();
        if (!password) {
            errorText.textContent = "Please enter your password.";
            return;
        }

        deactivateBtn.disabled = true;
        deactivateBtn.style.opacity = 0.5;

        fetch("deactivate_account.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: "password=" + encodeURIComponent(password),
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    window.location.href = data.redirect;
                } else {
                    errorText.textContent = data.error || "Something went wrong";
                    deactivateBtn.disabled = false;
                    deactivateBtn.style.opacity = 1;
                }
            })
            .catch(() => {
                errorText.textContent = "Server error. Try again.";
                deactivateBtn.disabled = false;
                deactivateBtn.style.opacity = 1;
            });
    });

    passwordInput.addEventListener("input", () => {
        errorText.textContent = "";
        deactivateBtn.disabled = false;
        deactivateBtn.style.opacity = 1;
    });

    // Close modal on background click
    window.addEventListener("click", function (event) {
        if (event.target === modal) {
            modal.style.opacity = "0";
            modal.style.transform = "translate(-50%, -50%) scale(0.95)";
            setTimeout(() => {
                modal.style.display = "none";
            }, 300);
        }
    });
});



// Auto select days base on the select option
document.getElementById("reason").addEventListener("change", function () {
    let daysInput = document.getElementById("days");
    let selectedOption = this.options[this.selectedIndex].text; // Get text of selected option

    if (this.value === "other" || selectedOption.includes("Dental Work")) {
        daysInput.value = "";
        daysInput.removeAttribute("readonly");
        daysInput.removeAttribute("disabled");
        daysInput.setAttribute("type", "number"); // Ensure only numbers
    } else if (this.value === "permanent") {
        daysInput.value = "Permanent";
        daysInput.setAttribute("readonly", true);
        daysInput.setAttribute("disabled", true);
    } else {
        daysInput.value = this.value;
        daysInput.setAttribute("readonly", true);
        daysInput.setAttribute("disabled", true);
    }
});

// Restrict input to numbers only
document.getElementById("days").addEventListener("input", function () {
    this.value = this.value.replace(/[^0-9]/g, ''); // Allow only numbers
});


// Profile handaling

document.addEventListener("DOMContentLoaded", function () {
    const imgIcon = document.getElementById("profile_img_change");
    const profileImgModal = document.getElementById("profile_img_modal");
    const cancelImgBtn = document.getElementById("cancel-profile-img");
    const imgInput = document.getElementById("profile_img_file");
    const preview = document.getElementById("profile_img_preview");

    // Open modal
    imgIcon.addEventListener("click", () => {
        profileImgModal.style.display = "block";
        setTimeout(() => {
            profileImgModal.style.opacity = "1";
            profileImgModal.style.transform = "translate(-50%, -50%) scale(1)";
        }, 10);
    });

    // Cancel
    cancelImgBtn.addEventListener("click", () => {
        profileImgModal.style.opacity = "0";
        profileImgModal.style.transform = "translate(-50%, -50%) scale(0.95)";
        setTimeout(() => {
            profileImgModal.style.display = "none";
            imgInput.value = "";
        }, 300);
    });

    // Preview selected image
    imgInput.addEventListener("change", function () {
        const file = this.files[0];
        const errorText = document.getElementById("fileSizeError");

        if (file) {
            if (file.size > 2 * 1024 * 1024) {
                errorText.style.display = "block";
                this.value = "";
                preview.src = "img.png"; // fallback preview
                return;
            } else {
                errorText.style.display = "none";
            }

            const reader = new FileReader();
            reader.onload = e => {
                preview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });


    // Close when clicking outside
    window.addEventListener("click", function (e) {
        if (e.target === profileImgModal) {
            profileImgModal.style.opacity = "0";
            profileImgModal.style.transform = "translate(-50%, -50%) scale(0.95)";
            setTimeout(() => {
                profileImgModal.style.display = "none";
            }, 300);
        }
    });
});

// Change full name of user
document.addEventListener("DOMContentLoaded", function () {
    let nameIcon = document.getElementById("full_name");
    let nameModal = document.getElementById("changeNameModal");
    let cancelNameBtn = document.getElementById("cancel-name-btn");

    nameIcon.addEventListener("click", function () {
        nameModal.style.display = "block";
        setTimeout(() => {
            nameModal.style.opacity = "1";
            nameModal.style.transform = "translate(-50%, -50%) scale(1)";
        }, 10);
    });

    cancelNameBtn.addEventListener("click", function () {
        nameModal.style.opacity = "0";
        nameModal.style.transform = "translate(-50%, -50%) scale(0.95)";
        setTimeout(() => {
            nameModal.style.display = "none";
        }, 300);
    });

    // Optional: close when clicking outside the modal
    window.addEventListener("click", function (event) {
        if (event.target === nameModal) {
            nameModal.style.opacity = "0";
            nameModal.style.transform = "translate(-50%, -50%) scale(0.95)";
            setTimeout(() => {
                nameModal.style.display = "none";
            }, 300);
        }
    });
});

// Change username 
document.addEventListener("DOMContentLoaded", function () {
    const usernameBtn = document.getElementById("username_change");
    const usernameModal = document.getElementById("changeUsernameModal");
    const cancelUsernameBtn = document.getElementById("cancel-username-btn");

    usernameBtn.addEventListener("click", function () {
        usernameModal.style.display = "block";
        setTimeout(() => {
            usernameModal.style.opacity = "1";
            usernameModal.style.transform = "translate(-50%, -50%) scale(1)";
        }, 10);
    });

    cancelUsernameBtn.addEventListener("click", function () {
        usernameModal.style.opacity = "0";
        usernameModal.style.transform = "translate(-50%, -50%) scale(0.95)";
        setTimeout(() => {
            usernameModal.style.display = "none";
        }, 300);
    });

    window.addEventListener("click", function (event) {
        if (event.target === usernameModal) {
            usernameModal.style.opacity = "0";
            usernameModal.style.transform = "translate(-50%, -50%) scale(0.95)";
            setTimeout(() => {
                usernameModal.style.display = "none";
            }, 300);
        }
    });
});

// username validation and avalibilty checks
document.addEventListener("DOMContentLoaded", function () {
    const usernameInput = document.getElementById("new_username");
    const usernameWarning = document.getElementById("usernameWarning");
    const takenSpan = document.getElementById("taken");
    let lastChecked = "";

    usernameInput.addEventListener("input", function () {
        const username = this.value.trim();
        const regex = /^[a-zA-Z][a-zA-Z0-9_]{2,14}$/;

        // Validate format
        if (!regex.test(username)) {
            usernameWarning.textContent =
                'Username must start with a letter, be 3–15 characters, and use letters, numbers, or underscores.';
            this.setCustomValidity('Invalid username format');
        } else {
            usernameWarning.textContent = '';
            this.setCustomValidity('');

            // Avoid duplicate checks
            if (username !== lastChecked && username.length >= 3) {
                lastChecked = username;
                checkUsernameAvailability(username);
            }
        }
    });

    function checkUsernameAvailability(username) {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "check_username.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                takenSpan.innerHTML = xhr.responseText;
            }
        };

        xhr.send("username=" + encodeURIComponent(username));
    }
});

// Email change
document.addEventListener("DOMContentLoaded", function () {
    const emailBtn = document.getElementById("email_change");
    const emailModal = document.getElementById("changeEmailModal");
    const cancelEmailBtn = document.getElementById("cancel-email-btn");

    emailBtn.addEventListener("click", function () {
        emailModal.style.display = "block";
        setTimeout(() => {
            emailModal.style.opacity = "1";
            emailModal.style.transform = "translate(-50%, -50%) scale(1)";
        }, 10);
    });

    cancelEmailBtn.addEventListener("click", function () {
        emailModal.style.opacity = "0";
        emailModal.style.transform = "translate(-50%, -50%) scale(0.95)";
        setTimeout(() => {
            emailModal.style.display = "none";
        }, 300);
    });

    window.addEventListener("click", function (event) {
        if (event.target === emailModal) {
            emailModal.style.opacity = "0";
            emailModal.style.transform = "translate(-50%, -50%) scale(0.95)";
            setTimeout(() => {
                emailModal.style.display = "none";
            }, 300);
        }
    });
});

// Email Ajax
document.addEventListener("DOMContentLoaded", function () {
    const emailInput = document.getElementById("new_email");
    const emailWarning = document.getElementById("emailWarning");
    const emailWarning2 = document.getElementById("emailWarning2");
    let lastCheckedEmail = "";

    emailInput.addEventListener("input", function () {
        const email = this.value.trim();
        const regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

        // Validation
        if (!regex.test(email)) {
            emailWarning.textContent = "Please enter a valid email address.";
            this.setCustomValidity("Invalid email format.");
        } else {
            emailWarning.textContent = "";
            this.setCustomValidity("");

            // Availability check
            if (email.length >= 5 && email !== lastCheckedEmail) {
                lastCheckedEmail = email;
                checkEmail(email);
            }
        }
    });

    function checkEmail(email) {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "check_email.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                emailWarning2.innerHTML = xhr.responseText;
            }
        };

        xhr.send("email=" + encodeURIComponent(email));
    }
});

// Address change
document.addEventListener("DOMContentLoaded", function () {
    const openBtn = document.getElementById("address_change");
    const modal = document.getElementById("changeAddressModal");
    const cancelBtn = document.getElementById("cancel-address-btn");

    openBtn.addEventListener("click", function () {
        modal.style.display = "block";
        setTimeout(() => {
            modal.style.opacity = "1";
            modal.style.transform = "translate(-50%, -50%) scale(1)";
        }, 10);
    });

    cancelBtn.addEventListener("click", function () {
        modal.style.opacity = "0";
        modal.style.transform = "translate(-50%, -50%) scale(0.95)";
        setTimeout(() => {
            modal.style.display = "none";
        }, 300);
    });

    window.addEventListener("click", function (event) {
        if (event.target === modal) {
            modal.style.opacity = "0";
            modal.style.transform = "translate(-50%, -50%) scale(0.95)";
            setTimeout(() => {
                modal.style.display = "none";
            }, 300);
        }
    });
});
// address change ajax
const districtChange = document.getElementById("district_change");
const tehsilChange = document.getElementById("tehsil_change");
const vcChange = document.getElementById("vc_change");

// Fetch districts
fetch("fetch_districts.php")
    .then((res) => res.json())
    .then((data) => {
        data.forEach((district) => {
            const opt = document.createElement("option");
            opt.value = district.id;
            opt.textContent = district.name;
            districtChange.appendChild(opt);
        });
    });

// Load Tehsils when District changes
districtChange.addEventListener("change", () => {
    const districtId = districtChange.value;
    tehsilChange.innerHTML = '<option value="">--Select Tehsil--</option>';
    vcChange.innerHTML = '<option value="">--Select VC--</option>';

    if (districtId) {
        fetch(`fetch_tehsils.php?district_id=${districtId}`)
            .then((res) => res.json())
            .then((data) => {
                data.forEach((tehsil) => {
                    const opt = document.createElement("option");
                    opt.value = tehsil.id;
                    opt.textContent = tehsil.name;
                    tehsilChange.appendChild(opt);
                });
            });
    }
});

// Load VCs when Tehsil changes
tehsilChange.addEventListener("change", () => {
    const tehsilId = tehsilChange.value;
    vcChange.innerHTML = '<option value="">--Select VC--</option>';

    if (tehsilId) {
        fetch(`fetch_vcs_ncs.php?tehsil_id=${tehsilId}`)
            .then((res) => res.json())
            .then((data) => {
                data.forEach((vc) => {
                    const opt = document.createElement("option");
                    opt.value = vc.id;
                    opt.textContent = vc.name;
                    vcChange.appendChild(opt);
                });
            });
    }
});


// change phone number
document.addEventListener("DOMContentLoaded", function () {
    const openBtn = document.getElementById("change_phone");
    const modal = document.getElementById("changeContactModal");
    const cancelBtn = document.getElementById("cancel-contact-btn");

    openBtn.addEventListener("click", function () {
        modal.style.display = "block";
        setTimeout(() => {
            modal.style.opacity = "1";
            modal.style.transform = "translate(-50%, -50%) scale(1)";
        }, 10);
    });

    cancelBtn.addEventListener("click", function () {
        modal.style.opacity = "0";
        modal.style.transform = "translate(-50%, -50%) scale(0.95)";
        setTimeout(() => {
            modal.style.display = "none";
        }, 300);
    });

    window.addEventListener("click", function (event) {
        if (event.target === modal) {
            modal.style.opacity = "0";
            modal.style.transform = "translate(-50%, -50%) scale(0.95)";
            setTimeout(() => {
                modal.style.display = "none";
            }, 300);
        }
    });
});

//   phone validation
document.getElementById('new_contact').addEventListener('input', function () {
    const warning = document.getElementById('contactWarningModal');
    const regex = /^03\d{9}$/; // Must start with 03 and be 11 digits

    if (!regex.test(this.value)) {
        warning.textContent = 'Contact number must be 11 digits and start with "03".';
        this.setCustomValidity('Invalid contact number format.');
    } else {
        warning.textContent = '';
        this.setCustomValidity('');
    }
});

// change whatsapp
document.addEventListener("DOMContentLoaded", function () {
    const openBtn = document.getElementById("change_wa");
    const modal = document.getElementById("changeWhatsappModal");
    const cancelBtn = document.getElementById("cancel-wa-btn");

    openBtn.addEventListener("click", function () {
        modal.style.display = "block";
        setTimeout(() => {
            modal.style.opacity = "1";
            modal.style.transform = "translate(-50%, -50%) scale(1)";
        }, 10);
    });

    cancelBtn.addEventListener("click", function () {
        modal.style.opacity = "0";
        modal.style.transform = "translate(-50%, -50%) scale(0.95)";
        setTimeout(() => {
            modal.style.display = "none";
        }, 300);
    });

    window.addEventListener("click", function (event) {
        if (event.target === modal) {
            modal.style.opacity = "0";
            modal.style.transform = "translate(-50%, -50%) scale(0.95)";
            setTimeout(() => {
                modal.style.display = "none";
            }, 300);
        }
    });
});

// Whatsapp validation
document.getElementById('new_whatsapp').addEventListener('input', function () {
    const warning = document.getElementById('whatsappWarningModal');
    const regex = /^03\d{9}$/;

    if (!regex.test(this.value)) {
        warning.textContent = 'WhatsApp number must be 11 digits and start with "03".';
        this.setCustomValidity('Invalid WhatsApp number format.');
    } else {
        warning.textContent = '';
        this.setCustomValidity('');
    }
});


//   change blood group
document.addEventListener("DOMContentLoaded", function () {
    const openBtn = document.getElementById("blood_group_change");
    const modal = document.getElementById("changeBloodModal");
    const cancelBtn = document.getElementById("cancel-blood-btn");

    openBtn.addEventListener("click", function () {
        modal.style.display = "block";
        setTimeout(() => {
            modal.style.opacity = "1";
            modal.style.transform = "translate(-50%, -50%) scale(1)";
        }, 10);
    });

    cancelBtn.addEventListener("click", function () {
        modal.style.opacity = "0";
        modal.style.transform = "translate(-50%, -50%) scale(0.95)";
        setTimeout(() => {
            modal.style.display = "none";
        }, 300);
    });

    window.addEventListener("click", function (event) {
        if (event.target === modal) {
            modal.style.opacity = "0";
            modal.style.transform = "translate(-50%, -50%) scale(0.95)";
            setTimeout(() => {
                modal.style.display = "none";
            }, 300);
        }
    });
});

//   edit mood enable
// document.addEventListener("DOMContentLoaded", function () {
//     const editBtn = document.getElementById("edit");
//     const modal = document.getElementById("editModeModal");
//     const cancelBtn = document.getElementById("cancel-edit-btn");

//     editBtn.addEventListener("click", function () {
//         modal.style.display = "block";
//         setTimeout(() => {
//             modal.style.opacity = "1";
//             modal.style.transform = "translate(-50%, -50%) scale(1)";
//         }, 10);
//     });

//     cancelBtn.addEventListener("click", function () {
//         modal.style.opacity = "0";
//         modal.style.transform = "translate(-50%, -50%) scale(0.95)";
//         setTimeout(() => {
//             modal.style.display = "none";
//         }, 300);
//     });

//     window.addEventListener("click", function (event) {
//         if (event.target === modal) {
//             modal.style.opacity = "0";
//             modal.style.transform = "translate(-50%, -50%) scale(0.95)";
//             setTimeout(() => {
//                 modal.style.display = "none";
//             }, 300);
//         }
//     });
// });
// ajax for edit button
document.addEventListener("DOMContentLoaded", function () {
    const editBtn = document.getElementById("edit");
    const modal = document.getElementById("editModeModal");
    const cancelBtn = document.getElementById("cancel-edit-btn");
    const form = document.getElementById("edit-mode-form");
    const passwordInput = document.getElementById("edit_password");
    const errorMsg = document.getElementById("edit-error-msg");
    const enableBtn = document.getElementById("enable-edit-btn");

    editBtn.addEventListener("click", function () {
        modal.style.display = "block";
        setTimeout(() => {
            modal.style.opacity = "1";
            modal.style.transform = "translate(-50%, -50%) scale(1)";
        }, 10);
    });

    cancelBtn.addEventListener("click", function () {
        closeModal();
    });

    window.addEventListener("click", function (event) {
        if (event.target === modal) {
            closeModal();
        }
    });

    function closeModal() {
        modal.style.opacity = "0";
        modal.style.transform = "translate(-50%, -50%) scale(0.95)";
        setTimeout(() => {
            modal.style.display = "none";
            errorMsg.textContent = '';
            enableBtn.disabled = false;
            passwordInput.value = '';
        }, 300);
    }

    form.addEventListener("submit", function (e) {
        e.preventDefault();
        const password = passwordInput.value.trim();
        errorMsg.textContent = '';

        fetch("ajax/verify_edit_password.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: "password=" + encodeURIComponent(password),
        })
            .then(res => res.text())
            .then(response => {
                if (response === "success") {
                    window.location.reload();
                } else {
                    errorMsg.textContent = "Incorrect password.";
                    enableBtn.disabled = true;
                }
            });
    });
});


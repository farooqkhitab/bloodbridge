<?php
require_once 'auth_check.php';
// manage-donors.php
require 'db_config.php';
// require 'auth_check.php'; // ✅ Check admin login session if needed

// Fetch all donors (for now without filters)
$donors = $pdo->query("SELECT d.*, s.total_donations FROM donors d
                      LEFT JOIN donation_summary s ON d.id = s.donor_id
                      ORDER BY d.created_at DESC")
               ->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'header.php'; // contains <head> and includes ?>

<body>
    <div class="container">
        <!-- Include Sidebar -->
        <?php include 'sidebar.php'; ?>

        <div class="main-content">
            <div class="header">

                <div class="user-info">
                                   
                    <div class="profile">
                        <img src="https://randomuser.me/api/portraits/men/1.jpg" alt="Admin Profile">
                        <span>Admin User</span>
                    </div>
                </div>
            </div>

            <div class="donor-management">

                <h2>Manage Donors</h2>

                <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?= htmlspecialchars($_GET['message'] ?? 'Operation completed successfully') ?>
                </div>
                <?php endif; ?>

                <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-triangle"></i>
                    <?= htmlspecialchars($_GET['message'] ?? 'An error occurred') ?>
                </div>
                <?php endif; ?>

                <?php if (isset($_GET['info'])): ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <?= htmlspecialchars($_GET['message'] ?? 'Information') ?>
                </div>
                <?php endif; ?>


                <div class="filter-section">
                    <h3>Filter Donors</h3>
                    <form action="manage-donors.php" method="POST">


                        <div class="filter-row">
                            <div class="input_group">
                                <label for="district">District</label>
                                <select id="district" name="district" required>
                                    <option value="">--Select District--</option>
                                </select>
                            </div>

                            <div class="input_group">
                                <label for="tehsil">Tehsil</label>
                                <select id="tehsil" name="tehsil" required>
                                    <option value="">--Select Tehsil--</option>
                                </select>
                            </div>

                            <div class="input_group">
                                <label for="vc">VC | NC</label>
                                <select id="vc" name="vc" required>
                                    <option value="">--Select VC--</option>
                                </select>
                            </div>
                        </div>

                        <div class="filter-row">
                            <div class="input_group">
                                <label for="blood_group">Blood Group</label>
                                <select name="blood_group" id="blood_group" required>
                                    <option value="">--Select Blood Group--</option>
                                    <option value="A+">A+</option>
                                    <option value="A-">A-</option>
                                    <option value="B+">B+</option>
                                    <option value="B-">B-</option>
                                    <option value="AB+">AB+</option>
                                    <option value="AB-">AB-</option>
                                    <option value="O+">O+</option>
                                    <option value="O-">O-</option>
                                </select>
                            </div>
                            <div class="filter-group">
                                <label for="name-filter">Full Name</label>
                                <input type="text" id="name-filter" placeholder="Filter by name">
                            </div>
                            <div class="filter-group">
                                <label for="username-filter">Username</label>
                                <input type="text" id="username-filter" placeholder="Filter by username">
                            </div>
                        </div>
                    </form>
                </div>
            </div>



            <div class="donors-table-section">
                <div class="table-header">
                    <h3>Donors List</h3>

                </div>

                <div class="table-container">
                    <table class="donors-table">
                        <thead>
                            <tr>
                                <!-- <th>ID</th> -->
                                <th>Profile</th>
                                <th>Full Name</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Blood Group</th>
                                <th>Contact | WA</th>
                           
                                <th>Location</th>
                                <th>Status</th>
                                <th>Donations</th>
                                <th>DOB</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>

                    </table>
                </div>

                <div class="pagination">
                    <button class="pagination-btn prev-page" disabled><i class="fas fa-chevron-left"></i></button>
                    <div class="page-numbers">
                    </div>
                    <button class="pagination-btn next-page"><i class="fas fa-chevron-right"></i></button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal for Edit Donor -->
    <div class="modal" id="editDonorModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Edit Donor</h3>
                <button class="close-modal"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">
                <form id="editDonorForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="edit-full-name">Full Name</label>
                            <input type="text" id="edit-full-name" name="full_name">
                        </div>
                        <div class="form-group">

                            <label for="edit-username">Username</label>
                            <input type="text" id="edit-username" name="username">
                            <span id="usernameWarning" class="text-danger small"></span>
                            <span id="taken" class="text-danger small"></span>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="edit-email">Email</label>
                            <input type="email" id="edit-email" name="email">
                            <span id="emailWarning" class="text-danger small"></span>
                            <span id="emailWarning2" class="text-danger small"></span>
                        </div>
                        <div class="form-group">
                            <label for="edit-dob">Date of Birth</label>
                            <input type="date" id="edit-dob" name="dob">
                            <span id="ageWarning" class="text-danger small"></span>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="edit-contact">Contact</label>
                            <input type="text" id="edit-contact" name="contact">
                            <span id="contactWarning"></span>
                        </div>
                        <div class="form-group">
                            <label for="edit-whatsapp">WhatsApp</label>
                            <input type="text" id="edit-whatsapp" name="whatsapp">
                            <span id="whatsappWarning"></span>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="edit-blood-group">Blood Group</label>
                            <select id="edit-blood-group" name="blood_group">
                                <option value="A+">A+</option>
                                <option value="A-">A-</option>
                                <option value="B+">B+</option>
                                <option value="B-">B-</option>
                                <option value="AB+">AB+</option>
                                <option value="AB-">AB-</option>
                                <option value="O+">O+</option>
                                <option value="O-">O-</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="edit-district">District</label>
                            <select id="edit-district" name="district">
                                <option value="">--Select District--</option>

                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="edit-tehsil">Tehsil</label>
                            <select id="edit-tehsil" name="tehsil">
                                <option value="">--Select Tehsil--</option>

                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit-vc">VC</label>
                            <select id="edit-vc" name="vc">
                                <option value="">--Select VC--</option>

                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Inactive Status for  (n days):</label>
                            <input type="number" name="days_active" id="edit-days" min="0" />
                        </div>
                    </div>

                    <div class="form-row">


                        <div class="form-group full-width">
                            <label for="edit-profile-picture">Profile Picture</label>
                            <div class="file-upload">
                                <input type="file" id="edit-profile-picture" name="profile_picture">
                                <label for="edit-profile-picture" class="file-upload-label">Choose file</label>
                                <span class="file-name">No file chosen</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="cancel-btn">Cancel</button>
                        <button class="save-btn">Save Changes</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
    <div class="modal-d" id="deleteConfirmModal" style="display: none;">
        <div class="modal-content-d">
            <h3>Confirm Deletion</h3>
            <p>Are you sure you want to delete this donor?</p>
            <div class="modal-footer-d">
                <button class="cancel-btn"
                    onclick="document.getElementById('deleteConfirmModal').style.display='none'">Cancel</button>
                <button id="confirmDeleteBtn" class="confirm-delete-btn">Yes, Delete</button>

            </div>
        </div>
    </div>





    <script src="dropdown.js"></script>
    <script src="script.js"></script>
    <script src="donor-script.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('editDonorModal');
        const closeModalBtn = modal.querySelector('.close-modal');
        const cancelBtn = modal.querySelector('.cancel-btn');
        const saveBtn = modal.querySelector('.save-btn');

        // Open modal and populate data
        document.addEventListener('click', function(e) {
            if (e.target.closest('.edit-btn')) {
                const btn = e.target.closest('.edit-btn');
                modal.querySelector('#edit-full-name').value = btn.dataset.full_name;
                modal.querySelector('#edit-username').value = btn.dataset.username;
                modal.querySelector('#edit-email').value = btn.dataset.email;
                modal.querySelector('#edit-dob').value = btn.dataset.dob;
                modal.querySelector('#edit-contact').value = btn.dataset.contact;
                modal.querySelector('#edit-whatsapp').value = btn.dataset.whatsapp;
                modal.querySelector('#edit-blood-group').value = btn.dataset.blood_group;
                modal.querySelector('#edit-district').value = btn.dataset.district;
                modal.querySelector('#edit-tehsil').value = btn.dataset.tehsil;
                modal.querySelector('#edit-vc').value = btn.dataset.vc;
                modal.querySelector('#edit-days').value = 0; // Reset field when modal opens



                modal.dataset.id = btn.dataset.id;

                modal.style.display = 'block';
            }
        });

        // Close modal
        closeModalBtn.addEventListener('click', () => modal.style.display = 'none');
        cancelBtn.addEventListener('click', (e) => {
            e.preventDefault();
            modal.style.display = 'none';
        });

        // Save changes
        saveBtn.addEventListener('click', function(e) {
            e.preventDefault();

            const formData = new FormData(document.getElementById('editDonorForm'));
            formData.append('id', modal.dataset.id);

            fetch('update_donor.php', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(res => {
                    if (res.success) {
                        alert('Donor updated successfully!');
                        modal.style.display = 'none';
                        // Optionally refresh donor list
                        loadDonors(); // Call your AJAX reload method
                    } else {
                        alert('Update failed: ' + res.message);
                    }
                });
        });
    });
    </script>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        // DOB Validation
        const dob = document.getElementById('edit-dob');
        const ageWarning = document.getElementById('ageWarning');

        if (dob) {
            dob.addEventListener('change', function() {
                const dobValue = new Date(this.value);
                const today = new Date();
                let age = today.getFullYear() - dobValue.getFullYear();
                const m = today.getMonth() - dobValue.getMonth();
                if (m < 0 || (m === 0 && today.getDate() < dobValue.getDate())) {
                    age--;
                }

                if (age < 18) {
                    ageWarning.textContent = "Too young (under 18)";
                    this.setCustomValidity("Too young");
                } else if (age > 65) {
                    ageWarning.textContent = "Too old (above 65)";
                    this.setCustomValidity("Too old");
                } else {
                    ageWarning.textContent = "";
                    this.setCustomValidity("");
                }
            });
        }

        // Username Validation
        const username = document.getElementById('edit-username');
        const usernameWarning = document.getElementById('usernameWarning');
        const taken = document.getElementById('taken');
        let lastUsername = '';

        if (username) {
            username.addEventListener('input', function() {
                const val = this.value.trim();
                const regex = /^[a-zA-Z][a-zA-Z0-9_]{2,14}$/;

                if (!regex.test(val)) {
                    usernameWarning.textContent = "Invalid username format";
                    this.setCustomValidity("Invalid username");
                } else {
                    usernameWarning.textContent = "";
                    this.setCustomValidity("");
                }

                if (val.length >= 3 && val !== lastUsername) {
                    lastUsername = val;
                    checkUsername(val);
                }
            });
        }

        function checkUsername(uname) {
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "check_username.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onload = function() {
                if (xhr.status === 200) {
                    taken.innerHTML = xhr.responseText;
                }
            };
            xhr.send("username=" + encodeURIComponent(uname));
        }

        // Email Validation
        const email = document.getElementById('edit-email');
        const emailWarning = document.getElementById('emailWarning');
        const emailWarning2 = document.getElementById('emailWarning2');
        let lastEmail = '';

        if (email) {
            email.addEventListener('input', function() {
                const val = this.value.trim();
                const regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

                if (!regex.test(val)) {
                    emailWarning.textContent = "Invalid email format";
                    this.setCustomValidity("Invalid email");
                } else {
                    emailWarning.textContent = "";
                    this.setCustomValidity("");
                }

                if (val.length >= 5 && val !== lastEmail) {
                    lastEmail = val;
                    checkEmail(val);
                }
            });
        }

        function checkEmail(email) {
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "check_email.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onload = function() {
                if (xhr.status === 200) {
                    emailWarning2.innerHTML = xhr.responseText;
                }
            };
            xhr.send("email=" + encodeURIComponent(email));
        }
        // Contact Number Validation
        document.getElementById('edit-whatsapp').addEventListener('input', function() {
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

        // Contact Number Validation
        document.getElementById('edit-contact').addEventListener('input', function() {
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
    });
    </script>
   <script>
let selectedDonorId = null;

// ✅ Event delegation: listen for any clicks on delete buttons
document.addEventListener("click", function (e) {
    const donorButton = e.target.closest(".delete-btn");

    if (donorButton) {
        e.preventDefault();
        selectedDonorId = donorButton.getAttribute("data-donor-id");
        console.log("Selected Donor ID:", selectedDonorId);
        document.getElementById("deleteConfirmModal").style.display = "block";
    }
});

// Confirm deletion
document.getElementById("confirmDeleteBtn").addEventListener("click", () => {
    console.log("selectedDonorId at deletion time:", selectedDonorId);
    if (!selectedDonorId) {
        alert("No donor selected.");
        return;
    }

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "delete_donor.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onload = function () {
        console.log("Server response:", xhr.responseText);
        if (xhr.status === 200 && xhr.responseText.trim() === "success") {
            alert("Donor deleted successfully.");
            location.reload();
        } else {
            alert("Failed to delete donor: " + xhr.responseText);
        }
    };

    xhr.onerror = function () {
        alert("AJAX error occurred.");
    };

    xhr.send("donor_id=" + encodeURIComponent(selectedDonorId));
});
</script>

<script>
   /*document.addEventListener("DOMContentLoaded", () => {
    const fields = ["blood_group", "name-filter", "username-filter"];

    // Restore static fields
    fields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field && localStorage.getItem(fieldId)) {
            field.value = localStorage.getItem(fieldId);
        }
    });

    // Save changes on static fields
    fields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field) {
            field.addEventListener("input", () => localStorage.setItem(fieldId, field.value));
            field.addEventListener("change", () => localStorage.setItem(fieldId, field.value));
        }
    });

    // Wait for AJAX-loaded dropdowns: district, tehsil, vc
 const waitForOptions = (id, callback) => {
        const el = document.getElementById(id);
        const interval = setInterval(() => {
            if (el && el.options.length > 1) { // Ensure options loaded
                clearInterval(interval);
                callback(el);
            }
        }, 100);
    };

    // Restore and save each select field
    ["district", "tehsil", "vc"].forEach(id => {
        waitForOptions(id, select => {
            const savedValue = localStorage.getItem(id);
            if (savedValue) select.value = savedValue;

            select.addEventListener("change", () => {
                localStorage.setItem(id, select.value);
            });
        });
    });

    // Optional: Clear filters button
    const form = document.querySelector(".filter-section form");
    if (form) {
        form.addEventListener("reset", () => {
            [...fields, "district", "tehsil", "vc"].forEach(id => {
                localStorage.removeItem(id);
            });
        });
    }
});*/
</script>






</body>

</html>
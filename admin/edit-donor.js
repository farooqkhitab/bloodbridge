// JavaScript for handling donor edit functionality
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('editDonorModal');
    const editButtons = document.querySelectorAll('.edit-btn');
    const closeModal = document.querySelector('.close-modal');
    const cancelBtn = document.querySelector('.cancel-btn');
    const saveBtn = document.querySelector('.save-btn');
    const editForm = document.getElementById('editDonorForm');
    
    let currentDonorId = null;

    // Open modal when edit button is clicked
    editButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Get the donor row
            const row = this.closest('tr');
            currentDonorId = row.getAttribute('data-id');
            
            // Extract donor data from the table row
            const cells = row.querySelectorAll('td');
            const donorData = {
                full_name: cells[1].textContent.trim(),
                username: cells[2].textContent.trim(),
                email: cells[3].textContent.trim(),
                blood_group: cells[4].textContent.trim(),
                contact: cells[5].textContent.split('\n')[0].trim(),
                whatsapp: cells[5].textContent.split('WhatsApp: ')[1]?.trim() || '',
                district: cells[6].textContent.split('\n')[0].trim(),
                tehsil: cells[6].textContent.split('\n')[1]?.split(',')[0]?.trim() || '',
                vc: cells[6].textContent.split(', ')[1]?.trim() || ''
            };
            
            // Populate the form with donor data
            populateForm(donorData);
            
            // Show the modal
            modal.style.display = 'block';
        });
    });

    // Close modal functions
    closeModal.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    cancelBtn.addEventListener('click', (e) => {
        e.preventDefault();
        modal.style.display = 'none';
    });

    // Close modal when clicking outside
    window.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });

    // Handle form submission
    saveBtn.addEventListener('click', function(e) {
        e.preventDefault();
        
        if (!currentDonorId) {
            alert('Error: No donor selected');
            return;
        }

        // Create FormData object
        const formData = new FormData(editForm);
        formData.append('donor_id', currentDonorId);
        formData.append('action', 'update_donor');

        // Send AJAX request
        fetch('update_donor.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Donor updated successfully!');
                // Reload the page to show updated data
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating the donor');
        });
    });

    // Function to populate form with donor data
    function populateForm(donorData) {
        document.getElementById('edit-full-name').value = donorData.full_name;
        document.getElementById('edit-username').value = donorData.username;
        document.getElementById('edit-email').value = donorData.email;
        document.getElementById('edit-contact').value = donorData.contact;
        document.getElementById('edit-whatsapp').value = donorData.whatsapp;
        document.getElementById('edit-blood-group').value = donorData.blood_group;
        
        // For location fields, you might need to trigger change events
        // to populate dependent dropdowns
        populateLocationFields(donorData.district, donorData.tehsil, donorData.vc);
    }

    // Function to populate location fields (assuming you have location data)
    function populateLocationFields(district, tehsil, vc) {
        // You'll need to implement this based on your location data structure
        // This is a placeholder - adjust according to your location loading logic
        
        const districtSelect = document.getElementById('edit-district');
        const tehsilSelect = document.getElementById('edit-tehsil');
        const vcSelect = document.getElementById('edit-vc');
        
        // Set district
        if (district) {
            districtSelect.value = district;
            // Trigger change event to load tehsils
            districtSelect.dispatchEvent(new Event('change'));
            
            // Use setTimeout to allow tehsils to load before setting value
            setTimeout(() => {
                if (tehsil) {
                    tehsilSelect.value = tehsil;
                    tehsilSelect.dispatchEvent(new Event('change'));
                    
                    setTimeout(() => {
                        if (vc) {
                            vcSelect.value = vc;
                        }
                    }, 100);
                }
            }, 100);
        }
    }

    // File upload handling
    const fileInput = document.getElementById('edit-profile-picture');
    const fileName = document.querySelector('.file-name');
    
    fileInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            fileName.textContent = this.files[0].name;
        } else {
            fileName.textContent = 'No file chosen';
        }
    });
});
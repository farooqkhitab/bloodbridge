<!-- Popup Form -->
 <!DOCTYPE html>
 <html lang="en">
 <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification</title>
    <style>
        /* Popup Styling */
.popup {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 1000;
    justify-content: center;
    align-items: center;
}
.popup-content {
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    width: 90%;
    max-width: 400px;
    position: relative;
    border: 1px solid #F8E1E1;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}
.popup-content h3 {
    margin-top: 0;
    color: #333;
    font-weight: 600;
    font-size: 20px;
    text-align: center;
}
.close-btn {
    position: absolute;
    top: 10px;
    right: 10px;
    font-size: 24px;
    cursor: pointer;
    color: #666;
}
.close-btn:hover {
    color: #333;
}
.form-group {
    margin-bottom: 15px;
    text-align: left;
}
.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 500;
    color: #333;
}
.form-group input {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-sizing: border-box;
    font-size: 14px;
}
.form-group .date-group {
    display: flex;
    align-items: center;
    gap: 10px;
}
.form-group .today-btn {
    padding: 8px 15px;
    background-color: #C12733;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    transition: background-color 0.3s;
}
.form-group .today-btn:hover {
    background-color: #a05252;
}
.submit-btn {
    background-color: #C12733;
    color: white;
    padding: 10px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    width: 100%;
    font-size: 16px;
    transition: background-color 0.3s;
}
.submit-btn:hover {
    background-color: #a05252;
}
    </style>
 </head>
 <body>
    
<div class="popup" id="saveLifePopup">
    <div class="popup-content">
        <span class="close-btn" id="closePopup">×</span>
        <h3>Record a Donation</h3>
        <form method="POST" action="#" enctype="multipart/form-data">
            <div class="form-group">
                <label for="recipient_username">Recipient Username:</label>
                <input type="text" id="recipient_username" name="recipient_username"
                    placeholder="Enter recipient's username" required>
            </div>
            <div class="form-group">
                <label for="donation_date">Donation Date (dd-mm-yyyy):</label>
                <div class="date-group">
                    <input type="text" id="donation_date" name="donation_date" placeholder="dd-mm-yyyy" required
                        pattern="\d{2}-\d{2}-\d{4}">
                    <button type="button" class="today-btn" id="todayBtn">Today</button>
                </div>
            </div>
            <div class="form-group">
                <label for="evidence">Upload Proof (Optional):</label>
                <input type="file" id="evidence" name="evidence" accept="image/*,application/pdf">
            </div>
            <button type="submit" class="submit-btn">Submit</button>
        </form>
    </div>
</div>
 </body>
 </html>
<script >
    document.addEventListener('DOMContentLoaded', () => {
    // Popup functionality
    const saveLifeBtn = document.querySelector('.save-btn');
    const popup = document.getElementById('saveLifePopup');
    const closePopup = document.getElementById('closePopup');

    if (saveLifeBtn && popup && closePopup) {
        saveLifeBtn.addEventListener('click', () => {
            popup.style.display = 'flex';
        });

        closePopup.addEventListener('click', () => {
            popup.style.display = 'none';
        });

        popup.addEventListener('click', (e) => {
            if (e.target === popup) {
                popup.style.display = 'none';
            }
        });

        // Today button functionality
        const todayBtn = document.getElementById('todayBtn');
        const donationDateInput = document.getElementById('donation_date');

        if (todayBtn && donationDateInput) {
            todayBtn.addEventListener('click', () => {
                const today = new Date();
                const day = String(today.getDate()).padStart(2, '0');
                const month = String(today.getMonth() + 1).padStart(2, '0');
                const year = today.getFullYear();
                donationDateInput.value = `${day}-${month}-${year}`;
            });
        }
    }
});
</script>
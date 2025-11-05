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
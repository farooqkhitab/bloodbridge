document.addEventListener('DOMContentLoaded', () => {
    let currentIndex = 0;
    let verificationRecords = [];
    let totalRecords = 0;

    // Fetch verification records
    async function fetchVerificationRecords() {
        try {
            const response = await fetch('api/get_verification_records.php');
            const data = await response.json();
            if (data.success) {
                verificationRecords = data.records;
                totalRecords = verificationRecords.length;
                console.log('Fetched records:', verificationRecords); // Debug log
                updateNavigation();
                if (totalRecords > 0) {
                    displayRecord(currentIndex);
                } else {
                    displayEmptyState();
                }
            } else {
                console.error('Failed to fetch records:', data.error);
                displayEmptyState();
            }
        } catch (error) {
            console.error('Error fetching verification records:', error);
            displayEmptyState();
        }
    }

    // Display a single record using index
    function displayRecord(index) {
        // Use a for loop to find the record at the current index
        for (let i = 0; i < verificationRecords.length; i++) {
            if (i === index) {
                const record = verificationRecords[i];
                console.log('Displaying record:', record); // Debug log
                document.getElementById('donorProfilePic').src = record.profile_picture || 'https://via.placeholder.com/120x80';
                document.getElementById('submissionTimestamp').textContent = `Submitted on: ${new Date(record.created_at).toLocaleString()}`;
                document.getElementById('verificationId').textContent = `#VER-${record.id}`;
                document.getElementById('donorFullName').textContent = record.donor_full_name;
                document.getElementById('donorUsername').textContent = `@${record.donor_username}`;
                document.getElementById('recipientFullName').textContent = record.recipient_full_name;
                document.getElementById('recipientUsername').textContent = `@${record.recipient_username}`;
                document.getElementById('donationDate').textContent = new Date(record.donation_date).toLocaleDateString();
                document.getElementById('recipientContact').textContent = record.recipient_contact || 'N/A';
                document.getElementById('recipientWhatsApp').textContent = record.recipient_whatsapp || 'N/A';

                // Display evidence images
                const evidenceImages = document.getElementById('evidenceImages');
                evidenceImages.innerHTML = '';
                if (record.file) {
                    const evidenceItem = document.createElement('div');
                    evidenceItem.className = 'evidence-item';
                    evidenceItem.innerHTML = `
                        <img src="${record.file}" alt="Evidence Image">
                        <span class="evidence-label">Hospital Receipt</span>
                        <button class="evidence-view-btn">
                            <i class="fas fa-search-plus"></i> View Full Size
                        </button>
                    `;
                    evidenceImages.appendChild(evidenceItem);
                }
                break; // Exit loop after finding and displaying the record
            }
        }
        updateNavigation();
    }

    // Update navigation buttons and counter
    function updateNavigation() {
        document.getElementById('prevBtn').disabled = currentIndex === 0;
        document.getElementById('nextBtn').disabled = currentIndex === totalRecords - 1;
        document.getElementById('verificationCount').textContent = totalRecords > 0 
            ? `Processing request ${currentIndex + 1} out of ${totalRecords}`
            : 'No requests to process';
        console.log('Navigation updated:', { currentIndex, totalRecords }); // Debug log
    }

    // Display empty state
    function displayEmptyState() {
        document.getElementById('verificationCard').innerHTML = '<p>No verification requests available.</p>';
        document.getElementById('prevBtn').disabled = true;
        document.getElementById('nextBtn').disabled = true;
        document.getElementById('verificationCount').textContent = 'No requests to process';
        console.log('Displayed empty state'); // Debug log
    }

    // Handle verification action
    async function handleVerification(recordId, action) {
        try {
            const response = await fetch('api/process_verification.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: recordId, action: action })
            });
            const data = await response.json();
            if (data.success) {
                // Remove the record from the array
                for (let i = 0; i < verificationRecords.length; i++) {
                    if (verificationRecords[i].id === recordId) {
                        verificationRecords.splice(i, 1);
                        break;
                    }
                }
                totalRecords--;
                console.log('Record processed:', { action, recordId, totalRecords }); // Debug log
                if (totalRecords === 0) {
                    displayEmptyState();
                } else {
                    if (currentIndex >= totalRecords) {
                        currentIndex = totalRecords - 1;
                    }
                    displayRecord(currentIndex);
                }
            } else {
                console.error('Verification failed:', data.error);
            }
        } catch (error) {
            console.error('Error processing verification:', error);
        }
    }

    // Event listeners for navigation
    document.getElementById('prevBtn').addEventListener('click', () => {
        if (currentIndex > 0) {
            currentIndex--;
            console.log('Navigating to previous record:', currentIndex); // Debug log
            displayRecord(currentIndex);
        }
    });

    document.getElementById('nextBtn').addEventListener('click', () => {
        if (currentIndex < totalRecords - 1) {
            currentIndex++;
            console.log('Navigating to next record:', currentIndex); // Debug log
            displayRecord(currentIndex);
        }
    });

    // Event listeners for verification actions
    document.getElementById('verifyBtn').addEventListener('click', () => {
        if (verificationRecords[currentIndex]) {
            handleVerification(verificationRecords[currentIndex].id, 'verify');
        }
    });

    document.getElementById('rejectBtn').addEventListener('click', () => {
        if (verificationRecords[currentIndex]) {
            handleVerification(verificationRecords[currentIndex].id, 'reject');
        }
    });

    // Event listener for evidence view button
    document.addEventListener('click', (e) => {
        if (e.target.classList.contains('evidence-view-btn')) {
            const imgSrc = e.target.parentElement.querySelector('img').src;
            alert('Full-size image view: ' + imgSrc); // Placeholder for modal
            console.log('Evidence view clicked:', imgSrc); // Debug log
        }
    });

    // Initial fetch
    fetchVerificationRecords();
});
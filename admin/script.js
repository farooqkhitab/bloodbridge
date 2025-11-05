// JavaScript for Dashboard

document.addEventListener('DOMContentLoaded', function() {
    // Blood Group Distribution Chart
    const bloodGroupCtx = document.getElementById('bloodGroupChart').getContext('2d');
    const bloodGroupChart = new Chart(bloodGroupCtx, {
        type: 'doughnut',
        data: {
            labels: ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'],
            datasets: [{
                label: 'Blood Group Distribution',
                data: [234, 87, 195, 53, 122, 45, 312, 97],
                backgroundColor: [
                    '#e74c3c', '#c0392b', '#e67e22', '#d35400', 
                    '#3498db', '#2980b9', '#2ecc71', '#27ae60'
                ],
                borderColor: [
                    '#fff', '#fff', '#fff', '#fff',
                    '#fff', '#fff', '#fff', '#fff'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'right'
                }
            },
            cutout: '60%'
        }
    });

    // Donation Trends Chart
    const donationTrendCtx = document.getElementById('donationTrendChart').getContext('2d');
    const donationTrendChart = new Chart(donationTrendCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Donations',
                data: [65, 78, 90, 85, 95, 110, 125, 130, 142, 135, 148, 142],
                backgroundColor: 'rgba(231, 76, 60, 0.2)',
                borderColor: '#e74c3c',
                borderWidth: 2,
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // Navigation active state
    const menuItems = document.querySelectorAll('.menu li');
    menuItems.forEach(item => {
        item.addEventListener('click', function() {
            menuItems.forEach(i => i.classList.remove('active'));
            this.classList.add('active');
        });
    });
    
    // Download Source Code Button
    const downloadBtn = document.getElementById('downloadSourceCode');
    if(downloadBtn) {
        downloadBtn.addEventListener('click', function() {
            // This function would create and download a zip file with all source code
            createAndDownloadZip();
        });
    }
    

});
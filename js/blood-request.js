document.addEventListener("DOMContentLoaded", function () {
    const filters = ["district", "tehsil", "vc", "blood_group"];
    const donorResults = document.getElementById("donor_results");
    const maxSelection = 20;
    let selectedDonors = new Set();
    let currentPage = 1;

    function updateSelectedCount() {
        const countDisplay = document.getElementById("selected-count");
        if (countDisplay) {
            countDisplay.innerText = `Selected: ${selectedDonors.size} / ${maxSelection}`;
        }
    }

    function getFilters() {
        return {
            district: document.getElementById("district").value,
            tehsil: document.getElementById("tehsil").value,
            vc: document.getElementById("vc").value,
            blood_group: document.getElementById("blood_group").value,
        };
    }

    function loadDonors(page = 1) {
        const data = new FormData();
        const filters = getFilters();
        for (const key in filters) data.append(key, filters[key]);
        data.append("page", page);

        fetch("fetch_donors.php", { method: "POST", body: data })
            .then(res => res.text())
            .then(html => {
                donorResults.innerHTML = html;
                currentPage = page;

                // Recheck already selected donors
                document.querySelectorAll(".donor-checkbox").forEach(cb => {
                    if (selectedDonors.has(cb.value)) {
                        cb.checked = true;
                    }

                    cb.addEventListener("change", () => {
                        const id = cb.value;
                        if (cb.checked) {
                            if (selectedDonors.size >= maxSelection) {
                                cb.checked = false;
                                alert(`⚠️ You can only select up to ${maxSelection} donors.`);
                                return;
                            }
                            selectedDonors.add(id);
                        } else {
                            selectedDonors.delete(id);
                        }

                        updateSelectedCount();
                    });
                });

                // Update pagination buttons
                document.querySelectorAll(".page-btn").forEach(btn => {
                    btn.addEventListener("click", () => loadDonors(btn.dataset.page));
                });

                updateSelectedCount();
            });
    }

    // Load donors when any filter changes
    filters.forEach(id => {
        document.getElementById(id).addEventListener("change", () => loadDonors(1));
    });

    // Select All (but obey max 20)
    document.getElementById("select-all").addEventListener("click", () => {
        const checkboxes = document.querySelectorAll(".donor-checkbox");
        let added = 0;
        checkboxes.forEach(cb => {
            if (!cb.checked && selectedDonors.size < maxSelection) {
                cb.checked = true;
                selectedDonors.add(cb.value);
                added++;
            }
        });
        updateSelectedCount();
    });

    // Unselect All (only for visible page)
    document.getElementById("unselect-all").addEventListener("click", () => {
        document.querySelectorAll(".donor-checkbox").forEach(cb => {
            cb.checked = false;
            selectedDonors.delete(cb.value); // remove from memory
        });
        updateSelectedCount();
    });

    // Send Alerts
    document.getElementById("send-alerts").addEventListener("click", () => {
        const message = prompt("Enter your custom message:");
        if (!message || selectedDonors.size === 0) {
            alert("⚠️ Please select donors and enter a message.");
            return;
        }

        fetch("send_sms.php", {
            method: "POST",
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ ids: Array.from(selectedDonors), message: message })
        })
            .then(res => {
                const resultBox = document.getElementById("sms-response");
                if (resultBox) {
                    resultBox.innerText = res;
                    resultBox.style.color = 'green';
                } else {
                    alert(res); // fallback
                }
            })

    });

    // Initial message
    donorResults.innerHTML = "<p>Please select filters above to load donors.</p>";
});

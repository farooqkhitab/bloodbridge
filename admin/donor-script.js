document.addEventListener('DOMContentLoaded', function () {
    // ========== FILTER BUTTONS ==========
    const applyFiltersBtn = document.querySelector('.apply-filters-btn');
    const resetFiltersBtn = document.querySelector('.reset-filters-btn');

    if (applyFiltersBtn) {
        applyFiltersBtn.addEventListener('click', function () {
            console.log('Applying filters');
            fetchDonors(1);
        });
    }

    if (resetFiltersBtn) {
        resetFiltersBtn.addEventListener('click', function () {
            document.getElementById('district').value = '';
            document.getElementById('tehsil').value = '';
            document.getElementById('vc').value = '';
            document.getElementById('blood_group').value = '';
            document.getElementById('name-filter').value = '';
            document.getElementById('username-filter').value = '';
            fetchDonors(1);
        });
    }

    // ========== MODALS ==========
    function bindModalButtons() {
        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const modal = document.getElementById('editDonorModal');
                if (modal) modal.style.display = 'flex';
            });
        });

        document.querySelectorAll('.deactivate-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const modal = document.getElementById('deactivateConfirmModal');
                if (modal) modal.style.display = 'flex';
            });
        });

        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const modal = document.getElementById('deleteConfirmModal');
                if (modal) modal.style.display = 'flex';
            });
        });

        document.querySelectorAll('.close-modal, .cancel-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                document.querySelectorAll('.modal').forEach(modal => {
                    modal.style.display = 'none';
                });
            });
        });
    }

    // Deactivation reason dropdown logic
    const deactivateReason = document.getElementById('deactivate-reason');
    const otherReasonGroup = document.getElementById('other-reason-group');
    if (deactivateReason) {
        deactivateReason.addEventListener('change', function () {
            otherReasonGroup.style.display = (this.value === 'other') ? 'block' : 'none';
        });
    }

    // File upload preview
    const fileInput = document.getElementById('edit-profile-picture');
    const fileLabel = document.querySelector('.file-name');
    if (fileInput && fileLabel) {
        fileInput.addEventListener('change', function () {
            fileLabel.textContent = this.files.length > 0 ? this.files[0].name : 'No file chosen';
        });
    }

    // Save Changes
    document.querySelector('.save-btn')?.addEventListener('click', function () {
        console.log('Saving changes');
        document.getElementById('editDonorModal').style.display = 'none';
    });

    // Deactivate Confirm
    document.querySelector('.deactivate-confirm-btn')?.addEventListener('click', function () {
        console.log('Deactivating donor');
        document.getElementById('deactivateConfirmModal').style.display = 'none';
    });

    // Delete Confirm
    document.querySelector('.delete-confirm-btn')?.addEventListener('click', function () {
        console.log('Deleting donor');
        document.getElementById('deleteConfirmModal').style.display = 'none';
    });

    // ========== AJAX FETCH DONORS ==========
    function fetchDonors(page = 1) {
        const formData = new FormData();
        formData.append("page", page);
        formData.append("district", document.getElementById("district").value);
        formData.append("tehsil", document.getElementById("tehsil").value);
        formData.append("vc", document.getElementById("vc").value);
        formData.append("blood_group", document.getElementById("blood_group").value);
        formData.append("name", document.getElementById("name-filter").value);
        formData.append("username", document.getElementById("username-filter").value);

        fetch("filter_donors.php", {
            method: "POST",
            body: formData
        })
            .then(res => res.json())
            .then(data => {
                const tbody = document.querySelector(".donors-table tbody");
                const paginationWrapper = document.querySelector(".pagination");

                tbody.innerHTML = data.tableRows;
                paginationWrapper.innerHTML = data.pagination;

                bindPagination(); // re-bind page buttons
                bindModalButtons(); // re-bind modals
            });
    }

    function bindPagination() {
        document.querySelectorAll(".page-number").forEach(btn => {
            btn.addEventListener("click", () => fetchDonors(btn.dataset.page));
        });

        document.querySelector(".prev-page")?.addEventListener("click", () => {
            const current = document.querySelector(".page-number.active");
            if (current && current.previousElementSibling?.classList.contains("page-number")) {
                fetchDonors(parseInt(current.innerText) - 1);
            }
        });

        document.querySelector(".next-page")?.addEventListener("click", () => {
            const current = document.querySelector(".page-number.active");
            if (current && current.nextElementSibling?.classList.contains("page-number")) {
                fetchDonors(parseInt(current.innerText) + 1);
            }
        });
    }

    // Initial bind & fetch
    ["district", "tehsil", "vc", "blood_group", "name-filter", "username-filter"].forEach(id => {
        document.getElementById(id)?.addEventListener("change", () => fetchDonors(1));
        document.getElementById(id)?.addEventListener("input", () => fetchDonors(1));
    });

    fetchDonors(); // Initial load
    
});

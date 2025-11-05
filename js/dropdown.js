const districtDropdown = document.getElementById("district");
const tehsilDropdown = document.getElementById("tehsil");
const vcDropdown = document.getElementById("vc");

// Fetch Districts
fetch("fetch_districts.php")
  .then((response) => response.json())
  .then((data) => {
    data.forEach((district) => {
      const option = document.createElement("option");
      option.value = district.id;
      option.textContent = district.name;
      districtDropdown.appendChild(option);
    });
  });

// Fetch Tehsils based on selected District
districtDropdown.addEventListener("change", () => {
  const districtId = districtDropdown.value;
  tehsilDropdown.innerHTML = '<option value="">-Select Tehsil-</option>';
  vcDropdown.innerHTML = '<option value="">-Select VC-</option>';

  if (districtId) {
    fetch(`fetch_tehsils.php?district_id=${districtId}`)
      .then((response) => response.json())
      .then((data) => {
        data.forEach((tehsil) => {
          const option = document.createElement("option");
          option.value = tehsil.id;
          option.textContent = tehsil.name;
          tehsilDropdown.appendChild(option);
        });
      });
  }
});

// Fetch VCs based on selected Tehsil
tehsilDropdown.addEventListener("change", () => {
  const tehsilId = tehsilDropdown.value;
  vcDropdown.innerHTML = '<option value="">-Select VC-</option>';

  if (tehsilId) {
    fetch(`fetch_vcs_ncs.php?tehsil_id=${tehsilId}`)
      .then((response) => response.json())
      .then((data) => {
        data.forEach((vc) => {
          const option = document.createElement("option");
          option.value = vc.id;
          option.textContent = vc.name;
          vcDropdown.appendChild(option);
        });
      });
  }
});

// Handle Search Button Click
document.getElementById("search-button").addEventListener("click", () => {
  const bloodGroup = document.getElementById("blood-group").value;
  const district = districtDropdown.value;
  const tehsil = tehsilDropdown.value;
  const vc = vcDropdown.value;

  console.log("Search Criteria:", { bloodGroup, district, tehsil, vc });
  // Perform search logic here (e.g., fetch donors from backend)
});
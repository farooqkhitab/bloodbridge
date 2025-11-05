<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/popup_test.css">
    <title>Blood Donation Ineligibility Form</title>
</head>
<body>

    <h2>Blood Donation Ineligibility Form</h2>

    

    <script>
        document.getElementById("reason").addEventListener("change", function() {
            let daysInput = document.getElementById("days");
            let selectedOption = this.options[this.selectedIndex].text; // Get text of selected option

            if (this.value === "other" || selectedOption.includes("Dental Work")) {
                daysInput.value = "";
                daysInput.removeAttribute("readonly");
            } else if (this.value === "permanent") {
                daysInput.value = "Permanent";
                daysInput.setAttribute("readonly", true);
            } else {
                daysInput.value = this.value;
                daysInput.setAttribute("readonly", true);
            }
        });

        document.getElementById("ineligibilityForm").addEventListener("submit", function(event) {
            event.preventDefault();
            alert("Form submitted successfully!");
        });
    </script>

</body>
</html>

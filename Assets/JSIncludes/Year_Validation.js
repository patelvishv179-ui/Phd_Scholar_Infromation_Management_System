document.addEventListener("DOMContentLoaded", function () {

    const yearInput = document.getElementById("year_of_passing");
    const yearError = document.getElementById("yearError");

    if (!yearInput || !yearError) return; // safety

    yearInput.addEventListener("input", validateYear);

    function validateYear() {

        const value = yearInput.value.trim();
        const regex = /^[0-9]{4}$/;
        const currentYear = new Date().getFullYear();

        if (value === "") {
            yearError.innerHTML = "Year field is required.";
            yearError.style.display = "block";
            yearInput.classList.add("is-invalid");
            return false;
        }
        else if (!regex.test(value)) {
            yearError.innerHTML = "Enter valid 4 digit year.";
            yearError.style.display = "block";
            yearInput.classList.add("is-invalid");
            return false;
        }
        else if (parseInt(value) > currentYear) {
            yearError.innerHTML = "Year cannot be in the future.";
            yearError.style.display = "block";
            yearInput.classList.add("is-invalid");
            return false;
        }
        else {
            yearError.style.display = "none";
            yearInput.classList.remove("is-invalid");
            return true;
        }
    }

    // expose if needed
    window.validateYear = validateYear;

});
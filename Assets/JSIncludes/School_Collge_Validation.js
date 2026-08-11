document.addEventListener("DOMContentLoaded", function () {

    const schoolInput = document.getElementById("school_college");
    const schoolError = document.getElementById("schoolError");

    if (!schoolInput || !schoolError) return; // safety

    schoolInput.addEventListener("input", validateSchool);

    function validateSchool() {

        const value = schoolInput.value.trim();
        const regex = /^[A-Za-z\s]+$/;

        if (value === "") {
            schoolError.innerHTML = "School / College field is required.";
            schoolError.style.display = "block";
            schoolInput.classList.add("is-invalid");
            return false;
        }
        else if (!regex.test(value)) {
            schoolError.innerHTML = "Only letters and spaces allowed.";
            schoolError.style.display = "block";
            schoolInput.classList.add("is-invalid");
            return false;
        }
        else {
            schoolError.style.display = "none";
            schoolInput.classList.remove("is-invalid");
            return true;
        }
    }

    // expose if needed
    window.validateSchool = validateSchool;

});
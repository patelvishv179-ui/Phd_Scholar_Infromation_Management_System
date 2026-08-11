document.addEventListener("DOMContentLoaded", function () {

    const uniInput = document.getElementById("university_board");
    const uniError = document.getElementById("uniError");

    if (!uniInput || !uniError) return;   // safety

    uniInput.addEventListener("input", validateUniversity);

    function validateUniversity() {

        const value = uniInput.value.trim();
        const regex = /^[A-Za-z\s]+$/;

        if (value === "") {
            uniError.innerHTML = "University / Board field is required.";
            uniError.style.display = "block";
            uniInput.classList.add("is-invalid");
            return false;
        }
        else if (!regex.test(value)) {
            uniError.innerHTML = "Only letters and spaces allowed.";
            uniError.style.display = "block";
            uniInput.classList.add("is-invalid");
            return false;
        }
        else {
            uniError.style.display = "none";
            uniInput.classList.remove("is-invalid");
            return true;
        }
    }

    // expose if needed
    window.validateUniversity = validateUniversity;

});
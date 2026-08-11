document.addEventListener("DOMContentLoaded", function () {

    const divInput = document.getElementById("division");
    const divError = document.getElementById("divError");

    if (!divInput || !divError) return; // safety

    divInput.addEventListener("input", validateDivision);

    function validateDivision() {

        const value = divInput.value.trim();
        const regex = /^[A-Za-z\s]+$/;

        if (value === "") {
            divError.innerHTML = "Division field is required.";
            divError.style.display = "block";
            divInput.classList.add("is-invalid");
            return false;
        }
        else if (!regex.test(value)) {
            divError.innerHTML = "Only letters and spaces allowed.";
            divError.style.display = "block";
            divInput.classList.add("is-invalid");
            return false;
        }
        else {
            divError.style.display = "none";
            divInput.classList.remove("is-invalid");
            return true;
        }
    }

    // expose if needed
    window.validateDivision = validateDivision;

});
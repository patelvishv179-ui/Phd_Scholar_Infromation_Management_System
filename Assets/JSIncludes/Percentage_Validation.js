document.addEventListener("DOMContentLoaded", function () {

    const perInput = document.getElementById("percentage");
    const perError = document.getElementById("perError");

    if (!perInput || !perError) return; // safety

    perInput.addEventListener("input", validatePercentage);

    function validatePercentage() {

        const value = perInput.value.trim();
        const regex = /^[0-9]+$/;   // only digits

        if (value === "") {
            perError.innerHTML = "Percentage field is required.";
            perError.style.display = "block";
            perInput.classList.add("is-invalid");
            return false;
        }
        else if (!regex.test(value)) {
            perError.innerHTML = "Only numbers allowed.";
            perError.style.display = "block";
            perInput.classList.add("is-invalid");
            return false;
        }
        else if (parseInt(value) < 0 || parseInt(value) > 100) {
            perError.innerHTML = "Percentage must be between 0 and 100.";
            perError.style.display = "block";
            perInput.classList.add("is-invalid");
            return false;
        }
        else {
            perError.style.display = "none";
            perInput.classList.remove("is-invalid");
            return true;
        }
    }

    // expose if needed
    window.validatePercentage = validatePercentage;

});
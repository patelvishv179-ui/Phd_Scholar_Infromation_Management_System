document.addEventListener("DOMContentLoaded", function () {

    const attemptInput = document.getElementById("no_of_attempts");
    const attemptError = document.getElementById("attemptError");

    if (!attemptInput || !attemptError) return; // safety

    attemptInput.addEventListener("input", validateAttempts);

    function validateAttempts() {

        const value = attemptInput.value.trim();
        const regex = /^[0-9]+$/;

        if (value === "") {
            attemptError.innerHTML = "No. of Attempts is required.";
            attemptError.style.display = "block";
            attemptInput.classList.add("is-invalid");
            return false;
        }
        else if (!regex.test(value)) {
            attemptError.innerHTML = "Only numbers allowed.";
            attemptError.style.display = "block";
            attemptInput.classList.add("is-invalid");
            return false;
        }
        else if (parseInt(value) < 0) {
            attemptError.innerHTML = "Attempts cannot be negative.";
            attemptError.style.display = "block";
            attemptInput.classList.add("is-invalid");
            return false;
        }
        else {
            attemptError.style.display = "none";
            attemptInput.classList.remove("is-invalid");
            return true;
        }
    }

    // expose if needed
    window.validateAttempts = validateAttempts;

});
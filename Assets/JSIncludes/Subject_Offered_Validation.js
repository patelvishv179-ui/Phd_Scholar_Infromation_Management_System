document.addEventListener("DOMContentLoaded", function () {

    const subjectInput = document.getElementById("subject_offered");
    const subjectError = document.getElementById("subjectError");

    if (!subjectInput || !subjectError) return; // safety

    subjectInput.addEventListener("input", validateSubject);

    function validateSubject() {

        const value = subjectInput.value.trim();
        const regex = /^[A-Za-z\s]+$/;

        if (value === "") {
            subjectError.innerHTML = "Subject Offered field is required.";
            subjectError.style.display = "block";
            subjectInput.classList.add("is-invalid");
            return false;
        }
        else if (!regex.test(value)) {
            subjectError.innerHTML = "Only letters and spaces allowed.";
            subjectError.style.display = "block";
            subjectInput.classList.add("is-invalid");
            return false;
        }
        else {
            subjectError.style.display = "none";
            subjectInput.classList.remove("is-invalid");
            return true;
        }
    }

    // expose if needed
    window.validateSubject = validateSubject;

});
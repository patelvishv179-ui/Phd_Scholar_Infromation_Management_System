document.addEventListener("DOMContentLoaded", function () {

    const examInput = document.getElementById("exam_passed");
    const errorMsg  = document.getElementById("examError");

    if (!examInput) return;

    examInput.addEventListener("input", validateExam);

    function validateExam() {
        const value = examInput.value.trim();
        const regex = /^[A-Za-z\s]+$/;

        if (value === "") {
            errorMsg.innerHTML = "Exam Passed field is required";
            errorMsg.style.display = "block";
            examInput.classList.add("is-invalid");
        }
        else if (!regex.test(value)) {
            errorMsg.innerHTML = "Only letters and spaces allowed.";
            errorMsg.style.display = "block";
            examInput.classList.add("is-invalid");
        }
        else {
            errorMsg.style.display = "none";
            examInput.classList.remove("is-invalid");
        }
    }

});
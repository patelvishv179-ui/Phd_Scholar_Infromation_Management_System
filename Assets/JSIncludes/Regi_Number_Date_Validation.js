// ===============================
// Registration Number Protection
// ===============================
document.getElementById("regno").addEventListener("input", function () {

    let original = this.dataset.original;
    let error = document.getElementById("regno_error");

    if (this.value !== original) {
        error.innerHTML = "Please do not change Registration Number";
    } else {
        error.innerHTML = "";
    }
});


// ===============================
// Registration Date Protection
// ===============================
document.getElementById("regdate").addEventListener("change", function () {

    let original = this.dataset.original;
    let error = document.getElementById("regdate_error");

    if (this.value !== original) {
        error.innerHTML = "Please do not change Registration Date";
    } else {
        error.innerHTML = "";
    }
});

const regDateInput = document.getElementById("registration_date");
const today = new Date().toISOString().split("T")[0];

// Force today date
regDateInput.value = today;

// Block manual change
regDateInput.addEventListener("change", function () {
  this.value = today;
});

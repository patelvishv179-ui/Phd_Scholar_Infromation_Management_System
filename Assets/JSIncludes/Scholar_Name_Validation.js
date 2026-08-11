const scholarInput = document.getElementById("scholar_name");
const scholarError = document.getElementById("scholarNameError");

scholarInput.addEventListener("input", function () {
  const value = this.value;

  // Required
  if (value.trim() === "") {
    scholarError.textContent = "Scholar name is required";
    return;
  }

  // Numbers not allowed
  if (/\d/.test(value)) {
    scholarError.textContent = "Numbers are not allowed";
    return;
  }

  // Only alphabets and spaces
  const regex = /^[A-Za-z\s]+$/;
  if (!regex.test(value)) {
    scholarError.textContent = "Special characters are not allowed";
    return;
  }

  // Valid
  scholarError.textContent = "";
});

// Trim extra spaces on blur
scholarInput.addEventListener("blur", function () {
  this.value = this.value.trim();
});
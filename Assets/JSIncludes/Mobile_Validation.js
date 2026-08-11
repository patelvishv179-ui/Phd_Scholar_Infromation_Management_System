const mobileInput = document.getElementById("mobile_number");
const mobileError = document.getElementById("mobileError");

mobileInput.addEventListener("input", function () {
  const value = this.value;

  // Required
  if (value === "") {
    mobileError.textContent = "Mobile number is required";
    return;
  }

  // Space check
  if (/\s/.test(value)) {
    mobileError.textContent = "Spaces are not allowed";
    return;
  }

  // Alphabet check
  if (/[A-Za-z]/.test(value)) {
    mobileError.textContent = "Alphabets are not allowed";
    return;
  }

  // Special character check
  if (/[^0-9]/.test(value)) {
    mobileError.textContent = "Special characters are not allowed";
    return;
  }

  // Length check
  if (value.length !== 10) {
    mobileError.textContent = "Mobile number must be exactly 10 digits";
    return;
  }

  // Valid
  mobileError.textContent = "";
});

// Trim on blur
mobileInput.addEventListener("blur", function () {
  this.value = this.value.trim();
});
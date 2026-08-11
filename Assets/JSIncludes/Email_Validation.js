console.log("EMAIL VALIDATION – UPDATED");

const emailInput = document.getElementById("email");
const emailError = document.getElementById("emailError");

emailInput.addEventListener("input", function () {
  const value = this.value;

  if (value.trim() === "") {
    emailError.textContent = "Email address is required";
    return;
  }

  if (/\s/.test(value)) {
    emailError.textContent = "Spaces are not allowed";
    return;
  }

  // ✅ letters + numbers + dot + @
  if (!/^[A-Za-z0-9.@]+$/.test(value)) {
    emailError.textContent = "Only letters, numbers, '.' and '@' are allowed";
    return;
  }

  if (!/^[A-Za-z0-9.]+@[A-Za-z0-9.]+\.[A-Za-z]{2,}$/.test(value)) {
    emailError.textContent = "Enter a valid email address";
    return;
  }

  emailError.textContent = "";
});

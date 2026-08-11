document.addEventListener("DOMContentLoaded", function () {

  const passwordInput = document.getElementById("password");
  const passwordError = document.getElementById("passwordError");
  const togglePassword = document.getElementById("togglePassword");
  const eyeIcon = document.getElementById("eyeIcon");

  if (!passwordInput || !passwordError || !togglePassword || !eyeIcon) {
    console.error("Password elements not found in DOM");
    return;
  }

  passwordInput.addEventListener("input", function () {

    const rawValue = this.value;     // user typed
    const value = rawValue.trim();  // trimmed

    if (rawValue === "") {
      passwordError.textContent = "Password is required";
      return;
    }

    if (/\s/.test(rawValue)) {
      passwordError.textContent = "Spaces are not allowed in password";
      return;
    }

    if (value.length < 8) {
      passwordError.textContent = "Password must be at least 8 characters";
      return;
    }

    if (!/[A-Z]/.test(value)) {
      passwordError.textContent = "At least one uppercase letter required";
      return;
    }

    if (!/[a-z]/.test(value)) {
      passwordError.textContent = "At least one lowercase letter required";
      return;
    }

    if (!/[0-9]/.test(value)) {
      passwordError.textContent = "At least one number required";
      return;
    }

    if (!/[@$!%*?&]/.test(value)) {
      passwordError.textContent =
        "At least one special character required (@$!%*?&)";
      return;
    }

    passwordError.textContent = "";
  });


  togglePassword.addEventListener("click", function () {
    if (passwordInput.type === "password") {
      passwordInput.type = "text";
      eyeIcon.classList.remove("bi-eye-slash");
      eyeIcon.classList.add("bi-eye");
    } else {
      passwordInput.type = "password";
      eyeIcon.classList.remove("bi-eye");
      eyeIcon.classList.add("bi-eye-slash");
    }
  });

});

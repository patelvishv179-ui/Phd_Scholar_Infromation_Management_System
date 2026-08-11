document.addEventListener("DOMContentLoaded", function () {

  const passwordInput = document.getElementById("password");
  const confirmPasswordInput = document.getElementById("cpassword");
  const confirmPasswordError = document.getElementById("confirmPasswordError");

  /* Disable confirm password until password filled */
  passwordInput.addEventListener("input", function () {

    if (this.value.trim() === "") {
      confirmPasswordInput.disabled = true;
      confirmPasswordInput.value = "";
      confirmPasswordInput.placeholder = "First please fill Password field";
      confirmPasswordError.textContent = "";
    } else {
      confirmPasswordInput.disabled = false;
      confirmPasswordInput.placeholder = "Confirm Password";
    }
  });

  /* Live match validation */
  confirmPasswordInput.addEventListener("input", function () {

    const pass = passwordInput.value;
    const cpass = this.value;

    if (cpass === "") {
      confirmPasswordError.textContent = "Confirm Password is required";
      return;
    }

    if (cpass !== pass) {
      confirmPasswordError.textContent = "Passwords do not match";
      return;
    }

    confirmPasswordError.textContent = "";
  });

});

document.addEventListener("DOMContentLoaded", function () {

    const generateBtn = document.getElementById("generateQrBtn");
    const registerBtn = document.getElementById("registerBtn");

    generateBtn.addEventListener("click", function () {

        // Call PHP to set session
        fetch("Assets/PHPIncludes/Qr_Validation.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "set_qr_session=1"
        })
        .then(res => res.text())
        .then(data => {

            if (data.trim() === "success") {

                registerBtn.disabled = false;
                registerBtn.innerText = "Register";
                generateBtn.innerText = "QR Generated ✔";
                generateBtn.disabled = true;

            }

        });

    });

});

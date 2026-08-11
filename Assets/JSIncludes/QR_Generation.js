document.addEventListener("DOMContentLoaded", function () {

    const generateBtn = document.getElementById("generateQrBtn");
    const registerBtn = document.getElementById("registerBtn");

    // If QR already generated in previous submit
    if (typeof QR_ALREADY_GENERATED !== "undefined" && QR_ALREADY_GENERATED === true) {

        generateBtn.disabled = true;
        generateBtn.innerText = "QR Generated ✓";

        registerBtn.disabled = false;
        registerBtn.innerText = "Register";
    }

    generateBtn.addEventListener("click", function () {

        fetch("Assets/PHPIncludes/Qr_Validation.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: "set_qr_session=1"
        })
        .then(response => response.text())
        .then(data => {

            if (data.trim() === "success") {

                generateBtn.disabled = true;
                generateBtn.innerText = "QR Generated ✓";

                registerBtn.disabled = false;
                registerBtn.innerText = "Register";

                alert("QR Generated Successfully");
            }
        });

    });

});

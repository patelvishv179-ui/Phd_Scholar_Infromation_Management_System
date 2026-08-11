/* ================= LOADER SHOW ================= */
console.log("LOADER IMPLEMENTED !");

/* ================= HIDE LOADER ON PAGE LOAD ================= */
window.addEventListener("load", hideLoader);
/*  FIX: BACK BUTTON / CACHE ISSUE */
window.addEventListener("pageshow", function () {
    hideLoader();
});
function hideLoader() {
    const loader = document.getElementById("loader");
    if (!loader) return;

    loader.classList.remove("d-flex");
    loader.classList.add("d-none");
}

function showLoader() {
    const loader = document.getElementById("loader");

    if (!loader) return;

    loader.classList.remove("d-none");
    loader.classList.add("d-flex");

    // 🔥 VALIDATION CHECK AFTER SMALL DELAY
    setTimeout(() => {

        // બધા forms check કર
        const forms = document.querySelectorAll("form");

        let isValid = true;

        forms.forEach(form => {
            if (!form.checkValidity()) {
                isValid = false;
            }
        });

        // જો કોઈ form invalid છે → loader hide
        if (!isValid) {
            loader.classList.remove("d-flex");
            loader.classList.add("d-none");
        }

    }, 50); // very small delay
}


/* ================= PAGE LOAD HIDE ================= */
window.addEventListener("load", function () {
    const loader = document.getElementById("loader");

    if (!loader) return;

    loader.classList.remove("d-flex");
    loader.classList.add("d-none");
});


/* ================= UNIVERSAL LINK LOADER ================= */
document.addEventListener("click", function (e) {

    const link = e.target.closest("a");

    if (!link) return;

    const href = link.getAttribute("href");

    if (
        !href ||
        href.startsWith("#") ||
        href.startsWith("mailto:") ||
        href.startsWith("tel:") ||
        link.target === "_blank" ||
        link.hasAttribute("download") ||
        link.classList.contains("no-loader")
    ) {
        return;
    }

    // ✔ valid navigation → loader show
    showLoader();
});
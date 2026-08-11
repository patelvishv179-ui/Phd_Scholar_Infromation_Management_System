// ===============================
// Guide Name & Address Validation
// ===============================
document.getElementById("guide").addEventListener("input", function(){

    let value = this.value;
    let error = document.getElementById("guideError");

    if(value.trim() === ""){
        error.innerHTML = "Guide Name & Address is required";
    }
    else if(!/^[A-Za-z\s\-\/]+$/.test(value)){
        error.innerHTML = "Only letters, space, - and / allowed";
    }
    else{
        error.innerHTML = "";
    }

});

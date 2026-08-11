let adhar = document.getElementById("adhar");
let adharErr = document.getElementById("adhar_error");

adhar.addEventListener("input", function(){

    // remove non-digits live
    this.value = this.value.replace(/[^0-9]/g, "");

    if(this.value === ""){
        adharErr.innerHTML = "Aadhar number is required";
    }
    else if(this.value.length !== 12){
        adharErr.innerHTML = "Enter 12 digit Aadhar number";
    }
    else{
        adharErr.innerHTML = "";
    }

});

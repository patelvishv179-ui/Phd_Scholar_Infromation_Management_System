let certNo = document.getElementById("cert_no");
let certErr = document.getElementById("cert_no_error");

certNo.addEventListener("input", function(){

    let val = this.value;

    if(val.trim() === ""){
        certErr.innerHTML = "Eligibility Certificate Number is required";
    }
    else if(/\s/.test(val)){
        certErr.innerHTML = "Spaces are not allowed";
    }
    else if(!/^[A-Za-z0-9]+$/.test(val)){
        certErr.innerHTML = "Only letters and numbers allowed";
    }
    else{
        certErr.innerHTML = "";
    }

});

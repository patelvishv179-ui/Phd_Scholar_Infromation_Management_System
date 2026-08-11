let email = document.getElementById("scholar_email");
let emailErr = document.getElementById("email_error");

email.addEventListener("input", function(){

    let val = this.value;

    let pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if(val.trim() === ""){
        emailErr.innerHTML = "Email is required";
    }
    else if(!pattern.test(val)){
        emailErr.innerHTML = "Enter valid email address";
    }
    else{
        emailErr.innerHTML = "";
    }

});

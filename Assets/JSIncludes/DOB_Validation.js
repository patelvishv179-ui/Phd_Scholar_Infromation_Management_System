console.log("DOB");

let dob = document.getElementById("dob");
let dobErr = document.getElementById("dob_error");

dob.addEventListener("change", function(){

    if(this.value === ""){
        dobErr.innerHTML = "Date of Birth is required";
        return;
    }

    let birth = new Date(this.value);
    let today = new Date();

    let age = today.getFullYear() - birth.getFullYear();

    if(age < 14){
        dobErr.innerHTML = "Minimum age must be 14 years";
    }
    else{
        dobErr.innerHTML = "";
    }

});

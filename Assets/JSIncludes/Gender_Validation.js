let gender = document.getElementById("gender");
let genderErr = document.getElementById("gender_error");

gender.addEventListener("change", function(){

    if(this.value === ""){
        genderErr.innerHTML = "Please select Gender";
    }
    else{
        genderErr.innerHTML = "";
    }

});

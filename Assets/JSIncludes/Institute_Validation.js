let institute = document.getElementById("institute_where");
let instErr   = document.getElementById("institute_error");

institute.addEventListener("input", function(){

    let pattern = /^[A-Za-z.\-\s]+$/;

    if(this.value.trim() === ""){
        instErr.innerHTML = "Institute name is required";
    }
    else if(!pattern.test(this.value)){
        instErr.innerHTML = "Only letters, space, dot (.) and hyphen (-) allowed";
    }
    else{
        instErr.innerHTML = "";
    }

});

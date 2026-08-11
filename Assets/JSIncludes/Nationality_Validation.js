let nat = document.getElementById("nationality");
let natErr = document.getElementById("nationality_error");

nat.addEventListener("input", function(){

    let val = this.value;

    if(val.trim() === ""){
        natErr.innerHTML = "Nationality is required";
    }
    else if(!/^[A-Za-z ]+$/.test(val)){
        natErr.innerHTML = "Only letters and spaces allowed";
    }
    else{
        natErr.innerHTML = "";
    }

});

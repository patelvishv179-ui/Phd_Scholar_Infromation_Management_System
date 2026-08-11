let ppin = document.getElementById("perm_pin");
let ppinErr = document.getElementById("perm_pin_error");

ppin.addEventListener("input", function(){

    this.value = this.value.replace(/[^0-9]/g,'');

    if(this.value.trim() === ""){
        ppinErr.innerHTML = "PIN code is required";
    }
    else if(!/^[1-9][0-9]{5}$/.test(this.value)){
        ppinErr.innerHTML = "Enter valid 6 digit PIN code";
    }
    else{
        ppinErr.innerHTML = "";
    }

});

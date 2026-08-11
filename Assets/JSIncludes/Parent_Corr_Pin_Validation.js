let pcpin = document.getElementById("parent_corr_pin");
let pcpinErr = document.getElementById("pcpin_error");

pcpin.addEventListener("input", function(){

    // allow only digits
    this.value = this.value.replace(/[^0-9]/g,'');

    if(this.value.trim() === ""){
        pcpinErr.innerHTML = "PIN code is required";
    }
    else if(!/^[1-9][0-9]{5}$/.test(this.value)){
        pcpinErr.innerHTML = "Enter valid 6 digit PIN code";
    }
    else{
        pcpinErr.innerHTML = "";
    }

});

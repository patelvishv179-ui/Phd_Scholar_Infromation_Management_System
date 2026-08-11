document.getElementById("fee_no").addEventListener("input", function(){

    let value = this.value.replace(/\s/g,'');
    this.value = value;

    let error = document.getElementById("feeError");

    if(value === ""){
        error.innerHTML = "Fee Receipt Number is required";
    }
    else if(!/^[A-Za-z0-9]+$/.test(value)){
        error.innerHTML = "Only letters and numbers allowed";
    }
    else{
        error.innerHTML = "";
    }

});
let pmobile = document.getElementById("parent_mobile");
let pmobileErr = document.getElementById("parent_mobile_error");

pmobile.addEventListener("input", function(){

    let val = this.value;

    // remove non-digits
    this.value = val.replace(/[^0-9]/g,'');

    if(this.value.trim() === ""){
        pmobileErr.innerHTML = "Mobile number is required";
    }
    else if(!/^[6-9][0-9]{9}$/.test(this.value)){
        pmobileErr.innerHTML = "Enter valid 10 digit mobile number";
    }
    else{
        pmobileErr.innerHTML = "";
    }

});

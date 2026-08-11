let permAddr = document.getElementById("perm_address");
let permAddrErr = document.getElementById("perm_address_error");

permAddr.addEventListener("input", function(){

    let val = this.value;

    if(val.trim() === ""){
        permAddrErr.innerHTML = "Permanent Address is required";
    }
    else if(!/^[A-Za-z0-9 ,]+$/.test(val)){
        permAddrErr.innerHTML = "Only letters, numbers and comma (,) allowed";
    }
    else{
        permAddrErr.innerHTML = "";
    }

});

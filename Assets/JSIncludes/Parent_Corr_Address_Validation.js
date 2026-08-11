let pcorr = document.getElementById("parent_corr_address");
let pcorrErr = document.getElementById("pcorr_error");

pcorr.addEventListener("input", function(){

    let val = this.value;

    if(val.trim() === ""){
        pcorrErr.innerHTML = "Correspondence Address is required";
    }
    else if(!/^[A-Za-z0-9 ,]+$/.test(val)){
        pcorrErr.innerHTML = "Only letters, numbers and comma (,) allowed";
    }
    else{
        pcorrErr.innerHTML = "";
    }

});

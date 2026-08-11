document.getElementById("fee_date").addEventListener("change", function(){

    let value = this.value;
    let error = document.getElementById("feeDateError");

    let today = new Date().toISOString().split("T")[0];

    if(value === ""){
        error.innerHTML = "JS Fee Receipt Date is required";
    }
    else if(value > today){
        error.innerHTML = "Future date is not allowed";
    }
    else{       
        error.innerHTML = "";
    }

});
let certDate = document.getElementById("cert_date");
let certDateErr = document.getElementById("cert_date_error");

certDate.addEventListener("change", function(){

    if(this.value === ""){
        certDateErr.innerHTML = "Certificate Date is required";
        return;
    }

    let selected = new Date(this.value);
    let today = new Date();
    today.setHours(0,0,0,0);

    if(selected > today){
        certDateErr.innerHTML = "Future date is not allowed";
    }
    else{
        certDateErr.innerHTML = "";
    }
});

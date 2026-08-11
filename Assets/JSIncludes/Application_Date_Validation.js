let appDate = document.getElementById("app_date");
let appDateErr = document.getElementById("app_date_error");

appDate.addEventListener("change", function(){

    let today = new Date().toISOString().split("T")[0];

    if(this.value === ""){
        appDateErr.innerHTML = "Application date is required";
    }
    else if(this.value > today){
        appDateErr.innerHTML = "Future date is not allowed";
        this.value = "";
    }
    else{
        appDateErr.innerHTML = "";
    }

});

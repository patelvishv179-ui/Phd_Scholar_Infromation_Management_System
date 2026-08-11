let category = document.getElementById("category");
let catErr   = document.getElementById("category_error");

category.addEventListener("change", function(){

    if(this.value === ""){
        catErr.innerHTML = "Please select admission category";
    }
    else{
        catErr.innerHTML = "";
    }

});

let sname = document.getElementById("scholar_name");
let snameErr = document.getElementById("scholar_name_error");

sname.addEventListener("input", function(){

    let val = this.value;

    if(val.trim() === ""){
        snameErr.innerHTML = "Scholar Name is required";
    }
    else if(!/^[A-Za-z ]+$/.test(val)){
        snameErr.innerHTML = "Only letters and spaces allowed";
    }
    else{
        snameErr.innerHTML = "";
    }

});

let pname = document.getElementById("parent_name");
let pnameErr = document.getElementById("parent_name_error");

pname.addEventListener("input", function(){

    let val = this.value;

    if(val.trim() === ""){
        pnameErr.innerHTML = "Parent / Guardian Name is required";
    }
    else if(!/^[A-Za-z ]+$/.test(val)){
        pnameErr.innerHTML = "Only letters and spaces allowed";
    }
    else{
        pnameErr.innerHTML = "";
    }

});

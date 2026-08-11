let rel = document.getElementById("parent_relation");
let relErr = document.getElementById("relation_error");

rel.addEventListener("input", function(){

    let val = this.value;

    if(val.trim() === ""){
        relErr.innerHTML = "Relationship is required";
    }
    else if(!/^[A-Za-z ]+$/.test(val)){
        relErr.innerHTML = "Only letters and spaces allowed";
    }
    else{
        relErr.innerHTML = "";
    }

});

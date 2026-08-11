let work = document.getElementById("applied_work");
let workErr = document.getElementById("applied_work_error");

work.addEventListener("input", function(){

    let pattern = /^[A-Za-z.\-\/\s]+$/;

    if(this.value.trim() === ""){
        workErr.innerHTML = "Applied for work is required";
    }
    else if(!pattern.test(this.value)){
        workErr.innerHTML = "Only letters, space, dot (.), hyphen (-) and slash (/) allowed";
    }
    else{
        workErr.innerHTML = "";
    }

});

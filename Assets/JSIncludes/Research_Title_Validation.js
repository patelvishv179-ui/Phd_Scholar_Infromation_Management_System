
// ===============================
// Research Title Validation
// ===============================


document.addEventListener("DOMContentLoaded", function(){

    document.getElementById("title").addEventListener("input", function(){

        let value = this.value;

        let error = document.getElementById("titleError");

        if(value.trim() === ""){

            error.innerHTML = "Research Title is required";

        }
        else if(!/^[A-Za-z\s\-\/]+$/.test(value)){

            error.innerHTML = "Only letters, space, - and / allowed";

        }
        else{

            error.innerHTML = "";

        }

    });

});


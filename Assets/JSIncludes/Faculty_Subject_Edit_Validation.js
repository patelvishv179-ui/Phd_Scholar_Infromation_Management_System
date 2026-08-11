document.addEventListener("DOMContentLoaded", function(){

    const facultySelect = document.getElementById("faculty");
    const subjectSelect = document.getElementById("subject");

    if(!facultySelect || !subjectSelect) return; // safety

    // Faculty change -> Load subject
    facultySelect.addEventListener("change", function(){

        let fid = this.value;

        if(fid === ""){
            subjectSelect.innerHTML = "<option value=''>-- Select Subject --</option>";
            return;
        }

        fetch("Academic_Detail_Edit.php?action=getSubjects&faculty_id=" + fid)
        .then(res => res.text())
        .then(data => {
            subjectSelect.innerHTML = data;
        });
    });

    // Faculty required
    facultySelect.addEventListener("change", function(){
        let err = this.nextElementSibling;
        err.innerHTML = (this.value==="") ? "Faculty is required" : "";
    });

    // Subject required
    subjectSelect.addEventListener("change", function(){
        let err = this.nextElementSibling;
        err.innerHTML = (this.value==="") ? "Subject is required" : "";
    });

});
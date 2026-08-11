document.addEventListener("DOMContentLoaded", function () {

  const facultySelect = document.getElementById("faculty");
  const subjectSelect = document.getElementById("subject");

  // =============================
  // PAGE LOAD (after validation error)
  // =============================
  if (OLD_FACULTY !== "") {
    facultySelect.value = OLD_FACULTY;
    loadSubjects(OLD_FACULTY, OLD_SUBJECT);
  }

  // =============================
  // FACULTY CHANGE
  // =============================
  facultySelect.addEventListener("change", function () {

    const fid = this.value;

    if (fid === "") {
      subjectSelect.innerHTML =
        "<option value=''>First Select Faculty</option>";
      subjectSelect.disabled = true;
      return;
    }

    loadSubjects(fid, "");
  });

  // =============================
  // LOAD SUBJECTS FUNCTION
  // =============================
  function loadSubjects(fid, selectedSubject) {

    fetch("Registration.php?action=getSubjects&faculty_id=" + fid)
      .then(res => res.text())
      .then(data => {

        subjectSelect.innerHTML = data;
        subjectSelect.disabled = false;

        if (selectedSubject !== "") {
          subjectSelect.value = selectedSubject;
        }
      });
  }

});

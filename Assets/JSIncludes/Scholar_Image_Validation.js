document.getElementById("scholar_image").addEventListener("change",function(){

    let file = this.value;
    let ext = file.split('.').pop().toLowerCase();

    if(["jpg","jpeg","png"].indexOf(ext)==-1){
        alert("Only JPG and PNG allowed");
        this.value="";
    }
});

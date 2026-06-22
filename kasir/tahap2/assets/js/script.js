$(document).ready(function(){

    $('.datatable').DataTable();

});

function previewImage(input,idPreview){

    let preview =
    document.getElementById(idPreview);

    let file =
    input.files[0];

    if(file){

        let reader =
        new FileReader();

        reader.onload=function(e){

            preview.src=e.target.result;

        }

        reader.readAsDataURL(file);

    }

}
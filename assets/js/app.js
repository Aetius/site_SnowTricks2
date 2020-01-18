/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
require('../css/app.css');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
// const $ = require('jquery');


//show a modal to confirm the delete of a trick
(function(){

    //let confirmClick = document.querySelectorAll(".trick-delete-modal");
    let doc = (document.querySelector("#articleId form"));
    let token = document.getElementById("token");

    document.addEventListener("click", function (e) {
        let searchValueE = e.target.attributes.class.value;


        if (searchValueE.split(" ").find(element => element === "trick-delete-modal")){
            let url = (e.target.parentNode.getAttribute("data-url"));
            let idTrick = e.target.parentNode.previousElementSibling.getAttribute('value');
            doc.setAttribute("action", url);
            token.setAttribute("value", idTrick);
        }
    })
})();


//correct the upload bug in bootstrap : show if a file is in standby for upload. (for exemple, picture file)
(function () {
    $('.dropdown-toggle').dropdown();
    $('.custom-file-input').on('change', function(event) {
        var inputFile = event.currentTarget;
        $(inputFile).parent()
            .find('.custom-file-label')
            .html(inputFile.files[0].name);
    });
})();


//show an arrow to go down .
(function(){
    $(function () {
        $(window).scroll(function () {
            if ($(this).scrollTop() > 200 ) {
                $('.up-arrow').css('right','15px');
                $('.down-arrow').css( 'right','-100px' );
            } else {
                $('.up-arrow').removeAttr( 'style' );
                $('.down-arrow').removeAttr( 'style' );
            }
        });
    });
})();

//animation arrow home page
let element = document.getElementById('arrow-down');
element.addEventListener("click", function (event) {
   /* event.preventDefault();*/
    window.scrollTo(0, 75);

})
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
    let article = (document.querySelector("#articleId form"));

    let token = document.getElementById("token");

    document.addEventListener("click", function (e) {
        let searchValueE = e.target.attributes.class.value;

        if (searchValueE.split(" ").find(element => element === "trick-delete-modal")){
            let url = (e.target.parentNode.getAttribute("data-url"));
            let idTrick = e.target.parentNode.previousElementSibling.getAttribute('value');
            article.setAttribute("action", url);
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
(function(){
    let element = document.getElementById('arrow-down');
    element.addEventListener("click", function (event) {
        /* event.preventDefault();*/
        window.scrollTo(0, 75);
    })
})();


//home page AJAX
(function () {

    let getHTTPRequest = function(){
        var httpRequest = false;

        if (window.XMLHttpRequest){
            httpRequest = new XMLHttpRequest();
            if(httpRequest.overrideMimeType){
                httpRequest.overrideMimeType('text/xml');
            }
        }else if (window.ActiveXObject){
            try {
                httpRequest = new ActiveXObject("Msxml2.XMLHTTP");
            }
            catch (e) {
                try {
                    httpRequest = new ActiveXObject("Microsoft.XMLHTTP");
                }
                catch (e) {}
            }
            if (!httpRequest){
                alert('Abandon: (impossible de créer une instance XMLHTTP');
                return false;
            }
        }
        return httpRequest;
    };

    var link = document.querySelector('#addTricks');


        link.addEventListener('click', function (e) {
            e.preventDefault();
            let wait = document.getElementById('waitButton');  console.log(wait)
            let numberPage = (document.getElementsByClassName('numberPage')).length;
            //result.innerHTML = "chargement";
            let httpRequest = getHTTPRequest();
            httpRequest.onreadystatechange = function () {
                if (httpRequest.readyState === 4) {
                    let create = document.createElement("div");
                    create.setAttribute('class', 'numberPage')
                    document.getElementById('result').appendChild(create).innerHTML = httpRequest.responseText
                    link.removeAttribute('hidden')
                    wait.setAttribute('hidden', 'true')
                    if (document.getElementById('hideButton')){
                        link.remove();
                    }

                 /*console.log(document.querySelector('.test'))*/
                    /*let picture = (document.querySelector("#pictureId"));
                    picture.setAttribute("action", url );*/
                }

            };

            httpRequest.open('GET', '/'+numberPage, true);
            httpRequest.send();
            link.setAttribute('hidden', 'true')
            wait.removeAttribute('hidden')

        })

})();





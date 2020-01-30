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
$(function(){

//correct the upload bug in bootstrap : show if a file is in standby for upload. (for exemple, images file)
    (function () {
        $('.dropdown-toggle').dropdown();
        $(document).on('change','.custom-file-input', function (event) {
            var inputFile = event.currentTarget;
            $(inputFile).parent()
                .find('.custom-file-label')
                .html(inputFile.files[0].name);
        });
    })();


//show an arrow to go down .
    (function () {
        $(function () {
            $(window).scroll(function () {
                if ($(this).scrollTop() > 200) {
                    $('.up-arrow').css('right', '15px');
                    $('.down-arrow').css('right', '-100px');
                } else {
                    $('.up-arrow').removeAttr('style');
                    $('.down-arrow').removeAttr('style');
                }
            });
        });
    })();
});

//animation arrow home page
(function () {
    let element = document.getElementById('arrow-down');
    if (element !== null){
        element.addEventListener("click", function (event) {
            /* event.preventDefault();*/
            window.scrollTo(0, 75);
        })
    }

})();


//show a modal to confirm the delete of a trick
(function () {

    //let confirmClick = document.querySelectorAll(".trick-delete-modal");
    let article = (document.querySelector("#articleId form"));

    let token = document.getElementById("token");

    document.addEventListener("click", function (e) {
        let searchValueE = e.target.attributes.class.value;

        if (searchValueE.split(" ").find(element => element === "trick-delete-modal")) {
            let url = (e.target.parentNode.getAttribute("data-url"));
            let idTrick = e.target.parentNode.previousElementSibling.getAttribute('value');
            article.setAttribute("action", url);
            token.setAttribute("value", idTrick);
        }
    })
})();

let getHTTPRequest = function () {
    var httpRequest = false;

    if (window.XMLHttpRequest) {
        httpRequest = new XMLHttpRequest();
        if (httpRequest.overrideMimeType) {
            httpRequest.overrideMimeType('text/xml');
        }
    } else if (window.ActiveXObject) {
        try {
            httpRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                httpRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
            }
        }
        if (!httpRequest) {
            alert('Abandon: (impossible de créer une instance XMLHTTP');
            return false;
        }
    }
    return httpRequest;
};
//home page AJAX
(function () {

    var link = document.querySelector('#addTricks');

    if (link !== null){
        link.addEventListener('click', function (e) {
            e.preventDefault();
            let wait = document.getElementById('waitButton');
            console.log(wait);
            let numberPage = (document.getElementsByClassName('numberPage')).length;
            //result.innerHTML = "chargement";
            let httpRequest = getHTTPRequest();
            httpRequest.onreadystatechange = function () {
                if (httpRequest.readyState === 4) {
                    let create = document.createElement("div");
                    create.setAttribute('class', 'numberPage');
                    document.getElementById('result').appendChild(create).innerHTML = httpRequest.responseText;
                    link.removeAttribute('hidden');
                    wait.setAttribute('hidden', 'true');
                    if (document.getElementById('hideButton')) {
                        link.remove();
                    }
                }
            };
            httpRequest.open('GET', '/' + numberPage, true);
            httpRequest.send();

            link.setAttribute('hidden', 'true');
            wait.removeAttribute('hidden')

        })
    }
})();

//Ajax request in edit photo
//function MDN to run this request with all browser
(function () {



    let links = document.querySelectorAll('.thumbnails');
    for (let i = 0; i<links.length; i++) {
        if (links[i] !== null){
            links[i].addEventListener('click', function (e) {
                e.preventDefault();

                result.innerHTML = "Chargement";
                let httpRequest = getHTTPRequest();
                httpRequest.onreadystatechange = function () {
                    if (httpRequest.readyState === 4) {

                        document.getElementById('result').innerHTML = httpRequest.responseText;
                        let picture = (document.querySelector("#pictureId"));
                        picture.setAttribute("action", url );
                    }

                };
                let url = this.getAttribute('data-url');
                httpRequest.open('GET', url, true);
                httpRequest.send();
            })
        }
    }
})();

//show trick's comments AJAX
(function () {



    var link = document.querySelector('#showMoreComments');

    if (link !== null){
        link.addEventListener('click', function (e) {
            e.preventDefault();
            let wait = document.getElementById('waitButton');

            let numberPage = (document.getElementsByClassName('numberPage')).length;
            //result.innerHTML = "chargement";
            let httpRequest = getHTTPRequest();
            httpRequest.onreadystatechange = function () {
                if (httpRequest.readyState === 4) {
                    let create = document.createElement("div");
                    create.setAttribute('class', 'numberPage');
                    document.getElementById('result').appendChild(create).innerHTML = httpRequest.responseText;
                    link.removeAttribute('hidden');
                    wait.setAttribute('hidden', 'true');
                    if (document.getElementById('hideButton')) {
                        link.remove();
                    }
                }
            };
            console.log(numberPage)
            let url = this.getAttribute('data-url'); console.log(url);
            httpRequest.open('GET', url+numberPage, true);
            httpRequest.send();
            link.setAttribute('hidden', 'true');
            wait.removeAttribute('hidden')

        })
    }
})();
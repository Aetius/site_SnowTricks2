/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
require('../css/app.css');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
 //const $ = require('jquery');
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

    //Ajax request in edit photo
  /*  (function () {
        let links = document.querySelectorAll('.thumbnails');
        for (let i = 0; i<links.length; i++) {
            if (links[i] !== null){
                links[i].addEventListener('click', function (e) {
                    e.preventDefault();
                    var id = "session_id()";
                    result.innerHTML = "Chargement";
                    let url = this.getAttribute('data-url');
                    $.ajax(url, {
                        cache : false,
                        headers: {
                            "X-Requested-With": "XMLHttpRequest",
                            "Content-Type": "application/x-www-form-urlencoded",
                            "PHPSESSID": "ltbseik0a5p64sojptseaq6539"
                        },
                        type: 'POST',
                        beforeSend(xhr){
                            xhr.withCredentials = true;
                            xhr.sessionStorage
                        },

                        //data:{"_csrf/https-authenticate": {_TOKEN}},


                       /!* xhrFields: {
                            withCredentials:true
                        },*!/

                        complete: function(result) {
                            document.getElementById('result').innerHTML = result.responseText;
                            let picture = (document.querySelector("#pictureId"));
                            picture.setAttribute("action", url);
                        }
                    });
                })
            }
        }
    })();*/



    //animation arrow home page
    (function () {
        let element = $('arrow-down');
        if (element !== null){
            element.click(function (event) {
                event.preventDefault();
                window.scrollTo(0, 75);
            })
        }

    })();


    //show a modal to confirm the delete of a trick
    (function () {

        document.addEventListener("click", function (e) {
            let searchValueE = e.target.attributes.class.value;

            if (searchValueE.split(" ").find(element => element === "trick-delete-modal")) {
                let url = (e.target.parentNode.getAttribute("data-url"));
                let idTrick = e.target.parentNode.previousElementSibling.getAttribute('value');
                $("#articleId form").attr("action", url);
                $("#token").attr("value", idTrick);
            }
        })
    })();

/*let getHTTPRequest = function () {
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
};*/
    //home page AJAX
    (function () {
        let link = $('#addTricks');
        if (link !== null){
            link.click(function (e) {
                e.preventDefault();
                let wait = $('waitButton');
                let url = ('/page/'+(($('.numberPage')).length));

                link.attr('hidden', 'true');
                wait.removeAttr('hidden')

                $.ajax(url, {
                    complete: function(result){
                        let create = document.createElement("div");
                        create.setAttribute('class', 'numberPage');
                        document.getElementById('result').appendChild(create).innerHTML = result.responseText;
                        link.removeAttr('hidden');
                        wait.attr('hidden', 'true');
                        if (document.getElementById('hideButton')) {
                            link.remove();
                        }
                    }
                })
            })
        }
    })();


    //Ajax request in edit photo
    //function MDN to run this request with all browser
     (function () {

        let links = $('.thumbnails');
        for (let i = 0; i<links.length; i++) {
            if (links[i] !== null){
                links[i].addEventListener('click', function (e) {
                    e.preventDefault();
                    result.innerHTML = "Chargement";
                    let url = this.getAttribute('data-url');

                    $.ajax(url, {
                        complete: function(result){
                            document.getElementById('result').innerHTML= result.responseText;
                            let picture = (document.querySelector("#pictureId"));
                            picture.setAttribute("action", url );
                        }
                    })
                })
            }
        }
    })();

    //show trick's comments AJAX
    (function () {
        let link = $('#showMoreComments');

        if (link !== null){
            link.click( function (e) {
                e.preventDefault();
                let wait = document.getElementById('waitButton');

                let numberPage = (document.getElementsByClassName('numberPage')).length;
                let url = (this.getAttribute('data-url'))+numberPage;
                link.attr('hidden', 'true');
                wait.removeAttribute('hidden')

                $.ajax(url, {
                    complete: function(result){
                        let create = document.createElement("div");
                        create.setAttribute('class', 'numberPage');
                        document.getElementById('result').appendChild(create).innerHTML = result.responseText;
                        link.removeAttr('hidden');
                        wait.setAttribute('hidden', 'true');
                        if (document.getElementById('hideButton')) {
                            link.remove();
                        }
                    }
                })
            })
        }
    })();
});

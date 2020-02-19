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
        $(".dropdown-toggle").dropdown();
        $(document).on("change",".custom-file-input", function (event) {
            var inputFile = event.currentTarget;
            $(inputFile).parent()
                .find(".custom-file-label")
                .html(inputFile.files[0].name);
        });
    })();


//show an arrow to go down .
    (function () {
        $(function () {
            $(window).scroll(function () {
                if ($(this).scrollTop() > 200) {
                    $(".up-arrow").css("right", "15px");
                    $(".down-arrow").css("right", "-100px");
                } else {
                    $(".up-arrow").removeAttr("style");
                    $(".down-arrow").removeAttr("style");
                }
            });
        });
    })();


    //animation arrow home page
    (function () {
        let element = $("arrow-down");
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
           if (e.target.attributes.class.value !== null){
               let searchValueE = e.target.attributes.class.value;

               if (searchValueE.split(" ").find(element => (element === "trick-delete-modal"))) {
                   let url = (e.target.parentNode.getAttribute("data-url"));
                   let idTrick = e.target.parentNode.previousElementSibling.getAttribute("value");
                   $("#articleId form").attr("action", url);
                   $("#token").attr("value", idTrick);
               }
           }

        })
    })();


    //home page AJAX
    (function () {
        let link = $("#addTricks");
        if (link !== null){
            link.click(function (e) {
                e.preventDefault();
                let wait = $("#waitButton");
                let url = ("/page/"+(($(".numberPage")).length));

                link.attr("hidden", "true");
                wait.removeAttr('hidden');

                $.ajax(url, {
                    complete: function(result){
                        let create = document.createElement("div");
                        create.setAttribute("class", "numberPage");
                        document.getElementById("result").appendChild(create).innerHTML = result.responseText;
                        link.removeAttr("hidden");
                        wait.attr("hidden", "true");
                        if (document.getElementById("hideButton")) {
                            link.remove();
                        }
                    }
                })
            })
        }
    })();


    //Ajax request in edit photo
    (function () {

        let links = $(".thumbnails");
        for (let i = 0; i<links.length; i++) {
            if (links[i] !== null){
                let result = $("#result");
                links[i].addEventListener("click", function (e) {
                    e.preventDefault();
                    result.innerHTML = "Chargement";
                    let url = this.getAttribute("data-url");

                    $.ajax(url, {
                        complete: function(result){
                            document.getElementById("result").innerHTML= result.responseText;
                            let picture = (document.querySelector("#pictureId"));
                            picture.setAttribute("action", url );
                        }
                    })
                })
            }
        }
    })();


    //Ajax request in edit videos
    (function () {
        let links = $(".thumbnails");
        for (let i = 0; i<links.length; i++) {
            if (links[i] !== null){
                let resultVideos = $("#resultVideos");
                links[i].addEventListener("click", function (e) {
                    e.preventDefault();
                    resultVideos.innerHTML = "Chargement";
                    let url = this.getAttribute("data-url");

                    $.ajax(url, {
                        complete: function(result){
                            document.getElementById("resultVideos").innerHTML= result.responseText;

                        }
                    })
                })
            }
        }
    })();

    //show trick's comments AJAX
    (function () {
        let link = $("#showMoreComments");

        if (link !== null){
            link.click( function (e) {
                e.preventDefault();
                let wait = document.getElementById("waitButton");

                let numberPage = (document.getElementsByClassName("numberPage")).length;
                let url = (this.getAttribute("data-url"))+numberPage;
                link.attr("hidden", "true");
                wait.removeAttribute("hidden")

                $.ajax(url, {
                    complete: function(result){
                        let create = document.createElement("div");
                        create.setAttribute("class", "numberPage");
                        document.getElementById("result").appendChild(create).innerHTML = result.responseText;
                        link.removeAttr("hidden");
                        wait.setAttribute("hidden", "true");
                        if (document.getElementById("hideButton")) {
                            link.remove();
                        }
                    }
                })
            })
        }
    })();



    //prototype video create trick
    (function(){
        $(".add-another-collection-widget").click(function (e) {
            let list = $($(this).attr("data-list-selector"));
            let counter = list.data("widget-counter") || list.children().length;

            let newWidget = list.attr("data-prototype");
            newWidget = newWidget.replace(/__name__/g, counter);
            counter++;

            list.data("widget-counter", counter);

            let newElem = $(list.attr("data-widget-tags")).html(newWidget);
            newElem.appendTo(list);
        });

    })();

    (function(){
        $(".delete-another-collection-widget").click(function (e) {
            $("li.add_widget:last").remove() ;

        });
    })();



});

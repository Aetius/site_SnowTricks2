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

/*
(function(){

})
*/
let confirmClick = document.querySelectorAll(".trick-delete-modal");
let doc = (document.querySelector("#articleId form"));
let token = document.getElementById("token");

document.addEventListener("click", function (e) {

    if (e.target.attributes.class.value ==="material-icons test" ){
        let url = (e.target.parentNode.getAttribute("data-url"));
        let idTrick = e.target.parentNode.previousElementSibling.getAttribute('value');
        doc.setAttribute("action", url);
        token.setAttribute("value", idTrick);

    }
})
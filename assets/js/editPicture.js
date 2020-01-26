


//Ajax request in edit photo
//function MDN to run this request with all browser
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

    let links = document.querySelectorAll('.thumbnails');
    for (let i = 0; i<links.length; i++) {

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
})();

/*
//change action in images modal
(function () {
    ;console.log(images)
    document.addEventListener("click", function (e) {
        let searchValueE = e.target.attributes.class.value;
        if (searchValueE.split(" ").find(element => element === "thumbnails-modal")){
            let url = (e.target.parentNode.getAttribute("data-url")); console.log(images)

        }
    })

})();*/


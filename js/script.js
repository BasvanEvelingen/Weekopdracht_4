window.addEventListener("load",init);
function init() {
    //loadJSON("themes.json",null,null);
    tinymce.init({
        selector: 'textarea',
        height: 100,
        branding: false,
        menubar: false,
        font_formats: 'Arial=arial,helvetica,sans-serif;Bitter=Bitter;Courier New=courier new,courier,monospace;Lato=Lato;Roboto=Roboto;',
        plugins: [
          'advlist autolink lists charmap anchor textcolor',
          'searchreplace visualblocks code fullscreen',
          'insertdatetime table contextmenu paste code'
        ],
        toolbar: 'undo redo | fontselect | formatselect | bold italic backcolor  | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat',
        content_css: [
            '//fonts.googleapis.com/css?family=Roboto:300,400,500,700',
            '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
            '//fonts.googleapis.com/css?family=Bitter:400,400i,700',
            '//www.tinymce.com/css/codepen.min.css']
      });
}
// Json laden en parsen
function loadJSON(path, success, error) {
    var request = new XMLHttpRequest();
    request.onreadystatechange = function()
    {
        if (request.readyState === XMLHttpRequest.DONE) {
            if (request.status === 200) {
                if (success)
                    success(JSON.parse(request.responseText));
                    console.log("succes");
            } else {
                if (error)
                    error(request);
            }
        }
    };
    request.open("GET", path, true);
    request.send();
}
/**
 *  CSS bestand vervangen
 *  @param cssFile: pad naar nieuw css bestand
 *  @param cssLinkIndex: index in de head van de te vervangen link
 * */
function changeCSS(cssFile, cssLinkIndex) {
  var oldlink = document.getElementsByTagName("link").item(cssLinkIndex);
  var newlink = document.createElement("link");
  newlink.setAttribute("rel", "stylesheet");
  newlink.setAttribute("type", "text/css");
  newlink.setAttribute("href", cssFile);
  document.getElementsByTagName("head").item(0).replaceChild(newlink, oldlink);
}

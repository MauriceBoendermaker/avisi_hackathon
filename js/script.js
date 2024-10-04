var sPath = window.location.pathname;
var sPage = sPath.substring(sPath.lastIndexOf('/') + 1);

switch(sPage) {
    case "":
        document.getElementById("nav-index").classList.add('active');
        break;
    case "gasten":
        document.getElementById("nav-gasten").classList.add('active');
        break;
    case "herbergen":
        document.getElementById("nav-herbergen").classList.add('active');
        break;
    case "beheerders":
        document.getElementById("nav-beheerders").classList.add('active');
        break;
    case "criteria":
        document.getElementById("nav-criteria").classList.add('active');
        break;
    case "statussen":
        document.getElementById("nav-statussen").classList.add('active');
        break;
}
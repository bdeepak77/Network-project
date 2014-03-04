function clickHandler(e) {
var newURL = "http://localhost/register/filelist.php";
  chrome.tabs.create({ url: newURL });
}

function clickHandler2(e) {
var newURL = "http://localhost/register/secretfile.php";
  chrome.tabs.create({ url: newURL });
}


// Add event listeners once the DOM has fully loaded by listening for the
// `DOMContentLoaded` event on the document, and adding your listeners to
// specific elements when it triggers.
document.addEventListener('DOMContentLoaded', function () {
  document.querySelector('#button1').addEventListener('click', clickHandler);
  document.querySelector('#button2').addEventListener('click', clickHandler2);
});
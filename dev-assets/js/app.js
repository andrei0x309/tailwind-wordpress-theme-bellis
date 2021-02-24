import { listen as quicklinkListen } from "quicklink"
import { LuminousGallery } from 'luminous-lightbox';

/*window.addEventListener('load', () =>{
   
    quicklinkListen();
    initLightbox();
    document.getElementById('menu-search-btn').addEventListener('click', showSearchModal);
});
*/
let searchModalOpen = false;


const initLightbox = () => {
    new LuminousGallery(document.querySelectorAll("a.lightbox"), 
    {arrowNavigation: true },
    {injectBaseStyles: true },
    );
};

const showSearchModal = () => {
  if(!searchModalOpen) {
  searchModalOpen = true;
  const searchModal=document.createElement('div');
  searchModal.id="full-search-modal";
  searchModal.innerHTML =  `
<button id="search-close-btn">X</button> 
<form id="menu-search-from" role="search" method="get" class="search-form" action="${window.location.origin}">
	<label style="display:none;" for="menu-search-input">Search</label>
        <input placeholder="Search" type="search" id="menu-search-input" class="search-field" name="s" autocomplete="off" />
        <input style="display:none;" type="submit" class="search-submit" value="search" />
</form>
  </div>`;
  document.getElementsByTagName("BODY")[0].append(searchModal);
  //searchModal.classList.add('search-modal-open');
 
  document.getElementById('search-close-btn').addEventListener('click', closeSearchModal);
  document.getElementById('menu-search-from').addEventListener('submit', searchAddSpinner);
  }
};

const searchAddSpinner = (e) => {
    addSimpleSpinner(e.target, false);
};

const closeSearchModal = () => {
    if(searchModalOpen) {
    const searchModal = document.getElementById('full-search-modal');
    document.getElementById('search-close-btn').removeEventListener('click', closeSearchModal);
    document.getElementById('menu-search-from').removeEventListener('menu-search-from').addEventListener('submit', searchAddSpinner);
    searchModalOpen = false;
    searchModal.style.animation = 'search-modal-close 0.4s linear forwards';
    searchModal.addEventListener('animationend', () => {
  searchModal.parentElement.removeChild(searchModal);
});
    }
};



const addSimpleSpinner = (element, prepend = true) => {
    const spinner = document.createElement('div');
    spinner.classList.add('loadingspinner');
    if(prepend) element.prepend(spinner);
    else element.appendChild(spinner);
    return spinner;
};

const delSimpleSpinner = (spinner) => {
    spinner.parentElement.removeChild(spinner);
};

const delAlertBox = ( ) => {
    const oldAlertBox = document.getElementById('a309-alert-box');
    if(oldAlertBox){
         oldAlertBox.parentElement.removeChild(oldAlertBox);
     }
};

const alertBox = ( alertClass='error', alertMsg = '' ) => {
    
    delAlertBox();
    
     switch (alertClass) {
        case 'error':
            alertClass = 'alert-error';
            break;
        case 'info':
           alertClass = 'alert-info';
            break;
        case 'success':
            alertClass = 'alert-success';
            break;
        default:
            alertClass = 'alert-error';
            break;
    }
     
     const alertBox = document.createElement('div');
     alertBox.id = 'a309-alert-box';
     alertBox.classList.add('alert');
     alertBox.classList.add(alertClass);
     alertBox.innerHTML = alertMsg;
     return alertBox;
 };
 
const goodReadsUpdate = async () => {
   const grFurl = `${window.location.origin}/wp-json/a309/v1/gr-widget`;
   const response = await fetch(grFurl,{ mode: 'cors',  headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    } });
   if(response.ok){
       const widgetId = 'gr_custom_widget_1613506906';
       const respTxt = await response.text();
        
       let widgetHTML = respTxt.match(/=([^]+)widget_div =/gm)[0].replace('  var widget_div =','');
       widgetHTML = widgetHTML.substring(3).slice(0, -2).trim();
       widgetHTML = widgetHTML.replace(/\\\//gm, '/').replace(/\\n/gm, '');
       widgetHTML = widgetHTML.replace(/\\"/gm, '"').replace(/<center>.*?<\/center>/gm, '').replace(/border="0"/gm, '').replace(/\\'/gm,`'`);
        
       document.getElementById(widgetId).innerHTML = widgetHTML;
   } 
};


document.addEventListener("DOMContentLoaded", function() {
quicklinkListen();

window.A309TH.delAlertBox = delAlertBox;
window.A309TH.alertBox = alertBox;
window.A309TH.addSimpleSpinner = addSimpleSpinner;
window.A309TH.delSimpleSpinner = delSimpleSpinner;
window.A309TH.quicklinkListen = quicklinkListen;
window.A309TH.initLightbox = initLightbox;

    window.A309TH.initLightbox();
    goodReadsUpdate();
    document.getElementById('menu-search-btn').addEventListener('click', showSearchModal);

 });



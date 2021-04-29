import { listen as quicklinkListen } from "quicklink"
import { LuminousGallery } from 'luminous-lightbox';


let searchModalOpen = false;
let loaderFired = false;
let isSwitchingTheme = false;

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
  searchModal.classList.add('full-search-modal');
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
    document.getElementById('menu-search-from').removeEventListener('menu-search-from', searchAddSpinner);
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
 
const showNavigateToPage = _ => {
   if(!loaderFired){
   const body = document.getElementsByTagName('body')[0];
   const barLoader = document.createElement('div');
   barLoader.classList.add('load-line');
   body.prepend(barLoader);
   const main = document.getElementById('main');
   if(main) main.style.opacity = 0.7;
    const spinnerTag = document.createElement('div');
    spinnerTag.classList.add('nav-spinner', 'spinner__1');
    body.prepend(spinnerTag);
    loaderFired = true;
    }
};


const themeSwitch = () => {

    const darkIcon = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
    </svg>`;
    
    const lightIcon = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
    </svg>`;
                        
    
        if(!isSwitchingTheme){
            const isDarkmode = document.documentElement.classList.contains("dark");
            isSwitchingTheme = true;
            const svg = document.getElementById('svg-theme-switch');
    let fetchUrl ;
    if (isDarkmode) {
        svg.classList.add('-translate-x-2');
        svg.classList.remove('translate-x-full');
        document.documentElement.classList.remove('dark');
        fetchUrl = `${window.location.origin}/wp-json/theme/v1/theme-switch/light/`;
        setTimeout(() => {
          svg.innerHTML = darkIcon;
        }, 200);
      } else {
        svg.classList.remove('-translate-x-2');
        svg.classList.add('translate-x-full');
        document.documentElement.classList.add('dark');
        fetchUrl = `${window.location.origin}/wp-json/theme/v1/theme-switch/dark/`;
        setTimeout(() => {
          svg.innerHTML = lightIcon;
        }, 200);
      }
        fetch(fetchUrl, {
                headers: {
                    'Content-Type': 'application/json'
                }
            });
       isSwitchingTheme = false;     
            
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
    document.getElementById('menu-search-btn').addEventListener('click', showSearchModal);
    document.getElementById('theme-switch').addEventListener('click', themeSwitch);
    
    window.addEventListener("beforeunload", showNavigateToPage);

 });



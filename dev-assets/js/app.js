import { listen as quicklinkListen } from "quicklink"
import { Luminous } from 'luminous-lightbox';

window.addEventListener('load', () =>{
  
    
    
    quicklinkListen();
    
    new Luminous(document.querySelector("a"));
 
});

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


document.addEventListener("DOMContentLoaded", function() {
window.A309TH.delAlertBox = delAlertBox;
window.A309TH.alertBox = alertBox;
window.A309TH.addSimpleSpinner = addSimpleSpinner;
window.A309TH.delSimpleSpinner = delSimpleSpinner;
 });



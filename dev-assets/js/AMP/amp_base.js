(async function ampBaseScript() {

const addSiSpinner = function(element, prepend = false) {
    const spinner = document.createElement('div');
    spinner.classList.add('loadingspinner');
    if(prepend){
        element.insertBefore(element, element.firstElementChild);
    }else{
        element.appendChild(spinner);
    }
    return spinner;
};

const delSimpleSpinner = (spinner) => {
    spinner.parentNode.removeChild(spinner);
};

const delAlertBox = ( ) => {
    const oldAlertBox = document.getElementById('a309-alert-box');
    if(oldAlertBox){
         oldAlertBox.parentNode.removeChild(oldAlertBox);
     }
};

const alertBox = ( alertClass='error', alertMsg = '', delAlertBox = '') => {
    
    if(delAlertBox) delAlertBox();
    
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
 
let lib = {};
lib.addSiSpinner = addSiSpinner.toString();
lib.delSiSpiner = delSimpleSpinner.toString();
lib.delAlertBox = delAlertBox.toString();
lib.alertBox = alertBox.toString();

await AMP.setState( { lib } );
console.log('base');
//console.log(this.exportFunction('test', function () { console.log('test'); } )  );
//console.log(this);
//console.log(AMP.script);
//console.log(script);

/*
 AMP.setState({'A309TH':{
        addSiSpinner,
        delSiSpiner
        }}); */

//AMP.A309TH.addSiSpinner = addSiSpinner;
//AMP.A309TH.delSiSpiner = addSiSpinner;

})();
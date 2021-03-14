 
 
 
 const addSiSpinner = function(element) {
    const spinner = document.createElement('div');
    spinner.classList.add('loadingspinner');
    element.appendChild(spinner);
    return spinner;
};

const delSiSpiner = function(spinner){
    spinner.parentNode.removeChild(spinner);
};
 
let lib = {};
lib.addSiSpinner = addSiSpinner.toString();
lib.delSiSpiner = 'ss';
AMP.setState( { lib } );
/*
 AMP.setState({'A309TH':{
        addSiSpinner,
        delSiSpiner
        }}); */

//AMP.A309TH.addSiSpinner = addSiSpinner;
//AMP.A309TH.delSiSpiner = addSiSpinner;


const addSimpleSpinner = (element) => {
    const spinner = document.createElement('div');
    spinner.classList.add('loadingspinner');
    element.prepend(spinner);
    return spinner;
};

const delSimpleSpinner = (spinner) => {
    spinner.parentElement.removeChild(spinner);
};


document.addEventListener("DOMContentLoaded", function() {
 
 
window.A309TH.addSimpleSpinner = addSimpleSpinner;
window.A309TH.delSimpleSpinner = delSimpleSpinner;
 });



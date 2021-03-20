
(async function ampSearch() {
    function waitForBase(timeout) {
    let start = Date.now();
    return new Promise(wait); // set the promise object within the ensureFooIsSet object
 
    async function wait(resolve, reject) {
        const obj1 = await AMP.getState('A309TH');
        const obj2 = await AMP.getState('lib');
        if (obj1 && obj2)
            resolve(Object.assign({}, JSON.parse(obj1), JSON.parse(obj2)));
        else if (timeout && (Date.now() - start) >= timeout)
            reject(new Error("timeout"));
        else
            setTimeout(wait.bind(this, resolve, reject), 70);
    }
}

let A309TH = await  waitForBase(5000);


const addSiSpinner = new Function("return " + A309TH.addSiSpinner)();    

let searchModalOpen = false;

const showSearchModal = () => {
  if(!searchModalOpen) {
  searchModalOpen = true;
  const searchModal=document.createElement('div');
  searchModal.id="full-search-modal";
  searchModal.classList.add('full-search-modal');
  searchModal.innerHTML =  `
<button id="search-close-btn">X</button> 
<form id="menu-search-from" role="search" method="get" class="search-form" action="${window.location.origin}">
	<label class="hidden" for="menu-search-input" hidden>Search</label>
        <input placeholder="Search" type="search" id="menu-search-input" class="search-field" name="s" autocomplete="off" />
        <input type="submit" class="search-submit hidden" value="search"/>
</form>
  </div>`;
  document.getElementsByTagName("BODY")[0].appendChild(searchModal);
 
  document.getElementById('search-close-btn').addEventListener('click', closeSearchModal);
  document.getElementById('menu-search-from').addEventListener('submit', searchAddSpinner);
  }
};

const searchAddSpinner = (e) => {
    addSiSpinner(e.target);
};

const closeSearchModal = () => {
    if(searchModalOpen) {
    const searchModal = document.getElementById('full-search-modal');
        document.getElementById('search-close-btn').removeEventListener('click', closeSearchModal);
    document.getElementById('menu-search-from').removeEventListener('menu-search-from', searchAddSpinner);
    searchModalOpen = false;
    searchModal.style.animation = 'search-modal-close 0.4s linear forwards';
    searchModal.addEventListener('animationend', () => {
    searchModal.parentNode.removeChild(searchModal);
});
    }
};

 document.getElementById('menu-search-btn').addEventListener('click', showSearchModal);

})();

 
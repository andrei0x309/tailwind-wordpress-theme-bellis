 
 // Document Ready
 document.addEventListener("DOMContentLoaded", function() {
 // Add Open Post Event
 document.querySelectorAll('.read-more').forEach(
         (node) => {
             node.addEventListener('click', openPost);
         });
 
 // Add Load More Events
 
window.articleNodes = null;
window.mainTagEL = document.querySelector('main'); 
 });
 

 window.onpopstate =  () => {
     backToPosts();
 };
 
 const addSpinner = (articleStart) => {
    
    const spinnerTag = document.createElement('div');
    spinnerTag.id = 'loadingSpinner';
    spinnerTag.style = 'position: absolute; width:100%; height:100%;  left: 0; top: 0;  background: rgb(183 183 183 / 72%);';
    
    spinnerTag.innerHTML = `
    <div class='sk-folding-cube' style="position: absolute; left:45%; top:${articleStart+80}px"%>
    <div class='sk-cube sk-cube-1'></div>
    <div class='sk-cube sk-cube-2'></div>
    <div class='sk-cube sk-cube-4'></div>
    <div class='sk-cube sk-cube-3'></div>
    </div>`;
    
    window.mainTagEL.appendChild(spinnerTag);
    return spinnerTag;
};
 
 const addPost = (postData) => {
     let postEl = null;
     
     if(postData.article){
    window.mainTagEL.insertAdjacentHTML('beforeend', postData.article);   
    postEl = document.getElementById(`post-${postData.post_id}`);
     }
         
    return postEl;
 
 };
 
 const removeSpinner = (spinnerTag) => {
     window.mainTagEL.removeChild(spinnerTag);
 };
 
 
 const openPost = async (e) => {
     
     e.preventDefault();
     // Var needed
     const article = e.target.closest('article');
     
     
     const postId = article.dataset.id;
     const postSlug = article.dataset.slug;
     const postTitle = article.dataset.title;
     
     // show Load Spinner
     const articleTopOffset = article.getBoundingClientRect().top - document.body.getBoundingClientRect().top;
     const spinnerTag = addSpinner(articleTopOffset);
     
     // fetch post 
     const fetchUrl = `${window.location.origin}/wp-json/a309/v1/get-post/${postId}`;
     
    const response = await fetch(fetchUrl, {
    headers: {
      'Content-Type': 'application/json'
    }
  });
    const data = await response.json(); // parses JSON response into native JavaScript objects
     
     // Remove posts
     
      window.articleNodes = window.mainTagEL.querySelectorAll('article');
        for(let articleNode of window.articleNodes) {
             window.mainTagEL.removeChild(articleNode);
        }


     console.log(data);
     
     // change URL
     
      const state = { 'post_id': postId, 'post_slug': postSlug, 'post_title': postTitle  };

      history.pushState(state, postTitle, postSlug);
     
     
     // Change Title and meta
     document.title = data.title;
     console.log('here');
     
     // Add Post to DOM
     addPost(data);
     window.scroll(0,0);
     
     // Execute Plugin
     SyntaxHighlighter.highlight();
     
     //Remove Spinner
       
    removeSpinner(spinnerTag);
     
     return false;
 };
 
 const backToPosts  = () =>{
     
     if(window.articleNodes){
        
        const curArt = window.mainTagEL.querySelector('article');
        console.log(curArt);
        const spinnerEl = addSpinner(curArt.getBoundingClientRect().top - document.body.getBoundingClientRect().top);
         
        window.mainTagEL.removeChild(curArt);
        const comments = document.getElementById('comments');
         
        for(let articleNode of window.articleNodes) {
             window.mainTagEL.appendChild(articleNode);
        }
        
        if(comments){
            window.mainTagEL.removeChild(comments);
        }
         
         window.articleNodes = null;
         
         removeSpinner(spinnerEl);
     }
       
 };
 
 
 const loadMorePosts = () =>{
       // Var needed  
       const numberOfPosts = 'x';  
     
       // show Load Spinner
       
       // fetch post 
     return false;
 };
 
 
 
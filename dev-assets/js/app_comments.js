
 /* global A309TH */

// Document Ready
 document.addEventListener("DOMContentLoaded", function() {
 
 // Add Load More Events

window.A309TH.commentsEl = document.getElementById('comments');
window.A309TH.commentFormEl = document.getElementById('commentform');

window.A309TH.commentsList = null;
window.A309TH.commentsShowMoreBtn = null;

window.A309TH.showCommentsBtn =  document.getElementById('comments-show-btn');
window.A309TH.postId = document.querySelector('article').dataset.id;
window.A309TH.page = 1;
// Add event on comments show
window.A309TH.showCommentsBtn.addEventListener('click', A309TH.showCommentsFn );
window.A309TH.commentFormEl.addEventListener('submit', sumbitComment );
 });


window.A309TH.commentsRemoveShowMoreBtn = () => {
    if(window.A309TH.commentsShowMoreBtn){
        window.A309TH.commentsShowMoreBtn.removeEventListener('click', window.A309TH.showMoreCommentsFn);
        window.A309TH.commentsEl.removeChild(window.A309TH.commentsShowMoreBtn);
        window.A309TH.commentsShowMoreBt = null;
    }
    
}

window.A309TH.commentsAddShowMoreBtn = () => {
    const showMoreBtn = document.createElement('button');
    showMoreBtn.id = 'comments-show-more-btn';
    showMoreBtn.innerHTML = 'Show More Comments';
    window.A309TH.commentsEl.appendChild(showMoreBtn);
    showMoreBtn.addEventListener('click', window.A309TH.showMoreCommentsFn)
    
    return showMoreBtn;
};


window.A309TH.fetchComments = async () => {
    
    console.log(`page ${window.A309TH.page }`);
    // fetch Comments 
     const fetchUrl = `${window.location.origin}/wp-json/a309/v1/get-comments/post/${window.A309TH.postId}/page/${window.A309TH.page}`;
     
    const response = await fetch(fetchUrl, {
    headers: {
      'Content-Type': 'application/json'
    }
  });
    const data = await response.json(); // parses JSON response into native JavaScript objects
    
    window.A309TH.page += 1;
    return data;
};


window.A309TH.showCommentsFn = async () => {
    window.A309TH.showCommentsBtn.innerHTML = `
     Loading
     <div class="loadingspinner"></div>
   `;
     window.A309TH.showCommentsBtn.disabled = true;
     
    const data = await window.A309TH.fetchComments();      
    
     
    window.A309TH.commentsList = document.createElement('ol');
    window.A309TH.commentsList.classList.add('comment-list');
    window.A309TH.commentsList.insertAdjacentHTML('beforeend', data.comments);
    
    window.A309TH.commentsEl.appendChild(window.A309TH.commentsList);
    
    console.log(window.A309TH.commentsEl.dataset);
    console.log(window.A309TH.commentsEl.dataset.noComments - 5);
    
    if(window.A309TH.commentsEl.dataset.noComments - 5 > 0){
        window.A309TH.commentsShowMoreBtn = window.A309TH.commentsAddShowMoreBtn();
    }
    
    
    window.A309TH.commentsEl.removeChild(window.A309TH.showCommentsBtn);
    
};

window.A309TH.showMoreCommentsFn = async () => {
  
  window.A309TH.commentsRemoveShowMoreBtn();
  const spinner = document.createElement('div');
  spinner.classList.add('loadingspinner');
  window.A309TH.commentsEl.appendChild(spinner);
  
 
  const data = await window.A309TH.fetchComments();      
  window.A309TH.commentsList.insertAdjacentHTML('beforeend', data.comments); 
  
      if(window.A309TH.commentsEl.dataset.noComments - (5 * (window.A309TH.page) ) > 0){
        window.A309TH.commentsShowMoreBtn = window.A309TH.commentsAddShowMoreBtn();
     }
     
  window.A309TH.commentsEl.removeChild(spinner);
};


const sumbitComment  =  async (e) => {
     
        e.preventDefault();
        window.A309TH.delAlertBox();
        const respondEl = document.getElementById('respond');
        const spinner = window.A309TH.addSimpleSpinner(respondEl, false);
        //serialize and store form data in a variable
        const commentform = window.A309TH.commentFormEl;
 
        const formdata = new URLSearchParams(new FormData(commentform));
        
 
        
        //Add a status message
        //statusdiv.html('<p>Processing...</p>');
        //Extract action URL from commentform
        var formurl=commentform.getAttribute('action');
        //Post Form with data
        
            const response = await fetch(formurl, {
    method: 'POST', // *GET, POST, PUT, DELETE, etc.
    cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
    credentials: 'same-origin', // include, *same-origin, omit
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: formdata // body data type must match "Content-Type" header
  });
    let alert = null;
    if(response.ok){
        const data = await response.json();
        
        if(data.error){
             alert =  window.A309TH.alertBox('error', `&#x26A0; ${data.msg}` );
        }else{
           alert = window.A309TH.alertBox('success', 'Comment was posted');
           
        }
       
    }else{
        alert =  window.A309TH.alertBox('error', '&#x26A0; HTTP fetch error, API down!');
    }
     
     respondEl.appendChild(alert);
     window.A309TH.delSimpleSpinner(spinner);
         
 };
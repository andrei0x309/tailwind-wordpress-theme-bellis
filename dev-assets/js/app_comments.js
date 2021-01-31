
 /* global A309JSPHP */

// Document Ready
 document.addEventListener("DOMContentLoaded", function() {
 
 // Add Load More Events

window.A309JSPHP.commentsEl = document.getElementById('comments');
window.A309JSPHP.showCommentsBtn =  document.getElementById('comments-show-btn');
// Add ecent on comments show
window.A309JSPHP.showCommentsBtn.addEventListener('click', A309JSPHP.showCommentsFn );
window.A309JSPHP.postId = document.querySelector('article').dataset.id;
window.A309JSPHP.page = 0;
 });



window.A309JSPHP.showCommentsFn = async () => {
    window.A309JSPHP.showCommentsBtn.innerHTML = `
     Loading
     <img src='${window.A309JSPHP['theme_URI']}/images/svgLoader.svg' alt="Loading" loading="lazy"/>
   `;
     window.A309JSPHP.showCommentsBtn.disabled = true;
     
          
     // fetch Comments 
     const fetchUrl = `${window.location.origin}/wp-json/a309/v1/get-comments/post/${window.A309JSPHP.postId}/page/${window.A309JSPHP.page}`;
     
    const response = await fetch(fetchUrl, {
    headers: {
      'Content-Type': 'application/json'
    }
  });
    const data = await response.json(); // parses JSON response into native JavaScript objects
    
    if(data.comments){
    const commentList = document.createElement('ol');
    commentList.classList.add('comment-list');
    commentList.insertAdjacentHTML('beforeend', data.comments);
    window.A309JSPHP.commentsEl.appendChild(commentList);
    
    }else{
    const noMoreComments = document.createElement('p');
    noMoreComments.innerHTML = 'No more comments';
    }
    
    window.A309JSPHP.commentsEl.removeChild(window.A309JSPHP.showCommentsBtn);
    
    
    
     
};


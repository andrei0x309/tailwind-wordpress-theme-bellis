
(async function ampComments() {
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
            setTimeout(wait.bind(this, resolve, reject), 75);
    }
}

let A309TH = await waitForBase(5000);


const addSiSpinner = new Function("return " + A309TH.addSiSpinner)();    
const delSiSpinner = new Function("return " + A309TH.delSiSpiner)();  
const delAlertBox = new Function("return " + A309TH.delAlertBox)();  
const alertBox = new Function("return " + A309TH.alertBox)();  



    const actionUrl = document.getElementById('commentform').getAttribute('action-xhr');
    let noComments = document.getElementById('comments').getAttribute('data-no-comments');
    const postId = document.getElementById('comments').getAttribute('data-post-id');
    
    const commentsEl = document.getElementById('comments');
    const commentFromEl = document.getElementById('commentform');

    let commentsList = null;
    let commentsShowMoreBtn = null;
    let storeRespEl = null;

    const showCommentsBtn = document.getElementById('comments-show-btn');
    const postCommentBtn = document.getElementById('submit-amp');
    
    let page = null;


const HTMLtoEL = (html) => {
    const t = document.createElement('div');
    //const sanHtml = html.trim().replace(/\t/gm, '').replace(/\\"/gm, '"');
    console.log(html);
    t.innerHTML = html;
    return t.children;
};

const fetchCommentsNo =  async () => {
    
    const fetchUrl = `${window.location.origin}/wp-json/a309/v1/get-comments-no/post/${postId}`;

    const response = await fetch(fetchUrl, {
        headers: {
            'Content-Type': 'application/json'
        }
    });
    const data = await response.json();
    page = Math.floor(data.commentNumber / 5);
};


const fetchComments = async () => {

    // fetch Comments 
    const fetchUrl = `${window.location.origin}/wp-json/a309/v1/get-comments/post/${postId}/page/${page}/amp/1`;

    const response = await fetch(fetchUrl, {
        headers: {
            'Content-Type': 'application/json',
        }
    });
    const data = await response.json(); // parses JSON response into native JavaScript objects

    page -= 1;
    return data;
};


const showCommentsFn = async () => {
    showCommentsBtn.innerHTML = `
     Loading
     <div class="loadingspinner"></div>
   `;
    showCommentsBtn.disabled = true;

    await fetchCommentsNo();
 
    const data = await fetchComments();
 
    //console.log(data);
    
    commentsList = document.createElement('ol');
    commentsList.id = 'comment-list';
    commentsList.classList.add('comment-list');
    const repFunc = (...args) =>{
        return `<amp-img
             alt
             src="${args[1]}"
             width="60"
             height="60"
             layout="fixed"
           >
           </amp-img>`;
    };
    
    
    let comments = HTMLtoEL(data.comments
            .replace(/<img.*?src=['"]{1}(.*?)['"]{1}.*?>/gms , repFunc )
            .trim()
            .replace(/<!--.*?-->/gms, '')
            .replace(/\t/gm ,'')
            .replace(/\n/gm, ''));
    
    console.log(comments);
    
    for( const comment of comments){
        commentsList.appendChild(comment);
    }       
    
    //commentsList.appendChild(comments));
    
    addReplyEvent(commentsList);
    commentsEl.appendChild(commentsList);

    commentsEl.removeChild(showCommentsBtn);
    
    if (page) {
        //await showMoreCommentsFn();
    }
 
};


const sumbitComment = async (e) => {

    delAlertBox();
    const respondEl = document.getElementById('amp-respond');
    const spinner =   addSiSpinner(respondEl);
    //serialize and store form data in a variable
 
   console.log(actionUrl);
   

   
   const bodyData = { comment:document.getElementById('comment')? document.getElementById('comment').value: '',
                      author:document.getElementById('author')? document.getElementById('author').value: '',
                      email:document.getElementById('email')? document.getElementById('email').value: '',
                      comment_post_ID:document.getElementById('comment_post_ID')? document.getElementById('comment_post_ID').value: '',
                      comment_parent:document.getElementById('comment_parent')? document.getElementById('comment_parent').value: '',
                      'wp-comment-cookies-consent': document.getElementById('wp-comment-cookies-consent')?document.getElementById('wp-comment-cookies-consent').value: '',
                      akismet_comment_nonce: document.getElementById('akismet_comment_nonce')?document.getElementById('akismet_comment_nonce').value:'',
                      'ak_js': document.getElementById('ak_js')?document.getElementById('ak_js').value:'',
                      'ak_hp_textarea': document.getElementById('ak_hp_textarea')?document.getElementById('ak_hp_textarea').value:'',
                      'redirect_to': document.getElementById('redirect_to')?document.getElementById('redirect_to').value:''
    };
   
   const searchParams = Object.keys(bodyData).map((key) => {
  return encodeURIComponent(key) + '=' + encodeURIComponent(bodyData[key]);
           }).join('&');
   
    const response = await fetch(actionUrl, {
        method: 'POST', // *GET, POST, PUT, DELETE, etc.
        cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
        credentials: 'same-origin', // include, *same-origin, omit
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: searchParams // body data type must match "Content-Type" header
    });
    let alert = null;
    if (response.ok) {
        const data = await response.json();

        if (data.error) {
            alert = alertBox('error', `&#x26A0; ${data.msg}`, delAlertBox);
            respondEl.appendChild(alert);
        } else {
            const commentList = document.getElementById('comment-list');
            if (commentList) {
                //addCommentToDOM(data.comment);
                alert = alertBox('success', 'Comment was posted', delAlertBox);
                respondEl.appendChild(alert);
            } else {
                alert = alertBox('success', 'Comment was posted', delAlertBox);
                respondEl.appendChild(alert);
            }
        }

    } else {
        alert = alertBox('error', '&#x26A0; HTTP fetch error, API down!', delAlertBox);
        respondEl.appendChild(alert);
    }
 
    delSiSpinner(spinner);

};

const replyMoveForm = (e) => {
    const respondEl = document.getElementById('amp-respond');
    storeRespEl = respondEl;
    const replyEl = document.getElementById('amp-respond').cloneNode(true);
    /*respondEl.parentNode.removeChild(respondEl);
    const commentId = e.target.getAttribute('data-commentid');
    const replyName = e.target.querySelector('.fn')?e.target.querySelector('.fn').textContent:'';
    const form = replyEl.querySelector('form');
    form.removeAttribute('amp-novalidate');
    form.removeAttribute('amp-novalidate');
    form.classList.remove('comment-form i-amphtml-form');
    const replyTitle = replyEl.querySelector('#reply-title');
    replyTitle.removeAttribute('on');
    replyTitle.removeAttribute('data-amp-bind-hidden');
    replyTitle.removeAttribute('hidden');
    replyTitle.textContent = `Reply to ${replyName}`;
    const cancelLink = document.createElement('a');
    cancelLink.id = 'cancel-comment-reply-link';
    cancelLink.setAttribute('rel', 'nofollow');
    cancelLink.setAttribute('href', '#respond ');
    replyEl.insertBefore(cancelLink, form);
    console.log(replyName);
    console.log(replyName);
    
    cancelLink.addEventListener('click', replyFormCancel);
    document.getElementById(`comment-${commentId}`).appendChild(replyEl); */

};

const replyFormCancel = () => {
      const respondEl = document.getElementById('amp-respond');
      const cancelLink = document.getElementById('cancel-comment-reply-link');
      cancelLink.removeEventListener('click', replyFormCancel);
      respondEl.parentNode.removeChild(respondEl);
      commentsEl.insertBefore(storeRespEl, commentsEl.firstChild);
};

const addReplyEvent = (element) => {
    const rLinks = element.querySelectorAll('.comment-reply-link');
    for (const link of rLinks) {
        link.addEventListener('click', replyMoveForm);
    }  
};


postCommentBtn.addEventListener('click', sumbitComment);
showCommentsBtn.addEventListener('click', showCommentsFn);


//document.getElementById('commentform').setAttribute('action-xhr', '');

/*






const eventsOnComments = () => {

// Add event on comments show
    if (showCommentsBtn) showCommentsBtn.addEventListener('click', showCommentsFn);
    commentFromEl.addEventListener('submit', sumbitComment);
};



//Called from index
window.A309TH.eventsOnComments = () => {
    window.A309TH.commentsEl = document.getElementById('comments');
    window.A309TH.commentFormEl = document.getElementById('commentform');

    window.A309TH.commentsList = null;
    window.A309TH.commentsShowMoreBtn = null;

    window.A309TH.showCommentsBtn = document.getElementById('comments-show-btn');
    window.A309TH.postId = document.querySelector('article').dataset.id;
    window.A309TH.page = null;
// Add event on comments show
    if (window.A309TH.showCommentsBtn)
        window.A309TH.showCommentsBtn.addEventListener('click', A309TH.showCommentsFn);
    window.A309TH.commentFormEl.addEventListener('submit', sumbitComment);
};



const commentsAddShowMoreBtn = () => {
    const showMoreBtn = document.createElement('button');
    showMoreBtn.id = 'comments-show-more-btn';
    showMoreBtn.innerHTML = 'Show More Comments';
    window.A309TH.commentsEl.appendChild(showMoreBtn);
    showMoreBtn.addEventListener('click', showMoreCommentsFn)

    return showMoreBtn;
};
*/




})();

 




    
    
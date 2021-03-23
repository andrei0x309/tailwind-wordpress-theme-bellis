(async function ampComments() {
/*    function waitForLib(timeout) {
    let start = Date.now();
    return new Promise(wait); // set the promise object within the ensureFooIsSet object
    
    async function wait(resolve, reject) {
        const obj2 = await AMP.getState('lib');
        if (obj2)
            resolve(Object.assign({}, JSON.parse(obj2)));
        else if (timeout && (Date.now() - start) >= timeout)
            reject(new Error("timeout"));
        else
            setTimeout(wait.bind(this, resolve, reject), 75);
    }
}

let A309TH = await waitForLib(8000);


const addSiSpinner = new Function("return " + A309TH.addSiSpinner)();    
const delSiSpinner = new Function("return " + A309TH.delSiSpiner)();  
const delAlertBox = new Function("return " + A309TH.delAlertBox)();  
const alertBox = new Function("return " + A309TH.alertBox)();  

*/

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

const delSiSpinner = (spinner) => {
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
     alertBox.classList.add('fade-in');
     alertBox.innerHTML = alertMsg;
     return alertBox;
 };
 


    const actionUrl = document.getElementById('commentform').getAttribute('action-xhr');
    let noComments = document.getElementById('comments').getAttribute('data-no-comments');
    const postId = document.getElementById('comments').getAttribute('data-post-id');
    
    const commentsEl = document.getElementById('comments');
    let storeRespEl = null;
    let replyEl = null;
    let hiddenRepLink = null;
    const commentFromEl = document.getElementById('commentform');

    let commentsList = null;
    let commentsShowMoreBtn = null;
     

    const showCommentsBtn = document.getElementById('comments-show-btn');
    const postCommentBtn = document.getElementById('submit-amp');
    
    let page = null;


const HTMLtoEL = (html) => {
    const t = document.createElement('div');
    t.innerHTML = html;
    return t.children;
};

const DelShowMoreCBtn = () => {
        if (commentsShowMoreBtn) {
        commentsShowMoreBtn.parentNode.removeChild(commentsShowMoreBtn);
        commentsShowMoreBtn = null;
    }
};

const AddShowMoreCBtn = () => {
    const showMoreBtn = document.createElement('button');
    showMoreBtn.id = 'comments-show-more-btn';
    showMoreBtn.innerHTML = 'Show More Comments';
    showMoreBtn.addEventListener('click', showMoreCommentsFn);
    commentsEl.appendChild(showMoreBtn);
    return showMoreBtn;
};


const fetchCommentsNo =  async () => {
    
    const fetchUrl = `${window.location.origin}/wp-json/a309/v1/get-comments-no/post/${postId}`;

    const response = await fetch(fetchUrl, {
        headers: {
            'Content-Type': 'application/json'
        }
    });
    const data = await response.json();
    page = Math.ceil(data.commentNumber / 5);
};


const fetchComments = async () => {

    // fetch Comments 
    const fetchUrl = `${window.location.origin}/wp-json/a309/v1/get-comments/post/${postId}/page/${page}`;

    const response = await fetch(fetchUrl, {
        headers: {
            'Content-Type': 'application/json',
        }
    });
    const data = await response.json(); // parses JSON response into native JavaScript objects

    page -= 1;
    return data;
};

const repImgAMP = (...args) =>{
        const src = args[1].replace(/#038;/gms, '');
        return `<amp-img
             alt
             src="${src}"
             width="60"
             height="60"
             layout="fixed"
           >
           </amp-img>`;
    };

const AMPifyComments = (comments) => {
       return HTMLtoEL(comments
            .replace(/<img.*?src=['"]{1}(.*?)['"]{1}.*?>/gms , repImgAMP )
            .trim()
            .replace(/<!--.*?-->/gms, '')
            .replace(/\t/gm ,'')
            .replace(/\n/gm, ''));
};

const AddCommentsToList = (ListEl, comments) =>{
    for( const comment of comments){
        ListEl.appendChild(comment);
    }  
};

const showCommentsFn = async () => {
        
    showCommentsBtn.innerHTML = `
     Loading
     <div class="loadingspinner"></div>
   `;
    showCommentsBtn.disabled = true;

    await fetchCommentsNo();
 
    const data = await fetchComments();
 
    
    commentsList = document.createElement('ol');
    commentsList.id = 'comment-list';
    commentsList.classList.add('comment-list');
    
    const comments = AMPifyComments(data.comments);
    addReplyEvent(comments);
    
        AddCommentsToList(commentsList, comments);

    commentsEl.appendChild(commentsList);

    commentsEl.removeChild(showCommentsBtn);
    
    if (page) {
        await showMoreCommentsFn();
    }
 
};

const showMoreCommentsFn = async () => {

    DelShowMoreCBtn();
    const spinner = addSiSpinner(commentsEl);
 
    const data = await fetchComments();
    const commentsList = document.getElementById('comment-list');
    const comments = AMPifyComments(data.comments);
    
    
    addReplyEvent(comments);
    AddCommentsToList(commentsList, comments);
    

    if (page) {
        commentsShowMoreBtn = AddShowMoreCBtn();
    }

    delSiSpinner(spinner);
};


const sumbitComment = async (e) => {

    delAlertBox();
    const respondEl = document.getElementById('amp-respond');
    const spinner =   addSiSpinner(respondEl);
    const submitBtn = document.getElementById('submit-amp');
    submitBtn.disabled = true;
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
  return `${encodeURIComponent(key)}${ bodyData[key]?'='+encodeURIComponent(bodyData[key]):''}`;
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
            const approved = parseInt(data.comment.comment_approved) === 1;
            console.log(approved);
            const notApprovedText = 'Comment was posted but was not approved it will be live after approval.';
            const commentList = document.getElementById('comment-list');
            if (commentList) {
                const com = addCommentToDOM(data.comment);
                if(!approved){
                alert = alertBox('info',notApprovedText, delAlertBox);
                com.appendChild(alert);
                }
            } else {
                if(approved){
                  alert = alertBox('success', 'Comment was posted', delAlertBox);
                }else{
                  alert = alertBox('info', notApprovedText, delAlertBox);
                }
                
                
                respondEl.appendChild(alert);
            }
        }

    } else {
        alert = alertBox('error', '&#x26A0; HTTP fetch error, API down!', delAlertBox);
        respondEl.appendChild(alert);
    }
    
    submitBtn.disabled = false;
    delSiSpinner(spinner);

};


const addCommentToDOM = (comment) => {


    const date = new Date(comment.comment_date);
    const options = {year: 'numeric', month: 'long', day: 'numeric'};

    const newCom = `<li id="comment-${comment.comment_ID}" class="comment byuser comment-author-${comment.comment_author} even thread-even comment-added fade-in">
			<article id="div-comment-${comment.comment_ID}" class="comment-body">
				<footer class="comment-meta">
					<div class="comment-author vcard">
						 ${comment.comment_avatar}
                      <b class="fn"><a href="${comment.comment_author_url}" rel="external nofollow ugc" class="url">${comment.comment_author}</a></b> 
                       <span class="says">says:</span>					</div><!-- .comment-author -->

					<div class="comment-metadata">
						<a href="https://blog-dev.flashsoft.eu:8443/async-task-without-queue-or-e-loop-php/#comment-743"><time datetime="${comment.comment_date}">${date.toLocaleDateString('en-US', options)} at ${date.toLocaleTimeString('en-US')}</time></a>					</div><!-- .comment-metadata -->

									</footer><!-- .comment-meta -->

				<div class="comment-content">
					<p>${comment.comment_content}</p>
				</div><!-- .comment-content -->

				<div class="reply">
                    <a rel="nofollow" class="comment-reply-link" href="#comment-${comment.comment_ID}" data-commentid="${comment.comment_ID}" data-postid="${comment.comment_post_ID}" data-belowelement="div-comment-${comment.comment_ID}" data-respondelement="respond" data-replyto="Reply to andrei0x309" aria-label="Reply to ${comment.comment_author}">Reply</a></div>			</article><!-- .comment-body -->
		</li>`;
    const ampComment = AMPifyComments(newCom)[0];
    const commentList = document.getElementById('comment-list');
    if (Number(comment.comment_parent) === 0) {
        commentList.insertBefore(ampComment, commentList.firstChild);
    } else {
        const cancelLink = document.getElementById('cancel-comment-reply-link');
        console.log(cancelLink.click);
        cancelLink.click();
        let commentParent = document.getElementById(`comment-${comment.comment_parent}`);
        let children = commentParent.querySelector('ol.children');
        console.log(children);
        if (!children) {
            children = document.createElement('ol');
            children.classList.add('children');
            commentParent.appendChild(children);
        }
        children.insertBefore(ampComment, children.firstChild);
        
        return ampComment;
    }

};

const replyMoveForm = (e) => {
    e.preventDefault();
    if(hiddenRepLink) hiddenRepLink.removeAttribute('hidden');
    const commentId = e.target.getAttribute('data-commentid');
    const divComment = document.getElementById(`div-comment-${commentId}`);
    hiddenRepLink = divComment.querySelector('.comment-reply-link');
    hiddenRepLink.setAttribute('hidden');
    if(storeRespEl === null){    
    const oldCancelLink = document.getElementById('cancel-comment-reply-link');
    if(oldCancelLink) oldCancelLink.parentNode.removeChild(oldCancelLink);
    const respondEl = document.getElementById('amp-respond');
    const form = respondEl.querySelector('form');
    if(form){
        form.removeAttribute('amp-novalidate');
        form.removeAttribute('class');
    }
     replyEl = respondEl.cloneNode(true);
     storeRespEl = respondEl;
     const resp = replyEl.querySelector('#respond');
     const fSubmit = resp.querySelector('#form-submit');
     const cancelLink = document.createElement('a');
        cancelLink.id = 'cancel-comment-reply-link';
        cancelLink.setAttribute('rel', 'nofollow');
        cancelLink.setAttribute('href', '#comments');
        cancelLink.textContent = `Cancel Reply`;
        resp.insertBefore(cancelLink, fSubmit);
     cancelLink.addEventListener('click', replyFormCancel);
     replyEl.querySelector('#submit-amp').addEventListener('click', sumbitComment);
    }
    document.getElementById('amp-respond').parentNode.removeChild(storeRespEl);
    
    let replyName = divComment.querySelector('.fn');
    replyName = replyName? replyName.textContent: '';
    const replyTitle = replyEl.querySelector('#reply-title');
    replyTitle.textContent = `Reply to ${replyName}`;
    const resp = replyEl.querySelector('#respond');
    const inParent = resp.querySelector('#comment_parent');
    inParent.setAttribute('value', commentId);
    document.getElementById(`comment-${commentId}`).insertBefore(replyEl, divComment.nextSibling);

};

const replyFormCancel = () => {
      const respondEl = document.getElementById('amp-respond');
      respondEl.parentNode.removeChild(respondEl);
      if(hiddenRepLink) {
        hiddenRepLink.removeAttribute('hidden');  
        hiddenRepLink = null;
      }
      storeRespEl.querySelector('#submit-amp').addEventListener('click', sumbitComment);
      commentsEl.insertBefore(storeRespEl, commentsEl.firstChild);
};

const addReplyEvent = (comments) => {
    for(const comment of comments){
       const links = comment.querySelectorAll('.comment-reply-link');
       for(const link of links){
            //link.removeAttribute('href');
            link.addEventListener('click', replyMoveForm);
        }
    }
};


postCommentBtn.addEventListener('click', sumbitComment);
showCommentsBtn.addEventListener('click', showCommentsFn);
 
})();

 




    
    
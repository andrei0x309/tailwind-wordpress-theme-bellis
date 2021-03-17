

 

/* document.addEventListener("DOMContentLoaded", function () {


    window.A309TH.eventsOnComments();

}); ( */
    
    const actionUrl = document.getElementById('commentform').getAttribute('action-xhr');
    let noComments = document.getElementById('comments').getAttribute('data-no-comments');
    const postId = document.getElementById('comments').getAttribute('data-post-id');
    
    const commentsEl = document.getElementById('comments');
    const commentFromEl = document.getElementById('commentform');

    let commentsList = null;
    const commentsShowMoreBtn = null;

    const showCommentsBtn = document.getElementById('comments-show-btn');
    
    let page = null;


const HTMLtoEL = (html) => {
    const t = document.createElement('template');
    const sanHtml = html.trim().replace(/\t/gm, '').replace(/\\"/gm, '"');
    console.log(sanHtml);
    t.innerHTML = sanHtml;
    return t.firstElementChild;
};

const fetchCommentsNo =  async () => {
    
    const fetchUrl = `${window.location.origin}/wp-json/a309/v1/get-comments-no/post/${postId}`;

    const response = await fetch(fetchUrl, {
        headers: {
            'Content-Type': 'application/json'
        }
    });
    const data = await response.json();
    page = Math.ceil(data.commentNumber / 5) - 1;
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


const showCommentsFn = async () => {
    showCommentsBtn.innerHTML = `
     Loading
     <div class="loadingspinner"></div>
   `;
    showCommentsBtn.disabled = true;

    await fetchCommentsNo();
 
    const data = await fetchComments();
 
    //console.log(data);
 
    let comments = `<ol id="comment-list" class="comment-list">${data.comments}</ol>`;
    console.log(HTMLtoEL(comments));
    
    commentsList.appendChild(HTMLtoEL(comments));

    commentsEl.appendChild(commentsList);

    commentsEl.removeChild(showCommentsBtn);
    
    if (page) {
        //await showMoreCommentsFn();
    }
 
};

showCommentsBtn.addEventListener('click', showCommentsFn);


//document.getElementById('commentform').setAttribute('action-xhr', '');

/*


const sumbitComment = async (e) => {

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
    var formurl = commentform.getAttribute('action');
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
    if (response.ok) {
        const data = await response.json();

        if (data.error) {
            alert = window.A309TH.alertBox('error', `&#x26A0; ${data.msg}`);
            respondEl.appendChild(alert);
        } else {
            const commentList = document.getElementById('comment-list');
            if (commentList) {
                addCommentToDOM(data.comment);
            } else {
                alert = window.A309TH.alertBox('success', 'Comment was posted');
                respondEl.appendChild(alert);
            }
        }

    } else {
        alert = window.A309TH.alertBox('error', '&#x26A0; HTTP fetch error, API down!');
        respondEl.appendChild(alert);
    }
 
    window.A309TH.delSimpleSpinner(spinner);

};



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
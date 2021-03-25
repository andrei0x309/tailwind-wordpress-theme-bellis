
/* global A309TH */

// Document Ready
document.addEventListener("DOMContentLoaded", function () {

    const commentsEl = document.getElementById('comments');
    const commentFormEl = document.getElementById('commentform');

    const showCommentsBtn = document.getElementById('comments-show-btn');
    const postId = document.querySelector('article').dataset.id;

    let commentsList = null;
    let commentsShowMoreBtn = null;
    let page = null;

//Called from index
    eventsOnComments = () => {
// Add event on comments show
        if (showCommentsBtn)
            showCommentsBtn.addEventListener('click', A309TH.showCommentsFn);
        commentFormEl.addEventListener('submit', sumbitComment);
    };

    const commentsRemoveShowMoreBtn = () => {
        if (commentsShowMoreBtn) {
            commentsShowMoreBtn.removeEventListener('click', showMoreCommentsFn);
            commentsEl.removeChild(commentsShowMoreBtn);
            commentsShowMoreBt = null;
        }

    };

    const commentsAddShowMoreBtn = () => {
        const showMoreBtn = document.createElement('button');
        showMoreBtn.id = 'comments-show-more-btn';
        showMoreBtn.innerHTML = 'Show More Comments';
        commentsEl.appendChild(showMoreBtn);
        showMoreBtn.addEventListener('click', showMoreCommentsFn)

        return showMoreBtn;
    };


    const fetchComments = async () => {

        // fetch Comments 
        const fetchUrl = `${window.location.origin}/wp-json/a309/v1/get-comments/post/${postId}/page/${page}`;

        const response = await fetch(fetchUrl, {
            headers: {
                'Content-Type': 'application/json'
            }
        });
        const data = await response.json(); // parses JSON response into native JavaScript objects

        page -= 1;
        return data;
    };


    const fetchCommentsNo = async () => {

        const fetchUrl = `${window.location.origin}/wp-json/a309/v1/get-comments-no/post/${postId}`;

        const response = await fetch(fetchUrl, {
            headers: {
                'Content-Type': 'application/json'
            }
        });
        const data = await response.json();
        page = Math.ceil(data.commentNumber / 5);

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

        if (Number(comment.comment_parent) === 0) {
            const commentList = document.getElementById('comment-list');
            commentList.insertAdjacentHTML('afterbegin', newCom);
        } else {
            const cancelLink = document.getElementById('cancel-comment-reply-link');
            cancelLink.click();
            let commentParent = document.getElementById(`comment-${comment.comment_parent}`);
            let children = commentParent.querySelector('ol.children');
            if (!children) {
                children = document.createElement('ol');
                children.classList.add('children');
                commentParent.appendChild(children);
            }
            children.insertAdjacentHTML('afterbegin', newCom);
            children.scrollIntoView();
        }

    };

    showCommentsFn = async () => {
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
        commentsList.insertAdjacentHTML('beforeend', data.comments);

        commentsEl.appendChild(commentsList);


        if (page) {
            await showMoreCommentsFn();
        }

        commentsEl.removeChild(showCommentsBtn);

    };

    const showMoreCommentsFn = async () => {

        commentsRemoveShowMoreBtn();
        const spinner = document.createElement('div');
        spinner.classList.add('loadingspinner');
        commentsEl.appendChild(spinner);


        const data = await fetchComments();
        commentsList.insertAdjacentHTML('beforeend', data.comments);

        //console.log(page);
        //console.log(commentsEl.dataset.noComments - (5 * (page)));

        if (page) {
            commentsShowMoreBtn = commentsAddShowMoreBtn();
        }

        commentsEl.removeChild(spinner);
    };


    const sumbitComment = async (e) => {

        e.preventDefault();
        delAlertBox();
        const respondEl = document.getElementById('respond');
        const spinner = addSimpleSpinner(respondEl, false);

        const commentform = commentFormEl;
        const formdata = new URLSearchParams(new FormData(commentform));


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
                alert = alertBox('error', `&#x26A0; ${data.msg}`);
                respondEl.appendChild(alert);
            } else {
                const commentList = document.getElementById('comment-list');
                if (commentList) {
                    addCommentToDOM(data.comment);
                } else {
                    alert = alertBox('success', 'Comment was posted');
                    respondEl.appendChild(alert);
                }
            }

        } else {
            alert = alertBox('error', '&#x26A0; HTTP fetch error, API down!');
            respondEl.appendChild(alert);
        }

        delSimpleSpinner(spinner);

    };
 
    eventsOnComments();

});

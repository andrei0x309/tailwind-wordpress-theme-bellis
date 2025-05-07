
/* global SyntaxHighlighter */

// Document Ready
document.addEventListener("DOMContentLoaded", function () {

    // Get Some Elements we need
    window.articleNodes = null;
    window.mainTagEL = document.querySelector('main');

    window.A309TH.postsRemoveShowMoreBtn = document.getElementById('show-more-posts-btn');
    window.A309TH.fetchPostsOffset = 6;

    // Add Load More Events
    window.A309TH.postsRemoveShowMoreBtn.addEventListener('click', loadMorePosts);

    // Add Open Post Event
    //modifyEventForOpenPost(['.read-more', '.post-image-link', '.blog-post-title-link' ]);

});

const modifyEventForOpenPost = (selectors = [], remove = false) => {
    for (const selector of selectors) {
        document.querySelectorAll(selector).forEach(
            (node) => {
                if (!remove) node.addEventListener('click', openPost);
                else node.removeEventListener('click', openPost);
            });
    }
};




const fetchPosts = async (offset = 0, perPage = 0) => {
    // fetch posts 
    const fetchUrl = `${window.location.origin}/wp-json/theme/v1/get-posts/offset/${offset}/per-page/${perPage}`;

    const response = await fetch(fetchUrl, {
        headers: {
            'Content-Type': 'application/json'
        }
    });
    let data;
    if (response.ok) {
        data = await response.json();
        data.httpError = false;
    } else {
        data = { httpError: true };
    }
    // parses JSON response into native JavaScript objects
    return data;
};



const loadMorePosts = async () => {

    window.A309TH.delAlertBox();
    const showMorePostsParent = window.A309TH.postsRemoveShowMoreBtn.parentElement;
    window.A309TH.postsRemoveShowMoreBtn.disabled = true;

    const spinner = window.A309TH.addSimpleSpinner(showMorePostsParent);

    showMorePostsParent.prepend(spinner);

    const data = await fetchPosts(window.A309TH.fetchPostsOffset, 3);
    window.A309TH.fetchPostsOffset += 3;
    if (!data.httpError) {
        if (data.articles) {
            showMorePostsParent.insertAdjacentHTML('beforebegin', data.articles);
            if (typeof SyntaxHighlighter !== 'undefined') SyntaxHighlighter.highlight();
        } else {
            showMorePostsParent.removeChild(window.A309TH.postsRemoveShowMoreBtn);
            showMorePostsParent.prepend(window.A309TH.alertBox('info', '&#x26A0; No more articles!'));
        }
    } else {
        showMorePostsParent.prepend(window.A309TH.alertBox('error', '&#x26A0; Error fetching request!'));
    }

    //modifyEventForOpenPost(['.read-more', '.post-image-link', '.blog-post-title-link' ] , true);
    //modifyEventForOpenPost(['.read-more', '.post-image-link', '.blog-post-title-link' ]);

    window.A309TH.postsRemoveShowMoreBtn.disabled = false;

    window.A309TH.delSimpleSpinner(spinner);


};



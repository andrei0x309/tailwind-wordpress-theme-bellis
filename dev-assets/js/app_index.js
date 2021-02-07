 
 /* global SyntaxHighlighter */

// Document Ready
document.addEventListener("DOMContentLoaded", function () {

// Get Some Elements we need
    window.articleNodes = null;
    window.mainTagEL = document.querySelector('main');

    window.A309TH.postsRemoveShowMoreBtn = document.getElementById('show-more-posts-btn');
    window.A309TH.postsRemoveShowMoreBtnParent = window.A309TH.postsRemoveShowMoreBtn.parentElement;
    window.A309TH.fetchPostsOffset = 4;

    // Add Load More Events
    window.A309TH.postsRemoveShowMoreBtn.addEventListener('click', loadMorePosts);

    // Add Open Post Event
    document.querySelectorAll('.read-more').forEach(
            (node) => {
        node.addEventListener('click', openPost);
    });

});


window.onpopstate = () => {
    backToPosts();
};

const addSpinner = (articleStart) => {

    const spinnerTag = document.createElement('div');
    spinnerTag.id = 'loadingSpinner';
    spinnerTag.style = 'position: absolute; width:100%; height:100%;  left: 0; top: 0;  background: rgb(183 183 183 / 72%);';

    spinnerTag.innerHTML = `
    <div class='sk-folding-cube' style="position: absolute; left:45%; top:${articleStart + 80}px"%>
    <div class='sk-cube sk-cube-1'></div>
    <div class='sk-cube sk-cube-2'></div>
    <div class='sk-cube sk-cube-4'></div>
    <div class='sk-cube sk-cube-3'></div>
    <div class='sk-cube-txt'>Loading</div>
    </div>`;

    window.mainTagEL.appendChild(spinnerTag);
    return spinnerTag;
};

const addPost = (postData) => {
    let postEl = null;

    if (postData.article) {
        window.mainTagEL.insertAdjacentHTML('afterbegin', postData.article);
        postEl = document.getElementById(`post-${postData.post_id}`);
    }

    return postEl;

};

const removeSpinner = (spinnerTag) => {
    window.mainTagEL.removeChild(spinnerTag);
};

const updateHead = (yoastHeadData) => {

    const head = document.getElementsByTagName('head')[0];
    const next = head.querySelector(`link[rel="next"]`);
    if (next)
        next.parentElement.removeChild(next);
    let template = document.createElement('template');
    template.innerHTML = yoastHeadData;
    let allNodes = [...template.content.childNodes];
    if (allNodes) {
        allNodes = allNodes.filter(el => el instanceof HTMLElement);
    }

    const replaceContentOrAddMetaEl = (attribute, node) => {
        const existingElement = head.querySelector(`meta[${attribute}="${node.getAttribute(`${attribute}`)}"]`);
        if (existingElement) {
            existingElement.content = node.content;
        } else {
            head.appendChild(node);
        }
    };

    allNodes.forEach(
            (node) => {

        switch (node.nodeName.toLowerCase()) {
            case 'meta':
                if (node.hasAttribute('name')) {
                    replaceContentOrAddMetaEl('name', node)
                } else if (node.hasAttribute('property')) {
                    replaceContentOrAddMetaEl('property', node)
                }
                break;
            case 'title':
                const existingTitle = head.querySelector('title');
                head.removeChild(existingTitle);
                document.title = node.textContent;
                break;
            case 'script':
                const existingScript = head.querySelector('script[type="application/ld+json"]');
                if (existingScript)
                    head.removeChild(existingScript);
                head.appendChild(node);
                break;
            case 'link':
            {
                const existingLink = head.querySelector('link[rel="canonical"]');
                if (existingLink) {
                    existingLink.href = node.href;
                } else
                    head.appendChild(node);
                break;
            }
        }

    }
    );

};

const addStyleScriptts = ( scrptStyle = [{ type:'', id:'', path:'', onLoadCallback:() => true }] ) => {
    const head = document.getElementsByTagName('head')[0];
    for(let item of scrptStyle){
        if(item.type !== undefined){
            let element = head.querySelector(`#${item.id}`);
            if(item.type === 'script'){
            if(!element) element =  document.createElement(item.type);
                element.src = item.path;
                element.onload = item.onLoadCallback;
            }else{
              if(!element) element =  document.createElement('link');
              element.rel='stylesheet'; 
              element.type = 'text/css';
              element.media = 'all';
              element.href= item.path;
            }
            element.id = item.id;
            head.appendChild(element);
        }
    }
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
    const fetchUrl = `${window.location.origin}/wp-json/a309/v1/get-post/${postId}/user/${window.A309TH.current_user_id}`;

    const response = await fetch(fetchUrl, {
        headers: {
            'Content-Type': 'application/json'
        }
    });
    const data = await response.json(); // parses JSON response into native JavaScript objects

    // Remove posts

    window.articleNodes = window.mainTagEL.querySelectorAll('article');
    for (let articleNode of window.articleNodes) {
        window.mainTagEL.removeChild(articleNode);
    }

    window.A309TH.postsRemoveShowMoreBtnParent.parentElement.removeChild(window.A309TH.postsRemoveShowMoreBtnParent);

    // change URL

    const state = {'post_id': postId, 'post_slug': postSlug, 'post_title': postTitle};

    history.pushState(state, postTitle, postSlug);


    // Change Title and meta
    //document.title = data.title;
    //console.log(data.yoast_seo);
    updateHead(data['yoast_seo']);
    
    // Add Stylsheet and comment js
    addStyleScriptts([ {type:'script', id:'a309CommentsJs-js', path:`${window.A309TH.theme_URI}/js/app_comments.js`,  onLoadCallback: () => { console.log('test'); window.A309TH.eventsOnComments();  }    },
                       {type:'script', id:'comment-reply-js', path:`${window.location.origin}/wp-includes/js/comment-reply.min.js`,  onLoadCallback: () => { window.addComment.init();  } },
                       {type:'script', id:'akismet-form-js', path:`${window.location.origin}/wp-content/plugins/akismet/_inc/form.js` },
                       {type:'style',  id:'a309CommentsCss-css', path:`${window.A309TH.theme_URI}/css/comments.css` },

]);
    
     
    
    // Add Post to DOM
    addPost(data);
    window.scroll(0, 0);

    // Execute Plugin
    SyntaxHighlighter.highlight();

    //Remove Spinner

    removeSpinner(spinnerTag);

    return false;
};

const backToPosts = () => {

    if (window.articleNodes) {

        const curArt = window.mainTagEL.querySelector('article');
        console.log(curArt);
        const spinnerEl = addSpinner(curArt.getBoundingClientRect().top - document.body.getBoundingClientRect().top);

        window.mainTagEL.removeChild(curArt);
        const comments = document.getElementById('comments');

        for (let articleNode of window.articleNodes) {
            window.mainTagEL.appendChild(articleNode);
        }

        if (comments) {
            window.mainTagEL.removeChild(comments);
        }

        window.articleNodes = null;
        window.mainTagEL.appendChild(window.A309TH.postsRemoveShowMoreBtnParent);

        removeSpinner(spinnerEl);
    }

};


const fetchPosts = async (offset = 0, perPage = 0) => {
    // fetch posts 
    const fetchUrl = `${window.location.origin}/wp-json/a309/v1/get-posts/offset/${offset}/per-page/${perPage}`;

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
        data = {httpError: true};
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
            SyntaxHighlighter.highlight();

        } else {
            showMorePostsParent.removeChild(window.A309TH.postsRemoveShowMoreBtn);
            showMorePostsParent.prepend(window.A309TH.alertBox('info', '&#x26A0; No more articles!'));
        }
    } else {
        showMorePostsParent.prepend(window.A309TH.alertBox('error', '&#x26A0; Error fetching request!'));
    }
    window.A309TH.postsRemoveShowMoreBtn.disabled = false;

    window.A309TH.delSimpleSpinner(spinner);


};
 
 
 
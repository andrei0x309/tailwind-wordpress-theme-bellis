<?php
if (post_password_required()) {
    return;
}

$theme_no_comments = get_comments_number();

// global $current_user;
// if($current_user) echo $current_user->user_login;
?>

    <div id="comments" data-post-id="<?php echo $post->ID; ?>" data-no-comments="<?php echo $theme_no_comments; ?>" data-post-slug="<?php echo $post->post_name; ?>" class="comments-area default-max-width <?php echo get_option('show_avatars') ? 'show-avatars' : ''; ?>">
    
<?php

$aria_req = ($req) ? " aria-required='true'" : '';
$comments_args = [
    'comment_field' => '<p class="comment-form-comment"><textarea id="comment" name="comment" placeholder="Your Comment* " aria-required="true"></textarea></p>',
    'comment_notes_before' => '',
    'fields' => [
        'author' => '<div class="comment-name-email-block"><p class="comment-form-author">'.
        '<i class="icon-user-solid-square"></i><input id="author" class="blog-form-input" placeholder="Name* " name="author" type="text" value="'.esc_attr($commenter['comment_author']).
        '" size="30"'.$aria_req.' /></p>',
        'email' => '<p class="comment-form-email">'.
        '<i class="icon-alternate_email"></i><input
   id="email" class="blog-form-input" placeholder="Email Address* " name="email" type="text" value="'.esc_attr($commenter['comment_author_email']).
        '" size="30"'.$aria_req.' /></p></div>',
        'url' => '',
    ],
    'logged_in_as' => null,
    'title_reply' => esc_html__('Leave a comment', 'a309'),
    'title_reply_before' => '<h2 id="reply-title" class="comment-reply-title">',
    'title_reply_after' => '</h2>',
];
comment_form($comments_args);

if (have_comments()) {
    ?>
            <h2 class="comments-title"><?php
            if ('1' === $theme_no_comments) {
                esc_html_e('1 comment', 'a309');
            } else {
                printf(
                    /* translators: %s: comment count number. */
                    esc_html(_nx('%s comment', '%s comments', $theme_no_comments, 'Comments title', 'a309')),
                    esc_html(number_format_i18n($theme_no_comments))
                );
            } ?></h2><!-- .comments-title -->


            <button  id="comments-show-btn" >
                Show Comments
            </button>
            <!-- TODO: NO-JS page with comments later        
           <noscript data-ampdevmode><a  id="comments-page" >
            Go to comments page
           </a>
           </noscript>
            -->


    <?php if (!comments_open()) { ?>
                <p class="no-comments"><?php esc_html_e('Comments are closed.', 'a309'); ?></p>
            <?php } ?>
        <?php
} ?>
    </div><!-- #comments -->

<?php

if ( post_password_required() ) {
	return;
}

$a309_no_comments = get_comments_number();

//global $current_user; 
//if($current_user) echo $current_user->user_login;
 

?>
<?php if(a309_is_amp() ): ?>
<amp-script id="comments-script" layout="container" src="<?php echo get_stylesheet_directory_uri() ?>/js/AMP/amp_comments.js" sandbox="allow-forms">
 
<?php endif; ?>
<div id="comments" data-post-id="<?php echo $post->ID; ?>" data-no-comments="<?php echo $a309_no_comments; ?>" class="comments-area default-max-width <?php echo get_option( 'show_avatars' ) ? 'show-avatars' : ''; ?>">
<?php
if(a309_is_amp()): ?>
<div id="amp-respond"> <?php endif;

        $aria_req = ($req) ? " aria-required='true'" : '' ;
        $comments_args = array(  
            
            'comment_field' => '<p class="comment-form-comment"><textarea id="comment" name="comment" placeholder="Your Comment* " aria-required="true"></textarea></p>',
            'comment_notes_before' => '',
            'fields' => 
                    [   
                            'author' =>
      '<div class="comment-name-email-block"><p class="comment-form-author">'  .
      '<i class="icon-user-solid-square"></i><input id="author" class="blog-form-input" placeholder="Name* " name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) .
      '" size="30"' . $aria_req . ' /></p>',

    'email' =>
      '<p class="comment-form-email">'.
      '<i class="icon-alternate_email"></i><input
   id="email" class="blog-form-input" placeholder="Email Address* " name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) .
      '" size="30"' . $aria_req . ' /></p></div>',
                        'url' => '',
                    ],
 
			'logged_in_as'       => null,
			'title_reply'        => esc_html__( 'Leave a comment', 'twentytwentyone' ),
			'title_reply_before' => '<h2 id="reply-title" class="comment-reply-title">',
			'title_reply_after'  => '</h2>',
		);
        if(a309_is_amp() ) { 
            $comments_args['cancel_reply_before'] = '';
            $comments_args['cancel_reply_after'] = '';
            $comments_args['cancel_reply_link'] = '';
            $comments_args['submit_button'] = '<noscript data-ampdevmode><input name="%1$s" type="submit" id="%2$s" class="%3$s" value="%4$s" /></noscript>'; }
	comment_form( $comments_args );
	if(a309_is_amp()):    
        ?>
 
    <p class="form-submit">
        <button id="submit-amp" class="submit fade-in">Post Comment</button>
    </p>
</div>
<?php   
endif;

	if ( have_comments() ) :
		?>
		<h2 class="comments-title">
			<?php if ( '1' === $a309_no_comments ) : ?>
				<?php esc_html_e( '1 comment', 'twentytwentyone' ); ?>
			<?php else : ?>
				<?php
				printf(
					/* translators: %s: comment count number. */
					esc_html( _nx( '%s comment', '%s comments', $a309_no_comments, 'Comments title', 'twentytwentyone' ) ),
					esc_html( number_format_i18n( $a309_no_comments ) )
				);
				?>
			<?php endif; ?>
		</h2><!-- .comments-title -->
                
                
                <button  id="comments-show-btn" >
                 Show Comments
                </button>
                <noscript data-ampdevmode><a  id="comments-page" >
                 Go to comments page
                </a>
                </noscript>
                
   

                

		<?php if ( ! comments_open() ) : ?>
			<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'twentytwentyone' ); ?></p>
		<?php endif; ?>
	<?php endif; ?>
</div><!-- #comments -->
<?php if(a309_is_amp() ): ?>
</amp-script>

<?php endif;
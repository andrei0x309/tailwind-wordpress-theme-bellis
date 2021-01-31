<?php

if ( post_password_required() ) {
	return;
}

$twenty_twenty_one_comment_count = get_comments_number();

global $current_user;
 
 
//if($current_user) echo $current_user->user_login;
 

?>

 


<div id="comments" class="comments-area default-max-width <?php echo get_option( 'show_avatars' ) ? 'show-avatars' : ''; ?>">

    	<?php
        $aria_req = ($req) ? " aria-required='true'" : '' ;
        $comments_args = array(  
            
            'comment_field' => '<p class="comment-form-comment"><textarea id="comment" name="comment" placeholder="Your Comment* " aria-required="true"></textarea></p>',
            
            'fields' => 
                    [   
                            'author' =>
      '<div class="comment-name-email-block"><p class="comment-form-author">'  .
      '<input id="author" class="blog-form-input" placeholder="Your Name* " name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) .
      '" size="30"' . $aria_req . ' /></p>',

    'email' =>
      '<p class="comment-form-email">'.
      '<input id="email" class="blog-form-input" placeholder="Your Email Address* " name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) .
      '" size="30"' . $aria_req . ' /></p></div>',
                        'url' => '',
                    ],
 
			'logged_in_as'       => null,
			'title_reply'        => esc_html__( 'Leave a comment', 'twentytwentyone' ),
			'title_reply_before' => '<h2 id="reply-title" class="comment-reply-title">',
			'title_reply_after'  => '</h2>',
		);
        
	comment_form( $comments_args );
	?>
    
<?php
	if ( have_comments() ) :
		?>
		<h2 class="comments-title">
			<?php if ( '1' === $twenty_twenty_one_comment_count ) : ?>
				<?php esc_html_e( '1 comment', 'twentytwentyone' ); ?>
			<?php else : ?>
				<?php
				printf(
					/* translators: %s: comment count number. */
					esc_html( _nx( '%s comment', '%s comments', $twenty_twenty_one_comment_count, 'Comments title', 'twentytwentyone' ) ),
					esc_html( number_format_i18n( $twenty_twenty_one_comment_count ) )
				);
				?>
			<?php endif; ?>
		</h2><!-- .comments-title -->
                
                
                <button  id="comments-show-btn" >
                 Show Comments
                </button>

                
   

                

		<?php if ( ! comments_open() ) : ?>
			<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'twentytwentyone' ); ?></p>
		<?php endif; ?>
	<?php endif; ?>
</div><!-- #comments -->

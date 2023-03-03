<?php
/**
 * The template for displaying Comments.
 *
 * The area of the page that contains both current comments
 * and the comment form. 
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() )
	return;

if ( have_comments() || comments_open() ) {
	?>
	<section class="comments_wrap">
	<?php
	if ( have_comments() ) {
	?>
		<div id="comments" class="comments_list_wrap">
			<h6 class="section_title comments_list_title"><?php $post_comments = get_comments_number(); echo esc_html($post_comments); ?> <?php echo esc_html($post_comments==1 ? esc_html__('Comment', 'grace-church') : esc_html__('Comment(s)', 'grace-church')); ?></h6>
			<ul class="comments_list">
				<?php
				wp_list_comments( array('callback'=>'grace_church_output_single_comment') );
				?>
			</ul><!-- .comments_list -->
			<?php if ( !comments_open() && get_comments_number()!=0 && post_type_supports( get_post_type(), 'comments' ) ) { ?>
				<p class="comments_closed"><?php esc_html_e( 'Comments are closed.', 'grace-church' ); ?></p>
			<?php }	?>
			<div class="comments_pagination"><?php paginate_comments_links(); ?></div>
		</div><!-- .comments_list_wrap -->
	<?php 
	}

	if ( comments_open() ) {
	?>
		<div class="comments_form_wrap">
			<h6 class="section_title comments_form_title"><?php esc_html_e('Add Your Comment', 'grace-church'); ?></h6>
			<div class="comments_form">
				<?php
				$commenter = wp_get_current_commenter();
				$req = get_option( 'require_name_email' );
				$aria_req = ( $req ? ' aria-required="true"' : '' );
				$comments_args = array(
						// change the id of send button 
						'id_submit'=>'send_comment',
						// change the title of send button 
						'label_submit'=> esc_html__('Send', 'grace-church'),
						// change the title of the reply section
						'title_reply'=>'',
						// remove "Logged in as"
						'logged_in_as' => '',
						// remove text before textarea
						'comment_notes_before' => '<p class="comments_notes">'. esc_html__('Your email address will not be published. Required fields are marked *', 'grace-church').'</p>',
						// remove text after textarea
						'comment_notes_after' => '',
						// redefine your own textarea (the comment body)
						'comment_field' => '<div class="comments_field comments_message">'
											. '<label for="comment" class="required">' . esc_html__('Your Message', 'grace-church') . '</label>'
											. '<textarea id="comment" name="comment" placeholder="' . esc_html__( 'Your Message', 'grace-church' ) . '" aria-required="true"></textarea>'
										. '</div>',
						'fields' => apply_filters( 'comment_form_default_fields', array(
							'author' => '<div class="columns_wrap_comments_form"><div class="comments_field comments_author">'
									. '<label for="author"' . ( $req ? ' class="required"' : '' ). '>' . esc_html__( 'First and last name', 'grace-church' ) . '</label>'
									. '<input id="author" name="author" type="text" placeholder="' . esc_attr__( 'First and last name', 'grace-church' ) . ( $req ? ' *' : '') . '" value="' . esc_attr( isset($commenter['comment_author']) ? $commenter['comment_author'] : '' ) . '" size="30"' . ($aria_req) . ' />'
									. '</div>',
							'email' => '<div class="comments_field comments_email">'
									. '<label for="email"' . ( $req ? ' class="required"' : '' ) . '>' . esc_html__( 'Your Email', 'grace-church' ) . '</label>'
									. '<input id="email" name="email" type="text" placeholder="' . esc_attr__( 'Your Email', 'grace-church' ) . ( $req ? ' *' : '') . '" value="' . esc_attr(  isset($commenter['comment_author_email']) ? $commenter['comment_author_email'] : '' ) . '" size="30"' . ($aria_req) . ' />'
									. '</div>',
							'phone' => '<div class="comments_field comments_phone">'
									. '<label for="phone">' . esc_html__( 'Phone', 'grace-church' ) . '</label>'
									. '<input id="phone" name="phone" type="text" placeholder="' . esc_attr__( 'Phone', 'grace-church' ) . '" value="' . esc_attr(  isset($commenter['comment_author_phone']) ? $commenter['comment_author_phone'] : '' ) . '" size="30"' . ($aria_req) . ' />'
									. '</div>',
							'url' => '<div class="comments_field comments_site">'
									. '<label for="url" class="optional">' . esc_html__( 'Website', 'grace-church' ) . '</label>'
									. '<input id="url" name="url" type="text" placeholder="' . esc_attr__( 'Website', 'grace-church' ) . '" value="' . esc_attr(  isset($commenter['comment_author_url']) ? $commenter['comment_author_url'] : '' ) . '" size="30"' . ($aria_req) . ' />'
									. '</div>'
									. '</div>'
						) )
				);
			
				comment_form($comments_args);
				?>
			</div>
		</div><!-- /.comments_form_wrap -->
	<?php 
	}
	?>
	</section><!-- /.comments_wrap -->
<?php 
}
?>
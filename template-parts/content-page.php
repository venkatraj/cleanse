<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package Cleanse
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>


	<div class="entry-content">    
	 <?php  if( has_post_thumbnail() && ! post_password_required() ) :   
				the_post_thumbnail('cleanse-blog-large-width'); 		
			endif;?>

		<?php the_content();

			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'cleanse' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	  
	<?php edit_post_link( __( 'Edit', 'cleanse' ), '<footer class="entry-footer"><span class="edit-link"><i class="fa fa-pencil"></i>', '</span></footer>' ); ?>


</article><!-- #post-## -->

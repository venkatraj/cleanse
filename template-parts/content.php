<?php
/**
 * @package Cleanse
 */ 
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php 
		$featured_image = get_theme_mod( 'featured_image',true );
	    $featured_image_size = get_theme_mod ('featured_image_size','1');
		if( $featured_image &&  has_post_thumbnail() ) : ?>
			<?php
		        if ( $featured_image_size == '1' ) :?>	  	
						<div class="post-thumb">
						  <?php	if( $featured_image && has_post_thumbnail() ) : 
								    the_post_thumbnail('cleanse-blog-large-width');
			                endif;?>
			            </div> <?php
		        else: ?>
		 	            <div class="post-thumb">
		 	                 <?php if( has_post_thumbnail() && ! post_password_required() ) :   
					               the_post_thumbnail('cleanse-small-featured-image-width');
								endif;?>
			             </div>  <?php				
	            endif; ?>
	           <?php
		endif;?>

 

 
	<div class="latest-content">
			<div class="entry-date"> 
				<span class="date-structure">   
					<h2 class="dd"><?php the_time('j');?></h2>
					<span class="month"><?php the_time('M');?></span>
					<span class="year"><?php the_time('Y');?></span>
				</span>
			</div>
			<header class="header-content"> 
				<div class="title-meta">
					<?php the_title( sprintf( '<h3 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' ); ?>
					<?php cleanse_top_meta();?>
				</div>
		   

			<div class="entry-content">   
				<?php
					/* translators: %s: Name of current post */
					the_content( sprintf(
						__( 'Read More', 'cleanse' ),
						the_title( '<span class="screen-reader-text">"', '"</span>', false )
					) );
					
					wp_link_pages( array(
						'before' => '<div class="page-links">' . esc_html__( 'Pages: ', 'cleanse' ),
						'after'  => '</div>',
					) );
			?>
				
			</div><!-- .entry-content -->

		  

			<?php if ( 'post' == get_post_type() ): ?>
				<footer class="entry-footer">
					<?php cleanse_entry_footer(); ?>
				</footer><!-- .entry-footer -->
			<?php endif;?>
		</header><!-- .entry-header -->
		<br style="clear:both;"> 
	</div>
	 

  

</article><!-- #post-## -->
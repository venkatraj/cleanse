<?php
/**
 * The template part for displaying results in search pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Cleanse
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<header class="entry-header">
		<?php the_title( sprintf( '<h1 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' ); ?>
	<div class="title-meta"> 
		<?php if ( 'post' == get_post_type() ) : ?>
			<div class="entry-date"> 
				<span class="date-structure">
					<h2 class="dd"><?php the_time('j');?></h2>
					<span class="month"><?php the_time('M');?></span>
					<span class="year"><?php the_time('Y');?></span>
				</span>
			</div><!-- .entry-meta -->
				<?php cleanse_top_meta();?>
			
			<?php endif; ?>
	</div>
		<br class="clear">
</header><!-- .entry-header -->

	<div class="entry-summary">    
		<?php the_excerpt(); ?>
	</div><!-- .entry-summary -->
<?php if ( 'post' == get_post_type() ): ?>
	<footer class="entry-footer">     
		<?php cleanse_entry_footer(); ?>   
	</footer><!-- .entry-footer -->
<?php endif;?>

</article><!-- #post-## -->

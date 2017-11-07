<?php
/**
 * Custom template tags for this theme.
 *   
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Cleanse  
 */

if ( ! function_exists( 'cleanse_post_nav' ) ) :
/**
 * Display navigation to next/previous post when applicable.
 */
function cleanse_post_nav() {    
	// Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous ) {
		return;
	}
	?>
	<nav class="navigation post-navigation clearfix" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Post navigation', 'cleanse' ); ?></h1>
		<div class="nav-links">
			<?php
				previous_post_link( '<div class="nav-previous"><span class="meta-previuous-post">%link</span></div>', _x( 'previous post', 'Previous post link', 'cleanse' ) );
				next_post_link(     '<div class="nav-next"><span class="meta-next-post">%link</span></div>',     _x( 'Next Post&nbsp;', 'Next post link',     'cleanse' ) );
			?>
		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;


if ( ! function_exists( 'cleanse_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function cleanse_entry_footer() { 
	// Hide category and tag text for pages.
	
	if ( 'post' == get_post_type() ) {    
		/* translators: used between list items, there is a space after the comma */
		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list( '', __( ', ', 'cleanse' ) );
		if ( $tags_list ) {
			printf( '<span class="tags-links"><span class="tag-title">Tags</span> : ' . __( '%1$s ', 'cleanse' ) . '</span>', $tags_list );
		}
	}
} 
endif;


/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
if ( ! function_exists( 'cleanse_categorized_blog' ) ) :
	function cleanse_categorized_blog() {
		if ( false === ( $all_the_cool_cats = get_transient( 'cleanse_categories' ) ) ) {
			// Create an array of all the categories that are attached to posts.
			$all_the_cool_cats = get_categories( array(
				'fields'     => 'ids',
				'hide_empty' => 1,

				// We only need to know if there is more than one category.
				'number'     => 2,
			) );

			// Count the number of categories that are attached to the posts.
			$all_the_cool_cats = count( $all_the_cool_cats );

			set_transient( 'cleanse_categories', $all_the_cool_cats );
		}

		if ( $all_the_cool_cats > 1 ) {
			// This blog has more than 1 category so cleanse_categorized_blog should return true.
			return true;
		} else {
			// This blog has only 1 category so cleanse_categorized_blog should return false.
			return false;
		}
	}
endif;

/**
 * Flush out the transients used in cleanse_categorized_blog.
 */
if ( ! function_exists( 'cleanse_category_transient_flusher' ) ) :
	function cleanse_category_transient_flusher() {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		// Like, beat it. Dig?
		delete_transient( 'cleanse_categories' );
	}
endif;
add_action( 'edit_category', 'cleanse_category_transient_flusher' );
add_action( 'save_post',     'cleanse_category_transient_flusher' );

// Recent Posts with featured Images to be displayed on home page
if( ! function_exists('cleanse_recent_posts') ) {
	function cleanse_recent_posts() {      
		$output = '';
		// WP_Query arguments
		$args = array (
			'post_type'              => 'post',
			'post_status'            => 'publish',   
			'posts_per_page'         => 6,
			'ignore_sticky_posts'    => true,
			'order'                  => 'DESC',
		);

		// The Query
		$query = new WP_Query( $args );
		 $i = 0;
		// The Loop
		if ( $query->have_posts() ) {
			$output .= '<div class="post-wrapper">'; 
			$output .= '<div class="container">';   
			$output .= '<h1 class="title-divider">' . apply_filters('cleanse_post_title',__('Recent Blogs','cleanse') ) . '</h1>';
			$output .= '<div class="latest-posts clearfix">';
			$output .= '<div class="one-third column">';
			while ( $query->have_posts() ) {
				$query->the_post();
				if( isset($i) ) {
					if ($i==1) {$output .= '</div><div class="one-third column">';}
					if ($i==2) {$output .= '</div><div class="one-third column small-posts">';}
					if($i>=2) {
						$output .= '<div class="latest-post">';
							$output .= '<h3><a href="'. esc_url(get_permalink()) . '">' . get_the_title() . '</a></h3>';
							$output .='<div class="entry-meta">';
								$output .='<span class="data-structure"><span class="dd"><i class="fa fa-clock-o"></i>' . get_the_time('j M Y').'</span></span>';
							$output .='</div><!-- entry-meta -->';
						$output .= '</div>';
					}
					if($i<2) {
						$output .= '<div class="latest-post">';
							$output .= '<div class="latest-post-thumb">'; 
								if ( has_post_thumbnail() ) {
									$output .= get_the_post_thumbnail($query->post->ID ,'cleanse-recent-posts-img');
								}
								else {
									$output .= '<img src="' . esc_url(get_stylesheet_directory_uri()) . '/images/no-image.png" alt="" >';
								}
							$output .= '</div><!-- .latest-post-thumb -->';
							$output .='<div class="entry-meta">';
								$output .='<span class="data-structure"><span class="dd"><i class="fa fa-clock-o"></i>' . get_the_time('j M Y').'</span></span>';
								$output .= cleanse_get_author();
							$output .='</div><!-- entry-meta -->';
						    $output .= '<h3><a href="'. esc_url(get_permalink()) . '">' . get_the_title() . '</a></h3>';
							$output .= '<div class="latest-post-content">';
								$output .= '<p>' . get_the_content() . '</p>';
								$output .= wp_link_pages( array(
									'before' => '<div class="page-links">' . esc_html__( 'Pages: ', 'cleanse' ),
									'after'  => '</div>',
									'echo'  =>  false,
								) );
							$output .= '</div><!-- .latest-post-content -->';
						$output .= '</div>';
					}
				}
				
				$i++; 
			}
			$output .= '<div class="more-news-link">';
				$output .= '<h3><a href="'. esc_url(get_permalink( get_option('page_for_posts' ) )) . '">More News</a></h3>';
			$output .= '</div>';
			$output .= '</div><!-- .latest-post -->';
			$output .= '</div><!-- latest post end -->';
			$output .= '</div><!-- .container -->';
			$output .= '</div><!-- .post-wrapper -->';
		} 
		$query = null;
		// Restore original Post Data
		wp_reset_postdata();
		echo $output;
	}
}

/**
  * Generates Breadcrumb Navigation 
  */
 
 if( ! function_exists( 'cleanse_breadcrumbs' )) {
 
	function cleanse_breadcrumbs() {
		/* === OPTIONS === */
		$text['home']     = __( 'Home','cleanse' ); // text for the 'Home' link
		$text['category'] = __( 'Archive by Category "%s"','cleanse' ); // text for a category page
		$text['search']   = __( 'Search Results for "%s" Query','cleanse' ); // text for a search results page
		$text['tag']      = __( 'Posts Tagged "%s"','cleanse' ); // text for a tag page
		$text['author']   = __( 'Articles Posted by %s','cleanse' ); // text for an author page
		$text['404']      = __( 'Error 404','cleanse' ); // text for the 404 page

		$showCurrent = 1; // 1 - show current post/page title in breadcrumbs, 0 - don't show
		$showOnHome  = 0; // 1 - show breadcrumbs on the homepage, 0 - don't show
		$breadcrumb_char = get_theme_mod( 'breadcrumb_char', '1' );
		if ( $breadcrumb_char ) {
		 switch ( $breadcrumb_char ) {
		 	case '2' :
		 		$delimiter = ' &#47; ';
		 		break;
		 	case '3':
		 		$delimiter = ' &gt; ';
		 		break;
		 	case '1':
		 	default:
		 		$delimiter = ' &raquo; ';
		 		break;
		 }
		}

		$before      = '<span class="current">'; // tag before the current crumb
		$after       = '</span>'; // tag after the current crumb
		/* === END OF OPTIONS === */

		global $post;
		$homeLink = esc_url(home_url()) . '/';
		$linkBefore = '<span typeof="v:Breadcrumb">';
		$linkAfter = '</span>';
		$linkAttr = ' rel="v:url" property="v:title"';
		$link = $linkBefore . '<a' . $linkAttr . ' href="%1$s">%2$s</a>' . $linkAfter;

		if (is_home() || is_front_page()) {

			if ($showOnHome == 1) echo '<div id="crumbs"><a href="' . esc_url($homeLink) . '">' . $text['home'] . '</a></div>';

		} else {

			echo '<div id="crumbs" xmlns:v="http://rdf.data-vocabulary.org/#">' . sprintf($link, esc_url($homeLink), $text['home']) . $delimiter;

			if ( is_category() ) {
				$thisCat = get_category(get_query_var('cat'), false);
				if ($thisCat->parent != 0) {
					$cats = get_category_parents($thisCat->parent, TRUE, $delimiter);
					$cats = str_replace('<a', $linkBefore . '<a' . $linkAttr, $cats);
					$cats = str_replace('</a>', '</a>' . $linkAfter, $cats);
					echo $cats;
				}
				echo $before . sprintf($text['category'], single_cat_title('', false)) . $after;

			} elseif ( is_search() ) {
				echo $before . sprintf($text['search'], get_search_query()) . $after;

			} elseif ( is_day() ) {
				echo sprintf($link, get_year_link(get_the_time(__( 'Y', 'cleanse') )), get_the_time(__( 'Y', 'cleanse'))) . $delimiter;
				echo sprintf($link, get_month_link(get_the_time(__( 'Y', 'cleanse')),get_the_time(__( 'm', 'cleanse'))), get_the_time(__( 'F', 'cleanse'))) . $delimiter;
				echo $before . get_the_time(__( 'd', 'cleanse')) . $after;

			} elseif ( is_month() ) {
				echo sprintf($link, get_year_link(get_the_time(__( 'Y', 'cleanse'))), get_the_time(__( 'Y', 'cleanse'))) . $delimiter;
				echo $before . get_the_time(__( 'F', 'cleanse')) . $after;

			} elseif ( is_year() ) {
				echo $before . get_the_time(__( 'Y', 'cleanse')) . $after;

			} elseif ( is_single() && !is_attachment() ) {
				if ( get_post_type() != 'post' ) {  
					$post_type = get_post_type_object(get_post_type()); 
					printf($link, get_post_type_archive_link(get_post_type()), $post_type->labels->singular_name);
					if ($showCurrent == 1) echo $delimiter . $before . get_the_title() . $after;
				} else {   
					$cat = get_the_category(); $cat = $cat[0];
					$cats = get_category_parents($cat, TRUE, $delimiter);
					if ($showCurrent == 0) $cats = preg_replace("#^(.+)$delimiter$#", "$1", $cats);
					$cats = str_replace('<a', $linkBefore . '<a' . $linkAttr, $cats);
					$cats = str_replace('</a>', '</a>' . $linkAfter, $cats);
					echo $cats;
					if ($showCurrent == 1) echo $before . get_the_title() . $after;
				}

			} elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
				$post_type = get_post_type_object(get_post_type());
				echo $before . $post_type->labels->singular_name . $after;

			} elseif ( is_attachment() ) {
				$parent = get_post($post->post_parent);
				$cat = get_the_category($parent->ID); $cat = $cat[0];
				$cats = get_category_parents($cat, TRUE, $delimiter);
				$cats = str_replace('<a', $linkBefore . '<a' . $linkAttr, $cats);
				$cats = str_replace('</a>', '</a>' . $linkAfter, $cats);
				echo $cats;
				printf($link, get_permalink($parent), $parent->post_title);
				if ($showCurrent == 1) echo $delimiter . $before . get_the_title() . $after;

			} elseif ( is_page() && !$post->post_parent ) {
				if ($showCurrent == 1) echo $before . get_the_title() . $after;

			} elseif ( is_page() && $post->post_parent ) {
				$parent_id  = $post->post_parent;
				$breadcrumbs = array();
				while ($parent_id) {
					$page = get_page($parent_id);
					$breadcrumbs[] = sprintf($link, get_permalink($page->ID), get_the_title($page->ID));
					$parent_id  = $page->post_parent;
				}
				$breadcrumbs = array_reverse($breadcrumbs);
				for ($i = 0; $i < count($breadcrumbs); $i++) {
					echo $breadcrumbs[$i];
					if ($i != count($breadcrumbs)-1) echo $delimiter;
				}
				if ($showCurrent == 1) echo $delimiter . $before . get_the_title() . $after;

			} elseif ( is_tag() ) {
				echo $before . sprintf($text['tag'], single_tag_title('', false)) . $after;

			} elseif ( is_author() ) {
		 		global $author;
				$userdata = get_userdata($author);
				echo $before . sprintf($text['author'], $userdata->display_name) . $after;

			} elseif ( is_404() ) {
				echo $before . $text['404'] . $after;
			}

			if ( get_query_var('paged') ) {
				if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ' (';
				 _e('Page', 'cleanse' ) . ' ' . get_query_var('paged');
				if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')';
			}

			echo '</div>';

		}
	
	} // end cleanse_breadcrumbs()

}

if ( ! function_exists( 'cleanse_author' ) ) :
	function cleanse_author() {
		$byline = sprintf(
			esc_html_x( ' %s', 'post author', 'cleanse' ),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
		);		

		echo $byline; 
	}
endif;
 
if ( ! function_exists( 'cleanse_get_author' ) ) :
	function cleanse_get_author() {  
		$byline = sprintf(
			esc_html_x( ' %s', 'post author', 'cleanse' ),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '"><i class="fa fa-user"></i> ' . esc_html( get_the_author() ) . '</a></span>'
		);		

		return $byline;  
	}
endif;  

if ( ! function_exists( 'cleanse_comments_meta' ) ) :
	function cleanse_comments_meta() {
		echo cleanse_get_comments_meta();	
	}  
endif;  

if ( ! function_exists( 'cleanse_get_comments_meta' ) ) :
	function cleanse_get_comments_meta() {			
		$num_comments = get_comments_number(); // get_comments_number returns only a numeric value
 
		if ( comments_open() ) {
		  if ( $num_comments == 0 ) {
		    $comments = __('No Comments','cleanse');
		  } elseif ( $num_comments > 1 ) {
		    $comments = $num_comments . __(' Comments','cleanse');
		  } else {
		    $comments = __('1 Comment','cleanse');  
		  }
		  $write_comments = '<span class="comments-link"><a href="' . esc_url(get_comments_link()) .'">'. esc_html($comments).'</a></span>';
		} else{
			$write_comments = '<span class="comments-link"><a href="' . esc_url(get_comments_link()) .'">'. esc_html(__('Leave a comment', 'cleanse') ).'</a></span>';
		}
		return $write_comments;	
	}

endif;

if ( ! function_exists( 'cleanse_edit' ) ) :
	function cleanse_edit() {
		edit_post_link( __( 'Edit', 'cleanse' ), '<span class="edit-link"><i class="fa fa-pencil"></i> ', '</span>' );
	}
endif;


// Related Posts Function by Tags (call using cleanse_related_posts(); ) /NecessarY/ May be write a shortcode?
if ( ! function_exists( 'cleanse_related_posts' ) ) :
	function cleanse_related_posts() {
		echo '<ul id="cleanse-related-posts">';
		global $post;
		$post_hierarchy = get_theme_mod('related_posts_hierarchy','1');
		$relatedposts_per_page  =  get_option('post_per_page') ;
		if($post_hierarchy == '1') {
			$related_post_type = wp_get_post_tags($post->ID);
			$tag_arr = '';
			if($related_post_type) {
				foreach($related_post_type as $tag) { $tag_arr .= $tag->slug . ','; }
		        $args = array(
		        	'tag' => esc_html($tag_arr),
		        	'numberposts' => intval( $relatedposts_per_page ), /* you can change this to show more */
		        	'post__not_in' => array($post->ID)
		     	);
		   }
		}else {
			$related_post_type = get_the_category($post->ID); 
			if ($related_post_type) {
				$category_ids = array();
				foreach($related_post_type as $category) {
				     $category_ids = $category->term_id; 
				}  
				$args = array(
					'category__in' => absint($category_ids),
					'post__not_in' => array($post->ID),
					'numberposts' => intval($relatedposts_per_page),
		        );
		    }
		}
		if( $related_post_type ) {
	        $related_posts = get_posts($args);
	        if($related_posts) {
	        	foreach ($related_posts as $post) : setup_postdata($post); ?>
		           	<li class="related_post">
		           		<a class="entry-unrelated" href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"><?php the_post_thumbnail('recent-work'); ?></a>
		           		<a class="entry-unrelated" href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
		           	</li>
		        <?php endforeach; }
		    else {
	            echo '<li class="no_related_post">' . __( 'No Related Posts Yet!', 'cleanse' ) . '</li>'; 
			 }
		}else{
			echo '<li class="no_related_post">' . __( 'No Related Posts Yet!', 'cleanse' ) . '</li>';
		}
		wp_reset_postdata();
		
		echo '</ul>';
	}
endif;


/*  Site Layout Option  */
if ( ! function_exists( 'cleanse_layout_class' ) ) :
	function cleanse_layout_class() {
	     $sidebar_position = get_theme_mod( 'sidebar_position', 'right' ); 
		     if( 'fullwidth' == $sidebar_position ) {
		     	echo 'sixteen';
		     }else{
		     	echo 'eleven';
		     }
		     if ( 'no-sidebar' == $sidebar_position ) {
		     	echo ' no-sidebar';
		     }
	}
endif;

/* More tag wrapper */
add_action( 'the_content_more_link', 'cleanse_add_more_link_class', 10, 2 );
if ( ! function_exists( 'cleanse_add_more_link_class' ) ) :
	function cleanse_add_more_link_class($link, $text ) {
		return '<p class="portfolio-readmore"><a class="btn btn-mini more-link" href="'. esc_url(get_permalink()) .'">'.__('Read More','cleanse').'</a></p>';
	}
endif;


/* Admin notice */
/* Activation notice */
add_action( 'load-themes.php',  'cleanse_one_activation_admin_notice'  );

if( !function_exists('cleanse_one_activation_admin_notice') ) {
	function cleanse_one_activation_admin_notice() {
        global $pagenow;
	    if ( is_admin() && ('themes.php' == $pagenow) && isset( $_GET['activated'] ) ) {
	        add_action( 'admin_notices', 'cleanse_admin_notice' );
	    } 
	}   
}  

/* TOP Meta*/
if( ! function_exists('cleanse_top_meta') ) {   
	function cleanse_top_meta() { 
		global $post;  
		if ( 'post' == get_post_type() ) {  ?>
			<div class="entry-meta">
				<?php cleanse_author(); ?>
				<?php 
				/* translators: used between list items, there is a space after the comma */
				$categories_list = get_the_category_list( __( ', ', 'cleanse' ) ); 
				if ( $categories_list ) {
					printf( '<span class="cat-links"> ' . __( '%1$s ', 'cleanse' ) . '</span>', $categories_list );
				} ?>
				<?php cleanse_comments_meta(); ?> 

			</div><!-- .entry-meta --><?php
		}
	}
}

/**
 * Add admin notice when active theme
 *
 * @return bool|null  
 */
function cleanse_admin_notice() { ?>   
    <div class="updated notice notice-alt notice-success is-dismissible">  
        <p><?php printf( __( 'Welcome! Thank you for choosing %1$s! To fully take advantage of the best our theme can offer please make sure you visit our <a href="%2$s">Welcome page</a>', 'cleanse' ), 'Cleanse', esc_url( admin_url( 'themes.php?page=cleanse_upgrade' ) ) ); ?></p>
    	<p><a href="<?php echo esc_url( admin_url( 'themes.php?page=cleanse_upgrade' ) ); ?>" class="button" style="text-decoration: none;"><?php _e( 'Get started with Cleanse', 'cleanse' ); ?></a></p>
    </div><?php  
}  

/* header video */
add_action('cleanse_before_header','cleanse_before_header_video');
if(!function_exists('cleanse_before_header_video')) {
	function cleanse_before_header_video() {
		if(function_exists('the_custom_header_markup') ) { ?>
		    <div class="custom-header-media">
				<?php the_custom_header_markup(); ?>
			</div>
	    <?php } 
	}
}

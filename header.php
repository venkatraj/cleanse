<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package Cleanse
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11"><?php
if ( is_singular() && pings_open() ) { ?>
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>"><?php
} ?>
<?php wp_head(); ?>
</head>
  
<body <?php body_class(); ?>>  
<div id="page" class="hfeed site">
	<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'cleanse' ); ?></a>
	<?php do_action('cleanse_before_header'); ?>
	<header id="masthead" class="site-header" role="banner">   
			<?php if( is_active_sidebar( 'top-left' )  || is_active_sidebar( 'top-right' ) ): ?>
				<div class="top-nav">
					<div class="container">		
						<div class="eight columns">
							<div class="cart-left">
								<?php dynamic_sidebar('top-left' ); ?>
							</div>
						</div>
						<?php if(get_theme_mod('contact_info','true') ) { ?>
							<div class="seven columns">
								<div class="cart-right">
									<?php dynamic_sidebar('top-right' ); ?>  
								</div>
							</div>
							<div class="one columns">
								<div class="header-right">
									<div class="contact-icon">
										<span><i class="fa fa-phone"></i></span>
									</div>
									<div class="contact-info">
										<span class="title"><?php echo apply_filters('cleanse_contact_info_title', __('Call Us Now','cleanse') ); ?></span>
										<span><?php echo esc_html(get_theme_mod('phone_num1')); ?></span>
										<span><?php echo esc_html(get_theme_mod('phone_num2')); ?></span>
									</div> 
								</div>
							</div>
						<?php } else { ?>
							<div class="eight columns">
								<div class="cart-right">
									<?php dynamic_sidebar('top-right' ); ?>  
								</div>
							</div>
						<?php } ?>

					</div>
				</div> <!-- .top-nav -->
			<?php endif;?>
			
			<div class="branding header-image">
			<div class="nav-wrap">
				<div class="container">
					<div class="four columns">
						<div class="site-branding">
							<?php 
								$logo_title = get_theme_mod( 'logo_title' );   
								$tagline = get_theme_mod( 'tagline',true);
								if( $logo_title ) :
	                                the_custom_logo();     
	                            else : ?>
									<h3 class="site-title"><a style="color: #<?php header_textcolor(); ?>" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h3>
							<?php endif; ?>
							<?php if( $tagline ) : ?>
									<p class="site-description" style="color: #<?php header_textcolor(); ?>"><?php bloginfo( 'description' ); ?></p>
							<?php endif; ?>
						</div><!-- .site-branding -->
					</div>
					
			
					<div class="twelve columns">
						<nav id="site-navigation" class="main-navigation clearfix" role="navigation">
							<button class="menu-toggle" aria-controls="menu" aria-expanded="false"><i class="fa fa-align-justify fa-2x" aria-hidden="true"></i></button>
							<?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>
						</nav><!-- #site-navigation -->
					</div>
					
				</div>
			</div>
			
	<?php  ?>
				</div>


	</header><!-- #masthead --> 

	<?php if ( function_exists( 'is_woocommerce' ) || function_exists( 'is_cart' ) || function_exists( 'is_checkout' ) ) :
	 if ( is_woocommerce() || is_cart() || is_checkout() ) { ?>
	   <?php $breadcrumb = get_theme_mod( 'breadcrumb',true ); ?>    
		   <div class="breadcrumb">
				<div class="container"><?php
				   if( !is_search() && !is_archive() && !is_404() ) : ?>
						<div class="breadcrumb-left eight columns">
							<h4><?php woocommerce_page_title(); ?></h4>   			
						</div><?php
					endif; ?>
					<?php if( $breadcrumb ) : ?>
						<div class="breadcrumb-right eight columns">
							<?php woocommerce_breadcrumb(); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
	<?php } 
	endif; ?>

	



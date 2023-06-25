<?php
/*
Template Name: CHAOS Menu
*/
?>

<?php get_header(); ?>

			<div id="content" class="clearfix row" style="background-image: url(<?php echo randomHomeBackground() ;?>)">

				<div id="main" class="col col-lg-12 clearfix" role="main">

					<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

					<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article">

						<header>

							<!--<div class="page-header"><h1><?php the_title(); ?></h1></div>
							<?php echo custom_breadcrumbs(); ?>-->


						</header> <!-- end article header -->
						<section class="post-content">
						<div class="hist-slides">
						<!--SUB PAGES MENU-->
						<div id="hist-chaos-menu" class="col-md-12 offset-5">
						<?php the_content();?>
						<div id="chaos">
                            <ul>                                
                            </ul>                            	

                        </div>

						</div>

						</section> <!-- end article section -->

						<footer>

							<p class="clearfix"><?php the_tags('<span class="tags">' . __("Tags","wpbootstrap") . ': ', ', ', '</span>'); ?></p>

						</footer> <!-- end article footer -->

					</article> <!-- end article -->

					<?php comments_template(); ?>

					<?php endwhile; ?>

					<?php else : ?>

					<article id="post-not-found">
					    <header>
					    	<h1><?php _e("Not Found", "wpbootstrap"); ?></h1>
					    </header>
					    <section class="post-content">
					    	<p><?php _e("Sorry, but the requested resource was not found on this site.", "wpbootstrap"); ?></p>
					    </section>
					    <footer>
					    <?php -e("fish", "wpbootstrap"); ?>
					    </footer>
					</article>

					<?php endif; ?>

				</div> <!-- end #main -->

				<?php //get_sidebar(); // sidebar 1 ?>

			</div> <!-- end #content -->

<?php get_footer(); ?>
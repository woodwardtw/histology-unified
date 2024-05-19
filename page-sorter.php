<?php
/*
Template Name: Sorter Menu
*/
?>

<?php get_header(); ?>

			<div id="content" class="clearfix row" style="background-image: url(<?php echo randomHomeBackground() ;?>)">

				<div id="main" class="col col-lg-12 clearfix" role="main">

					<?php if (have_posts() && current_user_can('administrator')) : while (have_posts()) : the_post(); ?>

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
						<div>
                            <ol id="sorter" class="moveable" >
                            	<?php 
                            		$parent = 0;
	                            	if(isset($_GET["parent"])){
	                            		$parent = $_GET["parent"];
	                            	}
	                            	$query_args = array(
									    'post_type'   => 'page',
									    'post_status' => 'publish',
									    'parent'      => $parent,
									    'sort_column' => 'post_date'
									);

									$pages = get_pages( $query_args );
									foreach ($pages as $key => $page) {
										//var_dump($page);
										$post_id = $page->ID;
										$children = get_children($post_id);
										$exclude = array(25806, 392, 6055);
										$expand = '';
										$edit = "../wp-admin/post.php?post={$post_id}&action=edit";
										if(!in_array($post_id,$exclude)){
											if($children){
												$expand = "<a href='?parent={$post_id}'>expand</a></span>";
											} else {
												$expand = "<a href='?parent={$post_id}'>add children</a></span>";
											}
											echo "<li draggable='true' class='sorter' id='post-{$post_id}' data-id='{$post_id}' data-date='{$page->post_date}'>{$page->post_title} 
												<div class='buttons'>
													<span class='expand'>{$expand}</span>
													<span class='edit'><a href='{$edit}'>edit</a></span>
													</div>
												</li>";
										}	
								}
								// $upost_id = 25810;
								// $unew_date = "2010-04-20 12:12:00";
								// $uargs = array(
								// 	'ID' => $upost_id,
								// 	'post_date' => $unew_date
								// );
								// wp_update_post($uargs);
                            	;?>                             
                            </ol>                            	
                            <button class="btn btn-primary" id="new-post" data-toggle="modal" data-target="#new-post-modal" data-parentid="<?php echo $parent;?>">Create new item</button>
                            <button class="btn btn-primary" onclick="history.back()">Go Back</button>
                            <button class="btn btn-primary" id="update-sort">Update sort</button>
                            <!--needs to add post_parent id if not 0 and set page_template to histology page-->
                        </div>

						</div>

						</section> <!-- end article section -->

						<footer>

							<p class="clearfix"><?php the_tags('<span class="tags">' . __("Tags","wpbootstrap") . ': ', ', ', '</span>'); ?></p>

						</footer> <!-- end article footer -->

					</article> <!-- end article --> 
					<div class="modal" tabindex="-1" role="dialog" id="new-post-modal">
					  <div class="modal-dialog" role="document">
					    <div class="modal-content">
					      <div class="modal-header">
					        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
					          <span aria-hidden="true">&times;</span>
					        </button>
					      </div>
					      <div class="modal-body">
					      	  <label for="np-name">New Post Name</label><br>
					          <input type="text" id="new-post-name" name="np-name">
					      </div>
					      <div class="modal-footer">
					        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					        <button type="button" class="btn btn-primary" id="new-post-submit">Submit new post</button>
					      </div>
					    </div>
					  </div>
					</div>

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
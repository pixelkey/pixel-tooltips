<?php

/**
 * The template for displaying archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package understrap
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

get_header();

$container = get_theme_mod('understrap_container_type');
?>

<div class="wrapper" id="archive-wrapper">

	<div class="<?php echo esc_attr($container); ?>" id="content" tabindex="-1">

		<div class="row">

			<!-- Do the left sidebar check -->
			<?php //get_template_part( 'global-templates/left-sidebar-check' ); 
			?>

			<main class="site-main" id="main">

				<?php if (have_posts()) : ?>

					<header class="page-header">

						<h1 class="page-title">Glossary</h1>
						<?php
						the_archive_description('<div class="taxonomy-description">', '</div>');
						?>
					</header><!-- .page-header -->

					<ul class="glossary-list row">

						<?php /* Start the Loop */ ?>
						<?php while (have_posts()) : the_post(); ?>

							<div class="col-md-4">
								<div  class="tool-tip-container">

									<div class="glossary-term">
										<a href="<?php the_permalink() ?>">
											<?php the_title(); ?>
										</a>
									</div>


									<div class="tooltip-content">
										<div class="tooltip-content-inner">
											<?php the_content(); ?>
										</div>
									</div>

								</div >
							</div>
						<?php endwhile; ?>

					</ul>

				<?php else : ?>

					<?php get_template_part('loop-templates/content', 'none'); ?>

				<?php endif; ?>

			</main><!-- #main -->

			<!-- The pagination component -->
			<?php understrap_pagination(); ?>

			<!-- Do the right sidebar check -->
			<?php //get_template_part( 'global-templates/right-sidebar-check' ); 
			?>

		</div> <!-- .row -->

	</div><!-- #content -->

</div><!-- #archive-wrapper -->

<?php get_footer(); ?>
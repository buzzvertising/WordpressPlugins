<?php
 
get_header(); ?>
<div id="primary">
    <div id="content" role="main">

    <?php while ( have_posts() ) : the_post();?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header">
 
                <!-- Display featured image in right-aligned floating div -->
                <div style="float: right; margin: 10px">
                    <?php the_post_thumbnail( array( 100, 100 ) ); ?>
                </div>
 
                <!-- Display Title and Author Name -->
                <strong>Dog Name: </strong><?php the_title(); ?><br />
                <strong>Birth Date: </strong>
                <?php echo esc_html( get_post_meta( get_the_ID(), 'birthdate', true ) ); ?>
                <br />
 
				<?php 
				 
				$image_ids = get_field('gallery', false, false);
				 
				$shortcode = '
				[gallery ids="' . implode(',', $image_ids) . '"]
				';
				 
				echo do_shortcode( $shortcode );
				 
				?>
				
				<?php
				
				print apply_filters( 'taxonomy-images-queried-term-image', '', array(
				'after' => '</div>',
				'attr' => array(
					'alt'   => 'Custom alternative text',
					'class' => 'my-class-list bunnies turtles',
					'src'   => 'this-is-where-the-image-lives.png',
					'title' => 'Custom Title',
					),
				'before' => '<div id="my-custom-div">',
				'image_size' => 'medium',
				) );
				
				$terms = apply_filters( 'taxonomy-images-get-terms', '' );
				if ( ! empty( $terms ) ) {
					print '<ul>';
					foreach( (array) $terms as $term ) {
						print '<li><a href="' . esc_url( get_term_link( $term, $term->taxonomy ) ) . '">' . wp_get_attachment_image( $term->image_id, 'detail' ) .  $term->description .'link </a></li>';
					}
					print '</ul>';
				}
				?>
				<?php echo do_shortcode('[donateextra]'); ?>
				</a>
            </header>
 
            <!-- Display movie review contents -->
            <div class="entry-content"><?php the_content(); ?></div>
        </article>
 
    <?php endwhile; ?>
    </div>
</div>
<?php wp_reset_query(); ?>
<?php get_footer(); ?>
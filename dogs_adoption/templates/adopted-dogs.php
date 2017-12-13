<?php
 /*Template Name: Adopted Dogs
 */
 
get_header(); ?>
<div id="primary">
    <div id="content" role="main">
    <?php
    $mypost = array( 'post_type' => 'dogs', 'State' => 'adopted');
    $loop = new WP_Query( $mypost );
    ?>
    <?php while ( $loop->have_posts() ) : $loop->the_post();?>
		<?php if (get_post_status ( ) == 'adopted') : ?>
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
 
				<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" >
						More about <?php the_title();  ?>
				</a>
            </header>
 
            <!-- Display movie review contents -->
            <div class="entry-content"><?php the_content(); ?></div>
        </article>
		<?php endif; ?> 
    <?php endwhile; ?>
    </div>
</div>
<?php wp_reset_query(); ?>
<?php get_footer(); ?>
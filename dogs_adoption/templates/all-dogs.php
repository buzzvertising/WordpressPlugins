<?php
 /*Template Name: All Dogs
 */
 
get_header(); ?>
<div id="primary">
    <div id="content" role="main">
    <?php
    $mypost = array( 
		'post_type' => 'dogs',
		'tax_query' => array(                     //(array) - use taxonomy parameters (available with Version 3.1).
			'relation' => 'AND',
			array(
				'taxonomy' => 'State',                //(string) - Taxonomy.
				'field' => 'slug',                    //(string) - Select taxonomy term by ('id' or 'slug')
				'terms' => array( 'adopted' ),    //(int/string/array) - Taxonomy term(s).
				'include_children' => false,           //(bool) - Whether or not to include children for hierarchical taxonomies. Defaults to true.
				'operator' => 'NOT IN'                    //(string) - Operator to test. Possible values are 'IN', 'NOT IN', 'AND'.
			)
		)
	);
    $loop = new WP_Query( $mypost );
    ?>
    <?php while ( $loop->have_posts() ) : $loop->the_post();?>
		<?php if (get_post_status ( ) == 'publish') : ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header">
 
                <!-- Display featured image in right-aligned floating div -->
                <div style="float: right; margin: 10px">
                    <?php the_post_thumbnail( array( 100, 100 ) ); ?>
                </div>
 
                <!-- Display Title and Author Name -->
                <strong>Dog Name: </strong><?php the_title(); ?><br />
				<strong>Pictures: </strong>
                <?php $images = get_field('gallery'); ?>
				<img src="<?php echo $images[0]['url']; ?>" alt="<?php  the_sub_field('title');?>" />
				
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
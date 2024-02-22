<?php get_header() ?>
<main>
<?php do_action('back_button'); ?>

<?php
	the_post_thumbnail( 'full' );
    the_title();
	the_content();
    echo get_the_date();
?>

</main>
	
<?php
get_footer();
?>
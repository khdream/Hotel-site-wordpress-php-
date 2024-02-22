
<?php
    get_header();
?>
<?php
    
    $ACF_product_text_0_1 = get_field('product_text_0_1');
    $ACF_product_text_0_2 = get_field('product_text_0_2');
    $ACF_product_img_1 = get_field('product_img_1');
    $ACF_product_text_1_1 = get_field('product_text_1_1');
    $ACF_product_text_1_2 = get_field('product_text_1_2');
    $ACF_product_img_2 = get_field('product_img_2');
    $ACF_product_text_2_1 = get_field('product_text_2_1');
    $ACF_product_text_2_2 = get_field('product_text_2_2');
    $ACF_product_img_3 = get_field('product_img_3');
    $ACF_product_text_3_1 = get_field('product_text_3_1');
    $ACF_product_text_3_2 = get_field('product_text_3_2');
    $ACF_product_img_4 = get_field('product_img_4');
    $ACF_product_text_4_1 = get_field('product_text_4_1');
    $ACF_product_text_4_2 = get_field('product_text_4_2');
    $ACF_product_img_5 = get_field('product_img_5');
    $ACF_product_text_5_1 = get_field('product_text_5_1');
    $ACF_product_text_5_2 = get_field('product_text_5_2');
    $ACF_product_img_6 = get_field('product_img_6');
    $ACF_product_text_6_1 = get_field('product_text_6_1');
    $ACF_product_text_6_2 = get_field('product_text_6_2');
    $ACF_product_img_7 = get_field('product_img_7');
    $ACF_product_text_7_1 = get_field('product_text_7_1');
    $ACF_product_text_7_2 = get_field('product_text_7_2');
    $ACF_product_img_8 = get_field('product_img_8');
    $ACF_product_text_8_1 = get_field('product_text_8_1');
    $ACF_product_text_8_2 = get_field('product_text_8_2');
    $ACF_product_img_9 = get_field('product_img_9');
    $ACF_product_text_9_1 = get_field('product_text_9_1');
    $ACF_product_text_9_2 = get_field('product_text_9_2');
    $ACF_product_img_10 = get_field('product_img_10');
    $ACF_product_text_10_1 = get_field('product_text_10_1');
    $ACF_product_text_10_2 = get_field('product_text_10_2');
    ?>
<main>
    <div class="container">
        <div class="button">
            <?php do_action('back_button'); ?>
        </div>
        <div class="row block">
            <div class="img-container-1">
				<?php the_post_thumbnail( 'full' ); ?>
                <div class="img-container-1-text">
                    <p class="f-1"><?php the_title() ?></p>
                    <p class="f-2"><?php the_content() ?></p>
                </div>
            </div>
            <div class="utowa">
                <h3 class="f-2">
                    <?=$ACF_product_text_0_1?>
                </h3>
                <p class="f-2">
                    <?=$ACF_product_text_0_2?>
            </div>
        </div>
        <div class="row">
            <div class="img-container-2">
                <img src="<?=$ACF_product_img_1?>" alt="" width="100%">
            </div>
            <div class="p-width">
                <h3 class="f-2"><?=$ACF_product_text_1_1?></h3>
                <p class="f-2"><?=$ACF_product_text_1_2?></p>
            </div>
        </div>
        <div class="row">
            <div class="img-container-2">
                <img src="<?=$ACF_product_img_2?>" alt="" width="100%">
            </div>
            <div class="p-width">
                <h3 class="f-2"><?=$ACF_product_text_2_1?></h3>
                <p class="f-2" ><?=$ACF_product_text_2_2?></p>
            </div>
        </div>
        <div class="row">
            <div class="img-container-2">
                <img src="<?=$ACF_product_img_3?>" alt="" width="100%">
            </div>
            <div class="p-width">
                <h3 class="f-2"><?=$ACF_product_text_3_1?></h3>
                <p class="f-2" ><?=$ACF_product_text_3_2?></p>
            </div>
        </div>
        <div class="row">
            <div class="img-container-2">
                <img src="<?=$ACF_product_img_4?>" alt="" width="100%">
            </div>
            <div class="p-width">
                <h3 class="f-2"><?=$ACF_product_text_4_1?></h3>
                <p class="f-2" ><?=$ACF_product_text_4_2?></p>
            </div>
        </div>
        <div class="row">
            <div class="img-container-2">
                <img src="<?=$ACF_product_img_5?>" alt="" width="100%">
            </div>
            <div class="p-width">
                <h3 class="f-2"><?=$ACF_product_text_5_1?></h3>
                <p class="f-2" ><?=$ACF_product_text_5_2?></p>
            </div>
        </div>
        <div class="row">
            <div class="img-container-2">
                <img src="<?=$ACF_product_img_6?>" alt="" width="100%">
            </div>
            <div class="p-width">
                <h3 class="f-2"><?=$ACF_product_text_6_1?></h3>
                <p class="f-2" ><?=$ACF_product_text_6_2?></p>
            </div>
        </div>
        <div class="row">
            <div class="img-container-2">
                <img src="<?=$ACF_product_img_7?>" alt="" width="100%">
            </div>
            <div class="p-width">
                <h3 class="f-2"><?=$ACF_product_text_7_1?></h3>
                <p class="f-2" ><?=$ACF_product_text_7_2?></p>
            </div>
        </div>
        <div class="row">
            <div class="img-container-2">
                <img src="<?=$ACF_product_img_8?>" alt="" width="100%">
            </div>
            <div class="p-width">
                <h3 class="f-2"><?=$ACF_product_text_8_1?></h3>
                <p class="f-2" ><?=$ACF_product_text_8_2?></p>
            </div>
        </div>
        <div class="row">
            <div class="img-container-2">
                <img src="<?=$ACF_product_img_9?>" alt="" width="100%">
            </div>
            <div class="p-width">
                <h3 class="f-2"><?=$ACF_product_text_9_1?></h3>
                <p class="f-2" ><?=$ACF_product_text_9_2?></p>
            </div>
        </div>
        <div class="row">
            <div class="img-container-2">
                <img src="<?=$ACF_product_img_10?>" alt="" width="100%">
            </div>
            <div class="p-width">
                <h3 class="f-2"><?=$ACF_product_text_10_1?></h3>
                <p class="f-2" ><?=$ACF_product_text_10_2?></p>
            </div>
        </div>
    </div>
</main>
	
<?php
get_footer();
?>
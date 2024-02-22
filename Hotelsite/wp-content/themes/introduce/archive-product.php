<?php
    get_header();
?>
<?php do_action('back_button'); ?>
		<div class="top-menu">
            <div class="top-menu-content">
                <a class="logo" href="<?php echo site_url(); ?>#"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/素材/logo.png" alt=""></a>
                <ul class="category f-2">
                    <li class="dropdown white f-2">泉ヶ谷について
                        <ul class="list_items">
                            <li class="f-2"><a href="<?php echo site_url() ?>/product/スキンケア-utowa/">こだわりの宿 泉ヶ谷</a></li>
                            <li class="f-2"><a href="#">約款</a></li>
                        </ul>
                    </li>
                    <li class="dropdown white f-2">お部屋のしつらえ
                        <ul class="list_items">
                            <li class="f-2"><a href="#">前田直紀</a></li>
                            <li class="f-2"><a href="#">鷲巣恭一郎</a></li>
                        </ul>
                    </li>
                    <li class="dropdown white f-2">こだわりの逸品
                        <ul class="list_items">
                            <li class="f-2"><a href="#">UTOA</a></li>
                            <li class="f-2"><a href="#">タオル</a></li>
                        </ul>
                    </li>
                    <li class="dropdown white f-2">宿泊のお食事
                        <ul class="list_items">
                            <li class="f-2"><a href="#">朝食</a></li>
                            <li class="f-2"><a href="#">夕食</a></li>
                        </ul>
                    </li>
                    <li class="dropdown white f-2">周辺スポット
                        <ul class="list_items">
                            <li class="f-2"><a href="#">丸子城址</a></li>
                            <li class="f-2"><a href="#">櫻井養蜂場</a></li>
                        </ul>
                    </li>
                    <li class="dropdown white f-2">設備の使用方法
                        <ul class="list_items">
                            <li class="f-2"><a href="#">プロジェクタ</a></li>
                            <li class="f-2"><a href="#">電機ケトル</a></li>
                            <li class="f-2"><a href="#">アラーム付き 時計</a></li>
                            <li class="f-2"><a href="#">流し台</a></li>
                        </ul>
                    </li>
                    <li class="dropdown white f-2">お風呂・サウナ
                        <ul class="list_items">
                            <li class="f-2"><a href="#">お風呂使用方法</a></li>
                            <li class="f-2"><a href="#">サウナ使用方法</a></li>
                            <li class="f-2"><a href="#">湯帷子の着方</a></li>
                        </ul>
                    </li>
                    <li class="dropdown white f-2">貸出備品
                        <ul class="list_items">
                            <li class="f-2"><a href="#">貸出備品一覧</a></li>
                            <li class="f-2"><a href="#">ロック解除方法</a></li>
                        </ul>
                    </li>
                    <li class="dropdown white f-2">お車以外のお客様
                        <ul class="list_items">
                            <li><a href="#">タクシー会社</a></li>
                            <li><a href="#">最寄りバス時刻表</a></li>
                        </ul>
                    </li>
                </ul>
                </div>
                

		</div>

<div class="first-page-container">
    <div>
        <p class="f-1">こだわりの逸品</p>
        <P class="f-2">プライベートサウナや専用の和風バスローブをご紹介します。</P>
    </div>
        <div class="row">
    
<?php
        $args = array(
                'posts_per_page' => 10, /* how many post you need to display */
                'offset' => 0,
                'orderby' => 'post_date',
                'order' => 'DESC',
                'post_type' => 'product', /* your post type name */
                'post_status' => 'publish'
        );

        $query = new WP_Query( $args );
        if ($query -> have_posts()):
            while ($query->have_posts()) : $query->the_post(); 
            ?>
                <div class="column">
                    <a href="<?= the_permalink() ?>">
                        <img src="<?php echo get_the_post_thumbnail_url( get_the_ID()); ?>" alt="" width="100%" width="200px"/>
                    </a>
                    <p class="f-2"><?php the_title() ?></p>
                    <p class="f-3"><?php the_content() ?></p>
                </div>
            <?php 
                    endwhile; 
                endif;
                ?>
            </div>

    </div>


<?php
    get_footer();
?>
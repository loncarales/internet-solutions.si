<!-- Thumbnail from Custom Field, Post first image or default thumbnail -->
<div class="thumbnail">
    <a href="<?php the_permalink() ?>" rel="bookmark">
        <?php 
        $images = get_children(array('post_parent'=>$post->ID, 'post_status'=>'inherit', 'post_type'=>'attachment', 'post_mime_type'=>'image', 'order'=>'ASC', 'orderby'=>'menu_order'));
        
        $PostContent = $post->post_content;
        $ImgSearch = '|<img.*?src=[\'"](.*?)[\'"].*?>|i';
        preg_match_all($ImgSearch, $PostContent, $PostImg);
        $ImgNumber = count($PostImg[0]);
        
        if (get_post_meta($post->ID, "Thumbnail", true)) {
            ?>
        <img src="<?php echo get_post_meta($post->ID, "Thumbnail", true); ?>" alt="<?php the_title(); ?>" />
        <?php 
        } elseif (get_post_meta($post->ID, "thumbnail", true)) {
            
        ?>
        <img src="<?php echo get_post_meta($post->ID, "thumbnail", true); ?>" alt="<?php the_title(); ?>" />
        <?php 
        } elseif ($images) {
            $count = 1;
            foreach ($images as $id=>$image) {
                if ($count === 1) {
                    $img = wp_get_attachment_thumb_url($image->ID);
                    $link = get_permalink($post->ID);
                    print "\n\n".'<img src="'.$img.'" alt="" />';
                }
                $count++;
            }
        } else {
            
        ?>
        <img src="<?php bloginfo('stylesheet_directory');?>/images/thumbnail.png" alt=""/><?php } ?>
        </a>
</div>
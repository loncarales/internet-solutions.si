<ul id="nav">
<li class="<?php if ( is_home() or is_archive() or is_paged() or is_search() or (function_exists('is_tag') and is_tag()) ) { ?>current_page_item<?php } else { ?>page_item<?php } ?>"></li>
<?php wp_list_pages('title_li=&depth=4&exclude=183657917,183658042,183657912,183657914,183657918&sort_column=menu_order'); ?>
</ul>
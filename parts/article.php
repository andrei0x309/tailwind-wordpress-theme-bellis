<?php

$articleFull = (isset($args['full_content']) && $args['full_content']);
$isPage = (isset($args['is_page']) && $args['is_page']);

?>
<article <?php if (!$articleFull) { ?> itemscope itemtype="http://schema.org/BlogPosting"
<?php }?>
class="<?php echo $articleFull ? '' : 'content-va-on '; ?> dark:bg-dark-bg dark:text-dark-text p-8 bg-white break-word post-body mb-4 <?php echo implode(' ', get_post_class()); ?>"
id="post-<?php the_ID(); ?>" data-id="<?php the_ID(); ?>" data-slug="<?php echo $post->post_name; ?>" data-title="<?php echo $post->post_title; ?>">
     <header>
         <?php if ($articleFull) { ?>
         <h1 class="blog-post-title title-underline">
         <?php
} else { ?>
         <h2 itemprop="headline" class="blog-post-title blog-post-title-link">
         <a href="<?php echo esc_url(get_the_permalink()); ?>" title="<?php echo esc_attr(get_the_title()); ?>">
         <?php }?>

         <?php the_title(); ?>

         <?php if ($articleFull) { ?>
            </h1>
         <?php
} else { ?>
          </a>
         </h2>
         <?php }?>

        <?php if (!$isPage) { ?>
        <div class="blog-post-meta flex flex-row">
            <address class="author px-2 pt-3 pb-6"><a rel="author" title="Author's page" href="<?php echo site_url(); ?>/author/alina/">
            <i class="icon-user-solid-square"></i><span class="inline" <?php if (!$articleFull) { ?>  itemprop="author"<?php }?>> <?php the_author(); ?></span></a>
            </address>
            <span class="px-2 pt-3 pb-6"><i class="icon-calendar"></i> 
            <time datetime="<?php echo get_the_date('Y-m-d'); ?>" title="<?php echo get_the_date(); ?>" <?php if (!$articleFull) { ?> itemprop="datePublished" <?php }?>>
            <?php echo get_the_date(); ?>
            </time></span>
            <span class="px-2 pt-3 pb-6" ><i class="icon-folder"></i> <?php the_category(', '); ?></span>
            <?php if (!$articleFull) { ?>
            <meta itemprop="mainEntityOfPage" content="<?php echo site_url(); ?>">
            <meta itemprop="dateModified" content="<?php the_modified_date(); ?>">
            <?php }?>
        </div>
        <?php }?>
     </header><!--  !-->
     <div <?php if (!$articleFull) { ?>  itemprop="articleBody" <?php }?> class="article-body">
     <?php
if (function_exists('yoast_breadcrumb') && $articleFull && !$isPage) {
    yoast_breadcrumb('<div id="breadcrumbs" class="mb-2">', '</div>');
}

?>

 <?php
$postThumbUrl = get_the_post_thumbnail_url();
if ($postThumbUrl) {?>
           <?php if (!$articleFull) {?> <a href="<?php echo get_the_permalink(); ?>"
            title="<?php echo esc_attr(get_the_title()); ?>" id="featured-thumbnail-<?php the_ID(); ?>"
           class="post-image post-image-left p-0 <?php echo $articleFull ? '' : 'post-image-link'; ?>"><?php }?>
                <?php echo '<div class="pt-2 pr-4 pl-4 pb-6 featured-thumbnail w-full content-center justify-center md:w-full md:text-center">';
    $alt = theme_thumbnail_get_alt();
    ?>
               <img <?php if (!$articleFull) { ?> itemprop="image"<?php }?> class="m-auto wp-post-image" width="601" height="338" loading="lazy" src="<?php echo theme_resize_img_src($postThumbUrl, 601); ?>" <?php echo ($alt) ? 'alt="'.$alt.'"' : 'alt'; ?> >
                <?php
echo '</div>'; ?>
            <?php if (!$articleFull) {?> </a> <?php }?>
<?php }?>


 <?php if ($articleFull) {
        the_content();

        /*
        $tags = get_the_tags();
        if($tags){ ?>
        <div class="blog-post-meta flex flex-row">
        <span class="px-2 pt-3 pb-6 " ><i class="icon-tags"></i> <?php echo implode(', ', array_map(function($term) {
        return $term->name;
        }, $tags))  ; ?></span>
        </div>
        <?php
        }    */

        if (function_exists('ADDTOANY_SHARE_SAVE_KIT' && !$isPage)) {
            ADDTOANY_SHARE_SAVE_KIT([
                'buttons' => [
                    'twitter',
                    'reddit',
                    'pinterest',
                    'whatsapp',
                    'facebook',
                    'email',
                    'print', ],
            ]);
        }
    } else {
        the_excerpt();
    }
?>
 </div>
 </article><!-- /.blog-post -->
 <?php

if ($articleFull && !$isPage) {
    echo do_shortcode('[yarpp]');

    comments_template();
}

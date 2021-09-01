<?php

function getMainImgUrl($post)
{
    if ($post->get_image_id() !== "") {
        $url = wp_get_attachment_url($post->get_image_id());
    } else {
        $url = get_site_url() . "/wp-content/uploads/woocommerce-placeholder.png";
    }

    return $url;
}

/**
 * Banner Product Display
 */

function banner_product($att = [], $content = null, $tag = '')
{
    // normalize attribute keys
    $atts = array_change_key_case((array) $att, CASE_LOWER);

    // set user parameters
    $bp_atts = shortcode_atts(
        array(
            'p_id' => 15,
            'banner_text' => "NEXT GENERATION NATURALS"
        ),
        $atts,
        $tag
    );

    $arguments = array(
        'include' => array($bp_atts['p_id'])
    );

    $products = wc_get_products($arguments);
    $o = '';

    if (!empty($products)) {
        foreach ($products as $product) {
            // Product Details left
            $o = '<div class="detail-left">';

            $main_img_url = getMainImgUrl($product);

            // Product image
            $o .= '<div class="img-box down"><img src="' . $main_img_url . '" loading="lazy"> </div>';
            // Product details
            $o .= '<div class="product-detail">';
            $o .= '<p class="product-excerpt">' . $product->get_short_description('view') . '</p>';
            $o .= '<a href="' . $product->get_permalink() . '"><button class="green-button"> View Details</button></a>';
            $o .= '</div>';

            $o .= '</div>';

            // Product Details right
            $o .= '<div class="detail-right">';

            // Banner text
            $o .= '<h1 class="banner-title">' . $bp_atts['banner_text'] . '</h1>';

            // Product gallery image
            $o .= '<div class="img-box up">';
            $gallery_img_ids = $product->get_gallery_image_ids();

            if ($gallery_img_ids[0] !== NULL) {
                $gallery_img_url = wp_get_attachment_url($gallery_img_ids[0]);
            } else {
                $gallery_img_url = get_site_url() . "/wp-content/uploads/woocommerce-placeholder.png";
            }

            $o .= '<img src="' . $gallery_img_url . '" loading="lazy">';
            $o .= '</div>';
            $o .= '</div>';
        }
    } else {
        $o .= '<p>Oops, products not found.</p>';
    }

    return $o;
}

add_shortcode("display_banner_product", "banner_product");

function getGalleryImgUrl($post)
{
    if ($post !== NULL) {
        $gallery_img_url = wp_get_attachment_url($post);
    } else {
        $gallery_img_url = get_site_url() . "/wp-content/uploads/woocommerce-placeholder.png";
    }

    return $gallery_img_url;
}

/**
 * Display Product lists in blocks
 */

function product_list($att = [], $content = null, $tag = '')
{
    // normalize attribute keys
    $atts = array_change_key_case((array) $att, CASE_LOWER);

    // set user parameters
    $pl_atts = shortcode_atts(
        array(
            'category_slug' => 'cream',
            'count' => 3
        ),
        $atts,
        $tag
    );

    $o = '';

    $arguments = array(
        'category' => array($pl_atts['category_slug']),
        'limit' => $pl_atts['count'],
        'orderby' => 'date',
        'order' => 'DESC'
    );

    $products = wc_get_products($arguments);

    if (!empty($products)) {
        foreach ($products as $product) {
            //Product Block
            $o .= '<div class="product-block slide" >';
            //Image box
            $o .= '<div class="img-box up" >';
            $imgUrl = getMainImgUrl($product);
            $o .= '<img class="main-img" src="' . $imgUrl . '" loading="lazy">';
            $o .= '</div>'; // img-box closing

            //Product details
            $o .= '<div class="product-detail">';

            $o .= '<div class="description">';
            $o .= $product->get_description();
            $o .= '</div>';

            $o .= '<div class="lower-section">';

            $o .= '<a href="' . $product->get_permalink() . '"><button class="green-button"> View Details</button></a>';
            $o .= '<div class="gallery-images">';
            $gallery_img_ids = $product->get_gallery_image_ids();
            $count = 0;
            foreach ($gallery_img_ids as $id) {
                $count++;
                $url = getGalleryImgUrl($id);
                $o .= '<div class="img-box up">';
                $o .= '<img src="' . $url . '" loading="lazy">';
                $o .= '</div>';
                if ($count > 1) {
                    break;
                }
            }

            $o .= '</div>'; // Gallery images closing

            $o .= '</div>'; // Lower section closing

            $o .= '</div>'; // Product detail closing

            $o .= '</div>'; // Product Block closing
        }
    } else {
        $o = "Oops, products not found.";
    }

    return $o;
}

add_shortcode("display_product_list", "product_list");

function LP_images($att = [], $content = null, $tag = '')
{
    // normalize attribute keys
    $atts = array_change_key_case((array) $att, CASE_LOWER);

    // set user parameters
    $lpi_atts = shortcode_atts(
        array(
            'category_slug' => 'lipstick',
            'count' => 4
        ),
        $atts,
        $tag
    );

    $arguments = array(
        'category' => array($lpi_atts['category_slug']),
        'limit' => $lpi_atts['count'],
        'orderby' => 'date',
        'order' => 'DESC'
    );

    $products = wc_get_products($arguments);

    $o = '';
    $count = 0;

    if (!empty($products)) {
        foreach ($products as $product) {
            $count++;
            $imgUrl = getMainImgUrl($product);

            if ($count % 2 == 0) {
                $imgClass = "down";
            } else {
                $imgClass = "up";
            }

            $p_link = $product->get_permalink();

            $o .= <<<EOD
            <a href= $p_link>
                <div class="img-box $imgClass">
                    <img src= $imgUrl loading="lazy">
                </div>
            </a>
EOD;
        }
    } else {
        $o .= '<p>Oops, products not found.</p>';
    }


    return $o;
}

add_shortcode("display_LP_images", "LP_images");

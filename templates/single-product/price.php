<?php
/**
 * Single Product Price, including microdata for SEO
 *
 * @author 		WooThemes / Sean Voss
 * @package 	Shatner/WooCommerce/Templates
 * @version     1.6.4 - Lolz
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post, $product;


 $own_price = get_post_meta($post->ID, '_own_price', true);
        
        if($own_price == 'yes')
        {
?>
            <script>
                jQuery(function($){
                    $('.cart').submit(function(){
                        $('#price').clone().attr('type','hidden').appendTo($('form.cart'));
                        return;
                    });
                });
            </script>
            <div class='name_price'><label>Name your own price</label><input name='price' id='price' type='text' /></div><?
        } else {
?>
<div itemprop="offers" itemscope itemtype="http://schema.org/Offer">

	<p itemprop="price" class="price"><?php echo $product->get_price_html(); ?></p>

	<meta itemprop="priceCurrency" content="<?php echo get_woocommerce_currency(); ?>" />
	<link itemprop="availability" href="http://schema.org/<?php echo $product->is_in_stock() ? 'InStock' : 'OutOfStock'; ?>" />

</div>
<? } ?>

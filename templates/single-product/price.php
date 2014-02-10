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
$enforce_minimum = get_post_meta($post->ID, '_own_price_enforce_minimum', true);
        
if($own_price == 'yes')
{
?>
    <div itemprop="offers" itemscope itemtype="http://schema.org/Offer">
        <div class="name_price">
	        <h5 itemprop="price" class="price">Suggested Price: <?php echo $product->get_price_html(); ?></h5>
            <p itemprop="name_price price" class="price">
              <label><?php echo get_option('shatner_label', "Name your own price") ?> </label>
              <input name='price' class='name_price' type='text' />
            
<?php 
if ($enforce_minimum == 'yes')
{ ?>
              <input type="hidden" id="minimum_price" name="minimum_price" value="<?php echo $product->get_price() ?>" />
<?php } ?>
            </p>
        </div>

        <meta itemprop="priceCurrency" content="<?php echo get_woocommerce_currency(); ?>" />
        <link itemprop="availability" href="http://schema.org/<?php echo $product->is_in_stock() ? 'InStock' : 'OutOfStock'; ?>" />

    </div>

    <?
        } else {
?>
<div itemprop="offers" itemscope itemtype="http://schema.org/Offer">

	<p itemprop="price" class="price"><?php echo $product->get_price_html(); ?></p>

	<meta itemprop="priceCurrency" content="<?php echo get_woocommerce_currency(); ?>" />
	<link itemprop="availability" href="http://schema.org/<?php echo $product->is_in_stock() ? 'InStock' : 'OutOfStock'; ?>" />

</div>
<? } ?>

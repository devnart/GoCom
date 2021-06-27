<?php
/**
 * Single Product tabs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/tabs.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.8.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Filter tabs and allow third parties to add their own.
 *
 * Each tab is an array containing title, callback and priority.
 *
 * @see woocommerce_default_product_tabs()
 */
$product_tabs = apply_filters( 'woocommerce_product_tabs', array() );

$tabs_layout = woodmart_get_opt( 'product_tabs_layout' ); // accordion tabs

$scroll        = ( $tabs_layout == 'accordion' );
$tab_count     = 0;
$content_count = 0;

woodmart_enqueue_js_script( 'single-product-tabs-accordion' );
woodmart_enqueue_js_script( 'product-accordion' );

if ( comments_open() ) {
	woodmart_enqueue_js_script( 'single-product-tabs-comments-fix' );
	woodmart_enqueue_js_script( 'woocommerce-comments' );
}

if ( ! empty( $product_tabs ) ) : ?>

	<div class="woocommerce-tabs wc-tabs-wrapper tabs-layout-<?php echo esc_attr( $tabs_layout ); ?>">
		<ul class="tabs wc-tabs">
			<?php foreach ( $product_tabs as $key => $product_tab ) : ?>
				<li class="<?php echo esc_attr( $key ); ?>_tab <?php echo $tab_count === 0 ? 'active' : ''; ?>">
					<a href="#tab-<?php echo esc_attr( $key ); ?>"><?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', esc_html( $product_tab['title'] ), $key ); ?></a>
				</li>
				<?php $tab_count++; ?>
			<?php endforeach; ?>
		</ul>
		<?php foreach ( $product_tabs as $key => $product_tab ) : ?>
			<div class="wd-tab-wrapper<?php echo woodmart_get_old_classes( ' woodmart-tab-wrapper' ); ?>">
				<a href="#tab-<?php echo esc_attr( $key ); ?>" class="wd-accordion-title<?php echo woodmart_get_old_classes( ' woodmart-accordion-title' ); ?> tab-title-<?php echo esc_attr( $key ); ?> <?php echo $content_count === 0 ? 'active' : ''; ?>"><?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', esc_html( $product_tab['title'] ), $key ); ?></a>
				<div class="woocommerce-Tabs-panel woocommerce-Tabs-panel--<?php echo esc_attr( $key ); ?> panel entry-content wc-tab" id="tab-<?php echo esc_attr( $key ); ?>">
					<div class="wc-tab-inner 
					<?php
					if ( $scroll ) {
						echo 'wd-scroll';}
					?>
					">
						<div class="<?php echo true == $scroll ? 'wd-scroll-content' : ''; ?>">
							<?php call_user_func( $product_tab['callback'], $key, $product_tab ); ?>
						</div>
					</div>
				</div>
			</div>
			<?php $content_count++; ?>
		<?php endforeach; ?>
		<?php do_action( 'woocommerce_product_after_tabs' ); ?>
	</div>

<?php endif; ?>

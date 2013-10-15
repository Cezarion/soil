<?php if ( !post_exists_id( $id ) ) : //Check if post exist ?>
	<pre><strong><?php echo _x( 'Post with ID '.$id.' doesn\'t exist' ,' base' ); ?></strong></pre>
	<?php return; ?>
<?php else: ?>
<?php
$adv_datas 	= get_post_meta_with_prefix( $id , '_fm_' );
$adv_slug		= get_post_field( 'post_name' , $id );
$adv_params 	= (object) $adv_datas->_fm_advertise_slideshow_parameters;
$adv_items 	= $adv_datas->_fm_advertise;
$i = 0;
?>

<div class="advertise-wrapper">
	<div id="adv_<?php echo $adv_slug; ?>" class="advertise advertise-<?php echo $adv_params->adv_theme; ?> carousel slide" data-interval="<?php echo $adv_params->adv_timer; ?>">
		<div class="carousel-inner">
		<?php foreach ($adv_items as $item) : ?>
			<?php if (!empty($item['adv_image'])) : ?>
				<?php
					$adv_img_alt = ( strlen( get_post_meta($item['adv_image'], '_wp_attachment_image_alt', true) ) > 0 )
											? trim(strip_tags( get_post_meta($item['adv_image'], '_wp_attachment_image_alt', true) ) )
											: $item['adv_title'];
					$attr  = array
							(
								'alt' 	=> $adv_img_alt,
								'title' 	=> $item['adv_title'],
							);
					$class = ( $i === 0 ) ? 'item active' : 'item';
				?>
				<div class="<?php echo $class; ?>">
					<a href="<?php echo $item['adv_link']; ?>" rel="external" title="<?php echo $item['adv_title']; ?>" target="_blank">
						<?php echo wp_get_attachment_image( $item['adv_image'], 'adv-'.$adv_params->adv_theme, '' , $attr ); ?>
					</a>
				</div>
			<?php endif; ?>
		<?php $i++; endforeach; ?>
		</div>
	</div>
</div>
<?php endif; ?>
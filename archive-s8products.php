<?php get_template_part('header'); ?>

<section id="products">
	<div class="container-fluid">
		<div class="row" style="flex-wrap: nowrap;">
			<div id="filter_icon" class="hiddendesktop"><img src="/wp-content/uploads/2018/01/filters.svg" alt="filters icon"/>Filters</div>
			<div id='reset' class="hidden"><img src='/wp-content/uploads/2018/01/green_arrow.svg' alt='backarrow icon' class='back_arrow_icon' />Back to results</div>
			<div id="prd_fil_sidebar" class="col-md-3 filter_sidebar">				
				<form action="<?php echo site_url() ?>/wp-admin/admin-ajax.php" method="POST" id="filter">				
					<?php
						if( $terms = get_terms( 'product_category', 'orderby=name' ) ) : 
							echo '<fieldset><legend>Products</legend>';
							foreach ( $terms as $term ) :
								echo '<input type="checkbox" class="prod_cat_filt" name="prod_cat_filt[]" id="' . $term->term_id . '" value="' . $term->slug . '"><label for="' . $term->term_id . '">' . $term->name . '</label><br />';
							endforeach;
							echo '</fieldset>';
						endif;
						if( $terms = get_terms( 'product_package', 'orderby=name' ) ) : 
							echo '<fieldset><legend>Packages</legend>';
							foreach ( $terms as $term ) :
								echo '<input type="checkbox" class="prod_pac_filt" name="prod_pac_filt[]" id="' . $term->term_id . '" value="' . $term->slug . '"><label for="' . $term->term_id . '">' . $term->name . '</label><span class="pck_clr" style="background-color: ' . get_field('package_color', $term) . '"></span><br />';
							endforeach;
							echo '</fieldset>';
						endif;
					?>
					<input type="hidden" name="action" value="productfilter">
				</form>
			</div>
			<div id="response" class="col-md-9">
				<?php 	
					
						$query = new WP_Query( 
							array(
								'post_type' => 's8products',
								'posts_per_page' => -1,
							)
						);
						
				if( $query->have_posts() ) : ?>
					<?php while( $query->have_posts() ): $query->the_post(); ?>	
						<?php $post = $query->post; ?>
						<?php $prd_image = get_field('primary_product_image'); ?>
						<div class="a360product col-md-4 col-sm-6" data-prd-id="<?php echo $post->ID; ?>" data-slug="<?php echo $post->post_name; ?>">
							<div class="a360product_image"><img src="<?php echo $prd_image['sizes']['thumbnail']; ?>" alt="<?php echo $prd_image['alt']; ?>" /></div>
							<h3><?php echo $post->post_title; ?></h3>
							<p><?php echo $post->post_excerpt; ?></p>
							
							<?php $term_list = wp_get_post_terms($post->ID, 'product_package', array("fields" => "all")); ?>
							
							<?php foreach ( $term_list as $term ) : ?>
								<span class="pck_clr" style="background-color: <?php the_field('package_color', $term); ?>"></span>
							<?php endforeach; ?>							
						</div>
												
					<?php endwhile; 
					wp_reset_postdata();
				else :
					echo "We don't have any products matching your criteria.";
				endif;
				
				?>
			</div>
			<div id="product_details"></div>			
		</div>
	</div>
</section>

<section id="footer-quote-request" style="background-image: url(<?php the_field('cta_bg', 4); ?>); background-size: cover; background-position: center;">
	<div class="container-fluid">
		<div class="row justify-content-center align-items-center">
			<div class="col-md-12">
				<h2>Total Control, your way.</h2>
				<div class="phone_number_cta"><a class="green" href="tel:+18886424567">(888) 642-4567</a></div>
				<h3>Let us give you a custom quote.</h3>
				<?php gravity_form( 1, false, false, false, '', false ); ?>
			</div>
		</div>
	</div>
</section>

<script>
jQuery(function($){
	$('#filter input:checkbox').on('change', function(){
		var filter = $('#filter');
		$.post({
			url:filter.attr('action'),
			data:filter.serialize(), // form data
			type:filter.attr('method'), // POST
			
			success:function(data){

				$('#response').html(data); // insert data
				
			}
		});
		return false;
	});	
});
</script>

<?php get_template_part('footer'); ?>

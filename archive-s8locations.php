<?php get_template_part('header'); ?>

<section id="locations">
	<div class="container-fluid">
		<div class="row">
			<div id="loc_fil_sidebar" class="col-md-3">
				<form action="<?php echo site_url() ?>/wp-admin/admin-ajax.php" method="POST" id="filter" >
					<fieldset>
						<input type="text" id="zip_filt" name="zip_filt[]" placeholder="Your Zip Code" maxlength="5">
					</fieldset>
					<input type="hidden" name="action" value="locationfilter">
				</form>
				<div class="locations_list">
					<?php 
							
						$query = new WP_Query( 
							array(
								'post_type' => 's8locations',
							)
						);
								
						if( $query->have_posts() ) : ?>
						<?php global $post; ?>
							<?php while( $query->have_posts() ): $query->the_post(); ?>	
								
								<div class="a360location" data-prd-id="<?php echo $post->ID; ?>">
									<div class="a360location_image">
										<a href="<?php the_permalink(); ?>"><img src="<?php the_field('location_image'); ?>" alt="<?php the_title();  ?>"/></a>
									</div>
									<div class="a360location_copy">
										<h4><a href="<?php the_permalink(); ?>"><?php the_title();  ?></a></h4>
										<p><?php the_field('address'); ?>
										<?php 
											$phone = get_field('phone_number'); 
											$phonelink = '+1' . preg_replace('/[^0-9]/', '', $phone);
										?>
										<br><a href="tel:<?php echo $phonelink; ?>"><?php echo $phone;?></a></p>
									</div>							
								</div>
														
							<?php endwhile;	
							wp_reset_postdata();
						else :
							echo "We currently don't serve in your area. Sign up for updates.";
						endif;					
					?>
				</div>
			</div>
			<div id="map" class="col-md-9">
				
			</div>	
		</div>
	</div>
</section>

<script>
jQuery(function($){
	$('#filter #zip_filt').on('change paste keyup', function(){
		var filter = $('#filter');
		if(this.value.length == this.getAttribute('maxlength')) {
	        if(!$(this).data('triggered')) {
		        $(this).data('triggered',true); 
				$.post({
					url:filter.attr('action'),
					data:filter.serialize(), // form data
					type:filter.attr('method'), // POST
					success:function(data){
						$('.locations_list').html(data); // insert data	
					}
					
				});
			}
		} else { $(this).data('triggered',false); }
		return false;
	});
});
</script>


    <script>
      function initMap() {
        
        var locations = [
	      ['San Antonio', 29.5275721, -98.4360978, 4, 'san-antonio-tx'],
	      ['Austin', 30.3949285, -97.710846, 5, 'austin-tx'],
	      ['Kansas City', 38.9771986, -94.7225916, 3, 'kansas-city-ks'],
	      ['Tulsa', 36.1166488, -95.8604198, 2, 'tulsa-ok'],
	      ['Springfield', 37.1274925, -93.28054, 1, 'springfield-mo'],
	      ['Houston', 29.9417462, -95.3909125, 6, 'houston-tx'],
	      ['Fort Worth', 32.8404683, -97.3016663, 7, 'fort-worth-tx'],
	      ['Dallas', 32.962952, -96.8449507, 8, 'dallas-tx'],
	      ['Wichita', 37.7472102, -97.2410527, 9, 'wichita-ks'],
	      ['Sacramento', 38.6537217, -121.3894514, 10, 'sacramento-ca'],
	      ['Phoenix', 33.3570251, -111.9771904, 11, 'phoenix-az'],
	      ['Oklahoma City', 35.4057372, -97.5997183, 12, 'oklahoma-city-ok'],
	      ['Stafford', 29.6358116, -95.6625232, 13, 'houston-tx-south']
	    ];
        
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 5,
		  center: new google.maps.LatLng(36, -100),
		  mapTypeId: google.maps.MapTypeId.ROADMAP
        });
        
        var icon = '/wp-content/uploads/2018/01/Oval-3-Copy.svg';
        
        var marker, i;

		    for (i = 0; i < locations.length; i++) { 
		      marker = new google.maps.Marker({
		        position: new google.maps.LatLng(locations[i][1], locations[i][2]),
		        map: map,
		        icon: icon,
		        url: '/location/' + locations[i][4]
		      });
			  google.maps.event.addListener(marker, 'click', function() {
			    window.location.href = this.url;
			  });
			}
	}	  
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCS-UgbjFphU_4mfJ5hztTYcgVVN4lZFRg&callback=initMap">
    </script>

<?php get_template_part('footer'); ?>

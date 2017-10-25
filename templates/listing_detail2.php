<?php
/* The loop starts here. */
global $listingpro_options;
if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();
		setPostViews(get_the_ID());
		$claimed_section = listing_get_metabox('claimed_section');
		$tagline_text = listing_get_metabox('tagline_text');

		$plan_id = listing_get_metabox_by_ID('Plan_id',get_the_ID());
		if(!empty($plan_id)){
			$plan_id = $plan_id;
		}else{
			$plan_id = 'none';
		}

		$contact_show = get_post_meta( $plan_id, 'contact_show', true );
		$map_show = get_post_meta( $plan_id, 'map_show', true );
		$video_show = get_post_meta( $plan_id, 'video_show', true );
		$gallery_show = get_post_meta( $plan_id, 'gallery_show', true );
		$tagline_show = get_post_meta( $plan_id, 'listingproc_tagline', true );
		$location_show = get_post_meta( $plan_id, 'listingproc_location', true );
		$website_show = get_post_meta( $plan_id, 'listingproc_website', true );
		$social_show = get_post_meta( $plan_id, 'listingproc_social', true );
		$faqs_show = get_post_meta( $plan_id, 'listingproc_faq', true );
		$price_show = get_post_meta( $plan_id, 'listingproc_price', true );
		$tags_show = get_post_meta( $plan_id, 'listingproc_tag_key', true );
		$hours_show = get_post_meta( $plan_id, 'listingproc_bhours', true );

		if($plan_id=="none"){
			$contact_show = 'true';
			$map_show = 'true';
			$video_show = 'true';
			$gallery_show = 'true';
			$tagline_show = 'true';
			$location_show = 'true';
			$website_show = 'true';
			$social_show = 'true';
			$faqs_show = 'true';
			$price_show = 'true';
			$tags_show = 'true';
			$hours_show = 'true';
		}

		$claim = '';
		if($claimed_section == 'claimed') {
			$claim = '<span class="claimed"><i class="fa fa-check"></i> '. esc_html__('Claimed', 'listingpro').'</span>';

		}elseif($claimed_section == 'not_claimed') {
			$claim = '';

		}
		global $post;

		$resurva_url = get_post_meta($post->ID, 'resurva_url', true);
		$menuOption = false;
		$menuTitle = '';
		$menuImg = '';
		$menuMeta = get_post_meta($post->ID, 'menu_listing', true);
		if(!empty($menuMeta)){
			$menuTitle = $menuMeta['menu-title'];
			$menuImg = $menuMeta['menu-img'];
			$menuOption = true;
		}

		$timekit = false;
		$timekit_booking = get_post_meta($post->ID, 'timekit_booking', true);

		if(!empty($timekit_booking)){
			$timekitAPP = $timekit_booking['timekit-app'];
			$timekitAPI = $timekit_booking['timekit-api-token'];
			$timekitListing = $timekit_booking['listing_id'];
			$timekitName = $timekit_booking['timekit_name'];
			$timekitEmail = $timekit_booking['timekit_email'];
			$timekit = true;
		}



		/* get user meta */
		$user_id=$post->post_author;
		$user_facebook = '';
		$user_linkedin = '';
		$user_clinkedin = '';
		$user_facebook = '';
		$user_instagram = '';
		$user_twitter = '';
		$user_pinterest = '';
		$user_cpinterest = '';

		$user_facebook = get_the_author_meta('facebook', $user_id);
		$user_google = get_the_author_meta('google', $user_id);
		$user_linkedin = get_the_author_meta('linkedin', $user_id);
		$user_instagram = get_the_author_meta('instagram', $user_id);
		$user_twitter = get_the_author_meta('twitter', $user_id);
		$user_pinterest = get_the_author_meta('pinterest', $user_id);
		/* get user meta */
		?>
		<!--==================================Section Open=================================-->
		<section class="aliceblue listing-second-view">
			<!--=======Galerry=====-->
			<?php
			$lp_detail_slider_styles = $listingpro_options['lp_detail_slider_styles'];
			$IDs = get_post_meta( $post->ID, 'gallery_image_ids', true );
			if($lp_detail_slider_styles == 'style1'){
				if (!empty($IDs)) {
					if($gallery_show=="true"){
						$imgIDs = explode(',',$IDs);
						$numImages = count($imgIDs);
						if($numImages >= 1 ){ ?>
							<div class="pos-relative">
								<div class="spinner">
								  <div class="double-bounce1"></div>
								  <div class="double-bounce2"></div>
								</div>
								<div class="single-page-slider-container style1">
									<div class="row">
										<div class="">
											<div class="listing-slide img_<?php echo esc_attr($numImages); ?>" data-images-num="<?php echo esc_attr($numImages); ?>">
												<?php
													//$imgSize = 'listingpro-gal';
													require_once (THEME_PATH . "/include/aq_resizer.php");
													$imgSize = 'listingpro-detail_gallery';

													foreach($imgIDs as $imgID){

														if($numImages == 3){
															$img_url = wp_get_attachment_image_src( $imgID, 'full');
															$imgurl = aq_resize( $img_url[0], '550', '420', true, true, true);
															$imgSrc = $imgurl;
														}elseif($numImages == 2){
															$img_url = wp_get_attachment_image_src( $imgID, 'full');
															$imgurl = aq_resize( $img_url[0], '800', '400', true, true, true);
															$imgSrc = $imgurl;
														}elseif($numImages == 1){
															$img_url = wp_get_attachment_image_src( $imgID, 'full');
															$imgurl = aq_resize( $img_url[0], '1170', '400', true, true, true);
															$imgSrc = $imgurl;
														}elseif($numImages == 4){
															$img_url = wp_get_attachment_image_src( $imgID, 'full');
															$imgurl = aq_resize( $img_url[0], '400', '400', true, true, true);
															$imgSrc = $imgurl;
														}else {
															/* $imgurl = wp_get_attachment_image_src( $imgID, $imgSize);
															$imgSrc = $imgurl[0]; */
															$img_url = wp_get_attachment_image_src( $imgID, 'full');
															$imgurl = aq_resize( $img_url[0], '350', '450', true, true, true);
															$imgSrc = $imgurl;
														}
														$imgFull = wp_get_attachment_image_src( $imgID, 'full');
														if(!empty($imgurl[0])){
															echo '
															<div class="slide">
																<a href="'. $imgFull[0] .'" rel="prettyPhoto[gallery1]">
																	<img src="'. $imgSrc .'" alt="post1" />
																</a>
															</div>';
														}
													}
												?>
											</div>
										</div>
									</div>
								</div>
							</div>
							<?php
						} else{
							$imgurl = wp_get_attachment_image_src( $imgIDs[0], 'listingpro-listing-gallery');
							$imgFull = wp_get_attachment_image_src( $imgID, 'full');
							if(!empty($imgurl[0])){
								echo '
								<div class="slide_ban text-center">
									<a href="'. $imgFull[0] .'" rel="prettyPhoto[gallery1]">
										<img src="'. $imgurl[0] .'" alt="post1" />
									</a>
								</div>';
							}
						}
					}
				}
			} else if($lp_detail_slider_styles == 'style2') {
				if (!empty($IDs)) {
					if($gallery_show=="true"){
						$imgIDs = explode(',',$IDs);
						$numImages = count($imgIDs);
						if($numImages >= 1 ){ ?>
							<div class="pos-relative">
								<div class="spinner">
								  <div class="double-bounce1"></div>
								  <div class="double-bounce2"></div>
								</div>
								<div class="single-page-slider-container style2">
									<div class="row">
										<div class="">
											<div class="listing-slide img_<?php echo esc_attr($numImages); ?>" data-images-num="<?php echo esc_attr($numImages); ?>">
												<?php
													$slider_height = $listingpro_options['slider_height'];
													//$imgSize = 'listingpro-gal';
													require_once (THEME_PATH . "/include/aq_resizer.php");
													$imgSize = 'listingpro-detail_gallery';
													foreach($imgIDs as $imgID){
														$img_url = wp_get_attachment_image_src( $imgID, 'full');
														$imgSrc = $img_url;
														$imgFull = wp_get_attachment_image_src( $imgID, 'full');
														$gstyle= 'style="height:'.$slider_height.'px;object-fit: cover"';
														if(!empty($img_url[0])){
															echo '
															<div class="slide">
																<a href="'. $imgFull[0] .'" rel="prettyPhoto[gallery1]">
																	<img '.$gstyle.' src="'. $img_url[0] .'" alt="post1" />
																</a>
															</div>';
														}
													}
												?>
											</div>
										</div>
									</div>
								</div>
							</div>
						<?php
						}
						else{
							$imgurl = wp_get_attachment_image_src( $imgIDs[0], 'listingpro-listing-gallery');
							$imgFull = wp_get_attachment_image_src( $imgID, 'full');
							if(!empty($imgurl[0])){
								echo '
								<div class="slide_ban text-center">
									<a href="'. $imgFull[0] .'" rel="prettyPhoto[gallery1]">
										<img src="'. $imgurl[0] .'" alt="post1" />
									</a>
								</div>';
							}
						}
					}
				}
			}
			?>
			<div class="post-meta-info">
				<div class="container">
					<div class="row">
						<div class="col-md-8 col-sm-8 col-xs-12">
							<div class="post-meta-left-box">
								<?php if (function_exists('listingpro_breadcrumbs')) listingpro_breadcrumbs(); ?>
								<h1><?php the_title(); ?> <?php echo $claim; ?></h1>
								<?php if(!empty($tagline_text)) {
											if($tagline_show=="true"){?>
											<p><?php echo $tagline_text; ?></p>
									<?php } ?>
								<?php } ?>
							</div>
						</div>
						<div class="col-md-4 col-sm-4 col-xs-12">
							<div class="post-meta-right-box text-right clearfix margin-top-20">
								<ul class="post-stat">
									<li id="fav-container">
										<a class="email-address add-to-fav" data-post-type="detail" href="" data-post-id="<?php echo get_the_ID(); ?>" data-success-text="<?php echo esc_html__('Saved', 'listingpro') ?>">
											<i class="fa <?php echo listingpro_is_favourite(get_the_ID(),$onlyicon=true); ?>"></i>
											<span class="email-icon">
												<?php echo listingpro_is_favourite(get_the_ID(),$onlyicon=false); ?>
											</span>

										</a>
									</li>
									<li class="reviews sbutton">
										<?php listingpro_sharing(); ?>
									</li>
								</ul>
								<div class="padding-top-30">
								<span class="rating-section">
									<?php
										$NumberRating = listingpro_ratings_numbers($post->ID);
										if($NumberRating != 0){
											echo lp_cal_listing_rate(get_the_ID());
									?>
											<span>
												<small><?php echo $NumberRating; ?></small>
												<br>
												<?php echo esc_html__('Ratings', 'listingpro'); ?>
											</span>
									<?php
										}else{
											echo lp_cal_listing_rate(get_the_ID());
										}
									?>
								</span>
									<?php if(!empty($resurva_url)){ ?>
											<a href="" class="secondary-btn make-reservation">
												<i class="fa fa-calendar-check-o"></i>
												<?php echo esc_html__('Book Now', 'listingpro'); ?>
											</a>
											<div class="ifram-reservation">
												<div class="inner-reservations">
													<a href="#" class="close-btn"><i class="fa fa-times"></i></a>
													<iframe src="<?php echo esc_url($resurva_url); ?>" name="resurva-frame" frameborder="0"></iframe>
												</div>
											</div>
									<?php }else{
												if (class_exists('ListingReviews')) {
													$allowedReviews = $listingpro_options['lp_review_switch'];
													if(!empty($allowedReviews) && $allowedReviews=="1"){
														if(get_post_status($post->ID)=="publish"){

											?>
													<a href="#reply-title" class="secondary-btn" id="clicktoreview">
														<i class="fa fa-star"></i>
														<?php echo esc_html__('Submit Review', 'listingpro'); ?>
													</a>
											<?php
													}
													}
												}
											}
									?>


								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="content-white-area">
				<div class="container single-inner-container single_listing" >
					<div class="row">
						<div class="col-md-8 col-sm-8 col-xs-12">
							<?php
							$video = listing_get_metabox('video');
							if(!empty($video))
							{
								if($video_show=="true")
								{
							?>
										<div class="video-option  margin-bottom-30">
											<h2>
												<span><i class="fa fa-play-circle-o"></i></span>
												<span><?php echo esc_html__('Checkout', 'listingpro'); ?></span>
												<?php echo get_the_title(); ?>
												<a href="<?php echo esc_url($video); ?>" class="watch-video popup-youtube">
													<?php echo esc_html__('Watch Video', 'listingpro'); ?>
												</a>
											</h2>
										</div>
										<!-- <img src="<?php //echo get_template_directory_uri(); ?>/assets/images/play-video.png" alt="">
										<div class="widget-video widget-box widget-bg-color lp-border-radius-5">

										<?php if(wp_oembed_get( $video )){?>
											<?php echo wp_oembed_get($video); ?>
										<?php }else{ ?>
											<?php echo wp_kses_post($video); ?>
										<?php } ?>
										</div> -->
								<?php } ?>
							<?php } ?>

							<?php
								$listingContent = get_the_content();
								if ( $listingContent!=="" ) {
							?>
								<div class="post-row">
									<div class="post-detail-content">
										<?php the_content(); ?>
									</div>
								</div>
							<?php
								}
							?>
							<?php
							$tags = get_the_terms( $post->ID ,'features');
							if(!empty($tags)){
								if($tags_show=="true"){?>
									<div class="post-row padding-top-20">
										<!-- <div class="post-row-header clearfix margin-bottom-15"><h3><?php echo esc_html__('Features', 'listingpro'); ?></h3></div> -->
										<ul class="features list-style-none clearfix">
											<?php
												foreach($tags as $tag) {
													$icon = listingpro_get_term_meta( $tag->term_id ,'lp_features_icon');
													?>
												<li>
													<a href="<?php echo get_term_link($tag); ?>" class="parimary-link">
														<span class="tick-icon">
															<?php if(!empty($icon)) { ?>
																<i class="fa <?php echo esc_attr($icon); ?>"></i>
															<?php }else { ?>
																<i class="fa fa-check"></i>
															<?php } ?>
														</span>
														<?php echo esc_html($tag->name); ?>
													</a>
												</li>
											<?php } ?>
										</ul>
									</div>
								<?php } ?>
							<?php } ?>
							<?php
								$faqs = listing_get_metabox('faqs');

								if($faqs_show=="true"){
									if( !empty($faqs) && count($faqs)>0 ){
										$faq = $faqs['faq'];
										$faqans = $faqs['faqans'];
										if( !empty($faq[1])){
										?>
										<div class="post-row faq-section padding-top-10 clearfix">
											<!-- <div class="post-row-header clearfix margin-bottom-15">
												<h3><?php echo esc_html__('Quick questions', 'listingpro'); ?></h3>
											</div> -->
											<div class="post-row-accordion">
												<div id="accordion">
													<?php for ($i = 1; $i <= (count($faq)); $i++) { ?>
														<?php if( !empty($faq[$i])) { ?>
															<h5>
															  <span class="question-icon"><?php echo esc_html__('Q', 'listingpro'); ?></span>
															  <span class="accordion-title"><?php echo esc_html($faq[$i]); ?></span>
															</h5>
															<div>
																<p>
																	<?php //echo do_shortcode($faqans[$i]); ?>
																	<?php echo nl2br(do_shortcode($faqans[$i]), false); ?>
																</p>
															</div><!-- accordion tab -->
														<?php } ?>
													<?php } ?>
												</div>
											</div>
										</div>
										<?php
										}
									}
								}
							 ?>
							<div id="submitreview">
								<?php
									//getting old reviews
									listingpro_get_all_reviews($post->ID);
								?>
							</div>
							<?php
								//comments_template();

								$allowedReviews = $listingpro_options['lp_review_switch'];
								if(!empty($allowedReviews) && $allowedReviews=="1"){
									if(get_post_status($post->ID)=="publish"){
										listingpro_get_reviews_form($post->ID);
									}
								}

							?>
						</div>
						<div class="col-md-4 col-sm-4 col-xs-12">
							<div class="sidebar-post">
								<?php if(!empty($timekit_booking) && $timekit == true){ ?>
								<div class="widget-box">
									<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/assets/js/booking.js"></script>
								  	<div id="bookingjs1">
										<script type="text/javascript">
										  var widget1 = new TimekitBooking();
										  widget1.init({
											targetEl: '#bookingjs1',
											name:     '<?php echo $timekitName; ?>',
											email:    '<?php echo $timekitEmail; ?>',
											apiToken: '<?php echo $timekitAPI; ?>',
											calendar: '22f86f0c-ee80-470c-95e8-dadd9d05edd2',
											timekitConfig: {
											  app: '<?php echo $timekitAPP; ?>'
											}

										  });
										</script>
								  	</div>
								</div>
								<?php } ?>
								<?php
									$buisness_hours = listing_get_metabox('business_hours');
									if(!empty($buisness_hours)){
										if($hours_show=="true"){
								?>
									<div class="widget-box">
										<?php get_template_part( 'include/timings' ); ?>
									</div>
									<?php
										}
									}
									?>


								<div class="widget-box map-area">
									<?php
									$latitude = listing_get_metabox('latitude');
									$longitude = listing_get_metabox('longitude');
									
									$latitude_custom = listing_get_metabox('latitude_custom');
									$longitude_custom = listing_get_metabox('longitude_custom');
									
									$gAddress = listing_get_metabox('gAddress');
									$gAddress_custom = listing_get_metabox('gAddress_custom');
									
									if(!empty($latitude) && !empty($longitude)){
										if($map_show=="true"){
									?>
												<div class="widget-bg-color post-author-box lp-border-radius-5">
													<div class="widget-header margin-bottom-25 hideonmobile">
														<ul class="post-stat">
															<li>
																<a class="md-trigger parimary-link singlebigmaptrigger" data-lat="<?php echo esc_attr($latitude); ?>" data-lan="<?php echo esc_attr($longitude); ?>" data-latt="<?php echo esc_attr($latitude_custom); ?>" data-lann="<?php echo esc_attr($longitude_custom); ?>" data-gadd="<?php echo esc_attr($gAddress); ?>" data-gadds="<?php echo esc_attr($gAddress_custom); ?>" data-modal="modal-4" >
																	<!-- <span class="phone-icon">
																		Marker icon by Icons8
																		<?php echo listingpro_icons('mapMarker'); ?>
																	</span>
																	<span class="phone-number ">
																		<?php echo esc_html__('View Large Map', 'listingpro'); ?>
																	</span> -->
																</a>
															</li>
														</ul>
													</div>
													<?php
													$lp_map_pin = $listingpro_options['lp_map_pin']['url'];
													?>
													<div class="widget-content ">
														<div class="widget-map pos-relative">
															<div id="singlepostmap" class="singlemap" data-pinicon="<?php echo esc_attr($lp_map_pin); ?>"></div>
															<div class="get-directions">
																<a href="https://www.google.com/maps?daddr=<?php echo esc_attr($latitude); ?>,<?php echo esc_attr($longitude); ?>" target="_blank" >
																	<span class="phone-icon">
																		<i class="fa fa-map-o"></i>
																	</span>
																	<span class="phone-number ">
																		<?php echo esc_html__('Get Directions', 'listingpro'); ?>
																	</span>
																</a>
															</div>
														</div>
													</div>
												</div><!-- ../widget-box  -->
										<?php } ?>
									<?php } ?>
									<div class="listing-detail-infos margin-top-20 clearfix">
										<ul class="list-style-none list-st-img clearfix">
											<?php
											
											$phone = listing_get_metabox('phone');
											$website = listing_get_metabox('website');
											//if(empty($facebook) && empty($twitter) && empty($linkedin)){}else{
												?>
												<?php if(!empty($gAddress)) {
													if($location_show=="true"){?>
														<li>
															<a>
																<span class="cat-icon">
																	<?php echo listingpro_icons('mapMarkerGrey'); ?>
																	<!-- <i class="fa fa-map-marker"></i> -->
																</span>
																<span>
																	<?php echo $gAddress ?>
																</span>
															</a>
														</li>
													<?php } ?>
												<?php } ?>
												<?php if(!empty($phone)) { ?>
													<?php if($contact_show=="true"){ ?>
														<li class="lp-listing-phone">
															<a data-lpID="<?php echo get_the_ID(); ?>" href="tel:<?php echo esc_attr($phone); ?>">
																<span class="cat-icon">
																	<?php echo listingpro_icons('phone'); ?>
																	<!-- <i class="fa fa-mobile"></i> -->
																</span>
																<span>
																	<?php echo esc_html($phone); ?>
																</span>
															</a>
														</li>
													<?php } ?>
												<?php } ?>
												<?php if(!empty($website)) {
														if($website_show=="true"){?>
															<li class="lp-user-web">
																<a data-lpID="<?php echo get_the_ID(); ?>" href="<?php echo esc_url($website); ?>" target="_blank">
																	<span class="cat-icon">
																		<?php echo listingpro_icons('globe'); ?>
																		<!-- <i class="fa fa-globe"></i> -->
																	</span>
																	<span><?php echo esc_url($website); ?></span>
																</a>
															</li>
														<?php } ?>
												<?php } ?>
											<?php //} ?>
										</ul>
										<?php
										$facebook = listing_get_metabox('facebook');
										$twitter = listing_get_metabox('twitter');
										$linkedin = listing_get_metabox('linkedin');
										$google_plus = listing_get_metabox('google_plus');
										$youtube = listing_get_metabox('youtube');
										$instagram = listing_get_metabox('instagram');
										if($social_show=="true"){
											if(empty($facebook) && empty($twitter) && empty($linkedin) && empty($google_plus) && empty($youtube) && empty($instagram)){}else{
												?>
												<div class="widget-box widget-social">
													<div class="widget-content clearfix">
														<ul class="list-style-none list-st-img">
															<?php if(!empty($facebook)){ ?>
																<li class="lp-fb">
																	<a href="<?php echo esc_url($facebook); ?>" class="padding-left-0" target="_blank">
																		<!-- <i class="fa fa-facebook"></i> -->
																		<?php echo listingpro_icons('fb'); ?>
																	</a>
																</li>
															<?php } ?>
															<?php if(!empty($twitter)){ ?>
																<li class="lp-tw">
																	<a href="<?php echo esc_url($twitter); ?>" class="padding-left-0" target="_blank">
																		<!-- <i class="fa fa-twitter"></i> -->
																		<?php echo listingpro_icons('tw'); ?>
																	</a>
																</li>
															<?php } ?>
															<?php if(!empty($linkedin)){ ?>
																<li  class="lp-li">
																	<a href="<?php echo esc_url($linkedin); ?>" class="padding-left-0" target="_blank">
																		<!-- <i class="fa fa-linkedin"></i> -->
																		<?php echo listingpro_icons('lnk'); ?>
																	</a>
																</li>
															<?php } ?>
															<?php if(!empty($google_plus)){ ?>
																<li  class="lp-li">
																	<a href="<?php echo esc_url($google_plus); ?>#" class="padding-left-0" target="_blank">
																		<!-- <i class="fa fa-linkedin"></i> -->
																		<?php echo listingpro_icons('gp'); ?>
																	</a>
																</li>
															<?php } ?>
															<?php if(!empty($youtube)){ ?>
																<li  class="lp-li">
																	<a href="<?php echo esc_url($youtube); ?>#" class="padding-left-0" target="_blank">
																		<!-- <i class="fa fa-linkedin"></i> -->
																		<?php echo listingpro_icons('yt'); ?>
																	</a>
																</li>
															<?php } ?>
															<?php if(!empty($instagram)){ ?>
																<li  class="lp-li">
																	<a href="<?php echo esc_url($instagram); ?>#" class="padding-left-0" target="_blank">
																		<!-- <i class="fa fa-linkedin"></i> -->
																		<?php echo listingpro_icons('insta'); ?>
																	</a>
																</li>
															<?php } ?>
														</ul>
													</div>
												</div><!-- ../widget-box  -->
											<?php } ?>
									<?php } ?>
									</div>
								</div>
								<?php
								$claimed_section = listing_get_metabox('claimed_section');
								$priceRange = listing_get_metabox_by_ID('price_status', get_the_ID());
								$listingpTo = listing_get_metabox('list_price_to');
								$listingprice = listing_get_metabox_by_ID('list_price', get_the_ID());
								$showClaim = true;
								if(isset($listingpro_options['lp_listing_claim_switch'])){
									if($listingpro_options['lp_listing_claim_switch']==1){
										$showClaim = true;
									}else{
										$showClaim = false;
									}
								}
								else{
									$showClaim = false;
								}
								$listingpricestatus = listing_get_metabox_by_ID('price_status', get_the_ID());

								?>
								<?php if( (!empty($menuMeta) && $menuOption == true ) || !empty($listingpTo) || !empty($listingprice) ||  ($showClaim==true && $claimed_section == 'not_claimed') || $listingpricestatus!="notsay" ) { ?>
									<div class="widget-box listing-price">
										<?php
											if(!empty($menuMeta) && $menuOption == true){
										?>
											<div class="menu-hotel">
												<a href="#" class="open-modal">
													<?php echo listingpro_icons('resMenu'); ?>
													<span>
														<?php if(!empty($menuTitle)){ echo $menuTitle; }else{ echo esc_html__('See Full Menu','listingpro'); } ?>
													</span>
												</a>
												<div class="hotel-menu">
													<div class="inner-menu">
														<a href="#" class="close-menu-popup"><i class="fa fa-times"></i></a>
														<img src="<?php echo esc_url($menuImg); ?>" alt="">
													</div>
												</div>
											</div>
											<?php } ?>

										<div class="price-area">

											<?php
												if($price_show=="true"){
													echo listingpro_price_dynesty(get_the_ID());
												}
											 ?>
											<?php get_template_part('templates/single-list/claimed' ); ?>

										</div>
										<?php get_template_part('templates/single-list/claim-form' ); ?>
									</div>
								<?php } ?>
								<?php echo listing_all_extra_fields($post->ID); ?>
								<?php
									$lp_leadForm = $listingpro_options['lp_lead_form_switch'];
									if($lp_leadForm=="1"){
										$claimed_section = listing_get_metabox('claimed_section');
										$show_leadform_only_claimed = $listingpro_options['lp_lead_form_switch_claim'];
										$showleadform = true;
										if($show_leadform_only_claimed== true){
											if($claimed_section == 'claimed') {
												$showleadform = true;
											}
											else{
												$showleadform = false;
											}
										}

										if($showleadform == true) {
								?>
										<div class="widget-box business-contact">
											<div class="contact-form quickform">
												<div class="user_text">
													<?php
													// ************** Matteo
													//$author_avatar_url = get_user_meta(get_the_author_meta( 'ID' ), "listingpro_author_img_url", true);
													$author_avatar_url = get_avatar_url( $user_id );
													// ***********end Matteo
													$avatar ='';
													if(!empty($author_avatar_url)) {
														$avatar =  $author_avatar_url;

													} else {
														// ************** Matteo
														//$avatar_url = listingpro_get_avatar_url (get_the_author_meta( 'ID' ), $size = '94' );
														$get_author_id = get_the_author_meta('ID');
														$avatar_url = get_avatar( get_the_author_meta( 'ID' ), $get_author_id );
														//echo('<!-- Matteo *** '.$avatar_url.'*** -->');
														// ***********end Matteo

														$avatar =  $avatar_url;

													}
												?>
													<div class="author-img">
														<img src="<?php echo esc_url($avatar); ?>" alt="">
													</div>
													<div class="author-social">
														<div class="status">
														<?php
															$u_display_name = get_the_author_meta('display_name');
															if(empty($u_display_name)){
																$u_display_name = get_the_author_meta('nickname');
															}
														?>
															<span class="online"><a><?php echo $u_display_name; ?></a></span>
														</div>
														<ul class="social-icons post-socials">
															<?php if(!empty($user_facebook)) { ?>
															<li>
																<a href="<?php echo esc_url($user_facebook); ?>">
																	<?php echo listingpro_icons('fbGrey'); ?>
																</a>
															</li>
															<?php } if(!empty($user_google)) { ?>
															<li>
																<a href="<?php echo esc_url($user_google); ?>">
																	<?php echo listingpro_icons('googleGrey'); ?>
																</a>
															</li>
															<?php } if(!empty($user_instagram)) { ?>
															<li>
																<a href="<?php echo esc_url($user_instagram); ?>">
																	<?php echo listingpro_icons('instaGrey'); ?>
																</a>
															</li>
															<?php } if(!empty($user_twitter)) { ?>
															<li>
																<a href="<?php echo esc_url($user_twitter); ?>">
																	<?php echo listingpro_icons('tmblrGrey'); ?>
																</a>
															</li>
															<?php } if(!empty($user_linkedin)) { ?>
															<li>
																<a href="<?php echo esc_url($user_linkedin); ?>">
																	<?php echo listingpro_icons('clinkedin'); ?>
																</a>
															</li>
															<?php } if(!empty($user_pinterest)) { ?>
															<li>
																<a href="<?php echo esc_url($user_pinterest); ?>">
																	<?php echo listingpro_icons('cinterest'); ?>
																</a>
															</li>
															<?php } ?>
														</ul>
													</div>
												</div>

													<form class="form-horizontal"  method="post" id="contactOwner">
														<?php

														$author_id = '';
														$author_email = '';
														$author_email = get_the_author_meta( 'user_email' );
														$author_id = get_the_author_meta( 'ID' );
														$gSiteKey = '';
														$gSiteKey = $listingpro_options['lp_recaptcha_site_key'];
														$enableCaptcha = lp_check_receptcha('lp_recaptcha_lead');

														?>
														<div class="form-group">
															<input type="text" class="form-control" name="name7" id="name7" placeholder="<?php esc_html_e('Name:','listingpro'); ?>">
														</div>
														<div class="form-group">
															<input type="email" class="form-control" name="email7" id="email7" placeholder="<?php esc_html_e('Email:','listingpro'); ?>">
														</div>
														<div class="form-group">
															<input type="text" class="form-control" name="phone" id="phone" placeholder="<?php esc_html_e('Phone','listingpro'); ?>">
														</div>
														<div class="form-group">
															<textarea class="form-control" rows="5" name="message7" id="message7" placeholder="<?php esc_html_e('Message:','listingpro'); ?>"></textarea>
														</div>
														<div class="form-group">
														<?php
															if($enableCaptcha==true){
																if ( class_exists( 'cridio_Recaptcha' ) ){
																	if ( cridio_Recaptcha_Logic::is_recaptcha_enabled() ) {
																	echo  '<div id="recaptcha-'.get_the_ID().'" class="g-recaptcha" data-sitekey="'.$gSiteKey.'"></div>';
																	}
																}
															}

														?>

														</div>
														<div class="form-group margin-bottom-0 pos-relative">
															<input type="submit" value="<?php esc_html_e('Send','listingpro'); ?>" class="lp-review-btn btn-second-hover">
															<input type="hidden" value="<?php the_ID(); ?>" name="post_id">
															<input type="hidden" value="<?php echo esc_attr($author_email); ?>" name="author_email">
															<input type="hidden" value="<?php echo esc_attr($author_id); ?>" name="author_id">
															<i class="lp-search-icon fa fa-send"></i>
														</div>
													</form>
											</div>
										</div>
								<?php } ?>
								<?php } ?>
								<?php if($post->post_status == 'publish'){ ?>
									<?php if(is_active_sidebar('listing_detail_sidebar')) { ?>
										<div class="sidebar">
											<?php dynamic_sidebar('listing_detail_sidebar'); ?>
										</div>
									<?php } ?>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!--==================================Section Close=================================-->
		<?php
		global $post;
		echo listingpro_post_confirmation($post);
	} // end while
}

<?php

					$type = 'listing';
					$term_id = '';
					$taxName = '';
					$termID = '';
					$term_ID = '';
					global $paged, $listingpro_options;
					
					
					$taxTaxDisplay = true;
					$TxQuery = '';
					$tagQuery = '';
					$catQuery = '';
					$locQuery = '';
					$taxQuery = '';
					$searchQuery = '';
					$sKeyword = '';
					$priceQuery = '';
					$postsonpage = '';
					if(isset($listingpro_options['listing_per_page'])){
						$postsonpage = $listingpro_options['listing_per_page'];
					}
					else{
						$postsonpage = 10;
					}
					
					
					if( !empty($_GET['s']) && isset($_GET['s']) && $_GET['s']=="home" ){
						if( !empty($_GET['lp_s_tag']) && isset($_GET['lp_s_tag'])){
							$lpsTag = sanitize_text_field($_GET['lp_s_tag']);
							$tagQuery = array(
								'taxonomy' => 'list-tags',
								'field' => 'id',
								'terms' => $lpsTag,
								'operator'=> 'IN' //Or 'AND' or 'NOT IN'
							);
						}
						
						if( !empty($_GET['lp_s_cat']) && isset($_GET['lp_s_cat'])){
							$lpsCat = sanitize_text_field($_GET['lp_s_cat']);
							$catQuery = array(
								'taxonomy' => 'listing-category',
								'field' => 'id',
								'terms' => $lpsCat,
								'operator'=> 'IN' //Or 'AND' or 'NOT IN'
							);
							$taxName = 'listing-category';
						}
						
						if( !empty($_GET['lp_s_loc']) && isset($_GET['lp_s_loc'])){							
							$lpsLoc = sanitize_text_field($_GET['lp_s_loc']);
							if(is_numeric($lpsLoc)){
								$lpsLoc = $lpsLoc;
							}
							else{
								$term = listingpro_term_exist($lpsLoc,'location');
								if(!empty($term)){
									$lpsLoc=$term['term_id'];
								}
								else{
									$lpsLoc = '';
								}
							}
							$locQuery = array(
								'taxonomy' => 'location',
								'field' => 'id',
								'terms' => $lpsLoc,
								'operator'=> 'IN' //Or 'AND' or 'NOT IN'
							);
						}
						/* on 3 april by zaheer */
						if( empty($_GET['lp_s_tag']) && empty($_GET['lp_s_cat']) && !empty($_GET['select']) ){
							
							$sKeyword = sanitize_text_field($_GET['select']);
							
						}
						if( empty($_GET['lp_s_loc']) && empty($_GET['lp_s_tag']) && empty($_GET['lp_s_cat']) && empty($_GET['select']) ){
							//$postsonpage = 25;
						}
						/* end on 3 april by zaheer */
						$TxQuery = array(
							'relation' => 'AND',
							$tagQuery,
							$catQuery,
							$locQuery,
						);
					$ad_campaignsIDS = listingpro_get_campaigns_listing( 'lp_top_in_search_page_ads', TRUE,$taxQuery,$TxQuery,$priceQuery,$sKeyword, null, null);	
					}
					else{
						$queried_object = get_queried_object();
						$term_id = $queried_object->term_id;
						$taxName = $queried_object->taxonomy;
						if(!empty($term_id)){
							$termID = get_term_by('id', $term_id, $taxName);
							$termName = $termID->name;
							$term_ID = $termID->term_id;
						}
						
						$TxQuery = array(
							array(
								'taxonomy' => $taxName,
								'field' => 'id',
								'terms' => $termID->term_id,
								'operator'=> 'IN' //Or 'AND' or 'NOT IN'
							),
						);
					$ad_campaignsIDS = listingpro_get_campaigns_listing( 'lp_top_in_search_page_ads', TRUE, $TxQuery,$searchQuery,$priceQuery,$sKeyword, null, null );
					}
					
					

					
					
					$args=array(
						'post_type' => $type,
						'post_status' => 'publish',
						'posts_per_page' => $postsonpage,
						's'	=> $sKeyword,
						'paged'  => $paged,
						'post__not_in' =>$ad_campaignsIDS,
						'tax_query' => $TxQuery				
					);
					
					$my_query = null;
					$my_query = new WP_Query($args);
					$found = $my_query->found_posts;
					if(($found > 1)){
						$foundtext = esc_html__('Results', 'listingpro');
					}else{
						$foundtext = esc_html__('Result', 'listingpro');
					}
					// Harry Code

					$listing_layout = $listingpro_options['listing_views'];
					$addClassListing = '';
					if($listing_layout == 'list_view') {
						$addClassListing = 'listing_list_view';
					}
?>

	<!--==================================Section Open=================================-->
	<section class="page-container clearfix section-fixed listing-with-map pos-relative taxonomy" id="<?php echo esc_attr($taxName); ?>">
			<div class="sidemap-container pull-right sidemap-fixed">
				<div class="map-pop map-container3" id="map-section">
					<div id='map' class="mapSidebar"></div>
				</div>
				<a href="#" class="open-img-view"><i class="fa fa-file-image-o"></i></a>
			</div>
			<div class="all-list-map"></div>
			<div class=" pull-left post-with-map-container-right">
				<div class="post-with-map-container pull-left">				
					<div class="margin-bottom-20 margin-top-30">
						<?php get_template_part( 'templates/search/filter'); ?>
					</div>
					<div class="content-grids-wraps">
						<div class="clearfix lp-list-page-grid" id="content-grids" >						
							<?php
								$array['features'] = '';
								?> 
								<div class="promoted-listings">
									<?php
									if( !empty($_GET['s']) && isset($_GET['s']) && $_GET['s']=="home" ){
										echo listingpro_get_campaigns_listing( 'lp_top_in_search_page_ads', false,$taxQuery,$TxQuery,$priceQuery,$sKeyword, null, null);
									}else{
										echo listingpro_get_campaigns_listing( 'lp_top_in_search_page_ads', false, $TxQuery,$searchQuery,$priceQuery,$sKeyword, null, null);
									}
									?> 
								<div class="md-overlay"></div>
								</div>
								<?php
								if( $my_query->have_posts() ) {
									while ($my_query->have_posts()) : $my_query->the_post();  
										get_template_part( 'listing-loop' );							
									endwhile;
									wp_reset_query();
								}elseif(empty($ad_campaignsIDS)){
									?>						
										<div class="text-center margin-top-80 margin-bottom-80">
											<h2><?php esc_html_e('No Results','listingpro'); ?></h2>
											<p><?php esc_html_e('Sorry! There are no listings matching your search.','listingpro'); ?></p>
											<p><?php esc_html_e('Try changing your search filters or','listingpro'); ?>
											<?php
											$currentURL = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
											?>
												<a href="<?php echo esc_url($currentURL); ?>"><?php esc_html_e('Reset Filter','listingpro'); ?></a>
											</p>
										</div>									
									<?php
								}	
								
							?>
						<div class="md-overlay"></div>
						</div>
					</div>
				
				<?php 
						//echo '<div id="lp-pages-in-cats">';
						//echo listingpro_load_more_filter($my_query, '1', $sKeyword);
						//echo '</div>';
				 ?>
				<div class="lp-pagination pagination lp-filter-pagination-ajx"></div>
				</div>
				<input type="hidden" id="lp_current_query" value="<?php echo $sKeyword ?>">
			</div>
	</section>
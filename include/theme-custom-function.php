<?php
add_action( 'admin_enqueue_scripts', 'theme_enqueue_admin_js' );
	function theme_enqueue_admin_js(){     
		wp_enqueue_script( 'script', get_stylesheet_directory_uri() . '/js/admin-script.js', array ( 'jquery' ), 1.1, true);
    }
function themeslug_enqueue_script() {
	wp_enqueue_script( 'script', get_stylesheet_directory_uri() . '/js/theme-script.js', array ( 'jquery' ), 1.1, true);
	wp_enqueue_script('load-ajax-script');
	wp_localize_script('load-ajax-script', 'ajax_load_term_object', array(
		'ajaxurl' => admin_url('admin-ajax.php')
	));
}
add_action( 'wp_enqueue_scripts', 'themeslug_enqueue_script' );

/* ============== ListingPro Search Filter========== */
add_action('wp_ajax_ajax_load_tags', 'ajax_load_tags');
add_action('wp_ajax_nopriv_ajax_load_tags', 'ajax_load_tags');
if (!function_exists('ajax_load_tags')) {
    function ajax_load_tags()
    {
        global $listingpro_options;
		$pageno           = '';
        if (isset($_POST['pageno'])) {
            $pageno = $_POST['pageno'];
        }
		$squery = '';
		if(isset($_POST['skeywork'])){
			$squery               = sanitize_text_field($_POST['skeywork']);
		}
		
        $info[]                   = '';
        $info['tag_name']         = $_POST['tag_name'];
        $info['cat_id']           = sanitize_text_field($_POST['cat_id']);
		
		if( is_numeric($_POST['loc_id'] ) ){
			$info['loc_id']    = sanitize_text_field($_POST['loc_id']);
		}
		else{
			$locTerm = get_term_by('name', $_POST['loc_id'], 'location');
			$loc_ID = $locTerm->term_id;
			$info['loc_id']           = $loc_ID;
		}
        
        $info['listStyle']        = sanitize_text_field($_POST['list_style']);
        $info['inexpensive']      = sanitize_text_field($_POST['inexpensive']);
        $info['moderate']         = sanitize_text_field($_POST['moderate']);
        $info['pricey']           = sanitize_text_field($_POST['pricey']);
        $info['ultra']            = sanitize_text_field($_POST['ultra']);
        $info['averageRate']      = sanitize_text_field($_POST['averageRate']);
        $info['mostRewvied']      = sanitize_text_field($_POST['mostRewvied']);
        $info['listing_openTime'] = sanitize_text_field($_POST['listing_openTime']);
        $tagQuery                 = '';
        $catQuery                 = '';
        $listing_time             = '';
        $sFeatures                = '';
        $sFeatures                = $_POST['tag_name'];
        if (!empty($info['listing_openTime'])) {
            $listing_time = $info['listing_openTime'];
        }
		global $paged;
        if (!empty($pageno)) {
            $paged = $pageno;
        }
        $priceQuery   = array();
        $categoryName = '';
        $LocationName = '';
        $locQuery     = '';
        $currentTax   = '';
        if (!empty($info['tag_name'])) {
            $tagQuery = array(
                'taxonomy' => 'features',
                'field' => 'id',
                'terms' => $info['tag_name']
            );
        }
        if (!empty($info['cat_id'])) {
            $categoryName = get_term_by('id', $info['cat_id'], 'listing-category');
            $categoryName = $categoryName->name;
            $catQuery     = array(
                'taxonomy' => 'listing-category',
                'field' => 'id',
                'terms' => $info['cat_id']
            );
        }
        if (!empty($info['loc_id'])) {
            $LocationName = get_term_by('id', $info['loc_id'], 'location');
            $LocationName = $LocationName->name;
            $locQuery     = array(
                'taxonomy' => 'location',
                'field' => 'id',
                'terms' => $info['loc_id']
            );
        }
        /* added by zaheer on 13 march */
        $orderBy        = '';
        $rateArray      = '';
        $reviewedArray  = '';
        $statusArray    = array();
        $optenTimeArray = array();
        $relation       = 'OR';
        if (!empty($info['averageRate'])) {
            $orderBy   = 'meta_value_num';
            $rateArray = array(
                'key' => $info['averageRate'],
                'compare' => 'EXIST'
            );
        }
        if (!empty($info['mostRewvied'])) {
            $orderBy       = 'meta_value_num';
            $reviewedArray = array(
                'key' => $info['mostRewvied'],
                'compare' => 'EXIST'
            );
        }
        if (!empty($info['inexpensive'])) {
            $inexArray = array(
                'key' => 'lp_listingpro_options',
                'value' => 'inexpensive',
                'compare' => 'LIKE'
            );
        }
        if (!empty($info['moderate'])) {
            $moderArray = array(
                'key' => 'lp_listingpro_options',
                'value' => 'moderate',
                'compare' => 'LIKE'
            );
        }
        if (!empty($info['pricey'])) {
            $pricyArray = array(
                'key' => 'lp_listingpro_options',
                'value' => 'pricey',
                'compare' => 'LIKE'
            );
        }
        if (!empty($info['ultra'])) {
            $ultrArray = array(
                'key' => 'lp_listingpro_options',
                'value' => 'ultra_high_end',
                'compare' => 'LIKE'
            );
        }
        if (!empty($info['inexpensive']) || !empty($info['moderate']) || !empty($info['pricey']) || !empty($info['ultra'])) {
            $statusArray = array(
                'key' => 'lp_listingpro_options',
                'value' => 'price_status',
                'compare' => 'LIKE'
            );
            $relation    = "AND";
        }
        if (!empty($info['inexpensive']) || !empty($info['moderate']) || !empty($info['pricey']) || !empty($info['ultra']) || !empty($info['averageRate']) || !empty($info['mostRewvied'])) {
            $priceQuery = array(
                'relation' => $relation, // Optional, defaults to "AND"
                $statusArray,
                array(
                    'relation' => 'OR',
                    $inexArray,
                    $moderArray,
                    $pricyArray,
                    $ultrArray
                ),
                array(
                    'relation' => 'OR',
                    $rateArray,
                    $reviewedArray
                )
            );
        }
		
		$listingperpage = '';
		if(isset($listingpro_options['listing_per_page']) && !empty($listingpro_options['listing_per_page'])){
			$listingperpage = $listingpro_options['listing_per_page'];
		}
		else{
			$listingperpage = 10;
		}
        /* added by zaheer on 13 march */
        $searchQuery = '';
        $TxQuery     = array(
            $tagQuery,
            $catQuery,
            $locQuery
        );
        if (empty($TxQuery)) {
            $TxQuery = array();
        }
        $ad_campaignsIDS = listingpro_get_campaigns_listing('lp_top_in_search_page_ads', TRUE, $TxQuery, $searchQuery, $priceQuery, null, null, null);
        $type            = 'listing';
        $args            = array(
            'post_type' => $type,
            'post_status' => 'publish',
            'posts_per_page' => $listingperpage,
            'paged' => $paged,
            's' => $squery,
            'post__not_in' => $ad_campaignsIDS,
            'orderby' => $orderBy,
            /* 'meta_key'  => $metaKey, */
            'meta_query' => $priceQuery,
            'tax_query' => array(
                $tagQuery,
                $catQuery,
                $locQuery
            )
        );
        $my_query        = null;
        $output          = null;
        $result          = null;
        $found           = null;
        $my_query        = new WP_Query($args);
        $found           = $my_query->found_posts;
        //$output .= '<div class="promoted-listings">';
        ob_start();
        $output .= listingpro_get_campaigns_listing('lp_top_in_search_page_ads', false, $TxQuery, $searchQuery, $priceQuery, $s=null, $noOFListing=null, $ad_campaignsIDS);
        $output .= ob_get_contents();
        ob_end_clean();
		ob_flush();
        //$output .= '</div>';
        if ($my_query->have_posts()) {
            while ($my_query->have_posts()):
                $my_query->the_post();
                if ($listing_time == 'open') {
                    $openStatus = listingpro_check_time(get_the_ID(), true);
                    if ($openStatus == 'open') {
                        ob_start();
                        get_template_part('listing-loop');
                        $htmlOutput .= ob_get_contents();
                        ob_end_clean();
						ob_flush();
                    }
                } else {
                    ob_start();
                    get_template_part('listing-loop');
                    $htmlOutput .= ob_get_contents();
                    ob_end_clean();
					ob_flush();
                }
            endwhile;
            wp_reset_query();
            if (empty($htmlOutput)) {
                $output .= '';
            } else {
                $output .= $htmlOutput;
            }
        } elseif (empty($ad_campaignsIDS)) {
            $output .= '';
        }
        if (($found > 1)) {
            $foundtext = 'Results';
        } else {
            $foundtext = 'Result';
        }
		//$output .= listingpro_load_more_filter($my_query, $pageno, $squery);
        $output            = utf8_encode($output);
        $term_group_result = json_encode(array(
            "foundtext" => $foundtext,
            "found" => $found,
            "tags" => $info['tag_name'],
            "cat" => $categoryName,
            "city" => $LocationName,
            "html" => $output,
            "opentime" => $listing_time
        ));
        die($term_group_result);
    }
}
	
add_action( 'admin_init', 'process_post' );
function process_post() {
    global $listingpro_settings, $reviews_options, $listingpro_formFields, $claim_options, $ads_options, $page_options;
	$listingpro_settings_custom = Array(
		Array(
			'name' => esc_html__('Google Address', 'listingpro-plugin'),
			'id' => 'gAddress_custom',
			'type' => 'text',
			'desc' => 'Google address for map'),		
		Array(
			'name' => esc_html__('Latitude', 'listingpro-plugin'),
			'id' => 'latitude_custom',
			'type' => 'text',
			'desc' => ''),
		Array(
			'name' => esc_html__('Longitude', 'listingpro-plugin'),
			'id' => 'longitude_custom',
			'type' => 'text',
			'desc' => ''),   
		);
		if(!empty($listingpro_settings))
			$listingpro_settings = array_merge($listingpro_settings_custom,$listingpro_settings);
}
/* pages_metabox_render */
add_action('save_post', 'savePostMetaCustom');
function savePostMetaCustom($post_id) {
	global $listingpro_settings, $reviews_options, $listingpro_formFields, $claim_options, $ads_options, $page_options;

	$meta = 'lp_'.strtolower(THEMENAME).'_options';
	
	// check autosave
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return $post_id;
	}
	
	// check permissions
	if ('page' == $_POST['post_type']) {
			if (!current_user_can('edit_page', $post_id)) {
					return $post_id;
			}
	} elseif (!current_user_can('edit_post', $post_id)) {
			return $post_id;
	}
	
	if($_POST['post_type']=='lp-reviews'){
		$metaboxes_reviews = $reviews_options;
	}
   
	if($_POST['post_type']=='lp-ads'){
		$metaboxes = $ads_options;
	}
	
	if($_POST['post_type']=='listing'){
		$metaboxes = $listingpro_settings;
	}
	
	if($_POST['post_type']=='form-fields'){
		$metaboxes = $listingpro_formFields;
	}
	if($_POST['post_type']=='lp-claims'){
		$metaboxes = $claim_options;
	}
	if($_POST['post_type']=='page'){
		$metaboxes = $page_options;
	}
	if(!empty($metaboxes_reviews)) {
		$myMeta = array();

		foreach ($metaboxes_reviews as $metabox) {
			$myMeta[$metabox['id']] = isset($_POST[$metabox['id']]) ? $_POST[$metabox['id']] : "";
		}

		update_post_meta($post_id, $meta, $myMeta);        

	}
	
	if(!empty($metaboxes)) {
		$myMeta = array();

		foreach ($metaboxes as $metabox) {
			$myMeta[$metabox['id']] = isset($_POST[$metabox['id']]) ? $_POST[$metabox['id']] : "";
		}		
		update_post_meta($post_id, $meta, $myMeta);        
		if(isset($_POST['lp_form_fields_inn'])){
			$metaFields = 'lp_'.strtolower(THEMENAME).'_options_fields';
			$fields = $_POST['lp_form_fields_inn'];		 	
			update_post_meta($post_id, $metaFields, $fields);
		}else{
			$metaFields = 'lp_'.strtolower(THEMENAME).'_options_fields';
			update_post_meta($post_id, $metaFields, '');
		}      
	}
}

	function LP_operational_hours_form($postID,$edit){		
		$output = '';
		$MondayOpen = '';
		$MondayClose = '';
		$TusedayOpen = '';
		$TusedayClose = '';
		$WednesdayOpen = '';
		$WednesdayClose = '';
		$ThursdayOpen = '';
		$ThursdayClose = '';
		$FridayOpen = '';
		$FridayClose = '';
		$SaturdayOpen = '';
		$SaturdayClose = '';
		$SundayOpen = '';
		$SundayClose = '';
		
		$MondayEnabled = 'disabled';
		$Mondaychecked = '';
		$TusedayEnabled = 'disabled';
		$Tusedaychecked = '';
		$WednesdayEnabled = 'disabled';
		$Wednesdaychecked = '';
		$ThursdayEnabled = 'disabled';
		$Thursdaychecked = '';
		$FridayEnabled = 'disabled';
		$Fridaychecked = '';
		$SaturdayEnabled = 'disabled';
		$Saturdaychecked = '';
		$SundayEnabled = 'disabled';
		$Sundaychecked = '';
		global $listingpro_options;

		$listingOphText = $listingpro_options['listing_oph_text'];
			$output .='
				
				<div class="form-group clearfix">
					<label for="operationalHours">'.$listingOphText.'</label>
					<div class="day-hours" id="day-hours-BusinessHours">
						<div class="hours-display">';
		if($edit == true && !empty($postID)){
			$buisness_hours = listing_get_metabox_by_ID('business_hours', $postID);
			$key = 0;
			if(!empty($buisness_hours)){	
				foreach($buisness_hours as $arrKey=>$buisness_hour){
					if(empty($buisness_hour['open'])){
						foreach($buisness_hour as $key=>$value){				
							$output .='<div class="hours">';
							if( !empty($value['open'])&& !empty($value['close'])){
								$output .='
									<span class="weekday">'.$arrKey.'</span>
									<span class="start">'.$value['open'].'</span>
								';
							}
							else{
								$output .='
									<span class="weekday">'.$arrKey.'</span>
									<span class="start-end fullday">
									'.esc_html__('24 hours open', 'listingpro-plugin').'
									</span>
								';
							}
			                	
			                $output .='<span>-</span>
			                	<span class="end">'.$value['close'].'</span>
			                	<a class="remove-hours" href="#">'.esc_html__( 'Remove', 'listingpro-plugin' ).'</a>';
								//if( !empty($value['open'])&& !empty($value['close'])){
									$output .='
										<input name="business_hours['.$arrKey.']['.$key.'][open]" value="'.$value['open'].'" type="hidden">
										<input name="business_hours['.$arrKey.']['.$key.'][close]" value="'.$value['close'].'" type="hidden">
									';
								//}
			                $output .='	
			                </div>';							
						}
					}
				}			
			}
			echo '<script>var last_bus_item = '.$key.'</script>';
		}else{
		$output .='
				       
			                <div class="hours">
			                	<span class="weekday">'.esc_html__( 'Monday', 'listingpro-plugin' ).'</span>
			                	<span class="start">09:00</span>
			                	<span>-</span>
			                	<span class="end">17:00</span>
			                	<a class="remove-hours" href="#">'.esc_html__( 'Remove', 'listingpro-plugin' ).'</a>
			                	<input name="business_hours['.esc_html__( 'Monday', 'listingpro-plugin' ).'][open]" value="09:00" type="hidden">
			                	<input name="business_hours['.esc_html__( 'Monday', 'listingpro-plugin' ).'][close]" value="17:00" type="hidden">
			                </div>
			                <div class="hours">
			                	<span class="weekday">'.esc_html__( 'Tuesday', 'listingpro-plugin' ).'</span>
			                	<span class="start">09:00</span>
			                	<span>-</span>
			                	<span class="end">17:00</span>
			                	<a class="remove-hours" href="#">'.esc_html__( 'Remove', 'listingpro-plugin' ).'</a>
			                	<input name="business_hours['.esc_html__( 'Tuesday', 'listingpro-plugin' ).'][open]" value="09:00" type="hidden">
			                	<input name="business_hours['.esc_html__( 'Tuesday', 'listingpro-plugin' ).'][close]" value="17:00" type="hidden">
			                </div>
			                <div class="hours">
			                	<span class="weekday">'.esc_html__( 'Wednesday', 'listingpro-plugin' ).'</span>
			                	<span class="start">09:00</span>
			                	<span>-</span>
			                	<span class="end">17:00</span>
			                	<a class="remove-hours" href="#">'.esc_html__( 'Remove', 'listingpro-plugin' ).'</a>
			                	<input name="business_hours['.esc_html__( 'Wednesday', 'listingpro-plugin' ).'][open]" value="09:00" type="hidden">
			                	<input name="business_hours['.esc_html__( 'Wednesday', 'listingpro-plugin' ).'][close]" value="17:00" type="hidden">
			                </div>
			                <div class="hours">
			                	<span class="weekday">'.esc_html__( 'Thursday', 'listingpro-plugin' ).'</span>
			                	<span class="start">09:00</span>
			                	<span>-</span>
			                	<span class="end">17:00</span>
			                	<a class="remove-hours" href="#">'.esc_html__( 'Remove', 'listingpro-plugin' ).'</a>
			                	<input name="business_hours['.esc_html__( 'Thursday', 'listingpro-plugin' ).'][open]" value="09:00" type="hidden">
			                	<input name="business_hours['.esc_html__( 'Thursday', 'listingpro-plugin' ).'][close]" value="17:00" type="hidden">
			                </div>
			                <div class="hours">
			                	<span class="weekday">'.esc_html__( 'Friday', 'listingpro-plugin' ).'</span>
			                	<span class="start">09:00</span>
			                	<span>-</span>
			                	<span class="end">17:00</span>
			                	<a class="remove-hours" href="#">'.esc_html__( 'Remove', 'listingpro-plugin' ).'</a>
			                	<input name="business_hours['.esc_html__( 'Friday', 'listingpro-plugin' ).'][open]" value="09:00" type="hidden">
			                	<input name="business_hours['.esc_html__( 'Friday', 'listingpro-plugin' ).'][close]" value="17:00" type="hidden">
			                </div>
			            ';
		}
		$output .= '</div>
				        <ul class="hours-select clearfix inline-layout up-4">
				            <li>
				                <select class="weekday select2">
									<option value="'.esc_html__( 'Monday', 'listingpro-plugin' ).'">'.esc_html__( 'Monday', 'listingpro-plugin' ).'</option>
									<option value="'.esc_html__( 'Tuesday', 'listingpro-plugin' ).'">'.esc_html__( 'Tuesday', 'listingpro-plugin' ).'</option>
									<option value="'.esc_html__( 'Wednesday', 'listingpro-plugin' ).'">'.esc_html__( 'Wednesday', 'listingpro-plugin' ).'</option>
									<option value="'.esc_html__( 'Thursday', 'listingpro-plugin' ).'">'.esc_html__( 'Thursday', 'listingpro-plugin' ).'</option>
									<option value="'.esc_html__( 'Friday', 'listingpro-plugin' ).'">'.esc_html__( 'Friday', 'listingpro-plugin' ).'</option>
									<option value="'.esc_html__( 'Saturday', 'listingpro-plugin' ).'" selected="">'.esc_html__( 'Saturday', 'listingpro-plugin' ).'</option>
									<option value="'.esc_html__( 'Sunday', 'listingpro-plugin' ).'">'.esc_html__( 'Sunday', 'listingpro-plugin' ).'</option>
				                </select>
				            </li>
				            <li>
				                <select class="hours-start select2">
									<option value="24:00">24:00 ('.esc_html__('midnight', 'listingpro-plugin').')</option>
									<option value="24:30">24:30 </option>
									<option value="01:00">01:00</option>
									<option value="01:30">01:30</option>
									<option value="02:00">02:00</option>
									<option value="02:30">02:30</option>
									<option value="03:00">03:00</option>
									<option value="03:30">03:30</option>
									<option value="04:00">04:00</option>
									<option value="04:30">04:30</option>
									<option value="05:00">05:00</option>
									<option value="05:30">05:30</option>
									<option value="06:00">06:00</option>
									<option value="06:30">06:30</option>
									<option value="07:00">07:00</option>
									<option value="07:30">07:30</option>
									<option value="08:00">08:00</option>
									<option value="08:30">08:30</option>
									<option value="09:00" selected="">09:00</option>
									<option value="09:30">09:30</option>
									<option value="10:00">10:00</option>
									<option value="10:30">10:30</option>
									<option value="11:00">11:00</option>
									<option value="11:30">11:30</option>
									<option value="12:00">12:00('.esc_html__('noon', 'listingpro-plugin').')</option>
									<option value="12:30">12:30</option>
									<option value="13:00">13:00</option>
									<option value="13:30">13:30</option>
									<option value="14:00">14:00</option>
									<option value="14:30">14:30</option>
									<option value="15:00">15:00</option>
									<option value="15:30">15:30</option>
									<option value="16:00">16:00</option>
									<option value="16:30">16:30</option>
									<option value="17:00">17:00</option>
									<option value="17:30">17:30</option>
									<option value="18:00">18:00</option>
									<option value="18:30">18:30</option>
									<option value="19:00">19:00</option>
									<option value="19:30">19:30</option>
									<option value="20:00">20:00</option>
									<option value="20:30">20:30</option>
									<option value="21:00">21:00</option>
									<option value="21:30">21:30</option>
									<option value="22:00">22:00</option>
									<option value="22:30">22:30</option>
									<option value="23:00">23:00</option>
									<option value="23:30">23:30</option>
				                </select>
				            </li>
				            <li>
				                <select class="hours-end select2">
									<option value="24:00">24:00 ('.esc_html__('midnight', 'listingpro-plugin').')</option>
									<option value="24:30">24:30 </option>
									<option value="01:00">01:00</option>
									<option value="01:30">01:30</option>
									<option value="02:00">02:00</option>
									<option value="02:30">02:30</option>
									<option value="03:00">03:00</option>
									<option value="03:30">03:30</option>
									<option value="04:00">04:00</option>
									<option value="04:30">04:30</option>
									<option value="05:00">05:00</option>
									<option value="05:30">05:30</option>
									<option value="06:00">06:00</option>
									<option value="06:30">06:30</option>
									<option value="07:00">07:00</option>
									<option value="07:30">07:30</option>
									<option value="08:00">08:00</option>
									<option value="08:30">08:30</option>
									<option value="09:00">09:00</option>
									<option value="09:30">09:30</option>
									<option value="10:00">10:00</option>
									<option value="10:30">10:30</option>
									<option value="11:00">11:00</option>
									<option value="11:30">11:30</option>
									<option value="12:00">12:00('.esc_html__('noon', 'listingpro-plugin').')</option>
									<option value="12:30">12:30</option>
									<option value="13:00">13:00</option>
									<option value="13:30">13:30</option>
									<option value="14:00">14:00</option>
									<option value="14:30">14:30</option>
									<option value="15:00">15:00</option>
									<option value="15:30">15:30</option>
									<option value="16:00">16:00</option>
									<option value="16:30">16:30</option>
									<option value="17:00" selected="">17:00</option>
									<option value="17:30">17:30</option>
									<option value="18:00">18:00</option>
									<option value="18:30">18:30</option>
									<option value="19:00">19:00</option>
									<option value="19:30">19:30</option>
									<option value="20:00">20:00</option>
									<option value="20:30">20:30</option>
									<option value="21:00">21:00</option>
									<option value="21:30">21:30</option>
									<option value="22:00">22:00</option>
									<option value="22:30">22:30</option>
									<option value="23:00">23:00</option>
									<option value="23:30">23:30</option>
				                </select>
								
				            </li>
								
							<li>
								<div class="checkbox form-group fulldayopen-wrap">
									<input type="checkbox" name="fulldayopen" id="fulldayopen" class="fulldayopen">
									<label for="fulldayopen">'.esc_html__('24 Hours' ,'listingpro-plugin').'</label>
								</div>
				                <button data-fullday = "'.esc_html__('24 hours open', 'listingpro-plugin').'" data-remove="'.esc_html__('Remove', 'listingpro-plugin').'" data-sorrymsg="'.esc_html__('Sorry','listingpro-plugin').'" data-alreadyadded="'.esc_html__('Already Added', 'listingpro-plugin').'" type="button" value="submit" class="ybtn ybtn--small add-hours"><span>'.esc_html__('+','listingpro-plugin').'</span></button>
				            </li>
				        </ul>
				    </div>
					

				</div>';
	
		return $output;
	}
?>
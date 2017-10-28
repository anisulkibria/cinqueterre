jQuery(function($) {
	$pinicon = jQuery('#singlepostmap').data('pinicon');
	if($pinicon===""){
		$pinicon = $siteURL+"wp-content/themes/listingpro/assets/images/pins/lp-logo.png";
	}
	
	$lat = jQuery('.singlebigmaptrigger').data("lat");
	$lan = jQuery('.singlebigmaptrigger').data("lan");
	$gAddress = jQuery('.singlebigmaptrigger').data("gadd");
	
	$latt = jQuery('.singlebigmaptrigger').data("latt");
	$lann = jQuery('.singlebigmaptrigger').data("lann");
	$gAddresss = jQuery('.singlebigmaptrigger').data("gadds");
	
	"use strict";
	if($lan != '' && $lat != ''){
		var locations = [
		  ['loan 1', $lat, $lan, $gAddress],
		  ['loan 2', $latt, $lann, $gAddresss]
		  ];

		  function init() {
			var myOptions = {
						center: new google.maps.LatLng($lat, $lan),
						zoom:17,
						scrollwheel:!1,
						mapTypeId: google.maps.MapTypeId.ROADMAP,
						styles:[ {
							featureType:"administrative",
							elementType:"labels.text.fill",
							stylers:[ {
								color: "#444444"
							}
							]
						}
						,
						{
							featureType:"landscape",
							elementType:"all",
							stylers:[ {
								color: "#f2f2f2"
							}
							]
						}
						,
						{
							featureType:"poi",
							elementType:"all",
							stylers:[ {
								visibility: "off"
							}
							]
						}
						,
						{
							featureType:"road",
							elementType:"all",
							stylers:[ {
								saturation: -100
							}
							,
							{
								lightness: 45
							}
							]
						}
						,
						{
							featureType:"road.highway",
							elementType:"all",
							stylers:[ {
								visibility: "simplified"
							}
							]
						}
						,
						{
							featureType:"road.arterial",
							elementType:"labels.icon",
							stylers:[ {
								visibility: "off"
							}
							]
						}
						,
						{
							featureType:"transit",
							elementType:"all",
							stylers:[ {
								visibility: "off"
							}
							]
						}
						,
						{
							featureType:"water",
							elementType:"all",
							stylers:[ {
								color: "#b7ecf0"
							}
							,
							{
								visibility: "on"
							}
							]
						}
						]
					};
			var map = new google.maps.Map(document.getElementById("singlepostmap"),
				myOptions);
			setMarkers(map,locations)
		  }
		  function setMarkers(map,locations){
			  var marker, i
				for (i = 0; i < locations.length; i++)
				 {  
				 var loan = locations[i][0]
				 var lat = locations[i][1]
				 var long = locations[i][2]
				 var add =  locations[i][3]

				 latlngset = new google.maps.LatLng(lat, long);

				  var marker = new google.maps.Marker({  
						  map: map, title: loan , position: latlngset,icon: ""+$pinicon+""  
						});
						map.setCenter(marker.getPosition())


						var content = "Loan Number: " + loan +  '</h3> ' + "Address: " + add     

				  var infowindow = new google.maps.InfoWindow()

				google.maps.event.addListener(marker,'click', (function(marker,content,infowindow){ 
						return function() {
						   infowindow.setContent(content);
						   infowindow.open(map,marker);
						};
					})(marker,content,infowindow)); 

				  }
		  }
		google.maps.event.addDomListener(window, "load", init);
	}
	
	
	var page = 1; // What page we are on.
	var ppp = 6; // Post per page
	isPreviousEventComplete = true, isDataAvailable = true;
		jQuery(window).on("scroll",function(){
			//alert(jQuery(document).height());
			if (jQuery(document).height() - 300 <= jQuery(window).scrollTop() + jQuery(window).height()) {
				if (isPreviousEventComplete && isDataAvailable) {
					isPreviousEventComplete = false;
					jQuery('#content-grids').append('<div class="loading-main-div"><button class="jk-load-more-btn loading" disabled="disabled">Older Posts</button></div>');
					
					var inexpensive='';
					moderate = '';
					pricey = '';
					ultra = '';
					averageRate = '';
					mostRewvied = '';
					listing_openTime = '';
					
					inexpensive = jQuery('.currency-signs #one').find('.active').data('price');
					moderate = jQuery('.currency-signs #two').find('.active').data('price');
					pricey = jQuery('.currency-signs #three').find('.active').data('price');
					ultra = jQuery('.currency-signs #four').find('.active').data('price');
					
					averageRate = jQuery('.search-filters li#listingRate').find('.active').data('value');	
					mostRewvied = jQuery('.search-filters li#listingReviewed').find('.active').data('value');
					listing_openTime = jQuery('.search-filters li#listing_openTime').find('.active').data('value');
					
					pageno = page + 1;
					skeywork = '';
					
					var tags_name = [];
					tags_name = jQuery('.tags-area input[type=checkbox]:checked').map(function(){
					  //return jQuery(this).val();
					}).get();

					
				listStyle = jQuery("#page").data('list-style');
					jQuery.ajax({
						type: 'POST',
						dataType: 'json',
						url: ajax_search_term_object.ajaxurl,
						data: { 
							'action': 'ajax_load_tags', 
							'inexpensive':inexpensive,
							'moderate':moderate,
							'pricey':pricey,
							'ultra':ultra,
							'averageRate':averageRate,
							'mostRewvied':mostRewvied,
							'listing_openTime':listing_openTime,
							'tag_name':tags_name,
							'cat_id': jQuery("#searchcategory").val(), 
							'loc_id': jQuery("#lp_search_loc").val(),
							'list_style': listStyle, 
							'pageno': pageno,
							'skeywork': skeywork
							},
						success: function(data) {	
							jQuery('.loading-main-div').remove();
							isPreviousEventComplete = false;
							if(data.html){	
								jQuery('.loading-main-div').remove();
								var pars = decode_utf8(data.html);								
								jQuery('#content-grids').append(pars);
								isPreviousEventComplete = true;
								page++;		
								var taxonomy = jQuery('section.taxonomy').attr('id');					
								if(taxonomy == 'location'){
									if(data.cat != ''){
										var CatName = data.cat;
										CatName = CatName.replace('&amp;', '&');
										jQuery('.filter-top-section .lp-title span.term-name').html(CatName+' Listings <span style="font-weight:normal;"> In </span>');
										jQuery('.filter-top-section .lp-title span.font-bold:last-child').text(data.city);
										//window.history.pushState("Details", "Title", 'location/'+data.cat);	
									}
									
								}else if(taxonomy == 'listing-category'){
			
									if(data.cat != ''){
										var CatName = data.cat;
										CatName = CatName.replace('&amp;', '&');
										jQuery('.filter-top-section .lp-title span.term-name').text(CatName);
										//window.history.pushState("Details", "Title", 'location/'+data.cat);	
									}
									
								}else if(taxonomy == 'features'){
									jQuery('.showbread').show();
									jQuery('.fst-term').html(data.tags);
									if(data.keyword != ''){
										jQuery('.s-term').html(',&nbsp;keyword&nbsp;"'+data.keyword+'"');
									}else{
										jQuery('.s-term').html(' ');
									}
									if(data.city != ''){
										if(data.cat != ''){									
											jQuery('.sec-term').html('&amp;&nbsp;'+data.city);
										}else{
											jQuery('.sec-term').html(data.city);
										}
									}else{
										jQuery('.sec-term').html(' ');
									}
									if(data.tags != ''){
										jQuery('.tag-term').html(',&nbsp;tagged&nbsp;('+data.tags+')');
									}
									if(data.tags == null){
										jQuery('.tag-term').html('');
									}
								}
								
								
								else if(taxonomy == 'keyword'){
									jQuery('.showbread').show();
									jQuery('.fst-term').html(data.cat);
									if(data.keyword != ''){
										jQuery('.s-term').html(',&nbsp;keyword&nbsp;"'+data.keyword+'"');
									}else{
										jQuery('.s-term').html(' ');
									}
									if(data.city != ''){
										if(data.cat != ''){									
											jQuery('.sec-term').html('&amp;&nbsp;'+data.city);
										}else{
											jQuery('.sec-term').html(data.city);
										}
									}else{
										jQuery('.sec-term').html(' ');
									}
									
									if(data.tags != ''){
										jQuery('.tag-term').html(',&nbsp;tagged&nbsp;('+data.tags+')');
									}
									if(data.tags == null){
										jQuery('.tag-term').html('');
									}
								}else{
									if(data.cat != ''){
										var CatName = data.cat;
										CatName = CatName.replace('&amp;', '&');
										jQuery('.filter-top-section .lp-title span.term-name').text(CatName);
										//window.history.pushState("Details", "Title", 'location/'+data.cat);	
									}
								}
								jQuery( ".all-list-map" ).trigger('click');
								
								return false;
							}
						  } 
						});					
				}
			}
		});
	
});
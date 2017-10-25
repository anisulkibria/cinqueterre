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
});
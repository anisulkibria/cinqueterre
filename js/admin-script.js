jQuery(function($) {
	$( "button.add-hours" ).unbind( "click" );
	// Add hours
	var incr = 0;
	if(last_bus_item != '') incr = last_bus_item + 1;
	jQuery('button.add-hours').on('click', function(event) {
		event.preventDefault();
		var $this = jQuery(this);
		var error = false;
		var fullday = '';
		var fullhoursclass = '';
		var weekday = jQuery('select.weekday').val();
		if(jQuery(".fulldayopen").is(":checked")){
			jQuery('.fulldayopen').attr('checked', false);
			jQuery('select.hours-start').prop("disabled", false);
			jQuery('select.hours-end').prop("disabled", false);
			var startVal ='';
			var endVal ='';
			var hrstart ='';
			var hrend ='';
			fullday = $this.data('fullday');
			fullhoursclass = 'fullhours';
		}
		else{
			var startVal = jQuery('select.hours-start').val();
			var endVal = jQuery('select.hours-end').val();
			var hrstart = jQuery('select.hours-start').find('option:selected').text();
			var hrend = jQuery('select.hours-end').find('option:selected').text();
		}
		
		var sorryMsg = jQuery(this).data('sorrymsg');
		var alreadyadded = jQuery(this).data('alreadyadded');
		var remove = jQuery(this).data('remove');
		jQuery('.hours-display .hours').each(function(index, element) { 
			var weekdayTExt = jQuery(element).children('.weekday').text();
			//if(weekdayTExt == weekday){
				//alert('jakir '+sorryMsg+'! '+weekday+' '+alreadyadded);
				//error = true;
			//}
		});
		if(error != true){			
			jQuery('.hours-display').append("<div class='hours "+fullhoursclass+"'><span class='weekday'>"+ weekday +"</span><span class='start-end fullday'>"+fullday+"</span><span class='start'>"+ hrstart +"</span><span>-</span><span class='end'>"+ hrend +"</span><a class='remove-hours' href='#'>"+remove+"</a><input name='business_hours["+weekday+"]["+incr+"][open]' value='"+startVal+"' type='hidden'><input name='business_hours["+weekday+"]["+incr+"][close]' value='"+endVal+"' type='hidden'></div>");
			var current = jQuery('select.weekday').find('option:selected');
			var nextval = current.next();
			current.removeAttr('selected');
			nextval.attr('selected','selected');
			jQuery('select.weekday').trigger('change.select2');
			incr++;
		}
	});
	
	if(jQuery('input').is('#gAddress_custom')){
		google.maps.event.addDomListener(window, 'load', initialize_custom); 
	}
	
});

function initialize_custom() {
	var input_custom = document.getElementById('gAddress_custom');
	var autocomplete_cus = new google.maps.places.Autocomplete(input_custom);
	google.maps.event.addListener(autocomplete_cus, 'place_changed', function () {
		var place_custome = autocomplete_cus.getPlace();
		document.getElementById('latitude_custom').value = place_custome.geometry.location.lat();
		document.getElementById('longitude_custom').value = place_custome.geometry.location.lng();

	});
	
}

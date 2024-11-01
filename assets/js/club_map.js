
    var marker = "";

    var markers = [];

    var map = "";

    var geocoder;

    

      function initMap() {

        map = new google.maps.Map(document.getElementById('map'), {

          zoom: 14,

          center: {lat: parseFloat(map_lat), lng: parseFloat(map_long)}

        });

        geocoder = new google.maps.Geocoder();


		try
		{
	        document.getElementById('find_address').addEventListener('click', function() {

	          geocodeAddress(geocoder, map);

	        });

	        

	        document.getElementById('address').addEventListener('blur', function() {

	          geocodeAddress(geocoder, map);

	        });			
		}
		catch(e)
		{
			
		}


      }



      function geocodeAddress(geocoder, resultsMap) {

      	setMapOnAll(null);

        var address = document.getElementById('address').value;

        address = address + document.getElementById('city_id').value;

        address = address + document.getElementById('country_name').value;

        

        var latlng = {lat: parseFloat( jQuery('#map_lat').val()), lng: parseFloat(jQuery('#map_long').val())};

        geocoder.geocode({'address': address}, function(results, status) {

          if (status === 'OK') {

            resultsMap.setCenter(results[0].geometry.location);

            

            jQuery('#map_lat').val(results[0].geometry.location.lat().toFixed(7));

            jQuery('#map_long').val(results[0].geometry.location.lng().toFixed(7));

            

            var marker = new google.maps.Marker({

              map: resultsMap,

              position: results[0].geometry.location,

              draggable:true,

            });

            markers.push(marker);

            

				google.maps.event.addListener(marker, 'dragend', function(evt){

		            jQuery('#map_lat').val(evt.latLng.lat().toFixed(7));

		            jQuery('#map_long').val(evt.latLng.lng().toFixed(7));    

				});            

          } else {

            alert(address_not_found_club_google_map);

          }

        });

      }

      

      function geoCodeByLatLong(geocoder, resultsMap) {

      	setMapOnAll(null);
		try
		{
       		var address = document.getElementById('address').value;
       	}
       	catch(e)
       	{
			
		}

        var latlng = {lat: parseFloat( jQuery('#map_lat').val()), lng: parseFloat(jQuery('#map_long').val())};

        geocoder.geocode({'location': latlng}, function(results, status) {

          if (status === 'OK') {

            resultsMap.setCenter(results[0].geometry.location);

            

            //jQuery('#map_lat').val(results[0].geometry.location.lat().toFixed(7));

            //jQuery('#map_long').val(results[0].geometry.location.lng().toFixed(7));

            

            var marker = new google.maps.Marker({

              map: resultsMap,

              position: latlng,

              draggable:google_map_marker_flag,

            });

            markers.push(marker);

            

				google.maps.event.addListener(marker, 'dragend', function(evt){

		            jQuery('#map_lat').val(evt.latLng.lat().toFixed(7));

		            jQuery('#map_long').val(evt.latLng.lng().toFixed(7));    

				});            

          } else {

            alert(address_not_found_club_google_map);

          }

        });

      }      

      

function get_all_club_on_map()
{
	if(typeof map_lat_lng_arr != 'undefined')
	{
	    var map = new google.maps.Map(document.getElementById('map'), {
	      zoom: 7,
	      center: new google.maps.LatLng(lat_average, log_average),
	      mapTypeId: google.maps.MapTypeId.ROADMAP
	    });		
		
		var infowindow = new google.maps.InfoWindow();
		
	    var marker, i;    
	    i = 0;
	    map_lat_lng_arr.forEach(function(map_element) {
	    	  
	      marker = new google.maps.Marker({
	        position: new google.maps.LatLng(map_element.map_lat, map_element.map_long),
	        map: map,
	      });

	      google.maps.event.addListener(marker, 'click', (function(marker, i, map_element) {
	        return function() {
	          infowindow.setContent(map_element.club_name);
	          infowindow.open(map, marker);
	        }
	      })(marker, i, map_element));
	      
	      i++;
	    });		
	}	
}      

      

function setMapOnAll(map) {

        for (var i = 0; i < markers.length; i++) {

          markers[i].setMap(map);

        }

      }   
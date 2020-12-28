<?php 
	/*$url = 'https://maps.googleapis.com/maps/api/directions/json?origin=32.582968,74.064206&destination=32.576943,74.060584&mode=driving&sensor=false&key='.$this->config->item('google_key');
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  // Disable SSL verification
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	$response = curl_exec($ch);
	curl_close($ch);
	$res = json_decode($response, true);
	echo "<prE>";
	var_dump($res['routes'][0]['legs'][0]['distance']['text']);
	var_dump($res['routes'][0]['legs'][0]['duration']['text']);	//[0]['distance']['text'] / [0]['duration']['text']
	echo "</pre>";
	*/
	$ordersD = $orders;
	
	$coordsCol = array_map(function($e) {
		return is_object($e) ? $e->coords : $e['coords'];
	}, $ordersD);
?>
<Style>
	/*  <span class="metadata-marker" style="display: none;" data-region_tag="css"></span>       Set the size of the div element that contains the map */
        #map {
            height: 800px;
            /* The height is 400 pixels */
           width: 100%;
            /* The width is the width of the web page */
        }
		#right-panel {
        font-family: 'Roboto','sans-serif';
        line-height: 30px;
        padding-left: 10px;
      }

      #right-panel select, #right-panel input {
        font-size: 15px;
      }

      #right-panel select {
        width: 100%;
      }

      #right-panel i {
        font-size: 12px;
      }
      #right-panel {
        height: 100%;
        width: 100%;
        overflow: auto;
      }
</style>
<div class="row">
	<div class="col-md-8"><div id="map"></div></div>
	<div class="col-md-4"><div id="right-panel">Panel here</div></div>
</div>


<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?libraries=geometry&key=<?=$this->config->item('google_key')?>&sensor=false&v=3"></script>

<script type="text/javascript">
	
	<?php 
		$str = implode('_',$coordsCol);
	?>
	var dbcoords = "<?php echo $str?>";
	// console.log(dbcoords);
    var my={directionsSVC:new google.maps.DirectionsService(),maps:{},routes:{}};
    /**
        * base-class     
        * @param points optional array array of lat+lng-values defining a route
        * @return object Route
    **/                     
    function Route(points) {
        this.origin       = null;
        this.destination  = null;
        this.waypoints    = [];
        if(points && points.length>1) { this.setPoints(points);}
        return this; 
    };

    /**
        *  draws route on a map 
        *              
        * @param map object google.maps.Map 
        * @return object Route
    **/                    
    Route.prototype.drawRoute = function(map) {
        var _this=this;
		//_this.directionsRenderer.setPanel(document.getElementById('right-panel'));
        my.directionsSVC.route(
          {"origin": this.origin,
           "destination": this.destination,
           "waypoints": this.waypoints,
           "travelMode": google.maps.DirectionsTravelMode.DRIVING,
		   
          },
          function(res,sts) {
                if(sts==google.maps.DirectionsStatus.OK){
                    if(!_this.directionsRenderer) {
						_this.directionsRenderer=new google.maps.DirectionsRenderer({ "draggable":false,suppressMarkers: false ,polylineOptions: {strokeColor: "red"}});
						
					}
                    _this.directionsRenderer.setMap(map);
					_this.directionsRenderer.setPanel(document.getElementById('right-panel'));
                    _this.directionsRenderer.setDirections(res);
                    google.maps.event.addListener(_this.directionsRenderer,"directions_changed", function() { _this.setPoints(); } );
                }   
          });
        return _this;
    };

    /**
    * sets map for directionsRenderer     
    * @param map object google.maps.Map
    **/             
    Route.prototype.setGMap = function(map){ this.directionsRenderer.setMap(map); };
			
    /**
    * sets origin, destination and waypoints for a route 
    * from a directionsResult or the points-param when passed    
    * 
    * @param points optional array array of lat+lng-values defining a route
    * @return object Route        
    **/
    Route.prototype.setPoints = function(points) {
        this.origin = null;
        this.destination = null;
        this.waypoints = [];
        if(points) {
          for(var p=0;p<points.length;++p){
            this.waypoints.push({location:new google.maps.LatLng(points[p][0], points[p][1]),stopover:false});
          }
          this.origin=this.waypoints.shift().location;
          this.destination=this.waypoints.pop().location;
        } else {
			var route=this.directionsRenderer.getDirections().routes[0];
			for(var l=0;l<route.legs.length;++l) {
				if(!this.origin)this.origin=route.legs[l].start_location;
					this.destination = route.legs[l].end_location;

				for(var w=0;w<route.legs[l].via_waypoints.length;++w) { 
					this.waypoints.push({location:route.legs[l].via_waypoints[w], stopover:false});
				}
			}
          //the route has been modified by the user when you are here you may call now this.getPoints() and work with the result
        }
        return this;
    };

    /**
    * retrieves points for a route 
    *         
    * @return array         
    **/
    Route.prototype.getPoints = function() {
      var points=[[this.origin.lat(),this.origin.lng()]];

      for(var w=0;w<this.waypoints.length;++w) { points.push([this.waypoints[w].location.lat(), this.waypoints[w].location.lng()]);}
      points.push([this.destination.lat(), this.destination.lng()]);
      return points;
    };
		const getCurrentPosition = ({ onSuccess, onError = () => { } }) => {
			if ('geolocation' in navigator === false) {
				return onError(new Error('Geolocation is not supported by your browser.'));
			}
			return navigator.geolocation.getCurrentPosition(onSuccess, onError);
		};
		const trackLocation = ({ onSuccess, onError = () => { },options }) => {
			if ('geolocation' in navigator === false) {
				return onError(new Error('Geolocation is not supported by your browser.'));
			}

			// Use watchPosition instead.
			return navigator.geolocation.watchPosition(onSuccess, onError);
		};
		const getPositionErrorMessage = code => {
		switch (code) {
			case 1:
			  return 'Permission denied.';
			case 2:
			  return 'Position unavailable.';
			case 3:
			  return 'Timeout reached.';
			default:
			  return null;
		  }
		}
    function initialize() {
		
		var myOptions = { zoom: 16, center: new google.maps.LatLng(-34.397, 150.644), mapTypeId: google.maps.MapTypeId.ROADMAP };
        my.maps.map1 = new google.maps.Map(document.getElementById("map"), myOptions);
		
		var arr = dbcoords.split('_');
		console.log(arr);
		var latlng = [];
		getCurrentPosition({
			onSuccess: ({ coords: { latitude: latt, longitude: lngg } }) => {
				console.log(latt+"=="+lngg);
				 var myOptions = { zoom: 16, center: new google.maps.LatLng(latt,lngg), mapTypeId: google.maps.MapTypeId.ROADMAP };
				 my.maps.map1 = new google.maps.Map(document.getElementById("map"), myOptions);
			},
			onError: err =>
				alert(`Error: ${getPositionErrorMessage(err.code) || err.message}`)
		});
		// Use the new trackLocation function.
		
		trackLocation({
			onSuccess: ({ coords: { latitude: lat, longitude: lng } }) => {
				
				$.each(arr,function(i,v) {
					latlng = v.split(',');
					var left = "r"+i;
					// var markerDestination = new google.maps.Marker({
						// map: my.maps.map1,
						// position: new google.maps.LatLng(latlng[0],latlng[1]),
						// draggable: true,
						// icon: 'http://www.google.com/mapfiles/markerA.png'
				// });
					//32.582968, 74.064206 // albadar house
					my.routes.left = new Route([[lat, lng],[latlng[0], latlng[1]]]).drawRoute(my.maps.map1);
					//my.routes.left = new Route([[lat, lng],[latlng[0], latlng[1]]]).drawRoute(my.maps.map1);
					
					var dist = google.maps.geometry.spherical.computeDistanceBetween (new google.maps.LatLng(32.582968, 74.064206), new google.maps.LatLng(latlng[0],latlng[1])); 
					var km = (dist/1000).toFixed(2)+" km";
					
				});
				
			},
			onError: err => {
			  alert(getPositionErrorMessage(err.code));
			},
			options:{
				enableHighAccuracy: true,
				timeout:1000,
				maximumAge: 0
			}
		});
		
		my.routes.rx = new Route();
    } 
	
	
	google.maps.event.addDomListener(window, "load", initialize);
	
	
</script>
//this launches the undertakers locator
				
var mapper = {

   infosList:[],
    // data  
   mapOptions : {
             center: new google.maps.LatLng( 50.86576283, 4.38527732 ), 
             zoom: 7,
             mapTypeId: google.maps.MapTypeId.ROADMAP,
             streetViewControl: true
           },
           
    // methods
    addMarker:function(data,map,title,infos)
    {
        var myMarkerImage = new google.maps.MarkerImage('puce.png');
        var marker = new google.maps.Marker({
            position: data,
            map: map,
            title:title,
            icon: {
			  path: google.maps.SymbolPath.CIRCLE,
			  scale: 2
			}
        });
        google.maps.event.addListener(marker, "click", function() {
            mapper.openInfoWindow(marker,infos);
        });
        return marker;
    },
    
    openInfoWindow:function(marker,infos)
    {
        var str = ['<span class="undertaker-item"><div class="title">PhoneDate : '+infos.PhoneDate+'</div><div class="title">ServerDate : '+infos.ServerDate+'</div>',
                '<address>Lng : '+infos.PosLong+'</br>Lat : '+infos.PosLat+'</address>',
                '</span><br/><br/><br/><span>Tel : </span>'+infos.Phone+'<br/><span>Fax : </span>'+infos.Fax
               ].join('\n');
               
        this.title.innerHTML = str;
        this.pin.set("position", marker.getPosition());
        this.infowindow.open(this.map, marker);
    },
    
    markUserOnMap:function(data)
    {
        if(data[0] && data[1]){
            var marker = new google.maps.Marker({
                position: new google.maps.LatLng(data[0],data[1]),
                map: this.map,
                title: "title"
            });
        }
    },
    
    initialize:function(myUndertakerList){
        this.map = new google.maps.Map(document.getElementById("googleMap"),
           this.mapOptions);
        mapper.infosList=myUndertakerList;
        // info window
        this.content = document.createElement("DIV");
        this.title = document.createElement("DIV");
        this.content.appendChild(this.title);
        //this.streetview = document.createElement("DIV");
        //this.streetview.style.width = "200px";
        //this.streetview.style.height = "200px";
        //this.content.appendChild(this.streetview);
        this.infowindow = new google.maps.InfoWindow({
            content: this.content
        });
        
        this.panorama = null;
        this.pin = new google.maps.MVCObject();
        google.maps.event.addListenerOnce(this.infowindow, "domready", function() {
          this.panorama = new google.maps.StreetViewPanorama(this.streetview, {
           navigationControl: false,
           enableCloseButton: false,
           addressControl: false,
           linksControl: false,
           visible: true
          });
          this.panorama.bindTo("position", this.pin);
        });
    },
    
    getMinValueForItem:function(item){
        // get the min value
        var value = 100000;
        for(var i=0; i<this.undertakers.length; i++)
            {
                eval("value=value>this.undertakers[i]."+item+"?this.undertakers[i]."+item+":value;");
            }
        return value;
    },
    
    fitMap:function(){

        latMin = this.getMinValueForItem("PosLat");
        latMax = this.getMaxValueForItem("PosLat");
        
        longMin = this.getMinValueForItem("PosLong");
        longMax = this.getMaxValueForItem("PosLong");
        
        // center map
        this.map.setCenter(new google.maps.LatLng(
        
            ((latMax+latMin)/2),
            ((longMax+longMin)/2)
        ));
        
        // crop map
        this.map.fitBounds(new google.maps.LatLngBounds(
            // bottom left
            new google.maps.LatLng(latMin, longMin),
            // top right
            new google.maps.LatLng(latMax, longMax)
        ));
    },
    
    getMaxValueForItem:function(item){
        // get the max value
        var value = 0;
        for(var i=0; i<this.undertakers.length; i++)
            {
                eval("value=value<this.undertakers[i]."+item+"?this.undertakers[i]."+item+":value;");
            }
        return value;
    },
    
    locate:function (undertakers, lineCoordinates) 
    {
           var marker = null;
           var title = "";
           this.undertakers = undertakers;
           var bounds = new google.maps.LatLngBounds();

           for(i=0;i<undertakers.length;i++)
           {
               title = undertakers[i].Name;
               marker = this.addMarker(
                   new google.maps.LatLng(undertakers[i].PosLat, undertakers[i].PosLong)
                   , this.map, title, undertakers[i]);

               if(undertakers[i].PosLat != "" || undertakers[i].PosLong != ""){
                   bounds.extend(marker.position);
               }else{
                   console.log('No PosLat & PosLong for undertaker "'+undertakers[i].Name+'"');
               }
           }
           //  Fit these bounds to the map
           this.map.fitBounds(bounds);

		  var line = new google.maps.Polyline({
			path: lineCoordinates,
			map: this.map
		  });
			
    }
}
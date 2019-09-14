<?php


$dir1 = scandir ( "." ); 

$lon= -122.12;

$lat=37.65 ;

$zz=10;
  $info='';
if (isset($_GET['lon'])) $lon = $_GET['lon'];
if (isset($_GET['lat'])) $lat = $_GET['lat'];
if (isset($_GET['zoom'])) $zz = $_GET['zoom'];
$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if (isset($_GET['info'])) $info = base64_decode($_GET['info']);


?><!DOCTYPE html>
<html>
    <head>
        <title>atlasmap</title>
        <meta charset="utf-8" />

        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="leaflet.css" />
        <!--[if lte IE 8]>
        <link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.6.4/leaflet.ie.css" />
        <![endif]-->
        <!--[if lte IE 8]><link rel="stylesheet"
        href="../dist/leaflet.ie.css" /><![endif]-->
    </head>
    <style>
        html, body  {            width:100%;
            height:100%;      margin:0; 
 font-family: verdana,arial,helvetica,sans-serif;
font-size: 14px;
}

        #map {
            display:block;
            width:100%;
            height:100%;
            margin:0;
            padding:0;
            font-family: verdana,arial,helvetica,sans-serif;
font-size: 10px;

        }
    </style>


    <link rel="stylesheet" href="leaflet.css" />
 
    <script src="jquery.js"></script>
    <script src="leaflet-src.js"></script>
 
<body>


<div id="map" ></div>
<div id="zcon" style="position:absolute; left :60px;top: 0px; background-color:rgba(255,255,255,0.3);padding:8px;">
   <div id="help"  style="margin:6px;">Click on map to update links...<br></div>
   <span id="goog"  style="padding:6px;"> <a href="https://www.google.com/maps/dir/Current+Location/<?php echo $lat.','. $lon;?>" target="_blank"><img src="gmap.png" height=50 /></a><a href="http://waze.to/?ll=<?php echo $lat.','. $lon;?>&navigate=yes"><img src="waze.png" height=50px /></a></span>

<span id="atlas"  style="padding:6px;">
  <a href="<?php echo $actual_link?>">
  <img src="ai.png" height=50 /></a></span>

<div id="textar" style="padding:6px;"><textarea placeholder="Add location tags here..." id="tinfo" style="width:200px; height:30px;"><?php echo $info;?></textarea></div>
<div id="textar2" style="padding:6px;"> </div>
 <button onclick="moveLocation()">center</button>
<button onclick="clearmap()">clear</button>

<div id="lonlat" style="padding:6px;" ><?php echo round($lon,6).', '.round($lat,6).' '.$zz.'';?></div>

</div>
<div id="preview" style="position:absolute; left :0px;bottom: 0px; background-color:rgba(255,255,255,1.0);padding:4px;"></div>



  <span id="current">none </span>
</div>
 

</div>

<script>//example user location

var prevlon = <?php echo round($lon,6);;?>;
var prevlat = <?php echo round($lat,6);;?>;
var lons = [];
var lats=[];
var dists = [];

var imcenters = [];
var c1=[
"rgba(0,200,0,0.8)",
"rgba(0,220,0,0.6)",
"rgba(2,22,220,0.8)",
"rgba(255,100,0,0.8)",
"rgba(255,0,0,0.8)"



];

var nimuri=50;
fnsssdd=[
 
{'fn':'test0201wf00.txt','style':''}
];
var polyline;
 
// var fll=fname.length;
 
var flc=0;
var track=0;
$(function() 
{
   



});
















function getcsv(fns,style,width)
{


    quads=[];

    $("#zz2").html("loading "+fns );
    
    $.get( fns, function( data ) {
      //$( ".result" ).html( data );

      lines = data.split('\n');
      clog("lineslength"+lines.length);

      for (n=0; n<lines.length; n++)
      {
        

        if (lines[n].length>10) {

     // clog(line);
            ll=JSON.parse(lines[n]);

      
          quads.push(ll['coords']);
        }

       }

    clog("quadslen"+ quads.length);
    drawData(quads,style,width);
    $("#zz2").html("path "+fns);
    });
     
}
 

 var BingLayer = L.TileLayer.extend({
                getTileUrl: function (tilePoint) {
                    this._adjustTilePoint(tilePoint);
            //console.log(tilePoint);
                    return L.Util.template(this._url, {
                        s: this._getSubdomain(tilePoint),
                        q: this._quadKey(tilePoint.x, tilePoint.y, this._getZoomForUrl())
                    });
                },
                _quadKey: function (x, y, z) {
                    var quadKey = [];
                    for (var i = z; i > 0; i--) {
                        var digit = '0';
                        var mask = 1 << (i - 1);
                        if ((x & mask) != 0) {
                            digit++;
                        }
                        if ((y & mask) != 0) {
                            digit++;
                            digit++;
                        }
                        quadKey.push(digit);
                    }
                    return quadKey.join('');
                }
            });



            var bing = new BingLayer('http://t{s}.tiles.virtualearth.net/tiles/a{q}.jpeg?g=1398', {
                subdomains: ['0'],
                opacity: 1,
                maxZoom: 20,
                attribution: ' '
            });
 

  var osm = new L.TileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', 
    {minZoom: 5, 
      maxZoom: 20, opacity: 0.6,
      attribution: ''}); 


 var e1006 = new BingLayer('https://s3-us-west-1.amazonaws.com/ocorp/20161006/a{q}.jpg', 
    {minZoom: 5, 
      maxZoom: 25, opacity: 1.0,
      attribution: ''}); 
 

  // 37.616 , -122.387 
  var map = new L.Map(document.querySelector('#map'), {
                layers: [ bing,osm, e1006 ],
                center: new L.LatLng( <?php echo $lat.','.$lon; ?>),
                zoom: <?php echo $zz; ?>,
                maxZoom:25
            });
 
 
 
 var baseMaps = {
};

var overlayMaps = {
 "satellite": bing ,
 'streets': osm,
 'e1006': e1006
};
 
L.control.layers(baseMaps, overlayMaps).addTo(map);
 
 
map.on('click', onMapClick);
var marker = new L.marker([<?php echo $lat.', '.$lon ;?>]).addTo(map);;



function makelinks(lon, lat, zoom){
var waze  = "<a href=\"http://waze.to/?ll="+lat+','+lon+"&navigate=yes\"><img src=\"waze.png\" height=50px /></a>";
var gurl = "https://www.google.com/maps/dir/Current+Location/"+lat+','+lon;
var gdirlink =   ' <a href="'+gurl+'" target="_blank"><img src="gmap.png" height=50px /></a>'  ;
var inf = utf8_to_b64( $('#tinfo').val() ); 
var jsi = utf8_to_b64( '{ "info" : "'+$('#tinfo').val()+'"}' ); 
var linkText = ' <a href="e1006.php?lon='+lon+'&lat='+lat+'&zoom='+zoom+'&info='+ inf + '&jsi='+ jsi +'" target="_blank"><img src="ai.png" height=50px /></a>';
 


    $('#atlas').html(linkText);




 var makeText = '<button id="butmake">create</button>';


dist =  greatcircledist(prevlat, prevlon, lat, lon)
 
dists.push(dist);
lats.push(lat);
lons.push(lon);

tt = '';
totdist =0;
for (var i = 0, len = dists.length; i < len; i++) {

   totdist += dists[i];
   

   if (zoom < 16)
    tt += "<br>"+lats[i].toFixed(4)+","+lons[i].toFixed(4)+ ","+zoom+" "+dists[i].toFixed(3)+"km";
   else 
    tt += "<br>"+lats[i].toFixed(4)+","+lons[i].toFixed(4)+ ","+zoom+" "+dists[i].toFixed(6)+"km";
}


$('#lonlat').html(''+totdist.toFixed(3)+'km '+tt);

  $('#goog').html(gdirlink+waze);


var loc0 = new L.LatLng(prevlat, prevlon);
var loc1 = new L.LatLng(lat, lon);
var loca = new L.LatLng(38, -122);
var locb = new L.LatLng(37, -123);


if(typeof( marker)==='undefined')
 {
  
 }
 else 
 {
    map.removeLayer(marker);
            
 }

polyline = new L.Polyline([loc1,loc0], {
        color:  'red',
        opacity: .8,
        weight: 3,
        
    }).addTo(map);;

 window.marker = new L.marker([lat,lon]).addTo(map);


prevlat = lat;
prevlon = lon;

}



function onMapClick(e) {
lat = e.latlng.lat;
lon = e.latlng.lng;
zoom = map.getZoom()
 makelinks(lon, lat, zoom);

// getrects(e.latlng.lng  ,  e.latlng.lat );

}

 
var latb=38.35685196;
var lonb=-122.51296777;

  window.rects=[];
  window.recto={}
 window.quads={}
window.drawn=[];
window.centers=[];

function getrects(lon, lat) {
window.rects=[];
window.centers=[];

  url='qk2img.php?lon='+lon+'&lat='+lat+'';
  clog(url);
   $.get( url, function( data ) {
      // $( ".result" ).html( data );

var obj = JSON.parse(data);
 
for (a=0;a<obj.georefdone.length; a++){
window.rects.push( obj.georefdone[a]);
  // clog('00'+ obj.georefdone[a]);
}

for (a=0;a<obj.ocguide.length; a++){
window.centers.push( obj.ocguide[a]);
 // clog('00'+ obj.ocguide[a]);
}


 drawrects();   
});

}


 


function drawrects()
{
 

 




}


 
 

function clog(t){console.log(t);} 




//draw all the data on the map
function drawData(quads,style,width) {

    //draw markers for all items




nnd=0;

 // clog('drawData');

 // clog( quads.length);


for (n=0;n< quads.length;n++) { 

 
quad= quads[n];

// clog('quadcsv'+quad);
       var loc0 = new L.LatLng(quad[0][1], quad[0][0]);
       var loc1 = new L.LatLng(quad[1][1], quad[1][0]);
       var loc2 = new L.LatLng(quad[2][1], quad[2][0]);
       var loc3 = new L.LatLng(quad[3][1], quad[3][0]);
        // clog("loc0"+loc0);
         // L.Marker(loc1, { color: "#f00", radius: 4 }).bindLabel( o.name + " " + o.lat + " " +o.lon, { direction: 'left' }).addTo(map);
 //L.marker(loc1, {icon: blueMarker}).bindLabel( o.name + " " + o.lat + " " +o.lon, { direction: 'left' }).addTo(map);
     var latlongs = [  loc0, loc1,loc2, loc3, loc0];
     // clog(latlongs);
// polyline = new L.Polyline(latlongs, {
//         color: c1[style],
//         opacity: .8,
//         weight: width,
        
//     }).addTo(map);;
  
}


 

//var rec1 = L.polyline(rec1, {color: 'red'}).addTo(map);


}


//draw polyline
function createPolyLine(loc1, loc2, item,color1,w,nl,item) {
var  o = items[item];
    var latlongs = [loc1, loc2];
    var polyline = new L.Polyline(latlongs, {
        color: color1,
        opacity: .8,
        weight: 2*w,
        clickable: false
    }).addTo(map);;
if (nl==60){

   
// L.Marker(loc1, { color: "rgba(255,0,0,0.6)", radius: 5 }).bindLabel(o.ht, { direction: 'left' }).addTo(map);



}



}


function xstep(z) {return (360/Math.pow(2,z));}

 function clearMap() {
    for(i in map._layers) {
clog('ll'+i);
      console.log(map._layers[i]);
        if(map._layers[i]._path != undefined) {
            try {
                map.removeLayer(map._layers[i]);
            }
            catch(e) {
                console.log("problem with " + e + map._layers[i]);
            }
        }
    }
}


function shiftimuri(n)
{

window.nimuri =window.nimuri +n;
$('#current').html('' + window.nimuri);


console.log('nimuri'+ window.nimuri);

}

function utf8_to_b64( str ) {
  return window.btoa(unescape(encodeURIComponent( str )));
}

function b64_to_utf8( str ) {
  return decodeURIComponent(escape(window.atob( str )));
}

 
function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition);
    } else {
        $('#textar2').html( "Geolocation is not supported by this browser.");
    }
}
function showPosition(position) {
     innerHTML = "Latitude: " + position.coords.latitude + 
    "<br>Longitude: " + position.coords.longitude; 

    $('#textar2').html( innerHTML);
}
function moveLocation() {

 $('#lonlat').html( "getting location...");
if ("geolocation" in navigator) {
  /* geolocation is available */
  navigator.geolocation.getCurrentPosition(function(position) {
 
    // innerHTML = "Latitude: " + position.coords.latitude + 
    // "<br>Longitude: " + position.coords.longitude; 
    $('#lonlat').html( '');

lat = position.coords.latitude;
lon = position.coords.longitude;
zoom = map.getZoom();
    map.setView([lat, lon], 16);
      makelinks(lon, lat, zoom);

});
} else {
  /* geolocation IS NOT available */
}

   
}


function greatcircledist(lat1, lon1, lat2, lon2) {
        // var rad = {'km': 6371.009, 'MI': 3958.761, 'NM': 3440.070, 'YD': 6967420, 'FT': 20902260};

        var r = 6371.009; 
        lat1 *= Math.PI / 180;
        lon1 *= Math.PI / 180;
        lat2 *= Math.PI / 180;
        lon2 *= Math.PI / 180;
        var lonDelta = lon2 - lon1;
        var a = Math.pow(Math.cos(lat2) * Math.sin(lonDelta) , 2) + Math.pow(Math.cos(lat1) * Math.sin(lat2) - Math.sin(lat1) * Math.cos(lat2) * Math.cos(lonDelta) , 2);
        var b = Math.sin(lat1) * Math.sin(lat2) + Math.cos(lat1) * Math.cos(lat2) * Math.cos(lonDelta);
        var angle = Math.atan2(Math.sqrt(a) , b);
        
        return angle * r;
    }

function clearmap()
{
 for(i in map._layers) {
        if(map._layers[i]._path != undefined) {
            try {
                map.removeLayer(map._layers[i]);
            }
            catch(e) {
                console.log("problem with " + e + map._layers[i]);
            }
        }
    }

 $('#lonlat').html( "");
 lons=[];
 lats = [];
 dists = [];

}

</script>

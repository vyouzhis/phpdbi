/*------------------------------------------------------------------
Project:    Oneline
Author:     Yevgeny S.
URL:        https://twitter.com/YevSim
Version:    1.0
Created:        28/04/2014
Last change:    22/07/2014
-------------------------------------------------------------------*/

/* ===== Google Map for Contact Us page ===== */

function initialize() {
  var map_canvas = document.getElementById('map_canvas');
  var map_options = {
    center: new google.maps.LatLng(44.5403, -78.5463),
    zoom: 8,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  }
  var map = new google.maps.Map(map_canvas, map_options)
}
google.maps.event.addDomListener(window, 'load', initialize);
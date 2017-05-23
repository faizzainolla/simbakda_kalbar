
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <title>Google Maps Location picker</title>
    <style type="text/css">    
      #map {
        margin: 10px;
        width: 600px;
        height: 300px;  
        padding: 10px;
      }
</style>
<script src="https://maps.googleapis.com/maps/api/geocode/json?address=1600+Amphitheatre+Parkway,+Mountain+View,+CA&key=AIzaSyDr-2LvRZ7rPWOdq68eWQre4BlyLJ__z2Q"
    type="text/javascript"></script>
  </head>
  <body>
  <div id="map"></div>
  <table>
  <form  action=''>
  <tr><td>Nama Lokasi:</td>
    <td><input type="text" name='nama_lokasi' id='nama_lokasi'></td></tr>
  <tr><td>Latitude</td> 
   <td> <input type="text" name='latitude' id='latitude'></td></tr>
 <tr> <td>Longitude</td>
      <td><input type="text" name='longitude' id='longitude'></td></tr>
  </form>
  </table>

  <script type="text/javascript">
    //* Fungsi untuk mendapatkan nilai latitude longitude
function updateMarkerPosition(latLng) {
  document.getElementById('latitude').value = [latLng.lat()]
    document.getElementById('longitude').value = [latLng.lng()]
}
       
var map = new google.maps.Map(document.getElementById('map'), {
zoom: 12,
center: new google.maps.LatLng(-7.781921,110.364678),
 mapTypeId: google.maps.MapTypeId.ROADMAP
  });
//posisi awal marker   
var latLng = new google.maps.LatLng(-7.781921,110.364678);
 
/* buat marker yang bisa di drag lalu 
  panggil fungsi updateMarkerPosition(latLng)
 dan letakan posisi terakhir di id=latitude dan id=longitude
 */
var marker = new google.maps.Marker({
    position : latLng,
    title : 'lokasi',
    map : map,
    draggable : true
  });
   
updateMarkerPosition(latLng);
google.maps.event.addListener(marker, 'drag', function() {
 // ketika marker di drag, otomatis nilai latitude dan longitude
 //menyesuaikan dengan posisi marker 
    updateMarkerPosition(marker.getPosition());
  });
</script>
  </body>
</html>
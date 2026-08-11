<?php
include('config.php');
// Fetch offices from database
$offices = $conn->query("SELECT * FROM offices");
$markers = [];
while($row = $offices->fetch_assoc()){
    $markers[] = [
        'lat' => $row['lat'],
        'lng' => $row['lng'],
        'country' => $row['country'],
        'city' => $row['city'],
        'address' => $row['address'],
        'phone' => $row['phone'],
        'fax' => $row['fax'],
        'pobox' => $row['pobox']
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Locate Us</title>

  <link rel="stylesheet" href="./style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
          integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

  <style>
    html, body {
      margin: 0;
      padding: 0;
      height: 100%;
      font-family: Arial, sans-serif;
    }

    .header-section {
      text-align: center;
      padding: 120px 20px 40px;
      padding-top:150px;
      background-color: #f5f5f5;
    }

    .header-section h1 {
      margin: 0;
      font-size: 2.5rem;
      color: #333;
    }

    .header-section p {
      margin-top: 10px;
      font-size: 1.2rem;
      color: #555;
    }

    #map {
      width: 100%;
      height: calc(100vh - 220px); /* Adjust based on header/footer */
    }

    @media (max-width: 768px) {
      .header-section h1 {
        font-size: 2rem;
      }
      .header-section p {
        font-size: 1rem;
      }
    }

    
  </style>
</head>
<body>
  <?php include('header.php'); ?>

  <!-- Page Header -->
  <div class="header-section">
    <h1>Locate Us</h1>
    <p>Find our location on the map below</p>
  </div>

  <!-- Map -->
  <div id="map"></div>

  <?php include('footer.php'); ?>

  <script>
  // Initialize map centered roughly in the Middle East
  const map = L.map('map').setView([27, 45], 5);

  // OpenStreetMap tiles
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; OpenStreetMap contributors',
      maxZoom: 19
  }).addTo(map);

  // Custom marker icon (red)
  const redIcon = new L.Icon({
      iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png',
      shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
      iconSize: [25, 41],
      iconAnchor: [12, 41],
      popupAnchor: [1, -34],
      shadowSize: [41, 41]
  });

  // Add markers from PHP
  const offices = <?php echo json_encode($markers); ?>;

  offices.forEach(office => {
      let popupContent = `<strong>${office.country} - ${office.city}</strong><br>${office.address}<br>Phone: ${office.phone}`;
      if(office.fax) popupContent += `<br>Fax: ${office.fax}`;
      if(office.pobox) popupContent += `<br>P.O. Box: ${office.pobox}`;

      const marker = L.marker([office.lat, office.lng], { icon: redIcon }).addTo(map);
      marker.bindPopup(popupContent);
  });

  // Fit map to all markers
  if(offices.length > 0){
      const group = L.featureGroup(offices.map(o => L.marker([o.lat, o.lng])));
      map.fitBounds(group.getBounds().pad(0.5));
  }
  </script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.13.0/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.13.0/ScrollTrigger.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.13.0/Draggable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.13.0/CustomEase.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.13.0/SplitText.min.js"></script>

 <script src="header.js"></script>
 <script src="footer.js"></script>
</body>
</html>

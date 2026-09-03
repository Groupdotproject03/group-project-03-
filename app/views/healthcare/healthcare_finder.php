<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Nearby Healthcare Finder</title>
<style>
* { box-sizing: border-box; }
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #f5f8fb;
    color: #333;
}
.header {
    background: #1976d2;
    color: white;
    padding: 22px;
    text-align: center;
}
.header h1 { margin: 0; font-size: 28px; }
.header p { margin: 8px 0 0; font-size: 14px; }
.container {
    width: 92%;
    max-width: 1100px;
    margin: 25px auto;
}
.location-box {
    background: white;
    padding: 20px;
    border-radius: 14px;
    box-shadow: 0 3px 12px rgba(0,0,0,0.08);
    margin-bottom: 20px;
    text-align: center;
}
.location-box button {
    background: #1976d2;
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 15px;
}
.location-box button:hover { background: #125ca8; }
#locationStatus { margin-top: 12px; font-size: 14px; }
.filters {
    display: flex;
    gap: 12px;
    margin-bottom: 25px;
    flex-wrap: wrap;
}
.filters input,
.filters select {
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 14px;
    background: white;
}
.filters input { flex: 1; min-width: 220px; }
.cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
}
.card {
    background: white;
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 3px 12px rgba(0,0,0,0.08);
    transition: 0.2s;
}
.card:hover { transform: translateY(-3px); }
.icon { font-size: 38px; margin-bottom: 8px; }
.card h3 { margin: 5px 0 10px; color: #1976d2; }
.badge {
    display: inline-block;
    background: #e3f2fd;
    color: #1976d2;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 12px;
    margin-bottom: 12px;
}
.info { font-size: 14px; margin: 8px 0; line-height: 1.5; }
.distance {
    font-weight: bold;
    color: #e65100;
    margin: 12px 0;
}
.actions {
    display: flex;
    gap: 8px;
    margin-top: 15px;
}
.actions a {
    flex: 1;
    text-align: center;
    padding: 10px;
    border-radius: 7px;
    text-decoration: none;
    font-size: 13px;
}
.direction { background: #1976d2; color: white; }
.call { background: #43a047; color: white; }
.back {
    display: inline-block;
    margin-bottom: 20px;
    text-decoration: none;
    color: #1976d2;
}
.no-result {
    text-align: center;
    background: white;
    padding: 30px;
    border-radius: 12px;
    display: none;
}
@media(max-width:600px) {
    .header h1 { font-size: 23px; }
    .container { width: 94%; }
    .filters { flex-direction: column; }
    .filters input, .filters select { width: 100%; }
    .actions { flex-direction: column; }
}
</style>
</head>
<body>

<div class="header">
    <h1>📍 Nearby Healthcare Finder</h1>
    <p>Find hospitals, clinics, pharmacies and ambulance services near you</p>
</div>

<div class="container">

<a href="dashboard.php" class="back">← Back to Dashboard</a>

<div class="location-box">
    <h3>📍 Find Healthcare Services Near You</h3>
    <p>Allow location access to see the distance from your current location.</p>
    <button onclick="getLocation()">📍 Use My Current Location</button>
    <div id="locationStatus">Location not detected yet.</div>
</div>

<div class="filters">
    <input type="text" id="search" placeholder="🔎 Search hospital, clinic, pharmacy..." onkeyup="filterServices()">
    <select id="typeFilter" onchange="filterServices()">
        <option value="All">All Services</option>
        <option value="Hospital">Hospital</option>
        <option value="Clinic">Clinic</option>
        <option value="Pharmacy">Pharmacy</option>
        <option value="Ambulance">Ambulance</option>
    </select>
</div>

<div class="cards">
<?php
if (!empty($services)) {
    foreach ($services as $row) {
        $type = htmlspecialchars($row['ServiceType']);
        $name = htmlspecialchars($row['ServiceName']);
        $address = htmlspecialchars($row['Address']);
        $phone = htmlspecialchars($row['Phone']);
        $hours = htmlspecialchars($row['OpeningHours']);
        $area = htmlspecialchars($row['Area']);
        $description = htmlspecialchars($row['Description']);

        $lat = $row['Latitude'];
        $lng = $row['Longitude'];

        if ($type == 'Hospital') {
            $icon = '🏥';
        } elseif ($type == 'Clinic') {
            $icon = '🩺';
        } elseif ($type == 'Pharmacy') {
            $icon = '💊';
        } elseif ($type == 'Ambulance') {
            $icon = '🚑';
        } else {
            $icon = '🏥';
        }
?>
<div class="card service-card"
     data-name="<?php echo strtolower($name); ?>"
     data-type="<?php echo $type; ?>">

    <div class="icon"><?php echo $icon; ?></div>
    <span class="badge"><?php echo $type; ?></span>
    <h3><?php echo $name; ?></h3>
    <div class="info">📍 <?php echo $address; ?></div>

    <?php if (!empty($area)) { ?>
        <div class="info">🏙️ <?php echo $area; ?></div>
    <?php } ?>

    <?php if (!empty($phone)) { ?>
        <div class="info">☎️ <?php echo $phone; ?></div>
    <?php } ?>

    <?php if (!empty($hours)) { ?>
        <div class="info">🕐 <?php echo $hours; ?></div>
    <?php } ?>

    <?php if (!empty($description)) { ?>
        <div class="info"><?php echo $description; ?></div>
    <?php } ?>

    <div class="distance" id="distance-<?php echo $row['ServiceID']; ?>">
        📏 Distance: Calculating...
    </div>

    <div class="actions">
        <a class="direction" target="_blank" href="https://www.google.com/maps/dir/?api=1&destination=<?php echo $lat; ?>,<?php echo $lng; ?>">
            🗺️ Get Directions
        </a>
        <?php if (!empty($phone)) { ?>
        <a class="call" href="tel:<?php echo $phone; ?>">
            ☎️ Call
        </a>
        <?php } ?>
    </div>

    <input type="hidden" class="service-lat" value="<?php echo $lat; ?>">
    <input type="hidden" class="service-lng" value="<?php echo $lng; ?>">
    <input type="hidden" class="service-id" value="<?php echo $row['ServiceID']; ?>">
</div>
<?php
    }
} else {
?>
<div class="no-result" style="display:block;">
    No healthcare services found.
</div>
<?php } ?>
</div>

<div id="noResults" class="no-result">
    No matching healthcare service found.
</div>

</div>

<script>
let userLatitude = null;
let userLongitude = null;

function getLocation() {
    const status = document.getElementById("locationStatus");
    if (!navigator.geolocation) {
        status.innerHTML = "❌ Geolocation is not supported by your browser.";
        return;
    }
    status.innerHTML = "📍 Detecting your location...";
    navigator.geolocation.getCurrentPosition(
        function(position) {
            userLatitude = position.coords.latitude;
            userLongitude = position.coords.longitude;
            status.innerHTML = "✅ Your location detected successfully.";
            calculateDistances();
        },
        function(error) {
            status.innerHTML = "❌ Location permission denied. Please allow location access.";
        },
        { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
    );
}

function calculateDistances() {
    const cards = document.querySelectorAll(".service-card");
    cards.forEach(function(card) {
        const lat = parseFloat(card.querySelector(".service-lat").value);
        const lng = parseFloat(card.querySelector(".service-lng").value);
        const id = card.querySelector(".service-id").value;
        const distance = calculateDistance(userLatitude, userLongitude, lat, lng);
        document.getElementById("distance-" + id).innerHTML = "📏 " + distance.toFixed(1) + " km away";
    });
}

function calculateDistance(lat1, lon1, lat2, lon2) {
    const R = 6371;
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLon = (lon2 - lon1) * Math.PI / 180;
    const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
              Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
              Math.sin(dLon / 2) * Math.sin(dLon / 2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    return R * c;
}

function filterServices() {
    const search = document.getElementById("search").value.toLowerCase();
    const selectedType = document.getElementById("typeFilter").value;
    const cards = document.querySelectorAll(".service-card");
    let visible = 0;

    cards.forEach(function(card) {
        const name = card.dataset.name;
        const type = card.dataset.type;
        const matchesSearch = name.includes(search);
        const matchesType = selectedType === "All" || type === selectedType;

        if (matchesSearch && matchesType) {
            card.style.display = "block";
            visible++;
        } else {
            card.style.display = "none";
        }
    });

    document.getElementById("noResults").style.display = visible === 0 ? "block" : "none";
}
</script>

</body>
</html>

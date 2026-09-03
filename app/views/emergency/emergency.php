<!DOCTYPE html>
<html>
<head>
<title>Emergency</title>
<style>
*{margin:0;padding:0;box-sizing:border-box;}
body{font-family:Arial;background:#fff3f2;}
.header{
    background:linear-gradient(135deg,#ff7b7b,#c62828);
    color:white;padding:15px 30px;display:flex;justify-content:space-between;align-items:center;
}
.header a{color:white;text-decoration:none;font-weight:bold;}
.wrap{max-width:800px;margin:30px auto;padding:0 15px;}
.card{
    background:white;border-radius:16px;padding:22px;margin-bottom:20px;
    box-shadow:0 6px 18px rgba(198,40,40,0.12);
}
.card h3{color:#c62828;margin-bottom:12px;}
.sos-btn{
    display:block;width:100%;padding:20px;font-size:20px;font-weight:bold;color:white;
    background:linear-gradient(135deg,#ff5252,#c62828);border:none;border-radius:14px;cursor:pointer;
    box-shadow:0 8px 20px rgba(198,40,40,0.35);
}
.sos-btn:hover{background:#b71c1c;}
.form-row{display:flex;flex-wrap:wrap;gap:10px;margin-top:14px;}
.form-row input, .form-row textarea{
    flex:1;min-width:200px;padding:10px;border:1px solid #e0b3b3;border-radius:8px;font-size:14px;
}
.small-note{font-size:12px;color:#999;margin-top:6px;}
.msg{color:#2e7d32;font-weight:bold;margin-bottom:10px;}

.req-item{
    border:1px solid #f3d6d6;border-radius:12px;padding:14px;margin-bottom:12px;
}
.req-item .top{display:flex;justify-content:space-between;align-items:center;}
.status-tag{padding:4px 12px;border-radius:12px;color:white;font-size:12px;font-weight:bold;}
.status-Requested{background:#e67e00;}
.status-Dispatched{background:#3f8efc;}
.status-OnTheWay{background:#1e88e5;}
.status-Arriving{background:#00897b;}
.status-Arrived{background:#28a745;}
.status-Cancelled{background:#999;}
.progress-track{height:8px;background:#f0e0e0;border-radius:4px;margin-top:10px;overflow:hidden;}
.progress-fill{height:100%;background:linear-gradient(90deg,#ff7b7b,#c62828);width:0%;transition:width 0.5s;}
.driver-info{font-size:13px;color:#555;margin-top:8px;}

.contact-list{list-style:none;}
.contact-list li{
    display:flex;justify-content:space-between;align-items:center;
    padding:10px 0;border-bottom:1px solid #f3e0e0;font-size:14px;
}
.contact-list a{color:#c62828;text-decoration:none;font-size:12px;}
.hotline{
    background:#fff0ef;border-radius:10px;padding:12px 16px;margin-bottom:12px;font-size:14px;
}
button.add-btn{margin-top:10px;padding:8px 16px;background:#c62828;color:white;border:none;border-radius:8px;cursor:pointer;}
</style>
</head>
<body>

<div class="header">
    <a href="dashboard.php">&larr; Back to Dashboard</a>
    <h2 style="font-weight:normal;">🚑 Emergency</h2>
    <div style="width:150px;"></div>
</div>

<div class="wrap">

<?php if (!empty($message)): ?><p class="msg"><?php echo htmlspecialchars($message); ?></p><?php endif; ?>

<!-- REQUEST AMBULANCE -->
<div class="card">
    <h3>Request an Ambulance</h3>
    <button class="sos-btn" onclick="document.getElementById('reqForm').style.display='block'; this.style.display='none';">
        🚨 Request Ambulance Now
    </button>

    <form id="reqForm" style="display:none;" method="POST" action="request_ambulance.php">
        <div class="form-row">
            <input type="text" name="location" id="locationInput" placeholder="Pickup location / address" required>
        </div>
        <div class="form-row">
            <input type="text" name="contact_number" placeholder="Contact number" required>
            <input type="text" name="notes" placeholder="Notes (optional): condition, landmark, etc.">
        </div>
        <input type="hidden" name="latitude" id="latField">
        <input type="hidden" name="longitude" id="lngField">
        <div class="small-note" id="geoNote">Trying to detect your current location for faster response...</div>
        <div class="form-row">
            <button class="add-btn" type="submit">Confirm & Send Request</button>
        </div>
    </form>
</div>

<!-- MY REQUESTS -->
<div class="card">
    <h3>My Ambulance Requests</h3>
    <div id="requestList">
    <?php if (!empty($ambulanceRequests)): ?>
        <?php foreach ($ambulanceRequests as $r):
            $statusClass = 'status-' . str_replace(' ', '', $r['Status']);
            $progressMap = ['Requested'=>10,'Dispatched'=>35,'On The Way'=>65,'Arriving'=>90,'Arrived'=>100,'Cancelled'=>0];
            $progress = $progressMap[$r['Status']] ?? 10;
        ?>
        <div class="req-item" data-request-id="<?php echo $r['RequestID']; ?>">
            <div class="top">
                <strong>#<?php echo $r['RequestID']; ?> — <?php echo htmlspecialchars($r['Location']); ?></strong>
                <span class="status-tag <?php echo $statusClass; ?>" data-status-tag><?php echo htmlspecialchars($r['Status']); ?></span>
            </div>
            <div class="small-note"><?php echo date("d M Y, h:i A", strtotime($r['RequestedAt'])); ?></div>
            <div class="progress-track"><div class="progress-fill" data-progress style="width:<?php echo $progress; ?>%;"></div></div>
            <div class="driver-info" data-driver-info>
                <?php if ($r['DriverName']): ?>
                    Driver: <?php echo htmlspecialchars($r['DriverName']); ?>
                    <?php if ($r['VehicleNo']): ?> · Vehicle: <?php echo htmlspecialchars($r['VehicleNo']); ?><?php endif; ?>
                    <?php if ($r['DriverPhone']): ?> · <?php echo htmlspecialchars($r['DriverPhone']); ?><?php endif; ?>
                    <?php if ($r['ETA']): ?> · ETA: <?php echo htmlspecialchars($r['ETA']); ?><?php endif; ?>
                <?php else: ?>
                    Awaiting driver assignment...
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="small-note">No ambulance requests yet.</p>
    <?php endif; ?>
    </div>
</div>

<!-- EMERGENCY CONTACTS -->
<div class="card">
    <h3>Emergency Contacts</h3>

    <div class="hotline">📞 Clinic Emergency Hotline: <strong>999</strong> (National Emergency Service)</div>

    <ul class="contact-list">
        <?php if (!empty($contacts)): ?>
            <?php foreach ($contacts as $c): ?>
            <li>
                <span><?php echo htmlspecialchars($c['Name']); ?> (<?php echo htmlspecialchars($c['Relation']); ?>) — <?php echo htmlspecialchars($c['Phone']); ?></span>
                <a href="emergency.php?delete_contact=<?php echo $c['ContactID']; ?>" onclick="return confirm('Remove this contact?');">Remove</a>
            </li>
            <?php endforeach; ?>
        <?php else: ?>
            <li><span class="small-note">No personal emergency contacts added yet.</span></li>
        <?php endif; ?>
    </ul>

    <form method="POST" action="emergency.php" class="form-row" style="margin-top:14px;">
        <input type="text" name="contact_name" placeholder="Name" required>
        <input type="text" name="contact_relation" placeholder="Relation (e.g. Spouse)">
        <input type="text" name="contact_phone" placeholder="Phone number" required>
        <button class="add-btn" name="add_contact">Add Contact</button>
    </form>
</div>

</div>

<script>
/* Try to capture current location for the pickup form */
if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(pos){
        document.getElementById('latField').value = pos.coords.latitude;
        document.getElementById('lngField').value = pos.coords.longitude;
        document.getElementById('geoNote').innerText = 'Current location detected — will be shared with the driver.';
    }, function(){
        document.getElementById('geoNote').innerText = 'Location not detected — please type your address above.';
    });
} else {
    document.getElementById('geoNote').innerText = '';
}

/* Poll for live status updates on recent requests */
function refreshStatuses(){
    document.querySelectorAll('.req-item').forEach(function(item){
        const id = item.getAttribute('data-request-id');
        fetch('ambulance_status.php?id=' + id)
            .then(r => r.json())
            .then(data => {
                if(!data.success) return;
                const tag = item.querySelector('[data-status-tag]');
                tag.innerText = data.status;
                tag.className = 'status-tag status-' + data.status.replace(/ /g,'');

                const progressMap = {'Requested':10,'Dispatched':35,'On The Way':65,'Arriving':90,'Arrived':100,'Cancelled':0};
                item.querySelector('[data-progress]').style.width = (progressMap[data.status] ?? 10) + '%';

                const info = item.querySelector('[data-driver-info]');
                if(data.driver_name){
                    let text = 'Driver: ' + data.driver_name;
                    if(data.vehicle_no) text += ' · Vehicle: ' + data.vehicle_no;
                    if(data.driver_phone) text += ' · ' + data.driver_phone;
                    if(data.eta) text += ' · ETA: ' + data.eta;
                    info.innerText = text;
                }
            });
    });
}
setInterval(refreshStatuses, 5000);
</script>

</body>
</html>

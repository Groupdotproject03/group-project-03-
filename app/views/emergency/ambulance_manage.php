<!DOCTYPE html>
<html>
<head>
<title>Ambulance Requests</title>
<style>
*{margin:0;padding:0;box-sizing:border-box;}
body{font-family:Arial;background:#fff3f2;}
.header{
    background:linear-gradient(135deg,#ff7b7b,#c62828);
    color:white;padding:15px 30px;display:flex;justify-content:space-between;align-items:center;
}
.header a{color:white;text-decoration:none;font-weight:bold;}
.wrap{max-width:1000px;margin:30px auto;padding:0 15px;}
.req-card{
    background:white;border-radius:14px;padding:18px 20px;margin-bottom:16px;
    box-shadow:0 5px 15px rgba(198,40,40,0.1);
}
.req-top{display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px;}
.req-top .pname{font-weight:bold;color:#c62828;}
.req-meta{font-size:12px;color:#888;margin-top:4px;}
.status-tag{padding:4px 12px;border-radius:12px;color:white;font-size:12px;font-weight:bold;}
.status-Requested{background:#e67e00;}
.status-Dispatched{background:#3f8efc;}
.status-OnTheWay{background:#1e88e5;}
.status-Arriving{background:#00897b;}
.status-Arrived{background:#28a745;}
.status-Cancelled{background:#999;}
form.update-form{display:flex;flex-wrap:wrap;gap:8px;margin-top:14px;align-items:center;}
form.update-form select, form.update-form input{
    padding:8px;border:1px solid #ddd;border-radius:6px;font-size:13px;
}
form.update-form button{padding:8px 16px;background:#c62828;color:white;border:none;border-radius:6px;cursor:pointer;}
.no-data{text-align:center;color:#aaa;padding:40px;}
</style>
</head>
<body>

<div class="header">
    <a href="dashboard.php">&larr; Back to Dashboard</a>
    <h2 style="font-weight:normal;">🚑 Ambulance Requests</h2>
    <div style="width:150px;"></div>
</div>

<div class="wrap">

<?php if (!empty($requests)): ?>
    <?php foreach ($requests as $r):
        $statusClass = 'status-' . str_replace(' ', '', $r['Status']);
    ?>
    <div class="req-card">
        <div class="req-top">
            <div>
                <span class="pname"><?php echo htmlspecialchars($r['PatientName']); ?></span>
                — <?php echo htmlspecialchars($r['Location']); ?>
            </div>
            <span class="status-tag <?php echo $statusClass; ?>"><?php echo htmlspecialchars($r['Status']); ?></span>
        </div>
        <div class="req-meta">
            Requested <?php echo date("d M Y, h:i A", strtotime($r['RequestedAt'])); ?>
            · Contact: <?php echo htmlspecialchars($r['ContactNumber']); ?>
            <?php if ($r['Notes']): ?> · Notes: <?php echo htmlspecialchars($r['Notes']); ?><?php endif; ?>
            <?php if ($r['Latitude'] && $r['Longitude']): ?>
                · <a href="https://maps.google.com/?q=<?php echo $r['Latitude']; ?>,<?php echo $r['Longitude']; ?>" target="_blank">View on map</a>
            <?php endif; ?>
        </div>

        <form class="update-form" method="POST" action="ambulance_manage.php">
            <input type="hidden" name="request_id" value="<?php echo $r['RequestID']; ?>">

            <select name="status">
                <?php foreach (['Requested','Dispatched','On The Way','Arriving','Arrived','Cancelled'] as $s): ?>
                <option value="<?php echo $s; ?>" <?php echo $r['Status']==$s?'selected':''; ?>><?php echo $s; ?></option>
                <?php endforeach; ?>
            </select>

            <input type="text" name="driver_name" placeholder="Driver name" value="<?php echo htmlspecialchars($r['DriverName'] ?? ''); ?>">
            <input type="text" name="driver_phone" placeholder="Driver phone" value="<?php echo htmlspecialchars($r['DriverPhone'] ?? ''); ?>">
            <input type="text" name="vehicle_no" placeholder="Vehicle no." value="<?php echo htmlspecialchars($r['VehicleNo'] ?? ''); ?>">
            <input type="text" name="eta" placeholder="ETA (e.g. 8 mins)" value="<?php echo htmlspecialchars($r['ETA'] ?? ''); ?>">

            <button type="submit" name="update">Update</button>
        </form>
    </div>
    <?php endforeach; ?>
<?php else: ?>
    <p class="no-data">No ambulance requests yet.</p>
<?php endif; ?>

</div>
</body>
</html>

<!DOCTYPE html>
<html>
<head>
<title>Consultation with <?php echo htmlspecialchars($otherName); ?></title>
<style>
*{margin:0;padding:0;box-sizing:border-box;}
body{font-family:Arial;background:#eaf3ff;}
.header{
    background:linear-gradient(135deg,#4facfe,#2c7ea6);
    color:white;
    padding:15px 30px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}
.header a{color:white;text-decoration:none;font-weight:bold;}
.wrap{max-width:700px;margin:30px auto;padding:0 15px;}
.info-box{
    background:white;border-radius:15px;padding:18px 22px;margin-bottom:15px;
    box-shadow:0 5px 15px rgba(23,84,127,0.15);
}
.info-box h3{color:#2c7ea6;margin-bottom:6px;}
.status-pill{
    display:inline-block;padding:4px 12px;border-radius:12px;color:white;font-size:12px;margin-left:8px;
}
.status-pill.live{background:#28a745;}
.status-pill.wait{background:#e67e00;}
.meeting-link-box{
    background:#f0f8ff;border-radius:10px;padding:14px;margin-top:10px;
}
.meeting-link-box a.join{
    display:inline-block;margin-top:8px;padding:8px 16px;background:#28a745;color:white;
    text-decoration:none;border-radius:8px;font-size:13px;
}
.set-link-row{display:flex;gap:8px;margin-top:8px;}
.set-link-row input{flex:1;padding:8px;border-radius:6px;border:1px solid #ccc;}
.set-link-row button{padding:8px 14px;background:#2c7ea6;color:white;border:none;border-radius:6px;cursor:pointer;}
.chat-box{
    background:white;border-radius:15px;box-shadow:0 5px 15px rgba(23,84,127,0.15);
    display:flex;flex-direction:column;height:420px;overflow:hidden;
}
.chat-messages{flex:1;overflow-y:auto;padding:16px;}
.msg{max-width:75%;padding:10px 14px;border-radius:14px;margin-bottom:10px;font-size:14px;line-height:1.4;clear:both;}
.msg.mine{background:#4facfe;color:white;float:right;border-bottom-right-radius:2px;}
.msg.theirs{background:#eef4fb;color:#333;float:left;border-bottom-left-radius:2px;}
.msg .time{display:block;font-size:10px;opacity:0.7;margin-top:4px;}
.chat-input{display:flex;border-top:1px solid #eee;padding:10px;gap:8px;}
.chat-input input{flex:1;padding:10px;border-radius:20px;border:1px solid #ccc;}
.chat-input button{padding:10px 18px;background:#4facfe;color:white;border:none;border-radius:20px;cursor:pointer;}
.locked-box{
    background:white;border-radius:15px;padding:50px 30px;text-align:center;color:#777;
    box-shadow:0 5px 15px rgba(23,84,127,0.15);
}
.encrypt-note{font-size:11px;color:#999;text-align:center;margin-top:10px;}
</style>
</head>
<body>

<div class="header">
    <a href="consultation.php">&larr; Back</a>
    <h2 style="font-weight:normal;">🩺 Consultation — <?php echo htmlspecialchars($otherName); ?></h2>
    <div style="width:80px;"></div>
</div>

<div class="wrap">

<div class="info-box">
    <h3>Appointment
        <?php if ($isActive): ?>
            <span class="status-pill live">Live Now</span>
        <?php else: ?>
            <span class="status-pill wait">Not Active</span>
        <?php endif; ?>
    </h3>
    <p><?php echo date("d M Y", strtotime($appt['Date'])); ?> at <?php echo date("h:i A", strtotime($appt['Time'])); ?></p>

    <?php if ($isActive): ?>
    <div class="meeting-link-box" id="meetingLinkBox">
        <strong>Meeting Link:</strong>
        <span id="meetingLinkDisplay">
            <?php echo $appt['MeetingLink'] ? '' : '<em>Not set yet</em>'; ?>
        </span>
        <div id="joinArea"></div>

        <?php if ($usertype == 'doctor'): ?>
        <div class="set-link-row">
            <input type="text" id="meetingLinkInput" placeholder="Paste Google Meet / Zoom link here"
                   value="<?php echo htmlspecialchars($appt['MeetingLink'] ?? ''); ?>">
            <button onclick="saveLink()">Save</button>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>

<?php if ($isActive): ?>

<div class="chat-box">
    <div class="chat-messages" id="chatMessages"></div>
    <div class="chat-input">
        <input type="text" id="msgInput" placeholder="Type your message..." onkeydown="if(event.key==='Enter') sendMsg();">
        <button onclick="sendMsg()">Send</button>
    </div>
</div>
<p class="encrypt-note">🔒 Messages are end-to-end encrypted and only visible to you and <?php echo htmlspecialchars($otherName); ?>.</p>

<?php else: ?>

<div class="locked-box">
    <h3>Chat is not active right now</h3>
    <p style="margin-top:10px;">
        The consultation chat and meeting link unlock 15 minutes before your scheduled time
        and remain open for 60 minutes after it.
    </p>
</div>

<?php endif; ?>

</div>

<?php if ($isActive): ?>
<script>
const appointmentId = <?php echo json_encode($appointment_id); ?>;
const myType = <?php echo json_encode($usertype); ?>;
let lastCount = 0;

function escapeHtml(str){
    const d = document.createElement('div');
    d.innerText = str;
    return d.innerHTML;
}

function renderMeetingLink(link){
    const disp = document.getElementById('meetingLinkDisplay');
    const joinArea = document.getElementById('joinArea');
    if(link){
        disp.innerHTML = escapeHtml(link);
        joinArea.innerHTML = '<a class="join" target="_blank" href="' + escapeHtml(link) + '">Join Meeting</a>';
    } else {
        disp.innerHTML = '<em>Not set yet</em>';
        joinArea.innerHTML = '';
    }
}

function loadMessages(){
    fetch('get_consultation_messages.php?id=' + appointmentId)
        .then(r => r.json())
        .then(data => {
            if(!data.success) return;
            renderMeetingLink(data.meeting_link);

            if(data.messages.length !== lastCount){
                const box = document.getElementById('chatMessages');
                box.innerHTML = '';
                data.messages.forEach(m => {
                    const div = document.createElement('div');
                    div.className = 'msg ' + (m.sender_type === myType ? 'mine' : 'theirs');
                    div.innerHTML = escapeHtml(m.text) + '<span class="time">' + m.time + '</span>';
                    box.appendChild(div);
                });
                box.scrollTop = box.scrollHeight;
                lastCount = data.messages.length;
            }
        });
}

function sendMsg(){
    const input = document.getElementById('msgInput');
    const text = input.value.trim();
    if(!text) return;
    input.value = '';
    fetch('send_consultation_message.php', {
        method:'POST',
        headers:{'Content-Type':'application/x-www-form-urlencoded'},
        body:'appointment_id=' + encodeURIComponent(appointmentId) + '&message=' + encodeURIComponent(text)
    }).then(()=> loadMessages());
}

<?php if ($usertype == 'doctor'): ?>
function saveLink(){
    const link = document.getElementById('meetingLinkInput').value.trim();
    fetch('send_consultation_message.php', {
        method:'POST',
        headers:{'Content-Type':'application/x-www-form-urlencoded'},
        body:'appointment_id=' + encodeURIComponent(appointmentId) + '&set_link=1&meeting_link=' + encodeURIComponent(link)
    }).then(()=> loadMessages());
}
<?php endif; ?>

loadMessages();
setInterval(loadMessages, 3000);
</script>
<?php endif; ?>

</body>
</html>

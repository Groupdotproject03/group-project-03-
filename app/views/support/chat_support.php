<!DOCTYPE html>
<html>
<head>
<title>Chat Support</title>
<style>
*{margin:0;padding:0;box-sizing:border-box;}
body{font-family:Arial;background:#eaf3ff;}
.header{
    background:linear-gradient(135deg,#4facfe,#2c7ea6);
    color:white;padding:15px 30px;display:flex;justify-content:space-between;align-items:center;
}
.header a{color:white;text-decoration:none;font-weight:bold;}

/* ---------- PATIENT VIEW ---------- */
.patient-wrap{max-width:600px;margin:30px auto;padding:0 15px;}
.chat-box{
    background:white;border-radius:15px;box-shadow:0 5px 15px rgba(23,84,127,0.15);
    display:flex;flex-direction:column;height:500px;overflow:hidden;
}
.chat-messages{flex:1;overflow-y:auto;padding:16px;}
.msg{max-width:75%;padding:10px 14px;border-radius:14px;margin-bottom:10px;font-size:14px;line-height:1.4;clear:both;}
.msg.mine{background:#4facfe;color:white;float:right;border-bottom-right-radius:2px;}
.msg.theirs{background:#eef4fb;color:#333;float:left;border-bottom-left-radius:2px;}
.msg .time{display:block;font-size:10px;opacity:0.7;margin-top:4px;}
.chat-input{display:flex;border-top:1px solid #eee;padding:10px;gap:8px;}
.chat-input input{flex:1;padding:10px;border-radius:20px;border:1px solid #ccc;}
.chat-input button{padding:10px 18px;background:#4facfe;color:white;border:none;border-radius:20px;cursor:pointer;}
.encrypt-note{font-size:11px;color:#999;text-align:center;margin-top:10px;}
.avail-pill{background:#28a745;color:white;font-size:12px;padding:3px 10px;border-radius:10px;}

/* ---------- STAFF VIEW ---------- */
.staff-wrap{display:flex;height:calc(100vh - 66px);}
.conv-list{width:300px;background:white;border-right:1px solid #eee;overflow-y:auto;}
.conv-item{
    padding:14px 16px;border-bottom:1px solid #f0f0f0;cursor:pointer;display:block;color:#333;text-decoration:none;
}
.conv-item:hover, .conv-item.active{background:#f0f8ff;}
.conv-item .name{font-weight:bold;font-size:14px;}
.conv-item .preview{font-size:12px;color:#888;margin-top:3px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.conv-item .unread{
    background:#e74c3c;color:white;font-size:11px;padding:1px 7px;border-radius:10px;float:right;
}
.staff-chat-area{flex:1;display:flex;flex-direction:column;}
.patient-info-bar{
    background:white;border-bottom:1px solid #eee;padding:14px 20px;display:flex;justify-content:space-between;align-items:center;
}
.patient-info-bar .info-name{font-weight:bold;color:#2c7ea6;}
.patient-info-bar .info-sub{font-size:12px;color:#888;}
.empty-state{flex:1;display:flex;align-items:center;justify-content:center;color:#aaa;}
</style>
</head>
<body>

<div class="header">
    <a href="dashboard.php">&larr; Back to Dashboard</a>
    <h2 style="font-weight:normal;">💬 Chat Support <span class="avail-pill">Available 24/7</span></h2>
    <div style="width:150px;"></div>
</div>

<?php if ($usertype == 'patient'): ?>

<div class="patient-wrap">
    <div class="chat-box">
        <div class="chat-messages" id="chatMessages"></div>
        <div class="chat-input">
            <input type="text" id="msgInput" placeholder="Type your message..." onkeydown="if(event.key==='Enter') sendMsg();">
            <button onclick="sendMsg()">Send</button>
        </div>
    </div>
    <p class="encrypt-note">🔒 Your messages are end-to-end encrypted. Our clinic staff usually reply within a few minutes.</p>
</div>

<script>
let lastCount = 0;

function escapeHtml(str){
    const d = document.createElement('div');
    d.innerText = str;
    return d.innerHTML;
}

function loadMessages(){
    fetch('get_support_messages.php')
        .then(r => r.json())
        .then(data => {
            if(!data.success) return;
            if(data.messages.length !== lastCount){
                const box = document.getElementById('chatMessages');
                box.innerHTML = '';
                data.messages.forEach(m => {
                    const div = document.createElement('div');
                    div.className = 'msg ' + (m.sender_type === 'patient' ? 'mine' : 'theirs');
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
    fetch('send_support_message.php', {
        method:'POST',
        headers:{'Content-Type':'application/x-www-form-urlencoded'},
        body:'message=' + encodeURIComponent(text)
    }).then(()=> loadMessages());
}

if(document.getElementById('chatMessages').childElementCount === 0){
    loadMessages();
}
loadMessages();
setInterval(loadMessages, 3000);
</script>

<?php else: /* ============ STAFF VIEW ============ */ ?>

<div class="staff-wrap">
    <div class="conv-list" id="convList"></div>

    <div class="staff-chat-area">
        <?php if ($active_patient): ?>
            <div class="patient-info-bar">
                <div>
                    <div class="info-name"><?php echo htmlspecialchars($active_patient['Name']); ?></div>
                    <div class="info-sub">
                        <?php echo htmlspecialchars($active_patient['Email']); ?> ·
                        <?php echo htmlspecialchars($active_patient['PhoneNo']); ?>
                        <?php if ($active_patient['avg_score'] !== null): ?>
                            · Avg Health Score: <?php echo round($active_patient['avg_score']); ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="chat-box" style="border-radius:0;box-shadow:none;flex:1;">
                <div class="chat-messages" id="chatMessages"></div>
                <div class="chat-input">
                    <input type="text" id="msgInput" placeholder="Type your reply..." onkeydown="if(event.key==='Enter') sendMsg();">
                    <button onclick="sendMsg()">Send</button>
                </div>
            </div>
        <?php else: ?>
            <div class="empty-state">Select a conversation on the left to start replying.</div>
        <?php endif; ?>
    </div>
</div>

<script>
const activePatientId = <?php echo json_encode($active_patient_id); ?>;
let lastCount = 0;

function escapeHtml(str){
    const d = document.createElement('div');
    d.innerText = str;
    return d.innerHTML;
}

function loadConversations(){
    fetch('get_support_conversations.php')
        .then(r => r.json())
        .then(data => {
            const list = document.getElementById('convList');
            list.innerHTML = '';
            data.conversations.forEach(c => {
                const a = document.createElement('a');
                a.href = 'chat_support.php?patient_id=' + c.patient_id;
                a.className = 'conv-item' + (c.patient_id == activePatientId ? ' active' : '');
                a.innerHTML = '<span class="name">' + escapeHtml(c.name) + '</span>' +
                               (c.unread > 0 ? '<span class="unread">' + c.unread + '</span>' : '') +
                               '<div class="preview">' + escapeHtml(c.preview) + '</div>';
                list.appendChild(a);
            });
            if(data.conversations.length === 0){
                list.innerHTML = '<div style="padding:20px;color:#aaa;font-size:13px;">No conversations yet.</div>';
            }
        });
}

function loadMessages(){
    if(!activePatientId) return;
    fetch('get_support_messages.php?patient_id=' + activePatientId)
        .then(r => r.json())
        .then(data => {
            if(!data.success) return;
            if(data.messages.length !== lastCount){
                const box = document.getElementById('chatMessages');
                box.innerHTML = '';
                data.messages.forEach(m => {
                    const div = document.createElement('div');
                    div.className = 'msg ' + (m.sender_type === 'staff' ? 'mine' : 'theirs');
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
    if(!text || !activePatientId) return;
    input.value = '';
    fetch('send_support_message.php', {
        method:'POST',
        headers:{'Content-Type':'application/x-www-form-urlencoded'},
        body:'patient_id=' + activePatientId + '&message=' + encodeURIComponent(text)
    }).then(()=> loadMessages());
}

loadConversations();
loadMessages();
setInterval(loadConversations, 5000);
setInterval(loadMessages, 3000);
</script>

<?php endif; ?>

</body>
</html>

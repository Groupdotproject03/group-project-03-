<?php
/*
    chatbot_widget.php
    -------------------
    Include this file at the bottom of any logged-in page (before </body>)
    to add a floating in-app assistant bubble. It answers common questions
    instantly using simple rule-based matching (chatbot_response.php) and
    points the user toward the right feature (booking, chat support,
    emergency ambulance, etc.) or a human via Chat Support when it can't help.
*/
?>
<style>
#cb-bubble{
    position:fixed;bottom:24px;right:24px;
    width:60px;height:60px;border-radius:50%;
    background:linear-gradient(135deg,#4facfe,#2c7ea6);
    color:white;display:flex;align-items:center;justify-content:center;
    font-size:26px;cursor:pointer;box-shadow:0 6px 18px rgba(23,84,127,0.4);
    z-index:9999;
}
#cb-window{
    position:fixed;bottom:96px;right:24px;width:320px;max-height:440px;
    background:white;border-radius:16px;box-shadow:0 10px 30px rgba(0,0,0,0.25);
    display:none;flex-direction:column;overflow:hidden;z-index:9999;
}
#cb-header{
    background:linear-gradient(135deg,#4facfe,#2c7ea6);color:white;
    padding:12px 16px;font-size:14px;font-weight:bold;display:flex;justify-content:space-between;align-items:center;
}
#cb-close{cursor:pointer;font-size:16px;}
#cb-messages{flex:1;overflow-y:auto;padding:12px;max-height:300px;background:#f5fafe;}
.cb-msg{padding:8px 12px;border-radius:12px;margin-bottom:8px;font-size:13px;line-height:1.4;max-width:85%;}
.cb-msg.bot{background:#eef4fb;color:#333;}
.cb-msg.user{background:#4facfe;color:white;margin-left:auto;}
#cb-input-row{display:flex;border-top:1px solid #eee;padding:8px;gap:6px;}
#cb-input-row input{flex:1;padding:8px;border-radius:16px;border:1px solid #ccc;font-size:13px;}
#cb-input-row button{padding:8px 12px;background:#4facfe;color:white;border:none;border-radius:16px;cursor:pointer;font-size:13px;}
</style>

<div id="cb-bubble" onclick="cbToggle()">💬</div>

<div id="cb-window">
    <div id="cb-header">
        <span>🤖 HealthChecker Assistant</span>
        <span id="cb-close" onclick="cbToggle()">✕</span>
    </div>
    <div id="cb-messages"></div>
    <div id="cb-input-row">
        <input type="text" id="cb-input" placeholder="Ask something..." onkeydown="if(event.key==='Enter') cbSend();">
        <button onclick="cbSend()">Send</button>
    </div>
</div>

<script>
let cbOpened = false;

function cbToggle(){
    const win = document.getElementById('cb-window');
    const open = win.style.display === 'flex';
    win.style.display = open ? 'none' : 'flex';
    if(!open && !cbOpened){
        cbOpened = true;
        cbAddMsg('bot', "Hi! I'm your HealthChecker assistant. Ask me about appointments, ambulance requests, chat support, or your reports.");
    }
}

function cbAddMsg(who, text){
    const box = document.getElementById('cb-messages');
    const div = document.createElement('div');
    div.className = 'cb-msg ' + who;
    div.innerText = text;
    box.appendChild(div);
    box.scrollTop = box.scrollHeight;
}

function cbSend(){
    const input = document.getElementById('cb-input');
    const text = input.value.trim();
    if(!text) return;
    cbAddMsg('user', text);
    input.value = '';

    fetch('chatbot_response.php', {
        method:'POST',
        headers:{'Content-Type':'application/x-www-form-urlencoded'},
        body:'question=' + encodeURIComponent(text)
    })
    .then(r => r.json())
    .then(data => cbAddMsg('bot', data.answer));
}
</script>

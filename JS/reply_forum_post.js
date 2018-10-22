function reply_forum_post() {
    var rfMessage = document.forms.reply_forum.rfMessage.value,
        rfMessage_err = document.getElementById('rfMessage_err');
        rfMessage_err.innerHTML = '';


    if(rfMessage.trim() === '') {
        rfMessage_err.innerHTML = 'Reply to forum post is required.';
        document.forms.reply_forum.rfMessage.focus();
        return false;
    } else {
        rfMessage_err.innerHTML = '';
    }
}
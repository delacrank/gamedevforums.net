function reply_conv_form() {
    var rcMess = document.forms.reply_conv.message.value,
        rcMess_err = document.getElementById('rcMess_err')
    rcMess_err.innerHTML = '';

    if(rcMess.trim() === '') {
        rcMess_err.innerHTML = 'Message is required';
        document.forms.reply_conv.message.focus();
        return false;
    } else {
        rcMess_err.innerHTML = '';
    }
}
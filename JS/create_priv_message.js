function create_message_form() {
    var mTo = document.forms.create_message.to.value,
        mSubject = document.forms.create_message.subject.value,
        mBody = document.forms.create_message.body.value,
        mTo_err = document.getElementById('mTo_err'),
        mSubject_err = document.getElementById('mSubject_err'),
        mBody_err = document.getElementById('mBody_err');
        mTo_err.innerHTML = '';
        mSubject_err.innerHTML = '';
        mBody_err.innerHTML = '';

    if(mTo.trim() === '') {
        mTo_err.innerHTML = 'Recipient name is required';
        document.forms.create_message.to.focus();
        return false;
    } else {
        mTo_err.innerHTML = '';
    }

    if(mSubject.trim() === '') {
        mSubject_err.innerHTML = 'Subject of message is required';
        document.forms.create_message.subject.focus();
        return false;
    } else {
        mSubject_err.innerHTML = '';
    }
    
    if(mBody.trim() === '') {
        mBody_err.innerHTML = 'Body of message is required';
        document.forms.create_message.body.focus();
        return false;
    } else {
        mBody_err.innerHTML = '';
    }
}
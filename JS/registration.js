function registration_form() {
    var rUsername = document.forms.registration.username.value,
        rEmail = document.forms.registration.email.value,
        rSq = document.forms.registration.sq.value,
        rPass1 = document.forms.registration.pass1.value,
        rPass2 = document.forms.registration.pass2.value,       
        rUsername_err = document.getElementById('rUsername_err'),
        rEmail_err = document.getElementById('rEmail_err'),
        rSq_err = document.getElementById('rSq_err'),
        rPass1_err = document.getElementById('rPass1_err'),
        rPass2_err = document.getElementById('rPass2_err'),
        rCheck = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z]{8,}$/,
        rSqCheck = /^[A-Za-z ]{2,20}$/,
        rEmailCheck = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

    rUsername_err.innerHTML = '';
    rEmail_err.innerHTML = '';
    rSq_err.innerHTML = '';
    rPass1_err.innerHTML = '';
    rPass2_err.innerHTML = '';

    if(rUsername.trim() === '') {
        rUsername_err.innerHTML = 'Username is required';
        document.forms.registration.username.focus();
        return false;
    } else if(!rCheck.test(rUsername)) {
        rUsername_err.innerHTML = 'Must contain at least 8 characters, 1 captial letter, a number and a lowercase letter.';
        document.forms.registration.username.focus();
        return false;
    } else {
         rUsername_err.innerHTML = '';
    }

    if(rEmail.trim() === '') {
        rEmail_err.innerHTML = 'Email is required';
        document.forms.registration.email.focus();
        return false;
    } else if(!rEmailCheck.test(rEmail)) {
        rEmail_err.innerHTML = 'Not a valid email address.';
        document.forms.registration.email.focus();
        return false;
    } else {
         rEmail_err.innerHTML = '';
    }
    
    if(rSq.trim() === '') {
        rSq_err.innerHTML = 'Security Question is required';
        document.forms.registration.sq.focus();
        return false;
    } else if(!rSqCheck.test(rSq)) {
        rSq_err.innerHTML = 'Security question must contains letters and should be at least 2 characters long';
        document.forms.registration.sq.focus();
        return false;
    } else {
         rSq_err.innerHTML = '';
    }
    
    if(rPass1.trim() === '') {
        rPass1_err.innerHTML = 'Password is required';
        document.forms.registration.pass1.focus();
        return false;
    } else if(!rCheck.test(rPass1)) {
        rPass1_err.innerHTML = 'Must contain at least 1 captial letter, a number and a lowercase letter.';
        document.forms.registration.pass1.focus();
        return false;
    } else {
         rPass1_err.innerHTML = '';
    }
    
    if(rPass2.trim() === '') {
        rPass2_err.innerHTML = 'Password is required';
        document.forms.registration.pass2.focus();
        return false;
    } else if(!rCheck.test(rPass2)) {
        rPass2_err.innerHTML = 'Must contain at least 1 captial letter, a number and a lowercase letter.';
        document.forms.registration.pass2.focus();
        return false;
    } else if(rPass1 !== rPass2) {
        rPass2_err.innerHTML = 'Passwords must match';
        document.forms.registration.pass2.focus();
        return false;
    } else {
         rPass2_err.innerHTML = '';
    }
}
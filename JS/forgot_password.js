function forgot_password_form() {
    var xUsername = document.forms.forgot_pass.xusername.value,
        xMother_name = document.forms.forgot_pass.mother_name.value,
        xUsername_err = document.getElementById('fpUsername_err'),
        xMother_name_err = document.getElementById('fpMother_name_err'),
        xCheck = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z]{8,}$/,
        xSqCheck = /^[A-Za-z ]{2,20}$/;

    xUsername_err.innerHTML = '';
    xMother_name_err.innerHTML = '';
        
    if(xUsername.trim() === '') {
        xUsername_err.innerHTML = 'Username is required';
        document.forms.forgot_pass.xusername.focus();
        return false;
    } else if(!xCheck.test(xUsername)) {
        xUsername_err.innerHTML = 'Must contain at least 8 characters, 1 captial letter, a number and a lowercase letter.';
        document.forms.forgot_pass.xusername.focus();
        return false;
    } else {
         xUsername_err.innerHTML = '';
    }
    
    if(xMother_name.trim() === '') {
        xMother_name_err.innerHTML = 'Security question is required';
        document.forms.forgot_pass.mother_name.focus();
        return false;
    } else if(!xSqCheck.test(xMother_name)) {
        xMother_name_err.innerHTML = 'Must contain at least 2 characters, must be characters.';
        document.forms.forgot_pass.mother_name.focus();
        return false;
    } else {
         xMother_name_err.innerHTML = '';
    }
}
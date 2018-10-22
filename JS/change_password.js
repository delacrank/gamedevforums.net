function change_profile_password() {
    var cpUsername = document.forms.change_password.cpusername.value,
        cpOld_pass = document.forms.change_password.old_pass.value,
        cpNew_pass = document.forms.change_password.new_pass.value,
        cpNew_pass2 = document.forms.change_password.new_pass2.value, 
        cpUsername_err = document.getElementById('cpUsername_err'),
        cpOld_pass_err = document.getElementById('cpOld_pass_err'),
        cpNew_pass_err = document.getElementById('cpNew_pass_err'),
        cpNew_pass2_err = document.getElementById('cpNew_pass2_err'),
        cpCheck = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z]{8,}$/;

    cpUsername_err.innerHTML = '';
    cpOld_pass_err.innerHTML = '';
    cpNew_pass_err.innerHTML = '';
    cpNew_pass2_err.innerHTML = '';

    if(cpUsername.trim() === '') {
        cpUsername_err.innerHTML = 'Username is required';
        document.forms.change_password.cpusername.focus();
        return false;
    } else if(!cpCheck.test(cpUsername)) {
        cpUsername_err.innerHTML = 'Must contain at least 1 captial letter, a number and a lowercase letter.';
        document.forms.change_password.cpusername.focus();
        return false;
    } else {
         cpUsername_err.innerHTML = '';
    }

    if(cpOld_pass.trim() === '') {
        cpOld_pass_err.innerHTML = 'Old password is required';
        document.forms.change_password.old_pass.focus();
        return false;
    } else if(!cpCheck.test(cpOld_pass)) {
        cpOld_pass_err.innerHTML = 'Must contain at least 1 captial letter, a number and a lowercase letter.';
        document.forms.change_password.old_pass.focus();
        return false;
    } else {
         cpOld_pass_err.innerHTML = '';
    }
    
    if(cpNew_pass.trim() === '') {
        cpNew_pass_err.innerHTML = 'New password is required';
        document.forms.change_password.new_pass.focus();
        return false;
    } else if(!cpCheck.test(cpNew_pass)) {
        cpNew_pass_err.innerHTML = 'Must contain at least 1 captial letter, a number and a lowercase letter.';
        document.forms.change_password.new_pass.focus();
        return false;
    } else {
         cpNew_pass_err.innerHTML = '';
    }
    
    if(cpNew_pass2.trim() === '') {
        cpNew_pass2_err.innerHTML = 'New Password 2 is required';
        document.forms.change_password.new_pass2.focus();
        return false;
    } else if(!cpCheck.test(cpNew_pass2)) {
        cpNew_pass2_err.innerHTML = 'Must contain at least 1 captial letter, a number and a lowercase letter.';
        document.forms.change_password.new_pass2.focus();
        return false;
    } else if(!(cpNew_pass === cpNew_pass2)) {
        cpNew_pass2_err.innerHTML = 'New Pass2 must match New pass1.';
        document.forms.change_password.new_pass2.focus();
        return false;
    } else {
        cpNew_pass2_err.innerHTML = '';
    }
}
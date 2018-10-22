function login_form() {
    var lUsername = document.forms.login.lusername.value,
        lPassword = document.forms.login.lpassword.value,
        lUsername_err = document.getElementById('lUsername_err'),
        lPassword_err = document.getElementById('lPassword_err'),
        lCheck = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z]{8,}$/;
    lUsername_err.innerHTML = '';
    lPassword_err.innerHTML = '';

    if(lUsername.trim() === '') {
        lUsername_err.innerHTML = 'Username is required';
        document.forms.login.lusername.focus();
        return false;
    } else if(!lCheck.test(lUsername)) {
        lUsername_err.innerHTML = 'Must contain at least 1 captial letter, a number and a lowercase letter.';
        document.forms.login.lusername.focus();
        return false;
    } else {
        lUsername_err.innerHTML = '';
    }

    if(lPassword.trim() === '') {
        lPassword_err.innerHTML = 'Password is required';
        document.forms.login.lpassword.focus();
        return false;
    } else if(!lCheck.test(lPassword)) {
        lPassword_err.innerHTML = 'Must contain at least 1 captial letter, a number and a lowercase letter.';
        document.forms.login.lpassword.focus();
        return false;
    } else {
        lPassword_err.innerHTML = '';
    } 
}

var lPanel = document.getElementsByClassName('login_modal_form'),
    lPanel_button = document.getElementsByClassName('lPanel_button'),
    lPanelIndex = 1,
    modal = document.getElementById('login_modal');

function login_modal() {
    var modal_content = document.getElementsByClassName('login_modal_content');
        lPanel[0].style.display = 'block';
        lPanel[1].style.display = 'none';
        lPanel[2].style.display = 'none';
        lPanel_button[0].childNodes[0].style.backgroundColor = '#5080a2';
        lPanel_button[0].childNodes[0].style.color = '#cad8e2';
        lPanel[0].childNodes[1].childNodes[1].style.borderColor = '#5080a2';
        modal.style.display = 'block';
        
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
}

function chooselPanel(n) {
    showlPanels(lPanelIndex = n);
}

function showlPanels(n) {
    var i;
    if(n > lPanel.length) {
        lPanelIndex = 1;
    }
    if(n < 1) {
        lPanelIndex = lPanel.length;
    }
    for(i = 0; i < lPanel.length; i++) {
        lPanel[i].style.display = 'none';
        if(i < 2) {
            lPanel_button[i].childNodes[0].style.backgroundColor = '#cad8e2';
            lPanel_button[i].childNodes[0].style.color = '#5080a2';
        } 
    }
    if(lPanel[lPanelIndex - 1].style.display === 'none') {
        lPanel[lPanelIndex - 1].style.display = 'block';
        if(lPanelIndex < 3) {
            lPanel_button[lPanelIndex - 1].childNodes[0].style.backgroundColor = '#5080a2';
            lPanel_button[lPanelIndex - 1].childNodes[0].style.color = '#cad8e2';
            lPanel[lPanelIndex - 1].childNodes[1].childNodes[1].style.borderColor = '#5080a2';
        } else {
            lPanel_button[0].childNodes[0].style.backgroundColor = '#5080a2';
            lPanel_button[0].childNodes[0].style.color = '#cad8e2';
            lPanel[0].childNodes[1].childNodes[1].style.borderColor = '#5080a2';
        }
    }
}
        
        
        
        
        
        
        
        
        
        
        
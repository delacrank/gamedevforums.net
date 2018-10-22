var pPanel_buttons = document.getElementsByClassName('pPanel_buttons'),
    pPanel = document.getElementsByClassName('pPanel'),
    formIndex = 1;

function choosePanel (n) {
    showPanels(panelIndex = n);
}

function showPanels(n) {
    var i;
    if(n > pPanel.length) {
        panelIndex = 1;
    }
    if(n < 1) {
        panelIndex = pPanel.length;
    }
    for(i = 0; i < pPanel.length; i++) {
        pPanel[i].style.display = 'none';
        pPanel_buttons[i].style.color = '#5080a2';
        pPanel_buttons[i].style.backgroundColor = '#cad8e2';
    }
    if(pPanel[panelIndex - 1].style.display === 'none') {
        pPanel[panelIndex - 1].style.display = 'block';
        pPanel_buttons[panelIndex - 1].style.color = '#cad8e2';
        pPanel_buttons[panelIndex - 1].style.backgroundColor = '#5080a2';
        localStorage.setItem('num', panelIndex - 1)
        localStorage.setItem('show', 'true');
    } 
}

window.onload = function() {
    var show = localStorage.getItem('show');
    var num  = localStorage.getItem('num');
    if(show === 'true') {
        pPanel[num].style.display = "block";
        pPanel_buttons[num].style.color = '#cad8e2';
        pPanel_buttons[num].style.backgroundColor = '#5080a2';
    } 
    if(location.search.includes('spUsername')) {
        pPanel[0].style.display = 'none';
        pPanel[1].style.display = 'none';
        pPanel[3].style.display = 'none';
        pPanel[4].style.display = 'none';
        pPanel_buttons[num].style.color = '#5080a2';
        pPanel_buttons[num].style.backgroundColor = '#cad8e2';
        pPanel[2].style.display = 'block';
        pPanel_buttons[2].style.color = '#cad8e2';
        pPanel_buttons[2].style.backgroundColor = '#5080a2';
    }
    if(location.search.includes('forum_friend')) {
        pPanel[0].style.display = 'none';
        pPanel[1].style.display = 'none';
        pPanel[2].style.display = 'none';
        pPanel[3].style.display = 'none';
        pPanel_buttons[num].style.color = '#5080a2';
        pPanel_buttons[num].style.backgroundColor = '#cad8e2';
        pPanel[4].style.display = 'block';
        pPanel_buttons[4].style.color = '#cad8e2';
        pPanel_buttons[4].style.backgroundColor = '#5080a2';
    }
}

function login_modal() {
    var modal = document.getElementById('login_modal');
        modal.style.display = 'block';
}
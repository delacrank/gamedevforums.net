var pForm_buttons = document.getElementsByClassName('pForm_buttons'),
    pForm_display = document.getElementsByClassName('pForm_display'),
    formIndex = 1;

function chooseDisplay (n) {
    showDisplays(formIndex = n);
}

function showDisplays(n) {
    var i;
    if(n > pForm_display.length) {
        formIndex = 1;
    }
    if(n < 1) {
        formIndex = pForm_display.length;
    }
    if(pForm_display[formIndex - 1].style.display === 'block'){
        pForm_display[formIndex - 1].style.display = 'none';
    } else {
        for(i = 0; i < pForm_display.length; i++) {
            pForm_display[i].style.display = 'none';
        }
        if(pForm_display[formIndex - 1].style.display === 'none') {
            pForm_display[formIndex - 1].style.display = 'block';
        } 
    }
}
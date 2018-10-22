/*jslint browser: true*/
/* eslint-env browser */
/*jslint node: true */

var nav = document.getElementById('navigation'),
    mini_nav = document.getElementById('menu'),
    screen_h = document.documentElement.scrollHeight;

mini_nav.style.paddingBottom = screen_h - 346 + 'px';


if(location.search.includes('home')) {
    nav.childNodes[1].childNodes[1].childNodes[0].style.color ='red';
    mini_nav.childNodes[1].childNodes[0].style.color ='red';
} else {
    nav.childNodes[1].childNodes[1].childNodes[0].style.color ='#e2caca';
    mini_nav.childNodes[1].childNodes[0].style.color ='#e2caca';
}

if(location.search.includes('forums') || location.search.includes('display_messages') || location.search.includes('display_posts')) {
    nav.childNodes[1].childNodes[3].childNodes[0].style.color ='red';
    mini_nav.childNodes[3].childNodes[0].style.color ='red';
} else {
    nav.childNodes[1].childNodes[3].childNodes[0].style.color ='#e2caca';
    mini_nav.childNodes[3].childNodes[0].style.color ='#e2caca';
}

if(location.search.includes('article')) {
    nav.childNodes[1].childNodes[5].childNodes[0].style.color ='red';
    mini_nav.childNodes[5].childNodes[0].style.color ='red';  
} else {
    nav.childNodes[1].childNodes[5].childNodes[0].style.color ='#e2caca';
    mini_nav.childNodes[5].childNodes[0].style.color ='#e2caca';   
}

if(location.search.includes('logout')) {
    nav.childNodes[1].childNodes[7].childNodes[0].style.color ='red';
    mini_nav.childNodes[7].childNodes[0].style.color ='red';
} else {
    nav.childNodes[1].childNodes[7].childNodes[0].style.color ='#e2caca';
    mini_nav.childNodes[7].childNodes[0].style.color ='#e2caca';
}

if(nav.childNodes[1].childNodes[8].childNodes[0]) {
    if(location.search.includes('profile') || location.search.includes('view_conversation')) {
        nav.childNodes[1].childNodes[8].childNodes[0].style.color ='red';
        mini_nav.childNodes[8].childNodes[0].style.color ='red';
    } else {
        nav.childNodes[1].childNodes[8].childNodes[0].style.color ='#e2caca';
        mini_nav.childNodes[8].childNodes[0].style.color ='#e2caca';
    }
}




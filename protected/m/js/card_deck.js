'use strict';
console.log("Мы в скрипте");
/*


document.querySelector('.card-deck').onmousewheel = function (event) {
    event = event || window.event;
    console.log(event);
    return false;
};
document.querySelector('.card-deck').onscroll = function () {
    var scrolled = window.pageYOffset || document.documentElement.scrollTop;
    console.log(scrolled + 'px');
    console.log("test");
};

*/

/*window.onload = function () {
    document.querySelector('wraper').onwheel = function (event) {
        event = event || window.event;
        console.log(event);
        return false;
    };
};*/

/*
 Метод	                Ищет по...	    вн?	Поддержка
getElementById	        id	            -	везде
getElementsByName	    name	        -	везде
getElementsByTagName	тег или '*'	    ✔	везде
getElementsByClassName	классу	        ✔   кроме IE8-
querySelector	        CSS-селектор	✔	везде
querySelectorAll	    CSS-селектор	✔	везде*/


let scrollDeltaY = 0;
let windowsSize = 400;
let elemWrap = document.getElementsByClassName('card-deck')[0];
let elemCard1 = document.getElementsByClassName('card1')[0];
let elemCard2 = document.getElementsByClassName('card2')[0];
let elemCard3 = document.getElementsByClassName('card3')[0];

if (elemWrap.addEventListener) {
    if ('onwheel' in document) {
        // IE9+, FF17+
        elemWrap.addEventListener("wheel", onWheel);

    } else if ('onmousewheel' in document) {
        // устаревший вариант события
        elemWrap.addEventListener("mousewheel", onWheel);
    } else {
        // Firefox < 17
        elemWrap.addEventListener("MozMousePixelScroll", onWheel);
    }
} else { // IE8-
    elemWrap.attachEvent("onmousewheel", onWheel);
}

// Это решение предусматривает поддержку IE8-
function onWheel(e) {
    e = e || window.event;

    // deltaY, detail содержат пиксели
    // wheelDelta не дает возможность узнать количество пикселей
    // onwheel || MozMousePixelScroll || onmousewheel
    let delta = e.deltaY || e.detail || e.wheelDelta;
    if (scrollDeltaY >= 0) {
        scrollDeltaY += delta * 5;
    }else {
        scrollDeltaY =0;
    }

    if (scrollDeltaY <= windowsSize) {

        elemCard1.style.marginTop = -scrollDeltaY + "px";
    } else if (+scrollDeltaY <= windowsSize * 2) {
        elemCard2.style.marginTop = -(scrollDeltaY - windowsSize) + "px";
    } else {
        elemCard3.style.marginTop = -(scrollDeltaY - windowsSize * 2) + "px";
    }
    console.log(scrollDeltaY);
    e.preventDefault ? e.preventDefault() : (e.returnValue = false);
}
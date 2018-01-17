'use strict';
window.onload = function () {

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
            scrollDeltaY += delta;
        } else {
            scrollDeltaY = 0;
        }

        if (scrollDeltaY <= windowsSize) {
            elemCard1.style.marginTop = -scrollDeltaY + "px";
            elemCard2.style.opacity = (""+scrollDeltaY/windowsSize);
        } else if (+scrollDeltaY <= windowsSize * 2) {
            elemCard2.style.marginTop = -(scrollDeltaY - windowsSize) + "px";
            elemCard3.style.opacity = (""+(scrollDeltaY - windowsSize)/windowsSize);
        } else {
            elemCard3.style.marginTop = -(scrollDeltaY - windowsSize * 2) + "px";
        }

               console.log(scrollDeltaY);
        e.preventDefault ? e.preventDefault() : (e.returnValue = false);
    }
};
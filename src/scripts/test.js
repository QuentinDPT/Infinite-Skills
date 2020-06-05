(function() {
    "use strict";

    // Variables ==============================================================
    var contextMenuClassName = "context-menu";
    var contextMenuItemClassName = "context-menu__item";
    var contextMenuLinkClassName = "context-menu__link";
    var contextMenuActive = "context-menu--active";

    var videoItemClassName = "video";
    var videoItemInContext;

    var videoItems = document.querySelectorAll(".video"); // video
    var menu = document.querySelector("#context-menu");

    var clickCoords;
    var clickCoordsX;
    var clickCoordsY;

    var menu = document.querySelector("#context-menu");
    var menuItems = menu.querySelector(".context-menu__item");
    var menuState = 0;
    var menuWidth;
    var menuHeight;
    var menuPosition;
    var menuPositionX;
    var menuPositionY;

    var windowWidth;
    var windowHeight;
    // ========================================================================



    // Helper functions =======================================================
    function clickInsideElement(e, className) {
        var el = e.srcElement || e.target;

        if (el.classList.contains(className)) {
            return el;
        } else {
            while (el = el.parentNode) {
                if (el.classList && el.classList.contains(className)) {
                    return el;
                }
            }
        }
        return false;
    }
    // ========================================================================



    function init() {
        contextListener();
        clickListener();
        keyupListener();
        resizeListener();
    }
    function contextListener() {
        document.addEventListener("contextmenu", e => {
            videoItemInContext = clickInsideElement(e, videoItemClassName);

            if (videoItemInContext) {
                e.preventDefault();
                toggleMenuOn();
                positionMenu(e);
            }
            else {
                videoItemInContext = null;
                toggleMenuOff();
            }
        });
    }
    function clickListener() {
        document.addEventListener( "click", function(e) {
            var clickeElIsLink = clickInsideElement( e, contextMenuLinkClassName );

            if ( clickeElIsLink ) {
                e.preventDefault();
                menuItemListener( clickeElIsLink );
            } else {
                var button = e.which || e.button;
                if ( button === 1 ) {
                    toggleMenuOff();
                }
            }
        });
    }
    function keyupListener() {
        window.onkeyup = function(e) {
            if ( e.keyCode === 27 ) {
                toggleMenuOff();
            }
        }
    }
    function toggleMenuOn() {
        if (menuState !== 1) {
            menuState = 1;
            menu.classList.add(contextMenuActive);
        }
    }
    function toggleMenuOff() {
        if (menuState !== 0) {
            menuState = 0;
            menu.classList.remove(contextMenuActive);
        }
    }
    function getPosition(e) {
        var posx = 0;
        var posy = 0;

        if (!e) var e = window.event;

        if (e.pageX || e.pageY) {
            posx = e.pageX;
            posy = e.pageY;
        } else if (e.clientX || e.clientY) {
            posx = e.clientX + document.body.scrollLeft +
                               document.documentElement.scrollLeft;
            posy = e.clientY + document.body.scrollTop +
                               document.documentElement.scrollTop;
        }

        return {
            x: posx,
            y: posy
        }
    }
    function positionMenu(e) {
        clickCoords = getPosition(e);
        clickCoordsX = clickCoords.x;
        clickCoordsY = clickCoords.y;

        menuWidth = menu.offsetWidth + 4;
        menuHeight = menu.offsetHeight + 4;

        windowWidth = window.innerWidth;
        windowHeight = window.innerHeight;

        if ( (windowWidth - clickCoordsX) < menuWidth ) {
            menu.style.left = windowWidth - menuWidth + "px";
        } else {
            menu.style.left = clickCoordsX + "px";
        }

        if ( (windowHeight - clickCoordsY) < menuHeight ) {
            menu.style.top = clickCoordsY - menuHeight + "px";
        } else {
            menu.style.top = clickCoordsY + "px";
        }
    }
    function resizeListener() {
        window.onresize = function(e) {
            toggleMenuOff();
        };
    }
    function menuItemListener( link ) {
        console.log( "Task ID - " +
                    videoItemInContext.getAttribute("data-id") +
                    ", Task action - " + link.getAttribute("data-action"));
        toggleMenuOff();
    }
    init();





    /*for (var i = 0, len = themeItems.length; i < len; i++) {
        var themeItem = themeItems[i];
        contextMenuListener(themeItem);
    }
    function contextMenuListener(el) {

    }*/
})();
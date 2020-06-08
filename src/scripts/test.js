let xmlhttp= window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");

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



function createModal(type, forms=[], inputs=[], buttons=[]) {
    var inputs = [];
    var forms = [];
    switch (type) {
        case "login":
            forms = [
                {
                    id: "form-auth",
                    method: "post",
                    action: "/api/authenticate",
                    title: "Login",
                    text: "Do you want to have access to all your purchases? Just login!"
                },
                {
                    id: "form-register",
                    method: "post",
                    action: "/api/signup",
                    title: "Register",
                    text: "Access Infinite skills content from anywhere!"
                }
            ]
            title = "LOGIN";
            inputs = [
                {
                    name: "login",
                    icon: "https://upload.wikimedia.org/wikipedia/commons/thumb/1/12/User_icon_2.svg/600px-User_icon_2.svg.png",
                    placeholder: "Username",
                    required: true,
                    type: "text",
                    form: "form-auth"
                },
                {
                    name: "password",
                    icon: "https://image.flaticon.com/icons/png/512/891/891399.png",
                    placeholder: "Password",
                    required: true,
                    type: "password",
                    form: "form-auth"
                },
                {
                    name: "login",
                    icon: "https://upload.wikimedia.org/wikipedia/commons/thumb/1/12/User_icon_2.svg/600px-User_icon_2.svg.png",
                    placeholder: "Username",
                    required: true,
                    type: "text",
                    form: "form-register"
                },
                {
                    name: "password",
                    icon: "https://image.flaticon.com/icons/png/512/891/891399.png",
                    placeholder: "Password",
                    required: true,
                    type: "password",
                    form: "form-register"
                },
                {
                    name: "password_confirm",
                    icon: "https://image.flaticon.com/icons/png/512/891/891399.png",
                    placeholder: "Confirm password",
                    required: true,
                    hidden: true,
                    type: "password",
                    form: "form-register"
                },
                {
                    name: "mail",
                    icon: "https://cdn.iconscout.com/icon/free/png-256/mail-1140-830582.png",
                    placeholder: "Mail",
                    required: true,
                    hidden: true,
                    type: "mail",
                    form: "form-register"
                },
                {
                    name: "mail_confirm",
                    icon: "https://cdn.iconscout.com/icon/free/png-256/mail-1140-830582.png",
                    placeholder: "Confirm mail",
                    required: true,
                    hidden: true,
                    type: "mail",
                    form: "form-register"
                }
            ]
            buttons = [
                {
                    name: "Login",
                    class: "btn btn-success",
                    type: "submit",
                    form: "form-auth"
                },
                {
                    name: "Register",
                    class: "btn bg-primary-color basic",
                    click: "changeForm('form-register');",
                    type: "button",
                    form: "form-auth"
                },
                {
                    name: "Create!",
                    class: "btn btn-success",
                    type: "submit",
                    form: "form-register"
                },
                {
                    name: "Login",
                    class: "btn bg-primary-color basic",
                    click: "changeForm('form-auth');",
                    type: "button",
                    form: "form-register"
                }
            ]
            break;
        default:

    }

    var overlay = document.createElement("div");
        overlay.className = "overlay";
        overlay.id = "dynamicOverlay";
    var container = document.createElement("div");
        container.className = "overlay-container p-2";
    overlay.appendChild(container);

    var divQuit = document.createElement("div");
        divQuit.className = "overlay-quit mr-2";
    var cross = document.createElement("button");
        cross.className = "btn btn-sm btn-danger overlay-quit-text";
        cross.innerText = "×";
        cross.onclick = quitOverlay;
    divQuit.appendChild(cross);
    container.appendChild(divQuit);

    var ovtitle = document.createElement("span");
        ovtitle.className = "overlay-title";
        ovtitle.innerText = forms[0].title;
        ovtitle.id = "ovtitle";
    var r = document.createElement("div");
        r.className = "row mb-4";
    var c = document.createElement("div");
        c.className = "col-12 centered-h";
    c.appendChild(ovtitle);
    r.appendChild(c);
    container.appendChild(r);

    var ovtext = document.createElement("span");
        ovtext.className = "overlay-text";
        ovtext.innerText = forms[0].text ? forms[0].text : "";
        ovtext.id = "ovtext";
    var r = document.createElement("div");
        r.className = "row mb-4";
    var c = document.createElement("div");
        c.className = "col-12 centered-h";
    c.appendChild(ovtext);
    r.appendChild(c);
    container.appendChild(r);

    for (var j = 0; j < forms.length; j++) {
        var f = document.createElement("form");
            f.id = forms[j].id;
            f.method = forms[j].method;
            f.title = forms[j].title;
            f.text = forms[j].text;
            if (j != 0) f.className = "hidden";
        for (var i = 0; i < inputs.length; i++) {
            if (inputs[i].form == f.id) {
                var row = document.createElement("div");
                    row.className = "row mb-4";
                var col = document.createElement("div");
                var colIcon = null;
                var inp = document.createElement("input");
                    inp.className = "form-control";
                    inp.placeholder = inputs[i].placeholder;
                    inp.type = inputs[i].type;
                    inp.name = inputs[i].name;
                    inp.required = (inputs[i].required ? inputs[i].required : false);
                if (inputs[i].icon) {
                    colIcon = document.createElement("div");
                    colIcon.className = "col-3 flex centered-h";
                    col.className = "col-9 flex centered-h";
                    var img = document.createElement("img");
                        img.className = "overlay-icon";
                        img.src = inputs[i].icon;
                    colIcon.appendChild(img);
                }
                else {
                    col.className = "col-12 flex centered-h";
                }
                col.appendChild(inp);
                if (colIcon != null) row.appendChild(colIcon);
                row.appendChild(col);
                f.appendChild(row);
            }
        }

        var fbut = [];
        for (var i = 0; i < buttons.length; i++) {
            if (buttons[i].form == f.id) {
                var b = document.createElement("button");
                    b.type = buttons[i].type;
                    if (buttons[i].click) b.setAttribute("onclick", buttons[i].click);
                    b.className = buttons[i].class;
                    b.innerText = buttons[i].name;
                fbut.push(b);
            }
        }

        var size = 12 / fbut.length < 3 ? 3 : 12 / fbut.length;
        var rowB = document.createElement("div");
            rowB.className = "row";
        for (var i = 0; i < fbut.length; i++) {
            var colB = document.createElement("div");
                colB.className = "col-" + size + " centered-h";
            colB.appendChild(fbut[i]);
            rowB.appendChild(colB);
        }
        f.appendChild(rowB);
        container.appendChild(f);
    }
    document.body.appendChild(overlay);
    document.body.style.overflow = "hidden";

    if (type == "login") initConnect();
}

function quitOverlay() {
    document.getElementById("dynamicOverlay").remove();
    document.body.style.overflow = "auto";
}
function changeForm(id) {
    var forms = Array.from(document.getElementById("dynamicOverlay").children[0].getElementsByTagName("form"));

    for (var i = 0; i < forms.length; i++) {
        if (forms[i].id != id) {
            forms[i].classList.add("hidden");
        }
        else {
            forms[i].classList.remove('hidden');
            document.getElementById('ovtitle').innerText = forms[i].title;
            document.getElementById("ovtext").innerText = forms[i].text ? forms[i].text : "";
        }
    }

}
function initConnect() {
    $("#form-auth").on("submit", function(e){
        e.preventDefault();
        let data = $(this).serialize();
        $.ajax({
           type: "POST",
           url: "/api/authenticate",
           data: data,
           success: function(res){
               if(res == 1){
                   location.href = "/home";
               }else{
                   $("#auth-error").remove();
                   $("<span id='auth-error' class='badge badge-danger mb-4'>Incorrect login or password</span>").insertBefore("#form-auth");
               }
           }
        });
    });
    $("#form-register").on("submit", function(e){
        e.preventDefault();
        let data = $(this).serialize();
        $.ajax({
           type: "POST",
           url: "/api/signup",
           data: data,
           success: function(res){
               if(res == 1){
                   location.href = "/home";
               }else{
                   $("#reg-error").remove();
                   $("<span id='reg-error' class='badge badge-danger mb-4'>An error occured</span>").insertBefore("#form-register");
               }
           }
        });
    });
}

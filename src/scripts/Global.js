// Get a form by it's id and submit it.
function submitForm(elem, formId) {
    // Get the form
    var form = document.getElementById(formId);

    // Complete actions related to form before we submit it
    switch (formId) {
        case "formVideo":
            // Init video id field for GET
            var videoId = elem.getElementsByTagName("img")[0].id;
            document.getElementById("video_id").value = videoId;
            break;
        case "formFollow":
            // Init owner id field for GET
            var ownerId = elem.getElementsByTagName("img")[0].id;
            document.getElementById("follow_id").value = ownerId;
            break;
        case "formFollowOwner":
            // If user wants to unfollow
            if (Array.from(elem.classList).indexOf("user-followed") != -1) {
                elem.innerText = "FOLLOW";
                elem.classList.remove("user-followed");
                elem.classList.add("btn-primary");
            }
            // If user wants to follow
            else {
                elem.innerText = "FOLLOWED"
                elem.classList.add("user-followed");
                elem.classList.remove("btn-primary");
            }
            break;
        case "formLike":
            // If user wants to unlike
            if (Array.from(elem.classList).indexOf("video-liked") != -1) {
                elem.innerText = "LIKE";
                elem.classList.remove("video-liked");
                elem.classList.add("btn-success");
            }
            // If user wants to like
            else {
                elem.innerText = "LIKED"
                elem.classList.add("video-liked");
                elem.classList.remove("btn-success");
            }
            break;
        case "formEditDesc":
        case "userForm":
        case "formConnect":
        default: break;
    }

    // Submit form
    form.submit();
}
// Expend a div to display hidden content
function readMore(span, divId) {
    var div = document.getElementById(divId);
    div.classList.add("user-text-more");
    span.setAttribute('onclick', "readLess(this, '" + divId + "')");
    span.innerText = "Less";
}
// Collapse expended div
function readLess(span, divId) {
    var div = document.getElementById(divId);
    div.classList.remove("user-text-more");
    span.setAttribute('onclick', "readMore(this, '" + divId + "')");
    span.innerText = "Read more";
}
// Format numbers to add a unit at the end
function formatNumber(num) {
    if (num >= 1000000000) return (num / 1000000000).toFixed(3) + "Mi";
    else if (num >= 1000000) return (num / 1000000).toFixed(3) + "M";
    else if (num >= 1000) return (num / 1000).toFixed(3) + "k";
    return num;
}
// Dynamically update follower counts
function changeFollowers() {
    var btn = document.getElementById("btnFollowOwner");
    var span = document.getElementById("spanFollowers")
    if (Array.from(btn.classList).indexOf("user-followed") > -1) {
        span.innerText = formatNumber(followers + 1) + " follower(s)";
    }
    else span.innerText = formatNumber(followers) + " follower(s)";
}


// OVERLAY ====================================================================
// Create an overlay
function createModal(type, redirect, forms=[], inputs=[], buttons=[], additional=[]) {
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
            additional = [
                {
                    name: "forgotPassword",
                    class: "clickable primary",
                    type: "span",
                    click: "location.href = '/forgotPassword'",
                    text: "Forgot password?",
                    form: "form-auth"
                }
            ]
            break;
        default: break;
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

    var firstInp = null;
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

                if (i==0) firstInp = inp;
            }
        }

        for (var i=0; i < additional.length; i++) {
            var a = additional[i]
            if (additional[i].form == f.id) {
                var row = document.createElement("div");
                    row.className = "row mb-4";
                var col = document.createElement("div");
                    col.className = "col-12 flex centered-h";
                var e = document.createElement(a.type);
                    e.className = a.class;
                    if (a.click) e.setAttribute("onclick", a.click);
                    e.name = a.name;
                    e.innerText = a.text;
                col.appendChild(e);
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

    if (type == "login") initConnect(redirect);
}
// Remove the overlay
function quitOverlay() {
    document.getElementById("dynamicOverlay").remove();
    document.body.style.overflow = "auto";
}
// Switch between forms in overlay
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
// Init ajax for dynamically added forms
function initConnect(redirect) {
    $("#form-auth").on("submit", function(e){
        e.preventDefault();
        let data = $(this).serialize();
        $.ajax({
           type: "POST",
           url: "/api/authenticate",
           data: data,
           success: function(res){
               if(res == 1){
                   location.href = redirect;
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
                   location.href = redirect;
               }else{
                   $("#reg-error").remove();
                   $("<span id='reg-error' class='badge badge-danger mb-4'>An error occured</span>").insertBefore("#form-register");
               }
           }
        });
    });
}
// ============================================================================

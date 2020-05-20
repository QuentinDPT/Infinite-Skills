function openFollowed(btn) {
    btn.classList.toggle("followed-btn-shown");
    btn.innerText = (btn.innerText == "+" ? "-" : "+");
    document.getElementById("divFollowed").classList.toggle("followed-div-shown");
    document.getElementById("followedContainer").classList.toggle("col-1");
    document.getElementById("followedContainer").classList.toggle("col-sm-1");
    document.getElementById("followedContainer").classList.toggle("col-md-1");
    document.getElementById("followedContainer").classList.toggle("col-12");
    document.getElementById("followedContainer").classList.toggle("col-sm-12");
    document.getElementById("followedContainer").classList.toggle("col-md-12");document.getElementById("followedContainer").classList.toggle("followed-container-shown");
    document.body.classList.toggle("no-scroll");
}
function addFollowed(id, imgsrc, name) {
    var div = document.getElementById("divFollowed").children[1];

    var row = document.createElement("div");
        row.className = "row pb-2";
        row.id = "followed-" + id;
        row.setAttribute("onclick", "submitForm(this, 'formFollow')");
    var divImg = document.createElement("div");
        divImg.className = "col-3 followed-img-container";
    var img = document.createElement("img");
        img.className = "rounded-circle followed-img";
        img.src = imgsrc;
        img.alt = "Avatar";
    var divName = document.createElement("div");
        divName.className = "col-9 followed-name-container";
    var span = document.createElement("span");
        span.className = "basic";
        span.innerText = name;

    divName.appendChild(span);
    divImg.appendChild(img);
    row.appendChild(divImg);
    row.appendChild(divName);
    div.appendChild(row);

    var noOne = document.getElementById("followed-no-one");
    if (noOne != undefined) {
        noOne.remove();
    }
}
function removeFollowed(id) {
    var div = document.getElementById("divFollowed").children[1];
    document.getElementById("followed-" + id).remove();

    if (div.childElementCount < 1) {
        var span = document.createElement("span");
            span.className = "basic";
            span.innerText = "You followed no one :(";
            span.id = "followed-no-one";
        div.appendChild(span);
    }
}

function readMore(span, divId) {
    var div = document.getElementById(divId);
    div.classList.add("comment-text-more");
    span.setAttribute('onclick', "readLess(this, '" + divId + "')");
    span.innerText = "Less";
}
function readLess(span, divId) {
    var div = document.getElementById(divId);
    div.classList.remove("comment-text-more");
    span.setAttribute('onclick', "readMore(this, '" + divId + "')");
    span.innerText = "Read more";
}
function showComments(btn) {
    document.getElementById("comments").classList.toggle("comments-show");
    btn.innerText = (btn.innerText == "Display" ? "Hide" : "Display");
}
function changeLike() {
    var btn = document.getElementById("btnLike");
    var span = document.getElementById("spanLikes")
    if (Array.from(btn.classList).indexOf("video-liked") > -1) {
        span.innerText = formatNumber(likes + 1);
    }
    else span.innerText = formatNumber(likes);
}
function changeFollowedList() {
    var btn = document.getElementById("btnFollowOwner");
    var idUser = document.getElementById("u").value;
    var imgUser = document.getElementById(idUser).src;
    console.log(imgUser);
    var nameUser = document.getElementById("ownerName").innerText;

    // If we follow
    if (Array.from(btn.classList).indexOf("user-followed") > -1) addFollowed(idUser, imgUser, nameUser);
    // If we unfollow
    else removeFollowed(idUser);
}

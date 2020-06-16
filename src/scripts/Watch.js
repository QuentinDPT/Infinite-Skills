// Display hidden content
function readMore(span, divId) {
    var div = document.getElementById(divId);
    div.classList.add("comment-text-more");
    span.setAttribute('onclick', "readLess(this, '" + divId + "')");
    span.innerText = "Less";
}
// Hide content
function readLess(span, divId) {
    var div = document.getElementById(divId);
    div.classList.remove("comment-text-more");
    span.setAttribute('onclick', "readMore(this, '" + divId + "')");
    span.innerText = "Read more";
}
// Show comments section for mobile
function showComments(btn) {
    document.getElementById("comments").classList.toggle("comments-show");
    btn.innerText = (btn.innerText == "Display" ? "Hide" : "Display");
}
// Dynamically add or remove a like
function changeLike() {
    var btn = document.getElementById("btnLike");
    var span = document.getElementById("spanLikes")
    if (Array.from(btn.classList).indexOf("video-liked") > -1) {
        span.innerText = formatNumber(likes + 1);
    }
    else span.innerText = formatNumber(likes);
}
// Dynamically add or remove a user form followed list
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
// Prevent user to submit an empty comment
function commentChanged(txt) {
    var replaced = txt.value.replace(/\s/g, '');
    if (replaced.length > 0) {
        $("#subComment").removeAttr("disabled");
    }
    else $("#subComment").attr("disabled", true);
}
// Submit a new comment
$("#form-comment").on("submit", function(e){
    e.preventDefault();
    let data = $(this).serialize();
    $.ajax({
       type: "GET",
       url: "/new-comment",
       data: data,
       success: function(res){
           if(res == 0){
               console.log("error");
           }else{
               let isVisible = $("#no-comment").is(":visible");
               if(isVisible){
                   $("#no-comment").hide();
               }
               $("#list-comments").prepend(res);
               $("#subComment").attr("disabled", true);
               $("#newComment").val("");
               $("#newComment").get(0).placeholder = "Wait 1m until next comment...";
               $("#newComment").attr("disabled", true);
               setTimeout(() => {
                   $("#subComment").removeAttr("disabled");
                   $("#newComment").removeAttr("disabled");
                   $("#newComment").get(0).placeholder = "Type your comment!";
               }, 60000)
               setCommentHeight();
           }
       }
    });
});
// Collapse long comments and add "read more" buttons
function setCommentHeight() {
    var list = $(".comment-text-container");
    for (var i = 0; i < list.length; i++) {
        var h = $("#" + list[i].children[1].id).height();
        if (h > 65 && list[i].childElementCount < 3) {
            var div = document.createElement("div");
                div.className = "comment-next";
            var span = document.createElement("span");
                span.className = "comment-button";
                span.setAttribute("onclick", "readMore(this, '" + list[i].children[1].id + "')");
                span.innerText = "Read more";
            div.appendChild(span)
            list[i].appendChild(div);
            list[i].children[1].classList.add("comment-text-after");
        }
    }
}
// When page is loaded, collapse long comments
$(document).ready(setCommentHeight);

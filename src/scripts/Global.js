/*  submitForm : Get a form by it's id and submit it.
 *  Input:
 *      - elem: html node which contains additionnal info
 *      - formId: Id of the form
 */
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
function readMore(span, divId) {
    var div = document.getElementById(divId);
    div.classList.add("user-text-more");
    span.setAttribute('onclick', "readLess(this, '" + divId + "')");
    span.innerText = "Less";
}
function readLess(span, divId) {
    var div = document.getElementById(divId);
    div.classList.remove("user-text-more");
    span.setAttribute('onclick', "readMore(this, '" + divId + "')");
    span.innerText = "Read more";
}
function formatNumber(num) {
    if (num >= 1000000000) return (num / 1000000000).toFixed(3) + "Mi";
    else if (num >= 1000000) return (num / 1000000).toFixed(3) + "M";
    else if (num >= 1000) return (num / 1000).toFixed(3) + "k";
    return num;
}
function changeFollowers() {
    var btn = document.getElementById("btnFollowOwner");
    var span = document.getElementById("spanFollowers")
    if (Array.from(btn.classList).indexOf("user-followed") > -1) {
        span.innerText = formatNumber(followers + 1) + " follower(s)";
    }
    else span.innerText = formatNumber(followers) + " follower(s)";
}

function changeFollowedList() {
    var btn = document.getElementById("btnFollowOwner");
    var idUser = document.getElementById("ownerId").value;
    var imgUser = document.getElementById("ownerImage").src;
    console.log(imgUser);
    var nameUser = document.getElementById("ownerName").innerText;

    // If we follow
    if (Array.from(btn.classList).indexOf("user-followed") > -1) addFollowed(idUser, imgUser, nameUser);
    // If we unfollow
    else removeFollowed(idUser);
}
function editDesc(cancel = false) {
    var div = document.getElementById("desc");
    var txt = document.getElementById("descTxt");
    var btn = document.getElementById("btnEditDesc");
    var btnCancel = document.getElementById("btnCancelDesc");

    // We want to edit
    if (Array.from(div.classList).indexOf("user-hidden") == -1) {
        txt.value = div.children[0].innerText;
        div.classList.toggle("user-hidden");
        txt.classList.toggle("user-hidden");
        btn.innerText = "Save"
        btnCancel.classList.toggle("user-hidden");
    }
    // We want to save new desc
    else {
        div.classList.toggle("user-hidden");
        txt.classList.toggle("user-hidden");
        btn.innerText = "Edit"
        btnCancel.classList.toggle("user-hidden");
        if (!cancel) {
            div.children[0].innerText = txt.value;
            document.getElementById("descHiddenInput").value = txt.value;
            submitForm(null, "formEditDesc");
        }
    }
}

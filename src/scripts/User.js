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
function changeUserPage(btn) {
    var divCreate = document.getElementById("divCreate");
    var divSearch = document.getElementById("divSearch");

    // We want to edit / create
    if (Array.from(btn.classList).indexOf("bg-primary-color") > -1) {
        btn.innerText = "Cancel";
    }
    // We want to cancel
    else {
        btn.innerText = "Create or edit videos";
    }

    btn.classList.toggle("bg-primary-color");
    btn.classList.toggle("bg-warning");
    divCreate.classList.toggle("user-hidden");
    divSearch.classList.toggle("user-hidden");
}
function openFile() {
    document.getElementById("file").click();
}
function loadFile() {
    var file = document.getElementById("file").files[0];
    if (file == undefined) return;
    var name = file.name.split(".");
    document.getElementById("spanFileName").innerText = "File name: " + name.slice(0, -1).join(".");
    document.getElementById("spanFileType").innerText = "Type: " + name[name.length-1];
    var p = document.getElementById("txtPrice");
    p.disabled = false;
    document.getElementById("typeVideo").value = "file";
}
function useUrl(cancel = false) {
    document.getElementById("divUrl").classList.toggle("user-hidden");

    if (cancel) return;
    document.getElementById("spanFileName").innerText = "Link: " + document.getElementById("txtUrl").value;
    document.getElementById("spanFileType").innerText = "Type: Youtube video";
    var p = document.getElementById("txtPrice");
    p.value = 0;
    p.disabled = true;
    document.getElementById("typeVideo").value = "youtube";
}
function useUrlImg(cancel = false) {
    document.getElementById("divUrlImg").classList.toggle("user-hidden");

    if (cancel) return;
    document.getElementById("imgNewVideo").src = document.getElementById("txtUrlImg").value;
}
function subForm() {
    document.getElementById("txtPrice").disabled = false;
}

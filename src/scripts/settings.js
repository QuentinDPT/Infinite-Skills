function openEditMode(cancel = false) {
    var div = document.getElementById("divAccount");
    div.classList.toggle("account-container-active");
    var divBtnEdit = document.getElementById("divBtnEdit");
    var txtUsername = document.getElementById("txtUsername");
    var spanUsername = document.getElementById("spanUsername");
    var txtMail = document.getElementById("txtMail");
    var spanMail = document.getElementById("spanMail");
    var divPass = document.getElementById("divPass");
    var divBtn = document.getElementById("divBtn");

    if (cancel) {
        divBtnEdit.classList.remove("hidden");
        divPass.classList.add("hidden");
        txtUsername.classList.add("hidden");
        txtMail.classList.add("hidden");
        spanUsername.classList.remove("hidden");
        spanMail.classList.remove("hidden");
        divBtn.classList.add("hidden");
        document.getElementById("divNewPass").classList.add("hidden");
        document.getElementById("spanChangePass").classList.remove("hidden");
    }
    else {
        divBtnEdit.classList.add("hidden");
        divPass.classList.remove("hidden");
        txtUsername.classList.remove("hidden");
        txtMail.classList.remove("hidden");
        spanUsername.classList.add("hidden");
        spanMail.classList.add("hidden");
        divBtn.classList.remove("hidden");
        document.getElementById("txtNewPass").disabled = true;
        document.getElementById("txtNewPassConfirm").disabled = true;
        document.getElementById("txtPass").value = "";
        document.getElementById("txtNewPass").value = "";
        document.getElementById("txtNewPassConfirm").value = "";
    }
}
function openDivPass() {
    document.getElementById("divNewPass").classList.remove("hidden");
    document.getElementById("spanChangePass").classList.add("hidden");
    document.getElementById("txtNewPass").disabled = false;
    document.getElementById("txtNewPassConfirm").disabled = false;
}
function changePfp(cancel = false) {
    if (Array.from(document.getElementById("divBtnEdit").classList).indexOf("hidden") == -1) return;
    div = document.getElementById("overlayPfp");
    div.classList.toggle("hidden");
    if (cancel) return;

    document.getElementById("pfp").src = document.getElementById("urlNewPfp").value;
}
function openOverlayDelete(cancel = false) {
    document.getElementById("overlayDelete").classList.toggle("hidden");
    if (!cancel) {
        document.getElementById("form-del").submit();
    }
}

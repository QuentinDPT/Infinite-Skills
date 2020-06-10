// Key enter pressed in research bar
document.getElementById("searchInput").addEventListener("keyup", e => {
    if (e.keyCode == 13) {
        document.getElementById("formSearch").submit();
    }
});
const currentTheme = localStorage.getItem('theme') ? localStorage.getItem('theme') : "light-blue";
document.documentElement.setAttribute('data-theme', currentTheme);

// Get theme
function getTheme() {
    var val =  changeColor.selectedOptions[0].value;
    var res = "blue";
    switch (val) {
        case "blue": res = "blue"; break;
        case "green": res = "green"; break;
        default: res = "orange"; break;
    }
    return res;
}
// Get current theme
function getCurrentTheme() {
    if (currentTheme.endsWith('blue')) return "blue";
    if (currentTheme.endsWith('orange')) return "orange";
    if (currentTheme.endsWith('green')) return "green";
}
// Get selected index
function getIndex(select, value) {
    for (var i = 0; i < select.options.length; i++) {
        if (select.options[i].value == value) return i;
    }
    return 0;
}

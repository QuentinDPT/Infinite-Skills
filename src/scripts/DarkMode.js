/*const toggleSwitch = document.querySelector('.theme-switch input[type="checkbox"]');
const changeColor = document.getElementById('select_bg_color');

document.getElementById("searchInput").addEventListener("keyup", e => {
    if (e.keyCode == 13) {
        document.getElementById("formSearch").submit();
    }
});
toggleSwitch.addEventListener('change', switchTheme, false);
changeColor.addEventListener('change', switchTheme, false);
const currentTheme = localStorage.getItem('theme') ? localStorage.getItem('theme') : "light-blue";

if (currentTheme) {
    document.documentElement.setAttribute('data-theme', currentTheme);

    if (currentTheme.startsWith('dark')) {
        toggleSwitch.checked = true;
    }
    changeColor.selectedIndex = getIndex(changeColor, getCurrentTheme());
}

function switchTheme(e) {
    if (e.target.checked || toggleSwitch.checked) {
        document.documentElement.setAttribute('data-theme', 'dark-' + getTheme());
        localStorage.setItem('theme', 'dark-' + getTheme()); //add this
    }
    else {
        document.documentElement.setAttribute('data-theme', 'light-' + getTheme());
        localStorage.setItem('theme', 'light-' + getTheme()); //add this
    }
}
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
function getCurrentTheme() {
    if (currentTheme.endsWith('blue')) return "blue";
    if (currentTheme.endsWith('orange')) return "orange";
    if (currentTheme.endsWith('green')) return "green";
}
function getIndex(select, value) {
    for (var i = 0; i < select.options.length; i++) {
        if (select.options[i].value == value) return i;
    }
    return 0;
}*/


document.getElementById("searchInput").addEventListener("keyup", e => {
    if (e.keyCode == 13) {
        document.getElementById("formSearch").submit();
    }
});

const currentTheme = localStorage.getItem('theme') ? localStorage.getItem('theme') : "light-blue";
document.documentElement.setAttribute('data-theme', currentTheme);


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
function getCurrentTheme() {
    if (currentTheme.endsWith('blue')) return "blue";
    if (currentTheme.endsWith('orange')) return "orange";
    if (currentTheme.endsWith('green')) return "green";
}
function getIndex(select, value) {
    for (var i = 0; i < select.options.length; i++) {
        if (select.options[i].value == value) return i;
    }
    return 0;
}

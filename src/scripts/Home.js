function changeFilter(type) {
    // Reset buttons
    var btnMoreLikes = document.getElementById("btnMoreLikes");
    var btnMoreViews = document.getElementById("btnMoreViews");
    var btnMoreRecent = document.getElementById("btnMoreRecent");

    btnMoreLikes.classList.remove("raised-primary");
    btnMoreViews.classList.remove("raised-primary");
    btnMoreRecent.classList.remove("raised-primary");
    btnMoreLikes.classList.add("stroked-primary");
    btnMoreViews.classList.add("stroked-primary");
    btnMoreRecent.classList.add("stroked-primary");

    // Update button's class
    switch (type) {
        case "likes":
            btnMoreLikes.classList.remove("stroked-primary");
            btnMoreLikes.classList.add("raised-primary");
            break;
        case "views":
            btnMoreViews.classList.remove("stroked-primary");
            btnMoreViews.classList.add("raised-primary");
            break;
        case "recent":
            btnMoreRecent.classList.remove("stroked-primary");
            btnMoreRecent.classList.add("raised-primary");
            break;
        default: break;
    }

    // Sort
    var data = "data-" + type;
    var div = document.getElementsByClassName("vrac")[0];
    var list = Array.from(div.children);
    list.sort((a, b) => {
        return b.getAttribute(data) - a.getAttribute(data);
    })

    // Add clones in order and remove previous elem
    for (var i=0; i<list.length; i++) {
        var newElem = list[i].cloneNode(true);
        list[i].remove();
        div.appendChild(newElem);
    }
}

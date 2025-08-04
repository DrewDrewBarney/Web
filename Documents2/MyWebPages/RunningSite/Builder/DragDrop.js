

function dragstartHandler(ev) {
    //window.alert("drag");
    ev.dataTransfer.setData("sourceId", ev.target.id);
}


function dragoverHandler(ev) {
    //window.alert("dragging over");
    ev.preventDefault();
}

function dropHandler(ev) {
    //window.alert("dropped");
    ev.preventDefault();
    const sourceId = ev.dataTransfer.getData("sourceId");
    //ev.target.appendChild(document.getElementById(sourceId));

    document.getElementById("delta").value = sourceId + "=>" + ev.target.id;
    document.getElementById("form").submit();
}


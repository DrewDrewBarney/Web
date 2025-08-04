


window.addEventListener("beforeunload", 
    function(){
        alert('boring');
        window.history.back();
    }, 
    false);
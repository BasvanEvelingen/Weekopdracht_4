window.addEventListener("load",init)

function init() {

    for (let i=2; i<=9; i++) {
        var tobj = document.getElementById("char"+i);
        TweenMax.from(tobj, 1,{ x:-100 ,ease: Bounce.easeOut,opacity:0, delay: (0.5+(i/4))});    
    }
}

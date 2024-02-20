const container = []
let lastClick=null;

const toggle_answer_hidden = (pRef) =>{
    if(lastClick != null && lastClick != pRef){
        lastClick.hidden = true
    }
    pRef.hidden = !(pRef.hidden)
    lastClick = pRef;
}

const load = () =>{
    ulHandler = document.getElementById("faqUL")
    for(let i=0;i<ulHandler.children.length;i++){
        ulHandler.children[i].children[0].onclick = toggle_answer_hidden.bind(null, ulHandler.children[i].children[1])
    }
}
window.addEventListener("load", load)
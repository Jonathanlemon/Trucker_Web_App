

const load = () => {
    let orgs = document.getElementById("organization_list")
    for(let i=0;i<orgs.children.length;i++){
        orgs.children[i].children[0].children[3].onclick=toggleUserVisibility.bind(null, orgs.children[i].children[1].children[0])
    }

}

const toggleUserVisibility = (list) => {
    console.log(list)
    if(list.style.display == "none"){
        list.style.display = "flex"
    }
    else{
        list.style.display = "none"
    }
}

window.addEventListener("load", load)
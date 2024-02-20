const load = () => {


    let orgEntries = document.getElementsByClassName("org_entry")

    for(let i=0;i<orgEntries.length;i++){
        //pointHistoryEntries[i].children[1].onclick=toggleUserVisibility.bind(null, pointHistoryEntries[i].children[4])
    }

}


const addOrganizationItem = (orgName, desc, status, org_id) => {
    newListElement = document.createElement("li")
    newListElement.setAttribute("class", "org_entry")
    newListElement.setAttribute("org_id", org_id)

    newListElement.appendChild(document.createElement("p"));
    newListElement.children[0].innerHTML = orgName;

    newListElement.appendChild(document.createElement("p"))
    newListElement.children[1].innerHTML = desc;

    newListElement.appendChild(document.createElement("button"))
    newListElement.children[2].innerHTML = "Apply"
    //newListElement.children[2].onclick = addToCart.bind(null, org_id);

    totalList = document.getElementById("app_listing")
    totalList.appendChild(newListElement)
}


window.addEventListener("load", load)
function show(value){
    document.querySelector(".dropdown-menu").value =value;
}

let dropdown= document.querySelector(".dropdown")
dropdown.onclick =function(){
    dropdown.classList.toggle("active");
}
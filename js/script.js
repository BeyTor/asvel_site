function toggleMenu(){
const nav = document.querySelector("nav");
nav.classList.toggle("show");
}

function openWhatsappMenu() {
  const menu = document.getElementById("whatsapp-menu");

  if(menu.style.display === "flex"){
    menu.style.display = "none";
  } else {
    menu.style.display = "flex";
  }
}
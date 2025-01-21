  
document.addEventListener('DOMContentLoaded', () => {  
    const toggleMenuButton = document.getElementById('toggle-menu');  
    const menu = document.getElementById('menu');  

    toggleMenuButton.addEventListener('click', () => {
        menu.classList.toggle('active'); 

    });

    
});  

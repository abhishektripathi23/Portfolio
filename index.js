document.addEventListener('DOMContentLoaded', () => {
    const toggleMenuButton = document.getElementById('toggle-menu');
    const menu = document.getElementById('menu');
    const closeMenuButton = document.getElementById('close-menu'); // The × button
  
    // Toggle menu and show/hide close button
    toggleMenuButton.addEventListener('click', () => {
      menu.classList.toggle('active');
      // Show/hide close button when menu opens/closes
      if (menu.classList.contains('active')) {
        closeMenuButton.style.display = 'block';
      } else {
        closeMenuButton.style.display = 'none';
      }
    });
  
    // Close menu when × button is clicked
    closeMenuButton.addEventListener('click', () => {
      menu.classList.remove('active');
      closeMenuButton.style.display = 'none';
    });
  
    // Close menu when clicking outside
    document.addEventListener('click', (event) => {
      if (!menu.contains(event.target) && event.target !== toggleMenuButton) {
        menu.classList.remove('active');
        closeMenuButton.style.display = 'none';
      }
    });
  });
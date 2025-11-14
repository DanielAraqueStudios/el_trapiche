// Animations for El Trapiche Website
document.addEventListener('DOMContentLoaded', () => {
    // Menu functionality
    const menuToggle = document.getElementById('menuToggle') || document.getElementById('hamburgerMenu');
    const fullscreenMenu = document.getElementById('fullscreenMenu');
    
    if (menuToggle && fullscreenMenu) {
        menuToggle.addEventListener('click', () => {
            fullscreenMenu.classList.toggle('active');
            menuToggle.classList.toggle('active');
            if (fullscreenMenu.classList.contains('active')) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        });

        fullscreenMenu.querySelectorAll('.menu-link').forEach(link => {
            link.addEventListener('click', () => {
                fullscreenMenu.classList.remove('active');
                menuToggle.classList.remove('active');
                document.body.style.overflow = '';
            });
        });
    }

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^=\

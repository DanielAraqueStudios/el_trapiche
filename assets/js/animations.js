document.addEventListener('DOMContentLoaded', () => {
    // Fade in elementos al hacer scroll
    const fadeElements = document.querySelectorAll('.fade-in-element');
    const appearOptions = {
        threshold: 0.15,
        rootMargin: "0px 0px -50px 0px"
    };

    const appearOnScroll = new IntersectionObserver((entries, appearOnScroll) => {
        entries.forEach(entry => {
            if (!entry.isIntersecting) return;
            entry.target.classList.add('appear');
            appearOnScroll.unobserve(entry.target);
        });
    }, appearOptions);

    fadeElements.forEach(element => {
        appearOnScroll.observe(element);
    });

    // Efecto Parallax para imágenes
    const parallaxImages = document.querySelectorAll('.img-fluid');
    window.addEventListener('scroll', () => {
        const scrolled = window.pageYOffset;
        parallaxImages.forEach(image => {
            const speed = 0.5;
            const yPos = -(scrolled * speed);
            image.style.transform = `translateY(${yPos}px)`;
        });
    });

    // Animación mejorada para cards
    const cards = document.querySelectorAll('.card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', (e) => {
            const bounds = card.getBoundingClientRect();
            const mouseX = e.clientX - bounds.left;
            const mouseY = e.clientY - bounds.top;
            
            card.style.transform = `
                perspective(1000px) 
                rotateX(${(mouseY - bounds.height/2)/20}deg) 
                rotateY(${-(mouseX - bounds.width/2)/20}deg) 
                translateY(-10px)
            `;
            card.style.transition = 'transform 0.3s ease';
            card.style.boxShadow = '0 15px 25px rgba(0,0,0,0.15)';
        });

        card.addEventListener('mousemove', (e) => {
            const bounds = card.getBoundingClientRect();
            const mouseX = e.clientX - bounds.left;
            const mouseY = e.clientY - bounds.top;
            const shine = card.querySelector('.shine');
            
            if (!shine) {
                const shine = document.createElement('div');
                shine.classList.add('shine');
                card.appendChild(shine);
            }
            
            shine.style.backgroundPosition = `${(mouseX/bounds.width) * 100}% ${(mouseY/bounds.height) * 100}%`;
        });

        card.addEventListener('mouseleave', () => {
            card.style.transform = 'perspective(1000px) rotateX(0) rotateY(0)';
            card.style.boxShadow = '0 4px 8px rgba(0,0,0,0.1)';
        });
    });

    // Animación de texto con split
    const headings = document.querySelectorAll('h1, h2');
    headings.forEach(heading => {
        const text = heading.textContent;
        const letters = text.split('');
        heading.textContent = '';
        letters.forEach((letter, i) => {
            const span = document.createElement('span');
            span.textContent = letter;
            span.style.animationDelay = `${i * 0.1}s`;
            span.classList.add('letter-animation');
            heading.appendChild(span);
        });
    });

    // Efecto de botones mejorado
    document.querySelectorAll('.btn').forEach(button => {
        button.addEventListener('mouseenter', (e) => {
            const rect = button.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            const circle = document.createElement('div');
            circle.classList.add('circle-effect');
            circle.style.left = x + 'px';
            circle.style.top = y + 'px';
            
            button.appendChild(circle);
            
            setTimeout(() => circle.remove(), 500);
        });
    });

    // Animación para el navbar al hacer scroll
    let prevScrollpos = window.pageYOffset;
    window.onscroll = () => {
        const currentScrollPos = window.pageYOffset;
        const navbar = document.querySelector('.navbar');
        
        if (prevScrollpos > currentScrollPos) {
            navbar.style.top = "0";
        } else {
            navbar.style.top = "-100px";
        }
        prevScrollpos = currentScrollPos;

        // Añadir sombra al navbar al hacer scroll
        if (currentScrollPos > 50) {
            navbar.classList.add('navbar-scrolled');
        } else {
            navbar.classList.remove('navbar-scrolled');
        }
    }

    // Observer para elementos que entran en viewport
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('active');
            }
        });
    }, { threshold: 0.1 });

    // Aplicar animaciones a elementos
    document.querySelectorAll('.slide-in').forEach(el => observer.observe(el));
    
    // Animación del carousel
    const carouselItems = document.querySelectorAll('.carousel-item');
    carouselItems.forEach(item => {
        item.addEventListener('transitionend', () => {
            if (item.classList.contains('active')) {
                item.querySelectorAll('.fade-up').forEach((el, index) => {
                    el.style.animationDelay = `${index * 0.2}s`;
                });
            }
        });
    });
});

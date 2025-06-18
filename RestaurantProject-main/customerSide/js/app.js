// Wrap all code in DOMContentLoaded to ensure DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu functionality
    const hamburger = document.querySelector('.header .nav-bar .nav-list .hamburger');
    const mobile_menu = document.querySelector('.header .nav-bar .nav-list ul');
    const menu_item = document.querySelectorAll('.header .nav-bar .nav-list ul li a');
    const header = document.querySelector('.header.container');

    // Initialize mobile menu toggle
    if (hamburger && mobile_menu) {
        hamburger.addEventListener('click', function() {
            hamburger.classList.toggle('active');
            mobile_menu.classList.toggle('active');
        });
    }

    // Scroll effect for header
    if (header) {
        document.addEventListener('scroll', function() {
            const scroll_position = window.scrollY;
            const isMenuPage = window.location.href.includes('RestaurantProject-main/customerSide/menu/menu.php');

            if (isMenuPage) {
                header.style.backgroundColor = '#29323c'; 
            } else if (scroll_position > 250) {
                header.style.backgroundColor = '#29323c';
            } else {
                header.style.backgroundColor = 'transparent';
            }
        });
    }

    // Mobile menu items click handler
    if (menu_item.length > 0 && hamburger && mobile_menu) {
        menu_item.forEach((item) => {
            item.addEventListener('click', function() {
                hamburger.classList.remove('active');
                mobile_menu.classList.remove('active');
            });
        });
    }

    // Menu category filtering
    const filterButtons = document.querySelectorAll('.filter-button');
    const menuSections = document.querySelectorAll('.menu-category-section');

    if (filterButtons.length > 0 && menuSections.length > 0) {
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                const category = button.dataset.category;

                filterButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');

                menuSections.forEach(section => {
                    if (category === 'all') {
                        section.classList.add('active');
                    } else {
                        section.classList.toggle('active', section.classList.contains(category));
                    }
                });
            });
        });

        // Show all items by default on page load
        const allButton = document.querySelector('.filter-button[data-category="all"]');
        if (allButton) {
            allButton.click();
        }
    }
});
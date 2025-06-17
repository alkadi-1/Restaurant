const hamburger = document.querySelector('.header .nav-bar .nav-list .hamburger');
const mobile_menu = document.querySelector('.header .nav-bar .nav-list ul');
const menu_item = document.querySelectorAll('.header .nav-bar .nav-list ul li a');
const header = document.querySelector('.header.container');

hamburger.addEventListener('click', () => {
	hamburger.classList.toggle('active');
	mobile_menu.classList.toggle('active');
});

document.addEventListener('scroll', () => {
	var scroll_position = window.scrollY;
    const isMenuPage = window.location.href.includes('RestaurantProject-main/customerSide/menu/menu.php');

	if (isMenuPage) {
		header.style.backgroundColor = '#29323c'; 
	} else if (scroll_position > 250) {
		header.style.backgroundColor = '#29323c';
	} else {
		header.style.backgroundColor = 'transparent';
	}
});

menu_item.forEach((item) => {
	item.addEventListener('click', () => {
		hamburger.classList.toggle('active');
		mobile_menu.classList.toggle('active');
	});
});

// Menu category filtering
const filterButtons = document.querySelectorAll('.filter-button');
const menuSections = document.querySelectorAll('.menu-category-section');

filterButtons.forEach(button => {
    button.addEventListener('click', () => {
        const category = button.dataset.category;

        filterButtons.forEach(btn => btn.classList.remove('active'));
        button.classList.add('active');

        menuSections.forEach(section => {
            if (category === 'all') {
                section.classList.add('active');
            } else {
                if (section.classList.contains(category)) {
                    section.classList.add('active');
                } else {
                    section.classList.remove('active');
                }
            }
        });
    });
});

// Show all items by default on page load
document.addEventListener('DOMContentLoaded', () => {
    document.querySelector('.filter-button[data-category="all"]').click();
});
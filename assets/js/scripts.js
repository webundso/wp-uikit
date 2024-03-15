/*
=================================================================
Filename: scripts.js
Description: additional Javascript functions
Author: Noël Girstmair | webundso GmbH
Last changes: 9.2.2024
=================================================================
*/

// Top top
window.addEventListener('scroll', function() {
	var toTopLink = document.getElementById('toTopLink');
	if (window.scrollY > 50) {
	// Wenn mehr als 100px gescrollt wurde, zeige den "To Top" Link an
		toTopLink.classList.add('visible');
	} else {
	// Ansonsten verstecke den "To Top" Link
		toTopLink.classList.remove('visible');
	}
});

// Hamburger Icon change

UIkit.util.on('#offcanvas-nav-primary', 'show', function () {
	//	alert('offen');
});

const iconOpen = document.getElementById('icon-open');
const iconClose = document.getElementById('icon-close');
document.getElementById('toggleOffcanvas').addEventListener('click', function() {
	iconOpen.classList.toggle('uk-hidden');
	iconClose.classList.toggle('uk-hidden');
});
document.getElementById('offcanvas-nav-primary').addEventListener('hide', function() {
	iconOpen.classList.remove('uk-hidden');
	iconClose.classList.add('uk-hidden');
});

// Toggle Icon
function toggleIconRotation(toggleLink, blockId) {
	var icon = document.querySelector('#toggleIcon_' + blockId);
	var iconClasses = icon.getAttribute('uk-icon');
		if (iconClasses.indexOf('chevron-right') !== -1) {
				icon.setAttribute('uk-icon', 'icon: chevron-down; ratio: 1.5');
		} else {
				icon.setAttribute('uk-icon', 'icon: chevron-right; ratio: 1.5');
		}
}


// Testen:

// const iconOpen = document.getElementById('icon-open');
// const iconClose = document.getElementById('icon-close');
// 
// document.getElementById('toggleOffcanvas').addEventListener('click', function() {
// 	if (iconOpen.classList.contains('uk-animation-fade')) {
// 		iconOpen.classList.remove('uk-animation-fade');
// 		iconClose.classList.remove('uk-animation-scale-up');
// 	} else {
// 		iconOpen.classList.add('uk-animation-fade');
// 		iconClose.classList.add('uk-animation-fade');
// 	}
// 
// 	iconOpen.classList.toggle('uk-hidden');
// 	iconClose.classList.toggle('uk-hidden');
// });
// 
// document.getElementById('offcanvas-nav-primary').addEventListener('hide', function() {
// 	iconOpen.classList.remove('uk-hidden', 'uk-animation-fade');
// 	iconClose.classList.add('uk-hidden');
// });
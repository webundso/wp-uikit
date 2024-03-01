/*
=================================================================
Filename: scripts.js
Description: additional Javascript functions
Author: Noël Girstmair | webundso GmbH
Last changes: 9.2.2024
=================================================================
*/

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
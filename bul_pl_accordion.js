document.addEventListener('click', function (bul_pl_accordion_event) {
    var bul_pl_accordion_content = document.querySelector(bul_pl_accordion_event.target.hash);
    if (!bul_pl_accordion_content) return;
	  
	bul_pl_accordion_event.preventDefault();
	
//By default, this allows multiple sections to be open at one time. If you want only one open at a time, and others to close when opening a new section, uncomment the next four lines (and edit this comment to be accurate):
	
// 	var bul_pl_accordions = document.querySelectorAll('.bul_pl_accordion-content.bul_pl_accordion_active');
// 	for (var i = 0; i < bul_pl_accordions.length; i++) {
// 	 bul_pl_accordions[i].classList.remove('bul_pl_accordion_active');
// 	} 

	if (bul_pl_accordion_content.classList.contains('bul_pl_accordion_active')) {
    	bul_pl_accordion_content.classList.remove('bul_pl_accordion_active');
    	return;
	}
	bul_pl_accordion_content.classList.toggle('bul_pl_accordion_active');
})

/* PLugin tabs */
var tabs = document.getElementsByClassName("nav-tab");
var forms = document.getElementsByClassName("form-table");

for (var i = 0; i < tabs.length; i++) {
	tabs[i].addEventListener('click', function(e) {

		for (var j = 0; j < tabs.length; j++) {
			tabs[j].classList.remove("nav-tab-active");
		}

		this.classList.add("nav-tab-active");

		for (var k = 0; k < tabs.length; k++) {
			forms[k].style.display = "none";
		}
		document.getElementById("form-"+this.id).style.display = "block";
		
	});
}





/* Plugin settings */

var cpt = document.getElementById("geo_post_type");
switch_post_type( cpt );

document.getElementById("geo_post_type").addEventListener( 'change', function( event ) {
	switch_post_type( this );
} );

function switch_post_type( cpt ) {
	var pt = cpt.value;
	var optionPT = document.getElementsByClassName('post-type-field');

	// Masquer et afficher les champs correspondant
	Array.prototype.forEach.call(optionPT, function(el, i){
		if ( pt === el.getAttribute('data-post-type') ) {
			el.style.display = "inherit";
		}
		else {
			el.style.display = "none";
		}
		
	});

}

document.getElementById("active-template").addEventListener( 'change', function( event ) {
	console.log(this.checked);
	if( "0" == this.checked ) {
		document.getElementById("template-popup").disabled = true;
	}
	else {
		document.getElementById("template-popup").disabled = false;
	}
});
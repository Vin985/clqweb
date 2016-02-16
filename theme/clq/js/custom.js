//Menu

var nav = responsiveNav(".nav-collapse", { // Selector
  animate: true, // Boolean: Use CSS3 transitions, true or false
  transition: 284, // Integer: Speed of the transition, in milliseconds
  label: "Menu", // String: Label for the navigation toggle
  insert: "before", // String: Insert the toggle before or after the navigation
  customToggle: "", // Selector: Specify the ID of a custom toggle
  closeOnNavClick: false, // Boolean: Close the navigation when one of the links are clicked
  openPos: "relative", // String: Position of the opened nav, relative or static
  navClass: "nav-collapse", // String: Default CSS class. If changed, you need to edit the CSS too!
  navActiveClass: "js-nav-active", // String: Class that is added to  element when nav is active
  jsClass: "js", // String: 'JS enabled' class which is added to  element
  init: function(){}, // Function: Init callback
  open: function(){}, // Function: Open callback
  close: function(){} // Function: Close callback
});

$(document).ready(function() {

	/** go top **/
	$(window).scroll(function() {
		if($(this).scrollTop() > 200) {
        $('#gotoTop').css('display','block');
		$('#gotoTop').stop().animate({opacity: 1});
		} else {
			$('#gotoTop').stop().animate({opacity: 0}, function(){
			$(this).css('display','none');
			});
		}
	});
	$('#gotoTop').click(function() {
		$('body,html').animate({scrollTop:0},400);
		return false;
	});
});
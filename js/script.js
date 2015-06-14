// JavaScript Document
var states =  ['Alabama', 'Alaska', 'Arizona', 'Arkansas', 'California', 'Colorado','Connecticut','Delaware','District of Columbia','Florida','Georgia','Hawaii','Idaho','Illinois','Indiana','Iowa','Kansas','Kentucky','Louisiana','Maine','Maryland','Massachusetts','Michigan','Minnesota','Mississippi','Missouri','Montana','Nebraska','Nevada','New Hampshire','New Jersey','New Mexico','New York','North Carolina','North Dakota','Ohio','Oklahoma','Oregon','Pennsylvania','Rhode Island','South Carolina','South Dakota','Tennessee','Texas','Utah','Vermont','Virginia', 'Washington','West', 'Virginia', 'Wisconsin','Wyoming'];

$(document).ready(function(e) {
for(i=0; i < states.length; i++ ){
	$("#states").append("<option>" + states[i] + "</option>");
	}

});

 // You can also use "$(window).load(function() {"
    $(function () {

      // Slideshow 1
      $("#slider1").responsiveSlides({
        maxwidth:500,
        speed: 800
      });
 

    });
	

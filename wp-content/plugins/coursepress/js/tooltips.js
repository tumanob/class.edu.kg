jQuery(document).ready(function($) {

    $(document).click(function() {
        $('.help-icon').removeClass( 'open' );
        $('.tooltip .tooltip-before[display!="none"], .tooltip .tooltip-button[display!="none"], .tooltip .tooltip-content[display!="none"]').parent().fadeOut(100);
    });

    $( document.body ).on('click', '.tooltip', function(e) {
        e.stopPropagation();
    });
    
    $( document.body ).on('click', '.tooltip-content', function(e) {
        e.preventDefault();
    });

    $( document.body ).on('click', '.help-icon', function(event) {
		if ( $( this ).hasClass('open') )
		{
			$( this ).removeClass('open');
			$( document ).click();
		} else {
	        event.stopPropagation();
			$( this ).addClass('open');
	        var tooltip = $(this).siblings('.tooltip');
	        var tooltip_before = $(this).siblings('.tooltip').find('.tooltip-before');

	        if (($(document).width()) - ($(this).offset().left + 35) > tooltip.width()) {
	            tooltip.css("left", $(this).position().left + 35);
	        } else {
	            tooltip.css("left", $(this).position().left - (tooltip.width() + 10));
	            tooltip_before.css("transform", 'rotate(180deg)');
	            tooltip_before.css("-ms-transform", 'rotate(180deg)');
	            tooltip_before.css("-webkit-transform", 'rotate(180deg)');
	            tooltip_before.css("left", tooltip.width());
	        }

	        tooltip.css("top", $(this).position().top - 7);
	        tooltip.fadeIn(300);
		}

    });

    $( document.body ).on('click', '.tooltip-button', function() {
        $(this).parent().fadeOut(100);
    });

});
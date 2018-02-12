(function ($) {
	var $testimonialSlider = $('#lsx-testimonials-slider');

	$testimonialSlider.on('init', function (event, slick) {
		if (slick.options.arrows && slick.slideCount > slick.options.slidesToShow)
			$testimonialSlider.addClass('slick-has-arrows');
	});

	$testimonialSlider.on('setPosition', function (event, slick) {
		if (!slick.options.arrows)
			$testimonialSlider.removeClass('slick-has-arrows');
		else if (slick.slideCount > slick.options.slidesToShow)
			$testimonialSlider.addClass('slick-has-arrows');
	});

	$testimonialSlider.slick({
		draggable: false,
		infinite: true,
		slidesToScroll: 1,
		swipe: false,
		cssEase: 'ease-out',
		dots: true,
		responsive: [{
			breakpoint: 992,
			settings: {
				draggable: true,
				arrows: false,
				swipe: true
			}
		}, {
			breakpoint: 768,
			settings: {
				slidesToShow: 1,
				draggable: true,
				arrows: false,
				swipe: true
			}
		}]
	});

	$('.single-testimonial a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
		$('#lsx-services-slider, #lsx-projects-slider, #lsx-products-slider, #lsx-testimonials-slider, #lsx-team-slider, .lsx-blog-customizer-posts-slider, .lsx-blog-customizer-terms-slider').slick('setPosition');
	});
})(jQuery);

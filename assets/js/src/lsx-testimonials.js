(function($) {
	$(document).ready(function() {
		var $testimonialSlider = $(
			"#lsx-testimonials-slider.lsx-testimonial-block"
		);

		$testimonialSlider.on("init", function(event, slick) {
			if (
				slick.options.arrows &&
				slick.slideCount > slick.options.slidesToShow
			)
				$testimonialSlider.addClass("slick-has-arrows");
		});

		$testimonialSlider.on("setPosition", function(event, slick) {
			if (!slick.options.arrows)
				$testimonialSlider.removeClass("slick-has-arrows");
			else if (slick.slideCount > slick.options.slidesToShow)
				$testimonialSlider.addClass("slick-has-arrows");
		});

		var slidesToScroll = 1;
		var slidesToShow = 1;
		var overrides = $testimonialSlider.attr("data-lsx-slick");
		if (undefined !== overrides && false !== overrides) {
			overrides = jQuery.parseJSON(overrides);
			console.log(overrides);
			if (
				undefined !== overrides.slidesToShow &&
				"" !== overrides.slidesToShow
			) {
				slidesToShow = overrides.slidesToShow;
			}
			if (
				undefined !== overrides.slidesToScroll &&
				"" !== overrides.slidesToScroll
			) {
				slidesToScroll = overrides.slidesToScroll;
			}
		}

		$testimonialSlider.slick({
			draggable: false,
			infinite: true,
			slidesToScroll: slidesToScroll,
			slidesToShow: slidesToShow,
			swipe: false,
			cssEase: "ease-out",
			dots: true,
			responsive: [
				{
					breakpoint: 992,
					settings: {
						draggable: true,
						arrows: false,
						swipe: true
					}
				},
				{
					breakpoint: 768,
					settings: {
						slidesToShow: 1,
						slidesToScroll: 1,
						draggable: true,
						arrows: false,
						swipe: true
					}
				}
			]
		});

		$('.single-testimonial a[data-toggle="tab"]').on(
			"shown.bs.tab",
			function(e) {
				$(
					"#lsx-services-slider, #lsx-projects-slider, #lsx-products-slider, #lsx-testimonials-slider, .lsx-testimonial-block,  #lsx-team-slider, .lsx-blog-customizer-posts-slider, .lsx-blog-customizer-terms-slider"
				).slick("setPosition");
			}
		);
	});
})(jQuery);

(function($) {
	'use strict';

	$('body').imagesLoaded(function() {

		// Slider
		hivetheme.getComponent('slider').each(function() {
			var container = $(this),
				slider = container.children('div:first'),
				arrows = $('<div class="slick-arrows" />').appendTo(container),
				width = $('#content').children('div:first').width(),
				settings = {
					appendArrows: arrows,
					prevArrow: '<i class="slick-prev fas fa-arrow-left"></i>',
					nextArrow: '<i class="slick-next fas fa-arrow-right"></i>',
					slidesToShow: 1,
					slidesToScroll: 1,
					variableWidth: true,
					centerMode: true,
					speed: 650,
				};

			arrows.css('right', (container.width() - width) / 2);
			slider.children('div').width(width);

			if (container.data('pause')) {
				settings['autoplay'] = true;
				settings['autoplaySpeed'] = parseInt(container.data('pause'));
			}

			slider.slick(settings);
		});

		// Parallax
		hivetheme.getComponent('parallax').each(function() {
			var container = $(this),
				background = '',
				offset = container.offset().top,
				speed = 0.25;

			if ($('#wpadminbar').length) {
				offset = offset - $('#wpadminbar').height();
			}

			if (container.data('image')) {
				container.css('background-image', 'url(' + container.data('image') + ')');
			}

			if (container.is('[data-speed]')) {
				speed = parseFloat(container.data('speed'));
			}

			background = container.css('background-image');

			if ($(window).width() >= 1024 && background.indexOf('url') === 0) {
				container.css('background-position-y', ($(window).scrollTop() - offset) * speed);

				$(window).on('scroll', function() {
					container.css('background-position-y', ($(window).scrollTop() - offset) * speed);
				});
			}
		});
	});
})(jQuery);

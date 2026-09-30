/**
 * Update Customizer settings live.
 *
 * @since 1.0.0
 */
(function ($) {
	'use strict';

	// Declare variables
	var api = wp.customize,
		$style_tag,
		$link_tag,
		blogsy_visibility_classes = 'blogsy-hide-mobile blogsy-hide-tablet blogsy-hide-mobile-tablet',
		blogsy_style_tag_collection = [],
		blogsy_link_tag_collection = [];

	/**
	 * Helper function to get style tag with id.
	 */
	function blogsy_get_style_tag(id) {
		if (blogsy_style_tag_collection[id]) {
			return blogsy_style_tag_collection[id];
		}

		$style_tag = $('head').find('#blogsy-dynamic-' + id);

		if (!$style_tag.length) {
			$('head').append('<style id="blogsy-dynamic-' + id + '" type="text/css" href="#"></style>');
			$style_tag = $('head').find('#blogsy-dynamic-' + id);
		}

		blogsy_style_tag_collection[id] = $style_tag;

		return $style_tag;
	}

	/**
	 * Helper function to get link tag with id.
	 */
	function blogsy_get_link_tag(id, url) {
		if (blogsy_link_tag_collection[id]) {
			return blogsy_link_tag_collection[id];
		}

		$link_tag = $('head').find('#blogsy-dynamic-link-' + id);

		if (!$link_tag.length) {
			$('head').append('<link id="blogsy-dynamic-' + id + '" type="text/css" rel="stylesheet" href="' + url + '"/>');
			$link_tag = $('head').find('#blogsy-dynamic-link-' + id);
		} else {
			$link_tag.attr('href', url);
		}

		blogsy_link_tag_collection[id] = $link_tag;

		return $link_tag;
	}

	/*
	 * Helper function to convert hex to rgba.
	 */
	function blogsy_hex2rgba(hex, opacity) {
		if ('rgba' === hex.substring(0, 4)) {
			return hex;
		}

		// Expand shorthand form (e.g. "03F") to full form (e.g. "0033FF").
		var shorthandRegex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;

		hex = hex.replace(shorthandRegex, function (m, r, g, b) {
			return r + r + g + g + b + b;
		});

		var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);

		if (opacity) {
			if (1 < opacity) {
				opacity = 1;
			}

			opacity = ',' + opacity;
		}

		if (result) {
			return 'rgba(' + parseInt(result[1], 16) + ',' + parseInt(result[2], 16) + ',' + parseInt(result[3], 16) + opacity + ')';
		}

		return false;
	}

	/**
	 * Spacing field CSS.
	 */
	function blogsy_spacing_field_css(selector, property, setting, responsive) {
		if (!Array.isArray(setting) && 'object' !== typeof setting) {
			return;
		}

		// Set up unit.
		var unit = 'px',
			css = '';

		if ('unit' in setting) {
			unit = setting.unit;
		}

		var before = '',
			after = '';

		Object.keys(setting).forEach(function (index, el) {
			if ('unit' === index) {
				return;
			}

			if (responsive) {
				if ('tablet' === index) {
					before = '@media only screen and (max-width: 1024px) {';
					after = '}';
				} else if ('mobile' === index) {
					before = '@media only screen and (max-width: 600px) {';
					after = '}';
				} else {
					before = '';
					after = '';
				}

				css += before + selector + '{';

				Object.keys(setting[index]).forEach(function (position) {
					if ('border' === property) {
						position += '-width';
					}

					if (setting[index][position]) {
						css += property + '-' + position + ': ' + setting[index][position] + unit + ';';
					}
				});

				css += '}' + after;
			} else {
				if ('border' === property) {
					index += '-width';
				}

				css += property + '-' + index + ': ' + setting[index] + unit + ';';
			}
		});

		if (!responsive) {
			css = selector + '{' + css + '}';
		}

		return css;
	}

	/**
	 * Range field CSS.
	 */
	function blogsy_range_field_css(selector, property, setting, responsive, unit, half_divide, half_custom) {
		var css = '',
			before = '',
			after = '';

		if (responsive && (Array.isArray(setting) || 'object' === typeof setting)) {
			Object.keys(setting).forEach(function (index, el) {
				if (setting[index]) {
					var value = parseFloat(setting[index]);
					if (half_divide) {
						value = value / 2;
					}
					if (half_custom) {
						value -= half_custom;
					}
					if ('tablet' === index) {
						before = '@media only screen and (max-width: 1024px) {';
						after = '}';
					} else if ('mobile' === index) {
						before = '@media only screen and (max-width: 600px) {';
						after = '}';
					} else if ('desktop' === index) {
						before = '';
						after = '';
					} else {
						return;
					}

					css += before + selector + '{' + property + ': ' + value + unit + '; }' + after;
				}
			});
		}

		if (!responsive) {
			if (setting.value) {
				setting = setting.value;
			} else {
				setting = 0;
			}

			var value = parseFloat(setting);

			if (half_divide) {
				value = value / 2;
			}
			if (half_custom) {
				value -= half_custom;
			}

			css = selector + '{' + property + ': ' + value + unit + '; }';
		}

		return css;
	}

	/**
	 * Typography field CSS.
	 */
	function blogsy_typography_field_css(selector, setting) {
		var css = '';

		css += selector + '{';

		if ('font-family' in setting && setting['font-family']) {
			if ('default' === setting['font-family']) {
				css += 'font-family: ' + blogsy_customizer_preview.default_system_font + ';';
			} else if (setting['font-family'] in blogsy_customizer_preview.fonts.standard_fonts.fonts) {
				css += 'font-family: ' + blogsy_customizer_preview.fonts.standard_fonts.fonts[setting['font-family']].fallback + ';';
			} else if ('inherit' !== setting['font-family']) {
				css += 'font-family: "' + setting['font-family'] + '";';
			}
		}

		if ('font-weight' in setting && setting['font-weight']) {
			css += 'font-weight:' + setting['font-weight'] + ';';
		}

		if ('font-style' in setting && setting['font-style']) {
			css += 'font-style:' + setting['font-style'] + ';';
		}

		if ('text-transform' in setting && setting['text-transform']) {
			css += 'text-transform:' + setting['text-transform'] + ';';
		}

		if ('text-decoration' in setting) {
			css += 'text-decoration:' + setting['text-decoration'] + ';';
		}
		if ('color' in setting) {
			css += 'color:' + setting['color'] + ';';
		}

		if ('letter-spacing' in setting) {
			css += 'letter-spacing:' + setting['letter-spacing'] + setting['letter-spacing-unit'] + ';';
		}

		if ('line-height-desktop' in setting) {
			css += 'line-height:' + setting['line-height-desktop'] + ';';
		}

		if ('font-size-desktop' in setting && 'font-size-unit' in setting) {
			css += 'font-size:' + setting['font-size-desktop'] + setting['font-size-unit'] + ';';
		}

		css += '}';

		if ('font-size-tablet' in setting && setting['font-size-tablet']) {
			css += '@media only screen and (max-width: 1024px) {' + selector + '{' + 'font-size: ' + setting['font-size-tablet'] + setting['font-size-unit'] + ';' + '}' + '}';
		}

		if ('line-height-tablet' in setting && setting['line-height-tablet']) {
			css += '@media only screen and (max-width: 1024px) {' + selector + '{' + 'line-height:' + setting['line-height-tablet'] + ';' + '}' + '}';
		}

		if ('font-size-mobile' in setting && setting['font-size-mobile']) {
			css += '@media only screen and (max-width: 600px) {' + selector + '{' + 'font-size: ' + setting['font-size-mobile'] + setting['font-size-unit'] + ';' + '}' + '}';
		}

		if ('line-height-mobile' in setting && setting['line-height-mobile']) {
			css += '@media only screen and (max-width: 600px) {' + selector + '{' + 'line-height:' + setting['line-height-mobile'] + ';' + '}' + '}';
		}

		return css;
	}

	/**
	 * Load google font.
	 */
	function blogsy_enqueue_google_font(font) {
		if (blogsy_customizer_preview.fonts.google_fonts.fonts[font]) {
			var id = 'google-font-' + font.trim().toLowerCase().replace(' ', '-');
			var url = blogsy_customizer_preview.google_fonts_url + '/css?family=' + font + ':' + blogsy_customizer_preview.google_font_weights;

			var tag = blogsy_get_link_tag(id, url);
		}
	}

	/**
	 * Design Options field CSS.
	 */
	function blogsy_design_options_css(selector, setting, type) {
		var css = '',
			before = '',
			after = '';

		if ('background' === type) {
			var bg_type = setting['background-type'];

			css += selector + '{';

			if ('color' === bg_type) {
				setting['background-color'] = setting['background-color'] ? setting['background-color'] : 'inherit';
				css += 'background: ' + setting['background-color'] + ';';
			} else if ('gradient' === bg_type) {
				css += 'background: ' + setting['gradient-color-1'] + ';';

				if ('linear' === setting['gradient-type']) {
					css +=
						'background: -webkit-linear-gradient(' +
						setting['gradient-linear-angle'] +
						'deg, ' +
						setting['gradient-color-1'] +
						' ' +
						setting['gradient-color-1-location'] +
						'%, ' +
						setting['gradient-color-2'] +
						' ' +
						setting['gradient-color-2-location'] +
						'%);' +
						'background: -o-linear-gradient(' +
						setting['gradient-linear-angle'] +
						'deg, ' +
						setting['gradient-color-1'] +
						' ' +
						setting['gradient-color-1-location'] +
						'%, ' +
						setting['gradient-color-2'] +
						' ' +
						setting['gradient-color-2-location'] +
						'%);' +
						'background: linear-gradient(' +
						setting['gradient-linear-angle'] +
						'deg, ' +
						setting['gradient-color-1'] +
						' ' +
						setting['gradient-color-1-location'] +
						'%, ' +
						setting['gradient-color-2'] +
						' ' +
						setting['gradient-color-2-location'] +
						'%);';
				} else if ('radial' === setting['gradient-type']) {
					css +=
						'background: -webkit-radial-gradient(' +
						setting['gradient-position'] +
						', circle, ' +
						setting['gradient-color-1'] +
						' ' +
						setting['gradient-color-1-location'] +
						'%, ' +
						setting['gradient-color-2'] +
						' ' +
						setting['gradient-color-2-location'] +
						'%);' +
						'background: -o-radial-gradient(' +
						setting['gradient-position'] +
						', circle, ' +
						setting['gradient-color-1'] +
						' ' +
						setting['gradient-color-1-location'] +
						'%, ' +
						setting['gradient-color-2'] +
						' ' +
						setting['gradient-color-2-location'] +
						'%);' +
						'background: radial-gradient(circle at ' +
						setting['gradient-position'] +
						', ' +
						setting['gradient-color-1'] +
						' ' +
						setting['gradient-color-1-location'] +
						'%, ' +
						setting['gradient-color-2'] +
						' ' +
						setting['gradient-color-2-location'] +
						'%);';
				}
			} else if ('image' === bg_type) {
				css +=
					'position: relative; z-index: 0;' +
					'background-image: url(' +
					setting['background-image'] +
					');' +
					'background-size: ' +
					setting['background-size'] +
					';' +
					'background-attachment: ' +
					setting['background-attachment'] +
					';' +
					'background-position: ' +
					setting['background-position-x'] +
					'% ' +
					setting['background-position-y'] +
					'%;' +
					'background-repeat: ' +
					setting['background-repeat'] +
					';';
			}

			css += '}';

			// Background image color overlay.
			if ('image' === bg_type && setting['background-color-overlay'] && setting['background-image']) {
				css += selector + '::before {content: ""; position: absolute; inset: 0; z-index: -1; background-color: ' + setting['background-color-overlay'] + '; }';
			}
		} else if ('color' === type) {
			setting['text-color'] = setting['text-color'] ? setting['text-color'] : 'inherit';
			setting['link-color'] = setting['link-color'] ? setting['link-color'] : 'inherit';
			setting['link-hover-color'] = setting['link-hover-color'] ? setting['link-hover-color'] : 'inherit';
			setting['link-active-color'] = setting['link-active-color'] ? setting['link-active-color'] : 'inherit';

			css += selector + ' { color: ' + setting['text-color'] + '; }';
			css += selector + ' a { color: ' + setting['link-color'] + '; }';
			css += selector + ' a:hover { color: ' + setting['link-hover-color'] + ' !important; }';
			css += selector + ' { --link-active-color: ' + setting['link-active-color'] + '; }';
		} else if ('border' === type) {
			setting['border-color'] = setting['border-color'] ? setting['border-color'] : 'inherit';
			setting['border-style'] = setting['border-style'] ? setting['border-style'] : 'solid';
			setting['border-left-width'] = setting['border-left-width'] ? setting['border-left-width'] : 0;
			setting['border-top-width'] = setting['border-top-width'] ? setting['border-top-width'] : 0;
			setting['border-right-width'] = setting['border-right-width'] ? setting['border-right-width'] : 0;
			setting['border-bottom-width'] = setting['border-bottom-width'] ? setting['border-bottom-width'] : 0;

			css += selector + '{';
			css += 'border-color: ' + setting['border-color'] + ';';
			css += 'border-style: ' + setting['border-style'] + ';';
			css += 'border-left-width: ' + setting['border-left-width'] + 'px;';
			css += 'border-top-width: ' + setting['border-top-width'] + 'px;';
			css += 'border-right-width: ' + setting['border-right-width'] + 'px;';
			css += 'border-bottom-width: ' + setting['border-bottom-width'] + 'px;';
			css += '}';
		} else if ('box-shadow' === type) {
			var x = parseInt(setting['x'] || 0, 10);
			var y = parseInt(setting['y'] || 0, 10);
			var blur = parseInt(setting['blur'] || 0, 10);
			var spread = parseInt(setting['spread'] || 0, 10);
			var color = setting['color'] || 'rgba(0,0,0,0.05)';
			var shadow_type = setting['type'] || 'outset';
			var shadow = x + 'px ' + y + 'px ' + blur + 'px ' + spread + 'px ' + color;
			if ('inset' === shadow_type) {
				shadow = 'inset ' + shadow;
			}

			css = selector + '{ box-shadow: ' + shadow + '; }';
		}

		return css;
	}

	/**
	 * Accent color.
	 */
	api('blogsy_accent_color', function (value) {
		value.bind(function (newval) {
			$style_tag = blogsy_get_style_tag('blogsy_accent_color');

			if (!newval) {
				$style_tag.html('');
				return;
			}

			var style_css = ':root { --pt-accent-color: ' + newval + '; --pt-accent-40-color: ' + blogsy_luminance(newval, .40) + '; --pt-accent-80-color: ' + blogsy_luminance(newval, .80) + '; }';

			$style_tag.html(style_css);
		});
	});

	/**
	 * Secondary color.
	 */
	api('blogsy_second_color', function (value) {
		value.bind(function (newval) {
			$style_tag = blogsy_get_style_tag('blogsy_second_color');

			if (!newval) {
				$style_tag.html('');
				return;
			}

			var style_css = ':root { --pt-second-color: ' + newval + '; }';

			$style_tag.html(style_css);
		});
	});

	/**
	 * Body bg color.
	 */
	api('blogsy_body_bg', function (value) {
		value.bind(function (newval) {
			$style_tag = blogsy_get_style_tag('blogsy_body_bg');

			if (!newval) {
				$style_tag.html('');
				return;
			}

			var style_css = ':root { --pt-body-bg-color: ' + newval + '; }';

			$style_tag.html(style_css);
		});
	});


	/**
	 * Body color.
	 */
	api('blogsy_body_color', function (value) {
		value.bind(function (newval) {
			$style_tag = blogsy_get_style_tag('blogsy_body_color');

			if (!newval) {
				$style_tag.html('');
				return;
			}

			var style_css = ':root { --pt-body-color: ' + newval + '; }';

			$style_tag.html(style_css);
		});
	});

	/**
	 * Heading color.
	 */
	api('blogsy_heading_color', function (value) {
		value.bind(function (newval) {
			$style_tag = blogsy_get_style_tag('blogsy_heading_color');

			if (!newval) {
				$style_tag.html('');
				return;
			}

			var style_css = ':root { --pt-headings-color: ' + newval + '; }';

			$style_tag.html(style_css);
		});
	});

	/**
	 * Button BG color.
	 */
	api('blogsy_button_bg_hover', function (value) {
		value.bind(function (newval) {
			$style_tag = blogsy_get_style_tag('blogsy_button_bg_hover');

			if (!newval) {
				$style_tag.html('');
				return;
			}

			var style_css = ':root { --pt-button-bg-hover: ' + newval + '; }';

			$style_tag.html(style_css);
		});
	});

	/**
	 * Button shape.
	 */
	api('blogsy_button_shape_style', function (value) {
		value.bind(function (newval) {
			const body = document.body;

			// Remove all classes that start with 'pt-shape--'
			body.className = body.className
				.split(/\s+/)
				.filter(className => !className.startsWith('pt-shape--'))
				.join(' ');

			// Then, add the new class
			body.classList.add('pt-shape--' + newval);
		});
	});

	/**
	 * Accent dark color.
	 */
	api('blogsy_accent_color_dark', function (value) {
		value.bind(function (newval) {
			$style_tag = blogsy_get_style_tag('blogsy_accent_color_dark');

			if (!newval) {
				$style_tag.html('');
				return;
			}

			var style_css = 'html[scheme="dark"] { --pt-accent-color: ' + newval + '; }';

			$style_tag.html(style_css);
		});
	});

	/**
	 * Accent second dark color.
	 */
	api('blogsy_second_color_dark', function (value) {
		value.bind(function (newval) {
			$style_tag = blogsy_get_style_tag('blogsy_second_color_dark');

			if (!newval) {
				$style_tag.html('');
				return;
			}

			var style_css = 'html[scheme="dark"] { --pt-second-color: ' + newval + '; }';

			$style_tag.html(style_css);
		});
	});

	/**
	 * Body BG dark color.
	 */
	api('blogsy_body_bg_dark', function (value) {
		value.bind(function (newval) {
			$style_tag = blogsy_get_style_tag('blogsy_body_bg_dark');

			if (!newval) {
				$style_tag.html('');
				return;
			}

			var style_css = 'html[scheme="dark"] { --pt-body-bg-color: ' + newval + '; }';

			$style_tag.html(style_css);
		});
	});

	/**
	 * Body dark color.
	 */
	api('blogsy_body_color_dark', function (value) {
		value.bind(function (newval) {
			$style_tag = blogsy_get_style_tag('blogsy_body_color_dark');

			if (!newval) {
				$style_tag.html('');
				return;
			}

			var style_css = 'html[scheme="dark"] { --pt-body-color: ' + newval + '; }';

			$style_tag.html(style_css);
		});
	});

	/**
	 * Heading dark color.
	 */
	api('blogsy_heading_color_dark', function (value) {
		value.bind(function (newval) {
			$style_tag = blogsy_get_style_tag('blogsy_heading_color_dark');

			if (!newval) {
				$style_tag.html('');
				return;
			}

			var style_css = 'html[scheme="dark"] { --heading-color: ' + newval + '; }';

			$style_tag.html(style_css);
		});
	});

	/**
	 * Button BG dark color.
	 */
	api('blogsy_button_bg_hover_dark', function (value) {
		value.bind(function (newval) {
			$style_tag = blogsy_get_style_tag('blogsy_button_bg_hover_dark');

			if (!newval) {
				$style_tag.html('');
				return;
			}

			var style_css = 'html[scheme="dark"] { --pt-button-bg-hover: ' + newval + '; }';

			$style_tag.html(style_css);
		});
	});

	/**
	 * Logo max height.
	 */
	api('blogsy_logo_max_height', function (value) {
		value.bind(function (newval) {
			var $logo = $('#site-header .pt-logo');

			if (!$logo.length) {
				return;
			}

			$style_tag = blogsy_get_style_tag('blogsy_logo_max_height');
			var style_css = '';

			style_css += blogsy_range_field_css('#site-header .pt-logo img, #site-sticky-header .pt-logo img', 'max-height', newval, true, 'px');
			style_css += blogsy_range_field_css('#site-header .pt-logo img.blogsy-svg-logo, #site-sticky-header .pt-logo img.blogsy-svg-logo', 'height', newval, true, 'px');

			$style_tag.html(style_css);
		});
	});

	/**
	 * Site title.
	 */
	api('blogsy_logo_title_typography', function (value) {
		value.bind(function (newval) {
			var $logo = $('#site-header .pt-logo .site-title');

			if (!$logo.length) {
				return;
			}

			$style_tag = blogsy_get_style_tag('blogsy_logo_title_typography');
			var style_css = '';

			style_css += blogsy_typography_field_css('#site-header .pt-logo .site-title', newval);

			blogsy_enqueue_google_font(newval['font-family']);

			$style_tag.html(style_css);
		});
	});

	/**
	 * Site tagline.
	 */
	api('blogsy_logo_tagline_typography', function (value) {
		value.bind(function (newval) {
			var $logo = $('#site-header .pt-logo .site-description');

			if (!$logo.length) {
				return;
			}

			$style_tag = blogsy_get_style_tag('blogsy_logo_tagline_typography');
			var style_css = '';

			style_css += blogsy_typography_field_css('#site-header .pt-logo .site-description', newval);

			blogsy_enqueue_google_font(newval['font-family']);

			$style_tag.html(style_css);
		});
	});

	/**
	 * Logo margin.
	 */
	api('blogsy_logo_margin', function (value) {
		value.bind(function (newval) {
			var $logo = $('#site-header .pt-logo');

			if (!$logo.length) {
				return;
			}

			$style_tag = blogsy_get_style_tag('blogsy_logo_margin');

			var style_css = blogsy_spacing_field_css('#site-header .pt-logo .logo-inner', 'margin', newval, true);
			$style_tag.html(style_css);
		});
	});

	/**
	 * Tagline.
	 */
	api('blogdescription', function (value) {
		value.bind(function (newval) {
			if ($('#site-header .pt-logo').find('.site-description').length) {
				$('#site-header .pt-logo').find('.site-description').html(newval);
			}
		});
	});

	/**
	 * Site Title.
	 */
	api('blogname', function (value) {
		value.bind(function (newval) {
			if ($('#site-header .pt-logo').find('.site-title').length) {
				$('#site-header .pt-logo').find('.site-title').find('a').html(newval);
			}
		});
	});

	/**
	 * Base HTML font size.
	 */
	api('blogsy_html_base_font_size', function (value) {
		value.bind(function (newval) {
			$style_tag = blogsy_get_style_tag('blogsy_html_base_font_size');
			var style_css = blogsy_range_field_css('html', 'font-size', newval, true, '%');
			$style_tag.html(style_css);
		});
	});

	/**
	 * Body font.
	 */
	api('blogsy_typo_body', function (value) {
		value.bind(function (newval) {
			$style_tag = blogsy_get_style_tag('blogsy_typo_body');
			var style_css = blogsy_typography_field_css('body', newval);

			blogsy_enqueue_google_font(newval['font-family']);

			$style_tag.html(style_css);
		});
	});

	/**
	 * Heading 1 font.
	 */
	api('blogsy_typo_h1', function (value) {
		value.bind(function (newval) {
			$style_tag = blogsy_get_style_tag('blogsy_typo_h1');

			var style_css = blogsy_typography_field_css('h1, .h1', newval);

			blogsy_enqueue_google_font(newval['font-family']);

			$style_tag.html(style_css);
		});
	});

	/**
	 * Heading 2 font.
	 */
	api('blogsy_typo_h2', function (value) {
		value.bind(function (newval) {
			$style_tag = blogsy_get_style_tag('blogsy_typo_h2');

			var style_css = blogsy_typography_field_css('h2, .h2', newval);

			blogsy_enqueue_google_font(newval['font-family']);

			$style_tag.html(style_css);
		});
	});

	/**
	 * Heading 3 font.
	 */
	api('blogsy_typo_h3', function (value) {
		value.bind(function (newval) {
			$style_tag = blogsy_get_style_tag('blogsy_typo_h3');

			var style_css = blogsy_typography_field_css('h3, .h3', newval);

			blogsy_enqueue_google_font(newval['font-family']);

			$style_tag.html(style_css);
		});
	});

	/**
	 * Heading 4 font.
	 */
	api('blogsy_typo_h4', function (value) {
		value.bind(function (newval) {
			$style_tag = blogsy_get_style_tag('blogsy_typo_h4');

			var style_css = blogsy_typography_field_css('h4, .h4', newval);

			blogsy_enqueue_google_font(newval['font-family']);

			$style_tag.html(style_css);
		});
	});

	/**
	 * Heading 5 font.
	 */
	api('blogsy_typo_h5', function (value) {
		value.bind(function (newval) {
			$style_tag = blogsy_get_style_tag('blogsy_typo_h5');
			var style_css = blogsy_typography_field_css('h5, .h5', newval);

			blogsy_enqueue_google_font(newval['font-family']);

			$style_tag.html(style_css);
		});
	});

	/**
	 * Heading 6 font.
	 */
	api('blogsy_typo_h6', function (value) {
		value.bind(function (newval) {
			$style_tag = blogsy_get_style_tag('blogsy_typo_h6');
			var style_css = blogsy_typography_field_css('h6, .h6', newval);

			blogsy_enqueue_google_font(newval['font-family']);

			$style_tag.html(style_css);
		});
	});
	/**
	 * Typo custom section heading.
	 */
	api('blogsy_typo_section_title', function (value) {
		value.bind(function (newval) {
			$style_tag = blogsy_get_style_tag('blogsy_typo_section_title');
			var style_css = blogsy_typography_field_css('.blogsy-section-heading .blogsy-divider-heading, .blogsy-section-heading .blogsy-divider-heading .title', newval);

			blogsy_enqueue_google_font(newval['font-family']);

			$style_tag.html(style_css);
		});
	});
	/**
	 * Typo Footer/Sidebar Widgets heading.
	 */
	api('blogsy_typo_widgets_title', function (value) {
		value.bind(function (newval) {
			$style_tag = blogsy_get_style_tag('blogsy_typo_widgets_title');
			var style_css = blogsy_typography_field_css('.blogsy-sidebar-widget .blogsy-divider-heading, .blogsy-sidebar-widget .blogsy-divider-heading .title', newval);

			blogsy_enqueue_google_font(newval['font-family']);

			$style_tag.html(style_css);
		});
	});
	/**
	 * Single post title font.
	 */
	api('blogsy_typo_single_post_title', function (value) {
		value.bind(function (newval) {
			$style_tag = blogsy_get_style_tag('blogsy_typo_single_post_title');
			var style_css = blogsy_typography_field_css('.single-hero-title .title', newval);

			$style_tag.html(style_css);
		});
	});
	/**
	 * Single post content font.
	 */
	api('blogsy_typo_single_post_content', function (value) {
		value.bind(function (newval) {
			$style_tag = blogsy_get_style_tag('blogsy_typo_single_post_content');
			var style_css = blogsy_typography_field_css('.single-content-inner', newval);

			$style_tag.html(style_css);
		});
	});
	/**
	 * Primary menu font.
	 */
	api('blogsy_typo_menu', function (value) {
		value.bind(function (newval) {
			$style_tag = blogsy_get_style_tag('blogsy_typo_menu');
			var style_css = blogsy_typography_field_css('.blogsy-header-nav > li a', newval);

			blogsy_enqueue_google_font(newval['font-family']);

			$style_tag.html(style_css);
		});
	});
	/**
	 * Terms font.
	 */
	api('blogsy_typo_terms', function (value) {
		value.bind(function (newval) {
			$style_tag = blogsy_get_style_tag('blogsy_typo_terms');
			var style_css = blogsy_typography_field_css('.term-item, .single-hero-title .category a', newval);

			blogsy_enqueue_google_font(newval['font-family']);

			$style_tag.html(style_css);
		});
	});

	/**
	 *	Header > Topbar
	 *
	 *  */

	/**
	* Top Bar visibility.
	*/
	api('blogsy_top_bar_visibility', function (value) {
		value.bind(function (newval) {
			var $topbar = $('.blogsy-topbar');

			blogsy_print_visibility_classes($topbar, newval);
		});
	});

	//  Topbar background color
	api('blogsy_top_bar_background', function (value) {
		value.bind(function (newval) {
			var $topbar = $('.blogsy-topbar');

			if (!$topbar.length) {
				return;
			}

			$style_tag = blogsy_get_style_tag('blogsy_top_bar_background');
			var style_css = blogsy_design_options_css('.blogsy-topbar', newval, 'background');

			$style_tag.html(style_css);
		});
	});

	// Topbar font color
	api('blogsy_top_bar_text_color', function (value) {
		value.bind(function (newval) {
			var $topbar = $('.blogsy-topbar');

			if (!$topbar.length) {
				return;
			}

			$style_tag = blogsy_get_style_tag('blogsy_top_bar_text_color');
			var style_css = '';

			newval['text-color'] = newval['text-color'] ? newval['text-color'] : 'inherit';
			newval['link-color'] = newval['link-color'] ? newval['link-color'] : 'inherit';
			newval['link-hover-color'] = newval['link-hover-color'] ? newval['link-hover-color'] : 'inherit';

			// Text color.
			style_css += '.blogsy-topbar { color: ' + newval['text-color'] + '; }';
			style_css += '.blogsy-location { color: ' + newval['text-color'] + '; }';
			style_css += '.blogsy-topbar .header-preferences { color: ' + newval['text-color'] + '; }';

			// Link color.
			style_css +=
				'.blogsy-topbar-widget__text a, ' +
				'.blogsy-topbar-widget .blogsy-header-nav > li.menu-item > a, ' +
				'.blogsy-topbar-widget__socials .blogsy-social-icons-widget > ul > li > a { color: ' + newval['link-color'] + '; }';

			// Link hover color.
			style_css +=
				'.blogsy-topbar-widget .blogsy-header-nav > li.menu-item > a:hover, ' +
				'.blogsy-topbar-widget .blogsy-header-nav > li.menu-item > a:focus, ' +
				'.blogsy-topbar-widget .blogsy-header-nav > li.menu-item-has-children:hover > a, ' +
				'.blogsy-topbar-widget .blogsy-header-nav > li.current-menu-item > a, ' +
				'.blogsy-topbar-widget .blogsy-header-nav > li.current-menu-ancestor > a, ' +
				'.blogsy-topbar-widget__text a:focus, ' +
				'.blogsy-topbar-widget__text a:hover, ' +
				'.blogsy-topbar-widget__socials .blogsy-social-icons-widget > ul > li > a:focus, ' +
				'.blogsy-topbar-widget__socials .blogsy-social-icons-widget > ul > li > a:hover { color: ' + newval['link-hover-color'] + '; }';

			$style_tag.html(style_css);
		});
	});

	/**
	 * Topbar border.
	 */
	api('blogsy_top_bar_border', function (value) {
		value.bind(function (newval) {
			var $topbar = $('.blogsy-topbar');

			if (!$topbar.length) {
				return;
			}

			$style_tag = blogsy_get_style_tag('blogsy_top_bar_border');
			var style_css = blogsy_design_options_css('.blogsy-topbar', newval, 'border');

			$style_tag.html(style_css);
		});
	});

	//  Header background color
	api('blogsy_header_background', function (value) {
		value.bind(function (newval) {
			var $header = $('#site-header');

			if (!$header.length) {
				return;
			}

			var css_selector = `html:not([scheme="dark"]) .pt-header-layout-1 #site-header .pt-header-inner .pt-header-container::after,
							 html:not([scheme="dark"]) .pt-header-layout-2 #site-header .pt-header-inner,
							 html:not([scheme="dark"]) .pt-header-layout-3 #site-header .pt-header-inner > .pt-header-container,
							 html:not([scheme="dark"]) .pt-header-layout-4 #site-header .pt-header-inner`;

			$style_tag = blogsy_get_style_tag('blogsy_header_background');
			var style_css = blogsy_design_options_css(css_selector, newval, 'background');

			$style_tag.html(style_css);
		});
	});


	// Header font color
	api('blogsy_header_text_color', function (value) {
		value.bind(function (newval) {
			var $header = $('#site-header');
			if (!$header.length) {
				return;
			}

			$style_tag = blogsy_get_style_tag('blogsy_header_text_color');
			var style_css = '';

			newval['text-color'] = newval['text-color'] ? newval['text-color'] : 'inherit';
			newval['link-color'] = newval['link-color'] ? newval['link-color'] : 'inherit';
			newval['link-hover-color'] = newval['link-hover-color'] ? newval['link-hover-color'] : 'inherit';
			newval['link-active-color'] = newval['link-active-color'] ? newval['link-active-color'] : 'inherit';

			// Text color.
			style_css += 'html:not([scheme="dark"]) .pt-header { color: ' + newval['text-color'] + '; }';

			// Link color.
			style_css +=
				'html:not([scheme="dark"]) .pt-header .blogsy-header-nav > li > a, html:not([scheme="dark"]) .pt-header .blogsy-header-v-nav > li > a, ' +
				'html:not([scheme="dark"]) .pt-header .pt-header-widget .blogsy-social-icons-widget:not(.minimal-fill, .rounded-fill) > ul > li > a { color: ' + newval['link-color'] + '; }';

			// Link hover color.
			style_css +=
				'html .pt-header .blogsy-header-nav > li > a:hover, ' +
				'html .pt-header .blogsy-header-nav > li.hovered > a, ' +
				'html .pt-header .blogsy-header-nav > li.current_page_item > a, ' +
				'html .pt-header .blogsy-header-nav > li.current-menu-item > a, ' +
				'html .pt-header .blogsy-header-nav > li.current-menu-ancestor > a' +
				'html .pt-header .blogsy-header-v-nav > li a:focus, ' +
				'html .pt-header .blogsy-header-v-nav > li a:hover, ' +
				'html .pt-header .pt-header-widget .blogsy-social-icons-widget:not(.minimal-fill, .rounded-fill) > ul > li > a:focus, ' +
				'html .pt-header .pt-header-widget .blogsy-social-icons-widget:not(.minimal-fill, .rounded-fill) > ul > li > a:hover { color: ' + newval['link-hover-color'] + '; }';

			// Link active color.
			style_css +=
				'html .pt-header .blogsy-header-nav > li.menu-item > a { --menu-shape-color: ' + newval['link-active-color'] + '; }';

			$style_tag.html(style_css);
		});
	});

	/**
	 * Header border.
	 */
	api('blogsy_header_border', function (value) {
		value.bind(function (newval) {
			var $header = $('#site-header');

			if (!$header.length) {
				return;
			}

			var css_selector = `.pt-header-layout-1 #site-header .pt-header-inner .pt-header-container::after,
							 .pt-header-layout-2 #site-header .pt-header-inner,
							 .pt-header-layout-3 #site-header .pt-header-inner > .pt-header-container,
							 .pt-header-layout-4 #site-header .pt-header-inner .pt-logo-container .pt-header-container
							 `;

			$style_tag = blogsy_get_style_tag('blogsy_header_border');
			var style_css = blogsy_design_options_css(css_selector, newval, 'border');

			$style_tag.html(style_css);
		});
	});


	//  Header navigation background color
	api('blogsy_header_navigation_background', function (value) {
		value.bind(function (newval) {
			var $header = $('#site-header .pt-nav-container');

			if (!$header.length) {
				return;
			}

			var css_selector = `html:not([scheme="dark"]) .pt-header-layout-4 #site-header .pt-header-inner .pt-nav-container`;

			$style_tag = blogsy_get_style_tag('blogsy_header_navigation_background');
			var style_css = blogsy_design_options_css(css_selector, newval, 'background');

			$style_tag.html(style_css);
		});
	});


	// Header navigation font color
	api('blogsy_header_navigation_text_color', function (value) {
		value.bind(function (newval) {
			var $header = $('#site-header .pt-nav-container');
			if (!$header.length) {
				return;
			}

			$style_tag = blogsy_get_style_tag('blogsy_header_navigation_text_color');
			var style_css = '';

			newval['text-color'] = newval['text-color'] ? newval['text-color'] : 'inherit';
			newval['link-color'] = newval['link-color'] ? newval['link-color'] : 'inherit';
			newval['link-hover-color'] = newval['link-hover-color'] ? newval['link-hover-color'] : 'inherit';
			newval['link-active-color'] = newval['link-active-color'] ? newval['link-active-color'] : 'inherit';

			// Text color.
			style_css += 'html:not([scheme="dark"]) .pt-header .pt-nav-container { color: ' + newval['text-color'] + '; }';

			// Link color.
			style_css +=
				'html:not([scheme="dark"]) .pt-header .pt-nav-container .blogsy-header-nav > li > a, ' +
				'html:not([scheme="dark"]) .pt-header .pt-nav-container .blogsy-header-v-nav > li > a, ' +
				'html:not([scheme="dark"]) .pt-header .pt-nav-container .pt-header-widget .blogsy-social-icons-widget:not(.minimal-fill, .rounded-fill) > ul > li > a { color: ' + newval['link-color'] + '; }';

			// Link hover color.
			style_css +=
				'html .pt-header .pt-nav-container .blogsy-header-nav > li > a:hover, ' +
				'html .pt-header .pt-nav-container .blogsy-header-nav > li.hovered > a, ' +
				'html .pt-header .pt-nav-container .blogsy-header-nav > li.current_page_item > a, ' +
				'html .pt-header .pt-nav-container .blogsy-header-nav > li.current-menu-item > a, ' +
				'html .pt-header .pt-nav-container .blogsy-header-nav > li.current-menu-ancestor > a' +
				'html .pt-header .pt-nav-container .blogsy-header-v-nav > li a:focus, ' +
				'html .pt-header .pt-nav-container .blogsy-header-v-nav > li a:hover, ' +
				'html .pt-header .pt-nav-container .pt-header-widget .blogsy-social-icons-widget:not(.minimal-fill, .rounded-fill) > ul > li > a:focus, ' +
				'html .pt-header .pt-nav-container .pt-header-widget .blogsy-social-icons-widget:not(.minimal-fill, .rounded-fill) > ul > li > a:hover { color: ' + newval['link-hover-color'] + '; }';

			// Link active color.
			style_css +=
				'html .pt-header .pt-nav-container .blogsy-header-nav > li.menu-item > a { --menu-shape-color: ' + newval['link-active-color'] + '; }';

			$style_tag.html(style_css);
		});
	});

	/**
	 * Header navigation border.
	 */
	api('blogsy_header_navigation_border', function (value) {
		value.bind(function (newval) {
			var $header = $('#site-header .pt-nav-container');

			if (!$header.length) {
				return;
			}

			var css_selector = `.pt-header-layout-4 .pt-header .pt-header-inner .pt-nav-container .pt-header-container`;

			$style_tag = blogsy_get_style_tag('blogsy_header_navigation_border');
			var style_css = blogsy_design_options_css(css_selector, newval, 'border');

			$style_tag.html(style_css);
		});
	});


	/**
	 * Footer Custom
	 */
	//Background
	api('blogsy_footer_widget_background', function (value) {
		value.bind(function (newval) {
			var $footer = $('.site-default-footer');

			if (!$footer.length) {
				return;
			}
			var copyright_separator_color;
			// Copyright separator color.
			if (newval['background-type'] === 'color') {
				var light_or_dark = newval['background-color'] === '#ffffff' ? -0.1 : 0.2;
				copyright_separator_color = blogsy_luminance(newval['background-color'], light_or_dark);
			}

			$style_tag = blogsy_get_style_tag('blogsy_footer_widget_background');
			var style_css = blogsy_design_options_css('.site-default-footer', newval, 'background');
			style_css += '.site-default-footer .default-footer-copyright { border-top-color: ' + copyright_separator_color + '; }';
			$style_tag.html(style_css);
		});
	});
	// Font color
	api('blogsy_footer_widget_text_color', function (value) {
		value.bind(function (newval) {
			var $footer = $('.site-default-footer');

			if (!$footer.length) {
				return;
			}



			$style_tag = blogsy_get_style_tag('blogsy_footer_widget_text_color');
			var style_css = '';

			newval['text-color'] = newval['text-color'] ? newval['text-color'] : 'inherit';
			newval['link-color'] = newval['link-color'] ? newval['link-color'] : 'inherit';
			newval['link-hover-color'] = newval['link-hover-color'] ? newval['link-hover-color'] : 'inherit';

			// Text color.
			style_css += '.site-default-footer, .site-default-footer .blogsy-divider-heading .title, .site-default-footer .wp-block-heading { color: ' + newval['text-color'] + '; }';

			// Link color.
			style_css += '.site-default-footer a' + '{ color: ' + newval['link-color'] + '; }';

			// Link hover color.
			style_css +=
				'.site-default-footer a:hover, ' +
				'.site-default-footer a:focus { color: ' + newval['link-hover-color'] + '; }';

			$style_tag.html(style_css);
		});
	});
	/**
	 * Footer border.
	 */
	api('blogsy_footer_widget_area_border', function (value) {
		value.bind(function (newval) {
			var $footer = $('.site-default-footer');

			if (!$footer.length) {
				return;
			}

			$style_tag = blogsy_get_style_tag('blogsy_footer_widget_area_border');
			var style_css = blogsy_design_options_css('.site-default-footer', newval, 'border');

			$style_tag.html(style_css);
		});
	});

	/**
	 * Footer offcanvas menu
	 */
	//Background
	api('blogsy_footer_canvas_menu_bg', function (value) {
		value.bind(function (newval) {
			var $footer_canvas = $('.footer-canvas-menu-bg');

			if (!$footer_canvas.length) {
				return;
			}

			$style_tag = blogsy_get_style_tag('blogsy_footer_canvas_menu_bg');
			var style_css = blogsy_design_options_css('.footer-canvas-menu-bg', newval, 'background');

			$style_tag.html(style_css);
		});
	});
	// Font color
	api('blogsy_footer_canvas_menu_color', function (value) {
		value.bind(function (newval) {
			var $footer_canvas = $('.footer-canvas-menu-bg');

			if (!$footer_canvas.length) {
				return;
			}

			$style_tag = blogsy_get_style_tag('blogsy_footer_canvas_menu_color');
			var style_css = '';

			newval['text-color'] = newval['text-color'] ? newval['text-color'] : 'inherit';
			newval['link-color'] = newval['link-color'] ? newval['link-color'] : 'inherit';
			newval['link-hover-color'] = newval['link-hover-color'] ? newval['link-hover-color'] : 'inherit';

			// Text color.
			style_css += '.footer-canvas-menu, .footer-canvas-menu .blogsy-divider-heading .title, .footer-canvas-menu .wp-block-heading { color: ' + newval['text-color'] + '; }';

			// Link color.
			style_css += '.footer-canvas-menu .blogsy-header-v-nav li a , .footer-canvas-menu-btn' + '{ color: ' + newval['link-color'] + '; }';

			// Link hover color.
			style_css +=
				'.footer-canvas-menu .blogsy-header-v-nav li a:hover,' +
				'.footer-canvas-menu .blogsy-header-v-nav li a:focus,' +
				'.footer-canvas-menu .blogsy-header-v-nav li.current_page_item a,' +
				'.footer-canvas-menu .blogsy-header-v-nav li.current-menu-item a { color: ' + newval['link-hover-color'] + '; }';


			$style_tag.html(style_css);
		});
	});

	/**
	 * Blog post title font size.
	 */
	api('blogsy_blog_title_font_size', function (value) {
		value.bind(function (newval) {
			var $blog_default = $('.default-archive-container');

			if (!$blog_default.length) {
				return;
			}

			$style_tag = blogsy_get_style_tag('blogsy_blog_title_font_size');
			var style_css = '';

			style_css += blogsy_range_field_css('.default-archive-container .post-wrapper .title', 'font-size', newval, true, newval.unit);

			$style_tag.html(style_css);
		});
	});

	/**
	 * Ticker title.
	 */
	api('blogsy_ticker_title', function (value) {
		value.bind(function (newval) {
			$('#blogsy-ticker .blogsy-news-ticker-title-text').text(newval);
		});
	});

	/**
	 * Ticker visibility.
	 */
	api('blogsy_ticker_visibility', function (value) {
		value.bind(function (newval) {
			blogsy_print_visibility_classes($('#blogsy-ticker'), newval);
		});
	});

	/**
	 * Hero height.
	 */
	api('blogsy_hero_slider_height', function (value) {
		value.bind(function (newval) {
			var $hero = $('#blogsy-hero');

			if (!$hero.length) {
				return;
			}

			$style_tag = blogsy_get_style_tag('blogsy_hero_slider_height');
			var style_css = '';

			// Main hero (normal).
			style_css += blogsy_range_field_css('#blogsy-hero .pt-hero-slider .post-wrapper, #blogsy-hero .blogsy-post-elastic-widget .swiper-vertical', 'height', newval, true, 'px');

			// Nexo card (half).
			style_css += blogsy_range_field_css('#blogsy-hero:has(.pt-hero-slider) .blogsy-post-nexo-widget:not(.pt-hero-slider) .post-wrapper', 'height', newval, true, 'px', true, 9.5);

			$style_tag.html(style_css);
		});
	});

	/**
	 * Hero post title font size.
	 */
	api('blogsy_hero_slider_title_font_size', function (value) {
		value.bind(function (newval) {
			var $hero = $('#blogsy-hero');

			if (!$hero.length) {
				return;
			}

			$style_tag = blogsy_get_style_tag('blogsy_hero_slider_title_font_size');
			var style_css = '';

			style_css += blogsy_range_field_css('#blogsy-hero .pt-hero-slider .post-wrapper .title', 'font-size', newval, true, newval.unit);

			$style_tag.html(style_css);
		});
	});

	/**
	 * Hero visibility.
	 */
	api('blogsy_hero_visibility', function (value) {
		value.bind(function (newval) {
			blogsy_print_visibility_classes($('#blogsy-hero'), newval);
		});
	});

	/**
	 * Featured Category title.
	 */
	api('blogsy_featured_category_title', function (value) {
		value.bind(function (newval) {
			$('#blogsy-featured-category .blogsy-divider-heading .title').text(newval);
		});
	});

	/**
	 * Hero post title font size.
	 */
	api('blogsy_ticker_speed', function (value) {
		value.bind(function (newval) {
			var $hero = $('#blogsy-ticker');

			if (!$hero.length) {
				return;
			}

			$style_tag = blogsy_get_style_tag('blogsy_ticker_speed');
			var style_css = '';

			style_css += blogsy_range_field_css('#blogsy-ticker .blogsy-ticker .blogsy-news-ticker-content-wrapper.animation-marquee .blogsy-news-ticker-items', '--marquee-time', newval, true, 's');

			$style_tag.html(style_css);
		});
	});

	/**
	 * Featured Category visibility.
	 */
	api('blogsy_featured_category_visibility', function (value) {
		value.bind(function (newval) {
			blogsy_print_visibility_classes($('#blogsy-featured-category'), newval);
		});
	});

	/**
	 * Featured Links title.
	 */
	api('blogsy_featured_links_title', function (value) {
		value.bind(function (newval) {
			$('#blogsy-featured-links .blogsy-divider-heading .title').text(newval);
		});
	});

	/**
	 * Featured Links visibility.
	 */
	api('blogsy_featured_links_visibility', function (value) {
		value.bind(function (newval) {
			blogsy_print_visibility_classes($('#blogsy-featured-links'), newval);
		});
	});


	/**
	 * Stories title.
	 */
	api('blogsy_stories_title', function (value) {
		value.bind(function (newval) {
			$('#blogsy-stories .blogsy-divider-heading .title').text(newval);
		});
	});

	/**
	 * Stories View All.
	 */
	api('blogsy_stories_view_all', function (value) {
		value.bind(function (newval) {
			$('#blogsy-stories .blogsy-section-heading .pt-button-text .text').text(newval);
		});
	});

	/**
	 * Stories visibility.
	 */
	api('blogsy_stories_visibility', function (value) {
		value.bind(function (newval) {
			blogsy_print_visibility_classes($('#blogsy-stories'), newval);
		});
	});

	/**
	 * PYML title.
	 */
	api('blogsy_pyml_title', function (value) {
		value.bind(function (newval) {
			$('#blogsy-pyml .blogsy-divider-heading .title').text(newval);
		});
	});

	/**
	 * PYML visibility.
	 */
	api('blogsy_pyml_visibility', function (value) {
		value.bind(function (newval) {
			blogsy_print_visibility_classes($('#blogsy-pyml'), newval);
		});
	});


	/**
	 * Card Box Shadow.
	 */
	api('blogsy_card_widget_box_shadow', function (value) {
		value.bind(function (newval) {
			var $sidebar = $('html:not([scheme="dark"]) .card-layout, html:not([scheme="dark"]) .card-layout-w');

			if (!$sidebar.length) {
				return;
			}

			$style_tag = blogsy_get_style_tag('blogsy_card_widget_box_shadow');
			var style_css = blogsy_design_options_css('html:not([scheme="dark"]) .card-layout, html:not([scheme="dark"]) .card-layout-w', newval, 'box-shadow');

			$style_tag.html(style_css);
		});
	});

	/**
	 * Card Border.
	 */
	api('blogsy_card_widget_border', function (value) {
		value.bind(function (newval) {
			var $sidebar = $('html:not([scheme="dark"]) .card-layout, html:not([scheme="dark"]) .card-layout-w');

			if (!$sidebar.length) {
				return;
			}

			$style_tag = blogsy_get_style_tag('blogsy_card_widget_border');
			var style_css = blogsy_design_options_css('html:not([scheme="dark"]) .card-layout, html:not([scheme="dark"]) .card-layout-w', newval, 'border');

			$style_tag.html(style_css);
		});
	});

	/**
	 * Card Background Color.
	 */
	api('blogsy_card_widget_bg_color', function (value) {
		value.bind(function (newval) {
			$style_tag = blogsy_get_style_tag('blogsy_card_widget_bg_color');

			if (!newval) {
				$style_tag.html('');
				return;
			}

			var style_css = 'html:not([scheme="dark"]) .card-layout, html:not([scheme="dark"]) .card-layout-w { background: ' + newval + '; }';

			$style_tag.html(style_css);
		});
	});


	/**
	 * Sidebar Widget Box Shadow.
	 */
	api('blogsy_sidebar_widget_box_shadow', function (value) {
		value.bind(function (newval) {
			var $sidebar = $('.sidebar-container .sidebar-container-inner > .blogsy-sidebar-widget');

			if (!$sidebar.length) {
				return;
			}

			$style_tag = blogsy_get_style_tag('blogsy_sidebar_widget_box_shadow');
			var style_css = blogsy_design_options_css('html:not([scheme="dark"]) .sidebar-container .sidebar-container-inner > .blogsy-sidebar-widget', newval, 'box-shadow');

			$style_tag.html(style_css);
		});
	});

	/**
	 * Sidebar Widget Border.
	 */
	api('blogsy_sidebar_widget_border', function (value) {
		value.bind(function (newval) {
			var $sidebar = $('.sidebar-container .sidebar-container-inner > .blogsy-sidebar-widget');

			if (!$sidebar.length) {
				return;
			}

			$style_tag = blogsy_get_style_tag('blogsy_sidebar_widget_border');
			var style_css = blogsy_design_options_css('html:not([scheme="dark"]) .sidebar-container .sidebar-container-inner > .blogsy-sidebar-widget', newval, 'border');

			$style_tag.html(style_css);
		});
	});

	/**
	 * Sidebar Widget Background Color.
	 */
	api('blogsy_sidebar_widget_bg_color', function (value) {
		value.bind(function (newval) {
			$style_tag = blogsy_get_style_tag('blogsy_sidebar_widget_bg_color');

			if (!newval) {
				$style_tag.html('');
				return;
			}

			var style_css = 'html:not([scheme="dark"]) .sidebar-container .sidebar-container-inner > .blogsy-sidebar-widget { background: ' + newval + '; }';

			$style_tag.html(style_css);
		});
	});

	/**
	 * Add a Box Shadow On The Footer.
	 */
	api('blogsy_footer_box_shadow_overlay', function (value) {
		value.bind(function (newval) {
			var $mainWrapper = $('.main-wrapper, .main-wrapper+#blogsy-pyml');

			if (!$mainWrapper.length) {
				return;
			}

			$style_tag = blogsy_get_style_tag('blogsy_footer_box_shadow_overlay');
			var style_css = blogsy_design_options_css('.main-wrapper, .main-wrapper+#blogsy-pyml', newval, 'box-shadow');

			$style_tag.html(style_css);
		});
	});

	// Selective refresh.
	if (api.selectiveRefresh) {

		// Bind partial content rendered event.
		api.selectiveRefresh.bind('partial-content-rendered', function (placement) {

			// Hero Slider.
			if ('blogsy_hero_slider_post_number' === placement.partial.id || 'blogsy_hero_slider_elements' === placement.partial.id) {
				document.querySelectorAll(placement.partial.params.selector).forEach((item) => {
					window.blogsy.blogsyHeroSlider($(item));
				});
			}

			// PYML Slider.
			if ('blogsy_pyml_post_number' === placement.partial.id || 'blogsy_pyml_elements' === placement.partial.id) {
				document.querySelectorAll(placement.partial.params.selector).forEach((item) => {
					window.blogsy.blogsyPymlSlider($(item));
				});
			}

		});
	}


	/*
	 * Helper function to print visibility classes.
	 */
	function blogsy_print_visibility_classes($element, newval) {
		if (!$element.length) {
			return;
		}

		$element.removeClass(blogsy_visibility_classes);

		if ('all' !== newval) {
			$element.addClass('blogsy-' + newval);
		}
	}


	/*
	 * Helper function to convert hex to rgba.
	 */
	function blogsy_hex2rgba(hex, opacity) {
		if ('rgba' === hex.substring(0, 4)) {
			return hex;
		}

		// Expand shorthand form (e.g. "03F") to full form (e.g. "0033FF").
		var shorthandRegex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;

		hex = hex.replace(shorthandRegex, function (m, r, g, b) {
			return r + r + g + g + b + b;
		});

		var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);

		if (opacity) {
			if (1 < opacity) {
				opacity = 1;
			}

			opacity = ',' + opacity;
		}

		if (result) {
			return 'rgba(' + parseInt(result[1], 16) + ',' + parseInt(result[2], 16) + ',' + parseInt(result[3], 16) + opacity + ')';
		}

		return false;
	}

	/**
	 * Helper function to lighten or darken the provided hex color.
	 */
	function blogsy_luminance(hex, percent) {

		// Convert RGB color to HEX.
		if (hex.includes('rgb')) {
			hex = blogsy_rgba2hex(hex);
		}

		// Expand shorthand form (e.g. "03F") to full form (e.g. "0033FF").
		var shorthandRegex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;

		hex = hex.replace(shorthandRegex, function (m, r, g, b) {
			return r + r + g + g + b + b;
		});

		var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);

		var isColor = /^#[0-9A-F]{6}$/i.test(hex);

		if (!isColor) {
			return hex;
		}

		var from, to;

		for (var i = 1; 3 >= i; i++) {
			result[i] = parseInt(result[i], 16);
			from = 0 > percent ? 0 : result[i];
			to = 0 > percent ? result[i] : 255;
			result[i] = result[i] + Math.ceil((to - from) * percent);
		}

		result = '#' + blogsy_dec2hex(result[1]) + blogsy_dec2hex(result[2]) + blogsy_dec2hex(result[3]);

		return result;
	}

	/**
	 * Convert dec to hex.
	 */
	function blogsy_dec2hex(c) {
		var hex = c.toString(16);
		return 1 == hex.length ? '0' + hex : hex;
	}

	/**
	 * Convert rgb to hex.
	 */
	function blogsy_rgba2hex(c) {
		var a, x;

		a = c.split('(')[1].split(')')[0].trim();
		a = a.split(',');

		var result = '';

		for (var i = 0; 3 > i; i++) {
			x = parseInt(a[i]).toString(16);
			result += 1 === x.length ? '0' + x : x;
		}

		if (result) {
			return '#' + result;
		}

		return false;
	}

	/**
	 * Check if is light color.
	 */
	function blogsy_is_light_color(color = '') {
		var r, g, b, brightness;

		if (color.match(/^rgb/)) {
			color = color.match(/^rgba?\((\d+),\s*(\d+),\s*(\d+)(?:,\s*(\d+(?:\.\d+)?))?\)$/);
			r = color[1];
			g = color[2];
			b = color[3];
		} else {
			color = +('0x' + color.slice(1).replace(5 > color.length && /./g, '$&$&'));
			r = color >> 16;
			g = (color >> 8) & 255;
			b = color & 255;
		}

		brightness = (r * 299 + g * 587 + b * 114) / 1000;

		return 137 < brightness;
	}

	/**
	 * Detect if we should use a light or dark color on a background color.
	 */
	function blogsy_light_or_dark(color, dark = '#000000', light = '#FFFFFF') {
		return blogsy_is_light_color(color) ? dark : light;
	}

	// Custom Customizer Preview class (attached to the Customize API)
	api.blogsyCustomizerPreview = {

		// Init
		init: function () {
			var self = this; // Store a reference to "this"
			var previewBody = self.preview.body;

			previewBody.on('click', '.blogsy-set-widget', function (e) {
				e.preventDefault();
				self.preview.send('set-footer-widget', $(this).data('sidebar-id'));
			});
		}
	};

	/**
	 * Capture the instance of the Preview since it is private (this has changed in WordPress 4.0)
	 *
	 * @see https://github.com/WordPress/WordPress/blob/5cab03ab29e6172a8473eb601203c9d3d8802f17/wp-admin/js/customize-controls.js#L1013
	 */
	var blogsyOldPreview = api.Preview;
	api.Preview = blogsyOldPreview.extend({
		initialize: function (params, options) {

			// Store a reference to the Preview
			api.blogsyCustomizerPreview.preview = this;

			// Call the old Preview's initialize function
			blogsyOldPreview.prototype.initialize.call(this, params, options);
		}
	});

	// Document ready
	$(function () {

		// Initialize our Preview
		api.blogsyCustomizerPreview.init();
	});

}(jQuery));

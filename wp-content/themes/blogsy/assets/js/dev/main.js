(function ($) {
	'use strict';

	// KEYBOARD EVENT DETECTION FUNCTION
	function isActivationKey(e) {
		// Modern approach with proper fallback
		return (e.key === 'Enter' || e.key === ' ') ||
			(e.keyCode === 13 || e.keyCode === 32);
	}

	// Check if element fully in viewport
	function isScrolledIntoViewport(elem) {
		const $elem = $(elem);
		const docViewTop = $(window).scrollTop();
		const docViewBottom = docViewTop + $(window).height();
		const elemTop = $elem.offset().top;
		const elemBottom = elemTop + $elem.outerHeight();
		return (elemBottom <= docViewBottom) && (elemTop >= docViewTop);
	}

	// AOS initialization from classes.
	$("[class*='aos-']").each(function () {
		var $el = $(this), cls = this.className;
		if (cls.indexOf('aos-init') !== -1) return;

		var m = cls.match(/aos-([a-z-]+)(?:\s|$)/);
		if (m) $el.attr('data-aos', m[1]).addClass('aos-init');

		m = cls.match(/aos-duration-(\d+)(?:\s|$)/);
		if (m) $el.attr('data-aos-duration', m[1]);

		m = cls.match(/aos-delay-(\d+)(?:\s|$)/);
		if (m) $el.attr('data-aos-delay', m[1]);
	});

	AOS.init({
		once: true,
		duration: 600
	});


	// Add class to body on window load
	$(window).on('load', () => $("body").addClass("site-loaded"));

	if (typeof SmoothScroll === 'function' && $('body').hasClass('blogsy-smooth-scroll')) {
		SmoothScroll({ keyboardSupport: false });
	}

	// Offcanvas click handlers
	$('.offcanvas-container').on('click', e => e.stopPropagation());

	$('body').on('click keydown', '.offcanvas-opener', function (e) {
		if (e.type === 'click' || (e.type === 'keydown' && isActivationKey(e))) {
			if (e.type === 'keydown') e.preventDefault();
			e.stopPropagation();
			const $offcanvas = $(this).closest('.blogsy-offcanvas');
			setTimeout(() => $offcanvas.find('.offcanvas-close').focus(), 100);
			$offcanvas.find('.offcanvas-container-inner').on('keydown', handleKeyboardNavigation);
			$offcanvas.find('.offcanvas-wrapper').addClass('open');
		}
	});

	$(document).on('click', '.offcanvas-wrapper', function (e) {
		if (e.target === this) {
			$('.offcanvas-wrapper').removeClass('open');
			const $offcanvas = $(this).closest('.blogsy-offcanvas');
			$offcanvas.find('.offcanvas-opener').focus();
			$offcanvas.find('.offcanvas-container-inner').off('keydown', handleKeyboardNavigation);
		}
	});

	$('.offcanvas-close').on('click keydown', function (e) {
		if (e.type === 'click' || (e.type === 'keydown' && isActivationKey(e))) {
			if (e.type === 'keydown') e.preventDefault();
			$('.offcanvas-wrapper').removeClass('open');
			const $offcanvas = $(this).closest('.blogsy-offcanvas');
			$offcanvas.find('.offcanvas-opener').focus();
			$offcanvas.find('.offcanvas-container-inner').off('keydown', handleKeyboardNavigation);
		}
	});

	// Popup search handlers
	$('body').on('click keydown', '.popup-search-opener', function (e) {
		if (e.type === 'click' || (e.type === 'keydown' && isActivationKey(e))) {
			if (e.type === 'keydown') e.preventDefault();
			e.stopPropagation();

			let $wrapper;
			const $style3Wrapper = $('.popup-search-wrapper.style-3');

			if ($style3Wrapper.length) {
				// Style-3 specific handling
				$('.pt-header-inner .blogsy-header-nav-wrapper').css('display', 'none');

				$wrapper = $style3Wrapper;
				$wrapper.css({
					'display': 'flex',
					'flex-direction': 'column',
					'justify-content': 'center',
					'flex': '1 0 auto',
					'max-width': '100%'
				});

				const $popupSearch = $wrapper.find('.popup-search');
				$popupSearch.addClass('open');

				// Add keyboard trap handler
				$popupSearch.off('keydown', handleKeyboardNavigation);
				$popupSearch.on('keydown', handleKeyboardNavigation);

				// Focus search field after delay
				setTimeout(() => {
					$wrapper.find('.search-field').first().focus();
				}, 150);

			} else {
				// Style-1 handling (default)
				$wrapper = $(this).closest('.popup-search-wrapper');
				const $popupSearch = $wrapper.find('.popup-search');

				$popupSearch.addClass('open');
				$popupSearch.off('keydown', handleKeyboardNavigation);
				$popupSearch.on('keydown', handleKeyboardNavigation);

				setTimeout(() => {
					$wrapper.find('.search-field').first().focus();
				}, 150);
			}
		}
	});

	$('.popup-search-form').on('click', e => e.stopPropagation());

	// Close search popup function
	function closeSearchPopup() {
		const $style3Wrapper = $('.popup-search-wrapper.style-3');

		if ($style3Wrapper.length && $style3Wrapper.css('display') !== 'none') {
			// Style-3 close handling
			$('.pt-header-inner .blogsy-header-nav-wrapper').css('display', '');
			$style3Wrapper.css('display', 'none');

			const $popupSearch = $style3Wrapper.find('.popup-search');
			$popupSearch.removeClass('open');
			$popupSearch.off('keydown', handleKeyboardNavigation);

			// Return focus to opener button
			setTimeout(() => {
				$('.popup-search-opener').first().focus();
			}, 50);
		} else {
			// Style-1 close handling
			const $allPopups = $('.popup-search.open');

			$allPopups.each(function () {
				const $popup = $(this);
				const $wrapper = $popup.closest('.popup-search-wrapper');

				$popup.removeClass('open');
				$popup.off('keydown', handleKeyboardNavigation);

				// Return focus to opener
				setTimeout(() => {
					$wrapper.find('.popup-search-opener').first().focus();
				}, 50);
			});
		}
	}
	// Close button handler
	$('body').on('click keydown', '.popup-search-close', function (e) {
		if (e.type === 'click' || (e.type === 'keydown' && isActivationKey(e))) {
			if (e.type === 'keydown') e.preventDefault();
			e.stopPropagation();
			closeSearchPopup();
		}
	});
	// Escape key handler
	$(document).on('keydown', function (e) {
		if (e.key === 'Escape' || e.keyCode === 27) {
			const $openPopup = $('.popup-search.open');
			if ($openPopup.length) {
				closeSearchPopup();
			}
		}
	});
	// Click outside to close
	$('body').on('click', function (e) {
		const $target = $(e.target);
		// Don't close if clicking inside search or on opener
		if ($target.closest('.popup-search-container').length ||
			$target.closest('.popup-search-opener').length) {
			return;
		}
		// Check if any popup is open
		if ($('.popup-search.open').length) {
			closeSearchPopup();
		}
	});

	// Time update display
	const $timeElem = $('#blogsy-time');
	if ($timeElem.length) {
		$timeElem.text(
			setInterval(() => {
				$timeElem.text(new Date().toLocaleTimeString());
			}, 1000)
		);
	}

	// Date update display with locale formatting
	const $dateElem = $('#blogsy-date');
	if ($dateElem.length) {
		const locale = $('html').attr('lang') || 'en';
		const options = { weekday: 'short', month: 'short', day: 'numeric', year: 'numeric' };
		$dateElem.text(new Date().toLocaleString(locale, options));
	}

	// Keyboard navigation trap in modals/popups
	function handleKeyboardNavigation(e) {
		const selectors = 'input, a, button, [tabindex]:not([tabindex="-1"])';
		const elements = $(this).find(selectors).filter(':visible');
		const firstEl = elements.first()[0];
		const lastEl = elements.last()[0];
		if ((e.key === 'Tab' || e.keyCode === 9)) {
			if (!e.shiftKey && document.activeElement === lastEl) {
				e.preventDefault();
				firstEl.focus();
			} else if (e.shiftKey && document.activeElement === firstEl) {
				e.preventDefault();
				lastEl.focus();
			}
		}
	}

	// Sticky header on scroll
	$(window).on('scroll', () => {
		const $stickyHeader = $('#site-sticky-header');
		if (!$stickyHeader.length) return;

		const stickyPos = $('#site-header').outerHeight() + 500;
		const scroll = $(window).scrollTop();

		if (scroll > stickyPos) {
			$stickyHeader.addClass('fixed');
			const stickyHeight = $stickyHeader.outerHeight();
			$('.sidebar-container.sticky .sidebar-container-inner, .e-con.blogsy-sticky-column').css('top', stickyHeight + 10);
		} else {
			$stickyHeader.removeClass('fixed');
			$stickyHeader.find('.popup-search, .offcanvas-wrapper').removeClass('open');
			$('.sidebar-container.sticky .sidebar-container-inner, .e-con.blogsy-sticky-column').css('top', 10);
		}
	});

	// Prevent empty anchor jumps in header nav
	$('.blogsy-header-nav li > a[href="#"], .blogsy-header-nav li > a[href=""], .blogsy-header-nav li > a:not([href])').on('click keydown', function (e) {
		// Only prevent empty/invalid links, not all links
		const href = $(this).attr('href');
		if (!href || href === '#' || href === '') {
			e.preventDefault();
			return false;
		}
	});

	// Header vertical nav toggles
	$('.blogsy-header-v-nav li.menu-item-has-children > .blogsy-mobile-toggle, .blogsy-header-v-nav li.page_item_has_children > .blogsy-mobile-toggle').on('click keydown', function (e) {
		if (e.type === 'click' || (e.type === 'keydown' && isActivationKey(e))) {
			if (e.type === 'keydown') e.preventDefault();
			$(this).parent().toggleClass('open').children('.sub-menu, .children').slideToggle();
		}
	});

	function blogsyMenuFocus() {
		let self = this;
		while (self && !self.classList.contains('blogsy-header-nav-wrapper') && !self.classList.contains('blogsy-header-v-nav-wrapper')) {
			if (self.tagName.toLowerCase() === 'li') {
				self.classList.toggle('hovered');
				const hasSubmenu = self.querySelector('ul');
				if (hasSubmenu) {
					self.setAttribute('aria-expanded', self.classList.contains('hovered'));
				}
			}
			self = self.parentElement;
		}
	}

	// Menu Accessibility Setup Function
	function setupMenuAccessibility() {
		if (!document.body.classList.contains('blogsy-menu-accessibility')) return;

		document.querySelectorAll('.blogsy-header-nav-wrapper, .blogsy-header-v-nav-wrapper').forEach(menu => {
			// Set aria-haspopup for all submenus
			menu.querySelectorAll('ul').forEach(subMenu => {
				subMenu.parentNode.setAttribute('aria-haspopup', 'true');
			});

			// Remove old listeners first to prevent duplicates
			menu.querySelectorAll('a').forEach(link => {
				link.removeEventListener('focus', blogsyMenuFocus, true);
				link.removeEventListener('blur', blogsyMenuFocus, true);
			});

			// Add focus/blur listeners to all links (including dynamically added ones)
			menu.querySelectorAll('a').forEach(link => {
				link.addEventListener('focus', blogsyMenuFocus, true);
				link.addEventListener('blur', blogsyMenuFocus, true);
			});
		});
	}

	// Run initially
	setupMenuAccessibility();

	// Custom debounce function
	function blogsyDebounce(func, wait) {
		let timeout;
		return function () {
			clearTimeout(timeout);
			timeout = setTimeout(() => func.apply(this, arguments), wait);
		};
	}

	// Mobile detection - adds/removes body class based on breakpoint
	function blogsyCheckMobileMenu() {
		const responsiveBreakpoint = blogsy_ajax_object.header_breakpoint || 1025;

		// Update body class if mobile breakpoint is reached
		if (window.innerWidth < responsiveBreakpoint) {
			document.body.classList.add('blogsy-is-mobile');
		} else {
			if (document.body.classList.contains('blogsy-is-mobile')) {
				document.body.classList.remove('blogsy-is-mobile');
			}
		}
	}

	// Smart Submenus - Prevents submenu overflow
	function blogsySmartSubmenus() {
		if (document.body.classList.contains('blogsy-is-mobile')) return;

		var winRight = window.innerWidth;
		const menuSelectors = '.blogsy-header-nav .sub-menu, .blogsy-header-nav .children';
		const submenus = document.querySelectorAll(menuSelectors);

		if (!submenus.length) return;

		submenus.forEach((item) => {
			const parentLi = item.closest('li');
			if (!parentLi) return;

			parentLi.classList.remove('opens-left', 'opens-right');

			// Store original styles
			const originalDisplay = item.style.display;
			const originalVisibility = item.style.visibility;
			const originalOpacity = item.style.opacity;

			// Temporarily show for calculation
			item.style.display = 'block';
			item.style.visibility = 'visible';
			item.style.opacity = '0';

			// Get position
			const rect = item.getBoundingClientRect();
			const elPosLeft = rect.left;
			const elPosRight = rect.right;

			// Restore original styles
			item.style.display = originalDisplay;
			item.style.visibility = originalVisibility;
			item.style.opacity = originalOpacity;

			// Apply positioning classes (with threshold for browser rounding)
			if (elPosRight > winRight - 1) {
				parentLi.classList.add('opens-left');
			} else if (elPosLeft < 1) {
				parentLi.classList.add('opens-right');
			}
		});
	}

	// Initialize mobile detection and smart submenus on DOM ready
	blogsyCheckMobileMenu();
	blogsySmartSubmenus();

	// Navigation overflow menu
	function setOverflowMenu() {
		const {
			navigation_cutoff: cutoffOption,
			navigation_cutoff_upto: cutoffUpto,
			navigation_cutoff_text: cutoffText
		} = blogsy_ajax_object;
		const responsiveBreakpoint = blogsy_ajax_object.header_breakpoint || 1025;

		const moreMenuIcon = `<span class="blogsy-svg-icon blogsy-svg-icon--ellipsis-vertical">
            <svg width="24" height="24" aria-hidden="true" role="img" focusable="false" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path fill="currentColor" d="M14.25 19.5a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm0-7.5a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm0-7.5a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z"/>
            </svg>
        </span>`;

		const createMoreMenuItem = (hasSubMenu) => {
			const className = hasSubMenu ? 'menu-item-has-children' : 'page_item_has_children';
			const submenuClass = hasSubMenu ? 'sub-menu' : 'children';
			return $(`<li class="menu-item ${className} menu-item-custom-more">
                <a href="#">${cutoffText} ${moreMenuIcon}</a>
                <ul class="${submenuClass}"></ul>
            </li>`);
		};

		function createOverflowMenu($navContainer, startIndex) {
			const $childItems = $navContainer.children(`li:nth-child(n+${startIndex + 1})`);
			let $customItem = $navContainer.find(".menu-item-custom-more");
			if (!$customItem.length && $childItems.length) {
				const hasSubMenu = $navContainer.find('.sub-menu').length > 0;
				$childItems.remove();
				$customItem = createMoreMenuItem(hasSubMenu);
				$navContainer.append($customItem);
				$customItem.find('.sub-menu, .children').append($childItems);
			}
		}

		function restoreMenuItems($customItem) {
			const $cutOffItems = $customItem.find('.sub-menu > li, .children > li');
			const $parentMenu = $customItem.closest('.blogsy-header-nav');
			$customItem.remove();
			$parentMenu.append($cutOffItems);
		}

		if (cutoffOption && cutoffUpto > 0 && $(window).width() >= responsiveBreakpoint) {
			const startIndex = cutoffUpto - 1;
			const $navContainers = $("#site-header .blogsy-header-nav-wrapper .blogsy-header-nav, #site-sticky-header .blogsy-header-nav-wrapper .blogsy-header-nav");
			$navContainers.each(function () {
				createOverflowMenu($(this), startIndex);
			});
		} else {
			const $customItems = $(".blogsy-header-nav-wrapper .blogsy-header-nav .menu-item-custom-more");
			$customItems.each(function () {
				restoreMenuItems($(this));
			});
		}

		// Re-setup menu accessibility after overflow menu changes
		setupMenuAccessibility();

		// Recalculate smart submenus after menu structure changes
		blogsySmartSubmenus();
	}

	// Run on window resize
	$(window).on('resize', blogsyDebounce(function () {
		setOverflowMenu();
		blogsyCheckMobileMenu();
		blogsySmartSubmenus();
	}, 100));

	// Run once when page loads
	$(window).on('load', function () {
		setOverflowMenu();
		blogsyCheckMobileMenu();
		blogsySmartSubmenus();
	});


	// Back to top button and scroll indicator
	const backToTopButton = document.getElementById('back-to-top');
	if (backToTopButton) {
		const activeClass = 'show';
		const offset = 200;

		// Scroll to top function
		const scrollToTop = function () {
			window.scrollTo({
				top: 0,
				behavior: 'smooth'
			});
		};

		// Get progress path elements
		const progressPath = backToTopButton.querySelector('.scroll-top-progress path');

		if (progressPath) {
			const pathLength = progressPath.getTotalLength();

			// Initialize progress circle
			progressPath.style.transition = progressPath.style.WebkitTransition = 'none';
			progressPath.style.strokeDasharray = `${pathLength} ${pathLength}`;
			progressPath.style.strokeDashoffset = pathLength;
			progressPath.getBoundingClientRect();
			progressPath.style.transition = progressPath.style.WebkitTransition = 'stroke-dashoffset 10ms linear';

			// Update progress circle
			const updateProgress = function () {
				const scroll = window.scrollY || window.pageYOffset || document.documentElement.scrollTop;
				const docHeight = Math.max(
					document.body.scrollHeight,
					document.documentElement.scrollHeight,
					document.body.offsetHeight,
					document.documentElement.offsetHeight,
					document.body.clientHeight,
					document.documentElement.clientHeight
				);
				const windowHeight = Math.max(
					document.documentElement.clientHeight,
					window.innerHeight || 0
				);
				const height = docHeight - windowHeight;
				const progress = pathLength - (scroll * pathLength / height);
				progressPath.style.strokeDashoffset = progress;
			};

			updateProgress();

			// Scroll event listener
			window.addEventListener('scroll', function () {
				updateProgress();
				const scrollPos = window.scrollY || window.pageYOffset || document.documentElement.scrollTop;

				if (scrollPos > offset) {
					backToTopButton.classList.add(activeClass);
				} else {
					backToTopButton.classList.remove(activeClass);
				}
			});
		}

		// Click event listener
		backToTopButton.addEventListener('click', scrollToTop);

		// Keyboard support (Enter/Space)
		backToTopButton.addEventListener('keydown', function (e) {
			if (isActivationKey(e)) {
				e.preventDefault();
				scrollToTop();
			}
		});
	}

	// Dark mode toggle switch
	$('body').on('click keydown', '.dark-mode-switcher', function (e) {
		if (e.type === 'click' || (e.type === 'keydown' && isActivationKey(e))) {
			if (e.type === 'keydown') e.preventDefault();

			const $html = $('html');
			const $body = $('body');
			const isDark = $html.attr('scheme') === 'dark';

			$body.addClass('noTransition');
			if (isDark) {
				$html.attr('scheme', 'light');
				setCookie('blogsyDarkMode', 'disabled', 30);
			} else {
				$html.attr('scheme', 'dark');
				setCookie('blogsyDarkMode', 'enabled', 30);
			}
			setTimeout(() => $body.removeClass('noTransition'), 200);
		}

		function setCookie(name, value, days) {
			if (navigator.cookieEnabled) {
				const date = new Date();
				date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000);
				document.cookie = `${name}=${value}; path=/; expires=${date.toUTCString()};`;
			}
		}
	});

	// Custom mouse cursor
	if ($(".blogsy-mouse-cursor").length) {
		$(document)
			.on('mousemove', e => {
				$(".blogsy-mouse-cursor").addClass('visible').css('transform', `translate(${e.clientX}px, ${e.clientY}px)`);
			})
			.on('mouseleave', () => $(".blogsy-mouse-cursor").removeClass('visible'));

		const hoverSelectors = 'a, .popup-search-opener, .offcanvas-opener, .offcanvas-close, .popup-search-close, .toc-header-collapse, .dark-mode-switcher, .comments-compact-btn, .footer-canvas-menu-btn, button, input[type="button"], input[type="reset"], input[type="submit"]';
		$('body').on('mouseenter', hoverSelectors, () => $(".blogsy-mouse-cursor").addClass('hovered'));
		$('body').on('mouseleave', hoverSelectors, () => $(".blogsy-mouse-cursor").removeClass('hovered'));
	}

	// Share link copy functionality
	$(".single-share-box-link .share-link-btn").on('click', function () {
		const $text = $(".single-share-box-link .share-link-text");
		$text.select();
		document.execCommand('copy');
		$('.single-share-box-link .copied-popup-text').addClass('show');
		setTimeout(() => $('.single-share-box-link .copied-popup-text').removeClass('show'), 2000);
	});

	// Compact comments expand button
	$('.comments-compact-btn').on('click', function () {
		$('.comments-compact-btn').remove();
		$('.comment-list-wrapper').removeClass('compact');
	});

	// Infinite scroll load more
	$(window).on('scroll', () => {
		$(".blogsy-post-load-more.infinite-scroll").each(function () {
			if (isScrolledIntoViewport($(this))) {
				$(this).trigger('click');
			}
		});
	});

	// Process each ticker wrapper independently
	$('.blogsy-news-ticker-content-wrapper').each(function () {
		const $wrapper = $(this);
		const $content = $wrapper.find('.blogsy-news-ticker-content');
		const $itemsContainer = $wrapper.find('.blogsy-news-ticker-items:not(.swiper-wrapper)');
		const $button = $wrapper.next('.blogsy-news-ticker-btn');

		// State management per ticker
		let isPlaying = true;

		// Clone the items container for seamless loop
		const $clone = $itemsContainer.clone();
		$content.append($clone);

		// Toggle play/pause
		$button.on('click keypress', function (e) {
			if (e.type === 'keypress' && e.which !== 13 && e.which !== 32) return;

			e.preventDefault();
			isPlaying = !isPlaying;

			if (isPlaying) {
				// Playing state
				$wrapper.removeClass('pause-paused');
				$wrapper.addClass('pause-hover');
				$button.find('.blogsy-svg-icon--pause').css('display', 'inline-block');
				$button.find('.blogsy-svg-icon--play').css('display', 'none');
			} else {
				// Paused state
				$wrapper.removeClass('pause-hover');
				$wrapper.addClass('pause-paused');
				$button.find('.blogsy-svg-icon--pause').css('display', 'none');
				$button.find('.blogsy-svg-icon--play').css('display', 'inline-block');
			}
		});
	});

	// Load more posts AJAX handler
	$('.blogsy-post-load-more').on('click', async function (e) {
		e.preventDefault();
		const $btn = $(this);
		if ($btn.hasClass('hide')) return;

		const $loader = $btn.next('.blogsy-post-load-more-loader');
		const $scope = $btn.closest('.blogsy-posts-container').find('.blogsy-posts-wrapper');
		const widgetId = $btn.data('widget-id');
		const postId = $btn.data('post-id');
		const nextPage = $btn.data('current-page') + 1;
		const nonces = await blogsy.getNonces();

		$.post({
			url: blogsy_ajax_object.AjaxUrl,
			data: {
				action: 'blogsy_get_load_more_posts',
				nonce: nonces.load_more_posts,
				widgetId,
				postId,
				pageNumber: nextPage,
				qVars: typeof blogsyLoadMoreQVars !== 'undefined' ? blogsyLoadMoreQVars : '',
			},
			dataType: 'json',
			beforeSend() {
				$btn.addClass('hide');
				$loader.addClass('show');
			},
			success(response) {
				if (response.data) {
					let items = $(response.data);
					$scope.append(items);
					if ($scope.hasClass('layout-masonry')) $scope.masonry('appended', items);
				}
				if (response.no_more) {
					$loader.remove();
					$btn.remove();
				} else {
					$btn.data('current-page', nextPage);
				}
			},
			complete() {
				$btn.removeClass('hide');
				$loader.removeClass('show');
			}
		});
	});

	// Prevent empty social icon clicks
	$('.blogsy-social-icons a[href=""], .blogsy-social-icons a[href="#"]').on('click', e => e.preventDefault());

	// Peony hero slider setup
	function blogsyHeroSlider(el) {
		const $sliderWrapper = el.find('.blogsy-posts-carousel-wrapper');
		if (!$sliderWrapper.length) return;

		$sliderWrapper.each(function () {
			const $currentWrapper = $(this);
			const sliderSettings = $currentWrapper.data('settings');

			const $swiperEl = $currentWrapper.find('.swiper').first();
			if (!$swiperEl.length) return;

			const $thumbsWrapper = $currentWrapper.siblings('.thumbs-slider-wrapper');

			let mainSwiper;
			let thumbsSwiper;

			if ($thumbsWrapper.length && $swiperEl.hasClass('main-slider')) {
				const thumbsSettings = $thumbsWrapper.data('settings');
				const thumbsSelector = '#' + $thumbsWrapper.attr('id') + ' .thumbs-slider';

				thumbsSwiper = new Swiper(thumbsSelector, thumbsSettings);
				mainSwiper = new Swiper($swiperEl[0], sliderSettings);

				mainSwiper.controller.control = thumbsSwiper;
				thumbsSwiper.controller.control = mainSwiper;
			} else {
				mainSwiper = new Swiper($swiperEl[0], sliderSettings);
			}
		});
	}
	blogsyHeroSlider($('#blogsy-hero'));

	// Post carousel slider
	function blogsyPymlSlider(el) {
		const $carouselPosts = el.find('.blogsy-posts-wrapper.layout-carousel .blogsy-posts-carousel-wrapper');
		if ($carouselPosts.length) {
			const carouselSettings = $carouselPosts.data('settings');
			const carouselSelector = '#' + $carouselPosts.attr('id') + ' .swiper';
			new Swiper(carouselSelector, carouselSettings);
		}
	}
	blogsyPymlSlider($('#blogsy-pyml'));

	/**
	 * Stories Popup
	 */
	const StoriesPopup = {
		container: $('.blogsy-stories'),
		activeStoryId: null,
		allStoryIds: [],
		activeStoryCount: null,
		storiesWrap: $('.blogsy-stories .blogsy-stories-box'),
		storiesPopup: $('.blogsy-stories .stories-popup .stories-popup__inner'),
		actionButtons: $('.blogsy-stories .stories-popup__actions'),
		storyArrows: $('.blogsy-stories .stories-popup__arrows'),
		swiper: null,
		secondarySwiper: null,
		swiperInstances: {},
		isPaused: false,
		outsideClickEnabled: false,
		isRtl: document.documentElement.dir === "rtl" || document.body.dir === "rtl",
		pendingTimeouts: [],
		isStoriesOpen: false,

		init: function () {
			if (!this.container.length) return;

			this.outsideClickEnabled = this.container.hasClass('outside-click--enabled');
			this.countClick();
			this.storyClick();
			this.close();
			this.pause();
			this.closePopupOnESCPress();

			$('body').css({
				'--expandWidth-timer': `${5000 / 1000}s`
			});

			let self = this;
			this.container.find('.stories-popup__slide').each(function () {
				let _this = $(this);
				let storyId = _this.data('id');
				if (storyId) {
					self.allStoryIds.push(storyId);
				}
			});
		},

		countClick: function () {
			this.container.on('click', '.stories-popup__slide .story-count', function (event) {
				event.stopPropagation();
			});
		},

		storyClick: function () {
			let self = this;
			this.container.on('click', '.blogsy-stories-box .stories-popup__slide', function (e) {
				e.preventDefault();

				let _this = $(this),
					storyId = _this.data('id'),
					count = _this.data('count');

				let max_inner_items = _this.closest('.blogsy-stories').data('max-inner-items') ?? 4;
				if (!storyId) return;

				self.activeStoryId = storyId;
				self.activeStoryCount = count;

				self.storiesPopup.parent().addClass(`term-id-${storyId}`);
				$('body').addClass('stories--open');
				self.isStoriesOpen = true;  // NEW: Mark stories as open

				if (!self.container.hasClass('added')) {
					self.ajaxCall(max_inner_items);
				} else {
					self.openStory();
				}
			});
		},

		openStory: function () {
			let self = this;

			self.initMainSwiper();
			self.storiesPopup.parent().addClass('open');

			let storyWrap = self.storiesPopup.find(`.stories-popup__story-wrap[data-id="${self.activeStoryId}"]`);
			if (storyWrap.length) {
				self.initSecondarySwiper(storyWrap);
				let img = storyWrap.find('.stories-popup__slide:first .image-wrapper img').first();
				self.addBackDrop(img);
				self.actionButtons.appendTo(storyWrap.parent());
				self.storyArrows.appendTo(storyWrap.parent());
			}

			// Navigate to active story
			if (self.swiper) {
				let slideIndex = self.swiper.slides.findIndex(slide =>
					$(slide).data('id') == self.activeStoryId
				);
				if (slideIndex !== -1) {
					self.swiper.slideTo(slideIndex, 0);
				}
			}

			self.container.addClass('added');
		},

		ajaxCall: async function (max_inner_items) {
			let self = this;
			const nonces = await blogsy.getNonces();
			$.ajax({
				method: 'POST',
				url: blogsy_ajax_object.AjaxUrl,
				data: {
					action: 'blogsy_stories_ajax_call',
					_wpnonce: nonces.story,
					storyIds: self.allStoryIds,
					count: self.activeStoryCount,
					inner_count: max_inner_items
				},
				beforeSend: function () {
					self.storiesWrap.addClass('retrieving-stories');
					self.storiesWrap.css('cursor', 'wait');
				},
				success: function (result) {
					let { success, data } = result;
					if (success && data) {
						self.storiesPopup.html(data);
						self.openStory();
						self.storiesPopup.parent().addClass('open');
					} else {
						console.log('Failed to load stories');
					}
				},
				error: function (xhr, status, error) {
					console.log('AJAX Error:', error);
				},
				complete: function () {
					self.storiesWrap.removeClass('retrieving-stories');
					self.storiesWrap.css('cursor', 'default');
				}
			});
		},

		initMainSwiper: function () {
			let self = this;

			// Destroy existing instance
			if (self.swiper) {
				self.swiper.destroy(true, true);
				self.swiper = null;
			}

			// Initialize main swiper
			self.swiper = new Swiper(self.storiesWrap.get(0), {
				slidesPerView: 1,
				effect: 'fade',
				fadeEffect: {
					crossFade: true
				},
				loop: false,
				speed: 300,
				allowTouchMove: true,
				direction: 'horizontal',
				rtl: self.isRtl,
				spaceBetween: 15,
				navigation: {
					nextEl: '.blogsy-stories .stories-popup__arrows .swiper-button-next',
					prevEl: '.blogsy-stories .stories-popup__arrows .swiper-button-prev',
					disabledClass: 'disabled'
				},
				on: {
					init: function () {
						self.updateArrowsState(this);
					},
					slideChange: function () {
						self.onMainSlideChange(this);
					}
				}
			});
		},

		onMainSlideChange: function (swiperInstance) {
			let self = this;
			self.resumeFromPause();
			let currentSlide = $(swiperInstance.slides[swiperInstance.activeIndex]);
			let newStoryId = currentSlide.data('id');
			let storyWrap = self.storiesPopup.find(`.stories-popup__story-wrap[data-id="${newStoryId}"]`);
			//let storyWrap = currentSlide.find('.stories-popup__story-wrap');

			if (!storyWrap.length) return;

			// CRITICAL FIX: Cancel pending timeouts when switching stories
			// This prevents timeouts from previous stories from firing
			self.cancelPendingTimeouts();

			// Destroy previous secondary swiper
			if (self.secondarySwiper && self.activeStoryId !== newStoryId) {
				self.secondarySwiper.destroy(true, true);
				self.secondarySwiper = null;
			}

			self.activeStoryId = newStoryId;

			// Update category class
			self.storiesPopup.parent().removeClass((index, className) => {
				return (className.match(/\bterm-id-\S+/g) || []).join(' ');
			}).addClass(`term-id-${self.activeStoryId}`);

			// Initialize secondary swiper for new slide
			self.initSecondarySwiper(storyWrap);

			// Move action buttons and arrows
			self.actionButtons.appendTo(storyWrap.parent());
			self.storyArrows.appendTo(storyWrap.parent());

			// Update ambient background
			self.addBackDrop(storyWrap.find('.stories-popup__slide:first .image-wrapper img').first());

			// Update arrows state
			self.updateArrowsState(swiperInstance);
		},

		initSecondarySwiper: function (selector) {
			let self = this;
			let $wrapper = $(selector);

			if (!$wrapper.length) return;

			let swiperContainer = $wrapper.find('.swiper').get(0);
			if (!swiperContainer) return;

			// Destroy existing secondary swiper
			if (self.secondarySwiper) {
				self.secondarySwiper.destroy(true, true);
				self.secondarySwiper = null;
			}

			// Initialize secondary swiper
			self.secondarySwiper = new Swiper(swiperContainer, {
				slidesPerView: 1,
				effect: 'fade',
				fadeEffect: {
					crossFade: true
				},
				loop: false,
				speed: 300,
				autoplay: {
					delay: 5000,
					disableOnInteraction: false,
					pauseOnMouseEnter: false
				},
				allowTouchMove: true,
				direction: 'horizontal',
				rtl: self.isRtl,
				pagination: {
					el: $wrapper.find('.swiper-pagination').get(0),
					clickable: true,
					renderBullet: function (index, className) {
						return `<span class="${className}"></span>`;
					}
				},
				navigation: {
					nextEl: $wrapper.find('.swiper-button-next').get(0),
					prevEl: $wrapper.find('.swiper-button-prev').get(0)
				},
				on: {
					init: function () {
						// Update ambient on init
						let activeSlide = $(this.slides[this.activeIndex]);
						self.addBackDrop(activeSlide.find('.image-wrapper img'));
						self.checkThumbnail(activeSlide);
					},
					slideChange: function () {
						self.onSecondarySlideChange(this);
					}
				}
			});
		},

		onSecondarySlideChange: function (swiperInstance) {
			let self = this;
			self.resumeFromPause();
			let currentIndex = swiperInstance.activeIndex;
			let slide = $(swiperInstance.slides[currentIndex]);

			// Update ambient
			self.addBackDrop(slide.find('.image-wrapper img'));

			// Check thumbnail
			self.checkThumbnail(slide);

			self.scheduleEndOfStoryAdvance(swiperInstance);
		},

		resumeFromPause: function () {
			let self = this;
			if (!self.isPaused) return;

			self.isPaused = false;
			const $pauseBtn = self.container.find('.stories-popup__button.pause');
			$pauseBtn.removeClass('paused');
			$pauseBtn.find('.blogsy-svg-icon--play').css('display', 'none');
			$pauseBtn.find('.blogsy-svg-icon--pause').css('display', 'inline-block');

			if (self.secondarySwiper && self.secondarySwiper.autoplay) {
				self.secondarySwiper.autoplay.start();
				self.scheduleEndOfStoryAdvance(self.secondarySwiper);
			}
		},

		scheduleEndOfStoryAdvance: function (swiperInstance) {
			let self = this;
			if (!swiperInstance || self.isPaused) return;

			let currentIndex = swiperInstance.activeIndex;
			let isLastSlide = currentIndex === swiperInstance.slides.length - 1;

			if (!isLastSlide) return;

			let timeoutId = setTimeout(() => {
				if (!self.isStoriesOpen || self.isPaused) {
					return;
				}

				if (
					self.swiper &&
					!self.swiper.destroyed &&
					self.swiper.slides &&
					self.swiper.slides.length > 0 &&
					!self.swiper.isEnd
				) {
					self.swiper.slideNext();
				} else {
					self.closeStories();
				}

				self.pendingTimeouts = self.pendingTimeouts.filter(id => id !== timeoutId);
			}, 4400);

			self.pendingTimeouts.push(timeoutId);
		},

		checkThumbnail: function (slide) {
			let innerStory = slide.closest('.stories-popup__story');
			if (slide.hasClass('no-thumb')) {
				innerStory.addClass('no-thumb');
			} else {
				innerStory.removeClass('no-thumb');
			}
		},

		updateArrowsState: function (swiperInstance) {
			let $prevArrow = $('.blogsy-stories .stories-popup__arrows .swiper-button-prev');
			let $nextArrow = $('.blogsy-stories .stories-popup__arrows .swiper-button-next');

			if (swiperInstance.isBeginning) {
				$prevArrow.addClass('disabled');
			} else {
				$prevArrow.removeClass('disabled');
			}

			if (swiperInstance.isEnd) {
				$nextArrow.addClass('disabled');
			} else {
				$nextArrow.removeClass('disabled');
			}
		},

		close: function () {
			let self = this;
			self.container.find('.stories-popup__button.close').on('click', function () {
				self.closeStories();
			});
		},

		closeStories: function () {
			let self = this;

			self.isStoriesOpen = false;
			self.cancelPendingTimeouts();

			$('body').removeClass('stories--open');
			self.storiesPopup.parent().removeClass('open');

			if (self.swiper && !self.swiper.destroyed && self.swiper.el) {
				self.swiper.destroy(true, true);
				self.swiper = null;
			}
			if (self.secondarySwiper && !self.secondarySwiper.destroyed && self.secondarySwiper.el) {
				self.secondarySwiper.destroy(true, true);
				self.secondarySwiper = null;
			}

			self.container.find('.stories-popup__backdrop').css({
				'background-image': ''
			});

			// Reset pause button + SVG icons
			const $pauseBtn = self.container.find('.stories-popup__button.pause');
			$pauseBtn.removeClass('paused');
			$pauseBtn.find('.blogsy-svg-icon--play').css('display', 'none');
			$pauseBtn.find('.blogsy-svg-icon--pause').css('display', 'inline-block');

			self.activeStoryId = null;
			self.activeStoryCount = null;
			self.isPaused = false;

			self.storiesPopup.parent().removeClass(function (index, className) {
				return (className.match(/\bterm-id-[^\s]+/g) || []).join(' ');
			});

			self.storiesWrap.find('.stories-popup__slide').removeAttr('style');
		},

		pause: function () {
			let self = this;
			self.container.find('.stories-popup__button.pause').on('click', function () {
				let _this = $(this);
				// Toggle paused class first
				_this.toggleClass('paused');

				self.isPaused = _this.hasClass('paused');

				// If paused, cancel any pending auto-advance timeouts immediately.
				if (self.isPaused) {
					self.cancelPendingTimeouts();
				}

				// SVG visibility toggle
				if (_this.hasClass('paused')) {
					// Paused state: show play, hide pause
					_this.find('.blogsy-svg-icon--pause').css('display', 'none');
					_this.find('.blogsy-svg-icon--play').css('display', 'inline-block');
				} else {
					// Playing state: show pause, hide play
					_this.find('.blogsy-svg-icon--play').css('display', 'none');
					_this.find('.blogsy-svg-icon--pause').css('display', 'inline-block');
				}

				// Swiper autoplay control
				if (self.secondarySwiper && self.secondarySwiper.autoplay) {
					if (_this.hasClass('paused')) {
						self.secondarySwiper.autoplay.stop();
					} else {
						self.secondarySwiper.autoplay.start();
						self.scheduleEndOfStoryAdvance(self.secondarySwiper);
					}
				}
			});
		},

		addBackDrop: function (imageTag) {
			let $img = $(imageTag);
			if ($img.length) {
				let storyImage = $img.attr('src');
				if (storyImage) {
					this.container.find('.stories-popup__backdrop').css({
						'background-image': 'url(' + storyImage + ')'
					});
				}
			} else {
				this.container.find('.stories-popup__backdrop').css({
					'background-image': 'none'
				});
			}
		},

		closePopupOnESCPress: function () {
			let self = this;
			$(document).on('keydown.StoriesPopup', function (event) {
				if (event.keyCode === 27 && $('body').hasClass('stories--open')) {
					self.closeStories();
				}
			});
		},
		// NEW: Helper method to cancel all pending timeouts
		cancelPendingTimeouts: function () {
			this.pendingTimeouts.forEach(timeoutId => {
				clearTimeout(timeoutId);
			});
			this.pendingTimeouts = [];
		}
	};

	// Initialize on DOM ready
	$(document).ready(function () {
		StoriesPopup.init();
	});

	// Expose globally
	window.blogsy = window.blogsy || {};
	window.blogsy.blogsyHeroSlider = blogsyHeroSlider;
	window.blogsy.blogsyPymlSlider = blogsyPymlSlider;

	// Get nonces.
	let blogsyNonces = null;
	let blogsyNoncePromise = null;

	window.blogsy.getNonces = async function () {

		if ( blogsyNonces ) {
		return blogsyNonces;
	}

	blogsyNoncePromise ??= fetch(
		blogsy_ajax_object.AjaxUrl + '?action=blogsy_get_nonces'
	)
		.then( response => response.json() )
		.then( json => {
			blogsyNonces = json.data;
			return blogsyNonces;
		} );

	return blogsyNoncePromise;
	}

})(jQuery);

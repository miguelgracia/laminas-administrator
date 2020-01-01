import simpleJSRoutingManager from './../simple-js-routing-manager';

function srmHomeController() {

    'use strict';

    this.indexAction = function() {
        // iPad and iPod detection
        const isiPad = function () {
            return (navigator.platform.indexOf("iPad") != -1);
        };

        const isiPhone = function () {
            return (
                (navigator.platform.indexOf("iPhone") != -1) ||
                (navigator.platform.indexOf("iPod") != -1)
            );
        };

        const goToTop = function () {

            $('#js-gotop').on('click', function (event) {
                event.preventDefault();
                $('html, body').animate({
                    scrollTop: $('html').offset().top
                }, 500);

                return false;
            });

        };

        // Page Nav
        const clickMenu = function () {

            let navbarLinks = document.querySelectorAll('#navbar a:not([class="external"])');

            [].forEach.call(navbarLinks, function (link) {
                link.addEventListener('click', function (event) {

                    let section = this.dataset.navSection;
                    let navbar = document.getElementById('navbar');

                    if ($('[data-section="' + section + '"]').length) {
                        $('html, body').animate({
                            scrollTop: $('[data-section="' + section + '"]').offset().top
                        }, 500);
                    }

                    if (navbar.is(':visible')) {
                        navbar.removeClass('in');
                        navbar.attr('aria-expanded', 'false');
                        document.getElementById('js-abstpl-nav-toggle').classList.remove('active');
                    }

                    event.preventDefault();
                    return false;
                });
            });

            $('.more-info').click(function (event) {
                event.preventDefault();
                $('a[data-nav-section="services"]').trigger('click');
            });
        };

        // Reflect scrolling in navigation
        const navActive = function (section) {

            let $el = $('#navbar > ul');
            $el.find('li').removeClass('active');
            $el.each(function () {
                $(this).find('a[data-nav-section="' + section + '"]').closest('li').addClass('active');
            });
        };

        const navigationSection = function () {

            let sections = document.querySelectorAll('section[data-section]');

            [].forEach.call(sections, function (section) {
                new Waypoint({
                    element: section,
                    handler: function (direction) {
                        if (direction === 'down') {
                            navActive(this.element.dataset.section);
                        }
                    },
                    offset: '150px'
                });

                new Waypoint({
                    element: section,
                    handler: function (direction) {
                        if (direction === 'up') {
                            navActive(this.element.dataset.section);
                        }
                    },
                    offset: function () {
                        const height = - (parseFloat(getComputedStyle(this.element, null).height.replace("px", "")));
                        return height + 155;
                    }
                });
            });
        };

        const checkScroll = function () {
            let header = document.getElementById('abstpl-header');
            let scrlTop = window.scrollY;

            if (scrlTop > 500) {
                header.classList.add('navbar-fixed-top', 'abstpl-animated',  'slideInDown');
                return;
            }

            if (scrlTop <= 500 && header.classList.contains('navbar-fixed-top')) {
                header.classList.add('navbar-fixed-top', 'abstpl-animated', 'slideOutUp');
                setTimeout(function () {
                    header.classList.remove('navbar-fixed-top', 'abstpl-animated', 'slideInDown', 'slideOutUp');
                }, 100);
            }
        };

        // Window Scroll
        const windowScroll = function () {
            window.onscroll = function () {
                checkScroll();
            };
        };

        const timeoutAnimationCallback = function (elements, cssClasses, animationType ) {
            return function () {
                [].forEach.call(elements, function (el, k) {
                    setTimeout(function () {
                        el.classList.add(...cssClasses);
                    }, k * 200, animationType);
                });
            }
        };

        // Animations
        // Home

        const homeAnimate = function () {
            let elem = document.getElementById('abstpl-home');
            if (elem === null) {
                return;
            }

            new Waypoint({
                element: elem,
                handler: function (direction) {
                    if (direction === 'down' && !this.element.classList.contains('animated')) {

                        setTimeout(timeoutAnimationCallback(
                            this.element.querySelectorAll('.to-animate'),
                            ['fadeInUp', 'animated'],
                            'easeInOutExpo'
                        ), 200);

                        this.element.classList.add('animated');
                    }
                },
                offset: '80%'
            });
        };

        const introAnimate = function () {
            let elem = document.getElementById('abstpl-intro');
            if (elem === null) {
                return;
            }

            new Waypoint({
                element: elem,
                handler: function (direction) {
                    if (direction === 'down' && !this.element.classList.contains('animated')) {
                        setTimeout(timeoutAnimationCallback(
                            this.element.querySelectorAll('.to-animate'),
                            ['fadeInRight', 'animated'],
                            'easeInOutExpo'
                        ), 1000);

                        this.element.classList.add('animated');
                    }
                },
                offset: '80%'
            });
        };

        const waypointDefaultAnimationCallback = function () {
            return function (direction) {
                if (direction === 'down' && !this.element.classList.contains('animated')) {

                    setTimeout(timeoutAnimationCallback(
                        this.element.querySelectorAll('.to-animate'),
                        ['fadeInUp', 'animated'],
                        'easeInOutExpo'
                    ), 200);

                    this.element.classList.add('animated');
                }
            };
        };

        const waypointMultipleAnimateCallback = function() {
            return function (direction) {

                if (direction === 'down' && !this.element.classList.contains('animated')) {

                    let sec = this.element.querySelectorAll('.to-animate').length;

                    sec = parseInt((sec * 200) + 400);

                    setTimeout(timeoutAnimationCallback(
                        this.element.querySelectorAll('.to-animate'),
                        ['fadeInUp', 'animated'],
                        'easeInOutExpo'
                    ), 200);

                    setTimeout(timeoutAnimationCallback(
                        this.element.querySelectorAll('.to-animate-2'),
                        ['bounceIn', 'animated'],
                        'easeInOutExpo'
                    ), sec);

                    this.element.classList.add('animated');
                }
            }
        }

        const animation = function (sectionId, isMultiple) {
            const elem = document.getElementById(sectionId);
            if (elem === null) {
                return;
            }

            new Waypoint({
                element: elem,
                handler: isMultiple ? waypointMultipleAnimateCallback() : waypointDefaultAnimationCallback(),
                offset: '80%'
            });
        };

        // Burger Menu
        const burgerMenu = function () {
            document.getElementById('js-abstpl-nav-toggle').addEventListener('click', function (event) {
                event.preventDefault();

                if (this.classList.contains('collapsed')) {
                    this.classList.remove('active');
                } else {
                    this.classList.add('active');
                }
            });
        };

        burgerMenu();
        clickMenu();
        windowScroll();
        navigationSection();
        goToTop();

        // Animations
        homeAnimate();
        introAnimate();

        [
            'abstpl-company',
            'abstpl-work',
            'abstpl-accessories',
            'abstpl-contact',
            'abstpl-technical-question'
        ].map((sectionId) => animation(sectionId, false));

        [
            'abstpl-services',
            'abstpl-counters'
        ].map((sectionId) => animation(sectionId, true));

        checkScroll();
    };
}
simpleJSRoutingManager.srmController(srmHomeController);

export default srmHomeController;
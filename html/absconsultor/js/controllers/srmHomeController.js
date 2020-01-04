import simpleJSRoutingManager from './../simple-js-routing-manager';
import animateScrollTo from 'animated-scroll-to';
import validate from 'validate.js';

function srmHomeController() {

    'use strict';

    const goToTop = function () {

        document.getElementById('js-gotop').addEventListener('click', function (event) {
            event.preventDefault();
            animateScrollTo(0);
            return false;
        });
    };

    // Page Nav
    const clickMenu = function () {

        let navbarLinks = document.querySelectorAll('#navbar a:not([class="external"])');

        [].forEach.call(navbarLinks, function (link) {
            link.addEventListener('click', function (event) {

                const section = this.dataset.navSection;
                const sectionElement = document.querySelector('[data-section="' + section + '"]');
                let navbar = document.getElementById('navbar');

                if (sectionElement !== null) {
                    var rect = sectionElement.getBoundingClientRect();

                    const offset = {
                        top: rect.top + window.scrollY,
                        left: rect.left + window.scrollX
                    };

                    animateScrollTo(offset.top);
                }

                if (navbar.offsetWidth > 0 && navbar.offsetHeight > 0) {
                    navbar.classList.remove('in');
                    navbar.setAttribute('aria-expanded', 'false');
                    document.getElementById('js-abstpl-nav-toggle').classList.remove('active');
                }

                event.preventDefault();
                return false;
            });
        });

        let moreInfoLinks = document.querySelectorAll('.more-info');

        [].forEach.call(moreInfoLinks, function (link) {
            link.addEventListener('click', function (event) {
                event.preventDefault();
                document.querySelector('a[data-nav-section="services"]').click();
            });
        });
    };

    // Reflect scrolling in navigation
    const navActive = function (section) {

        let activeLi = document.querySelector('#navbar > ul > li.active');
        if (activeLi !== null) {
            activeLi.classList.remove('active');
        }

        let selectedLink = document.querySelector('a[data-nav-section="' + section + '"]');
        selectedLink.parentNode.classList.add('active');
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

    const validation = function (formId, constraints) {

        // Hook up the form so we can prevent it from being posted

        let form = document.querySelector(formId);
        form.addEventListener("submit", function(ev) {
            ev.preventDefault();
            handleFormSubmit(form);
        });
        // Hook up the inputs to validate on the fly
        let inputs = form.querySelectorAll("input, textarea, select")
        for (let i = 0; i < inputs.length; ++i) {
            inputs.item(i).addEventListener("change", function(ev) {
                const errors = validate(form, constraints) || {};
                showErrorsForInput(this, errors[this.name])
            });
        }
        function handleFormSubmit(form, input) {
            // validate the form against the constraints
            const errors = validate(form, constraints);
            // then we update the form to reflect the results
            showErrors(form, errors || {});
            if (!errors) {
                showSuccess();
            }
        }
        // Updates the inputs with the validation errors
        function showErrors(form, errors) {
            // We loop through all the inputs and show the errors for that input
            [].forEach.call(form.querySelectorAll("input[name], select[name]"), function (input) {
                // Since the errors can be null if no errors were found we need to handle
                // that
                showErrorsForInput(input, errors && errors[input.name]);
            });
        }
        // Shows the errors for a specific input
        function showErrorsForInput(input, errors) {
            // This is the root of the input
            let formGroup = closestParent(input.parentNode, "form-group");
            // Find where the error messages will be insert into
            let messages = formGroup.querySelector(".messages");
            // First we remove any old messages and resets the classes
            resetFormGroup(formGroup);
            // If we have errors
            if (errors) {
                // we first mark the group has having errors
                formGroup.classList.add("has-error");
                // then we append all the errors
                [].forEach.call(errors, function (error) {
                    addError(messages, error);
                });
            } else {
                // otherwise we simply mark it as success
                formGroup.classList.add("has-success");
            }
        }
        // Recusively finds the closest parent that has the specified class
        function closestParent(child, className) {
            if (!child || child == document) {
                return null;
            }
            if (child.classList.contains(className)) {
                return child;
            }

            return closestParent(child.parentNode, className);
        }
        function resetFormGroup(formGroup) {
            // Remove the success and error classes
            formGroup.classList.remove("has-error");
            formGroup.classList.remove("has-success");
            // and remove any old messages
            [].forEach.call(formGroup.querySelectorAll(".help-block.error"), function (el) {
                el.parentNode.removeChild(el);
            });
        }
        // Adds the specified error with the following markup
        // <p class="help-block error">[message]</p>
        function addError(messages, error) {
            let block = document.createElement("p");
            block.classList.add("help-block");
            block.classList.add("error");
            block.innerText = error;
            messages.appendChild(block);
        }
        function showSuccess() {
            // We made it \:D/
            alert("Success!");
        }
    };

    this.indexAction = function() {

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

        validation('form#question_form', {
            question_email: {
                // Email is required
                presence: true,
                // and must be an email (duh)
                email: true
            },
            question_name: {
                presence: true,
            },
            question_customer_code: {
                presence: true,
            },
            question_topic: {
                presence: true,
            },
            question_message: {
                presence: true,
                length: {
                    minimum: 30
                }
            },
        });

        validation('form#contact_form', {
            name: {
                presence: true,
            },
            email: {
                presence: true,
                email:true
            },
            phone: {
                presence: true,
            },
            message: {
                presence: true,
                length: {
                    minimum: 30
                }
            }
        });
    };
}
simpleJSRoutingManager.srmController(srmHomeController);

export default srmHomeController;
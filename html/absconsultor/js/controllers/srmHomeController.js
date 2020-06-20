import simpleJSRoutingManager from './../simple-js-routing-manager';
import animateScrollTo from 'animated-scroll-to';
import axios from 'axios';
import validator from "validator";

function srmHomeController() {

    'use strict';

    const goToTop = function () {

        [].forEach.call(document.querySelectorAll('.gotop'), function (gotop) {
            gotop.addEventListener('click', function (event) {
                event.preventDefault();
                animateScrollTo(0);
                return false;
            });
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

    const newRecaptcha = function (form, callback) {
        const googleRecaptchaKey = document.getElementById('google-captcha-key').value;
        grecaptcha.execute(googleRecaptchaKey, {action: form.id}).then(function(token) {
            form.querySelector('input[name$="[g-recaptcha-response]"]').value=token;
            if (typeof callback !== "undefined") {
                callback();
            }
        });
    };

    const validation = function (formId, fieldset) {
        let sending = false;
        const form = document.querySelector(formId);

        const validatorMap = {
            isEmpty: {
                name: 'isEmpty',
                passing: false
            },
            emailAddressInvalidFormat: {
                name: 'isEmail',
                passing: true
            }
        };

        [].forEach.call(form.elements, function (element) {
            element.addEventListener('blur', function () {

                let formGroup = form.querySelector('div.form-group[data-name="'+this.name+'"]');
                if (formGroup === null) {
                    return;
                }

                let messagesWrapper = formGroup.querySelector('.messages');
                const value = validator.trim(this.value);
                [].forEach.call(messagesWrapper.querySelectorAll('p'), function (msg) {
                    const validatorFunction = validatorMap[msg.dataset.validator];
                    if (!validatorFunction) {
                        return;
                    }
                    const validatorResult = validator[validatorFunction.name](value);
                    if (validatorFunction.passing === validatorResult) {
                        msg.parentNode.removeChild(msg);
                    }
                });

                if (messagesWrapper.children.length == 0) {
                    formGroup.classList.remove('has-error');
                }
            });
        });

        form.addEventListener("submit", function(ev) {
            ev.preventDefault();
            if (sending) {
                return;
            }

            sending = true;
            newRecaptcha(this, function () {
                axios.post(form.action, new FormData(form)).then(function (result) {
                    alert(result.data.message);
                    let button = form.querySelector('button[type="submit"]');
                    button.parentNode.removeChild(button);
                }).catch(function (result) {

                    if (result.response.status !== 422) {
                        return;
                    }

                    const messages = result.response.data.message;

                    [].forEach.call(form.querySelectorAll('.messages'), function (msg) {
                        msg.innerHTML = '';
                    });

                    for (const element in messages) {
                        let formGroup = form.querySelector('div.form-group[data-name="'+fieldset+'['+element+']"]');
                        formGroup.classList.add('has-error');
                        let messageContainer = formGroup.querySelector(' .messages');
                        messageContainer.innerHTML = '';
                        for (let validatorType in messages[element]) {
                            const validationMessage = messages[element][validatorType];
                            let p = document.createElement('p');
                            p.classList.add('help-block','error');
                            p.dataset.validator = validatorType
                            p.innerHTML = validationMessage;
                            messageContainer.appendChild(p);
                        }
                    }
                }).finally(function () {
                    [].forEach.call(form.querySelectorAll('div.form-group'), function (formGroup) {

                        let messagesWrapper = formGroup.querySelector('.messages');

                        if (messagesWrapper !== null && messagesWrapper.children.length == 0) {
                            formGroup.classList.remove('has-error');
                        }
                    });
                    sending = false;
                });
            });
        });

        grecaptcha.ready();
    };

    const pagination = function () {
        const gallery = document.querySelectorAll('.js-show-more-gallery');

        for (let g = 0; g < gallery.length; g++) {
            gallery[g].addEventListener('click', function(event)  {
                event.preventDefault();
                axios.get(this.href).then(response => {
                    console.log(response);
                    console.log('jare');
                });
            });
        }
    };

    this.indexAction = function() {
        burgerMenu();
        clickMenu();
        windowScroll();
        navigationSection();
        goToTop();
        pagination();

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

        validation('form#question_form', 'question');
        validation('form#contact_form', 'contact');

        for (let lbInstance in fsLightboxInstances) {

            fsLightboxInstances[lbInstance].props.onInit = function () {
                setTimeout(function () {
                    let content = document.querySelector('a[data-fslightbox="' + lbInstance + '"').parentNode.querySelector('.content')

                    let div = document.createElement('div');
                    div.innerHTML = content.innerHTML;
                    div.style.position = 'fixed';
                    div.style.width = '100%';
                    div.style.color = 'white';
                    div.style.textAlign = 'center';
                    div.style.bottom = '10px';

                    document.querySelector('.fslightbox-container').appendChild(div);
                }, 1000);
            };
        }
    };
}
simpleJSRoutingManager.srmController(srmHomeController);

export default srmHomeController;
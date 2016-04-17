/**
 * =============
 * Routing
 * =============
 */
(function($){
    $.fn.simpleRouting =(function() {
        var _this = this;

        _this.oController = {};
        _this.oClassInstances = {};

        var _settings = {
                debug: true,
                route_mode: 'param', // param, object
                init_controller_name: 'data-ds-controller'
            },
            _routes,
            _oCallbacks = {
                lteController: {},
                lteClass: {}
            },
            _exceptionCallback = function(message) {
                this.message = message;
            },
            _DsCallbackUndefinedException  = _exceptionCallback,
            _DsObjExistsException          = _exceptionCallback,
            _DsInstanceControllerException = _exceptionCallback,
            _DsMethodControllerException   = _exceptionCallback,

            _instantiateClasses = function() {
                var lteClassName,lteClassInstance;

                for(lteClassName in _oCallbacks.lteClass) {
                    _this.oClassInstances[lteClassName] = {};
                    (_oCallbacks.lteClass[lteClassName]).apply(_this.oClassInstances[lteClassName],[_this]);
                    _this[lteClassName] = _this.oClassInstances[lteClassName];
                }

                for(lteClassInstance in _this.oClassInstances) {
                    var oClass = _this.oClassInstances[lteClassInstance];
                    if(typeof oClass.init !== 'undefined' && typeof oClass.init === 'function') {
                        oClass.init();
                    }
                }
            },

            _runClassesComplete = function() {
                var lteClassInstance;
                for(lteClassInstance in _this.oClassInstances) {
                    var oClass = _this.oClassInstances[lteClassInstance];
                    if(typeof oClass.complete !== 'undefined' && typeof oClass.complete === 'function') {
                        oClass.complete();
                    }
                }
            },

            _instantiateController = function(name, method) {
                if(typeof _oCallbacks.lteController[name] !== 'undefined') {
                    (_oCallbacks.lteController[name]).apply(_this.oController);
                    if(typeof _this.oController[method] == 'function') {
                        _this.oController[method].apply(_this);
                    } else {
                        throw new _DsMethodControllerException('The method controller "' + controllerMethod + '" does not exists');
                    }
                } else {
                    throw new _DsInstanceControllerException('The controller "' + controllerName + '" does not exists');
                }
            },

            _add = function (type, objName, callbackFn) {
                try {
                    if(typeof(callbackFn) !== 'undefined' && typeof(callbackFn) === 'function') {
                        if (typeof(_oCallbacks[type][objName]) === 'undefined') {
                            _oCallbacks[type][objName] = callbackFn;
                        } else {
                            throw new _DsObjExistsException(objName + '. The ' + type + ' object already exists.');
                        }
                    } else {
                        throw new _DsCallbackUndefinedException(objName + '. controller function is undefined.');
                    }
                } catch(e) {
                    _this.debug(e,'error');
                }
            };

        _this.debug = function (msg, type) {
            type = typeof type == 'string' ? type : 'log';
            if(_settings.debug) {
                if(typeof window.console === 'object') {
                    console[type](msg);
                } else {
                    alert(msg);
                }
            }
        };

        _this.lteController = function (objName, callbackFn) {
            _add('lteController', objName, callbackFn);
        };

        _this.lteClass = function (objName, callbackFn) {
            _add('lteClass', objName, callbackFn);
        };

        _this.routes = function (routes) {
            _routes = routes;
            return _this;
        };

        _this.run = function (customSettings) {

            jQuery.extend(_settings,customSettings);

            var controller,
                controllerName,
                controllerMethod,
                currentPath;

            try {
                _instantiateClasses();
                if(typeof _routes == 'object') {
                    currentPath = location.pathname;
                    var regExp;
                    for(var path in _routes) {
                        var strRegEx = path.replace('{:num}','[0-9]+').replace('{:any}','[A-z0-9_.\-\~]+');
                        regExp = new RegExp(strRegEx + '$');
                        if(regExp.test(currentPath)) {
                            if(_routes[path] instanceof Array) {
                                _instantiateController(_routes[path][0], _routes[path][1]);
                            } else if(typeof _routes[path] == 'function') {
                                _routes[path].apply(_this);
                            }
                            break;
                        }
                    }
                } else {
                    controller = document.getElementById(_settings.init_controller_name);

                    if(controller) {
                        controllerName = controller.getAttribute('value');
                        controllerMethod = controller.getAttribute('name');
                        _instantiateController(controllerName, controllerMethod);
                    } else {
                        _this.debug('This view has not controllers');
                    }
                    _runClassesComplete();
                }
            } catch (e) {
                _this.debug(e,'error');
            }
        };

        return this;
    });
})(jQuery);
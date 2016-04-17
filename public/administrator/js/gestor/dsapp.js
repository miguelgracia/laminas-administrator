var ds = ds || {};

ds = (function(){ 'use strict';
    var self = this;
    self.oController = {};
    self.oClassInstances = {};

    var __settings = {
            debug: true,
            route_mode: false,
            init_controller_name: 'data-ds-controller'
        },
        __routes,
        __oCallbacks = {
            controller: {},
            dsclass: {}
        },
        __exceptionCallback = function(message) {
            this.message = message;
        },
        __DsCallbackUndefinedException  = __exceptionCallback,
        __DsObjExistsException          = __exceptionCallback,
        __DsInstanceControllerException = __exceptionCallback,
        __DsMethodControllerException   = __exceptionCallback,

        __instantiateClasses = function() {
            var dsclassName,dsclassInstance;

            for(dsclassName in __oCallbacks.dsclass) {
                self.oClassInstances[dsclassName] = {};
                (__oCallbacks.dsclass[dsclassName]).apply(self.oClassInstances[dsclassName],[self]);
                self[dsclassName] = self.oClassInstances[dsclassName];
            }

            for(dsclassInstance in self.oClassInstances) {
                var oClass = self.oClassInstances[dsclassInstance];
                if(typeof oClass.init !== 'undefined' && typeof oClass.init === 'function') {
                    oClass.init();
                }
            }
        },

    //Este método se llama justo después de ejecutarse el controlador. Busca la función "complete" dentro de cada clase
    //y la ejecuta.

        __runClassesComplete = function() {
            var dsclassInstance;
            for(dsclassInstance in self.oClassInstances) {
                var oClass = self.oClassInstances[dsclassInstance];
                if(typeof oClass.complete !== 'undefined' && typeof oClass.complete === 'function') {
                    oClass.complete();
                }
            }
        },

        __instantiateController = function(name, method) {
            if(typeof __oCallbacks.controller[name] !== 'undefined') {
                (__oCallbacks.controller[name]).apply(self.oController);
                if(typeof self.oController[method] == 'function') {
                    self.oController[method].apply(self);
                } else {
                    throw new __DsMethodControllerException('The method controller "' + controllerMethod + '" does not exists');
                }
            } else {
                throw new __DsInstanceControllerException('The controller "' + controllerName + '" does not exists');
            }
        },

        __add = function (type, objName, callbackFn) {
            try {
                if(typeof(callbackFn) !== 'undefined' && typeof(callbackFn) === 'function') {
                    if (typeof(__oCallbacks[type][objName]) === 'undefined') {
                        __oCallbacks[type][objName] = callbackFn;
                    } else {
                        throw new __DsObjExistsException(objName + '. The ' + type + ' object already exists.');
                    }
                } else {
                    throw new __DsCallbackUndefinedException(objName + '. controller function is undefined.');
                }
            } catch(e) {
                self.debug(e,'error');
            }
        };

    self.debug = function (msg, type) {
        type = typeof type == 'string' ? type : 'log';
        if(__settings.debug) {
            if(typeof window.console === 'object') {
                console[type](msg);
            } else {
                alert(msg);
            }
        }
    };

    self.controller = function (objName, callbackFn) {
        __add('controller', objName, callbackFn);
    };

    self.dsclass = function (objName, callbackFn) {
        __add('dsclass', objName, callbackFn);
    };

    self.routes = function (routes) {
        __routes = routes;
    };

    self.run = function (customSettings) {

        jQuery.extend(__settings,customSettings);

        var controller,
            controllerName,
            controllerMethod,
            currentPath;

        try {
            __instantiateClasses();
            if(typeof __routes == 'object') {
                currentPath = location.pathname;
                var regExp;
                for(var path in __routes) {
                    regExp = new RegExp(path.replace('{:num}','[0-9]+').replace('{:any}','[A-z0-9_.\-\~]+'));
                    if(regExp.test(currentPath)) {
                        if(__routes[path] instanceof Array) {
                            __instantiateController(__routes[path][0], __routes[path][1]);
                        } else if(typeof __routes[path] == 'function') {
                            __routes[path].apply(self);
                        }
                        break;
                    }
                }
            } else {
                controller = document.getElementById(__settings.init_controller_name);

                if(controller) {
                    controllerName = controller.getAttribute('value');
                    controllerMethod = controller.getAttribute('name');
                    __instantiateController(controllerName, controllerMethod);
                } else {
                    self.debug('This view has not controllers');
                }
                __runClassesComplete();
            }
        } catch (e) {
            self.debug(e,'error');
        }
    };

    return this;
}).apply(ds);

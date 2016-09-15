var simpleJSRoutingManager = simpleJSRoutingManager || {};

(function (){ 'use strict';

    var _this = this;

    /**
     * @private _settings - Valores por defecto de los parámetros configurables
     *
     * @type {
     *  {
     *      debug:                          boolean,
     *      route_mode:                     boolean,
     *      init_controller_name:           string,
     *      defaultMethodController:        string,
     *      classMethodBeforeController:    string,
     *      classMethodAfterController:     string
     *      classPreffix:                   string
     *      classSuffix:                    string
     *      controllerPrefix:               string
     *      controllerSuffix:               string
     *   }
     * }
     *
     */
    var _settings = {

        /**
         * Activa o desactiva el modo debug
         * Si está activado, se mostrarán por consola todos los mensajes
         * que pinta la función debug()
         */
        debug: true,

        /**
         * Nombre del campo hidden insertado en la vista que nos indica el controlador
         * que debemos ejecutar. Buscará el nombre en el campo value y el dataset
         * para el método por defecto. Si no hubiese, se ejecutaría el indicado en _settings
         */

        init_controller_name: 'simple-routing-manager',
        /**
         * DefaultMethodController: Nombre del método que se ejecutará por defecto
         * para los controladores.
         */
        defaultMethodController: 'run',

        /**
         * classMethodBeforeController: Nombre del método de la clase que se ejecutará por defecto
         * cuando hagamos llamada al método run de simpleJSRoutingManager. La llamada
         * se realiza ANTES de llamar al método por defecto del controlador
         */
        classMethodBeforeController: 'beforeController',

        /**
         * classMethodAfterController: Nombre del método de la clase que se ejecutará por defecto
         * cuando hagamos llamada al método run de simpleJSRoutingManager. La llamada
         * se realiza DESPUES de llamar al método por defecto del controlador
         */

        classMethodAfterController: 'afterController',

        /**
         * Prefijos y sufijos que deberán tener los nombre de clases y controladores
         */
        srmClassPreffix:       'srm',
        srmClassSuffix:        'Class',
        srmControllerPreffix:  'srm',
        srmControllerSuffix:   'Controller'
    },
    _routes = {},
    _contructor = {
        srmClass: function Class() {
            this.srm = _this;
        },
        srmController: function Controller() {
            this.srm = _this;
        },
        reactClass: function reactClass() {

        }
    },

    _toInstance = {
        srmController: {},
        srmClass: {},
        reactClass: {}
    },
    _oInstances = {
        srmController: {},
        srmClass: {},
        reactClass: {}
    },

    _instantiateSrmClasses = function() {
        var srmClassName, srmClassInstance;
        for(srmClassName in _toInstance.srmClass) {
            var oClass = _toInstance.srmClass[srmClassName],
                defaultMethod = _settings.classMethodBeforeController;

            _contructor.srmClass.prototype = new oClass();
            _this[srmClassName] = _oInstances.srmClass[srmClassName] = srmClassInstance = new _contructor.srmClass;

            if(typeof srmClassInstance[defaultMethod] !== 'undefined' && typeof srmClassInstance[defaultMethod] === 'function') {
                srmClassInstance[defaultMethod]();
            }
        }
    },

    _instantiateController = function(nameController, method) {
        var oController;
        if(typeof _toInstance.srmController[nameController] !== 'undefined') {
            oController = _toInstance.srmController[nameController];

            _contructor.srmController.prototype = new oController();
            oController = new _contructor.srmController;

            if(typeof oController[method] == 'function') {
                oController[method]();
            } else {
                throw 'The method controller "' + method + '" does not exists';
            }
        } else {
            throw 'The controller ' + nameController + ' does not exists';
        }
    },

    /**
     * @private _runClassesAfterController
     *
     * Este método se llama justo después de ejecutarse el controlador.
     * Busca la funcién declarada en _settings.classMethodAfterController dentro de cada clase
     * y la ejecuta.
     */

    _runClassesAfterController = function() {
        var srmClassInstance;
        for(srmClassInstance in _oInstances.srmClass) {
            var oClass = _oInstances.srmClass[srmClassInstance];
            if(typeof oClass[_settings.classMethodAfterController] !== 'undefined' && typeof oClass[_settings.classMethodAfterController] === 'function') {
                oClass[_settings.classMethodAfterController]();
            }
        }
    },

    /**
     * Comprueba el nombre del objecto a instanciar va a pertenecer
     * a una clase de React
     *
     * @param objName
     * @returns {boolean}
     * @private
     */
    _isReactClass = function(objName) {
        var reactRegExp = new RegExp('^react(\\w+)$');
        return reactRegExp.test(objName);
    },

    /**
     * Comprueba el nombre del objecto a instanciar va a pertenecer
     * a un controlador o clase de SimpleRoutingManager
     *
     * @param objName
     * @returns {boolean}
     * @private
     */
    _isSrm = function(type, objName) {
        var preffix = _settings[type + 'Preffix'],
            suffix = _settings[type + 'Suffix'],

            regExp = new RegExp('^' + preffix + '(\\w+)' + suffix + '$');

        return regExp.test(objName);
    },

    /**
     * Comprueba si el nombre del controlador o clase cumple los parámetros requeridos.
     *
     * @param type
     * @param objName
     * @returns {boolean}
     * @private
     */
    _isValidNameObject = function(type, objName) {
        return  _isSrm(type,objName) || _isReactClass(objName);
    },

    /**
     * _set - Añadimos un controlador o una clase al routing manager
     *
     * @param type          srmClass | srmController
     * @param srmObject     function que debemos instanciar
     * @returns {*}         Devolvemos el objecto instanciado
     * @private
     */
    _set = function (type, srmObject) {

        var objName = srmObject.prototype.constructor.name,
            error;

        if(_isValidNameObject(type, objName)) {
            if (typeof(_toInstance[type][objName]) === 'undefined') {
                _toInstance[type][objName] = srmObject;
                return srmObject;
            }
        } else {
            error = 'Invalid name for ' + objName + '\n'
                + 'You must use "' + _settings[type + 'Preffix'] + '" preffix and "'
                + _settings[type + 'Suffix'] + '" suffix';
            throw error;
        }
    },

    _get = function(type, objName) {
        return _oInstances[type][objName];
    };

    _this.getSrmClass = function(objName) {
        return _get('srmClass',objName);
    };

    _this.getReactClass = function(objName) {
        var reactClassName = 'react' + objName;
        if(typeof _oInstances.reactClass[reactClassName] === 'undefined') {
            var oClass = new _toInstance.reactClass[reactClassName](_this.getReactClass);
             _oInstances.reactClass[reactClassName] = oClass;
        }
        return _get('reactClass',reactClassName);
    };

    /**
     * debug - encapsula las funciones console y alert. En aquellos navegadores que console
     *         no está disponible.
     * @param msg
     * @param type (log | warn | error)
     */
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

    /**
     * srmController - Encapsula una llamada a _set pasándole el primer parámetro predefinido
     * @param objName
     * @returns {*} - Devuelve una instancia de tipo controlador
     */
    _this.srmController = function (objName) {
        return _set('srmController', objName);
    };

    /**
     * srmClass - Encapsula una llamada a _set pasándole el primer parámetro predefinido
     * @param objName
     * @returns {*} - Devuelve una instancia de tipo clase
     */

    _this.srmClass = function (objName) {
        return _set('srmClass', objName);
    };

    _this.reactClass = function(objName) {
        return _set('reactClass', objName);
    };

    /**
     * routes - Nos permite crear un objeto para definir los controladores
     *          que estarán observando las rutas
     * @param routes
     * @returns {simpleJSRoutingManager}
     *
     * Ejemplo
     * simpleJSRoutingManager.routes({
     *   '/user/{:num}' : function userController() {
     *      this.run: function() {
     *          console.log('Hola desde User Controller!');
     *      }
     *   }
     *   '/blog/{:any}/{:num}': ['ControllerName','controllerMethod']
     * }).run();
     */
    _this.routes = function (routes) {
        _routes = routes || {};
        return _this;
    };

    /**
     * Antes de ejecutar, podemos sobreescribir los valores por defecto del objeto
     * settings.
     *
     * @param customSettings
     * @returns {simpleJSRoutingManager}
     */
    _this.settings = function(customSettings) {
        _settings = Object.assign(_settings, customSettings);
        return _this;
    };

    /**
     * run   - Punto inicial de ejecución de la librería
     * @param customSettings
     */
    _this.run = function (customSettings) {

        _this.settings(customSettings);

        var controller,
            controllerName,
            controllerMethod,
            controllerInstance,
            currentPath;

        try {
            _instantiateSrmClasses();
        } catch (e) {
            _this.debug(e,'error');
        }

        controller = document.getElementById(_settings.init_controller_name);

        /**
         * La declaración de un objecto enrutador prevalece
         * a la declaración específica del controlador en la vista
         */
        if(Object.keys(_routes).length > 0) {
            currentPath = location.pathname;
            var regExp;
            for(var path in _routes) {
                var strRegEx = path.replace(new RegExp('{:num}', 'g') ,'[0-9]+').replace(new RegExp('{:any}','g'),'[A-z0-9_.\-\~]+');

                regExp = new RegExp(strRegEx + '$');
                if(regExp.test(currentPath)) {

                    if(_routes[path] instanceof Array) {
                        controllerName = _routes[path][0];
                        controllerMethod = _routes[path][1] || _settings.defaultMethodController;
                    } else if(typeof _routes[path] == 'function') {
                        controllerInstance = _set('srmController',_routes[path]);
                        controllerName = controllerInstance.constructor.name;
                        controllerMethod = _settings.defaultMethodController;
                    }

                    try {
                        _instantiateController(controllerName, controllerMethod);
                    } catch (e) {
                        _this.debug(e,'error');
                    }
                    break;
                }
            }
        } else if (controller) {
            controllerName = controller.getAttribute('name');
            controllerMethod = controller.getAttribute('value');
            _instantiateController(controllerName, controllerMethod);
        } else {
            _this.debug('This view has not controllers', 'warn');
        }

        _runClassesAfterController();
    };

}).apply(simpleJSRoutingManager);

export default simpleJSRoutingManager
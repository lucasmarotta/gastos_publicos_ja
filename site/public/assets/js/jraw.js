/*
	JRAW - Jquery RAW
	Biblioteca inspirada no Jquery feita com javascript puro (RAW).
	Autor: Lucas Lara Marotta
*/
(function( global, factory ) {

	"use strict";
	factory(global);

})(window, function(window){

	"use strict";

	//Define variáveis globais e a classe JRAW
    var document = window.document,
    	arr = [],
    	push = arr.push,
    	readyBound = false,
    	type = function(value) {
    		return (typeof value);
    	},
    	DOMContentLoaded = function(){
			document.removeEventListener( "DOMContentLoaded", DOMContentLoaded, false );
			JRAW.isReady = true;
			this.call();
    	},
    	Ajax = function(request) {

    		//Atributos padrão
			var self = this,
				defaultRequest = {
				async:true,
				url:undefined,
				cache:false,
				cors: true,
				type:'GET',
				data:null,
				charset:"utf-8",
				dataType:"text",
				timeout:0,
				credentials:null,
				success:function(){},
				fail:function(){},
				done:function(){}		
			};
			this.xhr = null;

			//Inicializa as configurações do Ajax
			this.init = function(request) {
				if(type(request) == "object") {
					this.request = JRAW.extend(defaultRequest, request);
				} else {
					this.request = null;
				}
			};

			//Envia a requisição HTTP
			this.send = function () 
			{
				this.xhr = new XMLHttpRequest();
				var mimeType;
				switch(this.request.dataType)
				{
					case "text":
					{
						mimeType = "text/plain";
						break;
					}

					case "xml":
					{
						mimeType = "application/xml";
						break;
					}

					case "html":
					{
						mimeType = "text/html";
						break;
					}

					case "json":
					{
						mimeType = "application/json";
						break;
					}
				}
				mimeType += " charset="+this.request.charset;
				if(this.xhr.overrideMimeType) this.xhr.overrideMimeType(mimeType);
				if(this.request.cache)
					this.request.url += ((/\?/).test(this.request.url) ? "&" : "?") + (new Date()).getTime();
				if(this.request.credentials) 
					this.xhr.open(this.request.type, this.request.url, this.request.async, this.request.credentials.user, this.request.credentials.password);
				else 
					this.xhr.open(this.request.type, this.request.url, this.request.async);

				if(this.request.cors) {
					this.xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
					this.xhr.setRequestHeader('Access-Control-Allow-Origin', 'ipinfo.io');
				}

				this.xhr.send(this.request.data);
				if(this.request.async) {
					this.xhr.onreadystatechange = function() {
						if(self.xhr.readyState == 4) self.getResponse();
					}
				}
				else this.getResponse();
			};

			//Obtem a resposta da requisição HTTP
			this.getResponse = function() 
			{
				var response = {
					readyState:this.xhr.readyState,
					status:this.xhr.status,
					data:(this.request.dataType == "xml") ? this.xhr.responseXML:this.xhr.responseText,
					header:this.xhr.getAllResponseHeaders()
				};
				if(this.xhr.status.toString().lastIndexOf("2",0) === 0 || this.xhr.status.toString().lastIndexOf("3",0) === 0)
					this.request.success(response);
				else this.request.fail(response);
				this.request.done(response);
			};
			this.init(request);
    	},
    	JRAW = function(selector, context) {
			return new JRAW.fn.init(selector, context);
		};

	//Definição de atributos de instância
	JRAW.fn = JRAW.prototype = {
		constructor: JRAW,

		ready: function(callback) {
	        if ( readyBound ) return;
	        readyBound = true;
	        if ( document.readyState === "complete" ) {
	            return setTimeout( JRAW.ready, 1);
	        }
			document.addEventListener( "DOMContentLoaded", DOMContentLoaded.bind(callback), false );
			return this;
		},

		toArray: function() {
			return slice.call( this );
		},

		get: function(index) {
			return this;
		},

		each: function(callback) {
			return JRAW.each(this, callback);
		},

		attr: function(name, value) {
			if(value !== undefined) {
				JRAW.each(this, function(){
					if(this.setAttribute) {
						this.setAttribute(name, value);
					}
				});
				return this;
			} else {
				if(this.getAttribute) {
					return this.getAttribute(name);
				}
				return null;
			}
		}
	};

	//Método para extender o objeto JRAW
	JRAW.extend = JRAW.fn.extend = function() {
		var options, name, copy, i = 1, target = arguments[0] || {};

		if(i === arguments.length) {
			target = this;
			i--;
		}

		for( ; i < arguments.length; i++) {
			options = arguments[i];
			if ( options != null && type(options) === "object" ) {
				for ( name in options ) {
					copy = options[name];
					if(copy !== undefined) {
						target[name] = options[name];
					}
				}
			}
		}
		return target;
	};

	//Definição de atributos estáticos
	JRAW.extend({
		isReady: false,

		makeArray: function(arr, results) {
			var ret = results || [];
			push.call( ret, arr );
			return ret;
		},

		each: function(obj, callback) {
			var length, i = 0;
			length = obj.length;
			for ( ; i < length; i++ ) {
				if (callback.call(obj[i], i) === false) {
					break;
				}
			}
			return obj;
		},

		getJSON: function(request) {
			var ajax = new Ajax(request);
			if(ajax.request != null) {
				var success = ajax.request.success, fail = ajax.request.fail;
				JRAW.extend(ajax.request, {
					tyoe: 'GET',
					dataType: 'json',
					async: true,
					success: function(response) {
						var jsonData = JSON.parse(response.data);
						if(jsonData) {
							success(jsonData);
						} else {
							fail("failed to parse json");
						}

					},
					fail: function(response) {
						fail(response);
					}
				});
				ajax.send();
			}
		},

		ajax: function(request) {
			var ajax = new Ajax(request);
			if(ajax.request != null) {
				ajax.send();
			}
			return this;
		},
	});

	//Método que inicializa o JRAW
	var rootJRAW,
		init = JRAW.fn.init = function(selector, context, root) {
			if(!selector) return this;	

			root = root || rootJRAW;
			var node, objType = type(selector);
			this.length = 0;

			if(objType === "string") {
				var match = document.querySelectorAll(selector);
				if(match !== "undefined" && match.length > 0) {
					this.length = match.length;
					for (var i = 0; i < match.length; i++) {
						this[i] = match[i];
					}
					return this;
				}
				return this;
			} else if(selector.nodeType) {
				this[ 0 ] = selector;
				this.length = 1;
				return this;
			} else if(objType === "function") {
				return root.ready !== undefined ? root.ready(selector) : selector(JRAW);
			}
			return JRAW.makeArray(selector, this);
		};

	init.prototype = JRAW.fn;
	rootJRAW = JRAW(document);
	window.JRAW = window.$ = JRAW;
	return JRAW;
});

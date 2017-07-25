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

		find: function(selector) {

		}
	};

	//Método para extender o objeto JRAW
	JRAW.extend = JRAW.fn.extend = function() {
		var options, name, copy;
		options = arguments[0];
		if ( options != null && type(options) === "object" ) {
			for ( name in options ) {
				copy = options[name];
				if(copy !== undefined) {
					this[name] = options[name];
				}
			}
		}
		return this;
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

		getJson: function(request) {

		},

		ajax: function(request) {

		}
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

$(function() {
	$(".container").each(function(){
		console.log(this);
	});
});

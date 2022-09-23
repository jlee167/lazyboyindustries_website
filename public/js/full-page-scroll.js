/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 2);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/full-page-scroll.js":
/*!******************************************!*\
  !*** ./resources/js/full-page-scroll.js ***!
  \******************************************/
/*! no static exports found */
/***/ (function(module, exports) {

/************************************************************************
* Original Source:  https://github.com/amendoa/fullPageScrollPureJS

    The MIT License (MIT)

    Copyright (c) 2016 Matheus Almeida

    Permission is hereby granted, free of charge, to any person obtaining a copy
    of this software and associated documentation files (the "Software"), to deal
    in the Software without restriction, including without limitation the rights
    to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
    copies of the Software, and to permit persons to whom the Software is
    furnished to do so, subject to the following conditions:

    The above copyright notice and this permission notice shall be included in all
    copies or substantial portions of the Software.

    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
    IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
    FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
    AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
    LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
    OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
    SOFTWARE.
*************************************************************************/

/************************************************************************
* Modified by LazyBoy (lazyboyindustries.main@gmail.com)
*************************************************************************/

/**
 * Full page
 */
(function () {
  'use strict';
  /**
   * Full scroll main function
   */

  var fullScroll = function fullScroll(params) {
    /**
     * Main div
     * @type {Object}
     */
    var main = document.getElementById(params.mainElement);
    /**
     * Sections divclass
     * @type {Array}
     */

    var sections = main.getElementsByTagName('section');
    /**
     * Full page scroll configurations
     * @type {Object}
     */

    var defaults = {
      container: main,
      sections: sections,
      animateTime: params.animateTime || 0.7,
      animateFunction: params.animateFunction || 'ease',
      maxPosition: sections.length - 1,
      currentPosition: 0,
      displayDots: typeof params.displayDots != 'undefined' ? params.displayDots : true,
      dotsPosition: params.dotsPosition || 'left'
    };
    this.defaults = defaults;
    /**
     * Init build
     */

    this.init();
  };
  /**
   * Init plugin
   */


  fullScroll.prototype.init = function () {
    this.buildPublicFunctions().buildSections().buildDots().addEvents();
    var anchor = location.hash.replace('#', '').split('/')[0];
    location.hash = 0;
    this.changeCurrentPosition(anchor);
    this.registerIeTags();
  };
  /**
   * Build sections
   * @return {Object} this(fullScroll)
   */


  fullScroll.prototype.buildSections = function () {
    var sections = this.defaults.sections;

    for (var i = 0; i < sections.length; i++) {
      sections[i].setAttribute('data-index', i);
    }

    return this;
  };
  /**
   * Build dots navigation
   * @return {Object} this (fullScroll)
   */


  fullScroll.prototype.buildDots = function () {
    this.ul = document.createElement('ul');
    this.ul.className = this.updateClass(1, 'dots', this.ul.className);
    this.ul.className = this.updateClass(1, this.defaults.dotsPosition == 'right' ? 'dots-right' : 'dots-left', this.ul.className);

    var _self = this;

    var sections = this.defaults.sections;

    for (var i = 0; i < sections.length; i++) {
      var li = document.createElement('li');
      var a = document.createElement('a');
      a.setAttribute('href', '#' + i);
      li.appendChild(a);

      _self.ul.appendChild(li);
    }

    this.ul.childNodes[0].firstChild.className = this.updateClass(1, 'active', this.ul.childNodes[0].firstChild.className);

    if (this.defaults.displayDots) {
      document.body.appendChild(this.ul);
    }

    return this;
  };
  /**
   * Add Events
   * @return {Object} this(fullScroll)
   */


  fullScroll.prototype.addEvents = function () {
    if (document.addEventListener) {
      document.addEventListener('mousewheel', this.mouseWheelAndKey, false);
      document.addEventListener('wheel', this.mouseWheelAndKey, false);
      document.addEventListener('keyup', this.mouseWheelAndKey, false);
      document.addEventListener('touchstart', this.touchStart, false);
      document.addEventListener('touchend', this.touchEnd, false);
      window.addEventListener("hashchange", this.hashChange, false);
      /**
       * Enable scroll if decive don't have touch support
       */

      if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
        if (!('ontouchstart' in window)) {
          document.body.style = "overflow: scroll;";
          document.documentElement.style = "overflow: scroll;";
        }
      }
    } else {
      document.attachEvent('onmousewheel', this.mouseWheelAndKey, false);
      document.attachEvent('onkeyup', this.mouseWheelAndKey, false);
    }

    return this;
  };
  /**
   * Build public functions
   * @return {[type]} [description]
   */


  fullScroll.prototype.buildPublicFunctions = function () {
    var mTouchStart = 0;
    var mTouchEnd = 0;

    var _self = this;

    this.mouseWheelAndKey = function (event) {
      if (event.deltaY > 0 || event.keyCode == 40) {
        _self.defaults.currentPosition++;

        _self.changeCurrentPosition(_self.defaults.currentPosition);
      } else if (event.deltaY < 0 || event.keyCode == 38) {
        _self.defaults.currentPosition--;

        _self.changeCurrentPosition(_self.defaults.currentPosition);
      }

      _self.removeEvents();
    };

    this.touchStart = function (event) {
      mTouchStart = parseInt(event.changedTouches[0].clientY);
      mTouchEnd = 0;
    };

    this.touchEnd = function (event) {
      mTouchEnd = parseInt(event.changedTouches[0].clientY);

      if (mTouchEnd - mTouchStart > 100 || mTouchStart - mTouchEnd > 100) {
        if (mTouchEnd > mTouchStart) {
          _self.defaults.currentPosition--;
        } else {
          _self.defaults.currentPosition++;
        }

        _self.changeCurrentPosition(_self.defaults.currentPosition);
      }
    };

    this.hashChange = function (event) {
      if (location) {
        var anchor = location.hash.replace('#', '').split('/')[0];

        if (anchor !== "") {
          if (anchor < 0) {
            _self.changeCurrentPosition(0);
          } else if (anchor > _self.defaults.maxPosition) {
            _self.changeCurrentPosition(_self.defaults.maxPosition);
          } else {
            _self.defaults.currentPosition = anchor;

            _self.animateScroll();
          }
        }
      }
    };

    this.removeEvents = function () {
      if (document.addEventListener) {
        document.removeEventListener('mousewheel', this.mouseWheelAndKey, false);
        document.removeEventListener('wheel', this.mouseWheelAndKey, false);
        document.removeEventListener('keyup', this.mouseWheelAndKey, false);
        document.removeEventListener('touchstart', this.touchStart, false);
        document.removeEventListener('touchend', this.touchEnd, false);
      } else {
        document.detachEvent('onmousewheel', this.mouseWheelAndKey, false);
        document.detachEvent('onkeyup', this.mouseWheelAndKey, false);
      }

      setTimeout(function () {
        _self.addEvents();
      }, 600);
    };

    this.disable = function () {
      document.removeEventListener('mousewheel', this.mouseWheelAndKey, false);
      document.removeEventListener('wheel', this.mouseWheelAndKey, false);
      document.removeEventListener('keyup', this.mouseWheelAndKey, false);
      document.removeEventListener('touchstart', this.touchStart, false);
      document.removeEventListener('touchend', this.touchEnd, false);
    };

    this.animateScroll = function () {
      var animateTime = this.defaults.animateTime;
      var animateFunction = this.defaults.animateFunction;
      var position = this.defaults.currentPosition * 100;
      this.defaults.container.style.webkitTransform = 'translateY(-' + position + '%)';
      this.defaults.container.style.mozTransform = 'translateY(-' + position + '%)';
      this.defaults.container.style.msTransform = 'translateY(-' + position + '%)';
      this.defaults.container.style.transform = 'translateY(-' + position + '%)';
      this.defaults.container.style.webkitTransition = 'all ' + animateTime + 's ' + animateFunction;
      this.defaults.container.style.mozTransition = 'all ' + animateTime + 's ' + animateFunction;
      this.defaults.container.style.msTransition = 'all ' + animateTime + 's ' + animateFunction;
      this.defaults.container.style.transition = 'all ' + animateTime + 's ' + animateFunction;

      for (var i = 0; i < this.ul.childNodes.length; i++) {
        this.ul.childNodes[i].firstChild.className = this.updateClass(2, 'active', this.ul.childNodes[i].firstChild.className);

        if (i == this.defaults.currentPosition) {
          this.ul.childNodes[i].firstChild.className = this.updateClass(1, 'active', this.ul.childNodes[i].firstChild.className);
        }
      }
    };

    this.changeCurrentPosition = function (position) {
      if (position !== "") {
        _self.defaults.currentPosition = position;
        location.hash = _self.defaults.currentPosition;
      }
    };

    this.registerIeTags = function () {
      document.createElement('section');
    };

    this.updateClass = function (type, newClass, currentClass) {
      if (type == 1) {
        return currentClass += ' ' + newClass;
      } else if (type == 2) {
        return currentClass.replace(newClass, '');
      }
    };

    return this;
  };

  window.fullScroll = fullScroll;
})();

/***/ }),

/***/ 2:
/*!************************************************!*\
  !*** multi ./resources/js/full-page-scroll.js ***!
  \************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /home/admin/LazyWeb/lazyweblaravel/resources/js/full-page-scroll.js */"./resources/js/full-page-scroll.js");


/***/ })

/******/ });
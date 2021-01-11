/**
 * http.js
 * A little AJAX abstraction class for helping us deal with the API.
 *
 * @author Nathan Campos <nathan@innoveworkshop.com>
 */
"use strict";

/**
 * HTTP request object constructor.
 *
 * @param {String} url Main URL for the request.
 */
function HTTP(url) {
	this.url = url;
}

/**
 * Performs some type of HTTP request.
 * 
 * @param  {String}   type     Request type. (eg GET, POST, PUT)
 * @param  {String}   url      URL to be requested.
 * @param  {Function} callback Callback function called after the request ends.
 */
HTTP.prototype.request = function (type, url, callback) {
	var req = new XMLHttpRequest();

	req.onreadystatechange = function () {
		if (req.readyState === 4) {
			callback(req.response);
		}
	};

	req.open(type, url);
	req.send();
}

/**
 * Performs a GET request.
 * 
 * @param  {Object}   params   Request parameters or NULL if none.
 * @param  {Function} callback Callback function called after the request ends.
 */
HTTP.prototype.get = function (params, callback) {
	var url = this.url;

	// Populate parameters.
	if (params !== null) {
		var buffer = "?";

		// Go through parameters.
		for (var key of Object.keys(params)) {
			buffer += key + "=" + encodeURIComponent(params[key]);
		}

		// Append parameters to URL.
		url += buffer;
	}

	// Perform request.
	this.request("GET", url, callback);
}

/**
 * stats.js
 * Handles all the statistics stuff on the client side of things.
 *
 * @author Nathan Campos <nathan@innoveworkshop.com>
 */
"use strict";

/**
 * Statistics object constructor.
 *
 * @param JSON initialData General data about the system.
 */
function Statistics(initialData) {
	this.initialData = initialData;
}

/**
 * Populates the floor details view with a bunch of graphs.
 */
Statistics.prototype.populateFloorsDetail = function () {
	console.log(this.initialData);
}

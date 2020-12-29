/**
 * main.js
 * Just a random assortment of stuff that was needed for interactivity. Later
 * I'll probably split this into multiple files and organize them better into
 * classes and all that good stuff.
 *
 * @author Nathan Campos <nathan@innoveworkshop.com>
 */

/**
 * Updates the timespan of devices on the network for the user.
 * 
 * @param  Number  hours  How many hours of devices you want?
 * @param  Boolean submit Should we reload the page to get these devices?
 */
function timespan_update(hours, submit) {
	// Set the label text.
	var label = document.getElementById("timespan-label");
	label.innerText = "Showing devices that were on the network for the past " +
		hours + " hours";

	// Should we reload the page with the new timespan selected?
	if (submit)
		window.location = window.location.href.split('?')[0] + "?ts=" + hours
}

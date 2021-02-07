/**
 * bgtoggler.js
 * Toggles 
 *
 * @author Nathan Campos <nathan@innoveworkshop.com>
 */
"use strict";

/**
 * Loads the building background settings and sets up the page accordingly.
 */
function loadBuildingBGSettings() {
	// Check if we have a background container in the first place.
	if (document.getElementById("bg-container") === null)
		return;

	// Disable the background image if the settings say so.
	if (localStorage.getItem("bgimage") == "false")
		toggleBuildingBG();
}

/**
 * Toggles the building background image.
 */
function toggleBuildingBG() {
	// Check if we have a background container in the first place.
	var container = document.getElementById("bg-container");
	if (container === null)
		return;

	// Toggle background.
	if (container.classList.contains("building-bg")) {
		container.classList.remove("building-bg");
		localStorage.setItem("bgimage", false);
	} else {
		container.classList.add("building-bg");
		localStorage.setItem("bgimage", true);
	}
}
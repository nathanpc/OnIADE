/**
 * stats.js
 * Handles all the statistics stuff on the client side of things.
 *
 * @author Nathan Campos <nathan@innoveworkshop.com>
 */
 "use strict";

/**
 * Statistics object constructor.
 */
function Statistics() {
	this.base_url = "/api/v1/stats.php";
	this.animationDuration = 500;
}

/**
* Populates the floor details view with a bunch of graphs.
*/
Statistics.prototype.populateFloorsDetail = function () {
	var parent = this;

	this.getFloors(function (floors) {
		floors = JSON.parse(floors);
		console.log(floors);

		floors = floors.floors;
		parent.graphFloorOccupancy(floors);
	});
}

/**
* Gets information about the floors.
* 
* @param {Function} callback Callback function after we got the floors.
*/
Statistics.prototype.getFloors = function (callback) {
	var http = new HTTP(this.base_url);
	http.get({ info: "floors" }, callback);
}

/**
 * Creates a graph of how crowded each floor is.
 * 
 * @param {Object} floors List of information on each of the floors.
 */
Statistics.prototype.graphFloorOccupancy = function (floors) {
	var cls = this;
	var ctx = document.getElementById("floors-occupation").getContext("2d");
	var labels = [];

	// Setup the datasets.
	var datasets = [
		{
			type_key: null,
			label: "Total",
			data: [],
			borderWidth: 1,
			backgroundColor: [],
			borderColor: []
		},
		{
			type_key: "mobile",
			label: "Smartphones",
			data: [],
			borderWidth: 1,
			backgroundColor: [],
			borderColor: []
		},
		{
			type_key: "desktop",
			label: "Computers",
			data: [],
			borderWidth: 1,
			backgroundColor: [],
			borderColor: []
		},
		{
			type_key: "bot",
			label: "Servers",
			data: [],
			borderWidth: 1,
			backgroundColor: [],
			borderColor: []
		}
	];

	// Go through floors gathering data.
	floors.forEach(function (floor) {
		var id = floor.id;
		var name = floor.name;
		var count = 0;
		var color = null;

		// Add floor to the label and push the totals dataset while we are at it.
		labels.push(name);
		datasets[0].data.push(floor.entries.length);
		datasets[0].backgroundColor.push("#0000AA33");
		datasets[0].borderColor.push("#0000AA");

		// Count different device types.
		for (var i = 1; i < datasets.length; i++) {
			cls.countDeviceType(floor, datasets[i].type_key, datasets[i]);
		}

		// Normalize colors.
		datasets.forEach(function (dataset) {
			var color = null;

			// Try to get a valid color.
			dataset.borderColor.forEach(function (borderColor) {
				if (borderColor !== null) {
					color = borderColor;
					return;
				}
			});

			// Did we actually get anything useful?
			if (color === null) 
				color = "#000000";

			// Go back setting all the colors correctly now.
			for (var i = 0; i < dataset.borderColor.length; i++) {
				dataset.borderColor[i] = color;
				dataset.backgroundColor[i] = color + "33";
			}
		});
	});

	// Setup and graph the data.
	var chart = new Chart(ctx, {
		type: "horizontalBar",
		data: {
			labels: labels,
			datasets: datasets
		},
		options: {
			responsiveAnimationDuration: this.animationDuration,
			maintainAspectRatio: false,
			scales: {
				yAxes: [
					{
						ticks: {
							beginAtZero: true
						}
					}
				]
			}
		}
	});
	chart.canvas.parentNode.style.height = "400px";
}

/**
 * Counts devices by type for the floor occupancy chart.
 * 
 * @param  {Object} floor   A floor object.
 * @param  {String} key     Device type key.
 * @param  {Array}  dataset Dataset to put the data in.
 */
Statistics.prototype.countDeviceType = function (floor, key, dataset) {
	var count = 0;
	var color = null;

	// Go through entries.
	floor.entries.forEach(function (entry) {
		// Check if we have a device type.
		if (entry.device.type !== null) {
			// Check if it matches our search.
			if (entry.device.type.key === key) {
				// Count it up.
				count++;

				// Check if we need to set the color.
				if (color === null)
					color = entry.device.type.icon.color;
			}
		}
	});

	dataset.data.push(count);
	dataset.borderColor.push(color);
	dataset.backgroundColor.push(color + "33");
	if (color === null)
		dataset.backgroundColor.push(null);
}

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
	this.base_url = "/api/v1";
	this.stats_endpoint = "/stats.php";
	this.history_endpoint = "/history.php";
	this.animationDuration = 500;
}

/**
* Gets information about the floors.
* 
* @param {Function} callback Callback function after we got the floors.
*/
Statistics.prototype.getFloors = function (callback) {
	var http = new HTTP(this.base_url + this.stats_endpoint);
	http.get({ info: "floors" }, callback);
}

/**
* Gets the last entries into the device history.
* 
* @param {Function} callback Callback function after we got the entries.
*/
Statistics.prototype.getLastEntries = function (callback) {
	var http = new HTTP(this.base_url + this.history_endpoint);
	http.get(null, callback);
}

/**
* Populates the floor details view with a bunch of graphs.
*/
Statistics.prototype.populateFloorsDetail = function () {
	var parent = this;

	this.getFloors(function (floors) {
		floors = JSON.parse(floors);
		floors = floors.floors;

		parent.graphFloorOccupancy(floors);
	});
}

/**
* Populates the operating system details view with a bunch of graphs.
*/
Statistics.prototype.populateOSDetail = function () {
	var parent = this;

	this.getLastEntries(function (entries) {
		entries = JSON.parse(entries);
		entries = entries.entries;

		// Create graphs.
		parent.graphOperatingSystem("desktop", "bot", entries);
		parent.graphOperatingSystem("mobile", "tablet", entries);
		parent.graphOSOnlineTime("desktop", "bot", entries);
		parent.graphOSOnlineTime("mobile", "tablet", entries);
	});
}

/**
 * Creates a graph of how crowded each floor is.
 * 
 * @param {Array} floors List of information on each of the floors.
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
		// Add floor to the label and push the totals dataset while we are at it.
		labels.push(floor.name);
		datasets[0].data.push(floor.entries.length);
		datasets[0].backgroundColor.push("#0000AA33");
		datasets[0].borderColor.push("#0000AA");

		// Count different device types.
		for (var i = 1; i < datasets.length; i++) {
			cls.countDeviceType(floor, datasets[i].type_key, datasets[i]);
		}
	});

	// Normalize dataset colors.
	cls.normalizeDatasetsColors(datasets);

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
 * Creates a graph of the operating systems in use.
 *
 * @param {String} type    Device type.
 * @param {String} alttype Alternative device type that fits this as well.
 * @param {Array}  entries List of device entries.
 */
Statistics.prototype.graphOperatingSystem = function (type, alttype, entries) {
	var cls = this;
	var ctx = document.getElementById(type + "-oses").getContext("2d");
	var labels = [];

	// Setup the datasets.
	var datasets = [
		{
			label: type.charAt(0).toUpperCase() + type.slice(1),
			data: [],
			backgroundColor: [],
			borderColor: []
		}
	];

	// Go through entries trying to find OS names.
	entries.forEach(function (entry) {
		// Check if we have a device type.
		if (entry.device.type !== null) {
			// Check if we have the correct type of device.
			if (((entry.device.type.key === type) || (entry.device.type.key === alttype)) 
					&& (entry.device.os !== null)) {
				// Check if it's already in our labels array.
				var already_added = false;
				labels.forEach(function (label) {
					if (label === entry.device.os.name) {
						already_added = true;
						return;
					}
				});

				// Add new label.
				if (!already_added)
					labels.push(entry.device.os.name);
			}
		}
	});
	//labels.push("Unknown");

	// Go through labels and count the devices that match them.
	labels.forEach(function (label) {
		// Handle the unknown label.
		if (label === "Unknown")
			label = null;

		// Count devices by operating system.
		cls.countDeviceOS(entries, type, label, datasets[0]);
	});

	// Setup and graph the data.
	var chart = new Chart(ctx, {
		type: "doughnut",
		data: {
			labels: labels,
			datasets: datasets
		},
		options: {
			responsiveAnimationDuration: this.animationDuration,
			maintainAspectRatio: false,
			legend: {
				position: "top"
			},
			animation: {
				animateScale: true,
				animateRotate: true
			}
		}
	});
	chart.canvas.parentNode.style.height = "400px";
}

/**
 * Creates a graph of the online time per operating systems in use.
 *
 * @param {String} type    Device type.
 * @param {String} alttype Alternative device type that fits this as well.
 * @param {Array}  entries List of device entries.
 */
Statistics.prototype.graphOSOnlineTime = function (type, alttype, entries) {
	var cls = this;
	var ctx = document.getElementById(type + "-oses-time").getContext("2d");
	var labels = [];

	// Setup the datasets.
	var datasets = [
		{
			label: type.charAt(0).toUpperCase() + type.slice(1),
			data: [],
			backgroundColor: [],
			borderColor: []
		}
	];

	// Go through entries trying to find OS names.
	entries.forEach(function (entry) {
		// Check if we have a device type.
		if (entry.device.type !== null) {
			// Check if we have the correct type of device.
			if (((entry.device.type.key === type) || (entry.device.type.key === alttype)) 
					&& (entry.device.os !== null)) {
				// Check if it's already in our labels array.
				var already_added = false;
				labels.forEach(function (label) {
					if (label === entry.device.os.name) {
						already_added = true;
						return;
					}
				});

				// Add new label.
				if (!already_added)
					labels.push(entry.device.os.name);
			}
		}
	});
	//labels.push("Unknown");

	// Go through labels and count the devices that match them.
	labels.forEach(function (label) {
		// Handle the unknown label.
		if (label === "Unknown")
			label = null;

		// Count devices by operating system.
		cls.countDeviceOSOnlineTime(entries, type, label, datasets[0]);
	});

	// Setup and graph the data.
	var chart = new Chart(ctx, {
		type: "doughnut",
		data: {
			labels: labels,
			datasets: datasets
		},
		options: {
			responsiveAnimationDuration: this.animationDuration,
			maintainAspectRatio: false,
			legend: {
				position: "top"
			},
			animation: {
				animateScale: true,
				animateRotate: true
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

/**
 * Counts devices by operating system for the operating system charts.
 * 
 * @param  {Array}  entries An entries array.
 * @param  {String} type    Device type.
 * @param  {String} name    Operating system name or NULL for the unknown.
 * @param  {Array}  dataset Dataset to put the data in.
 */
Statistics.prototype.countDeviceOS = function (entries, type, name, dataset) {
	var count = 0;
	var color = null;

	// Go through entries.
	entries.forEach(function (entry) {
		// Check if we are searching for unknowns.
		if (name === null) {
			if (entry.device.type != null) {
				if ((entry.device.type.key === type) && (entry.device.os === null)) {
					count++;
				}
			}

			color = "#000000";
			return;
		}

		// Check if we have an operating system.
		if (entry.device.os !== null) {
			// Check if it matches our search.
			if (entry.device.os.name === name) {
				// Count it up.
				count++;

				// Check if we need to set the color.
				if (color === null)
					color = entry.device.os.icon.color;
			}
		}
	});

	dataset.data.push(count);
	dataset.borderColor.push(color);
	dataset.backgroundColor.push(color + "33");
	if (color === null)
		dataset.backgroundColor.push(null);
}

/**
 * Counts devices by operating system for the operating system charts.
 * 
 * @param  {Array}  entries An entries array.
 * @param  {String} type    Device type.
 * @param  {String} name    Operating system name or NULL for the unknown.
 * @param  {Array}  dataset Dataset to put the data in.
 */
Statistics.prototype.countDeviceOSOnlineTime = function (entries, type, name, dataset) {
	var count = 0;
	var color = null;

	// Go through entries.
	entries.forEach(function (entry) {
		// Check if we are searching for unknowns.
		if (name === null) {
			if (entry.device.type != null) {
				if ((entry.device.type.key === type) && (entry.device.os === null)) {
					count += entry.device.time_online.today;
				}
			}

			color = "#000000";
			return;
		}

		// Check if we have an operating system.
		if (entry.device.os !== null) {
			// Check if it matches our search.
			if (entry.device.os.name === name) {
				// Count it up.
				count += entry.device.time_online.today;

				// Check if we need to set the color.
				if (color === null)
					color = entry.device.os.icon.color;
			}
		}
	});

	dataset.data.push(count);
	dataset.borderColor.push(color);
	dataset.backgroundColor.push(color + "33");
	if (color === null)
		dataset.backgroundColor.push(null);
}

/**
 * Normalizes the colors of the datasets.
 * 
 * @param  {Array} datasets An array of datasets to have their colors normalized.
 */
Statistics.prototype.normalizeDatasetsColors = function (datasets) {
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
}

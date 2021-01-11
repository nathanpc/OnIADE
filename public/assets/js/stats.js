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
	var ctx = document.getElementById("floors-occupation").getContext("2d");
	var labels = [];
	var data = [];

	// Go through floors.
	floors.forEach(function (floor) {
		labels.push(floor.name);
		data.push(floor.entries.length);
	});

	// Setup and graph the data.
	var chart = new Chart(ctx, {
		type: "horizontalBar",
		data: {
			labels: labels,
			datasets: [
				{
					label: "Total",
					data: data,
					borderWidth: 1
				}
			],
		},
		options: {
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
}

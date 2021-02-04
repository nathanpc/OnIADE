<?php
/**
 * DateTimeUtil.php
 * Collection of utilities to use with DateTime objects.
 * 
 * @author Nathan Campos <nathan@innoveworkshop.com>
 */

namespace OnIADE\Utilities;
require __DIR__ . "/../../vendor/autoload.php";
use DateTime;

class DateTimeUtil {
	/**
	 * Gets a MySQL string representation of a DateTime object.
	 * 
	 * @param  DateTime $datetime Object to be converted to string.
	 * @return string             MySQL DATETIME string.
	 */
	public static function mysql_format($datetime) {
		return $datetime->format("Y-m-d H:i:s");
	}

	/**
	 * Creates a nice human-readable "X minutes ago"-type string.
	 * @link https://stackoverflow.com/a/18602474/126353 Original Author
	 * 
	 * @param  DateTime $datetime Date and time to compare to now.
	 * @param  boolean  $full     Show a very detailed string down to the seconds?
	 * @return string             Human-readable time passed string.
	 */
	public static function since_string($datetime, $full = false) {
		// Get the difference and normalize the data.
		$now = new DateTime;
		$diff = $now->diff($datetime);
		$diff->w = floor($diff->d / 7);
		$diff->d -= $diff->w * 7;

		// Key to human-readable string lookup table.
		$string = array(
			"y" => "year",
			"m" => "month",
			"w" => "week",
			"d" => "day",
			"h" => "hour",
			"i" => "minute",
			"s" => "second",
		);

		// Go through and create a human-readable string.
		foreach ($string as $k => &$v) {
			if ($diff->$k) {
				$v = $diff->$k . " " . $v . ($diff->$k > 1 ? "s" : "");
			} else {
				unset($string[$k]);
			}
		}

		// Should we just give the user the whole massive string?
		if (!$full)
			$string = array_slice($string, 0, 1);

		return $string ? implode(", ", $string) . " ago" : "just now";
	}
}

?>

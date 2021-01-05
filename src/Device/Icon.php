<?php
/**
 * Icon.php
 * Abstraction class for a Font Awesome icon.
 *
 * @author Nathan Campos <nathan@innoveworkshop.com>
 */

namespace OnIADE\Device;
require __DIR__ . "/../../vendor/autoload.php";
use PDO;

class Icon {
	private $name;
	private $color;

	/**
	 * Device icon object constructor.
	 * 
	 * @param string $name  Icon name.
	 * @param string $color Icon color.
	 */
	public function __construct($name = null, $color = null) {
		$this->name = $name;
		$this->color = $color;
	}

	/**
	 * Gets the icon name.
	 * 
	 * @return string Icon name.
	 */
	public function get_name() {
		if (is_null($this->name))
			return "fas fa-times";

		return $this->name;
	}

	/**
	 * Sets the icon name.
	 * 
	 * @param string $name Icon name.
	 */
	public function set_name($name) {
		$this->name = $name;
	}

	/**
	 * Gets the icon color.
	 * 
	 * @return string Icon color.
	 */
	public function get_color() {
		if (is_null($this->color))
			return "#636464";

		return $this->color;
	}

	/**
	 * Sets the icon color.
	 * 
	 * @param string $color Icon color.
	 */
	public function set_color($color) {
		$this->color = $color;
	}

	/**
	 * Gets the Font Awesome tag for this icon.
	 * 
	 * @param  boolean $with_color Should we have the color added?
	 * @return string              Font Awesome tag with color if asked for.
	 */
	public function as_tag($with_color = true) {
		$html = "";

		if ($with_color)
			$html .= "<span style=\"color: " . $this->get_color() . "\">";

		$html .= "<i class=\"" . $this->get_name() . "\"></i>";

		if ($with_color)
			$html .= "</span>";

		return $html;
	}

	/**
	 * Array representation of this object. Perfect for use in JSON responses.
	 * 
	 * @return array Array representation of this object.
	 */
	public function as_array() {
		return array(
			"name" => $this->name,
			"color" => $this->color
		);
	}
}

?>

<?php
/**
 * UploadHandler.php
 * A little helper class to deal with file uploads.
 * 
 * @author Nathan Campos <nathan@innoveworkshop.com>
 */

namespace OnIADE\Utilities;
require __DIR__ . "/../../vendor/autoload.php";

class UploadHandler {
	private $uploaddir;
	private $file;

	/**
	 * Creates a new file upload handler object.
	 * 
	 * @param assoc  $file      $_FILES file entry.
	 * @param string $uploaddir Directory inside of the webroot where uploads
	 *                          will be placed.
	 */
	public function __construct($file, $uploaddir = "uploads") {
		$this->file = $file;
		$this->uploaddir = __DIR__ . "/../../public/$uploaddir/";
	}

	/**
	 * Moves the uploaded file to a path relative to the server's public folder.
	 * 
	 * @param  string  $webpath Full path to the filename relative to the server's
	 *                          public folder.
	 * @return boolean          TRUE if everything went fine.
	 */
	public function move($webpath) {
		// Check for errors.
		if ($this->file["error"] !== UPLOAD_ERR_OK)
			return false;

		return move_uploaded_file($this->file["tmp_name"],
			__DIR__ . "/../../public" . $webpath);
	}

	/**
	 * Saves the file to the usual uploads place.
	 * 
	 * @param  string  $subdir Sub-directory to place the uploaded file inside
	 *                         the uploads directory.
	 * @param  string  $name   New file name. IMPORTANT: An extension will be
	 *                         appended to this variable.
	 * @return boolean         TRUE if everything went fine.
	 */
	public function save($subdir = null, $name = null) {
		$path = $this->uploaddir;

		// Check for errors.
		if ($this->file["error"] !== UPLOAD_ERR_OK)
			return false;

		// Append subdirectory to path.
		if (!is_null($subdir))
			$path .= "$subdir/";

		// Append name and extension.
		if (!is_null($name)) {
			$path .= $name;
			$path .= $this->get_extension();
		} else {
			$path .= $this->get_basename();
		}

		// Move file and return its result.
		return move_uploaded_file($this->file["tmp_name"], $path);
	}

	/**
	 * Checks if the uploaded file is an image.
	 * 
	 * @return boolean TRUE if the uploaded file is an image.
	 */
	public function is_image() {
		return getimagesize($this->file["tmp_name"]) !== false;
	}

	/**
	 * Gets the original name of the file uploaded.
	 * 
	 * @return string Original's file basename.
	 */
	private function get_basename() {
		return basename($this->file["name"]);
	}

	/**
	 * Gets the extension of the uploaded filename.
	 * 
	 * @return string Uploaded file extension.
	 */
	private function get_extension() {
		return pathinfo($this->file["name"], PATHINFO_EXTENSION);
	}
}

?>

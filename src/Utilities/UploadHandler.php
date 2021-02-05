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
	const WEBROOT = __DIR__ . "/../../public";

	private $uploaddir;
	private $file;

	/**
	 * Creates a new file upload handler object.
	 * 
	 * @param assoc  $file      $_FILES file entry.
	 * @param string $uploaddir Directory inside of the webroot where uploads
	 *                          will be placed.
	 */
	public function __construct($file = null, $uploaddir = "/uploads") {
		$this->file = $file;
		$this->uploaddir = UploadHandler::WEBROOT . $uploaddir;
	}

	/**
	 * Gets the uploaded file path.
	 * 
	 * @param  string  $fname      File name to be checked. Extension must be
	 *                             omitted if $ignore_ext is TRUE.
	 * @param  boolean $ignore_ext Should we ignore the file extensions and
	 *                             focus only in the actual name?
	 * @param  boolean $web_rel    Should the returned path be relative to the
	 *                             web public root?
	 * @return string              Path to the uploaded file or NULL if one
	 *                             wasn't found.
	 */
	public function get_path($fname, $ignore_ext = false, $web_rel = false) {
		$path = $this->uploaddir . "/$fname";

		// Add globbing if we need to ignore the file extension.
		if ($ignore_ext)
			$path .= ".*";

		// Grab the first file found.
		foreach (glob($path) as $fpath) {
			if ($web_rel) {
				return substr($fpath, strlen(UploadHandler::WEBROOT));
			} else {
				return $fpath;
			}
		}

		// Fail if we didn't have any hits.
		return null;
	}

	/**
	 * Checks if the uploaded file exists in the uploads directory.
	 * 
	 * @param  string  $fname      File name to be checked. Extension must be
	 *                             omitted if $ignore_ext is TRUE.
	 * @param  boolean $ignore_ext Should we ignore the file extensions and
	 *                             focus only in the actual name?
	 * @return boolean             TRUE if the file exists.
	 */
	public function exists($fname, $ignore_ext = false) {
		return !is_null($this->get_path($fname, $ignore_ext));
	}	

	/**
	 * Saves the file to the usual uploads place.
	 * 
	 * @param  string  $name New file name. IMPORTANT: An extension will be
	 *                       appended to this variable.
	 * @return boolean       TRUE if everything went fine.
	 */
	public function save($name) {
		$path = $this->uploaddir . "/";

		// Check if we have a file defined.
		$this->is_file_defined();

		// Check if we had something uploaded.
		if (!$this->was_uploaded())
			return false;

		// Check for errors.
		if ($this->file["error"] !== UPLOAD_ERR_OK)
			return false;

		// Append name and extension.
		if (!is_null($name)) {
			$path .= $name;
			$path .= "." . $this->get_extension();
		} else {
			$path .= $this->get_basename();
		}

		// Move file and return its result.
		return move_uploaded_file($this->file["tmp_name"], $path);
	}

	/**
	 * Checks if we actually had something uploaded.
	 * 
	 * @return boolean Was something actually uploaded?
	 */
	public function was_uploaded() {
		// Check if we have a file defined.
		$this->is_file_defined();

		return file_exists($this->file["tmp_name"]) &&
			is_uploaded_file($this->file["tmp_name"]);
	}

	/**
	 * Checks if the uploaded file is an image.
	 * 
	 * @return boolean TRUE if the uploaded file is an image.
	 */
	public function is_image() {
		// Check if we have a file defined.
		$this->is_file_defined();

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

	/**
	 * Checks if we have a file defined.
	 * 
	 * @param  boolean $just_die Should we just die if the file isn't defined?
	 * @return boolean           Do we have a file defined?
	 */
	private function is_file_defined($just_die = true) {
		if ($just_die) {
			if (is_null($this->file))
				die("No file specified.");
		}

		return is_null($this->file);
	}
}

?>

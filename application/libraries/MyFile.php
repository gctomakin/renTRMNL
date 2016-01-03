<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class MyFile {
	// Properties
	private $maxSize;
	private $minSize;
	private $types;
	private $file;
	private $imgMaxWidth;
	private $imgMaxHeight;
	private $path;

	// Functions
	public function __construct($file = "") {
		$this->file = $file;
		$this->ini();
	}

	/**
	 * Initialize properties for their default values
	 */
	public function ini() {
		$this->maxSize = 500000; // Default Max size
		$this->minSize = 100; // Default Min Size
		$this->imgMaxWidth = 250; // Default Image Max Width
		$this->imgMaxHeight = 250; // Default Image Max Height
		$this->types = array(
			'image/gif',
			'image/png',
			'image/jpeg',
			'image/pjpeg',
			'text/plain'
		);
		$this->path = 'public';
	}

	public function validateImage() {
		$img = getimagesize($this->file['tmp_name']);
		if (!$img) {
		   $res['message'][] = 'File is not an image.';
		   $res['result'] = false;
		} else {
			$res = $this->commonValidate();
		}
		return $res;
	}

	public function commonValidate() {
		$res['result'] = false;
		
		if ($this->file['size'] > $this->maxSize) {
			$res['message'][] = "File size exceeds the maximum size of {$this->maxSize}";
		}
		if ($this->file['size'] < $this->minSize) {
			$res['message'][] = "File size must above minimum size of {$this->minSize}";
		}
		if (!in_array($this->file["type"], $this->types)) {
			$res['message'][] = "File does not exist in default types in MyFile Class";
		}

		if (empty($res['message'])) {
			$res['result'] = true;
		}

		return $res;
	}

	public function isText() {
		if ($this->file["type"] != 'text/plain') {
			$res['result'] = false;
			$res['message'][] = "File is not plain text.";
		} else {
			$res = $this->commonValidate();		
		}
		return $res;
	}

	/**
	 * Upload a file with name and specified folder
	 * @param  String $name   Complete name of the file
	 * @param  String $folder Folder where the file will be uploaded
	 * @return Boolean        Returns true if successfully file uploaded
	 */
	public function upload($name, $folder) {
		$targetFile = $this->path . $folder . $name;
		return move_uploaded_file($this->file['tmp_name'], $targetFile);
	}

	/**
	 * Checks file directory if exist
	 * @param  String  $targetFile Full path of file
	 * @return boolean       Returns true if exist otherwise false
	 */
	public function isExist($targetFile) {
		return file_exists($this->path . $targetFile);
	}

	/**
	 * Delete file target
	 * @param  String $targetFile Full path of file
	 * @return Boolean            Returns successfully deleted or an error occured
	 */
	public function delete($targetFile, $folder) {
		if (!empty($targetFile) && $this->isExist($folder . $targetFile)) {
			return unlink($this->path . $folder . $targetFile);
		} else {
			return false;
		}
	}

	public function generateName($keyword) {
		$time = round(microtime(true) * 1000);
		return $keyword . $time;		
	}


	// SETTERS
	public function setMaxSize($size) {
		if (is_int($size)) {
			$this->maxSize = $size;
		} else {
			exit("Max size must be integer");
		}
	}
	public function setMinSize($size) {
		if (is_int($size)) {
			$this->minSize = $size;
		} else {
			exit("Max size must be integer");
		}
	}
	public function setPath($path) {
		$this->path = $path;
	}

	// GETTERS
	public function getPath($path) { return $this->path; }
}
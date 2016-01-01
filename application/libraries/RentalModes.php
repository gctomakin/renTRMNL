<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class RentalModes {

	private $modes = array(
		1 => "Hourly",
		2 => "Daily",
		3 => "Weekly",
		4 => "Monthly",
		5 => "Yearly"
	);

	public function getMode($mode) {
		if (array_key_exists($mode, $this->modes)) {
			return $this->modes[$mode];
		} else {
			return "Not found mode";
		}
	}

	public function getModes() {
		return $this->modes;
	}
}
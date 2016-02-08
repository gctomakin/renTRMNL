<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class RentalModes {

	private $modes = array(
		1 => "Daily",
		7 => "Weekly",
		30 => "Monthly",
		365 => "Yearly"
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
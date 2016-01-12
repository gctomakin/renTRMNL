<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class MyDateTime {

	private $startDate;
	private $endDate;
	private $difference;

	public function __construct($date) {
		$this->startDate = date('Y-m-d', strtotime($date[0]));
		$this->endDate = date('Y-m-d', strtotime($date[1]));
		$this->difference();
	}

	public function difference() {
		$from = new DateTime($this->startDate);
		$to = new DateTime($this->endDate);
		$this->difference = $from->diff($to);
	}

	public function getInterval() {
		$days = $this->difference->days;
		if ($days <= 0) {
			return $this->_getHourInterval();
		} else {
			return $this->_getDayInterval($days);
		}
	}

	private function _getHourInterval() {
		$totalHour = 23;
		$interval = round($totalHour / 3);
		$startHour = 0;
		$data = array();
		while ($startHour <= $totalHour) {
			$time = str_pad($startHour, 2, '0', STR_PAD_LEFT) . ':00:00';
			array_push($data, $this->startDate . ' ' . $time);
			$startHour += $interval;
		}
		array_push($data, $this->startDate . ' 23:59:59');
		return array(
			'date' => $data,
			'interval' => $interval,
			'type' => 'hours',
			'difference' => $totalHour
		);
	}

	private function _getDayInterval($days) {
		$range = round($days / 5);
		$interval = $range > 0 ? $range : 1;
		$from = $this->startDate;
		$data = array();
		while (strtotime($from) < strtotime($this->endDate)) {
			array_push($data, $from);
			$from = date('Y-m-d H:i:s', strtotime('+' . $interval . ' day', strtotime($from)));
		}
		array_push($data, date('Y-m-d H:i:s', strtotime($this->endDate)));
		return array(
			'date' => $data,
			'interval' => $interval,
			'type' => 'days',
			'difference' => $this->difference->days
		);
	}

	public function getStartDate() { return $this->startDate; }
	public function getEndDate() { return $this->endDate; }
	public function setStartDate($date) { $this->startDate = $date; }
	public function setEndDate($date) { $this->endDate = $date; }

	public function getDifference() { return $this->difference; }
}
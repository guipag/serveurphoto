<?php

namespace LT\PhotosBundle\Services;

class LTScolardate extends \Twig_Extension {

    public function getScolardate(\Datetime $date) {
	$firstYear = $date->format('Y') + floor($date->format('m') / 8) - 1;
        $lastYear = $date->format('Y') + floor($date->format('m') / 8);

	return array('begin' => $firstYear, 'end' => $lastYear);
    }

    public function getTextScolardate(\Datetime $date) {
	$years = $this->getScolardate($date);
	return strval($years['begin']) . '-' . strval($years['end']);
    }

    public function getActualScolarYear() {
	return $this->getTextScolardate(new \DateTime());
    }

    public function getFunctions() {
	return array(
		    new \Twig_SimpleFunction('scolarYear', array($this, 'getTextscolarDate')),
		    new \Twig_SimpleFunction('actualScolarYear', array($this, 'getActualScolarYear'))
		);
    }
    public function getName() {
	return 'LTScolardate';
    }
}

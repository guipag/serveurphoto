<?php

namespace LT\PhotosBundle\Model;

use Symfony\Component\HttpFoundation\Request;

class EventSearch {
  protected $name;
  protected $slug;
  protected $dateFrom;
  protected $dateTo;

  public function __construct() {
    $date = new \DateTime();
    $month = new \DateInterval('P1Y');
    $date->sub($month);
    $date->setTime('00','00','00');

    $this->dateFrom = $date;
    $this->dateTo = new \DateTime();
    $this->dateTo->setTime('23','59','59');
  }

  public function setDateFrom($dateFrom) {
    if ($dateFrom != "")
      $this->dateFrom = $dateFrom;

    return $this;
  }

  public function getDateFrom() {
    return $this->dateFrom;
  }

  public function setDateTo($dateTo) {
    if ($dateTo != "")
      $this->dateTo = $dateTo;

    return $this;
  }

  public function getDateTo() {
    return $this->dateTo;
  }

  public function clearDates() {
    $this->dateFrom = null;
    $this->dateTo = null;

    return $this;
  }

  public function setName($title) {
    $this->name = $name;

    return $this;
  }

  public function getName() {
    return $this->name;
  }

  public function setSlug($slug) {
    $this->slug = $slug;

    return $this;
  }

  public function getSlug() {
    return $this->slug;
  }
}

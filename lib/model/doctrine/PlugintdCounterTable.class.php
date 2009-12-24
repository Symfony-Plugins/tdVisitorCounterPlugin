<?php
/**
 */
class PlugintdCounterTable extends Doctrine_Table
{
 /**
  * Saves a single visitor counter entry.
  */
  static public function mark()
  {
    $mark = new tdCounter();
    $mark->ref_ip = $_SERVER['REMOTE_ADDR'];
    $mark->agent = $_SERVER['HTTP_USER_AGENT'];
    $mark->save();
  }

 /**
  * Retrieves number of all visits.
  *
  * @return integer number of visits
  */
  static public function getCount()
  {
    $result = Doctrine_Query::create()
      ->select('COUNT(c.id)')
      ->from('tdCounter c')
      ->fetchArray();
    return $result[0]['COUNT'];
  }

 /**
  * Returns query retrieving all visits during last days (number of days given
  * by the parameter).
  *
  * @param integer $days number of days for the stats chart.
  * @return Doctrine_Query query retrieving all visits during last days.
  */
  static public function getLastDaysCounts($days) {
    return Doctrine_Query::create()
      ->select('COUNT(c.id) AS count, DATE_FORMAT(c.created_at, \'%Y-%m-%d\') AS date')
      ->from('tdCounter c')
      ->where('c.created_at > CONCAT(DATE_FORMAT(DATE_SUB(CURRENT_TIMESTAMP, INTERVAL '.$days.' DAY),\'%Y-%m\'), \'-00\')')
      ->andWhere('c.created_at < CURRENT_TIMESTAMP')
      ->groupBy('DATE_FORMAT(c.created_at, \'%Y-%m-%d\')');
  }

 /**
  * Returns query retrieving all visits during last months (number of days given
  * by the parameter).
  *
  * @param integer $months number of months for the stats chart.
  * @return Doctrine_Query query retrieving all visits during last months.
  */
  static public function getLastMonthsCounts($months) {
    return Doctrine_Query::create()
      ->select('COUNT(c.id) AS count, DATE_FORMAT(c.created_at, \'%Y-%m\') AS date')
      ->from('tdCounter c')
      ->where('c.created_at > CONCAT(DATE_FORMAT(DATE_SUB(CURRENT_TIMESTAMP, INTERVAL '.$months.' MONTH),\'%Y-%m\'), \'-00\')')
      ->andWhere('c.created_at < CURRENT_TIMESTAMP')
      ->groupBy('DATE_FORMAT(c.created_at, \'%Y-%m\')');
  }
}
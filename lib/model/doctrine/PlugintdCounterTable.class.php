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

  static public function getLastDaysCounts($days) {
    return Doctrine_Query::create()
      ->select('COUNT(c.id) AS count, DATE_FORMAT(c.created_at, \'%Y-%m-%d\') AS date')
      ->from('tdCounter c')
      ->where('c.created_at > CONCAT(DATE_FORMAT(DATE_SUB(CURRENT_TIMESTAMP, INTERVAL '.$days.' DAY),\'%Y-%m\'), \'-00\')')
      ->andWhere('c.created_at < CURRENT_TIMESTAMP')
      ->groupBy('DATE_FORMAT(c.created_at, \'%Y-%m-%d\')');
  }

  static public function getLastMonthsCounts($months) {
    return Doctrine_Query::create()
      ->select('COUNT(c.id) AS count, DATE_FORMAT(c.created_at, \'%Y-%m\') AS date')
      ->from('tdCounter c')
      ->where('c.created_at > CONCAT(DATE_FORMAT(DATE_SUB(CURRENT_TIMESTAMP, INTERVAL '.$months.' MONTH),\'%Y-%m\'), \'-00\')')
      ->andWhere('c.created_at < CURRENT_TIMESTAMP')
      ->groupBy('DATE_FORMAT(c.created_at, \'%Y-%m\')');
  }
}
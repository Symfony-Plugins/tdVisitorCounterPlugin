<?php
/**
 * tdVisitorManager.class
 */

/**
 * tdVisitorManager
 *
 * @author Tomasz Ducin <tomasz.ducin@gmail.com>
 * @package symfony
 * @subpackage tdVisitorCounterPlugin
 */

class tdVisitorManager
{
  /**
   * Mark a visitor: check if the visitor's browser already has a cookie set,
   * if not - insert a database record.
   */
  static public function markVisitor()
  {
    if (!isset($_COOKIE['counter']))
    {
      setcookie('counter', 'visited', time() + sfConfig::get('td_visitor_counter_cookie_interval'));
      tdCounterTable::mark();
    }
  }
}
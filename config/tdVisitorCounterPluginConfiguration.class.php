<?php
/**
 * tdVisitorCounterPluginConfiguration.class
 */

/**
 * tdVisitorCounterPluginConfiguration
 *
 * @author Tomasz Ducin <tomasz.ducin@gmail.com>
 * @package symfony
 * @subpackage tdVisitorCounterPlugin
 */

class tdVisitorCounterPluginConfiguration extends sfPluginConfiguration
{
  /**
   * Initialize
   */
  public function initialize()
  {
    // number of months for visitor counter statistics
    sfConfig::set('td_visitor_counter_months', 8);

    // number of days for visitor counter statistics
    sfConfig::set('td_visitor_counter_days', 15);

    // number of days for visitor counter statistics
    sfConfig::set('td_visitor_counter_cookie_interval', 180);
  }
}
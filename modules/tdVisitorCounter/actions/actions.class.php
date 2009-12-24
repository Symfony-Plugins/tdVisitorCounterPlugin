<?php
/**
 * tdVisitorCounter actions.
 *
 * @package    tdVisitorCounterPlugin
 * @class      tdVisitorCounterActions
 * @author     Tomasz Ducin
 * @version    SVN: $Id: actions.class.php
 */
class tdVisitorCounterActions extends sfActions
{
 /**
  * Index action shows two charts: visitors count calculated daily and monthly.
  *
  * @param sfRequest A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
  }

  private function getMax($max)
  {
    $length = strlen($max);
    if ($length == 1)
    {
      return 10;
    }
    else
    {
      $result = '1'.str_repeat('0', $length);
      $diff = $result / 5;
      while ($result - $diff > $max)
      {
        $result -= $diff;
      }
      return $result;
    }
  }

 /**
  * Outputs Open-Flash-Chart formatted data for a chart used within index action.
  *
  * @return sfView::NONE
  */
  public function executeOfcDataVisitorsDaily()
  {
    $day_count = sfConfig::get('td_visitor_counter_days') - 1;
    $count_data = Doctrine::getTable('tdCounter')->getLastDaysCounts($day_count)->fetchArray();

    $chartData = array();
    $chartMax = 0;
    $chartLabels = array();

    //Array with sample random data
    foreach($count_data as $data)
    {
      $chartData[] = $data['count'];
      $chartLabels[] = $data['date'];
      if ($data['count'] > $chartMax) $chartMax = $data['count'];
    }

    //To create a bar chart we need to create a stBarOutline Object
    $bar = new stBarOutline( 80, '#78B9EC', '#3495FE' );
    $bar->key( 'Number of visitors during last '.$day_count.' days', 10 );

    //Passing the random data to bar chart
    $bar->data = $chartData;

    //Creating a stGraph object
    $g = new stGraph();
    $g->title( 'Visitors daily statistics', '{font-size: 20px;}' );
    $g->bg_colour = '#E4F5FC';
    $g->set_inner_background( '#E3F0FD', '#CBD7E6', 90 );
    $g->x_axis_colour( '#8499A4', '#E4F5FC' );
    $g->y_axis_colour( '#8499A4', '#E4F5FC' );

    //Pass stBarOutline object i.e. $bar to graph
    $g->data_sets[] = $bar;

    //Setting labels for X-Axis
    $g->set_x_labels($chartLabels);

    $chartStep = sfConfig::get('td_visitor_counter_days') / 5;

    // to set the format of labels on x-axis e.g. font, color, step
    $g->set_x_label_style( 10, '#18A6FF', 0, $chartStep );

    // To tick the values on x-axis
    // 2 means tick every 2nd value
    $g->set_x_axis_steps( $chartStep );

    //set maximum value for y-axis
    //we can fix the value as 20, 10 etc.
    //but its better to use max of data
    $g->set_y_max( $this->getMax($chartMax) );
    $g->y_label_steps( 5 );
    $g->set_y_legend( 'daily visitors count', 14, '#18A6FF' );
    echo $g->render();

    return sfView::NONE;
  }

  public function executeOfcDataVisitorsMonthly()
  {
    $month_count = sfConfig::get('td_visitor_counter_months') - 1;
    $count_data = Doctrine::getTable('tdCounter')->getLastMonthsCounts($month_count)->fetchArray();

    $chartData = array();
    $chartMax = 0;
    $chartLabels = array();

    //Array with sample random data
    foreach($count_data as $data)
    {
      $chartData[] = $data['count'];
      $chartLabels[] = $data['date'];
      if ($data['count'] > $chartMax) $chartMax = $data['count'];
    }

    //To create a bar chart we need to create a stBarOutline Object
    $bar = new stBarOutline( 80, '#78B9EC', '#3495FE' );
    $bar->key( 'Number of visitors during last '.$month_count.' months', 10 );

    //Passing the random data to bar chart
    $bar->data = $chartData;

    //Creating a stGraph object
    $g = new stGraph();
    $g->title( 'Visitors monthly statistics', '{font-size: 20px;}' );
    $g->bg_colour = '#E4F5FC';
    $g->set_inner_background( '#E3F0FD', '#CBD7E6', 90 );
    $g->x_axis_colour( '#8499A4', '#E4F5FC' );
    $g->y_axis_colour( '#8499A4', '#E4F5FC' );

    //Pass stBarOutline object i.e. $bar to graph
    $g->data_sets[] = $bar;

    //Setting labels for X-Axis
    $g->set_x_labels($chartLabels);

    $chartStep = 1; // sfConfig::get('td_visitor_counter_months') / 5;

    // to set the format of labels on x-axis e.g. font, color, step
    $g->set_x_label_style( 10, '#18A6FF', 0, $chartStep );

    // To tick the values on x-axis
    // 2 means tick every 2nd value
    $g->set_x_axis_steps( $chartStep );

    //set maximum value for y-axis
    //we can fix the value as 20, 10 etc.
    //but its better to use max of data
    $g->set_y_max( $this->getMax($chartMax) );
    $g->y_label_steps( 5 );
    $g->set_y_legend( 'monthly visitors count', 14, '#18A6FF' );
    echo $g->render();

    return sfView::NONE;
  }
}
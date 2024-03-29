<?php
/**
 * tdVisitorCounter actions.
 *
 * @package    tdVisitorCounterPlugin
 * @author     Tomasz Ducin <tomasz.ducin@gmail.com>
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
    $i18n = $this->getContext()->getI18n();
    $day_count = sfConfig::get('td_visitor_counter_days');
    $count_data = Doctrine::getTable('tdCounter')->getLastDaysCounts($day_count - 1)->fetchArray();

    $tmp_data = array();
    foreach($count_data as $data)
    {
      $tmp_data[$data['date']] = $data['count'];
    }

    $day_difference = 60 * 60 * 24;
    $time = time() - $day_difference * ($day_count -1);
    $dates = array();
    for($i = 0; $i < $day_count; $i++)
    {
      $dates[] = date("Y-m-d", $time);
      $time += $day_difference;
    }

    $result_data = array();
    foreach($dates as $date)
    {
      $result_data[$date] = (isset($tmp_data[$date]) ? $tmp_data[$date] : 0 );
    }

    $chartData = array();
    $chartMax = 0;
    $chartLabels = array();

    //Array with sample random data
    foreach($result_data as $date => $count)
    {
      $chartData[] = $count;
      $chartLabels[] = $date;
      if ($count > $chartMax) $chartMax = $count;
    }

    //To create a bar chart we need to create a stBarOutline Object
    $bar = new stBarOutline( 80, '#78B9EC', '#3495FE' );
    $key_label_1 = $i18n->__('Number of visitors during last', array(), 'td');
    $key_label_2 = $i18n->format_number_choice('[1] 1 day|(1,+Inf] %1% days',
      array('%1%' => $day_count), $day_count, 'td');
    $bar->key( $key_label_1.' '.$key_label_2, 10 );

    //Passing the random data to bar chart
    $bar->data = $chartData;

    //Creating a stGraph object
    $g = new stGraph();
    $g->title( $i18n->__('Visitors daily statistics', array(), 'td'), '{font-size: 20px;}' );
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
    $g->set_y_legend( $i18n->__('daily visitors count', array(), 'td'), 14, '#18A6FF' );
    echo $g->render();

    return sfView::NONE;
  }

  public function executeOfcDataVisitorsMonthly()
  {
    $i18n = $this->getContext()->getI18n();
    $month_count = sfConfig::get('td_visitor_counter_months');
    $count_data = Doctrine::getTable('tdCounter')->getLastMonthsCounts($month_count - 1)->fetchArray();

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
    $key_label_1 = $i18n->__('Number of visitors during last', array(), 'td');
    $key_label_2 = $i18n->format_number_choice('[1] 1 month|(1,+Inf] %1% months',
      array('%1%' => $month_count), $month_count, 'td');
    $bar->key( $key_label_1.' '.$key_label_2, 10 );

    //Passing the random data to bar chart
    $bar->data = $chartData;

    //Creating a stGraph object
    $g = new stGraph();
    $g->title( $i18n->__('Visitors monthly statistics', array(), 'td'), '{font-size: 20px;}' );
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
    $g->set_y_legend( $i18n->__('monthly visitors count', array(), 'td'), 14, '#18A6FF' );
    echo $g->render();

    return sfView::NONE;
  }
}
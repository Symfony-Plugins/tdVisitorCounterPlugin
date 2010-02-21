<?php use_helper('I18N') ?>

<h1><?php echo __('Visitors stats', array(), 'td') ?></h1>

<?php stOfc::createChart(600, 450, '@td_ofc_data_visitor_counter_monthly', false); ?>
<?php stOfc::createChart(600, 450, '@td_ofc_data_visitor_counter_daily', false); ?>

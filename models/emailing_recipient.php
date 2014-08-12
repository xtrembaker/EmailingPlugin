<?php
/**
 * 
 */
class EmailingRecipient extends EmailingAppModel{
  
  public $validate = array();
  
  /**
   * 
   * 
   * @var type 
   */
  public $belongsTo = array(
      'Emailing.Emailing'
  );
  
  /**
   *
   * @var type 
   */
  private $__type_values = array(
      'to' => 'to',
      'cc' => 'cc',
      'cci' => 'cci'
  );
  
}
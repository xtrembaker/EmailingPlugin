<?php

class EmailingSender extends EmailingAppModel {
  
  /**
   * hasMany
   * 
   * @var type 
   */
  public $hasMany = array(
      'Emailing.Emailing'
  );
  
  /**
   * BeforeValidate's Callback
   * 
   * @return boolean
   */
  public function beforeValidate(){
    // Is new ?
    if(!isset($this->data['EmailingSender']['id'])){
      $conditions = $this->data['EmailingSender'];
      // Check if a sender already exists with those conditions
      if(false !== $emailingSenderId = $this->getEmailingSenderIdByConditions($conditions)){
        $this->set('id',$emailingSenderId);
      }
    }
    return true;
  }
  
  /**
   * Return the Emailing Sender id matching conditions, if found
   * 
   * @return mixed EmailingSender'id if found, false otherwise
   */
  public function getEmailingSenderIdByConditions($conditions){
    $found = $this->find('first', array(
        'conditions' => $conditions,
        'fields' => array('EmailingSender.id'),
        'recursive' => -1
    ));
    if(!empty($found)){
      return $found['EmailingSender']['id'];
    }
    return false;
  }
}
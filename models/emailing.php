<?php
/**
 * 
 */
class Emailing extends EmailingAppModel{
  
  /**
   *
   * @var type 
   */
  public $validate = array();
  
  /**
   *
   * @var type 
   */
  private $__status_values = array(
      'draft' => 'draft',
      'ready' => 'ready',
      'sent' => 'sent'
  );
  
  /**
   *
   * @var type 
   */
  public $belongsTo = array(
      'Emailing.EmailingSender'
  );
  
  /**
   *
   * @var type 
   */
  public $hasMany = array(
      'Emailing.EmailingRecipient'
  );
  
  /**
   * Model's constructor
   * 
   * @param type $id
   * @param type $table
   * @param type $ds
   */
  function __construct($id = false, $table = null, $ds = null) {
    parent::__construct($id, $table, $ds);
    $this->validate = array(
        'emailing_sender_id' => array(
            'rule' => 'numeric',
            'required' => true,
            'message' => __d('EmailingPlugin','Model.Emailing.validate.sender_id', true)
        ),
        'content' => array(
            'rule' => 'notEmpty',
            'required' => true,
            'message' => __d('EmailingPlugin','Model.Emailing.validate.content', true)
        ),
        'subject' => array(
            'rule' => 'notEmpty',
            'required' => true,
            'message' => __d('EmailingPlugin','Model.Emailing.validate.subject', true)
        ),
        'template' => array(
            'rule' => 'notEmpty',
            'required' => true,
            'message' => __d('EmailingPlugin','Model.Emailing.validate.template', true)
        ),
        'status' => array(
            'rule' => array('inList', array_values($this->__status_values)),
            'required' => true,
            'message' => __d('EmailingPlugin','Model.Emailing.validate.status', true)
        ),
        'launch' => array(
            'required' => true,
            'message' => __d('EmailingPlugin','Model.validate.launch.launch_time', true)
        )
    );
  }
  
  /**
   * beforeSave callback
   * 
   * @param type $options
   */
  public function beforeSave($options = array()) {
    parent::beforeSave($options);
    if(isset($this->data[$this->alias]['status']) && $this->data[$this->alias]['status'] == 'sent'){
      $this->data[$this->alias]['sent_date'] = date('Y-m-d H:i:s');
    }
    return true;
  }
  
  /**
   * Set the correct value for the email status field
   * 
   * @param type $status
   * @return boolean return true if no error, false otherwise
   */
  public function setStatus($status){
    if(array_key_exists($status, $this->__status_values)){
      $value = $this->__status_values[$status];
      $this->set('status', $value);
      return true;
    }
    return false;
  }
  
  /**
   * 
   */
  public function getStatus(){
    
  }
  
  /**
   * Save a new emailing
   * 
   * @param array $data
   * @return boolean
   */
  public function saveEntry($data){
    if(array_key_exists('EmailingSender', $data)){      
      $emailingSender['EmailingSender'] = $data['EmailingSender'];
      unset($data['EmailingSender']);
      $this->EmailingSender->set($emailingSender);
      if(false !== $this->EmailingSender->validates()){
        if(false !== $this->EmailingSender->save()){
          $data['Emailing']['emailing_sender_id'] = $this->EmailingSender->id;
          if(parent::saveAll($data, array('validate' => 'only'))){
            $save = parent::saveAll($data, array('validate' => false)); 
            if(false !== $save){
              return true;
            }
          }
        }else{
          //echo "save failed EmailSender";
        }
      }else{
        //echo "validation failed EmailSender";
      }
    }
    return false;
  }
  
  /**
   * 
   */
  public function paginateAllEmailing(){
    return array(
        'recursive' => 2
    );
  }
  
}
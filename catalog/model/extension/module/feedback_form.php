<?php 

class ModelExtensionModuleFeedbackForm extends Model {

    public function add($data) {

        $this->db->query("INSERT INTO ".DB_PREFIX."feedback_form (name, phone) VALUES ('".$this->db->escape($data['name'])."', '".$this->db->escape($data['phone'])."')");

    }

}




<?php 

class ModelExtensionModulePartners extends Model {

    public function add($data) {

        $this->db->query("INSERT INTO ".DB_PREFIX."partners (company_name, contact_person, phone, email, city, cooperation_type, trade_duration) VALUES ('".$this->db->escape($data['company_name'])."', '".$this->db->escape($data['contact_person'])."', '".$this->db->escape($data['phone'])."', '".$this->db->escape($data['email'])."', '".$this->db->escape($data['city'])."', '".$this->db->escape($data['cooperation_type'])."', '".$this->db->escape($data['trade_duration'])."')" );

    }

}




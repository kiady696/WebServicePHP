<?php

    class User_model extends CI_Model {

        public function __construct(){
            $this->load->database();
        }
        
        public function getAllUsers(){
            $query = $this->db->get('utilisateur');
            return $query->result_array();
        }

    }



?>
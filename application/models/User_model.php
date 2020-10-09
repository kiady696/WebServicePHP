<?php

    class User_model extends CI_Model {

        public $id;
        public $nom;
        public $username;
        public $email;

        public function __construct(){
            $this->load->database();
        }

        public function count($tablename){
            return $this->db->count_all($tablename);
        }

        public function delete($id){
            $this->db->where('id' , $id);
            $this->db->delete('utilisateur');
        }

        public function inserer($data){
            $idnb = ($this->db->count_all('utilisateur')) + 2;
            $id = 'U'+$idnb;

            $toInsert = array(
                'id' => $id,
                'nom' => $data['nom'],
                'username' => $data['username'],
                'email' => $data['email']
            );

            $this->db->insert('utilisateur' , $toInsert);
        }

        public function update($data){
            $this->nom = $data['nom'];
            $this->username = $data['username'];
            $this->email = $data['email'];

            $toSend = array(
                'nom' => $this->nom,
                'username' => $this->username,
                'email' => $this->email
            );
            $this->db->where('id' , $data['id']);
            $this->db->update('utilisateur',$toSend);
        }

        public function set($data){
            $this->id = $data['id'];
            $this->nom = $data['nom'];
            $this->username = $data['username'];
            $this->email = $data['email'];
        }

        public function checkUser($name , $username , $email){
            //regexes in php
            $regName = "/^[a-zA-Zéàè][a-z]+([-\'])?/i";
            $regUsername = "/^[a-zA-Z0-9]+[a-z0-9]+/i";
            $regEmail = "/^[\.a-zA-Z0-9-_éèàâîïäç]+@[a-z]+(\.[a-z]{2,3})$/i";
            if(preg_match($regName ,$name) == 0 || preg_match($regUsername ,$username) == 0 || preg_match($regEmail ,$email) == 0){
                return false;
            } 

            return true;
        }

        //Mcheck am login
        public function check($username , $email){
            $sql = "SELECT * FROM utilisateur where username=? and email=?";
            $query = $this->db->query($sql , array($username , $email));
            if($query->result_array()){
                return true;
            }
            return false;
        }

        //Modèle get @ PAGINATION
        public function search($limit,$offset){
            $sql = "SELECT * FROM utilisateur limit ? offset ?";
            $query =$this->db->query($sql , array($limit , $offset));
            return $query->result_array();
        }
        
        public function getAllUsers(){
            $query = $this->db->get('utilisateur');
            return $query->result_array();
        }

    }



?>
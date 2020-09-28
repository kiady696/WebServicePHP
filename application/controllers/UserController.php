<?php

use chriskacerguis\RestServer\RestController;

class UserController extends RestController{
    
    function __construct(){
        parent::__construct();
        $this->load->model('user_model');
    }

    public function users_get(){
        try {
            $users = $this->user_model->getAllUsers(); //mandray parametre limite sy n° de page rehefa asiana pagination
            
            $id = $this->get('id');
            //var_dump($id);

            if( $id === null ){
                if( $users ){
                    $this->response( $users , 200 );
                }else{
                    $this->response( [
                        'status' => false,
                        'message' => 'No users were found'
                    ], 404 );
                }
            }else{
                for($i = 0 ; $i < count($users) ; $i++){
                    //var_dump($users[2]['id']);
                    if($users[$i]['id'] == $id ){
                        $this->response([ 
                            $users[$i]
                        ], 200 );
                    }                  
                }
                $this->response( [
                    'status' => false,
                    'message' => 'No such user found'
                    ], 404 );
            }


        }catch(Exception $e){
            $this->response([
                'status' => 'exception throwed',
                'message' => $e->get_message()
            ] , 500 );
        }
    }

    public function users_post(){
        //maka an'ireo post nalefanle xhr
        //mi-inserer raha valide ireo post
        //mamerina message success kely raha voa-inséré ireo posts
        //mamerina message erreur raha tsia inserted ireo posts
    }


}














?>
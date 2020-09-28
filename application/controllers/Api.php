<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Api extends RestController {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
    }

    public function users_get()
    {
        try{
            
            // Users from a data store e.g. database
            $users = [
                ['id' => 0, 'name' => 'John', 'email' => 'john@example.com'],
                ['id' => 1, 'name' => 'Jim', 'email' => 'jim@example.com'],
            ];

            $id = $this->get( 'id' ); //ty le '.../id/2' dia ilay id amny url get

            if ( $id === null ) //raha tsisy tohiny oe '/id/..' ilay url dia ireo users rehetra izany no avereno
            {
                // Check if the users data store contains users
                if ( $users )
                {
                    // Set the response and exit
                    $this->response( $users , 200 );
                }
                else
                {
                    // Set the response and exit
                    $this->response( [
                        'status' => false,
                        'message' => 'No users were found'
                    ], 404 );
                }
            }
            else
            {
                if ( array_key_exists( $id, $users ) ) //raha nisy '.../id/...' ilay url ka mi-existe ilay id anaty liste anle users
                {
                    $this->response([ 
                        $users[$id]
                    ], 200 ); //averiny ilay user manana ilay id am url
                }
                else
                {
                    $this->response( [
                        'status' => false,
                        'message' => 'No such user found'
                    ], 404 );
                }
            }

        } catch (Exception $e) {
            show_404();
        }
        
    }
}
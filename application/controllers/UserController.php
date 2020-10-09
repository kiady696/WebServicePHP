<?php

use chriskacerguis\RestServer\RestController;

class UserController extends RestController{
    
    function __construct(){
        parent::__construct();
        $this->load->model('user_model');
    }

    // Modèle login
    public function login_post(){
        $loginData = $this->post(); //formData ny tonga avany
        //mverifier fa valide ireo input
        if($this->user_model->checkUser($loginData['username'] , $loginData['username'] , $loginData['email'])){
            //mverifier fa efa ao ilay user teh hiconnecte
            $granted = $this->user_model->check($loginData['username'] , $loginData['email']);
            if($granted){
                //foronina ny token-any ho alefa json (atao zavatra arithmetique miova foana ohatra)
                $tokenValue = $loginData['username'] + 'TOKEN' + 8465198465123846524865134865 * date("d");

                //Afaka oe apiana colonne token ny table user dia stockena any io valeur anle token io dia isaky ny manao action izy de verifiena any io token io

                $token = [
                    'tokenName' => 'c_user' , 
                    'tokenValue' => $tokenValue
                ];
                $this->response([
                    'token' => $token , 
                    'status' => true , 
                    'message' => 'bienvenue'
                ] , 200);

            }else{
                //amerenana json misy oe Pas encore de compte? veuillez vous inscrire
                $this->response([
                    'status' => false ,
                    'message' => 'Vous n\'avez pas encore de compte? veuillez vous inscrire'
                ] , 401);
            }
        }

    }

    // Modèle fangalana an'ilay header 'Authorization: Bearer [Token]'
    public function getAuthorizationHeader(){
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        }
        else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            //print_r($requestHeaders);
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }
    /**
     * get access token from header
     * */
    public function getBearerToken() {
        $headers = $this->getAuthorizationHeader();
        // HEADER: Get the access token from the header
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }

    // Fomba apesaina anle bearerToken azo avany amle header js
    public function checkToken($BearedToken){
        //$authenticatedToken = $this->user_model->checkToken($BearedToken);
        if($authenticatedToken){
            return true;
        }
        return false;
    }

    // Modèle read + PAGINATION
    public function userss_get(){ // Manao pagination an
        $nPage = $this->get('page'); //numero an'ilay page angatahina
        $limit = 5; //ny isan'ny apoitra eo amin'ilay page
        $div = $this->user_model->count('utilisateur') / $limit;
        $nbPage = intval($div + 1); // ny nombre de Pages hiseho amin'ilay pagination
        if($div == 1){
            $nbPage = 1;
        }
        $offset = $limit*($nPage-1);
        $users = $this->user_model->search($limit,$offset);
        //if($users){
        //if($users == null){ response = 'no result'

            $authorisation = $this->getBearerToken();
            if($authorisation == null){
                $this->response([
                    'status' => false , 
                    'message' => 'tsy mety ilay bearer token (tsy nandefa ianao / tsy anaty basetsika io token io)'
                ] , 200);
            }

            $this->response([
                'data' => $users ,
                'auth' => $authorisation ,
                'nbPage' => $nbPage
            
            ], 200);

        /*}else{

            $this->response( [
                'status' => false,
                'message' => 'nisy blem ana search'
            ], 404 );
            
        }*/


    }

    // Modèle read tsotra
    public function users_get(){
        try {
            $users = $this->user_model->getAllUsers(); //mandray parametre limite sy n° de page rehefa asiana pagination
            
            $id = $this->get('id'); //raha nisy oe /id/... ilay url
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

                //afaka atao getWhere koa ny eto
                for($i = 0 ; $i < count($users) ; $i++){
                    //var_dump($users[2]['id']);
                    if($users[$i]['id'] == $id ){
                        $this->response(
                            $users[$i]
                        , 200 );
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
    
    // Modèle update
    public function users_post(){
        //maka an'ireo post nalefanle xhr (formData)
        $data = $this->post();
        //verification validité $data
        if($data){
            //verify data
            //manambotra fonction mamerina boolean mverifier ny props ana user ao @ user_model
            if($this->user_model->checkUser($data['nom'] , $data['username'] , $data['email'])){
               
                //if verified : update table utilisateur set nom , username , email where id = $data["id"]
                //$this->user_model->set($data);
                $this->user_model->update($data);

                $this->response([
                    'data' => $data ,
                    'message' => 'User updated successfully' 
                ], 200);
            }else{
                $this->response([
                    'status' => false,
                    'message' => 'Champ(s) invalide(s)!'
    
                ], 200);
            }
            
           
        }else{
            $this->response([
                'status' => false,
                'message' => 'Query Params Error'

            ], 200);
        }
        //mi-inserer raha valide ireo post
        //mamerina message success kely raha voa-inséré ireo posts
        //mamerina message erreur raha tsia inserted ireo posts
    }

    // Modèle delete
    public function users_delete(){
        $data = $this->get('id');
        //$id = $data['id'];
        $this->user_model->delete($data);
        $this->response([
            'status' => true , 
            'data' =>  $data ,
            'message' => 'Suppression effectuée' 
        ] , 200);
    }

    // Modèle create/insert
    public function users_put(){
        $data = $this->put();
        //apres verif , inserer
        if($this->user_model->checkUser($data['nom'] , $data['username'] , $data['email'])){
            $this->user_model->inserer($data);

            $this->response([
                'status' => true , 
                'data' =>  $data ,
                'message' => 'Addition effectuée' 
            ] , 200);
        }else{
            $this->response([
                'status' => false , 
                'data' =>  $data ,
                'message' => 'Champ(s) invalides!' 
            ] , 200);
        }


        

    }


}














?>
<?php
	class Rest_Api extends Rest_Rest {
	
		public $data = "";
			
		public function __construct(){
			parent::__construct();				// Init parent contructor
		}
		
		/**
		 * Public method for access api.
		 * This method dynmically call the method based on the query string
		 *
		 */
		public function processApi($func){
			$func = strtolower(trim(str_replace("/","",$_REQUEST['rquest']))); 		
			if((int)method_exists($this,$func) > 0)
				$this->$func();
			else
				$this->response('',204);				// If the method not exist with in this class, response would be "Page not found".
		}
		
		/**
		 * Simple verif exist preco
		 * numanc : <NUMANC>
		 */
		private function preco(){
			 if($this->get_request_method() != "GET"){ $this->response('',406); } 
			$my_preco=new Model_Precos;
			$numanc=$this->_request['numanc'];
			$res=$my_preco->fetchRow("numanc='$numanc'");
			if(is_object($res)){
				$this->response($this->json($res->toArray()), 200);
			}else{
				$this->response('',204);
			}
		}
		
		/**
		* Suppression preco
		* numanc : $numanc
		*/
		private function suppr_res(){
			if($this->get_request_method() != "DELETE"){ $this->response('',406); }
		   $my_preco=new Model_Precos;
		   $numanc=$this->_request['numanc'];
		   $ret=$my_preco->delete($numanc);
		   If($ret){
			   $success = array('status' => "Success", "msg" => "Element supprimé.");
			   $this->response($this->json($success),200);
		   }else{
			   $this->response('',204);
		   }
		}
		
		/**
		 *	Encode array into JSON
		*/
		private function json($data){
			if(is_array($data)){
				return json_encode($data);
			}
		}
	}
	
	// Initiiate Library
	$api = new Rest_Api;
	$api->processApi();

?>
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {


	public function __construct()
	{
		parent::__construct();
		$this->load->model('User_model');
        require_once APPPATH.'third_party/src/Google_Client.php';
        require_once APPPATH.'third_party/src/contrib/Google_Oauth2Service.php';	
	}

  public function index()
  {   

   
        if($this->session->userdata('is_customer_logged_in')){   redirect(base_url('users'));    }
      if ($this->input->server('REQUEST_METHOD') === 'POST')
        {
          $this->form_validation->set_rules('email', 'email', 'trim|required|valid_email');
          $email = strtolower($this->input->post('email'));
          $password = md5($this->input->post('password'));
          $is_valid = $this->User_model->validate($email,$password);
          if(empty($is_valid)) {
            $this->form_validation->set_rules('error', 'error', 'required');
            $this->form_validation->set_message('required', 'Email or Password is Wrong.');
          }
          
        $this->session->set_flashdata('login', 'false');
        $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</strong></div>');

             
          if ($this->form_validation->run())
          { 
            
          
           $data = array('full_name'=>$is_valid[0]['name'], 'email'=>$is_valid[0]['email'],  'cust_id'=>$is_valid[0]['id'], 'is_customer_logged_in' => true);
          $this->session->set_userdata($data);
          $this->session->set_userdata('time',time());
          $this->input->set_cookie('user_email', $is_valid[0]['email'],3600);
          redirect(base_url().'users');

         }/*validation run*/
         else {
           $this->session->set_flashdata('login', 'false');
         }
       }
    $this->load->view('index');
  }

  public function users()
  {   

    if(!$this->session->userdata('is_customer_logged_in')){   redirect(base_url());    }
    $data['users'] = $this->User_model->select_manual('customer');
    $this->load->view('users',$data);
  }


  public function google_login()
  {
    $clientId = '319124449251-iab714reb6d54rsknc47lbke0h35m9l7.apps.googleusercontent.com'; //Google client ID
    $clientSecret = 'FqaG1gtby6h_ReFEW-mtEica'; //Google client secret
    $redirectURL = base_url() .'google_login';
    //$redirectURL = 'https://www.flipcardapp.in/google_login';
    
    //https://curl.haxx.se/docs/caextract.html

    //Call Google API
    $gClient = new Google_Client();
    $gClient->setApplicationName('Login');
    $gClient->setClientId($clientId);
    $gClient->setClientSecret($clientSecret);
    $gClient->setRedirectUri($redirectURL);
    $google_oauthV2 = new Google_Oauth2Service($gClient);
    
    if(isset($_GET['code']))
    {
      $gClient->authenticate($_GET['code']);
      $_SESSION['token'] = $gClient->getAccessToken();
      header('Location: ' . filter_var($redirectURL, FILTER_SANITIZE_URL));
    }

    if (isset($_SESSION['token'])) 
    {
      $gClient->setAccessToken($_SESSION['token']);
    }
    
    if ($gClient->getAccessToken()) {
            $userProfile = $google_oauthV2->userinfo->get();
      $email = $userProfile['email'];
      if(!empty($email)){
             $is_valid = $this->User_model->get_user_by_email($email);
        
        if(!empty($is_valid))
      {
       


         $data = array('full_name'=>$is_valid[0]['name'], 'email'=>$is_valid[0]['email'],  'cust_id'=>$is_valid[0]['id'], 'is_customer_logged_in' => true);
          $this->session->set_userdata($data);
          $this->session->set_userdata('time',time());
          $this->input->set_cookie('user_email', $is_valid[0]['email'],3600);
          redirect(base_url().'users');
      

       } else{

        

          $data_to_store = array(
             'name' => $userProfile['name'],  
             'email' => $email,
             'status' => 'active'
           ); 
           $return = $this->User_model->add_user($data_to_store);

          $data = array('full_name'=>$userProfile['name'], 'email'=>$email,  'cust_id'=>$return, 'cust_img'=>'', 'is_customer_logged_in' => true);
          $this->session->set_userdata($data);
          $this->session->set_userdata('time',time());
          $this->input->set_cookie('user_email', $is_valid[0]['email'],3600);
          redirect(base_url('users'));
         
       }
       
       
      }
       
        } 
    else 
    {
            $url = $gClient->createAuthUrl();
        header("Location: $url");
            exit;
        }
  } 
  
	
	public function signup()
	{
	if($this->session->userdata('is_customer_logged_in')){   redirect(base_url()); 	  }
		 if ($this->input->server('REQUEST_METHOD') === 'POST')
        {

          $this->form_validation->set_rules('name', 'Name', 'required|trim');
          $this->form_validation->set_rules('email', 'email', 'trim|required|valid_email');
		  $is_email = $this->User_model->select_manual('customer',array('email'=>$this->input->post('email')));
		  if(!empty($is_email)) {
			$this->form_validation->set_rules('error', 'error', 'required');
			$this->form_validation->set_message('required', 'Email Already Exist.');
		  }
          $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</strong></div>');

             
          if ($this->form_validation->run())
          { 
            $data_to_store = array(
             'name' => $this->input->post('name'),  
             'email' => strtolower($this->input->post('email')),
             'password' => md5($this->input->post('password')),
             'status'=>'active'
           ); 
            $return = $this->User_model->add_user($data_to_store);
             
           if($return == TRUE){
             $this->session->set_flashdata('login', 'true');
             redirect(base_url());
           }else{
             $this->session->set_flashdata('register', 'not_updated');
           }
         }/*validation run*/

       }
		
		$this->load->view('signup');
	}
	function logout()
	{
		$this->session->sess_destroy();
		redirect(base_url());
	}

    function delete() {
        $id = $this->uri->segment(2);
        $this->User_model->delete_user($id);
        redirect(base_url('users'));

    }
}

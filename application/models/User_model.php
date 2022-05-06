<?php 
class User_model extends CI_Model {
 


 	
	function delete_manual($table,$where)
	{
		$this->db->where($where);
		$this->db->delete($table);
	}
	function select_manual($table,$where='',$where2=''){
		$this->db->select('*');
		$this->db->from($table); 
		if($where != '') { $this->db->where($where); }
		if($where2 != '') { $this->db->where($where2); }
		$query = $this->db->get();
		return $query->result_array();
	}


	function validate($email,$password){
		$this->db->select('*');
		$this->db->from('customer'); 
		$this->db->where('email',$email);
		$this->db->where('password',$password);
		$query = $this->db->get();
		return $query->result_array();
	}

	function get_user_by_email($email){
		$this->db->select('*');
		$this->db->from('customer'); 
		$this->db->where('email',$email);
		$query = $this->db->get();
		return $query->result_array();
	}

	function add_user($data){
		$this->db->insert('customer', $data); 
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}

	function delete_user($id)
	{
		$this->db->where('id',$id);
		$this->db->delete('customer');
	}
   
   }
?>
<?php
class dev_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}
 
	function updateData($table,$data,$arg=[])
	{
		if(isset($arg['where'])){$this->db->where($arg['where']);}
		return $this->db->update($table,$data);
	}

	function updateData1($table,$data,$arg=[],$cache=false)
	{
		if(isset($arg['where'])){$this->db->where($arg['where']);}
		if(!isset($arg['batchCol']))
		$this->db->update($table,$data);
		else
		$this->db->update_batch($table,$data,$arg['batchCol']);
		if($cache){$this->cache->save($cache['index'],$cache['cacheValue'],$cache['time']);}
	}

	function deleteData($table,$where=false)
	{
		if($where!==false)
		$this->db->where($where);
		return $this->db->delete($table);
	}

	function getData($table,$resultType="all_array",$arg=[])
	{
		if(isset($arg['cache'])){if($record=$this->cache->get($arg['cache']['index'])){return $record;}}

		if(isset($arg['wherein'])){$this->db->where_in($arg['wherein']['column'],$arg['wherein']['data']);}
		if(isset($arg['where'])){$this->db->where($arg['where']);}
		if(isset($arg['wherentin'])){ $this->db->where_not_in($arg['wherentin']['column'],$arg['wherentin']['data']);}
		if(isset($arg['select'])){$this->db->select($arg['select']);}
		if(isset($arg['join'])){$this->db->join($arg['join']['table'],$arg['join']['query'],$arg['join']['type']);}
		if(isset($arg['like'])){$this->db->like($arg['like']['col'],$arg['like']['query']);}
		if(isset($arg['order'])){$this->db->order_by($arg['order']['col'],$arg['order']['type']);}
		if(isset($arg['groupby'])){$this->db->group_by($arg['groupby']);}
		if(isset($arg['limit'])){$this->db->limit($arg['limit']);}
		if(isset($arg['distinct'])){$this->db->distinct($arg['distinct']);}

		$return=null;
		if($resultType=="all_array"){$return=$this->db->get($table)->result_array();}
		else if($resultType=="row_array"){$return=$this->db->get($table)->row_array();}
		else{$return=$this->db->count_all_results($table);}
		if(isset($arg['cache'])){
		$this->cache->file->save($arg['cache']['index'],$return,$arg['cache']['time']);}
		return $return;
	}
	function insertData($table,$insert,$batch=false)
	{
		if(!$batch)
		return $this->db->insert($table,$insert);
		else
		return $this->db->insert_batch($table,$insert);
	}
	public function insert_id($table,$data)
	{
		$this->db->insert($table,$data);
		return $this->db->insert_id();
	}

	function query($query,$resultType)
	{
		$res=$this->db->query($query);
		if($resultType=="all_array")
		{
			return $res->result_array();
		}
		else if($resultType=="row_array")
		{
			return $res->row_array();
		}
		else
		{
			return $res->count_all_results($table);
		}
	}
}

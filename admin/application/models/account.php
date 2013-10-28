<?php
if ( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );
require_once ('ICrud.php');
class Account extends CI_Model implements ICrud
{
	private $table = 'accounts';

	public function __construct()
	{
		parent::__construct();
	}

	public function count( $parameter = null, $extension = null )
	{
		if ( !empty( $parameter ) )
		{
			foreach ( $parameter as $key => $value )
			{
				$this->db->where( $key, $value );
			}
		}
		if ( !empty( $extension ) )
		{
		}
		return $this->db->count_all_results( $this->table );
	}

	public function create( $row )
	{
		if ( !empty( $row ) )
		{
			return $this->db->insert( $this->table, $row );
		}
		else
		{
			return false;
		}
	}

	public function read( $parameter = null, $extension = null, $limit = 0, $offset = 0 )
	{
		if ( !empty( $parameter ) )
		{
			foreach ( $parameter as $key => $value )
			{
				$this->db->where( $key, $value );
			}
		}
		if ( !empty( $extension ) )
		{
		}
		if ( $limit == 0 && $offset == 0 )
		{
			$query = $this->db->get( $this->table );
		}
		else
		{
			$query = $this->db->get( $this->table, $limit, $offset );
		}
		if ( $query->num_rows() > 0 )
		{
			return $query->result();
		}
		else
		{
			return false;
		}
	}

	public function update( $id, $row )
	{
		if ( !empty( $id ) && !empty( $row ) )
		{
			$this->db->where( 'id', $id );
			return $this->db->update( $this->table, $row );
		}
		else
		{
			return false;
		}
	}

	public function delete( $id )
	{
		if ( !empty( $id ) )
		{
			$this->db->where( 'id', $id );
			return $this->db->delete( $this->table );
		}
		else
		{
			return false;
		}
	}
}

?>
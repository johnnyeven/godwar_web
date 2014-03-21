<?php
if ( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );
require_once ('ICrud.php');
class Equipment extends CI_Model implements ICrud
{
	private $table = 'equipments';
	private $gamedb = null;

	public function __construct()
	{
		parent::__construct();
		$this->gamedb = $this->load->database('gamedb', TRUE);
	}

	public function count( $parameter = null, $extension = null )
	{
		if ( !empty( $parameter ) )
		{
			foreach ( $parameter as $key => $value )
			{
				$this->gamedb->where( $key, $value );
			}
		}
		if ( !empty( $extension ) )
		{
		}
		return $this->gamedb->count_all_results( $this->table );
	}

	public function create( $row )
	{
		if ( !empty( $row ) )
		{
			return $this->gamedb->insert( $this->table, $row );
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
				$this->gamedb->where( $key, $value );
			}
		}
		if ( !empty( $extension ) )
		{
		}
		if ( $limit == 0 && $offset == 0 )
		{
			$query = $this->gamedb->get( $this->table );
		}
		else
		{
			$query = $this->gamedb->get( $this->table, $limit, $offset );
		}
		if ( $query->num_rows() > 0 )
		{
			return $query->result_array();
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
			$this->gamedb->where( 'id', $id );
			return $this->gamedb->update( $this->table, $row );
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
			$this->gamedb->where( 'id', $id );
			return $this->gamedb->delete( $this->table );
		}
		else
		{
			return false;
		}
	}
}

?>
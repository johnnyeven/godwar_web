<?php
if ( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );
require_once ('ICrud.php');
class Alchemy_config extends CI_Model implements ICrud
{
	private $table    = 'alchemy';
	private $configDB = null;

	public function __construct()
	{
		parent::__construct();
		$this->configDB = $this->load->database('configdb', TRUE);
	}

	public function count( $parameter = null, $extension = null )
	{
		if ( !empty( $parameter ) )
		{
			foreach ( $parameter as $key => $value )
			{
				$this->configDB->where($key, $value );
			}
		}
		if ( !empty( $extension ) )
		{
		}
		return $this->configDB->count_all_results($this->table );
	}

	public function create( $row )
	{
		if ( !empty( $row ) )
		{
            if (is_array($row['materials'])) {
                $row['materials'] = json_encode($row['materials']);
            }
            if (is_array($row['product'])) {
                $row['product'] = json_encode($row['product']);
            }
			return $this->configDB->insert($this->table, $row );
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
				$this->configDB->where($key, $value );
			}
		}
		if ( !empty( $extension ) )
		{
		}
		if ( $limit == 0 && $offset == 0 )
		{
			$query = $this->configDB->get($this->table );
		}
		else
		{
			$query = $this->configDB->get($this->table, $limit, $offset );
		}
		if ( $query->num_rows() > 0 )
		{
			$result = $query->result_array();
            foreach ($result as &$value) {
                $value['materials'] = json_decode($value['materials'], true);
                $value['product'] = json_decode($value['product'], true);
            }
			return $result;
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
            if (is_array($row['materials'])) {
                $row['materials'] = json_encode($row['materials']);
            }
            if (is_array($row['product'])) {
                $row['product'] = json_encode($row['product']);
            }
			$this->configDB->where('id', $id );
			return $this->configDB->update($this->table, $row );
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
			$this->configDB->where('id', $id );
			return $this->configDB->delete($this->table );
		}
		else
		{
			return false;
		}
	}

	public function db()
	{
		return $this->configDB;
	}
}

?>
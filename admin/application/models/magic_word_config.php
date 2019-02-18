<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
require_once('ICrud.php');

class Magic_word_config extends CI_Model implements ICrud
{
    private $table    = 'magic_word';
    private $configDB = null;

    public function __construct()
    {
        parent::__construct();
        $this->configDB = $this->load->database('configdb', true);
    }

    public function count($parameter = null, $extension = null)
    {
        if (!empty($parameter)) {
            foreach ($parameter as $key => $value) {
                $this->configDB->where($key, $value);
            }
        }
        if (!empty($extension)) {
        }
        return $this->configDB->count_all_results($this->table);
    }

    public function create($row)
    {
        if (!empty($row)) {
            if (is_array($row['equipment_position'])) {
                $row['equipment_position'] = json_encode($row['equipment_position']);
            }
            if (is_array($row['property'])) {
                $row['property'] = json_encode($row['property']);
            }
            return $this->configDB->insert($this->table, $row);
        } else {
            return false;
        }
    }

    public function read($parameter = null, $extension = null, $limit = 0, $offset = 0)
    {
        if (!empty($parameter)) {
            foreach ($parameter as $key => $value) {
                $this->configDB->where($key, $value);
            }
        }
        if (!empty($extension)) {
        }
        if ($limit == 0 && $offset == 0) {
            $query = $this->configDB->get($this->table);
        } else {
            $query = $this->configDB->get($this->table, $limit, $offset);
        }
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            foreach ($result as &$value) {
                $value['equipment_position'] = json_decode($value['equipment_position'], true);
                $value['property'] = json_decode($value['property'], true);
            }
            return $result;
        } else {
            return false;
        }
    }

    public function update($id, $row)
    {
        if (!empty($id) && !empty($row)) {
            if (is_array($row['equipment_position'])) {
                $row['equipment_position'] = json_encode($row['equipment_position']);
            }
            if (is_array($row['property'])) {
                $row['property'] = json_encode($row['property']);
            }
            $this->configDB->where('id', $id);
            return $this->configDB->update($this->table, $row);
        } else {
            return false;
        }
    }

    public function delete($id)
    {
        if (!empty($id)) {
            $this->configDB->where('id', $id);
            return $this->configDB->delete($this->table);
        } else {
            return false;
        }
    }

    public function db()
    {
        return $this->configDB;
    }
}

?>
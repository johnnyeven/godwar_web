<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
require_once('ICrud.php');

class Equipment_config extends CI_Model implements ICrud
{
    private $table    = 'equipment';
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
            if (is_array($row['job'])) {
                $row['job'] = json_encode($row['job']);
            }
            if (is_array($row['success_rate'])) {
                $row['success_rate'] = json_encode($row['success_rate']);
            }
            if (is_array($row['atk_upgrade'])) {
                $row['atk_upgrade'] = json_encode($row['atk_upgrade']);
            }
            if (is_array($row['def_upgrade'])) {
                $row['def_upgrade'] = json_encode($row['def_upgrade']);
            }
            if (is_array($row['mdef_upgrade'])) {
                $row['mdef_upgrade'] = json_encode($row['mdef_upgrade']);
            }
            if (is_array($row['health_max_upgrade'])) {
                $row['health_max_upgrade'] = json_encode($row['health_max_upgrade']);
            }
            if (is_array($row['hit_upgrade'])) {
                $row['hit_upgrade'] = json_encode($row['hit_upgrade']);
            }
            if (is_array($row['flee_upgrade'])) {
                $row['flee_upgrade'] = json_encode($row['flee_upgrade']);
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
                $value['job'] = json_decode($value['job'], true);
                $value['success_rate'] = json_decode($value['success_rate'], true);
                $value['atk_upgrade'] = json_decode($value['atk_upgrade'], true);
                $value['def_upgrade'] = json_decode($value['def_upgrade'], true);
                $value['mdef_upgrade'] = json_decode($value['mdef_upgrade'], true);
                $value['health_max_upgrade'] = json_decode($value['health_max_upgrade'], true);
                $value['hit_upgrade'] = json_decode($value['hit_upgrade'], true);
                $value['flee_upgrade'] = json_decode($value['flee_upgrade'], true);
            }
            return $result;
        } else {
            return false;
        }
    }

    public function update($id, $row)
    {
        if (!empty($id) && !empty($row)) {
            if (is_array($row['job'])) {
                $row['job'] = json_encode($row['job']);
            }
            if (is_array($row['success_rate'])) {
                $row['success_rate'] = json_encode($row['success_rate']);
            }
            if (is_array($row['atk_upgrade'])) {
                $row['atk_upgrade'] = json_encode($row['atk_upgrade']);
            }
            if (is_array($row['def_upgrade'])) {
                $row['def_upgrade'] = json_encode($row['def_upgrade']);
            }
            if (is_array($row['mdef_upgrade'])) {
                $row['mdef_upgrade'] = json_encode($row['mdef_upgrade']);
            }
            if (is_array($row['health_max_upgrade'])) {
                $row['health_max_upgrade'] = json_encode($row['health_max_upgrade']);
            }
            if (is_array($row['hit_upgrade'])) {
                $row['hit_upgrade'] = json_encode($row['hit_upgrade']);
            }
            if (is_array($row['flee_upgrade'])) {
                $row['flee_upgrade'] = json_encode($row['flee_upgrade']);
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
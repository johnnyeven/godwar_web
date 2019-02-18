<?php
if (!defined('BASEPATH')) {
    exit ('No direct script access allowed');
}

class Map extends CI_Controller
{
    private $pageName    = 'action/map';
    private $user        = null;
    private $currentRole = null;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('utils/check_user', 'check');
        $this->user = $this->check->validate();
        $this->currentRole = $this->check->check_role();
    }

    public function info($map_id)
    {
        if (empty($map_id)) {
            $map_id = $this->currentRole->role ['map_id'];
        }
        $map_id = intval($map_id);

        $this->load->model('config/map_config');
        if (!empty($map_id)) {
            $parameter = [
                'id' => $map_id,
            ];
            $map = $this->map_config->read($parameter);
            $result = $map [0]['monster'];

            $this->load->model('config/monster_config');
            $resultMonster = $this->monster_config->read(
                null,
                [
                    'where_in' => ['id', $result],
                    'order_by' => ['level', 'asc'],
                ]
            );
        }
        $maps = $this->map_config->read();

        $parameter = [
            'role'                 => $this->currentRole->role,
            'maps'                 => $maps,
            'monsters'             => $resultMonster,
            'current_selected_map' => $map [0],
        ];
        $this->load->model('utils/render');
        $this->render->render($this->pageName, $parameter);
    }

    public function move($map_id)
    {
        if (!empty($map_id)) {
            $map_id = intval($map_id);

            $this->load->model('config/map_config');
            $parameter = [
                'id' => $map_id,
            ];
            $map = $this->map_config->read($parameter);

            if (!empty($map)) {
                $parameter = [
                    'map_id' => $map_id,
                ];

                $this->load->model('role');
                $this->role->update($this->currentRole->role ['id'], $parameter);

                redirect('action/map/info');
            } else {
                showMessage(MESSAGE_TYPE_ERROR, 'MAP_ID_INVALID', '', 'action/map/info', true, 5);
            }
        } else {
            showMessage(MESSAGE_TYPE_ERROR, 'MAP_ID_INVALID', '', 'action/map/info', true, 5);
        }
    }
}

?>
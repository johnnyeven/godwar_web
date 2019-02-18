<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Equipment extends CI_Controller
{
    private $pageName    = 'role/equipment';
    private $user        = null;
    private $currentRole = null;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('utils/check_user', 'check');
        $this->user = $this->check->validate();
        $this->currentRole = $this->check->check_role();
    }

    public function index()
    {
        //1=武器2=头盔3=护手4=盔甲5=腰带6=鞋子7=戒指8=项链
        $this->load->config('const.config');
        $job_list = $this->config->item('const_job');
        $equipment_title = [
            1 => '武器',
            2 => '头盔',
            3 => '护手',
            4 => '盔甲',
            5 => '腰带',
            6 => '鞋子',
            7 => '戒指',
            8 => '项链',
        ];
        $this->load->model('mequipment');
        $parameter = [
            'role_id' => $this->currentRole->role['id'],
        ];
        $result = $this->mequipment->read($parameter);

        $parameter = [
            'role_id'     => $this->currentRole->role['id'],
            'is_equipped' => 1,
        ];
        $e = $this->mequipment->read($parameter);
        $equipped = [];
        foreach ($e as $item) {
            $equipped[ intval($item['position']) ] = $item;
        }

        $data = [
            'job_list'        => $job_list,
            'equipment_title' => $equipment_title,
            'equipped'        => $equipped,
            'equipments'      => $result,
            'role'            => $this->currentRole->role,
        ];

        $this->load->model('utils/render');
        $this->render->render($this->pageName, $data);
    }

    public function equip($id)
    {
        if (!empty($id)) {
            $this->load->model('mequipment');
            $parameter = [
                'id'      => $id,
                'role_id' => $this->currentRole->role['id'],
            ];
            $current = $this->mequipment->read($parameter);
            if (!empty($current)) {
                $current = $current[0];

                if ($current['is_equipped'] == '1') {
                    showMessage(
                        MESSAGE_TYPE_ERROR,
                        'EQUIPMENT_ERROR_EQUIPPED',
                        '',
                        'role/equipment',
                        true,
                        5
                    );
                } else {
                    if ($current['level'] <= $this->currentRole->role['level']) {
                        $jobs = json_decode($current['job']);
                        if (in_array($this->currentRole->role[ job ], $jobs)
                            || in_array(
                                99,
                                $jobs
                            )) {
                            $position = $current['position'];
                            $parameter = [
                                'role_id'     => $this->currentRole->role['id'],
                                'position'    => $position,
                                'is_equipped' => 1,
                            ];
                            $result = $this->mequipment->read($parameter);
                            if (!empty($result)) {
                                $result = $result[0];
                                $parameter = [
                                    'is_equipped' => 0,
                                ];
                                $this->mequipment->update($result['id'], $parameter);
                            }

                            if (!empty($current['magic_words'])) {
                                $word_list = ['atk', 'def', 'mdef', 'health_max', 'hit', 'flee'];
                                $magic_words = json_decode($current['magic_words'], true);

                                foreach ($magic_words as $magic) {
                                    foreach ($magic['property'] as $property => $value) {
                                        if (in_array($property, $word_list)) {
                                            if ($magic['property'][ $property . '_unit' ] == 1) {
                                                $current[ $property . '_inc' ] += intval($value);
                                            } elseif ($magic['property'][ $property . '_unit' ]
                                                      == 2) {
                                                if (!empty($current[ $property . '_base' ])) {
                                                    $current[ $property . '_inc' ] += intval(
                                                        $current[ $property . '_base' ] * $value
                                                    );
                                                } else {
                                                    $current[ $property . '_inc' ] += intval(
                                                        $this->currentRole->role[ $property ]
                                                        * $value
                                                    );
                                                }
                                            }
                                        }
                                    }
                                }
                            }

                            $parameter = [
                                'atk_inc'        => $current['atk_inc'],
                                'def_inc'        => $current['def_inc'],
                                'mdef_inc'       => $current['mdef_inc'],
                                'health_max_inc' => $current['health_max_inc'],
                                'hit_inc'        => $current['hit_inc'],
                                'flee_inc'       => $current['flee_inc'],
                                'is_equipped'    => 1,
                                'is_locked'      => 1,
                            ];
                            $this->mequipment->update($id, $parameter);

                            $this->load->model('config/race_config');
                            $param = [
                                'id' => $this->currentRole->role ['race'],
                            ];
                            $raceResult = $this->race_config->read($param);
                            $raceResult = $raceResult [0];

                            $this->load->model('config/job_config');
                            $param = [
                                'id' => intval($this->currentRole->role ['job']),
                            ];
                            $jobResult = $this->job_config->read($param);
                            $jobResult = $jobResult [0];

                            if (!empty($raceResult) && !empty($jobResult)) {
                                $this->currentRole->calculate_property($raceResult, $jobResult);
                                $this->currentRole->save();
                            }

                            redirect('role/equipment');
                        } else {
                            showMessage(
                                MESSAGE_TYPE_ERROR,
                                'EQUIPMENT_NOT_JOB',
                                '',
                                'role/equipment',
                                true,
                                5
                            );
                        }
                    } else {
                        showMessage(
                            MESSAGE_TYPE_ERROR,
                            'EQUIPMENT_NOT_LEVEL',
                            '',
                            'role/equipment',
                            true,
                            5
                        );
                    }
                }
            } else {
                showMessage(
                    MESSAGE_TYPE_ERROR,
                    'EQUIPMENT_ID_NOT_EXIST',
                    '',
                    'role/equipment',
                    true,
                    5
                );
            }
        } else {
            showMessage(MESSAGE_TYPE_ERROR, 'EQUIPMENT_ID_NO_PARAM', '', 'role/equipment', true, 5);
        }
    }

    public function unequip($id)
    {
        if (!empty($id)) {
            $this->load->model('mequipment');
            $parameter = [
                'id'      => $id,
                'role_id' => $this->currentRole->role['id'],
            ];
            $result = $this->mequipment->read($parameter);
            if (!empty($result)) {
                $result = $result[0];
                if ($result['is_equipped'] == '0') {
                    showMessage(
                        MESSAGE_TYPE_ERROR,
                        'EQUIPMENT_ERROR_UNEQUIPPED',
                        '',
                        'role/equipment',
                        true,
                        5
                    );
                } else {
                    $parameter = [
                        'atk_inc'        => 0,
                        'def_inc'        => 0,
                        'mdef_inc'       => 0,
                        'health_max_inc' => 0,
                        'hit_inc'        => 0,
                        'flee_inc'       => 0,
                        'is_equipped'    => 0,
                    ];
                    $this->mequipment->update($id, $parameter);

                    $this->load->model('config/race_config');
                    $param = [
                        'id' => $this->currentRole->role ['race'],
                    ];
                    $raceResult = $this->race_config->read($param);
                    $raceResult = $raceResult [0];

                    $this->load->model('config/job_config');
                    $param = [
                        'id' => intval($this->currentRole->role ['job']),
                    ];
                    $jobResult = $this->job_config->read($param);
                    $jobResult = $jobResult [0];

                    if (!empty($raceResult) && !empty($jobResult)) {
                        $this->currentRole->calculate_property($raceResult, $jobResult);
                        $this->currentRole->save();
                    }

                    redirect('role/equipment');
                }
            } else {
                showMessage(
                    MESSAGE_TYPE_ERROR,
                    'EQUIPMENT_ID_NOT_EXIST',
                    '',
                    'role/equipment',
                    true,
                    5
                );
            }
        } else {
            showMessage(MESSAGE_TYPE_ERROR, 'EQUIPMENT_ID_NO_PARAM', '', 'role/equipment', true, 5);
        }
    }

    public function sell($id)
    {
        if (!empty($id)) {
            $this->load->model('mequipment');
            $parameter = [
                'id'      => $id,
                'role_id' => $this->currentRole->role['id'],
            ];
            $result = $this->mequipment->read($parameter);
            if (!empty($result)) {
                $result = $result[0];
                if ($result['is_locked'] == '1') {
                    showMessage(
                        MESSAGE_TYPE_ERROR,
                        'EQUIPMENT_ERROR_LOCKED',
                        '',
                        'role/equipment',
                        true,
                        5
                    );
                } elseif ($result['is_equipped'] == '1') {
                    showMessage(
                        MESSAGE_TYPE_ERROR,
                        'EQUIPMENT_ERROR_EQUIPPED',
                        '',
                        'role/equipment',
                        true,
                        5
                    );
                } else {
                    $price = $result['price'];
                    if ($price > 0) {
                        $this->load->library('Gift');
                        $this->_hook_gifts($this->currentRole->role);
                        $parameter = [
                            'action' => 'sell',
                            'price'  => $price,
                        ];
                        $this->gift->call_hook('after_billing_sell', $parameter);
                        $price = $parameter['price'];

                        $this->mequipment->delete($id);

                        $this->currentRole->role['gold'] += $price;
                        $this->currentRole->save();

                        redirect('role/equipment');
                    } else {
                        showMessage(
                            MESSAGE_TYPE_ERROR,
                            'EQUIPMENT_NO_SELL',
                            '',
                            'role/equipment',
                            true,
                            5
                        );
                    }
                }
            } else {
                showMessage(
                    MESSAGE_TYPE_ERROR,
                    'EQUIPMENT_ID_NOT_EXIST',
                    '',
                    'role/equipment',
                    true,
                    5
                );
            }
        } else {
            showMessage(MESSAGE_TYPE_ERROR, 'EQUIPMENT_ID_NO_PARAM', '', 'role/equipment', true, 5);
        }
    }

    public function sell_all()
    {
        $this->load->model('mequipment');
        $parameter = [
            'role_id'     => $this->currentRole->role['id'],
            'is_equipped' => 0,
            'is_locked'   => 0,
        ];
        $extension = [
            'select_sum' => 'price',
        ];
        $result = $this->mequipment->read($parameter, $extension);
        $price = intval($result[0]['price']);

        if ($price > 0) {
            $this->load->library('Gift');
            $this->_hook_gifts($this->currentRole->role);
            $gift_param = [
                'action' => 'sell',
                'price'  => $price,
            ];
            $this->gift->call_hook('after_billing_sell', $gift_param);
            $price = $gift_param['price'];

            $this->mequipment->delete($parameter);

            $this->currentRole->role['gold'] += $price;
            $this->currentRole->save();

            redirect('role/equipment');
        } else {
            showMessage(
                MESSAGE_TYPE_ERROR,
                'EQUIPMENT_NO_SELL_ITEMS',
                '',
                'role/equipment',
                true,
                5
            );
        }
    }

    public function lock($id)
    {
        if (!empty($id)) {
            $this->load->model('mequipment');
            $parameter = [
                'id'      => $id,
                'role_id' => $this->currentRole->role['id'],
            ];
            $result = $this->mequipment->read($parameter);
            if (!empty($result)) {
                $parameter = [
                    'is_locked' => 1,
                ];
                $this->mequipment->update($id, $parameter);

                redirect('role/equipment');
            } else {
                showMessage(
                    MESSAGE_TYPE_ERROR,
                    'EQUIPMENT_ID_NOT_EXIST',
                    '',
                    'role/equipment',
                    true,
                    5
                );
            }
        } else {
            showMessage(MESSAGE_TYPE_ERROR, 'EQUIPMENT_ID_NO_PARAM', '', 'role/equipment', true, 5);
        }
    }

    public function unlock($id)
    {
        if (!empty($id)) {
            $this->load->model('mequipment');
            $parameter = [
                'id'      => $id,
                'role_id' => $this->currentRole->role['id'],
            ];
            $result = $this->mequipment->read($parameter);
            if (!empty($result)) {
                $parameter = [
                    'is_locked' => 0,
                ];
                $this->mequipment->update($id, $parameter);

                redirect('role/equipment');
            } else {
                showMessage(
                    MESSAGE_TYPE_ERROR,
                    'EQUIPMENT_ID_NOT_EXIST',
                    '',
                    'role/equipment',
                    true,
                    5
                );
            }
        } else {
            showMessage(MESSAGE_TYPE_ERROR, 'EQUIPMENT_ID_NO_PARAM', '', 'role/equipment', true, 5);
        }
    }

    public function destroy($id)
    {
        if (!empty($id)) {
            $this->load->model('mequipment');
            $parameter = [
                'id'      => $id,
                'role_id' => $this->currentRole->role['id'],
            ];
            $result = $this->mequipment->read($parameter);
            if (!empty($result)) {

                $result = $result[0];
                if ($result['is_equipped'] == '1') {
                    showMessage(
                        MESSAGE_TYPE_ERROR,
                        'EQUIPMENT_ERROR_EQUIPPED',
                        '',
                        'role/equipment',
                        true,
                        5
                    );
                } elseif ($result['is_locked'] == '1') {
                    showMessage(
                        MESSAGE_TYPE_ERROR,
                        'EQUIPMENT_ERROR_LOCKED',
                        '',
                        'role/equipment',
                        true,
                        5
                    );
                } else {
                    $this->mequipment->delete($id);

                    redirect('role/equipment');
                }
            } else {
                showMessage(
                    MESSAGE_TYPE_ERROR,
                    'EQUIPMENT_ID_NOT_EXIST',
                    '',
                    'role/equipment',
                    true,
                    5
                );
            }
        } else {
            showMessage(MESSAGE_TYPE_ERROR, 'EQUIPMENT_ID_NO_PARAM', '', 'role/equipment', true, 5);
        }
    }

    private function _hook_gifts($role)
    {
        if (isset($role['gift'])) {
            $gifts = json_decode($role['gift']);
            if (is_array($gifts)) {
                $this->load->config('gifts.config');
                $giftConfig = $this->config->item('gifts_hook_action_name');
                foreach ($gifts as $gift) {
                    if (is_array($giftConfig[ $gift ])) {
                        foreach ($giftConfig[ $gift ] as $action) {
                            $parameter = [
                                'action'  => $action,
                                'gift_id' => $gift,
                            ];

                            $this->gift->hook($parameter);
                        }
                    }
                }
            }
        }
    }
}

?>
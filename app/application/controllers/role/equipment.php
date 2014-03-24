<?php
if ( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );
class Equipment extends CI_Controller
{
	private $pageName = 'role/equipment';
	private $user = null;
	private $currentRole = null;

	public function __construct()
	{
		parent::__construct();
		$this->load->model( 'utils/check_user', 'check' );
		$this->user = $this->check->validate();
		$this->currentRole = $this->check->check_role();
	}

	public function index()
	{
		//1=武器2=头盔3=护手4=盔甲5=腰带6=鞋子7=戒指8=项链
		$equipment_title = array(
			1	=>	'武器',
			2	=>	'头盔',
			3	=>	'护手',
			4	=>	'盔甲',
			5	=>	'腰带',
			6	=>	'鞋子',
			7	=>	'戒指',
			8	=>	'项链'
		);
		$this->load->model('mequipment');
		$parameter = array(
			'role_id'	=>	$this->currentRole->role['id']
		);
		$result = $this->mequipment->read($parameter);

		$parameter = array(
			'role_id'		=>	$this->currentRole->role['id'],
			'is_equipped'	=>	1
		);
		$e = $this->mequipment->read($parameter);
		$equipped = array();
		foreach($e as $item)
		{
			$equipped[$item['position']] = $item;
		}

		$data = array(
			'equipment_title'	=>	$equipment_title,
			'equipped'			=>	$equipped,
			'equipments'		=>	$result
		);

		$this->load->model( 'utils/render' );
		$this->render->render( $this->pageName, $data );
	}

	public function equip($id)
	{
		if(!empty($id))
		{
			$this->load->model('mequipment');
			$parameter = array(
				'id'		=>	$id,
				'role_id'	=>	$this->currentRole->role['id']
			);
			$result = $this->mequipment->read($parameter);
			if(!empty($result))
			{
				$result = $result[0];
				$position = $result['position'];
				$parameter = array(
					'role_id'		=>	$this->currentRole->role['id'],
					'position'		=>	$position,
					'is_equipped'	=>	1
				);
				$result = $this->mequipment->read($parameter);
				if(!empty($result))
				{
					$result = $result[0];
					$parameter = array(
						'is_equipped'	=>	0
					);
					$this->mequipment->update($result['id'], $parameter);
				}

				$parameter = array(
					'is_equipped'	=>	1
				);
				$this->mequipment->update($id, $parameter);

				redirect('role/equipment');
			}
			else
			{
				showMessage( MESSAGE_TYPE_ERROR, 'EQUIPMENT_ID_NOT_EXIST', '', 'role/equipment', true, 5 );
			}
		}
		else
		{
			showMessage( MESSAGE_TYPE_ERROR, 'EQUIPMENT_ID_NO_PARAM', '', 'role/equipment', true, 5 );
		}
	}

	public function unequip($id)
	{
		if(!empty($id))
		{
			$this->load->model('mequipment');
			$parameter = array(
				'id'		=>	$id,
				'role_id'	=>	$this->currentRole->role['id']
			);
			$result = $this->mequipment->read($parameter);
			if(!empty($result))
			{
				$parameter = array(
					'is_equipped'	=>	0
				);
				$this->mequipment->update($id, $parameter);

				redirect('role/equipment');
			}
			else
			{
				showMessage( MESSAGE_TYPE_ERROR, 'EQUIPMENT_ID_NOT_EXIST', '', 'role/equipment', true, 5 );
			}
		}
		else
		{
			showMessage( MESSAGE_TYPE_ERROR, 'EQUIPMENT_ID_NO_PARAM', '', 'role/equipment', true, 5 );
		}
	}
}

?>
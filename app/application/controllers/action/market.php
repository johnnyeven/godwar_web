<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Market extends CI_Controller
{
	private $pageName = 'action/market';
	private $user = null;
	private $currentRole = null;

	public function __construct()
	{
		parent::__construct ();
		$this->load->model ( 'utils/check_user', 'check' );
		$this->user = $this->check->validate ();
		$this->currentRole = $this->check->check_role ();
	}

	public function index()
	{

	}

	public function sell($id)
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
				$this->pageName = 'action/market_sell';

				$result = $result[0];
				$data = array(
					'equipment'	=>	$result
				);

				$this->load->model( 'utils/render' );
				$this->render->render( $this->pageName, $data );
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
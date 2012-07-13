<?php
class AccountsController extends AppController
{

    public function index()
    {

        $this->set('title_for_layout','Cuentas');

        $accounts = $this->Account->find('all',array(
            'conditions'=> array(
                'Account.user_id'=>$this->Auth->user('id'),
            ),
            'order'=> array('default DESC','created DESC')
        ));

        foreach($accounts as &$account){
            $this->Account->id = $account['Account']['id'];
            $account['Account']['amount'] = $this->Account->getActualAmount();
        } unset ($account);

        $this->set(compact('accounts'));

    }

	public function add($accountId = null)
	{

        $this->set('title_for_layout','Nueva cuenta');

		if (!empty($this->request->data)) {

			$success = true;

			$this->request->data['Account']['user_id'] = $this->Auth->user('id');

            if(empty($this->request->data['Account']['entity_name']))
                unset($this->request->data['Account']['entity_name']);

            if(isset($this->request->data['Movement']['amount'])){
                $this->request->data('Movement.user_id',$this->Auth->user('id'));
                //$this->request->data('Movement.name','Creación de cuenta');
                $this->request->data('Movement.date',date('Y-m-d'));
                $this->request->data('Movement.confirmed',true);
                $this->request->data('Movement.type','absolute');
            }

			$success &= $this->Account->saveAssociated($this->request->data['Account']);

            if($success && $this->request->data['Account']['default']==true){
                $success &= $this->Account->updateAll(
                    array('default'=>false),
                    array(
                        'Account.id !='=> $this->Account->id,
                        'Account.user_id'=> $this->Auth->user('id')
                    )
                );
            }

			if($success) {
				$this->Session->setFlash(__('The account has been saved'));
				$this->redirect('/users/index');
			} else {
				$this->Session->setFlash(__('The account could not be saved. Please, try again.'));
			}
		} else if(!empty($accountId)) {
            $this->request->data = $this->Account->find('first',array(
                'conditions'=>array(
                    'Account.id'=> $accountId,
                    'Account.user_id'=> $this->Auth->user('id')
                ),
                'fields'=> array('id','name','entity_name'),
                'contain'=> array()
            ));
        }

	}

    public function edit($accountId)
    {
        $this->add($accountId);
        $this->render('add');
    }

    function edit_movement($movementId)
    {
        $this->movement($movementId);
        $this->render('movement');
    }

	public function movements()
	{

		$conditions = array(
			'Account.user_id'=> $this->Auth->user('id'),
		);

		if(!empty($accountId))
			$conditions['Movement.account_id'] = $accountId;

		$movements = $this->Account->Movement->find('all',array(
			'conditions' => $conditions,
			'order'=> 'Movement.created DESC',
			'contain'=> array('Account.name'),
			'limit'=>10
		));

		$this->set(compact('movements'));

	}

    public function delete_movement($movementId)
    {
        $this->Account->Movement->id = $movementId;
        $account = $this->Account->Movement->read('user_id');

        if($account === false)
            throw new Exception('Movimiento no existente');
        if($this->Account->Movement->data['Movement']['user_id'] != $this->Auth->user('id'))
            throw new AclException('No tienes acceso a este elemento');

        $this->Account->Movement->delete($movementId,true);

    }

	public function delete($accountId)
	{

		$this->Account->id = $accountId;
		$account = $this->Account->read('user_id');

		if($account === false)
			throw new Exception('Cuenta no existente');
		if($this->Account->data['Account']['user_id'] != $this->Auth->user('id'))
			throw new AclException('No tienes acceso a este elemento');

		$this->Account->delete($accountId,true);

	}

    public function view($id)
    {

        $account = $this->Account->find('first',array(
            'conditions'=> array(
                'Account.id'=> $id
            ),
            'contain'=> array(
                'LastMovement'=>array('order'=>'date DESC, id DESC'),
            )
        ));

        $this->Account->id = $id;
        $account['Account']['amount'] = $this->Account->getActualAmount();

        $this->set(compact('account','actualAmount'));

    }

    public function confirm_movement($movementId){

        $this->Account->Movement->id = $movementId;
        $movement = $this->Account->Movement->read(array('user_id','date'));

        if($movement === false)
            throw new Exception('Cuenta no existente');
        if($movement['Movement']['user_id'] != $this->Auth->user('id'))
            throw new AclException('No tienes acceso a este elemento');

        $this->Account->Movement->set('confirmed',true);

        if($movement['Movement']['date']==null)
            $this->Account->Movement->set('date',date('Y-m-d'));

        $success = $this->Account->Movement->save();

        if($success) {
            $this->Session->setFlash('Movimiento guardado');
            $this->redirect($this->referer());
        } else {
            $this->Session->setFlash('Error al guardar el movimiento');
        }

    }

    function change_amount($accountId)
    {
        $account = $this->Account->find('first',array(
            'fields'=> array('id','name','user_id'),
            'conditions'=> array(
                'Account.id'=> $accountId
            ),
            'contain'=> array()
        ));
        if(!empty($this->request->data)) {

            $this->Account->id = $accountId;

            $this->request->data = array_merge($this->request->data,array(
                'Movement'=> array(
                    'amount'=> $this->request->data['Movement']['amount'],
                    'account_id'=> $account['Account']['id'],
                    'user_id'=> $this->Auth->user('id'),
                    //'name'=> __('Actualización'),
                    'date'=> date('Y-m-d'),
                    'confirmed'=> true,
                    'type'=> 'absolute'
                )
            ));

            $success = $this->Account->Movement->save($this->request->data);
            if($success) {

                $this->Session->setFlash(__('Cantidad en cuenta actualizada'));
                $this->redirect(array('action'=>'view',$accountId));

            } else {

                $this->Account->Movement->validationErrors;
                $this->Session->setFlash(__('Error al guardar el movimiento'));
            }

        } else {
            $this->Account->id = $accountId;
            $this->request->data['Movemenet']['amount'] = $this->Account->getActualAmount();
        }

        $this->set(compact('account'));

    }

    function set_default($id){
        $this->Account->updateAll(
            array('default'=>false),
            array('Account.user_id'=> $this->Auth->user('id'))
        );
        $this->Account->id = $id;
        $this->Account->set('default',true);
        $this->Account->save();
        $this->redirect($this->referer());

    }


}
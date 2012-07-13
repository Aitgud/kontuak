<?php
class UsersController extends AppController
{

    public function beforeFilter(){
        parent::beforeFilter ();
        $this->Auth->allow('add');
    }

	public function index(){

        $this->set('title_for_layout','Página principal');

        $date = new DateTime();
        $date->sub(new DateInterval('P4D'));
        $planning = $this->User->Account->planify(14,$date);

		$user = $this->User->find('first',array(
			'conditions'=>array(
				'User.id'=> $this->Auth->user('id')
			),
			'fields'=> array('id'),
			'contain'=> array(
				'Account'=>array('id','name'),
                'Movement'=>array(
                    'Tag.name',
                    'Account.name',
                    'limit'=>10
                ),
                'LastMovement'=> array(
                    'Account.name',
                    'Tag.name',
                    'order'=> 'date DESC',
                    'limit'=>10
                ),
                'PendingMovement' => array(
                    'Tag.name',
                    'Account.name',
                    'limit'=>10
                ),
                'UnconfirmedMovement'=> array(
                    'Tag.name',
                    'Account.name',
                    'limit'=>10
                ),
                'ComingMovement'=> array(
                    'Tag.name',
                    'Account.name',
                    'order'=> 'date ASC',
                    'limit'=>10
                ),
			),
		));

        foreach($user['Account'] as &$account){
            $this->User->Account->id = $account['id'];
            $account['amount'] = $this->User->Account->getActualAmount();
            $account['estimated_amount'] = $this->User->Account->getActualAmount(true);
        } unset ($account);

        $accounts = $this->User->Account->find('list',array(
            'conditions'=>array('user_id'=>$this->Auth->user('id'))
        ));


        $this->set('noDrawHeader',true);
		$this->set(compact('user','planning','accounts'));

	}

    public function login() {

        if($this->Auth->user()) {
            $this->redirect($this->Auth->loginRedirect);
        }

        if($this->Cookie->read('userLoginData')){
            $this->request->data = $this->Cookie->read('userLoginData');
            if($this->Auth->login()) $this->redirect($this->Auth->loginRedirect);
            else $this->Cookie->delete('userLoginData');
        }


        if ($this->request->data) {
            if(!empty($this->request->data['action_add'])){

                $success = $this->add();

                if ($success) {
                    $this->keepConnected();
                    $this->request->data['User'] = array_merge($this->request->data['User'], array('id' => $this->User->id));
                    $this->Auth->login($this->request->data['User']);
                    $this->redirect($this->Auth->loginRedirect);
                } else {
                    $this->Session->setFlash(__('Ha habido un problema al crear la cuenta, revisa los datos.'));
                }

            } else if(!empty($this->request->data['action_login'])){
                if ($this->Auth->login()) {
                    $this->keepConnected();
                    $this->redirect($this->Auth->redirect());
                } else {
                    $this->Session->setFlash(__('Invalid username or password, try again'));
                }

            }
        }
    }

    /**
     * Guarda los datos de login que están en $this->request en Cookie
     */
    private function keepConnected()
    {
        if($this->request->data['User']['keepConnected']){
            unset($this->request->data['User']['keepConnected'],$this->request->data['action_login']);
            $this->Cookie->write('userLoginData',$this->request->data);
        }
    }

    public function logout() {
        $this->Cookie->delete('userLoginData');
        $this->redirect($this->Auth->logout());
    }

    private function add()
    {

        $success = false;

        if (!empty($this->request->data)) {

            $this->request->data['Account'] = array(
                array('name'=> __('Cuenta principal')),
                array('name'=>__('Dinero en metálico'))
            );

            $success = $this->User->saveAssociated($this->request->data);

        }

        return (bool)$success;

    }

}
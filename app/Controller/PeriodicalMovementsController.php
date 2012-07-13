<?php
class PeriodicalMovementsController extends AppController
{

    function index()
    {
        $movements = $this->PeriodicalMovement->find('all',array(
            'conditions'=> array(
                'PeriodicalMovement.user_id'=>$this->Auth->user('id')
            )
        ));

        $this->set(compact('movements'));
    }

    function add($accountId=null)
    {

        if(!empty($accountId)){

            $account = $this->PeriodicalMovement->Account->find('first',array(
               'conditions'=>array(
                   'Account.id'=> $accountId,
               )
            ));

            if(empty($account)) {
                throw new Exception('La cuenta no existe');
            } else if ($account['Account']['user_id'] != $this->Auth->user('id')) {
                throw new AclException('No tienes autorización para esa cuenta');
            }

        }

        if ($this->request->is('post')) {

            $success = true;

            $account = $this->PeriodicalMovement->Account->find('first',array(
                'conditions'=>array(
                    'Account.id'=> $this->request->data('PeriodicalMovement.account_id'),
                )
            ));

            if(empty($account)) {
                throw new Exception('La cuenta no existe');
            } else if ($account['Account']['user_id'] != $this->Auth->user('id')) {
                throw new AclException('No tienes autorización para esa cuenta');
            }

            $this->request->data('PeriodicalMovement.user_id',$this->Auth->user('id'));

            if(!$this->request->data('PeriodicalMovement.have_end_date'))
                $this->request->data('PeriodicalMovement.end_date',null);

            $this->PeriodicalMovement->create();
            $success &= $this->PeriodicalMovement->save($this->request->data['PeriodicalMovement']);

            if($success) {
                $this->Session->setFlash(__('Periodo creado correctamente'));
                $this->redirect(array('action'=>'index'));
            } else {
                $this->Session->setFlash(__('Error al crear el periodo.'));
            }

        } else {

            if(empty($accountId)) {

                $accounts = $this->PeriodicalMovement->Account->find('list',array(
                    'conditions'=> array(
                        'Account.user_id'=> $this->Auth->user('id')
                    )
                ));

                if(count($accounts)==1)
                    $this->request->data('PeriodicalMovement.account_id',key($accounts));
                else
                    $this->set(compact('accounts'));
            } else {
                $this->request->data('PeriodicalMovement.account_id',$accountId);
            }
        }

    }
}
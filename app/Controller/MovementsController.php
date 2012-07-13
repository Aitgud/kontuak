<?php
/**
 *
 */
class MovementsController extends AppController
{

    public function add($movementId = null)
    {

        if(!empty($this->request->params['named']['account_id'])) {

            $account = $this->Movement->Account->find('first',array(
                'conditions'=>array(
                    'Account.id' => $this->request->params['named']['account_id'],
                )
            ));

            if (empty ($account)) {
                throw new Exception('Número de cuenta incorrecto');
            } if ($account['Account']['user_id'] != $this->Auth->user('id')) {
                throw new AclException('No tienes acceso a esta cuenta');
            }

        }

        if (!empty($this->request->data)) {

            $success = true;

            if($this->request->data('Movement.amount')==false)
                $this->request->data('Movement.amount',null);
            if($this->request->data('Movement.min_amount')==false)
                $this->request->data('Movement.min_amount',null);
            if($this->request->data('Movement.max_amount')==false)
                $this->request->data('Movement.max_amount',null);

            if($this->request->data['Movement']['date_empty']){
                $this->request->data['Movement']['date'] = array(
                    'year'=>null,
                    'month'=>null,
                    'day'=>null
                );
            }

            if(!empty($account))
                $this->request->data('Movement.account_id',$account['Account']['id']);


            $this->request->data('Movement.user_id',$this->Auth->user('id'));


            $success &= $this->saveTags();

            if($success)
                $success &= $movement = $this->Movement->save($this->request->data);

            if($success) {
                $this->Session->setFlash('Movimiento guardado');
                $this->redirect(array('controller'=>'users','action'=>'index'));
            } else {
                $this->Session->setFlash('Error al guardar el movimiento');
            }

        } elseif (!empty($movementId)) {

            $movement = $this->Movement->find('first',array(
                'conditions'=>array(
                    'Movement.id'=>$movementId
                ),
                'contain'=> array(
                    'Tag'
                )
            ));

            $movement['Movement']['tags'] = array();
            foreach($movement['Tag'] as $tag){
                $movement['Movement']['tags'][] = $tag['name'];
            }
            $movement['Movement']['tags'] = implode(', ',$movement['Movement']['tags']);

            $this->request->data = $movement;

        } else {

            if(!empty($account))
                $this->request->data('Movement.account_id',$account['Account']['id']);

        }

        $accounts = $this->Movement->Account->find('list',array(
            'conditions'=>array(
                'user_id'=>$this->Auth->user('id')
            ),
        ));

        $defaultAccountId = $this->Movement->Account->field('id',array(
            'Account.user_id'=> $this->Auth->user('id'),
            'Account.default'=> true
        ));

        $this->set(compact('accounts','defaultAccountId'));

    }

    private function saveTags()
    {
        try {
            $success = true;
            $this->Movement->begin();
            $this->request->data['Tag'] = array();

            $tags = explode(',',$this->request->data['Movement']['tags']);

            foreach($tags as $i=>&$tag){
                $tag = trim($tag);
                if(empty($tag)) unset($tags[$i]);
            } unset($tag);

            $existingTags = array_flip($this->Movement->Tag->find('list',array(
                'conditions'=>array(
                    'user_id'=> $this->Auth->user('id'),
                    'name'=> $tags
                )
            )));

            foreach($tags as $tag) {

                if(isset($existingTags[trim($tag)]))
                    $this->request->data['Tag'][] = $existingTags[trim($tag)];
                else {

                    $this->Movement->Tag->create();
                    $data = array(
                        'Tag'=> array(
                            'name'=> trim($tag),
                            'user_id'=> $this->Auth->user('id'),
                        ),
                    );
                    $success &= $this->Movement->Tag->save($data);
                    $this->request->data['Tag'][] = $this->Movement->Tag->id;

                }
            }

            if($success) $this->Movement->commit();
            else $this->Movement->rollback();

            return $success;
        } catch(Exception $e) {
            return false;
        }

    }

    function edit($movementId)
    {
        $this->add($movementId);
        $this->render('add');
    }


}
<?php

App::uses('AuthComponent', 'Controller/Component');

class User extends AppModel
{

    public $name = 'User';

	public $actsAs = array('Containable');

	public $hasMany = array(
		'Account',
        'PeriodicalMovement',
        'Movement',
        'LastMovement' => array(
            'className' => 'Movement',
            'conditions' => array(
                'confirmed'=> true,
                'date NOT'=> NULL,
                'date <= CURDATE()',
            )
        ),
        'PendingMovement' => array(
            'className' => 'Movement',
            'conditions' => array(
                'date'=> NULL,
            )
        ),
        'UnconfirmedMovement'=> array(
            'className'=>'Movement',
            'conditions'=> array(
                'confirmed'=> false,
                'date NOT'=> NULL,
                'date <= CURDATE()',
            )
        ),
        'ComingMovement' => array(
            'className'=>'Movement',
            'conditions'=> array(
                'date NOT'=> NULL,
                'date > CURDATE()',
            )
        )
	);

    public $validate = array(
        'email' => array(
            'required' => array(
                'rule' => Validation::RULE_NOT_EMPTY,
                'message' => 'Necesario'
            ),
            'isUnique' => array(
                'rule' => Validation::RULE_IS_UNIQUE,
                'message' => 'Dirección de correo ocupada'
            ),
            'email'=> array(
                'rule'=> array('email',true),
                'message'=> 'La dirección facilitada parece incorrecta'
            )
        ),
        'password' => array(
            'required' => array(
                'rule' => array(Validation::RULE_NOT_EMPTY),
                'message' => 'Necesario'
            ),
            'minLength'=> array(
                'rule'=>array('minLength',6),
                'message'=> 'Por tu seguridad, debe constar de un mínimo de 8 caracteres e incluir letras y números',
                'escape'=> false
            )
        ),
    );

    public function beforeSave() {
        if (isset($this->data[$this->alias]['password'])) {
            $this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
        }
        return true;
    }


}
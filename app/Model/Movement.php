<?

class Movement extends AppModel
{

    public $actsAs = array('Containable');

    var $name = 'Movement';

	var $belongsTo = array(
		'Account'
	);

    var $hasAndBelongsToMany = array(
        'Tag'
    );

    public $validate = array(
        'amount'=> array(
            'notEmpty'=> array(
                'rule'=>'notEmpty',
                'message'=> 'Campo obligatorio'
            )
        ),
        'name'=> array(
            'notEmpty'=> array(
                'rule'=>'notEmpty',
                'message'=> 'Campo obligatorio'
            )
        )
    );

}
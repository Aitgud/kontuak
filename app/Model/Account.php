<?php
class Account extends AppModel
{

    public $actsAs = array('Containable');

    const FORMAT_SQL_DATE = 'Y-m-d';
    const FORMAT_SQL_TIME = 'h:i:s';
    const FORMAT_SQL_DATETIME = 'Y-m-d h:i:s';

    var $name = 'Account';

    var $validate = array(
        'amount'=> array(
            'notEmpty'=>array(
                'rule'=>'notEmpty',
                'message'=>'Campo obligatorio'
            )
        )
    );

	var $hasMany = array(
		'Movement'=> array(
			'dependent'=> true
		),
        'LastMovement' => array(
            'className'=> 'Movement',
            'conditions' => array(
                'confirmed'=>true,
                'date NOT'=>null,
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
	);

    public function getActualAmount($estimated = false)
    {
        return $this->getAmountAtDate(new DateTime(),$estimated);
    }

    public function getAmountAtDate(DateTime $date, $estimated = false)
    {

        $userId = AuthComponent::user('id');
        $dateText = $date->format(self::FORMAT_SQL_DATE);

        $conditions = array(
            'user_id' => $userId,
            'date <=' => $dateText,
            'type ='=> 'relative'
        );

        $firstAmount = $this->Movement->find('first',array(
            'fields'=> array('id','amount','date'),
            'conditions'=> array(
                'date <='=> $dateText,
                'type'=> 'absolute',
            ),
            'order'=> 'date DESC, id DESC',
            'contain'=> array(),
        ));

        if(!empty($firstAmount)){
            $total = $firstAmount['Movement']['amount'];
            $conditions['date >='] = $firstAmount['Movement']['date'];
        } else {
            $total = 0;
        }


        if(!empty($this->id)) $conditions['account_id'] = $this->id;

        if(!$estimated) array_unshift($conditions,'confirmed = TRUE');

        $amount = $this->Movement->find('first',array(
            'fields'=> array('SUM(amount)'),
            'conditions'=> $conditions,
            'contain'=> array(),
        ));

        $total += (float) current(current($amount));

        // si es estimado buscamos los máximos de los movimientos que tienen el amount a null para sumarlo
        if($estimated) {

            $conditions[] = array('amount IS NULL');

            $maxAmounts = $this->Movement->find('first',array(
                'fields'=> array('SUM(max_amount)'),
                'conditions'=> $conditions,
                'contain'=> array(),
                'date >='=> $firstAmount['Movement']['date'],
                'type ='=> 'relative'
            ));

            $total += (float) current(current($maxAmounts));

        }

        return (float) $total;

    }

    /**
     * @param int $days
     * @param DateTime|null $fromDay
     * @return array
     */
    public function planify($days, $fromDay = null){

        if($fromDay==null) $fromDay = new DateTime();

        $oneDay = new DateInterval('P1D');

        $day = $fromDay;

        $i = 0;
        $planning = array();
        while($i<=$days) {
            $planning[] = array(
                'day' => clone $day,
                'real' => $this->getAmountAtDate($day),
                'estimated' => $this->getAmountAtDate($day,true),
                'movements' => $this->Movement->find('all',array(
                    'fields'=> array('id','name','amount','min_amount','max_amount','confirmed','priority','type'),
                    'conditions'=> array('date'=> $day->format('Y-m-d')),
                    'contain'=> array()
                ))
            );
            $day->add($oneDay);
            $i++;
        }

        return $planning;

    }

}
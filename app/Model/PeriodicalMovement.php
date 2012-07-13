<?php
class PeriodicalMovement extends AppModel
{

    var $name = 'PeriodicalMovement';

    var $belongsTo = array(
        'Account'
    );
}
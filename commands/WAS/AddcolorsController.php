<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;
use yii\console\Controller;
use app\models\ProductColor;
use app\models\Color;

class AddcolorsController extends Controller{

	public $filename;
    
    public function options()
    {
        return ['filename'];
    }
    
    public function optionAliases()
    {
        return ['f' => 'filename'];
    }	

    public function actionIndex(){
        $aColors = [
            'Коралловый',
            'Коричневый',
            'Красный',
            'Кремовый',            
            'Лиловый, фиолетовый',
            'Оранжевый, персиковый',
            'Фуксия',
            'Розовый',
            'Серый',            
            'Серебристый',
            'Синий',
            'Сиреневый',
            'Фиолетовый',     
            'Черный',
            'Ментол'
        ];
        foreach ($aColors as $colorName) {
            $color = new Color();
            $color->name = $colorName;
            $color->code = '#ffd966';
            $color->save();
        }
    }

}

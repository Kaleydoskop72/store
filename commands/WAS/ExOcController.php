<?php


namespace app\commands;
use yii\console\Controller;
use app\models\ProductColor;

class ExOcController extends Controller{

    

    public function actionIndex($mode){
        switch ('category') {
            case 'value':
                $this->exportCategory();
                break;
            
            default:
                # code...
                break;
        }
    }


}


?>
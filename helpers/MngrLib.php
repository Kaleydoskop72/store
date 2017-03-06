<?php 

namespace app\helpers;
use Yii;

class MngrLib{


    public static function printPrc($i, $_countAll=0){
    	$res = "";
        static $countAll = 0;
        static $prcOld = 0;
        if ($_countAll != 0){
            $countAll = $_countAll;
        }
        if ($countAll > 0){
	        $prc = (int) ($i*100/$countAll);
	        if ($prc != $prcOld){
	            $res = $prc."%\n";
	        }
	        $prcOld = $prc;        	
    	}
    	return $res;
    }


}



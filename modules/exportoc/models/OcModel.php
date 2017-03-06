<?php

namespace app\modules\exportoc\models;

use Yii;
use app\helpers\Filesystem;

class OcModel extends \yii\db\ActiveRecord{
 

    public function delFromTable($className, $key, $val){
        $className = "app\\modules\\exportoc\\models\\".$className;
        foreach ($className::findAll([$key => $val]) as $cc){
            $cc->delete();
        }       
    }


    public function delFromTableAll($table){
        $className = "app\\modules\\exportoc\\models\\".$table;
        $list = $className::find()->all();
        foreach ($list as $r) {
            $r->delete();
        }
    }


    public function photoCheckDir($path){ 
        Filesystem::chkDir($path);
        // $isOcSiteFTP = \Yii::$app->params['isOcSiteFTP'];
        // if (!file_exists($path)){
        //     echo "mkdir ".$path."\n";
        //     if ($isOcSiteFTP){
        //         echo "photoCheckDir: ".$path."\n";
        //     }else{
        //         mkdir($path);
        //     }            
        // }
    }    


    public function copyImage($fileSrc, $fileDst, $isWaterMark){
        // $isOcSiteFTP = \Yii::$app->params['isOcSiteFTP'];
        if ($isWaterMark){
            $watermarkDir = \Yii::$app->params['dirImage'];
            $watermark = imagecreatefrompng($watermarkDir.'watermark.png');
            $watermark_width = imagesx($watermark);
            $watermark_height = imagesy($watermark);

            $image_path = $fileSrc;
            //echo "handle: ".$image_path."\n";
            $image = imagecreatefromjpeg($image_path);
            $size = getimagesize($image_path);

            $dest_x = $size[0] - $watermark_width - 5;
            $dest_y = $size[1] - $watermark_height - 5;

            $dest_x = $size[0] - $watermark_width - 5;
            $dest_y = $size[1] - $watermark_height - 5;
            imagealphablending($image, true);
            imagealphablending($watermark, true);

            imagecopy($image, $watermark, $dest_x, $dest_y, 0, 0, $watermark_width, $watermark_height);
            $tmp = \Yii::$app->params['dirTmp'];
            imagejpeg($image, $tmp);
            Filesystem::copy($tmp, $fileDst);
            // if ($isOcSiteFTP){
            //     $tmp = \Yii::$app->params['dirTmp'];
            //     imagejpeg($image, $tmp);
            //     echo "copyImage: ".$tmp."\n";
            // }else{
            //     imagejpeg($image, $fileDst);
            // }
            imagedestroy($image);
            imagedestroy($watermark);
        }else{
            Filesystem::copy($fileSrc, $fileDst);
            // $cmd = "cp ".$fileSrc." ".$fileDst;
            // if ($isOcSiteFTP){
            //     echo $cmd."\n";
            // }else{
            //     system($cmd);
            // }
        }
    }


}

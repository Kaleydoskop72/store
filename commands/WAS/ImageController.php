<?php



namespace app\commands;
use yii\console\Controller;
use app\models\ProductColor;

class ImageController extends Controller{


    public function convert($fileSrc, $fileDst){
        $cmd = 'convert '.$fileSrc;
        $cmd .= ' -filter Triangle -define filter:support=2 -thumbnail 1024';
        $cmd .= '  -unsharp 0.25x0.08+8.3+0.045 -dither None -posterize 136 -quality 82 -define jpeg:fancy-upsampling=off -define png:compression-filter=5 -define png:compression-level=9 -define png:compression-strategy=1 -define png:exclude-chunk=all -interlace none -colorspace sRGB';
        $cmd .= ' '.$fileDst;
        echo $cmd."\n";
        system($cmd);
    }

    public function mkConvert(){
        if ($handle = opendir(\Yii::$app->params['dirImage'])){
            while ($dirname = readdir($handle)){
                if ($dirname === '.' || $dirname === '..' || $dirname === 'photoData.txt'){
                    continue;
                }
                echo $dirname."\n";
                if ($handle2 = opendir(\Yii::$app->params['dirImage'].'/'.$dirname)){
                    while ($filename = readdir($handle2)){
                        if ($filename === '.' || $filename === '..'){
                            continue;
                        }
                        if (preg_match('/original_(.+)/', $filename, $mathes)){
                            $fileSrc = \Yii::$app->params['dirImage'].'/'.$dirname.'/'.$filename;
                            $fileDst = \Yii::$app->params['dirImage'].'/'.$dirname.'/'.$mathes[1];
                            //echo "...".$filename.' - '.filesize($fileSrc)."\n";
                            // echo $fileSrc."\n";
                            // echo $fileDst."\n";
                            $this->convert($fileSrc, $fileDst);
                        }
                    }
                    closedir($handle2);
                }
            }
            closedir($handle);
        }  
    }    


    public function mkCopy(){
        if ($handle = opendir(\Yii::$app->params['dirImage'])){
            while ($dirname = readdir($handle)){
                if ($dirname === '.' || $dirname === '..' || $dirname === 'photoData.txt'){
                    continue;
                }
                //echo $dirname."\n";
                if ($handle2 = opendir(\Yii::$app->params['dirImage'].'/'.$dirname)){
                    while ($filename = readdir($handle2)){
                        if ($filename === '.' || $filename === '..'){
                            continue;
                        }
                        $fileSrc = \Yii::$app->params['dirImage'].'/'.$dirname.'/'.$filename;
                        $fileDst = \Yii::$app->params['dirImage'].'/'.$dirname.'/original_'.$filename;
                        $fileSize = filesize($fileSrc);
                        if ($fileSize >= 66000){
                            //echo $fileSrc." -> ".$fileDst."\n";
                            rename($fileSrc, $fileDst);
                        }
                    }
                    closedir($handle2);
                }
            }
            closedir($handle);
        }  
    }


    public function actionIndex($mode){  
        switch ($mode) {
            case 'copy':
                $this->mkCopy();
                break;
            case 'convert':
                $this->mkConvert();
                break;                
        }
    }


}




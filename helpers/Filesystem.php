<?php 

namespace app\helpers;
use Yii;

class Filesystem{

    static function getParam(){
		$aParam = [
			'isOcSiteFTP'	=> \Yii::$app->params['isOcSiteFTP'],
			'ftpServer' 	=> \Yii::$app->params['ftpServer'],		
			'ftpUser' 		=> \Yii::$app->params['ftpUser'],
			'ftpPassword' 	=> \Yii::$app->params['ftpPassword'],		
		];
		return $aParam;
	}


	function existDir($ftp, $dir){
		// return true;
	    $origin = ftp_pwd($ftp);
	    
	    if (@ftp_chdir($ftp, $dir)){
	        ftp_chdir($ftp, $origin);
	        return true;
	    }
	    return false;
	}


	public static function chkDir($dir){ 
		// return true;
		$p = self::getParam();
        if ($p['isOcSiteFTP']){
			$conn_id = ftp_connect($p['ftpServer']);
			$login_result = ftp_login($conn_id, $p['ftpUser'], $p['ftpPassword']);
			// echo "dir == ".$dir."\n";
			if (!self::existDir($conn_id, $dir)){
				if (!ftp_mkdir($conn_id, $dir)) {
				 	echo "Не удалось создать $dir на сервере\n";
				}
			}
			ftp_close($conn_id);
        }else{
        	if (!file_exists($dir)){
        		mkdir($dir);
        	}
        }
	}


	public static function copy($srcFile, $dstFile){ 
		// return true;
		$p = self::getParam();
        if ($p['isOcSiteFTP']){
			$conn_id = ftp_connect($p['ftpServer']);
			$login_result = ftp_login($conn_id, $p['ftpUser'], $p['ftpPassword']);
			if ($login_result){
				echo "connect FTP OK!\n";
				echo "src == ".$srcFile."\n";
				echo "dst == ".$dstFile."\n";
				if (!($rc = ftp_put($conn_id, $dstFile, $srcFile, FTP_BINARY))) {
				 	echo "Не удалось загрузить $fileSrc -> $fileDst на сервер\n";
				}
				ftp_close($conn_id);				
			}else{
				echo "No connect FTP!\n";
			}
        }else{
            copy($srcFile, $dstFile);
        }    
	}


	public static function delete($file){ 
		// return true;
		$p = self::getParam();
        if ($p['isOcSiteFTP']){
			$conn_id = ftp_connect($p['ftpServer']);
			$login_result = ftp_login($conn_id, $p['ftpUser'], $p['ftpPassword']);
			if (!ftp_delete($conn_id, $file)) {
			 	echo "Не удалось удалить $file на сервере\n";
			}
			ftp_close($conn_id);
        }else{
            unlink($file);
        }    
	}

}



<?php

namespace app\modules\exportoc\models;

use Yii;
use app\models\Category;
use app\models\Product;
use app\helpers\Filesystem;

class OcCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        //return '{{%oc_category}}';
        return 'oc_category';        
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return \Yii::$app->get('dboc');
    }

    public function getDbConnection(){
        return Yii::app()->dboc;
    }
 

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'top', 'column', 'sort_order', 'status'], 'integer'],
            [['top', 'column', 'status', 'date_added', 'date_modified'], 'required'],
            [['date_added', 'date_modified'], 'safe'],
            [['image'], 'string', 'max' => 255],
        ];
    }


    public function clean(){
        \Yii::$app->dboc->createCommand()->truncateTable('oc_category')->execute();
        \Yii::$app->dboc->createCommand()->truncateTable('oc_category_description')->execute();
        \Yii::$app->dboc->createCommand()->truncateTable('oc_category_filter')->execute();        
        \Yii::$app->dboc->createCommand()->truncateTable('oc_category_path')->execute();
        \Yii::$app->dboc->createCommand()->truncateTable('oc_category_to_layout')->execute();
        \Yii::$app->dboc->createCommand()->truncateTable('oc_category_to_store')->execute();                
    }


    public function addTblProductFilter($p){
        $typeProduct = Category::TYPE_NONE;
        if ($cat = Category::findOne($p->idCategory)){
            $typeProduct = $cat->typeProduct;
        }
        foreach (OcFilter::$filters as $filter) {
            if (in_array($typeProduct, $filter['apply'])){
                if ($fg = OcFilterGroup::find()->where(['type_filter' => $filter['type']])->one()){
                    foreach (OcFilter::find()->where(['filter_group_id' => $fg->filter_group_id])->all() as $fl) {
                        $pf = new OcProductFilter();
                        $pf->product_id = $p->id;
                        $pf->filter_id = $fl->filter_id;
                        $pf->save();
                    }
                }
            }
        }
    }    


    public function exportSimple(){
        //self::clean();
        $list = Category::find()->where(['isWork' => 1, 'isReady' => 1])->all();
        foreach ($list as $c) {
            if ($c->getCount() > 0 && !$c->isProduct){
                echo $c->name."\n";
                if (!($ocCat = OcCategory::findOne($c->id))){
                    $ocCat = new OcCategory();
                }                
                $ocCat->category_id = $c->id;
                $ocCat->parent_id = $c->idParent;
                $ocCat->status = 1;
                $ocCat->top = $c->idParent == 0 ? 1 : 0;
                $ocCat->column = 1;
                $ocCat->countProduct = $c->getCount();
                $ocCat->type_product = $c->typeProduct;                    
                $ocCat->date_added = new \yii\db\Expression("NOW()");
                $ocCat->date_modified = new \yii\db\Expression("NOW()");
                $ocCat->image = '';                
                if ($c->isImage){
                    $dirSrc = self::getPathImageSrc($c->id);
                    $dirDst = self::getPathImageDst($c->id);
                    self::photoCheckDir($dirDst); 
                    $fileSrc = $dirSrc."/0.jpg";
                    $fileDst = $dirDst."/0.jpg";
                    Filesystem::copy($fileSrc, $fileDst);
                    $ocCat->image = self::getPathImageDb($c->id).'/0.jpg';
                }else{
                    $listProducts = Product::find()->where([ 'idCategory' => $c->id, 'isReady' => 1, 'idParent' => 0 ])->all();
                    if (count($listProducts) > 0) {                    
                        $iP = rand(0, count($listProducts)-1);
                        $p = $listProducts[$iP];
                        $ocCat->image = 'data/product/p'.$p->id.'/0.jpg';
                        echo "catImg: ".$c->id." ".'data/product/p'.$p->id.'/0.jpg'."\n";
                    }                    
                }
                if (!$ocCat->save()){
                    echo "not save OcCategory\n";
                    print_r($ocCat->getErrors());
                }

                if (!($OcCatD = OcCategoryDescription::findOne($c->id))){
                    $OcCatD = new OcCategoryDescription();
                }
                $OcCatD->category_id = $c->id;
                $OcCatD->language_id = 1;
                $OcCatD->name = $c->name;
                $OcCatD->title = $c->title;
                // $OcCatD->meta_title = $c->name; 
                $OcCatD->description = $c->description;   
                $OcCatD->meta_description = 'купить '.$c->name." в Тюмени"; 
                $OcCatD->meta_keyword = 'купить, '.$c->name.", Тюмень";                                     
                $OcCatD->save();    
                if (!$OcCatD->save()){
                    echo "not save OcCategoryDescription\n";
                    print_r($OcCatD->getErrors());
                }   
                if (!($urlAlias = OcUrlAlias::find()->where(['keyword' => $c->seoURL])->one())){
                    $urlAlias = new OcUrlAlias();
                }                
                $urlAlias->query = 'category_id='.$c->id;
                $urlAlias->keyword = $c->seoURL;
                $urlAlias->save();

                foreach (OcFilter::$filters as $filter) {
                    if (in_array($c->typeProduct, $filter['apply'])){
                        if ($fg = OcFilterGroup::find()->where(['type_filter' => $filter['type']])->one()){
                            foreach (OcFilter::find()->where(['filter_group_id' => $fg->filter_group_id])->all() as $fl) {
                                if (!($cf = OcCategoryFilter::find()->where(['category_id' => $c->id, 'filter_id' => $fl->filter_id])->one())){
                                    $cf = new OcCategoryFilter();
                                }                                
                                $cf->category_id = $c->id;
                                $cf->filter_id = $fl->filter_id;
                                $cf->save();
                            }
                        }
                    }
                }                

                if (!($OcCat2L = OcCategoryToLayout::find()->where(['category_id' => $c->id, 'store_id' => 0, 'layout_id' => 0])->one())){
                    $OcCat2L = new OcCategoryToLayout();
                }                
                $OcCat2L->category_id = $c->id;
                $OcCat2L->store_id = 0;
                $OcCat2L->layout_id = 0;                                        
                $OcCat2L->save();   
                if (!$OcCat2L->save()){
                    echo "not save OcCategoryToLayout\n";
                    print_r($OcCat2L->getErrors());
                }       

                if (!($OcCat2S = OcCategoryToStore::find()->where(['category_id' => $c->id, 'store_id' => 0])->one())){
                    $OcCat2S = new OcCategoryToStore();
                }    
                $OcCat2S->category_id = $c->id;
                $OcCat2S->store_id = 0;                                 
                $OcCat2S->save();   
                if (!$OcCat2S->save()){
                    echo "not save OcCategoryToStore\n";
                    print_r($OcCat2S->getErrors());
                }       

                if (!($OcCatP = OcCategoryPath::find()->where(['category_id' => $c->id, 'path_id' => $c->id, 'level' => ($c->idParent == 0 ? 0 : 1)])->one())){
                    $OcCatP = new OcCategoryPath();
                }  
                $OcCatP->category_id = $c->id;
                $OcCatP->path_id = $c->id;  
                $OcCatP->level = $c->idParent == 0 ? 0 : 1;
                $OcCatP->save();
                if (!$OcCatP->save()){
                    echo "not save OcCategoryPath\n";
                    print_r($OcCatP->getErrors());
                }           

                if ($c->idParent != 0){
                    if (!($OcCatP = OcCategoryPath::find()->where(['category_id' => $c->id, 'path_id' => $c->idParent, 'level' => 0])->one())){
                        $OcCatP = new OcCategoryPath();
                    }                      
                    $OcCatP->category_id = $c->id;
                    $OcCatP->path_id = $c->idParent;    
                    $OcCatP->level = 0;
                    $OcCatP->save();
                    if (!$OcCatP->save()){
                        echo "not save OcCategoryPath\n";
                        print_r($OcCatP->getErrors());
                    }                   
                }     
            }                                              
        }
    }


    public function getPathImageSrc($id){
        return \Yii::$app->params['dirImage']."/c".$id;
    }


    public function getPathImageDst($id){
        return \Yii::$app->params['ocDirImage']."/c".$id;
    }    


    public function getPathImageDb($id){
        return "data/product/c".$id;
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


    public function exportFast($mode){
        $fCat = fopen("./cat.csv", "w");
        $list = Category::find()->where(['isWork' => 1, 'isReady' => 1])->all();
        foreach ($list as $c){
            if ($c->getCount() > 0){
                $str  = $c->id.";";
                $str .= $c->name.";";
                $str .= $c->idParent.";";
                $str .= $c->typeProduct.";";
                $str .= (count($listProducts) > 0) ? $p->id.";" : "";
                $str .= ";";

                foreach (OcFilter::$filters as $filter) {
                    if (in_array($c->typeProduct, $filter['apply'])){
                        if ($fg = OcFilterGroup::find()->where(['type_filter' => $filter['type']])->one()){
                            foreach (OcFilter::find()->where(['filter_group_id' => $fg->filter_group_id])->all() as $fl) {
                                $cf = new OcCategoryFilter();
                                $cf->category_id = $c->id;
                                $cf->filter_id = $fl->filter_id;
                                $cf->save();
                            }
                        }
                    }
                }                

                $OcCat2L = new OcCategoryToLayout();
                $OcCat2L->category_id = $c->id;
                $OcCat2L->store_id = 0;
                $OcCat2L->layout_id = 0;                                        
                $OcCat2L->save();   
                if (!$OcCat2L->save()){
                    echo "not save OcCategoryToLayout\n";
                    print_r($OcCat2L->getErrors());
                }       

                $OcCat2S = new OcCategoryToStore();
                $OcCat2S->category_id = $c->id;
                $OcCat2S->store_id = 0;                                 
                $OcCat2S->save();   
                if (!$OcCat2S->save()){
                    echo "not save OcCategoryToStore\n";
                    print_r($OcCat2S->getErrors());
                }       

                $OcCatP = new OcCategoryPath();
                $OcCatP->category_id = $c->id;
                $OcCatP->path_id = $c->id;  
                $OcCatP->level = $c->idParent == 0 ? 0 : 1;
                $OcCatP->save();
                if (!$OcCatP->save()){
                    echo "not save OcCategoryPath\n";
                    print_r($OcCatP->getErrors());
                }           

                if ($c->idParent != 0){
                    $OcCatP = new OcCategoryPath();
                    $OcCatP->category_id = $c->id;
                    $OcCatP->path_id = $c->idParent;    
                    $OcCatP->level = 0;
                    $OcCatP->save();
                    if (!$OcCatP->save()){
                        echo "not save OcCategoryPath\n";
                        print_r($OcCatP->getErrors());
                    }                   
                }     
            }                                              
        }        
    }


    public function fill($mode){
        switch ($mode) {
            case 'exportFast':
                self::exportFast();
                break;
            case 'exportSimple':
                self::exportSimple();
                break;                
        }
    }

}

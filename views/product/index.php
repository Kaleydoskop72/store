
<script>
    function changeDropDown($id) {
        $v = $('#ddId'+$id).val();
        //$v = $('#ddId46 option:selected').val();
        //alert('---> '+$v); 
        $.ajax({
          url: '/product/update-kit-category/?id='+$id+'&idKitCategory='+$v,
          success: function(data) {
            //$.pjax.reload({container:"#grid"});  
             //$('#w0').html(data);
        }
        });      
        //$.pjax.reload({container:"#grid"});  
        // $.ajax({
        //   url: '/product/index/?id=0',
        //   success: function(data) {
        //     //$.pjax.reload({container:"#grid"});  
        //      $('#w0').html(data);
        //   }
        // });                           
    }
</script>


<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\Product;
use app\models\ProductStore;
use app\models\Store;
use app\models\Color;
use app\models\Design;
use app\models\KitCategory;
use app\models\Category;
use app\models\Manufacturer;
use app\models\Settings;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Товары';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="product-index">

    <h1 id="q123"><?= Html::encode($this->title) ?></h1>
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить товар', ['create'], ['class' => 'btn btn-success']) ?>
    </p>





<?php 


    function badProduct2Html($errors){
        $aKeyStr = [
            'badPhoto' => 'фото',
            'badName' => 'название',
            'badSku' => 'артикул',
            'badId1C' => '1C',
            'badCategory' => 'категория',
            'badManufacturer' => 'производитель',
            'badPrice' => 'цены',
            'badColorName' => 'название цвета',
            'badContent' => 'состав',
            'badCatKit' => 'категория набора',  
            'badSize' => 'размер',
            'badColorCount' => 'количество цвтов',
            'badCanva' => 'канва',
            'badTech' => 'техника',  
            'chkChilds' => 'потомки',                  
        ];
        $str = Html::beginTag('table', ['class' => 'table']);                                    
        foreach ($errors as $key => $val){
            if ($val){
                $str .= Html::beginTag('tr');   
                $str .= Html::tag('td', $aKeyStr[$key]);  
                $str .= Html::endTag('tr');                    
            }
        }
        $str .= Html::endTag('table');              
        return $str;      
    }


    function bool2Str($val){
        if ($val != 0){            
            return '<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>';                                            
        }else{
            return '<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>'; 
        }
    }

    Pjax::begin(['id' => 'grid']); 
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'id',
                'format' => 'raw',
                'value' => function($data){
                    $p = Product::findOne($data['id']);               
                    $res = $data->id.'<br><strong>'.$p->getTypeProductStr().'</strong>'; 
                    return $res;
                }
            ],
            [
                'attribute' => 'id1C',
                'format' => 'raw',           
                'value' => function($data){
                    $errors = [];
                    $chk = Product::checkData($data['id'], $errors);
                    //$str = 'данные: '. bool2Str($chk).'<br>';
                    $str = Html::beginTag('table', ['class' => 'table']);                                    
                        $str .= Html::beginTag('tr');
                            $str.= Html::tag('td', '1c/sku:'); 
                            $str.= Html::tag('td', Html::a(
                                    $data['id1C'].'/'.$data['sku'],
                                    '/product/update/?id='.$data->id));
                        $str.= Html::endTag('tr');
                        $str.= Html::beginTag('tr');                            
                            $str.= Html::tag('td', 'данные'); 
                            $str.= Html::tag('td', bool2Str($chk));
                        $str.= Html::endTag('tr');
                    $str.= Html::endTag('table');
                    $str .= badProduct2Html($errors);
                    return $str;
                }
            ],
            [
                'label' => 'info',
                'format' => 'raw',
                'value' => function($data){
                    $str = Html::beginTag('table', ['class' => 'table']);                  
                        $str .= Html::beginTag('tr');
                            $str.= Html::tag('td', 'готов:'); 
                            $str.= Html::tag('td', bool2Str($data['isReady']));                                      
                        $str.= Html::endTag('tr');   
                        $str.= Html::beginTag('tr');
                            $str.= Html::tag('td', 'опубликован:'); 
                            $str.= Html::tag('td', bool2Str($data['isPublished']));
                        $str.= Html::endTag('tr');
                    $str.= Html::endTag('table');
                    if ($data['isExportMarket'] == 1){
                        $str .= '<b>ЯндексMarket</b>';
                    }
                    return $str;                    
                }
            ],        
            [       
                'label' => 'склады',          
                'format' => 'raw',                 
                'value' => function($data){
                    $a = ProductStore::getPrices($data['id1C']);
                    $str = "";
                    if (count($a) > 0){
                        $str .= Html::beginTag('table', ['class' => 'table']);                                   
                            $str .= Html::beginTag('tr');
                                $str.= Html::tag('th', 'склад'); 
                                $str.= Html::tag('th', 'кол');                              
                                $str.= Html::tag('th', 'цена');                                      
                            $str.= Html::endTag('tr');
                            foreach ($a as $store){
                                if ($store['kol'] > 0){
                                    $str.= Html::beginTag('tr');
                                        $storeName = Store::findOne($store['idStore'])->name;
                                        $str.= Html::tag('td', $storeName); 
                                        $str.= Html::tag('td', $store['kol']);                                
                                        $str.= Html::tag('td', $store['price']);
                                    $str.= Html::endTag('tr');                                
                                }
                            }   
                        $str.= Html::endTag('table');                        
                    }
                    return $str;                    
                }
            ],                       
            // 'sku',
            [
                'attribute' => 'idCategory',
                'format' => 'raw',    
                'filter' => Category::getListFilter(),  
                'value' => function ($data){
                    if ($data['idCategory'] != NULL){
                        $str = Category::getName($data['idCategory']);  
                        $isShowCatKit = false;
                        if ($settings = Settings::findOne(1)){
                            $isShowCatKit = $settings->isShowCatKit;
                        }
                        if ($isShowCatKit){
                            $str .= "<br><br>";
                            $str .= Html::dropDownList('ddId'.$data['id'], $data['idKitCategory'], ArrayHelper::map(KitCategory::find()->all(), 'id', 'name'),                                            
                                [   'id' => 'ddId'.$data['id'],
                                    'onchange'=>'changeDropDown('.$data['id'].')' ]
                            );
                        }
                        return $str;                                    
                    }
                    return '';
                },
            ],   
            // [
            //     'attribute' => 'idManufacturer',
            //     'format' => 'html',    
            //     'value' => function ($data){
            //         if ($data['idManufacturer'] != NULL){
            //             return Manufacturer::getList()[$data['idManufacturer']];
            //             // $m = Manufacturer::findOne($data['idManufacturer']);
            //             // return $m->name;
            //         }else{
            //             return '';
            //         }
            //     },
            // ],            
            // [
            //     'attribute' => 'idKitCategory',
            //     'format' => 'raw',    
            //     'filter' => KitCategory::getListFilter(),                 
            //     'value' => function ($data){
            //         $str = Html::dropDownList('ddId'.$data['id'], $data['idKitCategory'], ArrayHelper::map(KitCategory::find()->all(), 'id', 'name'),                                            
            //             [   'id' => 'ddId'.$data['id'],
            //                 'onchange'=>'changeDropDown('.$data['id'].')' ]
            //         );
            //             // $.get( "index.php?r=suborders/listprice&id="+$(this).val(), function( data ) {
            //             //   $( "#suborders-product_price" ).val( data );            
            //        // $str .= " <br><br> ".Html::a('ok', ['/product/update-kit-cat/?id='.$data['id'],  ], ['class'=>'btn btn-success']);
            //         return $str;
            //     },
            // ],               
            // 'idLocate',
            [
                'attribute' => 'name',
                'format' => 'raw',
                'value' => function($data){
                    $res = Html::a(
                        $data->name,
                        '/product/update/?id='.$data->id
                    );
                    $res .= "<br>";
                    $res .= $data->nameYandex;
                    $res .= "<br><br>";
                    $res .= Color::getListHtml($data['id']);
                    $res .= "<br>";
                    $res .= Design::getListHtml($data['id']);
                    return $res;
                }
            ],      
           [
                'header' => 'Фото',
                'format' => 'html',    
                'filter' => [0 => 'нет', 1 => 'есть фото'],                  
                'value' => function ($data) {
                    $img[0] = ($data['isImageMain']) ? Html::img(Yii::getAlias('@web').'/images/p'. $data['id'].'/0.jpg',
                                ['width' => '90px']) : '';
                    $img[1] = ($data['isImageMore']) ? Html::img(Yii::getAlias('@web').'/images/p'. $data['id'].'/1.jpg',
                                ['width' => '90px']) : '';
                    $img[2] = ($data['isImageMore2']) ? Html::img(Yii::getAlias('@web').'/images/p'. $data['id'].'/2.jpg',
                                ['width' => '90px']) : '';     
                    $img[3] = ($data['isImageMore3']) ? Html::img(Yii::getAlias('@web').'/images/p'. $data['id'].'/3.jpg',
                                ['width' => '90px']) : '';                                                     
                    $str = Html::beginTag('table', ['class' => 'table']);                   
                        $str.= Html::beginTag('tr');
                            $str.= Html::tag('td', Html::a($img[0], '/product/update/?id='.$data->id)); 
                            $str.= Html::tag('td', Html::a($img[1], '/product/update/?id='.$data->id));     
                            $str.= Html::tag('td', Html::a($img[2], '/product/update/?id='.$data->id));  
                            $str.= Html::tag('td', Html::a($img[3], '/product/update/?id='.$data->id));                                                                    
                        $str.= Html::endTag('tr');   
                    $str.= Html::endTag('table');   
                    return $str;                                                                                          
                    //return Html::a($str, '/product/update/?id='.$data->id);                                                 
                },
            ],        
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{delete}{link}',
        ],          
        ],         
    ]); ?>
<?php Pjax::end(); ?></div>

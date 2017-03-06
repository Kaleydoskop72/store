<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\Product;
use app\models\Category;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BadProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Товары с ошибками';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bad-product-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

<?php

function addRow($data, $aData){
    $res = Html::beginTag('tr');
    foreach ($aData as $key => $value) {
        $res .= Html::tag('td', $value.':'); 
        $str = '';
        if ($data[$key]){
            $str = '<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>'; 
        }
        $res .= Html::tag('td', $str); 
    }
    $res .= Html::endTag('tr');    
    return $res;
}

?>


<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'idProduct',
            'id1C',            
            [
                'label' => 'Товар',
                'format' => 'html',    
                'value' => function ($data){
                    $productName = '';
                    if($p = Product::findOne($data['idProduct'])){
                        $productName = $p->name.'<br>'.$p->id1C.' / '.$p->sku;
                    }
                    if ($c = Category::findOne($p->idCategory)) {
                        $productName .= "<br>".$c->name;
                    }
                    return $productName;
                }
            ],          
            [
                'label' => 'Главная информация',
                'format' => 'raw',           
                'value' => function($data){
                    $isOk = $data['badPhoto'] && $data['badName'] && $data['badId1C'] &&
                         $data['badCategory'] && $data['badPrice'] && $data['badManufacturer'];

                    if ($isOk){
                        return '';
                    }          
                    $str = Html::beginTag('table', ['class' => 'table']);

                    $str .= addRow($data, [  'badPhoto' => 'Фото',  'badName' => 'Название' ]);
                    $str .= addRow($data, [  'badId1C'  => '1C',    'badCategory' => 'Категория' ]);
                    $str .= addRow($data, [  'badPrice' => 'Цена',  'badManufacturer' => 'Производитель' ]);                                                        
                    $str.= Html::endTag('table');
                    return $str;
                }
            ],
            [
                'label' => 'Ткани',
                'format' => 'raw',           
                'value' => function($data){
                    $product = Product::findOne($data['idProduct']); 
                    $typeProduct = Category::TYPE_NONE;
                    if ($cat = Category::findOne($product->idCategory)){
                        $typeProduct = $cat->typeProduct;
                    }
                    if ($typeProduct != Category::TYPE_FABRIC){
                        return '';
                    }

                    $isOk = $data['badContent'] && $data['badColorName'];

                    if ($isOk){
                        return '';
                    }                          
                    $str = Html::beginTag('table', ['class' => 'table']);                                    
                    $str .= addRow($data, ['badContent' => 'Состав']);
                    $str .= addRow($data, ['badColorName' => 'Название цвета']);                                                                            
                    $str.= Html::endTag('table');
                    return $str;
                }
            ],     
            [
                'label' => 'Наборы',
                'format' => 'raw',           
                'value' => function($data){
                    $product = Product::findOne($data['idProduct']); 
                    $typeProduct = Category::TYPE_NONE;
                    if ($cat = Category::findOne($product->idCategory)){
                        $typeProduct = $cat->typeProduct;
                    }
                    if ($typeProduct != Category::TYPE_KIT){
                        return '';
                    }           
                    $isOk = $data['badCatKit'] && $data['badContent'] && $data['badSize'] &&
                         $data['badColorCount'] && $data['badCanva'] && $data['badTech'];

                    if ($isOk){
                        return '';
                    }                                   
                    $str = Html::beginTag('table', ['class' => 'table']);                                    
                    $str .= addRow($data, ['badCatKit' => 'Категория набора', 'badContent' => 'Состав']);
                    $str .= addRow($data, ['badSize' => 'Размер', 'badColorCount' => 'Кол. цветов']);     
                    $str .= addRow($data, ['badCanva' => 'Канва', 'badTech' => 'Техника выш.']);                                                                                            
                    $str.= Html::endTag('table');
                    return $str;
                }
            ],                                                                         
        ],
    ]); ?>
<?php Pjax::end(); ?></div>

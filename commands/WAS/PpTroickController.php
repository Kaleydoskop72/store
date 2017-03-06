<?php



namespace app\commands;
use yii\console\Controller;
use app\models\ProductColor;


if(!function_exists('file_get_html')) { 
    echo "!!!\n";
    //require_once ('simple_html_dom.php'); 
}
// include_once "simple_html_dom.php";


$listProduct = array();


class PpTroickController extends ParseController{
    public $categoryId = 12;
    public $manufacturerId = 5;
    public $listColors = [];
    public $listProduct = [];

    function initData(){
        $this->listColors = array(
            0017 => 'серо-голубой',
            0021 => 'вишня',
            0023 => 'вишня',
            0025 => 'вишня',
            0028 => 'вишня',
            0032 => 'темно-фиолетовый',
            0036 => 'темно-фиолетовый',
            0037 => 'темно-фиолетовый',
            0042 => 'красный',
            0043 => 'красный',
            0045 => 'красный',
            0052 => 'светлый салат',
            0053 => 'светлый салат',
            0057 => 'светлый салат',
            0058 => 'светлый салат',
            0064 => 'светлая сирень',
            0070 => 'лесной колокольчик',
            0086 => 'терракот',
            0089 => 'терракот',
            0107 => 'темно-синий',
            0108 => 'темно-синий',
            0112 => 'зеленый',
            0131 => 'багряный',
            0140 => 'черный',
            0155 => 'сиреневые дали',
            0156 => 'сиреневые дали',
            0160 => 'розовый',
            0165 => 'розовый',
            0168 => 'розовый',
            0170 => 'василек',
            0178 => 'василек',
            0190 => 'песочный',
            0192 => 'песочный',
            0196 => 'песочный',
            0197 => 'песочный',
            0198 => 'песочный',
            0199 => 'песочный',
            0201 => 'бежевый',
            0204 => 'бежевый',
            0205 => 'бежевый',
            0220 => 'светло розовый',
            0230 => 'отбелка',
            0235 => 'супер белый',
            0242 => 'омут',
            0243 => 'омут',
            0244 => 'омут',
            0259 => 'светло-серый',
            0263 => 'фиолетовый',
            0266 => 'фиолетовый',
            0270 => 'бледно-голубой',
            0272 => 'бледно-голубой',
            0276 => 'бледно-голубой',
            0277 => 'бледно-голубой',
            0281 => 'голубой',
            0282 => 'голубой',
            0294 => 'перванш',
            0300 => 'светло-голубой',
            0305 => 'светло-голубой',
            0312 => 'изумруд',
            0334 => 'морская волна',
            0335 => 'морская волна',
            0339 => 'морская волна',
            0342 => 'изумруд',
            0350 => 'брусника',
            0358 => 'брусника',
            0368 => 'талая вода',
            0373 => 'болотный',
            0384 => 'кристалл',
            0392 => 'сирень',
            0394 => 'сирень',
            0395 => 'сирень',
            0410 => 'шоколадный',
            0411 => 'шоколадный',
            0412 => 'шоколад',
            0417 => 'шоколад',
            0432 => 'серый',
            0447 => 'полынь',
            0453 => 'бегония',
            0457 => 'бегония',
            0460 => 'само',
            0463 => 'само',
            0467 => 'само',
            0469 => 'само',
            0471 => 'голубая бирюза',
            0473 => 'голубая бирюза',
            0474 => 'голубая бирюза',
            0475 => 'голубая бирюза',
            0478 => 'голубая бирюза',
            0484 => 'серо-зеленый',
            0490 => 'ярко-оранжевый',
            0493 => 'ярко-оранжевый',
            0498 => 'ярко-оранжевый',
            0499 => 'ярко-оранжевый',
            0512 => 'жемчуг',
            0515 => 'жемчуг',
            0522 => 'бирюза',
            0538 => 'фрез',
            0540 => 'оливковый',
            0556 => 'суровый лен',
            0557 => 'суровый лен',
            0580 => 'зеленое яблоко',
            0585 => 'зеленое яблоко',
            0591 => 'желтый',
            0596 => 'желтый',
            0598 => 'желтый',
            0601 => 'темно-бежевый',
            0602 => 'темно-бежевый',
            0603 => 'темно-бежевый',
            0606 => 'темно-бежевый',
            0608 => 'темно-бежевый',
            0659 => 'кобальт',
            0661 => 'фламинго',
            0665 => 'фламинго',
            0666 => 'фламинго',
            0690 => 'шафран',
            0695 => 'шафран',
            0723 => 'яркая зелень',
            0726 => 'яркая зелень',
            0736 => 'цикломен',
            0740 => 'георгин',
            0752 => 'зеленая бирюза',
            0753 => 'зеленая бирюза',
            0754 => 'зеленая бирюза',
            0756 => 'зеленая бирюза',
            0760 => 'облепиха',
            0770 => 'суровый',
            0812 => 'светлые сумерки',
            0813 => 'светлые сумерки',
            0818 => 'светлые сумерки',
            0842 => 'айсберг',
            0851 => 'мулине (отб./крас.)',
            0892 => 'мулине (отб.+черн.)',
            0900 => 'меланж',
            0909 => 'меланж(черный)',
            1004 => 'липа',
            1008 => 'липа',
            1014 => 'мальва',
            1042 => 'перламутр',
            1045 => 'перламутровый',
            1080 => 'шампанское',
            1082 => 'шампанское',
            1084 => 'шампанское',
            1194 => 'меланж ( натуральный)',
            1222 => 'ярко-голубой',
            1224 => 'ярко-голубой',
            1229 => 'ярко-голубой',
            1251 => 'молочный шоколад',
            1253 => 'молочный шоколад',
            1290 => 'золотистый',
            1292 => 'золотистый',
            1293 => 'золотистый',
            1310 => 'фиалка',
            1312 => 'фиалка',
            1315 => 'фиалка',
            1327 => 'аквамарин',
            1342 => 'лимон',
            1346 => 'лимон',
            1383 => 'морские водоросли',
            1389 => 'морские водоросли',
            1390 => 'коралл',
            1396 => 'коралл',
            1397 => 'коралл',
            1399 => 'коралл',
            1420 => 'винный',
            1425 => 'винный',
            1430 => 'светлая бирюза',
            1431 => 'светлая бирюза',
            1433 => 'светлая бирюза',
            1435 => 'светлая бирюза',
            1446 => 'алый',
            1449 => 'алый',
            1470 => 'габардин',
            1474 => 'габардин',
            1505 => 'натуральный',
            1507 => 'натуральный',
            1508 => 'натуральный',
            1542 => 'кирпичный',
            1547 => 'кирпич',
            1550 => 'аспарагус',
            1572 => 'стальной',
            1575 => 'стальной',
            1591 => 'ежевика',
            1596 => 'ежевика',
            1597 => 'ежевика',
            1605 => 'джинсовый',
            1623 => 'оранжевый',
            1624 => 'оранжевый',
            1635 => 'верба',
            1645 => 'яркая сирень',
            1775 => 'меланж (зеленое яблоко)',
            1782 => 'оливковая зелень',
            1800 => 'мулине',
            1856 => 'меланж (коричневый)',
            1874 => 'светло-бежевый',
            1881 => 'клевер',
            1883 => 'клевер',
            1885 => 'клевер',
            1925 => 'светлая азалия',
            1926 => 'светлая азалия',
            1952 => 'меланж',
            2049 => 'мулине',
            2064 => 'меланж (т.корич.)',
            2065 => 'меланж (св.корич.)',
            2206 => 'гранит',
            2273 => 'меланж (тем.брусника)',
            2321 => 'мулине (черный/ярко-розовый)',
            2334 => 'бледный салат',
            2423 => 'светлый терракот',
            2444 => 'натуральный светлый',
            2447 => 'натуральный светлый',
            2449 => 'натуральный светлый',
            2454 => 'натуральный темный',
            2458 => 'натуральный темный',
            2459 => 'натуральный темный',
            2575 => 'мулине (персик/корич.)',
            2578 => 'мулине (желтый/шоколад)',
            2585 => 'мулине (золото/хаки)',
            2590 => 'мулине (оранж./липа)',
            2592 => 'мулине(шоколад/золотистый)',
            2595 => 'мулине (цикломен/миндальный)',
            2599 => 'мулине( тем.бирюза\красный)',
            2764 => 'мулине (отб/лимон)',
            2866 => 'персик',
            2890 => 'меланж (маренго)',
            2891 => 'меланж (темно-серый)',
            2892 => 'меланж (серо-голубой)',
            2931 => 'мулине (бежевый)',
            2934 => 'мулине (отбелка)',
            3011 => 'салат',
            3014 => 'салат',
            3015 => 'салат',
            3029 => 'меланж (баклажан)',
            3060 => 'яркая мальва',
            3065 => 'яркая мальва',
            3067 => 'яркая мальва',
            3081 => 'меланж (светло джинсовый)',
            3083 => 'меланж (темно-серый)',
            3084 => 'меланж (кофе)',
            3086 => 'меланж (темно-бежевый)',
            3088 => 'меланж (голубой)',
            3100 => 'меланж (шоколад)',
            3135 => 'меланж (фрез)',
            3172 => 'светлая фисташка',
            3213 => 'коньяк',
            3215 => 'коньяк',
            3234 => 'мулине (св.серый)',
            3255 => 'меланж (листопад)',
            3256 => 'меланж ( лимон)',
            3257 => 'меланж(салат)',
            3258 => 'меланж (горчица)',
            3259 => 'меланж (серый)',
            3282 => 'меланж (серый)',
            3283 => 'меланж (фрез)',
            3291 => 'яркий салат',
            3293 => 'яркий салат',
            3392 => 'мулине',
            3420 => 'океан',
            3505 => 'мулине (персик)',
            3508 => 'мулине (зеленое яблоко)',
            3509 => 'мулине (черный)',
            3533 => 'нептун',
            3574 => 'темный салат',
            3581 => 'миндальный',
            3622 => 'мулине (черн/суров)',
            3652 => 'темно-коричневый',
            3654 => 'темно-коричневый',
            3656 => 'темно-коричневый',
            3658 => 'темно-коричневый',
            3659 => 'темно-коричневый',
            3676 => 'светло-джинсовый',
            3708 => 'меланж',
            3712 => 'меланж',
            3719 => 'меланж (брусника)',
            3733 => 'мулине (цикломен)',
            3803 => 'коричневый',
            3843 => 'темная бирюза',
            3858 => 'мята',
            3859 => 'мята',
            3860 => 'листопад',
            3880 => 'фуксия',
            4006 => 'секционный',
            4023 => 'секционный',
            4060 => 'секционная',
            4169 => 'секционная',
            6030 => 'меланж',
            6033 => 'меланж',
            6035 => 'меланж',
            6036 => 'меланж',
            6037 => 'меланж',
            6038 => 'меланж',
            6039 => 'меланж',
            6040 => 'меланж',
            7070 => 'принт',
            7090 => 'принт',
            7091 => 'принт',
            7092 => 'принт',
            7097 => 'принт',
            7111 => 'принт',
            7117 => 'принт',
            7120 => 'принт',
            7123 => 'принт',
            7126 => 'принт',
            7179 => 'принт',
            7182 => 'принт',
            7184 => 'принт',
            7185 => 'принт',
            7211 => 'принт',
            7216 => 'принт',
            7217 => 'принт',
            7231 => 'принт',
            7240 => 'принт',
            7247 => 'принт',
            7255 => 'принт',
            7260 => 'принт',
            7261 => 'принт',
            7264 => 'принт',
            7269 => 'принт',
            7270 => 'принт',
            7271 => 'принт',
            7272 => 'принт',    
        );


        array_push($this->listProduct, array(
            'url'       => 'http://www.troitskwool.com/yarn-for-hand-knitting/mellow-camel/',
            'price'     =>  210,
            'colors'    =>  array(2447, 0140),
            'colorsReserv'  =>  array(0030, 2333, 3677,0257, 0695, 1923, 1312, 0360, 0108, 0272, 1435, 3521, 0307, 0463, 0140, 0197)
            ));
        array_push($this->listProduct, array(
            'url'       => 'http://www.troitskwool.com/yarn-for-hand-knitting/pastoral/',
            'price'     =>  110,
            'colors'    =>  array()
            ));
        array_push($this->listProduct, array(
            'url'       => 'http://www.troitskwool.com/yarn-for-hand-knitting/waterfall/',
            'price'     =>  190,
            'colors'    =>  array(7184, 0432, 0230, 0140)
            ));
        array_push($this->listProduct, array(
            'url'       => 'http://www.troitskwool.com/yarn-for-hand-knitting/winters-tale/',
            'price'     =>  320,
            'colors'    =>  array()
            ));
        array_push($this->listProduct, array(
            'url'       => 'http://www.troitskwool.com/yarn-for-hand-knitting/baby/',
            'price'     =>  75,
            'colors'    =>  array(1550, 0474, 0282, 1597, 1881, 0070, 3860, 1251, 0230, 1042, 0192, 1433, 0156, 0037, 1310, 0723),
            'colorsReserv'  =>  array(0596, 0140, 1390, 0384, 3581) 
            ));
        array_push($this->listProduct, array(
            'url'       => 'http://www.troitskwool.com/yarn-for-hand-knitting/countryside/',
            'price'     =>  160,
            'colors'    =>  array(1550, 0453, 0170, 1425, 1470, 0474, 0596, 0753, 0112, 1883, 1390, 0042, 0384, 1952,
                                6033, 6035, 6038, 6037, 6039, 1042, 0192, 0160, 1874, 0818, 0156, 0107, 1315, 3060, 3065, 4169)
            ));
        array_push($this->listProduct, array(
            'url'       => 'http://www.troitskwool.com/yarn-for-hand-knitting/simple/',
            'price'     =>  100,
            'colors'    =>  array()
            ));
        array_push($this->listProduct, array(
            'url'       => 'http://www.troitskwool.com/yarn-for-hand-knitting/fluffy/',
            'price'     =>  220,
            'colors'    =>  array(0342, 0263),
            'colorsReserv'  =>  array(0414, 1507, 0246, 1080, 0214, 0230, 1472, 0590),
            ));
        array_push($this->listProduct, array(
            'url'       => 'http://www.troitskwool.com/yarn-for-hand-knitting/scottish-tweed/',
            'price'     =>  190,
            'colors'    =>  array()
            ));
    }


    public function actionIndex(){
        $this->initData();
        foreach ($this->listProduct as $p){
            $productMain = $this->parsePage($p['url']);
            $productMain['idManufacturer'] = $this->manufacturerId;
            $productMain['idCategory'] = $this->categoryId;
            $productMain['idProduct'] = $this->insertProduct($productMain);
            echo "product: \n";
            print_r($productMain);
            $productColors = $this->parseColor($p['url']);
            foreach ($productColors as $c){
                print_r($c);
                if (array_key_exists('imgUrl', $c)){
                    $productMain['imgUrl'] = $c['imgUrl'];
                    $productMain['imgFile'] = $c['imgFile'];
                    $productMain['colorName'] = $c['name'];
                    $productMain['colorCode'] = $c['code'];  
                    $productMain['price'] = $c['price']; 
                    $this->insertProductColor($productMain);                     
                }  
                sleep(0.5);                          
            }

            // echo "productColors: \n";            
            // print_r($productColors);    
            // sleep(1);
        }        
        // print_r($this->aUrl);
        // foreach ($this->aUrl as $u) {
        //     $this->parsePage($u);
        //     // exit;
        //     sleep(1);
        // }    
    }



    // function findKeyPrj($str, $aKey, &$productKey){
    //     // echo "str == $str\n";    
    //     foreach ($aKey as $key){
    //         // echo "key == ".$key['key']."\n";
    //         if (preg_match('/'.$key['key'].'/', $str, $matches)){
    //             $productKey = $key['productKey'];           
    //             return 1;
    //         }
    //     }
    //     return 0;
    // }



    function parsePage($url){
        // $html = $this->getDom($url);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_REFERER, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/533.4 (KHTML, like Gecko) Chrome/5.0.375.125 Safari/533.4");
        $str = curl_exec($curl);
        curl_close($curl);

        $html = new simple_html_dom();   
        $html->load($str);  
        $product = array();

        foreach($html->find('.main-image') as $div){
            foreach($div->find('img') as $img){     
                $product['imgUrl'] = 'http://www.troitskwool.com'.$img->img_href;
                //http://www.troitskwool.com/files/nodus_items/0033/16610/image-16610-1421239827.jpg
                $product['imgFile'] = explode('/', $img->img_href)[5];
            }
        }
        foreach($html->find('.info') as $div){
            foreach($div->find('h1') as $h1){       
                $product['name'] = $h1->plaintext;          
            }
            foreach($div->find('.code') as $a){     
                foreach($a->find('.value') as $v){      
                    $product['sku'] = $v->plaintext;
                }
            }
            foreach($div->find('.attributes') as $a){
                foreach($a->find('.item') as $item){
                    $attrName = trim($item->find('.title',0)->plaintext);
                    $attrVal  = trim($item->find('.value',0)->plaintext);
                    switch ($attrName){
                        case 'Сезон:':
                            $product['season'] = $attrVal;
                            break;
                        case 'Состав пряжи:':
                            $product['structure'] = $attrVal;
                            break;
                        case 'Длина нити (м):':
                            $product['length'] = $attrVal;
                            break;
                        case 'Вес мотка (г):':
                            $product['weight'] = $attrVal;
                            break;
                        case 'Описание:':
                            $product['description'] = $attrVal;
                            break;
                    }
                }
            }       
        }
        return $product;
    }


    function parseColor($url){
        $html = $this->getDom($url);
        $colors = array();
        $i = 0;

        foreach($html->find('div.catalog-items-list') as $div){ 
            foreach($div->find('div.catalog-item') as $item){   
                //echo $item->plaintext."\n";
                foreach($item->find('a.fancybox') as $a){       
                    $colors[$i]['imgUrl'] = 'http://www.troitskwool.com'.$a->href;
                    $colors[$i]['imgFile'] = explode('/', $a->href)[6];
                }
                foreach($item->find('div.title') as $title){        
                    $name = trim(explode('/', $title->plaintext)[0]);
                    preg_match('/(.+)\s+(\d+)/', $name, $matches);  
                    $colors[$i]['name'] = $name;
                    $colors[$i]['code'] = $matches[2];                    
                    // $colors[$i]['name'] = trim(explode('/', $title->plaintext)[0]);
                    // $colors[$i]['code'] = trim(explode(' ', $colors[$i]['name'])[1]);
                }           
                foreach($item->find('div.price') as $price){
                    $colors[$i]['price'] = trim(explode('.', $price->plaintext)[0]);
                }       
                $i++;               
            }
        }
        return $colors;
    }



    // function parsePage($u){
    //     $aKey = [
    //         [ 'productKey' => 'forFilter',  'key' => 'Для фильтрации' ],            
    //         [ 'productKey' => 'season',     'key' => 'Сезонность' ],
    //         [ 'productKey' => 'purpose',    'key' => 'Назначение' ],    
    //         [ 'productKey' => 'techEmbr',   'key' => 'Способ вязания' ],        
    //         [ 'productKey' => 'length',     'key' => 'Длина нити в мотке' ],        
    //         [ 'productKey' => 'weight',     'key' => 'Вес мотка' ], 
    //         [ 'productKey' => 'thread',     'key' => 'Структура нити' ],    
    //         [ 'productKey' => 'structure',  'key' => 'Состав' ],        
    //         [ 'productKey' => 'kolInPack',  'key' => 'Количество в упаковке' ],                                             
    //     ];
    //     $html = file_get_html($u['url']);
    //     $product = [];

    //     $div = $html->find('.catalog_head', 0);
    //     $h1 = $div->find('h1', 0);
    //     if (!empty($u['name'])){
    //         $product['name'] = $u['name'];      
    //     }else{
    //         $product['name'] = trim($h1->plaintext);
    //     }
    //     echo "name == ".$product['name']."\n";

    //     $product['idCategory'] = $this->idCategory;
    //     $div = $html->find('.main_img', 0);
    //     $img = $div->find('img', 0);    
    //     $product['imgUrl'] = $img->attr['data-large'];
    //     preg_match('/\/(\w+\.jpg)/', $product['imgUrl'], $matches);         
    //     $product['imgFile'] = $matches[1];      

    //     $table = $html->find('.item_features', 0);
    //     $td = $table->find('.item_description', 0);
    //     $product['description'] = '';
    //     if (!empty($u['txt'])){
    //         $product['description'] = $u['txt'];        
    //     }else{
    //         foreach ($td->find('p') as $p) {
    //             $product['description'] = $product['description'].' '.trim($p->plaintext);
    //         }   
    //     }

    //     foreach ($table->find('tr') as $tr) {
    //         $attrName = trim($tr->find('td', 0)->plaintext);
    //         $attrVal = trim($tr->find('td', 1)->plaintext);
    //         $patterns = [];
    //         $patterns[0] = '/&nbsp;/';
    //         $replacements = [];
    //         $replacements[0] = '';
    //         $attrVal =  preg_replace($patterns, $replacements, $attrVal);       
    //         // echo "> ".$attrName." ".$attrVal."\n";
    //         if ($this->findKeyPrj($attrName, $aKey, $key)){
    //             $product[$key] = $attrVal;
    //         }
    //     }
    //     $product['price'] = $u['price'];

    //     echo "+++++++++ product: \n";
    //     print_r($product);
    //     //insertCategory($data);


    //     // $product['sku'] = '';
    //     $product['idManufacturer'] = $this->idManufacturer;
        
    //     $product['idProduct'] = $this->insertProduct($product);

    //     if (empty($u['colors'])){
    //         $div = $html->find('.colors_list', 0);
    //         foreach ($div->find('.colour_card') as $color){
    //             if (preg_match('/(\d+)\s+(.+)/', trim($color->find('.name',0)->plaintext), $matches)){
    //                 $product['colorCode'] = $matches[1];
    //                 $product['colorName'] = $matches[2];                    
    //             }else{
    //                 $product['colorCode'] = trim($color->find('.name',0)->plaintext);
    //                 echo "111 *** ".trim($color->find('.name',0)->plaintext)."\n";
    //             }
                
    //             // echo "art_no: ".trim($color->find('.art_no',0)->plaintext)."\n";
    //             $product['imgUrl'] = trim($color->find('img',0)->src);
    //             preg_match('/\/(\w+\.jpg)/', $product['imgUrl'], $matches);
    //             $product['imgFile'] = $matches[1];      

    //             $this->insertProductColor($product);                 
    //         }
    //     }else{
    //         foreach ($u['colors'] as $color){
    //             $product['imgUrl'] = $color['colorUrl'];
    //             $product['colorCode'] = $color['colorName'];                  
    //             if (preg_match('/\/(\w+\.jpg)/', $product['imgUrl'], $matches)){
    //                 $product['imgFile'] = $matches[1];                 
    //             }else{
    //                 echo "222 *** ". $product['imgUrl']."\n";
    //             }

    //             $this->insertProductColor($product);           
    //         }         
    //     }
    //     return $product;
    // }


}



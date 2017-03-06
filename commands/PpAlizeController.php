<?php



namespace app\commands;
use yii\console\Controller;
use app\models\ProductColor;


include "simple_html_dom.php";

class PpAlizeController extends ParseController{
    public $idManufacturer = 0;
    public $idCategory = 17484;

    // public $aCategory = [];


    public $aUrl = [
        [
            'url' => 'http://rukodelie-rostov.ru/catalog/bebi-vul.html',
            'price' => 120
        ],
        [
            'url' => 'http://rukodelie-rostov.ru/catalog/bebi-vul-batik.html',
            'price' => 130
        ],
        [
            'url' => 'http://rukodelie-rostov.ru/catalog/kantri-new.html',
            'price' => 270
        ],  
        [
            'name' => 'Бэлла батик',
            'url' => 'http://rukodelie-rostov.ru/catalog/bahar.html',
            'txt' => '
                        Очень качественная натуральная пряжа Бэлла батик представляет собой специально обработанный хлопок. Мерсеризация придает нити особый блеск, а качество самой нити — без каких-либо утолщений, узелков — проявляется и в узорах, создаваемых умелыми руками мастериц. Пряжа Бэлла батик отлично подходит для работы и крючком, и спицами, послушно ложится в узоры любой сложности, предназначена для вывязывания летних легких вещей. В силу натуральности и экологической чистоты эта пряжа предназначается и для детских вещей. При работе с пряжей Бэлла батик можно использовать узоры разной степени сложности — от простой чулочной вязки до сложных жаккардов.

                        Оптимальный размер спиц №3-3,5 и крючка: №3

                        Плотность вязания: 10х10см = 26р.х34п.',
            'colors' => [
                [ 'colorName' => '5559', 'colorUrl' => 'http://www.alize.gen.tr/images/1421328700bella_batik_5559.jpg' ],
                [ 'colorName' => '5512', 'colorUrl' => 'http://www.alize.gen.tr/images/1421328733bella_batik_5512.jpg' ],
                [ 'colorName' => '2905', 'colorUrl' => 'http://www.alize.gen.tr/images/1333113069bellabatik_2905.jpg' ],
                [ 'colorName' => '1815', 'colorUrl' => 'http://www.alize.gen.tr/images/1333112973bellabatik_1815.jpg' ],
                [ 'colorName' => '2807', 'colorUrl' => 'http://www.alize.gen.tr/images/1333113007bellabatik_2807.jpg' ],
                [ 'colorName' => '2126', 'colorUrl' => 'http://www.alize.gen.tr/images/1333112979bellabatik_2126.jpg' ],
                [ 'colorName' => '3677', 'colorUrl' => 'http://www.alize.gen.tr/images/1360068118bella_batik_3677.jpg' ],
                [ 'colorName' => '3675', 'colorUrl' => 'http://www.alize.gen.tr/images/1360068041bella_batik_3675.jpg' ],
                [ 'colorName' => '2130', 'colorUrl' => 'http://www.alize.gen.tr/images/1333112985bellabatik_2130.jpg' ],
                [ 'colorName' => '2131', 'colorUrl' => 'http://www.alize.gen.tr/images/1333112992bellabatik_2131.jpg' ],
                [ 'colorName' => '2132', 'colorUrl' => 'http://www.alize.gen.tr/images/1333112916bella_batik_2132.jpg' ],
                [ 'colorName' => '4591', 'colorUrl' => 'http://www.alize.gen.tr/images/1390310555bellabatik_4591.jpg' ],
                [ 'colorName' => '4150', 'colorUrl' => 'http://www.alize.gen.tr/images/1360068371bellabatik_4150.jpg' ],
                [ 'colorName' => '4151', 'colorUrl' => 'http://www.alize.gen.tr/images/1360068470bellabatik_4151.jpg' ],
                [ 'colorName' => '4595', 'colorUrl' => 'http://www.alize.gen.tr/images/1390310466bellabatik_4595.jpg' ],
            ],
            'price' => 120
        ],  
        [
            'name' => 'Бэлла',
            'url' => 'http://rukodelie-rostov.ru/catalog/bahar.html',
            'txt' => '
                        Очень качественная натуральная пряжа Бэлла представляет собой специально обработанный хлопок. Мерсеризация придает нити особый блеск, а качество самой нити — без каких-либо утолщений, узелков — проявляется и в узорах, создаваемых умелыми руками мастериц. Пряжа Бэлла отлично подходит для работы и крючком, и спицами, послушно ложится в узоры любой сложности, предназначена для вывязывания летних легких вещей. В силу натуральности и экологической чистоты эта пряжа предназначается и для детских вещей. При работе с пряжей Бэлла можно использовать узоры разной степени сложности — от простой чулочной вязки до сложных жаккардов.

                        Оптимальный размер спиц №3-3,5 и крючка: №3

                        Плотность вязания: 10х10см = 26р.х34п.',
            'colors' => [
                [ 'colorName' => '417 нагой', 'colorUrl' => 'http://www.alize.gen.tr/images/1451295282bella_417.jpg' ],
                [ 'colorName' => '466 верблюжий', 'colorUrl' => 'http://www.alize.gen.tr/images/1451295364bella_466.jpg' ],
                [ 'colorName' => '629 норка', 'colorUrl' => 'http://www.alize.gen.tr/images/1451295497bella_629.jpg' ],
                [ 'colorName' => '55 белый', 'colorUrl' => 'http://www.alize.gen.tr/images/1360070345bella_55.jpg' ],
                [ 'colorName' => '1 молочный', 'colorUrl' => 'http://www.alize.gen.tr/images/1360070394bella_01.jpg' ],
                [ 'colorName' => '76 бежевый', 'colorUrl' => 'http://www.alize.gen.tr/images/1421329472bella_76.jpg' ],
                [ 'colorName' => '40 голубой', 'colorUrl' => 'http://www.alize.gen.tr/images/1360070930bella_40.jpg' ],
                [ 'colorName' => '333 ярко - синий', 'colorUrl' => 'http://www.alize.gen.tr/images/1360071049bella_333.jpg' ],
                [ 'colorName' => '266 зеленый (мята)', 'colorUrl' => 'http://www.alize.gen.tr/images/1360071125bella_266.jpg' ],
                [ 'colorName' => '477 бирюзовый', 'colorUrl' => 'http://www.alize.gen.tr/images/1421329513bella_477.jpg' ],
                [ 'colorName' => '612 кислотный', 'colorUrl' => 'http://www.alize.gen.tr/images/1360072344bella_612.jpg' ],
                [ 'colorName' => '492 зеленый', 'colorUrl' => 'http://www.alize.gen.tr/images/1360071579bella_492.jpg' ],
                [ 'colorName' => '20 изумруд', 'colorUrl' => 'http://www.alize.gen.tr/images/1390312715bella_20.jpg' ],
                [ 'colorName' => '613 пудра', 'colorUrl' => 'http://www.alize.gen.tr/images/1360071747bella_613.jpg' ],
                [ 'colorName' => '32 розовый', 'colorUrl' => 'http://www.alize.gen.tr/images/1360070627bella_32.jpg' ],
                [ 'colorName' => '619 коралловый', 'colorUrl' => 'http://www.alize.gen.tr/images/1390312497bella_619.jpg' ],
                [ 'colorName' => '198 темно-розовый', 'colorUrl' => 'http://www.alize.gen.tr/images/1360070708bella_198.jpg' ],
                [ 'colorName' => '489 ярко - розовый', 'colorUrl' => 'http://www.alize.gen.tr/images/1360070774bella_489.jpg' ],
                [ 'colorName' => '158 синий электрик', 'colorUrl' => 'http://www.alize.gen.tr/images/1360070833bella_158.jpg' ],
                [ 'colorName' => '45 темно фиолетовый', 'colorUrl' => 'http://www.alize.gen.tr/images/1390312577bella_45.jpg' ],
                [ 'colorName' => '21 серый', 'colorUrl' => 'http://www.alize.gen.tr/images/1360071258bella_21.jpg' ],
                [ 'colorName' => '488 желтый', 'colorUrl' => 'http://www.alize.gen.tr/images/1360071603bella_488.jpg' ],
                [ 'colorName' => '487 оранжевый', 'colorUrl' => 'http://www.alize.gen.tr/images/1360071672bella_487.jpg' ],
                [ 'colorName' => '56 красный', 'colorUrl' => 'http://www.alize.gen.tr/images/1360073193bella_56.jpg' ],
                [ 'colorName' => '387 Голубой Сочи', 'colorUrl' => 'http://www.alize.gen.tr/images/1360070984bella_387.jpg' ],
                [ 'colorName' => '60 черный', 'colorUrl' => 'http://www.alize.gen.tr/images/1360070587bella_60.jpg' ],      
            ],
            'price' => 110
        ],      
        [
            'url' => 'http://rukodelie-rostov.ru/catalog/burkum.html',
            'price' => 160
        ],  
        [
            'url' => 'http://rukodelie-rostov.ru/catalog/miss.html',
            'price' => 130
        ],  
        [
            'url' => 'http://rukodelie-rostov.ru/catalog/miss-batik.html',
            'price' => 140
        ],  
        [
            'url' => 'http://rukodelie-rostov.ru/catalog/lana-gold.html',
            'price' => 220
        ],  
        [
            'url' => 'http://rukodelie-rostov.ru/catalog/lana-gold-fain.html',
            'price' => 220
        ],      
        [
            'url' => 'http://rukodelie-rostov.ru/catalog/ponponella.html',
            'price' => 220
        ],      
        [
            'url' => 'http://rukodelie-rostov.ru/catalog/kashemir.html',
            'price' => 320
        ],  
        [
            'url' => 'http://rukodelie-rostov.ru/catalog/bahar.html',
            'price' => 250
        ],  
        [
            'name' => 'Бахар батик',
            'url' => 'http://rukodelie-rostov.ru/catalog/bahar.html',
            'txt' => 'Очень качественная натуральная пряжа Бахар батик представляет собой специально обработанный хлопок. Мерсеризация придает нити особый блеск, а качество самой нити — без каких-либо утолщений, узелков — проявляется и в узорах, создаваемых умелыми руками мастериц. Пряжа Бахар батик отлично подходит для работы и крючком, и спицами, послушно ложится в узоры любой сложности, предназначена для вывязывания летних легких вещей. В силу натуральности и экологической чистоты эта пряжа предназначается и для детских вещей. При работе с пряжей Бахар батик можно использовать узоры разной степени сложности — от простой чулочной вязки до сложных жаккардов.

    Оптимальный размер спиц №3-3,5 и крючка: №3

    Плотность вязания: 10х10см = 26р.х34п.',
            'colors' => [
                [ 'colorName' => '5512', 'colorUrl' => 'http://www.alize.gen.tr/images/1421307394bahar_batik_5512_b.jpg' ],
                [ 'colorName' => '5547', 'colorUrl' => 'http://www.alize.gen.tr/images/1421307443bahar_batik_5547_b.jpg' ],
                [ 'colorName' => '5738', 'colorUrl' => 'http://www.alize.gen.tr/images/14512903695738_b.jpg' ],
                [ 'colorName' => '1815', 'colorUrl' => 'http://www.alize.gen.tr/images/1333018580model_baharbatik_1815.jpg' ],
                [ 'colorName' => '1833', 'colorUrl' => 'http://www.alize.gen.tr/images/1397051040bahar_batik_1833_k.jpg' ],
                [ 'colorName' => '3674', 'colorUrl' => 'http://www.alize.gen.tr/images/1397051106bahar_batik_3674_b.jpg' ],
                [ 'colorName' => '1767', 'colorUrl' => 'http://www.alize.gen.tr/images/1397051149bahar_batik_1767_b.jpg' ],
                [ 'colorName' => '4516', 'colorUrl' => 'http://www.alize.gen.tr/images/1397051167bahar_batik_4516_b.jpg' ],
                [ 'colorName' => '3673', 'colorUrl' => 'http://www.alize.gen.tr/images/1397051273bahar_batik_3673_b.jpg' ],
                [ 'colorName' => '1772', 'colorUrl' => 'http://www.alize.gen.tr/images/1397051420bahar_batik_model_1772_b.jpg' ],
                [ 'colorName' => '1822', 'colorUrl' => 'http://www.alize.gen.tr/images/1333018620bahar_batik_model_1822.jpg' ],
                [ 'colorName' => '1774', 'colorUrl' => 'http://www.alize.gen.tr/images/1333018544bahar_batik_model_1774.jpg' ],
            ],
            'price' => 280
        ],          
        [
            'url' => 'http://rukodelie-rostov.ru/catalog/superlana-maksi.html',
            'price' => 220
        ],  
        [
            'name' => 'Макси фловер',
            'url' => 'http://rukodelie-rostov.ru/catalog/superlana-maksi.html',
            'txt' => '',
            'colors' => [
                [ 'colorName' => '5160', 'colorUrl' => 'http://www.alize.gen.tr/images/1406011268flower_5160.jpg' ],
                [ 'colorName' => '5071', 'colorUrl' => 'http://www.alize.gen.tr/images/1380108720flower_5071.jpg' ],
                [ 'colorName' => '5303', 'colorUrl' => 'http://www.alize.gen.tr/images/14383340835303.jpg' ],
                [ 'colorName' => '5302', 'colorUrl' => 'http://www.alize.gen.tr/images/14383387115302.jpg' ],
                [ 'colorName' => '5072', 'colorUrl' => 'http://www.alize.gen.tr/images/1380108746flower_5072.jpg' ],
                [ 'colorName' => '5164', 'colorUrl' => 'http://www.alize.gen.tr/images/1406011242flower_5164.jpg' ],
                [ 'colorName' => '5073', 'colorUrl' => 'http://www.alize.gen.tr/images/1380108763flower_5073.jpg' ],
                [ 'colorName' => '5309', 'colorUrl' => 'http://www.alize.gen.tr/images/14383387935309.jpg' ],
                [ 'colorName' => '5076', 'colorUrl' => 'http://www.alize.gen.tr/images/1380108797flower_5076.jpg' ],
                [ 'colorName' => '5074', 'colorUrl' => 'http://www.alize.gen.tr/images/1380108774flower_5074.jpg' ],
                [ 'colorName' => '5075', 'colorUrl' => 'http://www.alize.gen.tr/images/1380108785flower_5075.jpg' ],
                [ 'colorName' => '5166', 'colorUrl' => 'http://www.alize.gen.tr/images/1406011198flower_5166.jpg' ],
                [ 'colorName' => '5266', 'colorUrl' => 'http://www.alize.gen.tr/images/14383403575266.jpg' ],
                [ 'colorName' => '5079', 'colorUrl' => 'http://www.alize.gen.tr/images/1380108829flower_5079.jpg' ],
                [ 'colorName' => '5083', 'colorUrl' => 'http://www.alize.gen.tr/images/1380108883flower_5083.jpg' ],
                [ 'colorName' => '5159', 'colorUrl' => 'http://www.alize.gen.tr/images/1406011289flower_5159.jpg' ],
                [ 'colorName' => '5156', 'colorUrl' => 'http://www.alize.gen.tr/images/1406011501flower_5156.jpg' ],
                [ 'colorName' => '5318', 'colorUrl' => 'http://www.alize.gen.tr/images/14383403995318.jpg' ],
                [ 'colorName' => '5300', 'colorUrl' => 'http://www.alize.gen.tr/images/14383404245300.jpg' ],
                [ 'colorName' => '5085', 'colorUrl' => 'http://www.alize.gen.tr/images/1380108907flower_5085.jpg' ],
                [ 'colorName' => '5084', 'colorUrl' => 'http://www.alize.gen.tr/images/1380108896flower_5084.jpg' ],
                [ 'colorName' => '5310', 'colorUrl' => 'http://www.alize.gen.tr/images/14383404655310.jpg' ],
                [ 'colorName' => '5081', 'colorUrl' => 'http://www.alize.gen.tr/images/1380108849flower_5081.jpg' ],
                [ 'colorName' => '5089', 'colorUrl' => 'http://www.alize.gen.tr/images/1380108957flower_5089.jpg' ],
                [ 'colorName' => '5088', 'colorUrl' => 'http://www.alize.gen.tr/images/1380108944flower_5088.jpg' ],
                [ 'colorName' => '5080', 'colorUrl' => 'http://www.alize.gen.tr/images/1380108839flower_5080.jpg' ],
                [ 'colorName' => '5315', 'colorUrl' => 'http://www.alize.gen.tr/images/14383405115315.jpg' ],
                [ 'colorName' => '5082', 'colorUrl' => 'http://www.alize.gen.tr/images/1380108870flower_5082.jpg' ],
                [ 'colorName' => '5078', 'colorUrl' => 'http://www.alize.gen.tr/images/1380108820flower_5078.jpg' ],
                [ 'colorName' => '5087', 'colorUrl' => 'http://www.alize.gen.tr/images/1380108932flower_5087.jpg' ],
                [ 'colorName' => 'комбинация 1', 'colorUrl' => 'http://www.alize.gen.tr/images/1380890574kombine_1.jpg' ],
                [ 'colorName' => 'комбинация 2', 'colorUrl' => 'http://www.alize.gen.tr/images/1380890614kombin_2.jpg' ],
                [ 'colorName' => 'комбинация 3', 'colorUrl' => 'http://www.alize.gen.tr/images/1380890714kombin_3.jpg' ],
                [ 'colorName' => 'комбинация 4', 'colorUrl' => 'http://www.alize.gen.tr/images/1380890652kombin_4.jpg' ],
                [ 'colorName' => 'комбинация 5', 'colorUrl' => 'http://www.alize.gen.tr/images/1380890471kombin_5.jpg' ],       
            ],
            'price' => 290
        ],  
        [
            'name' => 'Стил',
            'url' => 'http://rukodelie-rostov.ru/catalog/kantri-new.html',
            'txt' => '',
            'colors' => [
                [ 'colorName' => '55 белый', 'colorUrl' => 'http://www.alize.gen.tr/images/142236094555.jpg' ],
                [ 'colorName' => '5 беж', 'colorUrl' => 'http://www.alize.gen.tr/images/142236280005.jpg' ],
                [ 'colorName' => '21 серый', 'colorUrl' => 'http://www.alize.gen.tr/images/142236105421.jpg' ],
                [ 'colorName' => '60 черный', 'colorUrl' => 'http://www.alize.gen.tr/images/142236123560.jpg' ],
                [ 'colorName' => '119 серебристый', 'colorUrl' => 'http://www.alize.gen.tr/images/1422361335119.jpg' ],
                [ 'colorName' => '263 светло бирюзовый', 'colorUrl' => 'http://www.alize.gen.tr/images/1422361610263.jpg' ],
                [ 'colorName' => '40 голубой', 'colorUrl' => 'http://www.alize.gen.tr/images/142236285040.jpg' ],
                [ 'colorName' => '143 пудра', 'colorUrl' => 'http://www.alize.gen.tr/images/1422361433143.jpg' ],
                [ 'colorName' => '14 темно желтый', 'colorUrl' => 'http://www.alize.gen.tr/images/142236098514.jpg' ],
                [ 'colorName' => '34 персиковый', 'colorUrl' => 'http://www.alize.gen.tr/images/142236108634.jpg' ],
                [ 'colorName' => '83 оранжевый', 'colorUrl' => 'http://www.alize.gen.tr/images/142236125883.jpg' ],
                [ 'colorName' => '43 темно фиолетовый', 'colorUrl' => 'http://www.alize.gen.tr/images/142236119743.jpg' ],
                [ 'colorName' => '116 розовый', 'colorUrl' => 'http://www.alize.gen.tr/images/1422362933116.jpg' ],
                [ 'colorName' => '149 фуксия', 'colorUrl' => 'http://www.alize.gen.tr/images/1422361477149.jpg' ],
                [ 'colorName' => '141 василек', 'colorUrl' => 'http://www.alize.gen.tr/images/1422363001141.jpg' ],
                [ 'colorName' => '193 фисташка', 'colorUrl' => 'http://www.alize.gen.tr/images/1422361560193.jpg' ],        
            ],      
            'price' => 270
        ],  
        [
            'url' => 'http://rukodelie-rostov.ru/catalog/bodrum.html',
            'price' => 220
        ],  
        [
            'name' => 'Шекерим',
            'url' => 'http://rukodelie-rostov.ru/catalog/shekerim-mini-kolor.html',
            'txt' => 'Рукодельницы, обратившие свое внимание на пряжу Sekerim, не разочаруются, ведь вязать оригинальные детские вещи из нее увлекательно и просто! Благодаря необычной фантазийной покраске этой пряжи и специальному антиаллергенному составу из нее можно вязать детскую одежду, пледы, шапочки, забавные шарфы и многое другое. Особенность Sekerim  в том, что для окрашивания нити используется специальный компьютерный подбор цвета, и без каких-либо схем и дополнительных усилий на готовом полотне образуются красивые разноцветные полоски и простые, но интересные узоры. Нить средней толщины отличается незначительным расходом, в работе она удобна и приятна.

    Оптимальный размер спиц №3-4; крючка №2-4

    Плотность вязания: 10х10см = 30р.х23п.',
            'colors' => [
                [ 'colorName' => '507', 'colorUrl' => 'http://www.alize.gen.tr/images/1375684751k_d_507.jpg' ],
                [ 'colorName' => '509', 'colorUrl' => 'http://www.alize.gen.tr/images/1375688402k_d_509.jpg' ],
                [ 'colorName' => '508', 'colorUrl' => 'http://www.alize.gen.tr/images/1375684986k_d_508.jpg' ],
                [ 'colorName' => '513', 'colorUrl' => 'http://www.alize.gen.tr/images/1406809066513.jpg' ],
                [ 'colorName' => '514', 'colorUrl' => 'http://www.alize.gen.tr/images/1438932829514.jpg' ],
                [ 'colorName' => '512', 'colorUrl' => 'http://www.alize.gen.tr/images/1406809087512.jpg' ],
                [ 'colorName' => '510', 'colorUrl' => 'http://www.alize.gen.tr/images/1406809137510.jpg' ],
                [ 'colorName' => '505', 'colorUrl' => 'http://www.alize.gen.tr/images/1333109870k_d_505.jpg' ],
                [ 'colorName' => '504', 'colorUrl' => 'http://www.alize.gen.tr/images/1333110097model_kendinden_desenli._504.jpg' ],
                [ 'colorName' => '502', 'colorUrl' => 'http://www.alize.gen.tr/images/1333110074model_kendinden_desenli_502.jpg' ],
                [ 'colorName' => '506', 'colorUrl' => 'http://www.alize.gen.tr/images/1375687961k_d_506.jpg' ],
                [ 'colorName' => '501', 'colorUrl' => 'http://www.alize.gen.tr/images/1333109777k_d_501.jpg' ],
                [ 'colorName' => '109', 'colorUrl' => 'http://www.alize.gen.tr/images/1333109746k_d_109.jpg' ],
                [ 'colorName' => '111', 'colorUrl' => 'http://www.alize.gen.tr/images/1438932969111.jpg' ],
                [ 'colorName' => '110', 'colorUrl' => 'http://www.alize.gen.tr/images/1438932877110.jpg' ],
                [ 'colorName' => '102', 'colorUrl' => 'http://www.alize.gen.tr/images/1333109735k_d_102.jpg' ],
                [ 'colorName' => '101', 'colorUrl' => 'http://www.alize.gen.tr/images/1333109727k_d_101.jpg' ],
            ],
            'price' => 160
        ],  
        [
            'url' => 'http://rukodelie-rostov.ru/catalog/angora-real-40.html',
            'price' => 220
        ],  
        [
            'url' => 'http://rukodelie-rostov.ru/catalog/angora-real-40-batik.html',
            'price' => 240
        ],  
        [
            'url' => 'http://rukodelie-rostov.ru/catalog/angora-gold-simli.html',
            'price' => 190
        ],  
        [
            'url' => 'http://rukodelie-rostov.ru/catalog/kotton-gold.html',
            'price' => 220
        ],  
        [
            'name' => 'Коттон Голд твид',
            'url' => 'http://rukodelie-rostov.ru/catalog/kotton-gold.html',
            'txt' => '',
            'colors' => [
                [ 'colorName' => '55 белый', 'colorUrl' => 'http://www.alize.gen.tr/images/145131170855.jpg' ],
                [ 'colorName' => '62 молочный', 'colorUrl' => 'http://www.alize.gen.tr/images/145580440562.jpg' ],
                [ 'colorName' => '1 молочный', 'colorUrl' => 'http://www.alize.gen.tr/images/145131248201.jpg' ],
                [ 'colorName' => '262 бежевый', 'colorUrl' => 'http://www.alize.gen.tr/images/1451314447262.jpg' ],
                [ 'colorName' => '60 черный', 'colorUrl' => 'http://www.alize.gen.tr/images/145131448560.jpg' ],
                [ 'colorName' => '87 угольный серый', 'colorUrl' => 'http://www.alize.gen.tr/images/145131452787.jpg' ],
                [ 'colorName' => '200 светло - серый', 'colorUrl' => 'http://www.alize.gen.tr/images/1452521561200.jpg' ],
                [ 'colorName' => '67 молочно-бежевый', 'colorUrl' => 'http://www.alize.gen.tr/images/145131439867.jpg' ],
                [ 'colorName' => '493 каштановый', 'colorUrl' => 'http://www.alize.gen.tr/images/1451314582493.jpg' ],
                [ 'colorName' => '2 горчичный', 'colorUrl' => 'http://www.alize.gen.tr/images/145131560702.jpg' ],
                [ 'colorName' => '89 терракот', 'colorUrl' => 'http://www.alize.gen.tr/images/145131472989.jpg' ],
                [ 'colorName' => '243 красный', 'colorUrl' => 'http://www.alize.gen.tr/images/1451314687243.jpg' ],
                [ 'colorName' => '33 темно- розовый', 'colorUrl' => 'http://www.alize.gen.tr/images/145131489333.jpg' ],
                [ 'colorName' => '38 коралловый', 'colorUrl' => 'http://www.alize.gen.tr/images/145131494838.jpg' ],
                [ 'colorName' => '149 фуксия', 'colorUrl' => 'http://www.alize.gen.tr/images/1451314621149.jpg' ],
                [ 'colorName' => '649 рубин', 'colorUrl' => 'http://www.alize.gen.tr/images/1451314652649.jpg' ],
                [ 'colorName' => '371 светло-розовый', 'colorUrl' => 'http://www.alize.gen.tr/images/1451314809371.jpg' ],
                [ 'colorName' => '98 розовый', 'colorUrl' => 'http://www.alize.gen.tr/images/145131477698.jpg' ],
                [ 'colorName' => '99 багряник', 'colorUrl' => 'http://www.alize.gen.tr/images/145131501899.jpg' ],
                [ 'colorName' => '616 фиолетовый', 'colorUrl' => 'http://www.alize.gen.tr/images/1451315056616.jpg' ],
                [ 'colorName' => '287 бирюзовый', 'colorUrl' => 'http://www.alize.gen.tr/images/1451315313287.jpg' ],
                [ 'colorName' => '245 морская волна  бирюзовый', 'colorUrl' => 'http://www.alize.gen.tr/images/1451315402245.jpg' ],
                [ 'colorName' => '236 темно-голубой', 'colorUrl' => 'http://www.alize.gen.tr/images/1451315146236.jpg' ],
                [ 'colorName' => '40  голубой', 'colorUrl' => 'http://www.alize.gen.tr/images/145949481840_copy.jpg' ],
                [ 'colorName' => '279 джинс', 'colorUrl' => 'http://www.alize.gen.tr/images/1451315108279.jpg' ],
                [ 'colorName' => '610 изумруд', 'colorUrl' => 'http://www.alize.gen.tr/images/1451315248610.jpg' ],
                [ 'colorName' => '372 хаки', 'colorUrl' => 'http://www.alize.gen.tr/images/1451315211372.jpg' ],
                [ 'colorName' => '110 цыпленок', 'colorUrl' => 'http://www.alize.gen.tr/images/1451315576110.jpg' ],
                [ 'colorName' => '612 кислотный', 'colorUrl' => 'http://www.alize.gen.tr/images/1451315522612.jpg' ],
            ],      
            'price' => 220
        ],      
        [
            'url' => 'http://rukodelie-rostov.ru/catalog/dekofur.html',
            'price' => 140
        ],      
        [
            'url' => 'http://rukodelie-rostov.ru/catalog/marifetli.html',
            'price' => 220
        ],          
    ];


    function checkSku($product){
        global $aSku;
        preg_match('/([а-яёА-Я]+)-(\d+)/', $product['sku'], $matches);
        // echo "sku == ";
        // print_r($matches);
        $skuChar = $matches[1];
        $skuNum = $matches[2];
        foreach ($aSku as $itemSku){
            if ($itemSku['char'] == $skuChar){
                foreach ($itemSku['number'] as $n){
                    if ($n == $skuNum){
                        return true;
                    }
                }
            }
        }
        return false;
    }


    function save($product){
        global $db_host, $db_user, $db_pass, $db_name, $db_link;

        echo "SAVE product: ".$product['name']."\n";
        insertProduct($product);
    }


    function findKeyPrj($str, $aKey, &$productKey){
        // echo "str == $str\n";    
        foreach ($aKey as $key){
            // echo "key == ".$key['key']."\n";
            if (preg_match('/'.$key['key'].'/', $str, $matches)){
                $productKey = $key['productKey'];           
                return 1;
            }
        }
        return 0;
    }


    function parsePage($u){
        $aKey = [
            [ 'productKey' => 'forFilter',  'key' => 'Для фильтрации' ],            
            [ 'productKey' => 'season',     'key' => 'Сезонность' ],
            [ 'productKey' => 'purpose',    'key' => 'Назначение' ],    
            [ 'productKey' => 'techEmbr',   'key' => 'Способ вязания' ],        
            [ 'productKey' => 'length',     'key' => 'Длина нити в мотке' ],        
            [ 'productKey' => 'weight',     'key' => 'Вес мотка' ], 
            [ 'productKey' => 'thread',     'key' => 'Структура нити' ],    
            [ 'productKey' => 'structure',  'key' => 'Состав' ],        
            [ 'productKey' => 'kolInPack',  'key' => 'Количество в упаковке' ],                                             
        ];
        $html = file_get_html($u['url']);
        $product = [];

        $product['colorCode'] = 0; 
        $div = $html->find('.catalog_head', 0);
        $h1 = $div->find('h1', 0);
        if (!empty($u['name'])){
            $product['name'] = $u['name'];      
        }else{
            $product['name'] = trim($h1->plaintext);
        }
        echo "name == ".$product['name']."\n";

        $product['idCategory'] = $this->idCategory;
        $div = $html->find('.main_img', 0);
        $img = $div->find('img', 0);    
        $product['imgUrl'] = $img->attr['data-large'];
        preg_match('/\/(\w+\.jpg)/', $product['imgUrl'], $matches);         
        $product['imgFile'] = $matches[1];      

        $table = $html->find('.item_features', 0);
        $td = $table->find('.item_description', 0);
        $product['description'] = '';
        if (!empty($u['txt'])){
            $product['description'] = $u['txt'];        
        }else{
            foreach ($td->find('p') as $p) {
                $product['description'] = $product['description'].' '.trim($p->plaintext);
            }   
        }

        foreach ($table->find('tr') as $tr) {
            $attrName = trim($tr->find('td', 0)->plaintext);
            $attrVal = trim($tr->find('td', 1)->plaintext);
            $patterns = [];
            $patterns[0] = '/&nbsp;/';
            $replacements = [];
            $replacements[0] = '';
            $attrVal =  preg_replace($patterns, $replacements, $attrVal);       
            // echo "> ".$attrName." ".$attrVal."\n";
            if ($this->findKeyPrj($attrName, $aKey, $key)){
                $product[$key] = $attrVal;
            }
        }
        $product['price'] = $u['price'];
        echo "+++++++++ product: \n";
        print_r($product);
        //insertCategory($data);

        // $product['sku'] = '';
        $product['idManufacturer'] = $this->idManufacturer;
        $product['weight'] = 0;        
        $product['idProduct'] = $this->insertProduct($product);
        $product['idParent'] = $product['idProduct'];
        if (empty($u['colors'])){
            $div = $html->find('.colors_list', 0);
            foreach ($div->find('.colour_card') as $color){
                if (preg_match('/(\d+)\s+(.+)/', trim($color->find('.name',0)->plaintext), $matches)){
                    $product['colorCode'] = $matches[1];
                    $product['colorName'] = $matches[2];                    
                }else{
                    $product['colorName'] = trim($color->find('.name',0)->plaintext);                    
                    $product['colorCode'] = trim($color->find('.name',0)->plaintext);
                }
                
                // echo "art_no: ".trim($color->find('.art_no',0)->plaintext)."\n";
                $product['imgUrl'] = trim($color->find('img',0)->src);
                preg_match('/\/(\w+\.jpg)/', $product['imgUrl'], $matches);
                $product['imgFile'] = $matches[1];    
                $this->insertProduct($product);                 
            }
        }else{
            foreach ($u['colors'] as $color){
                $product['imgUrl'] = $color['colorUrl'];
                $product['colorName'] = $color['colorName'];                      
                $product['colorCode'] = $color['colorName'];                  
                if (preg_match('/\/(\w+\.jpg)/', $product['imgUrl'], $matches)){
                    $product['imgFile'] = $matches[1];                 
                }
                $this->insertProduct($product);           
            }         
        }
        return $product;
    }


    public function actionIndex($mode){
        if ($mode == 'fill'){
            foreach ($this->aUrl as $u) {
                $this->parsePage($u);
                sleep(1);
            } 
        }         
        if ($mode == 'sku_1c'){
            $this->sku_1c();
        }
    }


    public function sku_1c(){
        $f = fopen("db/gold.spr", "r");
        if ($f) {
            while (($str = fgets($f)) !== false) {
                $str = iconv("CP1251", "UTF-8", $str);
                $a = split(';', $str);
                if (count($a) == 18){
                    $id1c = $a[0];
                    $name = $a[2]; 
                    //echo $id1c." ".$name."\n";                  
                    preg_match('/([а-яА-ЯёЁ]+-\d+)\s+/', $name, $matches);
                    if (!empty($matches[1])){
                       $sku = $matches[1]; 
                       echo "[ 'id1c' => ".$id1c.", 'sku' => '".$sku."' ],\n";
                    }                    
                }
            }
            if (!feof($f)) {
                echo "Error: unexpected fgets() fail\n";
            }
            fclose($f);
        }
    }

}




<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Journal;
use AppBundle\Resources\SUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     *
     */
    public function indexAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        /** @var Journal[] $journals */
        $journals = $em->getRepository('AppBundle:Journal')->findBy(array(), array('date' => 'DESC'));

        /** @var Journal $j */
        foreach($journals as $j)
            if($j->getNumberSet())
                $j->setNumber(1);


        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'journals' => $journals
        ]);
    }


    /**
     * @Route("/preland/{title}/{year}/{month}/{number}", name="detail", requirements={
     *         "month": "\d+",
     *         "number": "\d+",
     *         "year": "\d+"
     *     })
     */
    public function detailAction(Request $request, $title, $year, $month, $number)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var Journal $journal */
        $journal = $em->getRepository('AppBundle:Journal')->findOneBy(['url' => $title, 'month' => $month, 'year' => $year, 'number' => $number]);
        if(!$journal)
            $journal = $em->getRepository('AppBundle:Journal')->findOneBy(['url' => $title, 'month' => $month, 'year' => $year, 'number' => null]);
        if($journal->getNumberSet())
            $journal->setNumber(1);


        /** @var Journal[] $all */
        $all = $em->getRepository('AppBundle:Journal')->findBy(['url' => $title]);

        foreach($all as $j)
            if($j->getNumberSet())
                $j->setNumber(1);

        foreach($all as $key => $a)
            if($a->getId() == $journal->getId())
                unset($all[$key]);

        return $this->render('default/detail.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'journal' => $journal,
            'rest' => $all
        ]);
    }

    /**
     * @Route("/pay/{title}/{year}/{month}/{number}", name="read", requirements={
     *         "month": "\d+",
     *         "number": "\d+",
     *         "year": "\d+"
     *     })
     *
     */
    public function payAction(Request $request, $title, $year, $month, $number)  {
        $html =  <<<HTML
            <div style="padding-top: 25%; min-height: 550px">
                <div class="subscribe">
                    <a href="http://join-men.kioskplus.ru/subscribe/?cr=78089&setpreprod=1" class="button flat">Получить доступ</a>
                    <p class="description">
                        Кликнув на кнопку “Получить доступ”, Вы соглашаетесь с
                        <br>
                        условиями подписки на доступ ко всему каталогу.
                        <br>
                        Стоимость 12 руб. с учетом НДС в день.
                    </p>
                </div> 
            </div>
HTML;





    }




    /**
     * @Route("/{title}/{year}/{month}/{number}", name="read", requirements={
     *         "month": "\d+",
     *         "number": "\d+",
     *         "year": "\d+"
     *     })
     *
     */
    public function readAction(Request $request, $title, $year, $month, $number)
    {

        if(!empty($_REQUEST['user_back']))
            SUtils::trace($_REQUEST);

        $page = $request->query->get('page');
        if(!$page) $page = 1;

        $em = $this->getDoctrine()->getManager();
        /** @var Journal $journal */
        $journal = $em->getRepository('AppBundle:Journal')->findOneBy(['url' => $title, 'month' => $month, 'year' => $year, 'number' => $number]);
        if(!$journal)
            $journal = $em->getRepository('AppBundle:Journal')->findOneBy(['url' => $title, 'month' => $month, 'year' => $year, 'number' => null]);

        $p = (int)$page;
        if($p < 10) $p = '00'.$p;
        elseif($p < 100)$p = '0'.$p;

        $html_path = '/html/'.$journal->getPath().'/'.$p.'.html';
        $html_dir_path = '/html/'.$journal->getPath().'/';

//        $fi = new \FilesystemIterator(realpath($this->get('kernel')->getRootDir().'/../web/').$html_dir_path, \FilesystemIterator::SKIP_DOTS);
        $iterator = new \GlobIterator(realpath($this->get('kernel')->getRootDir().'/../web/').$html_dir_path. '*.html', \FilesystemIterator::KEY_AS_FILENAME);
        $pages = iterator_count($iterator);

        $fileNames = [];
        foreach($iterator as $file)
            $fileNames[] = $file->getFileName();


/*Кликнув на кнопку “Получить доступ”, Вы соглашаетесь с
                        <br>
                        условиями подписки на доступ ко всему каталогу.
                        <br>
                        Стоимость 12 руб. с учетом НДС в день.*/

        $current_url = $request->get('_route');
        $baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();

        $back_url = $baseurl.$request->getPathInfo().'?page='.$page.'&user_back=yes';


//        SUtils::trace($back_url);


//        $content = file_get_contents('http://join-men.kioskplus.ru/subscribe/?cr=78089&setpreprod=1&returnurl='.$back_url);
//        $content = substr($content, strpos($content, '<form id="form-step1"'), strrpos($content, '</form>'));
//        $content = str_replace('href="/platinum/#!/terms/terms"', 'href="http://join-men.kioskplus.ru/platinum/#!/terms/terms"', $content);
//
//
//
////        $content = str_replace('<meta charset="utf-8">', '', $content);
////        $content = str_replace('<meta name="description" content=" ">', '', $content);
////        $content = str_replace('<meta name="description" content=" ">', '', $content);
////        $content = str_replace('<meta name="keywords" content="">', '', $content);
////        $content = str_replace('<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">', '', $content);
////        $content = str_replace('<meta name="google-site-verification" content="">', '', $content);
////        $content = str_replace('<meta http-equiv="content-language" content="ru">', '', $content);
////        $content = str_replace('<meta http-equiv="X-Frame-Options" content="deny">', '', $content);
////        $content = str_replace('<meta charset="utf-8">', '', $content);
////        $content = str_replace('<meta charset="utf-8">', '', $content);
////        $content = str_replace('<meta charset="utf-8">', '', $content);
//
////        SUtils::trace($content);
//
        if($page > $journal->getListing() + 4 && empty($_SESSION['authorized'])){
//
////            $html = '<iframe src="http://join-men.kioskplus.ru/subscribe/?cr=78089&setpreprod=1&returnurl=http://beta.kioskplus.ru/" width="468" height="60" align="left">';
////            $html = '<iframe src="http://join-men.kioskplus.ru/subscribe/?cr=78089&setpreprod=1&returnurl=http://beta.kioskplus.ru/" name="targetframe" allowTransparency="true" scrolling="no" frameborder="0" ></iframe>';
//
//            $html = '<div class="inner_sub">';
//
//            $html .= '<style>
///* ----- START head-meta v0001 css ----- */
///* ----- END head-meta v0003 css ----- */
///* ----- START anti-fraud v0003 css ----- */
///* ----- END anti-fraud v0005 css ----- */
///* ----- START ios-iphone4-viewport v0005 css ----- */
///* ----- END ios-iphone4-viewport v0001 css ----- */
///* ----- START orientation v0001 css ----- */
///* ----- END orientation v0001 css ----- */
///* ----- START noscript-tracking v0001 css ----- */
///* ----- END noscript-tracking v0001 css ----- */
///* ----- START text-header v0001 css ----- */
///* ----- END text-header v0008 css ----- */
///* ----- START logo v0008 css ----- */
///*logo css*/
///*colors*/
//.logo-container
//{
//background: gray;
//}
///*structure*/
//.logo-container
//{
//width:100%;
//height:50px;
//}
///* ----- END logo v0001 css ----- */
///* ----- START content v0001 css ----- */
///*content-staticImg*/
///*colors*/
//.content-staticImg-container
//{
//background: black;
//}
///*structure*/
//.content-staticImg-container
//{
//width:100%;
//min-height:150px;
//}
///* ----- END content v0002 css ----- */
///* ----- START form-step1 v0002 css ----- */
///* ----- END form-step1 v0006 css ----- */
///* ----- START insert-number v0006 css ----- */
///* ----- END insert-number v0004 css ----- */
///* ----- START checkbox v0004 css ----- */
//#mt-checkbox-container input:checked,#mt-checkbox-container input:not(checked){position:absolute;top:-500px;
//
//left:-900px;
//
//}
//#mt-checkbox-container label{display:inline-block;position:relative}
//#mt-checkbox-container input:checked + label,#mt-checkbox-container input:not(checked) + label{font-size:13px;cursor:pointer}
//#mt-checkbox-container input:not(checked) + label:before{border:1px solid #3E3E3E;background-color:#EFEFEF;content:"\00a0";display:inline-block;width:14px;height:14px;font-size:15px;line-height:15px;margin-right:5px}
//#mt-checkbox-container input:checked + label:before{border:1px solid #3E3E3E;background-color:#EFEFEF;color:#3E3E3E;content:"\2714";font-size:15px;text-align:center;line-height:15px;font-weight:bold}
//.empty-terms{color:#FF0000;text-align:center}
//
//
///* ----- END checkbox v0007 css ----- */
///* ----- START button-step1 v0007 css ----- */
//.disabledColors{background:#ccc !important;color:#fff !important}
///* ----- END button-step1 v0010 css ----- */
///* ----- START form-step1 v0010 css ----- */
///* ----- END form-step1 v0006 css ----- */
///* ----- START links v0006 css ----- */
//.mt-internet-plus img{background:url("http://s.motime.com/img//wl/webstore_webapp/landing_images/general/carrier/sprite_FR.png?v=20170407091212") no-repeat -58px -65px;background-size:208px 81px;height:30px;width:100px}
///* ----- END links v0006 css ----- */
///* ----- START catlegals v0006 css ----- */
///* ----- END catlegals v0001 css ----- */
///* ----- START filter-digit v0001 css ----- */
///* ----- END filter-digit v0001 css ----- */
///* ----- START alternative-domain-login v0001 css ----- */
///* ----- END alternative-domain-login v0001 css ----- */
///* ----- START landing-simulator v0001 css ----- */
///* ----- END landing-simulator v0001 css ----- */
///* ----- START msisdn-from-querystring v0001 css ----- */
///* ----- END msisdn-from-querystring v0001 css ----- */
///* ----- START cat-pixel v0001 css ----- */
///* ----- END cat-pixel v0001 css ----- */
//</style><style>/* ------- START mt_css_work.css ----- */
//html{font-family:sans-serif;-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%}
//*,*:before,*:after{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box}
//img,fieldset{border:none;vertical-align:middle}
//a,a:active,a:focus,a:hover,a:visited{text-decoration:underline}
//.mt-container-wrap{margin:0 auto}
//.text-center{text-align:center}
//.text-left{text-align:left}
//.text-right{text-align:right}
//.mt-container-wrap{width:100%}
//.col-sm-6,.col-sm-12,.col-md-6,.col-md-12,.col-lg-6,.col-lg-12{position:relative;min-height:1px;width:100%}
//body.landscape .col-sm-6{width:50%;float:left}
//body.landscape .col-md-6{width:50%;float:left}
//.mt-container-wrap:before,.mt-container-wrap:after,.row:before,.row:after{content:"";display:table}
//.mt-container-wrap:after,.row:after{clear:both}
//label{display:block;max-width:100%;margin-bottom:5px}
//.btn{display:inline-block;text-align:center;vertical-align:middle;-ms-touch-action:manipulation;touch-action:manipulation;cursor:pointer;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none}
//.btn.disabled,.btn[disabled],fieldset[disabled] .btn{cursor:not-allowed;opacity:0.65;filter:alpha(opacity=65);-webkit-box-shadow:none;box-shadow:none}
//.text-item-label{display:inline;font-size:75%;font-weight:700;color:#fff;text-align:center;white-space:nowrap;vertical-align:baseline}
//.clearfix{clear:both}
//.mt-inpage-spinner{margin:20px auto;width:50px;height:40px;text-align:center;font-size:10px}
//.mt-inpage-spinner > div{background-color:#333;height:100%;width:6px;display:inline-block;-webkit-animation:sk-stretchdelay 1.2s infinite ease-in-out;animation:sk-stretchdelay 1.2s infinite ease-in-out}
//.mt-inpage-spinner .rect2{-webkit-animation-delay:-1.1s;animation-delay:-1.1s}
//.mt-inpage-spinner .rect3{-webkit-animation-delay:-1s;animation-delay:-1s}
//.mt-inpage-spinner .rect4{-webkit-animation-delay:-.9s;animation-delay:-.9s}
//.mt-inpage-spinner .rect5{-webkit-animation-delay:-.8s;animation-delay:-.8s}
//@-webkit-keyframes sk-stretchdelay{
//0%,100%,40%{-webkit-transform:scaleY(.4)}
//20%{-webkit-transform:scaleY(1)}
//}
//@keyframes sk-stretchdelay{
//0%,100%,40%{transform:scaleY(.4);-webkit-transform:scaleY(.4)}
//20%{transform:scaleY(1);-webkit-transform:scaleY(1)}
//}
//</style><style type="text/css" title="currentStyle">html{font-size:100%;background-color:#fff}
//*{margin:0;padding:0;-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box}
//body{text-align:center;font-family:"Trebuchet MS",Arial,Helvetica,sans-serif;background-color:#fff;color:#000}
//img{border:none}
//fieldset{border:none}
//button,label{cursor:pointer}
//a,a:active,a:focus,a:hover,a:visited{color:#000}
//p{font-size:0.750rem;margin:3px 2px}
///* Logo */
//.custom-logo{background-color:#DE4249;height:44px;padding:2px 0;display:none}
//.custom-logo-content{background:url(http://s.motime.com/img/leafengine_preprod/ru_kioskplusmen//0001/objects/logo.png?v=1491392930) no-repeat 10px center;background-size:66px 40px;height:40px}
///* Main image */
//.custom-content-description-big-dynamicImg-container{display:none}
//
//
//
//section.custom-container-item{background:url(http://s.motime.com/img/leafengine_preprod/ru_kioskplusmen//0001/objects/staticImgBgkDesktop.jpg?v=1491392930) no-repeat center top;background-size:auto 100%;height:386px}
//section.custom-container-main{width:70%;margin:0 auto}
//
//body.landscape section.custom-container-main{padding:3% 0}
//body.landscape section.custom-container-main form{width:96%;margin:0 auto}
///* Form */
//.spaceHiddenCheckbox{height:0.250rem !important}
//label{margin-bottom:3px !important}
//.btn{width:80%;margin:3px auto !important;padding:7px 0;color:#fff;font-size:1.250rem;background-color:#DE4249;border:none;-webkit-border-radius:50px;-moz-border-radius:50px;border-radius:50px}
//.arrow-button{display:none}
///* Form WiFi Insert Number */
//.custom-insert-number label{font-size:1.000rem;font-weight:700}
//.custom-insert-number input#msisdn{width:80%;margin:0 auto;padding:7px 0;text-align:center;color:#333;font-size:1.250rem;border:2px solid #DE4249;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0}
///* Form WiFi PIN */
//.custom-insert-pin label{font-size:1.000rem;font-weight:700}
//.custom-insert-pin input#password{width:80%;margin:0 auto;padding:7px 0;text-align:center;color:#333;font-size:1.250rem;border:2px solid #DE4249;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0}
///* Errors */
//.custom-insert-number-error p{color:#f00;font-weight:700}
//.custom-empty-terms p{color:#f00;font-weight:700}
//.custom-insert-pin-error p{color:#f00;font-weight:700}
///* TYP */
//h1{font-size:1.500rem;margin:3px 2px}
//h2{font-size:1.000rem;margin:3px 2px}
//body.custom-page-thankyou .btn{width:80%;padding:7px 0;color:#fff;font-size:1.250rem;background-color:#DE4249;border:none;-webkit-border-radius:10px;-moz-border-radius:10px;border-radius:10px}
//body.custom-page-thankyou .btn a{color:#fff;text-decoration:none}
///* Other Useful Variables */
///* Detect Peculiar Carrier */
//
//
///* Detect 3G/WiFi */
//
//
//
///* Detect WiFi Carrier logo inside/Fake Payment */
//
//
///* Detect Smartphones */
//
///* Detect Tablet */
//
///* Detect Desktop */
//
//
///* Detect Operative System */
//
//
//
//</style><style type="text/css" title="currentStyle">html{font-size:100%;background-color:#fff}
//*{margin:0;padding:0;-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box}
//body{text-align:center;font-family:"Trebuchet MS",Arial,Helvetica,sans-serif;background-color:#fff;color:#000}
//img{border:none}
//fieldset{border:none}
//button,label{cursor:pointer}
//a,a:active,a:focus,a:hover,a:visited{color:#000}
//p{font-size:0.750rem;margin:3px 2px}
///* Logo */
//.custom-logo{background-color:#DE4249;height:44px;padding:2px 0;display:none}
//.custom-logo-content{background:url(http://s.motime.com/img/leafengine_preprod/ru_kioskplusmen//0001/objects/logo.png?v=1491392930) no-repeat 10px center;background-size:66px 40px;height:40px}
///* Main image */
//.custom-content-description-big-dynamicImg-container{display:none}
//
//
//
//section.custom-container-item{background:url(http://s.motime.com/img/leafengine_preprod/ru_kioskplusmen//0001/objects/staticImgBgkDesktop.jpg?v=1491392930) no-repeat center top;background-size:auto 100%;height:386px}
//section.custom-container-main{width:70%;margin:0 auto}
//
//body.landscape section.custom-container-main{padding:3% 0}
//body.landscape section.custom-container-main form{width:96%;margin:0 auto}
///* Form */
//.spaceHiddenCheckbox{height:0.250rem !important}
//label{margin-bottom:3px !important}
//.btn{width:80%;margin:3px auto !important;padding:7px 0;color:#fff;font-size:1.250rem;background-color:#DE4249;border:none;-webkit-border-radius:50px;-moz-border-radius:50px;border-radius:50px}
//.arrow-button{display:none}
///* Form WiFi Insert Number */
//.custom-insert-number label{font-size:1.000rem;font-weight:700}
//.custom-insert-number input#msisdn{width:80%;margin:0 auto;padding:7px 0;text-align:center;color:#333;font-size:1.250rem;border:2px solid #DE4249;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0}
///* Form WiFi PIN */
//.custom-insert-pin label{font-size:1.000rem;font-weight:700}
//.custom-insert-pin input#password{width:80%;margin:0 auto;padding:7px 0;text-align:center;color:#333;font-size:1.250rem;border:2px solid #DE4249;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0}
///* Errors */
//.custom-insert-number-error p{color:#f00;font-weight:700}
//.custom-empty-terms p{color:#f00;font-weight:700}
//.custom-insert-pin-error p{color:#f00;font-weight:700}
///* TYP */
//h1{font-size:1.500rem;margin:3px 2px}
//h2{font-size:1.000rem;margin:3px 2px}
//body.custom-page-thankyou .btn{width:80%;padding:7px 0;color:#fff;font-size:1.250rem;background-color:#DE4249;border:none;-webkit-border-radius:10px;-moz-border-radius:10px;border-radius:10px}
//body.custom-page-thankyou .btn a{color:#fff;text-decoration:none}
///* Other Useful Variables */
///* Detect Peculiar Carrier */
//
//
///* Detect 3G/WiFi */
//
//
//
///* Detect WiFi Carrier logo inside/Fake Payment */
//
//
///* Detect Smartphones */
//
///* Detect Tablet */
//
///* Detect Desktop */
//
//
///* Detect Operative System */
//
//
//
//</style>
//';
//            $html .= '<style>';
//            $html .= <<<CSS
//.inner_sub{
//    width: 50%;
//    margin: auto;
//    margin-top: 15%;
//}
//
//CSS;
//$html .= '</style>';
//
//            $html .= $content;
//            $html .= '</div>';

//            $html =  '
//            <div style="padding-top: 25%; min-height: 550px">
//                <div class="subscribe">
//                    Введите номер, чтобы авторизоваться
//                    <a href="http://join-men.kioskplus.ru/subscribe/?cr=78089&setpreprod=1&returnurl='.$back_url.'" class="button flat">Получить доступ</a>
//                    <p class="description">
//
//                        <input>
//                    </p>
//                </div>
//            </div>
//';
            $html =  '
                <div style="padding-top: 25%; min-height: 550px">
                    <div class="subscribe">
                        <a href="http://join-men.kioskplus.ru/subscribe/?cr=78089&setpreprod=1&returnurl='.$back_url.'" class="button flat">Получить доступ</a>
                        <p class="description">
                            Кликнув на кнопку “Получить доступ”, Вы соглашаетесь с
                            <br>
                            условиями подписки на доступ ко всему каталогу.
                            <br>
                            Стоимость 12 руб. с учетом НДС в день.
                        </p>
                    </div> 
                </div>
            ';
        }else
            $html = file_get_contents(realpath($this->get('kernel')->getRootDir().'/../web/').$html_path);

        return $this->render('default/read.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'html_path' => $html_path,
            'html' => $html,
            'journal' => $journal,
            'page' => $page,
            'pages' => $pages,
            'fileNames' => $fileNames
        ]);
    }



    /**
     * @Route("/admin", name="admin")
     *
     */
    public function adminAction(Request $request)
    {

        $added = $request->query->get('added');

//        SUtils::trace($added);

        if($added){
            $this->addJournal($request);
        }


        $conn_id = ftp_connect('ftp.buongiorno.ru');
        $login_result = ftp_login($conn_id, 'burda', 'burda1898');
        $contents = ftp_nlist($conn_id, "./kiosk_plus");
        ftp_close($conn_id);

        $not_dirs = ['json', 'pdf', 'prodtest', 'srv'];
        $j_exists = [];

        $em = $this->getDoctrine()->getManager();
        /** @var Journal[] $journals */
        $journals = $em->getRepository('AppBundle:Journal')->findAll();
        foreach($journals as $j)
            $j_exists[] = $j->getPath();

        $dirs = [];
        $prefix = [];
        foreach($contents as $dir){
            $d = str_replace('./kiosk_plus/','',$dir);
            if(!in_array($d, $not_dirs) && !in_array($d, $j_exists)) {
                $dirs[] = $d;
                $prefix[] = substr($d,0, strpos($d, '/'));
            }
        }

        return $this->render('default/admin.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'dirs' => $dirs,
            'prefix' => $prefix
        ]);
    }


    /**
     * @Route("/add", name="add")
     */
    public function addAction(Request $request)
    {

        $dir = $request->request->get('dir');

        $prefix = substr($dir,0, strpos($dir, '/'));
        $year = substr($dir,strpos($dir, '_') + 1, 4);
        $number = substr($dir,strrpos($dir,'_') + 1);
        $genre = 'stylelife';
        $publisher = 'publisher';

        $identifier = '';

        $conn_id = ftp_connect('ftp.buongiorno.ru');
        $login_result = ftp_login($conn_id, 'burda', 'burda1898');
        $contents = ftp_nlist($conn_id, "./kiosk_plus/srv/images/".$dir.'/');

        $first_dir = array_shift($contents);
        $first_dir = substr($first_dir, strrpos($first_dir,'/') + 1);

        $img_dir = './kiosk_plus/srv/images/'.$dir.'/'.$first_dir;

        $contents = ftp_nlist($conn_id, $img_dir);

        ftp_close($conn_id);


        $img = array_shift($contents);
        $img_name = substr($img, strrpos($img,'/') + 1);

        if($img_name == 'icon.jpg'){
            $img = array_shift($contents);
            $img_name = substr($img, strrpos($img,'/') + 1);
        }

        return $this->render('default/add.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'path' => $dir,
            'genre' => $genre,
            'year' => $year,
            'publisher' => $publisher,
            'number' => $number,
            'first_dir' => $first_dir,
            'img_name' => $img_name
        ]);
    }

//    /**
//     * @Route("/subscribe/{id}/", name="subscribe"})
//     *
//     */
//    public function subscribeAction(Request $request, $id)
//    {
//
//        file_get_contents();
//
//
//        return $this->render('default/detail.html.twig', [
//            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
//        ]);
//    }


    public function addJournal(Request $request){

//        SUtils::trace($request->request->get('date'));

//        SUtils::dump($request->request->get('date'));

        $date = \DateTime::createFromFormat('Y-m-d', $request->request->get('date'));
        $em = $this->getDoctrine()->getManager();
        $journal = new Journal();

//        SUtils::trace($date);

//        $identifier = $date->format();
        //todo use this somehow
        $identifier = '';

        $journal->setTitle($request->request->get('name'));
        $journal->setDescription('');
        $journal->setImageMain($request->request->get('image_main'));
        $journal->setUrl($request->request->get('url'));
        $journal->setDate($date);
        $journal->setNumber($request->request->get('number'));
        $journal->setIdentifier($identifier);
        $journal->setListing($request->request->get('listing'));
        $journal->setPath($request->request->get('path'));
        $journal->setGenre($request->request->get('genre'));
        $journal->setPublisher($request->request->get('publisher'));
        $journal->setYear($request->request->get('year'));
        $journal->setMonth($request->request->get('month'));
        $journal->setDoubleNumber($request->request->get('double_number') == 'on' ? true : false);
        $journal->setDoubleMonth($request->request->get('double_month') == 'on' ? true : false);
        $journal->setEveryWeek($request->request->get('every_week') == 'on' ? true : false);

        $em->persist($journal);
        $em->flush();

        $path = $request->request->get('path');

        $dir = realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR.'web/html/'.$path.'/';
        $img_dir = realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR.'web/images/'.$path.'/';

        if(!is_dir($dir) && !is_dir($img_dir)) {
            $oldmask = umask(0);
            mkdir($dir, 0777, true);
            mkdir($img_dir, 0777, true);
            umask($oldmask);
        }

        $conn_id = ftp_connect('ftp.buongiorno.ru') or die("Couldn't connect to ftp.buongiorno.ru");
        $login_result = ftp_login($conn_id, 'burda', 'burda1898');
        if ((!$conn_id) || (!$login_result))
            die("FTP Connection Failed");


        //downloading html files
        $contents = ftp_nlist($conn_id, "./kiosk_plus/".$path.'/');
        foreach($contents as $file) {
            $file_name = substr($file, strrpos($file, '/') + 1);
            $local_file = $dir . $file_name;
            ftp_get($conn_id, $local_file, $file, FTP_BINARY);
        }


        $contents = ftp_nlist($conn_id, "./kiosk_plus/srv/images/".$path.'/');

        foreach($contents as $d){
            $dir_name = substr($d, strrpos($d, '/') + 1);
            $local_dir = $img_dir . $dir_name;

            if(!is_dir($local_dir)) {
                $oldmask = umask(0);
                mkdir($local_dir, 0777, true);
                umask($oldmask);
            }

            $dir_contents = ftp_nlist($conn_id, "./kiosk_plus/srv/images/".$path.'/'.$dir_name.'/');

            foreach($dir_contents as $image) {
                $image_name = substr($image, strrpos($image, '/') + 1);
                $local_image = $local_dir . '/' . $image_name;
                ftp_get($conn_id, $local_image, $image, FTP_BINARY);
            }
        }

        ftp_close($conn_id);
    }
}

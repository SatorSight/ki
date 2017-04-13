<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\IpAdress;
use AppBundle\Entity\Journal;
use AppBundle\Resources\SUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Session\Session;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     *
     */
    public function indexAction(Request $request)
    {





        $host = $request->getHost();

        $men_j = ['Playboy', 'The Rake', 'Quattroruote', 'Maxim'];




        $em = $this->getDoctrine()->getManager();
        /** @var Journal[] $journals */
        $journals = $em->getRepository('AppBundle:Journal')->findBy(array(), array('date' => 'DESC'));




        if(strpos($host, 'men') !== false)
            foreach ($journals as $key => $jjj)
                if(!in_array($jjj->getTitle(), $men_j))
                    unset($journals[$key]);

        reset($journals);

//        SUtils::trace($journals);





        /** @var Journal $j */
        foreach($journals as $j)
            if($j->getNumberSet())
                $j->setNumber(1);


        $j_names = [];
        foreach ($journals as $jr){
            if(!in_array($jr->getTitle(), $j_names))
                $j_names[] = $jr->getTitle();
        }

        reset($journals);

        //SUtils::dump($journals);

        $keys = array_keys($journals);

        $jour = [];
        $jour[] = $journals[$keys[0]];
        $jour[] = $journals[$keys[1]];


        



//SUtils::trace($jour);





        $journals_grouped = [];
        foreach ($journals as $jj){
            if(empty($journals_grouped[$jj->getTitle()]) || (isset($journals_grouped[$jj->getTitle()]) && count($journals_grouped[$jj->getTitle()]) < 2)) {
                $image = $jj->getImageMain();
                $jj->setImageMain(self::renameImageToMin($jj->getImageMain()));
                $journals_grouped[$jj->getTitle()][] = $jj;
            }
        }


        foreach ($jour as $j)
            if(count($journals_grouped[$j->getTitle()]) > 1)
                array_shift($journals_grouped[$j->getTitle()]);

//        SUtils::dump($journals_grouped);


        foreach($journals_grouped as $key => $j)
            if(count($journals_grouped[$key]) > 1)
                $journals_grouped[$key] = [array_shift($journals_grouped[$key])];


        /*
        вверх
        квартророут
        Psychologies
        Forbes
        Maxim
        Игромания
        SNC
        */

//        SUtils::trace($journals_grouped);
//        SUtils::dump($j_names);
        $j_names = array_reverse($j_names,false);
//        SUtils::dump($j_names);

        $new_journals = [];

        if(!empty($journals_grouped['Quattroruote']))
            $new_journals[] = 'Quattroruote';
        if(!empty($journals_grouped['Psychologies']))
            $new_journals[] = 'Psychologies';
        if(!empty($journals_grouped['Forbes']))
            $new_journals[] = 'Forbes';
        if(!empty($journals_grouped['Maxim']))
            $new_journals[] = 'Maxim';
        if(!empty($journals_grouped['Игромания']))
            $new_journals[] = 'Игромания';
        if(!empty($journals_grouped['SNC']))
            $new_journals[] = 'SNC';


        foreach ($j_names as $name){
            if(!in_array($name,$new_journals))
                $new_journals[] = $name;
        }









        $baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();




        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'journals' => $jour,
            'journals_grouped' => $journals_grouped,
            'j_names' => $new_journals,
            'base_url' => $baseurl,
            'body_class' => self::getBodyClass($request)
        ]);
    }


    /**
     * @Route("/bundle", name="bundle")
     *
     */
    public function bundleAction(Request $request)
    {
        $host = $request->getHost();
        $men_j = ['Playboy', 'The Rake', 'Quattroruote', 'Maxim'];

        $em = $this->getDoctrine()->getManager();
        /** @var Journal[] $journals */
        $journals = $em->getRepository('AppBundle:Journal')->findBy(array(), array('date' => 'DESC'));

        if(strpos($host, 'men') !== false)
            foreach ($journals as $key => $jjj)
                if(!in_array($jjj->getTitle(), $men_j))
                    unset($journals[$key]);


        /** @var Journal $j */
        foreach($journals as $j)
            if($j->getNumberSet())
                $j->setNumber(1);


        $j_names = [];
        foreach ($journals as $jr){
            if(!in_array($jr->getTitle(), $j_names))
                $j_names[] = $jr->getTitle();
        }

        reset($journals);


        $journals_grouped = [];
        foreach ($journals as $jj){
            if(empty($journals_grouped[$jj->getTitle()]) || (isset($journals_grouped[$jj->getTitle()]) && count($journals_grouped[$jj->getTitle()]) < 4)) {
                $jj->setImageMain(self::renameImageToMin($jj->getImageMain()));
                $journals_grouped[$jj->getTitle()][] = $jj;
            }
        }

//        foreach($journals_grouped as $key => $j)
//            if(count($journals_grouped[$key]) > 1)
//                $journals_grouped[$key] = [array_shift($journals_grouped[$key])];

        $new_journals = [];

        if(!empty($journals_grouped['Quattroruote']))
            $new_journals[] = 'Quattroruote';
        if(!empty($journals_grouped['Psychologies']))
            $new_journals[] = 'Psychologies';
        if(!empty($journals_grouped['Forbes']))
            $new_journals[] = 'Forbes';
        if(!empty($journals_grouped['Maxim']))
            $new_journals[] = 'Maxim';
        if(!empty($journals_grouped['Игромания']))
            $new_journals[] = 'Игромания';
        if(!empty($journals_grouped['SNC']))
            $new_journals[] = 'SNC';


        foreach ($j_names as $name){
            if(!in_array($name,$new_journals))
                $new_journals[] = $name;
        }

        $baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();

        return $this->render('default/bundle.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'journals_grouped' => $journals_grouped,
            'j_names' => $new_journals,
            'base_url' => $baseurl,
            'body_class' => self::getBodyClass($request)
        ]);
    }



    /**
     * @Route("/all", name="all")
     *
     */
    public function allAction(Request $request)
    {

        $baseUrl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();

        $em = $this->getDoctrine()->getManager();
        /** @var Journal[] $journals */
        $journals = $em->getRepository('AppBundle:Journal')->findAll();

        foreach ($journals as $jj)
            $jj->setImageMain(self::renameImageToMin($jj->getImageMain()));

//        $journals_four_grouped = [];
//        $key = 0;
//        foreach ($journals as $j){
//            if(isset($journals_four_grouped[$key]) && count($journals_four_grouped[$key]) > 3)
//                $key++;
//            $journals_four_grouped[$key][] = $j;
//        }


        return $this->render('default/all.html.twig', [
            'journals' => $journals,
            'base_url' => $baseUrl,
            'body_class' => self::getBodyClass($request)
        ]);

    }


    /**
     * @Route("/cover/{title}/{year}/{month}/{number}", name="detail", requirements={
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
        if(!$journal)
            throw $this->createNotFoundException('404 Journal not found!');
        if($journal->getNumberSet())
            $journal->setNumber(1);





        /** @var Journal[] $all */
        $all = $em->getRepository('AppBundle:Journal')->findBy(['url' => $title], ['date' => 'DESC']);

        foreach($all as $j) {
            if ($j->getNumberSet())
                $j->setNumber(1);
            $j->setImageMain(self::renameImageToMin($j->getImageMain()));
        }

        foreach($all as $key => $a)
            if($a->getId() == $journal->getId())
                unset($all[$key]);

        $baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();

        return $this->render('default/detail.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'journal' => $journal,
            'rest' => $all,
            'base_url' => $baseurl,
            'body_class' => self::getBodyClass($request)
        ]);
    }

//    /**
//     * @Route("/pay/{title}/{year}/{month}/{number}", name="read", requirements={
//     *         "month": "\d+",
//     *         "number": "\d+",
//     *         "year": "\d+"
//     *     })
//     *
//     */
//    public function payAction(Request $request, $title, $year, $month, $number)  {
//        $html =  <<<HTML
//            <div style="padding-top: 25%; min-height: 550px">
//                <div class="subscribe">
//                    <a href="http://join-men.kioskplus.ru/subscribe/?cr=78089&setpreprod=1" class="button flat">Получить доступ</a>
//                    <p class="description">
//                        Кликнув на кнопку “Получить доступ”, Вы соглашаетесь с
//                        <br>
//                        условиями подписки на доступ ко всему каталогу.
//                        <br>
//                        Стоимость 12 руб. с учетом НДС в день.
//                    </p>
//                </div>
//            </div>
//HTML;
//
//
//
//
//
//    }

    public static function ipToInt($ip){
        return (int)str_replace('.', '', $ip);
    }


    /**
     * @Route("/test/", name="test")
     *
     */
    public function testAction(Request $request){
//        $em = $this->getDoctrine()->getManager();
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//        $ipa = '80.83.228.';
//        for ($i = 1; $i < 256; $i++){
//            $ip = new IpAdress();
//            $ip->setIp($ipa.$i)->setOperator('MTS');
//            $em->persist($ip);
//        }
//        $em->flush();
//        $ipa = '80.83.229.';
//        for ($i = 1; $i < 256; $i++){
//            $ip = new IpAdress();
//            $ip->setIp($ipa.$i)->setOperator('MTS');
//            $em->persist($ip);
//        }
//        $em->flush();
//        $ipa = '80.83.230.';
//        for ($i = 1; $i < 256; $i++){
//            $ip = new IpAdress();
//            $ip->setIp($ipa.$i)->setOperator('MTS');
//            $em->persist($ip);
//        }
//        $em->flush();
//        $ipa = '80.83.231.';
//        for ($i = 1; $i < 255; $i++){
//            $ip = new IpAdress();
//            $ip->setIp($ipa.$i)->setOperator('MTS');
//            $em->persist($ip);
//        }
//        $em->flush();
//
//
//        $ipa = '80.83.238.';
//        for ($i = 1; $i < 127; $i++){
//            $ip = new IpAdress();
//            $ip->setIp($ipa.$i)->setOperator('MTS');
//            $em->persist($ip);
//        }
//        $em->flush();
//
//        $ipa = '80.83.239.';
//        for ($i = 1; $i < 127; $i++){
//            $ip = new IpAdress();
//            $ip->setIp($ipa.$i)->setOperator('MTS');
//            $em->persist($ip);
//        }
//        $em->flush();
//
//        $ipa = '80.83.237.';
//        for ($i = 1; $i < 127; $i++){
//            $ip = new IpAdress();
//            $ip->setIp($ipa.$i)->setOperator('MTS');
//            $em->persist($ip);
//        }
//        $em->flush();
//
//        $ipa = '80.83.237.';
//        for ($i = 1; $i < 127; $i++){
//            $ip = new IpAdress();
//            $ip->setIp($ipa.$i)->setOperator('MTS');
//            $em->persist($ip);
//        }
//        $em->flush();
//
//
//
//
//













        die('end');



//        $path = realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR.'web';
//        $width  = 480;
//
//        $em = $this->getDoctrine()->getManager();
//        /** @var Journal[] $journals */
//        $journals = $em->getRepository('AppBundle:Journal')->findAll();
//        foreach ($journals as $j){
//
//            $image = $j->getImageMain();
////
//            $img = new \imagick($path.$image);
//            $img->scaleImage($width, 0);
////        $img->cropImage($width, $height, 0, 0);
//            $img->setImageCompression(\imagick::COMPRESSION_JPEG);
//            $img->setImageCompressionQuality(0);
//
//
//            $image_name = substr($image, strrpos($image, '/') + 1);
//            $image_new_name = $path.str_replace($image_name, '_'.$image_name,$image);
//            $image_final_name = $path.str_replace($image_name, '__'.$image_name,$image);
//
//
//            $img->writeImage($image_new_name);
//
//
//            $sh = exec('cd ../vendor/mozjpeg && ./cjpeg -quality 70 -outfile '.$image_final_name.' '.$image_new_name,$output);
//
//
//
//
//
//            SUtils::dump($output);
//            SUtils::dump($image_name);
//            SUtils::dump($image_new_name);
//            SUtils::trace($image);
//
//
//
//
//        }


    }



    /**
     * @Route("/admin/categories/", name="categories")
     *
     */
    public function adminCategoriesAction(Request $request){

        $em = $this->getDoctrine()->getManager();
        /** @var Category[] $categories */
        $categories = $em->getRepository('AppBundle:Category')->findAll();

        return $this->render('default/read.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,

        ]);
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


        $session = $request->getSession();
//        $session = new Session();
//        $session->start();
        if(!empty($_REQUEST['bridge_token']))
            $session->set('bridge_token', $_REQUEST['bridge_token']);
        if(!empty($request->query->get('alreadyAllowed')))
            $session->set('bridge_token', 'yes');
//            $this->getUser()->setAttribute('bridge_token', $_REQUEST['bridge_token']);

//        if(!empty($_REQUEST['bridge_token']))
//            $session->set('bridge_token', $_REQUEST['bridge_token']);
//        if(!empty($_REQUEST))
//            SUtils::dump($_REQUEST);
//        else echo 'no req';
//        if(!empty($_SESSION))
//            SUtils::trace($_SESSION);
//        else echo 'no session';

        $page = $request->query->get('page');
        if(!$page) $page = 1;

        $em = $this->getDoctrine()->getManager();
        /** @var Journal $journal */
        $journal = $em->getRepository('AppBundle:Journal')->findOneBy(['url' => $title, 'month' => $month, 'year' => $year, 'number' => $number]);
        if(!$journal)
            $journal = $em->getRepository('AppBundle:Journal')->findOneBy(['url' => $title, 'month' => $month, 'year' => $year, 'number' => null]);

        if(!$journal)
            throw $this->createNotFoundException('404 Journal not found!');

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
                        Стоимость 12 руб. с учетом НДС в день.

tech.kioskplus.ru -> http://join-tech.kioskplus.ru/subscribe/?cr=78290&setpreprod=1
avto.kioskplus.ru -> http://join-avto.kioskplus.ru/subscribe/?cr=78369&setpreprod=1
kind.kioskplus.ru -> http://join-kind.kioskplus.ru/subscribe/?cr=77889&setpreprod=1
men-kioskplus.ru -> http://join-men.kioskplus.ru/subscribe/?cr=78089&setpreprod=1

*/

        $current_url = $request->get('_route');
        $baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();

        $back_url = $baseurl.$request->getPathInfo().'?page='.$page.'&user_back=yes';

        /*http://buon.kiosk.buongiorno.ru/subscribe/?cr=78706%E2%80%A6*/





        $host = $request->getHost();

        $join = 'join-';
        $kiosk = 'kioskplus';

        $add_param = '';

        $cr = '78089';
        $domain = 'men';
        if(strpos($host, 'tech') !== false) {
            $cr = '78290';
            $domain = 'tech';
        }
        if(strpos($host, 'avto') !== false) {
            $cr = '78369';
            $domain = 'avto';
        }
        if(strpos($host, 'kind') !== false){
            $cr = '77889';
            $domain = 'kind';
        }
        if(strpos($host, 'premium') !== false) {
            $cr = '78706';
            $domain = 'buon';
            $join = '';
            $kiosk = 'kiosk.buongiorno';
        }

        if(strpos($host, 'men') !== false) {
            /*
* http://join-men.kioskplus.ru/subscribe/?cr=78818 Beeline
http://join-men.kioskplus.ru/subscribe/?cr=78816 MTS

http://join-men.kioskplus.ru/subscribe/?cr=78839&setpreprod=1
http://join-men.kioskplus.ru/subscribe/?cr=78225&setpreprod=1
* */

            $operator = 'undefined';

            $ip = $_SERVER['REMOTE_ADDR'];
            /** @var IpAdress $ipObj */
            $ipObj = $em->getRepository('AppBundle:IpAdress')->findOneBy(['ip' => $ip]);
            if($ipObj)
                if($ipObj->getOperator() == 'Beeline')
                    $operator = 'Beeline';
                else
                    $operator = 'MTS';

            if($operator == 'Beeline'){
                $cr = 78839;
            }

            if($operator == 'MTS'){
                $cr = 78839;
            }
        }



//        SUtils::dump($route);
//        SUtils::trace($url);




        if($page > $journal->getListing() + 4 && empty($session->get('bridge_token'))){

            $html =  '
                <style>#controls .arrow {display: none;}</style>
                <div id="page10">
                    <div class="subscribe">
                        <div class="logo">
                            
                        </div>
                        <a href="http://'.$join.$domain.'.'.$kiosk.'.ru/subscribe/?cr='.$cr.'&setpreprod=1&returnurl='.$back_url.'" class="bt">Оформить подписку</a>
                        <p class="description">Оформляя подписку, вы&nbsp;соглашаетесь с&nbsp;условиями её&nbsp;предоставления. Стоимость цифровой подписки на&nbsp;журналы составляет 20&nbsp;руб.&nbsp;в&nbsp;день включая НДС.</p>
                    </div>
                    <div id="slider">
                        <div class="item slider1">
                           <div class="blur">
                                <div class="inner"> </div>
                            </div>
                        </div>
                        <div class="item slider2">
                           <div class="blur">
                                <div class="inner"> </div>
                            </div>          
                        </div>
                        <div class="item slider3">
                           <div class="blur">
                                <div class="inner"> </div>
                            </div>           
                        </div>
                        <div class="item slider4">
                           <div class="blur">
                                <div class="inner"> </div>
                            </div>           
                        </div>
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
            'fileNames' => $fileNames,
            'body_class' => self::getBodyClass($request)
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


        $not_dirs = ['json', 'pdf', 'prodtest', 'srv', 'test', 'TEST1', 'uploadTest'];
        $j_exists = [];

        $em = $this->getDoctrine()->getManager();
        /** @var Journal[] $journals */
        $journals = $em->getRepository('AppBundle:Journal')->findAll();
        foreach($journals as $j)
            $j_exists[] = $j->getPath();

        $dirs = [];
        $prefix = [];
        $images = [];

        $counter = 0;

        foreach($contents as $dir){

            $counter++;

            $d = str_replace('./kiosk_plus/','',$dir);

            $local_path = realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR.'web/tmp/'.$d.'/';
            if(!in_array($d, $not_dirs) && !in_array($d, $j_exists)) {



                if(!is_dir($local_path)) {

                    $inner_contents = ftp_nlist($conn_id, './kiosk_plus/srv/images/' . $d);
                    $first_image_folder = array_shift($inner_contents);
                    $first_image_folder_name = substr($first_image_folder, strrpos($first_image_folder, '/') + 1);
                    $images_inner = ftp_nlist($conn_id, $first_image_folder);

                    $first_image = '';
                    foreach ($images_inner as $img_inner){
                        if(strpos($img_inner, 'icon') !== false)
                            $first_image = $img_inner;
                    }

                    if(!$first_image){
                        $images[$d] = 'no';

                    }else {

                        $first_image_name = substr($first_image, strrpos($first_image, '/') + 1);

                        $local_dir = realpath($this->getParameter('kernel.root_dir') . '/..') . DIRECTORY_SEPARATOR . 'web/tmp/' . $d . '/';


                        $remote_image = $first_image_folder . '/' . $first_image_name;

                        $local_image = realpath($this->getParameter('kernel.root_dir') . '/..') . DIRECTORY_SEPARATOR . 'web/tmp/' . $d . '/' . $first_image_folder_name . '/' . $first_image_name;


                        $exists = false;
                        if (!is_dir($local_dir)) {
                            $oldmask = umask(0);
                            mkdir($local_dir, 0777, true);
                            umask($oldmask);
                        }

                        if (!is_dir($local_dir . $first_image_folder_name)) {
                            $oldmask = umask(0);
                            mkdir($local_dir . $first_image_folder_name, 0777, true);
                            umask($oldmask);
                        }


                        ftp_get($conn_id, $local_image, $remote_image, FTP_BINARY);

                        $images[$d] = '/tmp/' . $d . '/' . $first_image_folder_name . '/' . $first_image_name;
                    }

                }else{

                    $dds = scandir($local_path);
                    $dds_inner = '';
                    foreach ($dds as $ddss)
                        if($ddss == '000' || $ddss == '001')
                            $dds_inner = $ddss;
                    $ffs = scandir($local_path.$dds_inner);
                    $ffs_inner = '';
                    foreach ($ffs as $ffss){
                        if(strpos($ffss, 'jpg') !== false || strpos($ffss, 'png') !== false )
                            $ffs_inner = $ffss;
                    }



                    $loc_file = $dds_inner.'/'.$ffs_inner;


//                    SUtils::dump($loc_file);
//                    SUtils::trace($local_path);
//                    SUtils::trace(realpath($this->getParameter('kernel.root_dir') . '/..') . DIRECTORY_SEPARATOR . 'web/tmp/');



                    $images[$d] = '/tmp/'.$d.'/'.$loc_file;

//                    SUtils::trace($images);

                }



//


                $dirs[] = $d;
                $prefix[] = substr($d,0, strpos($d, '_'));



            }

//            if($counter > 20) break;

        }

        ftp_close($conn_id);

        return $this->render('admin/admin.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'dirs' => $dirs,
            'prefix' => $prefix,
            'images' => $images
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

        return $this->render('admin/add.html.twig', [
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


    /*http://beta.kioskplus.ru/info/index.html
    http://beta.kioskplus.ru/manage/index.html
    http://beta.kioskplus.ru/term/index.html*/



    /**
     * @Route("/info", name="info")
     */
    public function infoAction(Request $request)
    {
        $folder = self::getBodyClass($request) == 'men' ? 'html_men' : 'html';
        $baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();
        return $this->render($folder.'/info.html.twig', ['base_url' => $baseurl, 'body_class' => self::getBodyClass($request)]);
    }
    /**
     * @Route("/manage", name="manage")
     */
    public function manageAction(Request $request)
    {
        $folder = self::getBodyClass($request) == 'men' ? 'html_men' : 'html';
        $baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();
        return $this->render($folder.'/manage.html.twig', ['base_url' => $baseurl, 'body_class' => self::getBodyClass($request)]);
    }
    /**
     * @Route("/term", name="term")
     */
    public function termAction(Request $request)
    {
        $folder = self::getBodyClass($request) == 'men' ? 'html_men' : 'html';
        $baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();
        return $this->render($folder.'/term.html.twig', ['base_url' => $baseurl, 'body_class' => self::getBodyClass($request)]);
    }


    /**
     * @Route("/ajax/get/{url}", name="get")
     */
    public function ajaxGetAction(Request $request, $url)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var Journal[] $journals */
        $journals = $em->getRepository('AppBundle:Journal')->findBy(['url' => $url], array('date' => 'DESC'));

        $journal = array_shift($journals);
        $journal->setImageMain(self::renameImageToMin($journal->getImageMain()));

        echo $journal->getImageMain();
    }


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
        $journal->setNumber((int)$request->request->get('number'));
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

    public static function renameImageToMin($image){
        $image_name = substr($image, strrpos($image, '/') + 1);
        $image_new_name = str_replace($image_name, '__' . $image_name, $image);
        return $image_new_name;
    }

    public static function getBodyClass(Request $request){
        $host = $request->getHost();

        $domain = 'men';
        if(strpos($host, 'tech') !== false) {
            $domain = 'tech';
        }
        if(strpos($host, 'food') !== false) {
            $domain = 'food';
        }
        if(strpos($host, 'avto') !== false) {
            $domain = 'avto';
        }
        if(strpos($host, 'kind') !== false){
            $domain = 'kind';
        }
        if(strpos($host, 'premium') !== false) {
            $domain = 'premium';
        }
        if(strpos($host, 'premium') !== false) {
            $domain = 'premium';
        }

        return $domain;
    }

}

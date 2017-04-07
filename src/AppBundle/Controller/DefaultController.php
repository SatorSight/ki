<?php

namespace AppBundle\Controller;

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

        $em = $this->getDoctrine()->getManager();
        /** @var Journal[] $journals */
        $journals = $em->getRepository('AppBundle:Journal')->findBy(array(), array('date' => 'DESC'));

        /** @var Journal $j */
        foreach($journals as $j)
            if($j->getNumberSet())
                $j->setNumber(1);


//        foreach ($journals){
//
//        }

        $jour = [];
        $jour[] = array_shift($journals);
        $jour[] = array_shift($journals);






        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'journals' => $jour
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

//        $session = new Session();
//        $session->start();
        if(!empty($this->getUser()->getAttribute('bridge_token')))
            $this->getUser()->setAttribute('bridge_token', $_REQUEST['bridge_token']);

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


        if($page > $journal->getListing() + 4 && empty($this->getUser()->getAttribute('bridge_token'))){

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

<?php
namespace AppBundle\Command;

use AppBundle\Entity\Journal;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class LoadDataCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        //todo make adminka

        $this->setName('dev:load-data')
             ->setDescription('Loads journals from html in folder.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {


        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');


        $j1 = new Journal();
        $j1->setTitle('7 дней');
        $j1->setDescription(' «Семь дней» – развлекательный иллюстрированный журнал для семейного чтения. Его содержание посвящено культурным и развлекательным событиям в мире телевидения, кино, шоу-бизнеса. Журнал рассказывает о звездах кино, шоу-бизнеса, спорта, известных личностях. ');
        $j1->setImageMain('/images/7D20170313.jpg');
        $j1->setUrl('7days');
        $j1->setDate(\DateTime::createFromFormat('Y-m-d', '2017-03-01'));
        $j1->setNumber(13);
        $j1->setIdentifier('7D20170313');
        $j1->setListing(0);
        $j1->setPath('7D_2017_13');
        $j1->setGenre('stylelife');
        $j1->setPublisher('Издательство 7 дней');
        $j1->setYear(2017);
        $j1->setMonth(3);

        $em->persist($j1);


        $j2 = new Journal();
        $j2->setTitle('The Rake');
        $j2->setDescription('Мужской журнал «Рейк» занял особую позицию на мировом рынке глянцевой прессы. Провозгласив возрождение интереса к классической мужской элегантности, он отверг сиюминутные тенденции, позерство и мишуру, характерные для изданий эпохи моды на моду. Консервативный дизайн, качественные фотографии, написанные экспертами тексты помогли «Рейку» завоевать интерес свободомыслящих, образованных мужчин-читателей с развитым вкусом. Журналу удалось объединить блистательных международных авторов, многие из которых выпустили монографии, посвященные мужскому классическому стилю. Помимо одежды, наиболее ярко выражающей индивидуальность, журнал исследует другие аспекты мужских интересов. Рубрики «Рейка» посвящены часам, аксессуарам, автомобилям, высокой кухне, путешествиям, искусству и коллекционированию, красоте и здоровью. В материалах журнала всегда главенствует интерес к вечным ценностям, но его взгляд на мир остается современным. ');
        $j2->setImageMain('/images/RAKE20170301.jpg');
        $j2->setUrl('rake');
        $j2->setDate(\DateTime::createFromFormat('Y-m-d', '2017-02-01'));
//        $j2->setNumber(1);
        $j2->setIdentifier('RAKE20170301');
        $j2->setListing(0);
        $j2->setPath('RK_2017_01');
        $j2->setGenre('stylelife');
        $j2->setPublisher('The Rake');
        $j2->setYear(2017);
        $j2->setMonth(3);
        $j2->setDoubleMonth(true);

        $em->persist($j2);


        $j5 = new Journal();
        $j5->setTitle('The Rake');
        $j5->setDescription('Мужской журнал «Рейк» занял особую позицию на мировом рынке глянцевой прессы. Провозгласив возрождение интереса к классической мужской элегантности, он отверг сиюминутные тенденции, позерство и мишуру, характерные для изданий эпохи моды на моду. Консервативный дизайн, качественные фотографии, написанные экспертами тексты помогли «Рейку» завоевать интерес свободомыслящих, образованных мужчин-читателей с развитым вкусом. Журналу удалось объединить блистательных международных авторов, многие из которых выпустили монографии, посвященные мужскому классическому стилю. Помимо одежды, наиболее ярко выражающей индивидуальность, журнал исследует другие аспекты мужских интересов. Рубрики «Рейка» посвящены часам, аксессуарам, автомобилям, высокой кухне, путешествиям, искусству и коллекционированию, красоте и здоровью. В материалах журнала всегда главенствует интерес к вечным ценностям, но его взгляд на мир остается современным. ');
        $j5->setImageMain('/images/RAKE20170301.jpg');
        $j5->setUrl('rake');
        $j5->setDate(\DateTime::createFromFormat('Y-m-d', '2017-01-01'));
//        $j5->setNumber(1);
        $j5->setIdentifier('RAKE20170301');
        $j5->setListing(0);
        $j5->setPath('RK_2017_01');
        $j5->setGenre('stylelife');
        $j5->setPublisher('The Rake');
        $j5->setYear(2017);
        $j5->setMonth(1);
        $j5->setMonth(1);

        $em->persist($j5);


        $j3 = new Journal();
        $j3->setTitle('Playboy');
        $j3->setDescription(' « Playboy - имя, ставшее легендой. Популярнейший во всем мире глянцевый мужской журнал появился в России в 1995 году и очень быстро стал самым востребованным изданием в своей категории. Playboy охватывает очень широкий тематический спектр: самые красивые женщины мира, эффектные пикториалы и эксклюзивные съемки знаменитостей, увлекательные путешествия по всему миру, престижные автомобили, эффектная мужская мода. Интеллектуальная проза, интервью с известными мужчинами — о бизнесе, увлечениях и отношении к жизни. Получать удовольствие, уметь находить во всем приятные стороны — эти жизненные ценности актуальны для мужчин во все времена. Playboy — это стильные фото и публикации о музыке, спорте, автомобилях, аудиовидеоновинках, сексе, карьере. ');
        $j3->setImageMain('/images/PB20170303.jpg');
        $j3->setUrl('playboy');
        $j3->setDate(\DateTime::createFromFormat('Y-m-d', '2017-03-01'));
//        $j3->setNumber(1);
        $j3->setIdentifier('PB20170303');
        $j3->setListing(0);
        $j3->setPath('PB_2017_01');
        $j3->setGenre('stylelife');
        $j3->setPublisher('Playboy');
        $j3->setYear(2017);
        $j3->setMonth(3);

        $em->persist($j3);


        $j4 = new Journal();
        $j4->setTitle('Forbes');
        $j4->setDescription(' Журнал Forbes очень влиятельный независимый деловой журнал в мире. Forbes пишет об историях успеха и поражений предпринимателей, новых идеях для бизнеса и инвестиций, публикует авторитетные рейтинги. Журнал публикует серию рейтингов, составленных по уникальной методике, которую на протяжении многих лет использует американский Forbes. Раз в год журнал публикует результаты исследования доходов руководителей крупнейших российских компаний. ');
        $j4->setImageMain('/images/FB20170304.jpg');
        $j4->setUrl('forbes');
        $j4->setDate(\DateTime::createFromFormat('Y-m-d', '2017-04-01'));
//        $j4->setNumber(1);
        $j4->setIdentifier('FB20170304');
        $j4->setListing(0);
        $j4->setPath('FB_2017_04');
        $j4->setGenre('stylelife');
        $j4->setPublisher('Forbes');
        $j4->setYear(2017);
        $j4->setMonth(4);

        $em->persist($j4);

        $em->flush();

        $output->writeln('Loading data done');

    }
}
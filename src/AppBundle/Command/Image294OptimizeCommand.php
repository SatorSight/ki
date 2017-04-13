<?php
namespace AppBundle\Command;

use AppBundle\Entity\Journal;
use AppBundle\Resources\SUtils;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class Image294OptimizeCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        //todo make adminka

        $this->setName('dev:images-optimize-294')
            ->setDescription('Makes resized __name files for all covers.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $path = realpath($this->getContainer()->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR.'web';

        /** @var Journal[] $journals */
        $journals = $em->getRepository('AppBundle:Journal')->findAll();

        foreach ($journals as $j){

            $image = $j->getImageMain();

            if(is_file($path.$image)) {

                $output->writeln('File processed '.$path.$image);

                $img = new \imagick($path . $image);
                $img->scaleImage(270, 0);
                $img->setImageCompression(\imagick::COMPRESSION_JPEG);
                $img->setImageCompressionQuality(0);

                $image_name = substr($image, strrpos($image, '/') + 1);
                $image_new_name = $path . str_replace($image_name, '_' . $image_name, $image);
                $image_final_name = $path . str_replace($image_name, '294_' . $image_name, $image);

                $img->writeImage($image_new_name);

                exec('cd vendor/mozjpeg && ./cjpeg -quality 90 -outfile ' . $image_final_name . ' ' . $image_new_name);

            }
        }

        $output->writeln('Processing data done');
    }
}
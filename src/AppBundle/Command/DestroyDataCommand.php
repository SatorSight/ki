<?php
namespace AppBundle\Command;

use AppBundle\Resources\SUtils;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class DestroyDataCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        //todo make adminka

        $this->setName('dev:destroy-data')
            ->setDescription('Removes journals from base (for dev).');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $all_journals =  $em->getRepository('AppBundle:Journal')->findAll();
        foreach($all_journals as $j)
            $em->remove($j);
        $em->flush();


        $output->writeln('Removing journals done');


    }
}
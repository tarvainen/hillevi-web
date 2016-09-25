<?php

namespace App\Command;

use App\Entity\User;
use App\Util\Password;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command to create user.
 *
 * @package App\Command
 *          
 * @author Atte Tarvainen <atte.tarvainen@pp1.inet.fi>
 */
class CreateUserCommand extends ContainerAwareCommand
{
    /**
     * Configures the command.
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('app:create-user')
            ->setDescription('Creates new user')
            ->setHelp('This command allows you to create a new user')
            ->addArgument('username', InputArgument::REQUIRED, 'Username')
            ->addArgument('password', InputArgument::REQUIRED, 'Password')
        ;
    }

    /**
     * Executes the command.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(
            array(
                'User Factory',
                '============',
                '',
            )
        );

        $user = new User();

        $user
            ->setUsername($input->getArgument('username'))
            ->setPassword(Password::hash($input->getArgument('password')))
        ;

        /**
         * @var Registry $doctrine
         */
        $doctrine = $this->getContainer()->get('doctrine');

        $em = $doctrine->getManager();
        
        $em->persist($user);
        $em->flush();

        if ($em->contains($user)) {
            $output->writeln(['Done.']);
        } else {
            $output->writeln(['Something went wrong.']);
        }
    }
}
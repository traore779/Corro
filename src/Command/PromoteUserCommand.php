<?php

namespace App\Command;

use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PromoteUserCommand extends Command
{
    protected static $defaultName = 'app:promote:user';

    private $om;

    public function __construct(EntityManagerInterface $om)
    {
        $this->om = $om;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Definir un nouveau role pour un utilisateur')
            ->addArgument('email', InputArgument::REQUIRED, 'L\'addresse email dont on veut promettre')
            ->addArgument('roles', InputArgument::REQUIRED, 'Le role dont on veut affecter')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $email = $input->getArgument('email');
        $roles = $input->getArgument('roles');

        $userRepository = $this->om->getRepository(Utilisateur::class);
        $user = $userRepository->findOneBy(array('email' => $email));

        if ($user) {
            $user->addRoles($roles);
            $this->om->persist($user);
            $this->om->flush();

            $io->success('Le role a bien été affecté à l\'utilisateur !');
        } else {
            $io->error('Cet email n\'est associé à aucun compte !');
        }

        return 0;
    }
}

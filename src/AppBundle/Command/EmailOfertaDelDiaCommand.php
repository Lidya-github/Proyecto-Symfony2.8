<?php
namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

    class EmailOfertaDelDiaCommand extends ContainerAwareCommand
    {

        protected function configure()
        {
            $this
                ->setName('app:email:oferta-del-dia')
                ->setDefinition(array(
                    new InputArgument('ciudad',InputArgument::OPTIONAL,
                    'El slug de la ciudad para la que se generan los emails'),
                new InputOption('accion',null,InputOption::VALUE_OPTIONAL,
                    'Indica si los emails sólo se generan o también se envían','enviar'),   
                ))
                ->setDescription('Genera y envía a cada usuario el email con la oferta diaria')
                ->setHelp('...');
        }

        protected function interact(InputInterface $input, OutputInterface $output)
        {
            $io = new SymfonyStyle($input, $output);
            $io->title('Bienvenido al generador de emails');
            $io->text('Para continuar, debes contestar a varias preguntas...');
            $ciudad = $io->ask('¿Para qué ciudad quieres generar los emails?', 'sevilla');
            $input->setArgument('ciudad', $ciudad);
            $accion = $io->choice('¿Qué quieres hacer con los emails?', array('enviar', 'generar'), 0);
            $input->setOption('accion', $accion);
            if (!$io->confirm(sprintf('¿Quieres %s ahora los emails de %s?', $accion, $ciudad))) {}

        }

        protected function execute(InputInterface $input, OutputInterface $output)
        {
            $host = 'dev' == $input->getOption('env') ? 'http://127.0.0.1:8000' : 'http://cupon.com';
            $accion = $input->getOption('accion');
            $em = $this->getContainer()->get('doctrine')->getManager();
            $usuarios = $em->getRepository('AppBundle:Usuario')->findBy();

            // Buscar la 'oferta del día' en todas las ciudades de la aplicación
            $ofertas = array();
            $ciudades = $em->getRepository('AppBundle:Ciudad')->findAll();
                foreach ($ciudades as $ciudad) {
                    $id = $ciudad->getId();
                    $slug = $ciudad->getSlug();
                    $ofertas[$id] = $em->getRepository('AppBundle:Oferta')->findOfertaDelDiaSiguiente($slug);
                }

            // Generar el email personalizado de cada usuario
            foreach ($usuarios as $usuario) {
                $ciudad = $usuario->getCiudad();
                $oferta = $ofertas[$ciudad->getId()];
                $contenido = $contenedor->get('twig')->render(
                'email/oferta_del_dia.html.twig',array(
                    'host' => $host,
                    'ciudad' => $ciudad,
                    'oferta' => $oferta,
                    'usuario' => $usuario)
                );
            }
            
            $contenedor = $this->getContainer();
// ...
            $mensaje = \Swift_Message::newInstance()
                ->setSubject('Oferta del día')
                ->setFrom('mailing@cupon.com')
                ->setTo('usuario1@localhost')
                ->setBody($contenido)
            ;
            $mensaje = \Swift_Message::newInstance()->setFrom('mailing@cupon.com');
            $mensaje = \Swift_Message::newInstance()->setFrom(array('mailing@cupon.com' => 'Cupon - Oferta del día'));
            $mensaje = \Swift_Message::newInstance()->setBody($contenido);
            $mensaje = \Swift_Message::newInstance()->setBody($contenido, 'text/html');

            $texto = $contenedor->get('twig')->render('email/oferta_del_dia.txt.twig',array());
            $html = $contenedor->get('twig')->render('email/oferta_del_dia.html.twig',array());
            $mensaje = \Swift_Message::newInstance()->setBody($texto)->addPart($html, 'text/html');
            $documento = $this->getContainer()->getParameter('kernel.root_dir').'/../web/uploads/documentos/promocion.pdf';
            $mensaje = \Swift_Message::newInstance()->attach(\Swift_Attachment::fromPath($documento));

            $this->contenedor->get('mailer')->send($mensaje);
        }
    }


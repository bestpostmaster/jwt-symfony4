<?php

namespace App\Controller;

use App\Entity\HostedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\HostedFileRepository;
use Psr\Log\LoggerInterface;

class FilesController extends AbstractController
{
    /**
     * @Route("/api/files", name="app_files")
     */
    public function index(HostedFileRepository $hostedFileRepository): Response
    {
        $userId = ($this->getUser())->getId();

        if($this->container->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            return $this->json($hostedFileRepository->findAll(), 200, [], ['groups' => 'file:read']);
        }

        return $this->json($hostedFileRepository->findBy(['user' => $userId]), 200, [], ['groups' => 'file:read']);
    }

    /**
     * TO DO
     * @Route("/api/files/upload", name="app_files_upload")
     */
    public function upload(Request $request, LoggerInterface $logger): Response
    {
        if (empty($request->files) || !($request->files)->get("file")) {
            throw new \Exception('No file sent');
        }

        $receivedFile = ($request->files)->get("file");
        $logger->info('Try to upload file : '.$receivedFile->getClientOriginalName());

        if(!$this->container->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            throw new \Exception('No user logged in');
        }

        $name = md5(uniqid(mt_rand(), true)).'.'.strtolower($receivedFile->getClientOriginalExtension());
        $directory = $this->getParameter('kernel.project_dir') . '/public/up/';
        $receivedFile->move($directory, $name);

        if (!file_exists($directory.$name)) {
            throw new \Exception('Upload error...');
        }

        $file = new HostedFile();
        $file->setRealDir('/public/up/');
        $file->setName($name);
        $file->setClientName($receivedFile->getClientOriginalName());
        $file->setUploadDate(new \DateTime('now', new \DateTimeZone('Europe/Paris')));
        $file->setUser($this->getUser());
        $file->setSize(filesize($directory.$name)/1000000);
        $file->setScaned(false);
        $file->setDescription($receivedFile->getClientOriginalName());
        $file->setDownloadCounter(0);
        $file->setUrl(md5(uniqid(mt_rand(), true)));
        $file->setUploadLocalisation($_SERVER['REMOTE_ADDR']);
        $file->setCopyrightIssue(false);
        $file->setConversionsAvailable('');
        $file->setVirtualDirectory('/');

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($file);
        $manager->flush($file);

        return $this->json($file, 200, [], ['groups' => 'file:read']);
    }

    /**
     * @Route("/api/files/download/{url}", name="app_files_download")
     */
    public function download(Request $request, HostedFileRepository $hostedFileRepository): BinaryFileResponse
    {
        $userId = ($this->getUser())->getId();
        $url = $request->get("url");

        if(!$this->container->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            throw new \Exception('No user logged in');
        }

        if($this->container->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            $result = $hostedFileRepository->findOneBy(['url' => $url]);
        }
        else {
            $result = $hostedFileRepository->findOneBy(['url' => $url, 'user' => $userId]);
        }

        if(!$result) {
            throw $this->createNotFoundException('The file does not exist');
        }

        $response = new BinaryFileResponse($this->getParameter('kernel.project_dir').$result->getRealDir().$result->getName());
        $extension = (explode('.',$result->getName()))[1] ?? '';
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $result->getDescription().'.'.$extension
        );

        return $response;
    }

    /**
     * TO DO
     * @Route("/api/files/delete/{fileId}", name="app_files_delete")
     */
    public function deleteById(Request $request, HostedFileRepository $hostedFileRepository): Response
    {
        $userId = ($this->getUser())->getId();
        $id = $request->get("fileId");

        if(!$this->container->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            throw new \Exception('No user logged in');
        }

        if($this->container->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            $result = $hostedFileRepository->findOneBy(['id' => $id]);
        }
        else {
            $result = $hostedFileRepository->findOneBy(['url' => $id, 'user' => $userId]);
        }

        if(!$result) {
            throw $this->createNotFoundException('The file does not exist');
        }

        $manager = $this->getDoctrine()->getManager();
        $manager->remove($result);
        $manager->flush();

        return $this->json([], 200);
    }

    /**
     * TO DO
     * @Route("/api/files/convert/{fileId}/{convertTo}", name="app_files_convert")
     */
    public function convert(HostedFileRepository $hostedFileRepository): Response
    {
        $userId = ($this->getUser())->getId();

        if($this->container->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            return $this->json($hostedFileRepository->findAll(), 200, [], ['groups' => 'file:read']);
        }

        return $this->json($hostedFileRepository->findBy(['user' => $userId]), 200, [], ['groups' => 'file:read']);
    }
}

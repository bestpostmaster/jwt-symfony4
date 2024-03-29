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
use App\Service\Converter;

class FilesController extends AbstractController
{
    private string $hostingDirectory;

    public function __construct(string $hostingDirectory)
    {
        $this->hostingDirectory = $hostingDirectory;
    }

    /**
     * @Route("/api/files", name="app_files")
     */
    public function index(HostedFileRepository $hostedFileRepository, string $hostingDirectory): Response
    {
        $userId = ($this->getUser())->getId();
        $this->hostingDirectory = $hostingDirectory;

        if($this->container->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            return $this->json($hostedFileRepository->findAll(), 200, [], ['groups' => 'file:read']);
        }

        return $this->json($hostedFileRepository->findBy(['user' => $userId]), 200, [], ['groups' => 'file:read']);
    }

    /**
     * TO DO
     * @Route("/api/files/upload", name="app_files_upload")
     */
    public function upload(Request $request, LoggerInterface $logger, string $hostingDirectory, string $projectDirectory): Response
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
        $receivedFile->move($this->hostingDirectory, $name);

        if (!file_exists($this->hostingDirectory.$name)) {
            throw new \Exception('Upload error...');
        }

        $file = new HostedFile();
        $file->setName($name);
        $file->setClientName($receivedFile->getClientOriginalName());
        $file->setUploadDate(new \DateTime('now', new \DateTimeZone('Europe/Paris')));
        $file->setUser($this->getUser());
        $file->setSize(filesize($this->hostingDirectory.$name)/1000000);
        $file->setScaned(false);
        $file->setDescription($request->get("description") ?? $receivedFile->getClientOriginalName());
        $file->setFilePassword($request->get("filePassword") ?? '');
        $file->setDownloadCounter(0);
        $file->setUrl(md5(uniqid(mt_rand(), true)).md5(uniqid(mt_rand(), true)));
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

        $response = new BinaryFileResponse($this->hostingDirectory.$result->getName());
        $extension = (explode('.',$result->getName()))[1] ?? '';
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $result->getDescription().'.'.$extension
        );

        return $response;
    }

    /**
     * @Route("/api/files/file-info/{fileId}", name="app_file_info")
     */
    public function fileInfo(Request $request, HostedFileRepository $hostedFileRepository): Response
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
            $result = $hostedFileRepository->findOneBy(['id' => $id, 'user' => $userId]);
        }

        if(!$result) {
            throw $this->createNotFoundException('The file does not exist');
        }

        return $this->json($result, 200, [], ['groups' => 'file:read']);
    }

    /**
     * @Route("/api/files/delete/{fileId}", name="app_files_delete", methods={"DELETE"})
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
            $result = $hostedFileRepository->findOneBy(['id' => $id, 'user' => $userId]);
        }

        if(!$result) {
            throw $this->createNotFoundException('The file does not exist');
        }

        $manager = $this->getDoctrine()->getManager();
        $manager->remove($result);
        $manager->flush();

        $fullPath = $this->hostingDirectory.$result->getName();

        if(file_exists($fullPath)) {
            unlink($this->hostingDirectory . $result->getName());
        }

        return $this->json([], 200);
    }

    /**
     * TO DO
     * @Route("/api/files/convert/{fileId}/{convertTo}", name="app_files_convert")
     */
    public function convert(Request $request, Converter $converter): Response
    {
        if(!$this->container->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            throw new \Exception('No user logged in');
        }

        $fileId = $request->get("fileId");
        $convertTo = $request->get("convertTo");

        $conversionStatus = $converter->convert($fileId, $convertTo);

        return $this->json([], 200, [], ['groups' => 'file:read']);
    }
}

<?php

namespace Cms\Service;

use Aws\S3\S3Client;
use Base\Entity\Aberto\Imovel;
use Doctrine\ORM\EntityManagerInterface;

class CmsService {

    public function cms()
    {
        return 'pronto';
    }

    public function cadastrarImovel($serviceLocator, $data, $file)
    {

        /** @var  EntityManagerInterface $entityManager */
        $entityManager = $serviceLocator->get('\Doctrine\ORM\EntityManager');

        $imovel = new Imovel();

        $entityManager->beginTransaction();

        try {
            $imovel->setNome($data['titulo']);
            $imovel->setImagem($file['file']['name']);
            $imovel->setLatitude($data['latitude']);
            $imovel->setLongitude($data['longitude']);
            $imovel->setDescricao($data['descricao']);

            $this->uploadFile($file);

            $entityManager->persist($imovel);
            $entityManager->flush();
            $entityManager->commit();
        } catch (\Exception $e) {
            $entityManager->rollback();
            return false;
        }

        return true;

    }


    public function deleteFile($file)
    {
        $fileName = '';
        if(!isset($file) || empty($file)){
            $fileName = $file;
        }

        try {
            $clientS3 = S3Client::factory(array(
                'key'    => 'AKIAJNS3DLXBQ34MF7ZA',
                'secret' => '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'
            ));

            $response = $clientS3->deleteObject(array(
                'Bucket' => "meu-teste-upload",
                'Key'    => $fileName,
            ));
        } catch(\Exception $e) {
            echo "Erro > {$e->getMessage()}";
        }

    }


    public function uploadFile($file)
    {

        try {
            if (!isset($file)) {
                throw new \Exception("Arquivo não enviado", 1);
            }

            $clientS3 = S3Client::factory(array(
                'key'    => 'AKIAJNS3DLXBQ34MF7ZA',
                'secret' => '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'
            ));

            $response = $clientS3->putObject(array(
                'Bucket'      => "meu-teste-upload",
                'Key'         => $file['file']['name'],
                'SourceFile'  => $file['file']['tmp_name'],
                'ContentType' => $file['file']['type'],
            ));
        } catch(\Exception $e) {
            echo "Erro > {$e->getMessage()}";
        }

    }

    public function editarImovel($serviceLocator, $id, $data, $file)
    {

        /** @var  EntityManagerInterface $entityManager */
        $entityManager = $serviceLocator->get('\Doctrine\ORM\EntityManager');

        /** @var  Imovel $imovelObject */
        $imovelRepository = $entityManager->getRepository(Imovel::class);

        $imovelObject = $imovelRepository->find($id);

        $entityManager->beginTransaction();

        try {
            $this->deleteFile($imovelObject->getImagem());

            $imovelObject->setNome($data['titulo']);
            $imovelObject->setImagem($file['file']['name']);
            $imovelObject->setLatitude($data['latitude']);
            $imovelObject->setLongitude($data['longitude']);
            $imovelObject->setDescricao($data['descricao']);

            $this->uploadFile($file);

            $entityManager->persist($imovelObject);
            $entityManager->flush();
            $entityManager->commit();
        } catch (\Exception $e) {
            $entityManager->rollback();
            return false;
        }

        return true;




    }

    public function excluirImovel($serviceLocator, $id)
    {

        /** @var  EntityManagerInterface $entityManager */
        $entityManager = $serviceLocator->get('\Doctrine\ORM\EntityManager');

        /** @var  Imovel $imovelObject */
        $imovelRepository = $entityManager->getRepository(Imovel::class);

        $imovelObject = $imovelRepository->find($id);

        $entityManager->beginTransaction();

        try {
            $this->deleteFile($imovelObject->getImagem());

            $entityManager->remove($imovelObject);
            $entityManager->flush();
            $entityManager->commit();
        } catch (\Exception $e) {
            $entityManager->rollback();
            return false;
        }

        return true;
    }

}
<?php

namespace ImovelBase\Service;

use Aws\S3\S3Client;
use Base\Entity\Aberto\Imovel;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use Doctrine\Common\Collections;

class ImovelService {

    public function getImovelByName($serviceLocator, $nomeImovel)
    {

        $entityManager = $serviceLocator->get('\Doctrine\ORM\EntityManager');

        $result = $entityManager->getRepository(Imovel::class)->createQueryBuilder('o')
            ->where('upper(o.nome) LIKE upper(:nome)')
            ->setParameter('nome', "%{$nomeImovel}%")
            ->getQuery()
            ->getArrayResult();


        foreach ($result as $index => &$item) {
            $item['imageBase64'] = $this->getObjectS3Amazon($item['imagem']);
        }
        
        return $result;
        
    }


    public function getImovelById($serviceLocator, $id)
    {

        $entityManager = $serviceLocator->get('\Doctrine\ORM\EntityManager');
        $ImovelEntity = $entityManager->getRepository(Imovel::class);

        $imovelObject = $ImovelEntity->find($id);

        $hydrator = new DoctrineObject($entityManager);

        $imovelArray = $hydrator->extract($imovelObject);
        $imovelArray['imageBase64'] = $this->getObjectS3Amazon($imovelArray['imagem']);

        return $imovelArray;

    }


    public function getAllImoveis($serviceLocator){

        $entityManager = $serviceLocator->get('\Doctrine\ORM\EntityManager');
        $ImovelEntity = $entityManager->getRepository(Imovel::class);

        $imoveisArrayObject = $ImovelEntity->findAll();
        $hydrator           = new DoctrineObject($entityManager);

        $imoveisArray = [];
        foreach ($imoveisArrayObject as $index => $imovel) {
            $imoveisArray[$index]                = $hydrator->extract($imovel);
            $imoveisArray[$index]['imageBase64'] = $this->getObjectS3Amazon($imoveisArray[$index]['imagem']);
        }

        return $imoveisArray;


    }


    public function getImoveisNasProximidades($serviceLocator, $imovelLatitude, $imovelLongitude)
    {

        $parametroDistanciaPadrao = 400;
        $imoveisNasProximidades = [];

        $imoveis = $this->getAllImoveis($serviceLocator);

        foreach ($imoveis as $indexEndereco => $endereco) {

            $retorno = $this->distanciaPontosGPS($imovelLatitude,
                $imovelLongitude,
                $endereco['latitude'],
                $endereco['longitude']);

            if($retorno <= $parametroDistanciaPadrao && $imovelLatitude != $endereco['latitude'] && $imovelLongitude != $endereco['longitude']){
                $imoveisNasProximidades[] = $endereco;
            }

        }

        return $imoveisNasProximidades;

    }



    private function distanciaPontosGPS($p1LA, $p1LO, $p2LA, $p2LO)
    {

        $r  = 6371.0;
        $pi = 3.14159265359;

        $p1LA = $p1LA * $pi / 180.0;
        $p1LO = $p1LO * $pi / 180.0;
        $p2LA = $p2LA * $pi / 180.0;
        $p2LO = $p2LO * $pi / 180.0;

        $dLat = $p2LA - $p1LA;
        $dLong = $p2LO - $p1LO;

        $a = sin($dLat / 2) * sin($dLat / 2) + cos($p1LA) * cos($p2LA) * sin($dLong / 2) * sin($dLong / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($r * $c * 1000); // resultado em metros.

    }


    public function getObjectS3Amazon($busca){

        try {

            // todo: criar um array config para a chave, e o bucket
            $clientS3 = S3Client::factory(array(
                'key'    => 'AKIAJU6N2VOCMYDNBFSA',
                'secret' => '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'
            ));

            // método putObject envia os dados pro bucket selecionado (no caso, teste-marcelo
            $response = $clientS3->getObject(array(
                'Bucket' => "meu-teste-upload",
                'Key'    => $busca,
            ));


            $body =  base64_encode($response['Body']);

            $objectArray = ['contentType' => $response['ContentType'],
                            'body'        => $body,];


            return $objectArray;

        } catch(\Exception $e) {
            return false;
        }

    }


  
}
<?php
return array(
    'doctrine' => array(
        'connection' => array(
            'orm_default' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOPgSql\Driver',
                'params'      => array(
                    'host'     => 'meu-banco.cgx9ep8zqnlq.sa-east-1.rds.amazonaws.com',
                    'port'     => '5432',
                    'user'     => 'admin2015',
                    'password' => 'admin2015#',
                    'dbname'   => 'meu_banco_2015',
                )
            )
        )
    )
);
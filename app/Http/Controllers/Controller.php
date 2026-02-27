<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function getPrefixs()
    {
        return [
            [
                'connection' => 'tbs',
                'prefixs' => [
                    'SPIN9', 'BA89', 'ACE333', 'JOKER79', 'THOR99', 'ALL89', 'JOKER123CLUB', 'BMX', 'ACE333AUTO', 'SUPERSLOTGO',
                ],
            ],
            [
                'connection' => 'ufa',
                'prefixs' => [
                    'UFACLICK', 'UFASIAM',
                ],
            ],
            [
                'connection' => 'gclub',
                'prefixs' => [
                    'GCLUBAUTO', 'GCLUB44AUTO', 'GCLUB24AUTO',
                ],
            ],
        ];
    }
}

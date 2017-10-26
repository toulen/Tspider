<?php
namespace Tspider;

class TspiderRequest extends Tbase
{

    public static function curlDo($method, $url, $params = [], $headers = []){

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_HEADER, 1);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);

        curl_setopt($ch, CURLOPT_USERAGENT, 'Tspider by toulen');

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        if($method == 'POST') {

            curl_setopt($ch, CURLOPT_POST, 1);

            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        }

        $result = curl_exec($ch);


        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);

        $header = substr($result, 0, $headerSize);

        $content = substr($result, $headerSize);

        curl_close($ch);

        return [
            'content' => $content,
            'header' => $header
        ];
    }
}
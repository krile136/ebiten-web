<?php

namespace App\Traits;

trait Aes
{
    /**
     * 配列データをAES128で暗号化する
     *
     * @param  array<mixed>  $data
     */
    private function encrypt(array $data): string
    {
        $iv = bin2hex(random_bytes(16));
        $key = config('aes.key');
        $json_string = json_encode($data);
        logger()->debug(compact('json_string'));
        $encrypted = openssl_encrypt($json_string, 'AES-128-CBC', hex2bin($key), 0, hex2bin($iv));

        return $iv.'|'.$encrypted;
    }

    /**
     * ivと暗号化された文字列から復号化してデータを取り出す
     *
     * @return mixed
     */
    private function decrypt(string $message)
    {
        $exploded_data = explode('|', $message);
        $iv = $exploded_data[0];
        $encrypted = $exploded_data[1];
        $key = config('aes.key');
        $decrypted = openssl_decrypt($encrypted, 'AES-128-CBC', hex2bin($key), 0, hex2bin($iv));

        return json_decode($decrypted, true);
    }
}

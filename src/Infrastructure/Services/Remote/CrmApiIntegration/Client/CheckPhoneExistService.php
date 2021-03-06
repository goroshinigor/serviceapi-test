<?php

namespace App\Infrastructure\Services\Remote\CrmApiIntegration\Client;

use App\Domain\Exceptions\ClientNotFoundException;

class CheckPhoneExistService
{
    /**
     *
     * @var string Justin API url
     */
    private $crmApiUrl = 'https://crmapi.justin.ua/api/justin/';

    /**
     * @var string method
     */
    private $uri = 'CheckPhone';

    /**
     * Returns memberId if client exist or throw Exception
     * @param string $phoneNumber
     * @return bool
     * @throws ClientNotFoundException
     */
    public function check(string $phoneNumber): string
    {
        $data = array(
            'phoneNumber' => $phoneNumber
        );
        $res = json_decode($this->sendRequest($data), true);
        if ('success' == $res['resultType']) return $res['data']['memberId'];
        throw new ClientNotFoundException();
    }

    /**
     *
     * @param type $data
     * @return type
     */
    private function sendRequest($data)
    {
        $post = json_encode($data);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->crmApiUrl . $this->uri);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
        $res = curl_exec($curl);
        curl_close($curl);
        return $res;
    }
}

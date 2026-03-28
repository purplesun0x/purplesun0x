<?php

interface PaymentGatewayInterface
{
    public function key(): string;

    /**
     * @param array{email:string,amount:float,session_token:string,callback_url:string} $payload
     * @return array{ok:bool,redirect_url?:string,error?:string,reference?:string,raw?:array}
     */
    public function initialize(array $payload): array;

    /**
     * @return array{ok:bool,error?:string,reference?:string,raw?:array}
     */
    public function verify(string $reference): array;
}

<?php
namespace App\Services\Validation;

class ValidateCpfCnpj
{
    private $cpfCnpj;

    public function __construct($cpfCnpj)
    {
        $this->cpfCnpj = $cpfCnpj;
    }

    public function execute()
    {
        $cpfCnpj = str_replace('.', '', $this->cpfCnpj);
        $cpfCnpj = str_replace('-', '', $cpfCnpj);
        $cpfCnpj = str_replace('/', '', $cpfCnpj);

        if (strlen($cpfCnpj) !== 11 && strlen($cpfCnpj) !== 14) {
            throw new \Exception('INVALID_CPF_CNPJ');
        }

        return 'ok';
    }
}

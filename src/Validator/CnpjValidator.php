<?php
namespace App\Validator;

use App\Validator\Constraints\Cnpj;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CnpjValidator extends ConstraintValidator 
{
    public function validate($value, Constraint $constraint) 
    {
        if (null === $value || '' === $value) {
            return;
        }

        if (!$this->isCnpjValid($value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }

    private function isCnpjValid(string $cnpj): bool 
    {
        // Remove caracteres não numéricos
        $cnpj = preg_replace('/[^0-9]/', '', $cnpj);
        
        // Verifica se tem 14 dígitos ou se todos são iguais
        if (strlen($cnpj) != 14 || preg_match('/(\d)\1{13}/', $cnpj)) {
            return false;
        }

        // Valida primeiro dígito verificador
        $pesos = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        $soma = 0;
        
        for ($i = 0; $i < 12; $i++) {
            $soma += $cnpj[$i] * $pesos[$i];
        }
        
        $resto = $soma % 11;
        $digito1 = ($resto < 2) ? 0 : 11 - $resto;
        
        // Valida segundo dígito verificador
        array_unshift($pesos, 6);
        $soma = 0;
        
        for ($i = 0; $i < 13; $i++) {
            $soma += $cnpj[$i] * $pesos[$i];
        }
        
        $resto = $soma % 11;
        $digito2 = ($resto < 2) ? 0 : 11 - $resto;
        
        // Verifica se os dígitos conferem
        return $cnpj[12] == $digito1 && $cnpj[13] == $digito2;
    }
}
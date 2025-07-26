<?php
namespace App\Validator;

use App\Validator\Constraints\Cpf;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CpfValidator extends ConstraintValidator 
{
    public function validate($value, Constraint $constraint) 
    {
        if (null === $value || '' === $value) {
            return;
        }

        if (!$this->isCpfValid($value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }

    private function isCpfValid(string $cpf): bool 
    {
        // Remove caracteres não numéricos
        $cpf = preg_replace('/[^0-9]/', '', $cpf);
        
        // Verifica se tem 11 dígitos ou se todos são iguais
        if (strlen($cpf) != 11 || preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        // Calcula o primeiro dígito verificador
        for ($i = 0, $j = 10, $soma = 0; $i < 9; $i++, $j--) {
            $soma += $cpf[$i] * $j;
        }
        
        $resto = $soma % 11;
        $digito1 = ($resto < 2) ? 0 : 11 - $resto;
        
        // Calcula o segundo dígito verificador
        for ($i = 0, $j = 11, $soma = 0; $i < 10; $i++, $j--) {
            $soma += $cpf[$i] * $j;
        }
        
        $resto = $soma % 11;
        $digito2 = ($resto < 2) ? 0 : 11 - $resto;
        
        // Verifica se os dígitos calculados conferem com os informados
        return $cpf[9] == $digito1 && $cpf[10] == $digito2;
    }
}
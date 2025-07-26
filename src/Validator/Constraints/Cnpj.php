<?php
namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Cnpj extends Constraint
{
    public $message = 'O CNPJ "{{ value }}" não é válido.';
}
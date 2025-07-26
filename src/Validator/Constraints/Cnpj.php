<?php
namespace App\Validator\Constraints;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Cnpj extends Constraint
{
    public $message = 'O CNPJ "{{ value }}" é inválido.';
}
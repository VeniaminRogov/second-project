<?php

namespace App\Exceptions;


use Symfony\Component\Validator\ConstraintViolationList;

class ValidationException extends \Exception
{
    private ConstraintViolationList $list;
    public function __construct(ConstraintViolationList $list)
    {
        $this->list = $list;
        parent::__construct($list);
    }

    public function getConstraintViolationList(): ConstraintViolationList
    {
       return $this->list;
    }
}
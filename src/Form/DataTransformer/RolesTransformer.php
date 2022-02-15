<?php

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class RolesTransformer implements DataTransformerInterface
{

    public function transform($rolesAsString): string
    {
        if(null == $rolesAsString)
        {
            return '';
        }

        return implode(',', $rolesAsString);
    }

    public function reverseTransform($rolesAsArray): array
    {
        return explode(',', $rolesAsArray);
    }
}
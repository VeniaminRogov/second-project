<?php

namespace App\Services;

use App\Entity\Products;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Translation\TranslatorInterface;

class FlashService
{
    private $flash;
    public function __construct(
        RequestStack $requestStack,
        private TranslatorInterface $translator
    ) {
        $this->flash = $requestStack->getSession()->getFlashBag();
    }

    public function onCreateUpdateProduct(?int $id)
    {
        if (!$id) {
            $this->flash->add('success', $this->translator->trans('product.create', [], 'flash'));
        } else{
            $this->flash->add('success', $this->translator->trans('product.update', [], 'flash'));
        }
    }

    public function onDeleteProduct()
    {
        $this->flash->add('danger', $this->translator->trans('product.delete', [], 'flash'));
    }
}

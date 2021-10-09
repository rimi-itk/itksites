<?php

namespace App\Form\Type\Admin;

use App\Repository\WebsiteRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WebsiteTypeFilterType extends AbstractType
{
    /**
     * @var WebsiteRepository
     */
    private $websiteRepository;

    public function __construct(WebsiteRepository $websiteRepository)
    {
        $this->websiteRepository = $websiteRepository;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $types = [];
        foreach ($this->websiteRepository->findAll() as $website) {
            $types[$website->getType()] = $website->getType();
        }
        $resolver->setDefaults([
            'choices' => $types,
        ]);
    }

    public function getParent()
    {
        return ChoiceType::class;
    }
}

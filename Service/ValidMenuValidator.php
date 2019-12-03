<?php

namespace Lch\MenuBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use http\Exception;
use Lch\MenuBundle\Entity\Menu;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Contracts\Translation\TranslatorInterface;


/**
 * Class ValidMenuValidator
 */
class ValidMenuValidator extends ConstraintValidator
{
    private $em;
    private $translator;

    /**
     * ValidMenuValidator constructor.
     * @param EntityManagerInterface $entityManager
     * @param TranslatorInterface $translator
     */
    public function __construct(EntityManagerInterface $entityManager,TranslatorInterface $translator)
    {
        $this->em = $entityManager;
        $this->translator = $translator;
    }

    /**
     * @param Menu $menu The Object that should be validated
     * @param \Symfony\Component\Validator\Constraint $constraint The constraint for the validation
     * @throws Exception
     */
    public function validate($menu, Constraint $constraint)
    {
        $conflicts = $this->em
            ->getRepository(Menu::class)
            ->findForValidator($menu);
        if (count($conflicts) > 0) {
            $this->context->addViolation($this->translator->trans('lch.error.menu.unique_location'));
        }
    }
}
<?php

namespace App\Form\Type\Module\Story;

use App\Entity\Module\Story\Story;
use App\Form\Widget as WidgetType;
use App\Service\Interface\CoreLocatorInterface;
use App\Twig\Translation\IntlRuntime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * StoryType.
 *
 * @author Sébastien FOURNIER <contact@sebastien-fournier.com>
 */
class StoryType extends AbstractType
{
    private TranslatorInterface $translator;
    private string $locale;
    private bool $inAdmin;

    /**
     * TabType constructor.
     */
    public function __construct(
        private readonly CoreLocatorInterface $coreLocator,
        private readonly IntlRuntime $intlExtension
    ) {
        $this->translator = $this->coreLocator->translator();
        $request = $coreLocator->request();
        $this->locale = $request->getLocale();
        $this->inAdmin = preg_match('/\/admin-'.$_ENV['SECURITY_TOKEN'].'/', $request->getUri());
    }

    /**
     * @throws \Exception
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $class = $this->inAdmin ? 'col-md-3' : 'col-12';

        $builder->add('lastName', TextType::class, [
            'label' => $this->translator->trans('Nom de famille', [], 'front_form'),
            'attr' => [
                'placeholder' => $this->inAdmin ? $this->translator->trans('Saisissez un nom', [], 'front_form') : false,
                'group' => $class,
            ],
            'constraints' => [new Assert\NotBlank()],
        ]);

        $builder->add('firstName', TextType::class, [
            'label' => $this->translator->trans('Prénom', [], 'front_form'),
            'attr' => [
                'placeholder' => $this->inAdmin ? $this->translator->trans('Saisissez un prénom', [], 'front_form') : false,
                'group' => $class,
            ],
            'constraints' => [new Assert\NotBlank()],
        ]);

        $builder->add('birthday', DateType::class, [
            'label' => $this->translator->trans("Date d'anniversaire", [], 'front_form'),
            'required' => !$this->inAdmin,
            'widget' => 'single_text',
            'html5' => false,
            'attr' => [
                'placeholder' => $this->inAdmin ? $this->translator->trans('Saisissez une date', [], 'front_form') : false,
                'group' => $class,
                'class' => 'mc-datepicker date-filter',
                'data-type' => 'date',
            ],
            'format' => $this->intlExtension->formatDate($this->locale)->datepickerPHP,
            'constraints' => !$this->inAdmin ? [new Assert\NotBlank()] : [],
        ]);

        $builder->add('email', TextType::class, [
            'label' => $this->translator->trans('E-mail', [], 'front_form'),
            'attr' => [
                'placeholder' => $this->inAdmin ? $this->translator->trans('Saisissez un email', [], 'front_form') : false,
                'group' => $class,
            ],
            'constraints' => [new Assert\NotBlank(), new Assert\Email()],
        ]);

        if ($this->inAdmin) {
            $builder->add('locale', WidgetType\LanguageIconType::class, [
                'label' => $this->translator->trans('Langue par défaut', [], 'admin'),
                'attr' => ['group' => 'col-md-3'],
                'constraints' => [new Assert\NotBlank()],
            ]);
        }

        $builder->add('message', TextareaType::class, [
            'label' => $this->translator->trans('Message', [], 'front_form'),
            'editor' => false,
            'attr' => [
                'placeholder' => $this->inAdmin ? $this->translator->trans('Saisissez un message', [], 'front_form') : false,
                'group' => 'col-12',
            ],
            'constraints' => [new Assert\NotBlank()],
        ]);

        if (!$this->inAdmin) {
            $builder->add('submit', SubmitType::class, [
                'label' => 'inspiration' === $options['colId']
                    ? $this->translator->trans('Envoyer mon expérience', [], 'front_form')
                    : $this->translator->trans('Envoyer ma story', [], 'front_form'),
            ]);
        } else {
            $save = new WidgetType\SubmitType($this->translator);
            $save->add($builder);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Story::class,
            'website' => null,
            'colId' => null,
            'translation_domain' => 'front_form',
        ]);
    }
}

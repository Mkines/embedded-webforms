<?php
// ../src/AppBundle/Forms/AcademicCoachRequestForm.php

namespace AppBundle\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

// Form Types:
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AcademicCoachRequestForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Grab the data model this form represents:
        $MainEntity = $builder->getData();
        $builder
            ->add('firstName', TextType::class, array('required' => true, 'label' => 'First Name ', 'attr' => array('class' => 'divided')))
            ->add('lastName', TextType::class, array('required' => true, 'label' => 'Last Name ', 'attr' => array('class' => 'divided')))
            ->add('email', TextType::class, array('required' => true, 'label' => 'Email', 'attr' => array('class' => 'long')))
            ->add('cellPhone', TextType::class, array('required' => true, 'label' => 'Cell Phone'))
            ->add('yearInCollege', ChoiceType::class, array(
                'attr' => array(
                    'class' => 'main-select-box',
                ),
                'choices' => array(
                    '' => '',
                    'Freshman' => 'Freshman',
                    'Sophomore' => 'Sophomore',
                    'Junior' => 'Junior',
                    'Senior' => 'Senior',
                    'Other' => 'Other'
                ),
                'label' => 'Year in College:',
                'expanded' => false,
                'multiple' => false,
                'placeholder' => Null,
            ))
            ->add('major', TextType::class, array('required' => true, 'label' => 'Major', 'attr' => array('class' => 'long')))
            ->add('reasonForCoachRequest', TextareaType::class, array('required' => true, 'label' => 'Reason you are requesting an Academic Coach: '))
            ->add('recommendation', TextType::class, array('required' => false, 'label' => 'If someone recommended an Academic Coach to you, who was it?', 'attr' => array('class' => 'long')))
            ->add('coachRequestNames', TextareaType::class, array('required' => false, 'label' => 'Please indicate if there is an Academic Coach you would like to request:'))
        ;

        $builder->add('submitRequest', SubmitType::class, array('label' => 'Submit Academic Coach Request', 'attr' => array('class' => 'submit-button-green')));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
        ));
    }
}
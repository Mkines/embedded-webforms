<?php
// ../src/AppBundle/Forms/GuruContactForm.php

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

class GuruContactForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Grab the data model this form represents:
        $MainEntity = $builder->getData();

        $builder
            ->add('firstName', TextType::class, array('required' => true, 'label' => 'First Name ', 'attr' => array('class' => 'divided')))
            ->add('lastName', TextType::class, array('required' => true, 'label' => 'Last Name ', 'attr' => array('class' => 'divided')))
            ->add('email', TextType::class, array('required' => true, 'label' => 'Contact Email', 'attr' => array('class' => 'long')))
            ->add('clubOrgName', TextType::class, array('required' => true, 'label' => 'Club/Organization Name', 'attr' => array('class' => 'long')))
            ->add('additionalNotes', TextareaType::class, array('required' => false, 'label' => 'Additional Notes or Comments:'))
            ;

        $builder->add('submitRequest', SubmitType::class, array('label' => 'Submit to Student Gurus', 'attr' => array('class' => 'submit-button-green')));
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
<?php
// ../src/AppBundle/Forms/FloryHonorsProgramRequestForm.php

namespace AppBundle\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

// Form Types:
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FloryHonorsProgramRequestForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fullname', TextType::class, array('required' => true, 'label' => 'Your Full Name', 'attr'=>array('class'=>'long')))
            ->add('highSchool', TextType::class, array('required' => true, 'label' => 'High School', 'attr'=>array('class'=>'long')))

            // File Uploader:
            ->add('essayAttached', FileType::class, array('label' => 'Essay (Doc, DocX, or PDF file)'))
        ;

        $builder->add('submit', SubmitType::class, array('label' => 'Submit Essay', 'attr'=>array('class'=>'submit-button-green')));
    }
}
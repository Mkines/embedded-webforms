<?php
// ../src/AppBundle/Forms/AcademicCoachRequestForm.php

namespace AppBundle\Forms;

use AppBundle\System\TwigTemplate\FieldTypes\BCTextField;
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

class RefundRequestForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Grab the data model this form represents:
        $MainEntity = $builder->getData();

        $builder
            ->add('firstName', TextType::class, array('required' => true, 'label' => 'First Name:', 'attr' => array('class' => 'col-sm-4 divided-fields')))
            ->add('lastName', TextType::class, array('required' => true, 'label' => 'Last Name:', 'attr' => array('class' => 'col-sm-4 divided-fields')))
            ->add('studentId', TextType::class, array(
                'required' => true,
                'label' => 'Student ID#:',
                'helper_text'=>'<strong>Note:</strong> it\'s on your college issued student ID card.',
                'attr' => array('class' => 'col-sm-4 new-line')))
            ->add('refundAmount', TextType::class, array('required' => true, 'label' => 'Refund Amount:', 'attr' => array('class' => 'col-sm-2 short-field new-line')))
            ->add('term', TextType::class, array('required' => false, 'label' => 'Term:', 'attr' => array('class' => 'col-sm-2 short-field')))
            ->add('akademosVoucher', TextType::class, array('required' => false, 'label' => 'Akademos Voucher Amount:', 'attr' => array('class' => 'col-sm-2 short-field new-line')))
            ->add('refundProcess',ChoiceType::class, array(
                'required' => false,
                'choices' => $MainEntity->getRefundProcessOptions(),
                'label' => 'Refund Request Payment Options:',
                'expanded' => true,
                'multiple' => false,
                'placeholder' => Null,
                'attr' => array('class' => 'col-sm-6')
            ))
            ->add('refundProcessBoxNumber', TextType::class, array(
                'required' => false,
                'label' => 'College Box Number Text Input',
                'attr' => array('class' => 'col-sm-2 short-field sub-field hidden label-none fadein')
            ))
            ->add('refundProcessOther', TextType::class, array(
                'required' => false,
                'label' => 'Other Option Text Input',
                'attr' => array('class' => 'col-sm-6 sub-field hidden label-none fadein')
            ))
            ->add('alternatePayeeName', TextType::class, array(
                'required' => false,
                'label' => 'Alternate Refund Recipient:',
                'helper_text'=>'<strong>Note:</strong> for example put your parent\'s name here if they should receive the refund check.',
                'attr' => array('class' => 'col-sm-4 new-line')))
            ->add('userSignature', TextType::class, array(
                'required' => true,
                'label' => 'Digital Signature:',
                'attr' => array('class' => 'col-sm-4 new-line')))
            ;

        // Submit Button:
        $builder->add('submitForm', SubmitType::class, array('label' => 'Submit Refund Request', 'attr'=>array('class'=>'college-green form-submit-button')));
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
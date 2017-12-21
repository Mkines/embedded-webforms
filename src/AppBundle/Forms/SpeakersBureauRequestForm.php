<?php
// ../src/AppBundle/Forms/SpeakersBureauRequestForm.php

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

class SpeakersBureauRequestForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('org', TextType::class, array('required' => true, 'label' => 'Organization', 'attr'=>array('class'=>'long')))
            ->add('orgRep', TextType::class, array('required' => true, 'label' => 'Organization\'s Representative'))
            ->add('addressLine1', TextType::class, array('required' => true, 'label' => 'Address Line 1', 'attr'=>array('class'=>'long')))
            ->add('addressLine2', TextType::class, array('required' => false, 'label' => 'Address Line 2', 'attr'=>array('class'=>'long')))
            ->add('city', TextType::class, array('required' => true, 'label' => 'City'))
            ->add('state', TextType::class, array('required' => true, 'label' => 'State/Province/Region', 'attr'=>array('style'=>'margin-right:25px;')))
            ->add('zipcode', TextType::class, array('required' => true, 'label' => 'Zip/Postal Code'))
            ->add('email', TextType::class, array('required' => true, 'label' => 'Email', 'attr'=>array('class'=>'long')))
            ->add('phone', TextType::class, array('required' => false, 'label' => 'Phone'))
            ->add('typeOfEvent', TextType::class, array('required' => false, 'label' => 'Type of Event', 'attr'=>array('class'=>'long')))
            ->add('requestedDate', TextType::class, array('required' => true, 'label' => 'Requested Date',
                'attr' => array(
                    'placeholder'=>'mm/dd/yyyy'
                )))
            /* Working on fixing the time type field in symfony... for I.E.
             * ->add('requestedTime', TimeType::class, array(
                'label'=>'Event Start Time',
                'placeholder' => array(
                    'hour' => 'Hour', 'minute' => 'Minute', 'second' => 'Second',
                ),
                'input'  => 'timestamp',
                'widget' => 'single_text',
            ))*/
            ->add('requestedTime', TextType::class, array('required' => false, 'label' => 'Requested Time', 'attr'=>array('class'=>'short', 'placeholder'=>'12:00 PM')))
            ->add('estimatedAudience', TextType::class, array('required' => false, 'label' => 'Estimated Audience', 'attr'=>array('class'=>'short')))
            ->add('eventLocation', TextType::class, array('required' => true, 'label' => 'Event Location', 'attr'=>array('class'=>'long')))
            ->add('speakerFirstChoice', TextType::class, array('required' => true, 'label' => 'Speaker (First Choice)'))
            ->add('topicFirstChoice', TextType::class, array('required' => true, 'label' => 'Topic (First Choice)'))
            ->add('speakerSecondChoice', TextType::class, array('required' => false, 'label' => 'Speaker (Second Choice)'))
            ->add('topicSecondChoice', TextType::class, array('required' => false, 'label' => 'Topic (Second Choice)'))
            ->add('comments', TextAreaType::class, array('required' => false, 'label' => 'Comment Section:'))
            ->add('submit', SubmitType::class, array('label' => 'Submit Speaker Request', 'attr'=>array('class'=>'submit-button-green')));
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            //'' => null,
        ));
    }
}
<?php
namespace AppBundle\Form\Extension;


use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class TextTypeExtension extends AbstractTypeExtension
{
    public function getExtendedType()
    {
        return TextType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        // makes it legal for FileType fields to have an image_property option
        $resolver->setDefined(array('helper_text'));
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if (isset($options['helper_text'])) {
            // set an "helper_text" variable that will be available when rendering this field
            $view->vars['helper_text'] = $options['helper_text'];
        }
    }
}
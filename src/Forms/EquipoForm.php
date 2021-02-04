<?php


namespace App\Forms;


use App\Entity\Equipo;
use App\Entity\Usuario;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EquipoForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nombre', TextType::class)
            ->add('correo', EmailType::class)
            ->add('direccion_campo', TextType::class)
            ->add('fotoFile', FileType::class,[
            'label'=>'Foto (PNG o JPG)',
            'mapped'=>false,
            'required'=>false
        ])
            ->add('guardar', SubmitType::class, array('label' => 'Guardar'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Equipo::class,
        ));
    }


}
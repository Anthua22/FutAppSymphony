<?php


namespace App\Forms;


use App\Entity\Equipo;
use App\Entity\Partido;
use App\Entity\Usuario;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PartidoForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('equipolocal', EntityType::class, [
            'class' => Equipo::class
        ])->add('equipovisitante', EntityType::class, [
            'class' => Equipo::class
        ])->add('arbitro', EntityType::class, [
            'class' => Usuario::class
        ])->add('direccionencuentro', TextType::class)
            ->add('fecha_encuentro', DateTimeType::class)
            ->add('guardar', SubmitType::class, array('label' => 'Guardar'));
    }

    public function configureOptions(OptionsResolver  $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'=>Partido::class
        ));
    }
}
<?php


namespace App\Helper;


use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class Utils extends AbstractExtension
{
    public function isOpcionMenuActiva(string $opcionMenu) : bool
    {
        $resultado = strpos($_SERVER['REQUEST_URI'], $opcionMenu) !== false;
        if ($resultado === false && $opcionMenu === 'index')
            $resultado = $_SERVER['REQUEST_URI'] === '/';
        return $resultado;
    }

    public function isOpcionMenuActivaInArray(array $opcionesMenu) : bool
    {
        foreach ($opcionesMenu as $opcionMenu) {
            if ($this->isOpcionMenuActiva($opcionMenu) === true)
                return true;
        }

        return false;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('menuactivo',[$this, 'isOpcionMenuActiva']),
            new TwigFunction('menuarrayfunction',[$this, 'isOpcionMenuActivaInArray'])
        ];
    }
}
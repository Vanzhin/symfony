<?php

namespace App\Twig\Extension;

use Symfony\Component\Asset\Packages;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Twig\Extension\RuntimeExtensionInterface;

class AppUpLoadedAsset implements RuntimeExtensionInterface
{


    private ParameterBagInterface $parameterBag;
    private Packages $packages;

    public function __construct(ParameterBagInterface $parameterBag, Packages $packages)
    {
        $this->parameterBag = $parameterBag;
        $this->packages = $packages;
    }

    public function asset(string $config, ?string $fileName, string $defaultImagePath = 'default_image' ): string
    {
        if ($fileName){
            return $this->packages->getUrl($this->parameterBag->get($config) . DIRECTORY_SEPARATOR . $fileName);

        }
        return $this->packages->getUrl($this->parameterBag->get($defaultImagePath));
    }
}
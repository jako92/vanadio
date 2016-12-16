<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),            
            new Brasa\SeguridadBundle\BrasaSeguridadBundle(),
            new Brasa\GeneralBundle\BrasaGeneralBundle(),
            new Brasa\TransporteBundle\BrasaTransporteBundle(),
            new Brasa\RecursoHumanoBundle\BrasaRecursoHumanoBundle(),
            new Brasa\ContabilidadBundle\BrasaContabilidadBundle(),
            new Brasa\TurnoBundle\BrasaTurnoBundle(),
            new Brasa\CarteraBundle\BrasaCarteraBundle(),
            new Brasa\AfiliacionBundle\BrasaAfiliacionBundle(),
            new Brasa\AdministracionDocumentalBundle\BrasaAdministracionDocumentalBundle(),
            new Brasa\InventarioBundle\BrasaInventarioBundle(),
            new Brasa\TesoreriaBundle\BrasaTesoreriaBundle(),
            new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
            
            // These are the other bundles the SonataAdminBundle relies on
            new Sonata\CoreBundle\SonataCoreBundle(),
            new Sonata\BlockBundle\SonataBlockBundle(),
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),

            // And finally, the storage and SonataAdminBundle
            new Sonata\DoctrineORMAdminBundle\SonataDoctrineORMAdminBundle(),
            new Sonata\AdminBundle\SonataAdminBundle(),            
        ];

        if (in_array($this->getEnvironment(), ['dev', 'test'], true)) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function getRootDir()
    {
        return __DIR__;
    }

    public function getCacheDir()
    {
        return dirname(__DIR__).'/var/cache/'.$this->getEnvironment();
    }

    public function getLogDir()
    {
        return dirname(__DIR__).'/var/logs';
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml');
    }
}

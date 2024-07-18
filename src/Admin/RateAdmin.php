<?php

declare(strict_types=1);

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Sonata\AdminBundle\Show\ShowMapper;

final class RateAdmin extends AbstractAdmin
{
    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        $collection
            ->remove('create')
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('baseCurrency.code')
            ->add('targetCurrency.code')
            ->add('rate')
            ->add('lastUpdated')
        ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('baseCurrency.code')
            ->add('targetCurrency.code')
            ->add('rate')
            ->add('lastUpdated')
            ->add(ListMapper::NAME_ACTIONS, null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ],
            ]);
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->add('baseCurrency', null, ['disabled' => true])
            ->add('targetCurrency', null, ['disabled' => true])
            ->add('rate')
            ->add('lastUpdated', null, ['attr' => ['disabled' => 'disabled']])
        ;
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('baseCurrency.code')
            ->add('targetCurrency.code')
            ->add('rate')
            ->add('lastUpdated')
        ;
    }
}

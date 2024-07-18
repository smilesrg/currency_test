<?php declare(strict_types=1);

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

final class CurrencyAdmin extends AbstractAdmin
{
    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('code')
            ->add('name')
            ->add('symbol')
        ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('code')
            ->add('name')
            ->add('symbol')
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
            ->add('code')
            ->add('name')
            ->add('symbol')
        ;
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('code')
            ->add('name')
            ->add('symbol')
        ;
    }
}

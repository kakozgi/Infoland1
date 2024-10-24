<?php

declare(strict_types=1);

namespace App\Orchid;

use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemPermission;
use Orchid\Platform\OrchidServiceProvider;
use Orchid\Screen\Actions\Menu;
use Orchid\Support\Color;


class PlatformProvider extends OrchidServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @param Dashboard $dashboard
     *
     * @return void
     */
    public function boot(Dashboard $dashboard): void
    {
        parent::boot($dashboard);

        // ...
    }

    /**
     * Register the application menu.
     *
     * @return Menu[]
     */
    public function menu(): array
    {
        return [
            // Menu::make('Get Started')
            //     ->icon('bs.book')
            //     ->title('Navigation')
            //     ->route(config('platform.index')),

            // Menu::make('Sample Screen')
            //     ->icon('bs.collection')
            //     ->route('platform.example')
            //     ->badge(fn () => 6),

            // Menu::make('Form Elements')
            //     ->icon('bs.card-list')
            //     ->route('platform.example.fields')
            //     ->active('*/examples/form/*'),

            // Menu::make('Overview Layouts')
            //     ->icon('bs.window-sidebar')
            //     ->route('platform.example.layouts'),

            // Menu::make('Grid System')
            //     ->icon('bs.columns-gap')
            //     ->route('platform.example.grid'),

            // Menu::make('Charts')
            //     ->icon('bs.bar-chart')
            //     ->route('platform.example.charts'),

            // Menu::make('Cards')
            //     ->icon('bs.card-text')
            //     ->route('platform.example.cards')
            //     ->divider(),

            Menu::make(__('Users'))
                ->icon('bs.people')
                ->route('platform.systems.users')
                ->permission('platform.systems.users')
                ->title(__('Access Controls')),

            Menu::make(__('Roles'))
                ->icon('bs.shield')
                ->route('platform.systems.roles')
                ->permission('platform.systems.roles')
                ->divider(),

            Menu::make('Facturacion por Cliente')
                ->icon('printer')  // Icono del menú
                ->route('platform.impresoras_contador')  // La ruta que creamos para listar impresoras
                -> title('Acciones') ,   // Título
               // ->permission('platform.impresoras.view'), // Permiso necesario para ver el menú

            Menu::make('Ingreso Contador Mensual')
                ->icon('printer')  // Icono del menú
                ->route('platform.impresoras_contador_editable')  // La ruta que creamos para listar impresoras
                 ,   // Título
               // ->permission('platform.impresoras.view'), // Permiso necesario para ver el menú

            Menu::make('Contador Contrato Individual')
                ->icon('printer')  // Icono del menú
                ->route('platform.contratos_impresoras')  // La ruta que creamos para listar contratos de impresoras
                ->divider()  ,   // Título
               // ->permission('platform.contratosImpresoras.view'), // Permiso necesario para ver el menú

            Menu::make('Clientes')
                ->icon('people')  // Icono del menú
                ->route('platform.clientes.list')  // La ruta que creamos para listar clientes
                ->title('Ingreso de Datos')     // Título del grupo en el menú
                ->permission('platform.clientes.view'), // Permiso necesario para ver el menú
Menu::make('Reemplazos')
                ->icon('printer')  // Icono del menú
                ->route('platform.reemplazos.list')  // La ruta que creamos para listar reemplazos
                  // Título
                ->permission('platform.reemplazos.view'), // Permiso necesario para ver el menú
            Menu::make('Marcas')
                ->icon('tag')  // Icono del menú
                ->route('platform.marcas.list')  // La ruta que creamos para listar marcas
                    // Título
                ->permission('platform.marcas.view'), // Permiso necesario para ver el menú

            Menu::make('Modelos de Impresora')
                ->icon('printer')  // Icono del menú
                ->route('platform.modelos_impresora.list')  // La ruta que creamos para listar modelos de impresora
                     // Título
                ->permission('platform.modelos_impresora.view'), // Permiso necesario para ver el menú

            Menu::make('Contratos')
                ->icon('file')  // Icono del menú
                ->route('platform.contratos.list')  // La ruta que creamos para listar contratos
                 // Título
                ->permission('platform.contratos.view'), // Permiso necesario para ver el menú

            Menu::make('Impresoras')
                ->icon('printer')  // Icono del menú
                ->route('platform.impresoras.list')  // La ruta que creamos para listar impresoras
                   // Título
                ->permission('platform.impresoras.view'), // Permiso necesario para ver el menú

            Menu::make('Facturas')
                ->icon('file')  // Icono del menú
                ->route('platform.facturas.list')  // La ruta que creamos para listar facturas
                   // Título
                ->permission('platform.facturas.view'), // Permiso necesario para ver el menú

            Menu::make('Historial de Contadores')
                ->icon('printer')  // Icono del menú
                ->route('platform.historiales.list')  // La ruta que creamos para listar historiales de contadores
                  // Título
                ->permission('platform.historiales.view'), // Permiso necesario para ver el menú

            
            
            Menu::make('Repuestos')
                ->icon('printer')  // Icono del menú
                ->route('platform.repuestos.list')  // La ruta que creamos para listar repuestos
                    // Título
                ->permission('platform.repuestos.view'), // Permiso necesario para ver el menú

            Menu::make('Reemplazo de Repuestos')
                ->icon('printer')  // Icono del menú
                ->route('platform.reemplazosRepuestos.list')  // La ruta que creamos para listar reemplazos de repuestos
                    // Título
                ->permission('platform.reemplazosRepuestos.view'), // Permiso necesario para ver el menú

            Menu::make('Contratos de Impresoras')
                ->icon('printer')  // Icono del menú
                ->route('platform.contratosImpresoras.list')  // La ruta que creamos para listar contratos de impresoras
                  // Título
                ->permission('platform.contratosImpresoras.view'), // Permiso necesario para ver el menú
        
            // Menu::make('Documentation')
            //     ->title('Docs')
            //     ->icon('bs.box-arrow-up-right')
            //     ->url('https://orchid.software/en/docs')
            //     ->target('_blank'),

            // Menu::make('Changelog')
            //     ->icon('bs.box-arrow-up-right')
            //     ->url('https://github.com/orchidsoftware/platform/blob/master/CHANGELOG.md')
            //     ->target('_blank')
            //     ->badge(fn () => Dashboard::version(), Color::DARK),
        ];
    }

    /**
     * Register permissions for the application.
     *
     * @return ItemPermission[]
     */
    public function permissions(): array
    {
        return [
            ItemPermission::group(__('System'))
                ->addPermission('platform.systems.roles', __('Roles'))
                ->addPermission('platform.systems.users', __('Users')),

            ItemPermission::group('Clientes')
                ->addPermission('platform.clientes.view', 'Ver Clientes')
                ->addPermission('platform.clientes.create', 'Crear Cliente')
                ->addPermission('platform.clientes.edit', 'Editar Cliente')
                ->addPermission('platform.clientes.delete', 'Eliminar Cliente'),

            ItemPermission::group('Marcas')
                ->addPermission('platform.marcas.view', 'Ver Marcas')
                ->addPermission('platform.marcas.create', 'Crear Marca')
                ->addPermission('platform.marcas.edit', 'Editar Marca') 
                ->addPermission('platform.marcas.delete', 'Eliminar Marca'),

            ItemPermission::group('Modelos de Impresora')
                ->addPermission('platform.modelos_impresora.view', 'Ver Modelos de Impresora')
                ->addPermission('platform.modelos_impresora.create', 'Crear Modelo de Impresora')
                ->addPermission('platform.modelos_impresora.edit', 'Editar Modelo de Impresora')
                ->addPermission('platform.modelos_impresora.delete', 'Eliminar Modelo de Impresora'),

            ItemPermission::group('Contratos')
                ->addPermission('platform.contratos.view', 'Ver Contratos')
                ->addPermission('platform.contratos.create', 'Crear Contrato')
                ->addPermission('platform.contratos.edit', 'Editar Contrato')
                ->addPermission('platform.contratos.delete', 'Eliminar Contrato'),
            
            ItemPermission::group('Impresoras')
                ->addPermission('platform.impresoras.view', 'Ver Impresoras')
                ->addPermission('platform.impresoras.create', 'Crear Impresora')
                ->addPermission('platform.impresoras.edit', 'Editar Impresora')
                ->addPermission('platform.impresoras.delete', 'Eliminar Impresora'),
            
            ItemPermission::group('Facturas')
                ->addPermission('platform.facturas.view', 'Ver Facturas')
                ->addPermission('platform.facturas.create', 'Crear Factura')
                ->addPermission('platform.facturas.edit', 'Editar Factura')
                ->addPermission('platform.facturas.delete', 'Eliminar Factura'),

            ItemPermission::group('Historial de Contadores')
                ->addPermission('platform.historiales.view', 'Ver Historial de Contadores')
                ->addPermission('platform.historiales.create', 'Crear Historial de Contador')
                ->addPermission('platform.historiales.edit', 'Editar Historial de Contador')
                ->addPermission('platform.historiales.delete', 'Eliminar Historial de Contador'),

            ItemPermission::group('Reemplazos')
                ->addPermission('platform.reemplazos.view', 'Ver Reemplazos')
                ->addPermission('platform.reemplazos.create', 'Crear Reemplazo')
                ->addPermission('platform.reemplazos.edit', 'Editar Reemplazo')
                ->addPermission('platform.reemplazos.delete', 'Eliminar Reemplazo'),

            ItemPermission::group('Repuestos')
                ->addPermission('platform.repuestos.view', 'Ver Repuestos')
                ->addPermission('platform.repuestos.create', 'Crear Repuesto')
                ->addPermission('platform.repuestos.edit', 'Editar Repuesto')
                ->addPermission('platform.repuestos.delete', 'Eliminar Repuesto'),

            ItemPermission::group('Reemplazo de Repuestos')
                ->addPermission('platform.reemplazosRepuestos.view', 'Ver Reemplazos de Repuestos')
                ->addPermission('platform.reemplazosRepuestos.create', 'Crear Reemplazo de Repuesto')
                ->addPermission('platform.reemplazosRepuestos.edit', 'Editar Reemplazo de Repuesto')
                ->addPermission('platform.reemplazosRepuestos.delete', 'Eliminar Reemplazo de Repuesto'),

            ItemPermission::group('Contratos de Impresoras')
                ->addPermission('platform.contratosImpresoras.view', 'Ver Contratos de Impresoras')
                ->addPermission('platform.contratosImpresoras.create', 'Crear Contrato de Impresora')
                ->addPermission('platform.contratosImpresoras.edit', 'Editar Contrato de Impresora')
                ->addPermission('platform.contratosImpresoras.delete', 'Eliminar Contrato de Impresora'),
        ];
    }
}

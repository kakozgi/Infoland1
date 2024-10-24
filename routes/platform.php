<?php

declare(strict_types=1);

use App\Orchid\Screens\Examples\ExampleActionsScreen;
use App\Orchid\Screens\Examples\ExampleCardsScreen;
use App\Orchid\Screens\Examples\ExampleChartsScreen;
use App\Orchid\Screens\Examples\ExampleFieldsAdvancedScreen;
use App\Orchid\Screens\Examples\ExampleFieldsScreen;
use App\Orchid\Screens\Examples\ExampleGridScreen;
use App\Orchid\Screens\Examples\ExampleLayoutsScreen;
use App\Orchid\Screens\Examples\ExampleScreen;
use App\Orchid\Screens\Examples\ExampleTextEditorsScreen;
use App\Orchid\Screens\PlatformScreen;
use App\Orchid\Screens\Role\RoleEditScreen;
use App\Orchid\Screens\Role\RoleListScreen;
use App\Orchid\Screens\User\UserEditScreen;
use App\Orchid\Screens\User\UserListScreen;
use App\Orchid\Screens\User\UserProfileScreen;
use Illuminate\Support\Facades\Route;
use Tabuna\Breadcrumbs\Trail;

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the need "dashboard" middleware group. Now create something great!
|
*/

// Main
Route::screen('/main', PlatformScreen::class)
    ->name('platform.main');

// Platform > Profile
Route::screen('profile', UserProfileScreen::class)
    ->name('platform.profile')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Profile'), route('platform.profile')));

// Platform > System > Users > User
Route::screen('users/{user}/edit', UserEditScreen::class)
    ->name('platform.systems.users.edit')
    ->breadcrumbs(fn (Trail $trail, $user) => $trail
        ->parent('platform.systems.users')
        ->push($user->name, route('platform.systems.users.edit', $user)));

// Platform > System > Users > Create
Route::screen('users/create', UserEditScreen::class)
    ->name('platform.systems.users.create')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.systems.users')
        ->push(__('Create'), route('platform.systems.users.create')));

// Platform > System > Users
Route::screen('users', UserListScreen::class)
    ->name('platform.systems.users')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Users'), route('platform.systems.users')));

// Platform > System > Roles > Role
Route::screen('roles/{role}/edit', RoleEditScreen::class)
    ->name('platform.systems.roles.edit')
    ->breadcrumbs(fn (Trail $trail, $role) => $trail
        ->parent('platform.systems.roles')
        ->push($role->name, route('platform.systems.roles.edit', $role)));

// Platform > System > Roles > Create
Route::screen('roles/create', RoleEditScreen::class)
    ->name('platform.systems.roles.create')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.systems.roles')
        ->push(__('Create'), route('platform.systems.roles.create')));

// Platform > System > Roles
Route::screen('roles', RoleListScreen::class)
    ->name('platform.systems.roles')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Roles'), route('platform.systems.roles')));

// Example...
Route::screen('example', ExampleScreen::class)
    ->name('platform.example')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push('Example Screen'));

Route::screen('/examples/form/fields', ExampleFieldsScreen::class)->name('platform.example.fields');
Route::screen('/examples/form/advanced', ExampleFieldsAdvancedScreen::class)->name('platform.example.advanced');
Route::screen('/examples/form/editors', ExampleTextEditorsScreen::class)->name('platform.example.editors');
Route::screen('/examples/form/actions', ExampleActionsScreen::class)->name('platform.example.actions');

Route::screen('/examples/layouts', ExampleLayoutsScreen::class)->name('platform.example.layouts');
Route::screen('/examples/grid', ExampleGridScreen::class)->name('platform.example.grid');
Route::screen('/examples/charts', ExampleChartsScreen::class)->name('platform.example.charts');
Route::screen('/examples/cards', ExampleCardsScreen::class)->name('platform.example.cards');

//Route::screen('idea', Idea::class, 'platform.screens.idea');


use App\Orchid\Screens\Cliente\ClienteListScreen;
use App\Orchid\Screens\Cliente\ClienteEditScreen;
use App\Orchid\Screens\Cliente\ClienteCreateScreen;
use App\Orchid\Screens\Cliente\ClienteDeleteScreen;

Route::screen('clientes', ClienteListScreen::class)
    ->name('platform.clientes.list');

Route::screen('clientes/create', ClienteCreateScreen::class)
    ->name('platform.clientes.create');

Route::screen('clientes/{cliente}/edit', ClienteEditScreen::class)
    ->name('platform.clientes.edit');

Route::screen('clientes/{cliente}/delete', ClienteDeleteScreen::class)
    ->name('platform.clientes.delete');

use App\Orchid\Screens\Marca\MarcaListScreen;
use App\Orchid\Screens\Marca\MarcaEditScreen;
use App\Orchid\Screens\Marca\MarcaCreateScreen;

Route::screen('marcas', MarcaListScreen::class)
    ->name('platform.marcas.list');

Route::screen('marcas/create', MarcaCreateScreen::class)
    ->name('platform.marcas.create');

Route::screen('marcas/{marca}/edit', MarcaEditScreen::class)
    ->name('platform.marcas.edit');

use App\Orchid\Screens\ModeloImpresoras\ModeloImpresoraListScreen;
use App\Orchid\Screens\ModeloImpresoras\ModeloImpresoraEditScreen;
use App\Orchid\Screens\ModeloImpresoras\ModeloImpresoraCreateScreen;

Route::screen('modelos_impresora', ModeloImpresoraListScreen::class)
    ->name('platform.modelos_impresora.list');

Route::screen('modelos_impresora/create', ModeloImpresoraCreateScreen::class)
    ->name('platform.modelos_impresora.create');

Route::screen('modelos_impresora/{modeloImpresora}/edit', ModeloImpresoraEditScreen::class)
    ->name('platform.modelos_impresora.edit');

use App\Orchid\Screens\Contrato\ContratoListScreen;
use App\Orchid\Screens\Contrato\ContratoEditScreen;
use App\Orchid\Screens\Contrato\ContratoCreateScreen;

Route::screen('contratos', ContratoListScreen::class)
    ->name('platform.contratos.list');

Route::screen('contratos/create', ContratoCreateScreen::class)
    ->name('platform.contratos.create');

Route::screen('contratos/{contrato}/edit', ContratoEditScreen::class)
    ->name('platform.contratos.edit');

use App\Orchid\Screens\Impresora\ImpresoraListScreen;
use App\Orchid\Screens\Impresora\ImpresoraEditScreen;
use App\Orchid\Screens\Impresora\ImpresoraCreateScreen;

Route::screen('impresoras', ImpresoraListScreen::class)
    ->name('platform.impresoras.list');

Route::screen('impresoras/create', ImpresoraCreateScreen::class)
    ->name('platform.impresoras.create');

Route::screen('impresoras/{impresora}/edit', ImpresoraEditScreen::class)
    ->name('platform.impresoras.edit');

use App\Orchid\Screens\Factura\FacturaListScreen;
use App\Orchid\Screens\Factura\FacturaEditScreen;
use App\Orchid\Screens\Factura\FacturaCreateScreen;

Route::screen('facturas', FacturaListScreen::class)
    ->name('platform.facturas.list');

Route::screen('facturas/create', FacturaCreateScreen::class)
    ->name('platform.facturas.create');

Route::screen('facturas/{factura}/edit', FacturaEditScreen::class)
    ->name('platform.facturas.edit');

use App\Orchid\Screens\HistorialContadores\HistorialContadorListScreen;
use App\Orchid\Screens\HistorialContadores\HistorialContadorEditScreen;
use App\Orchid\Screens\HistorialContadores\HistorialContadorCreateScreen;

Route::screen('historiales_contador', HistorialContadorListScreen::class)
    ->name('platform.historiales.list');

Route::screen('historiales_contador/create', HistorialContadorCreateScreen::class)
    ->name('platform.historiales.create');

Route::screen('historiales_contador/{historialContador}/edit', HistorialContadorEditScreen::class)
    ->name('platform.historiales.edit');

use App\Orchid\Screens\Reemplazo\ReemplazoListScreen;
use App\Orchid\Screens\Reemplazo\ReemplazoEditScreen;
use App\Orchid\Screens\Reemplazo\ReemplazoCreateScreen;

Route::screen('reemplazos', ReemplazoListScreen::class)
    ->name('platform.reemplazos.list');

Route::screen('reemplazos/create', ReemplazoCreateScreen::class)
    ->name('platform.reemplazos.create');

Route::screen('reemplazos/{reemplazo}/edit', ReemplazoEditScreen::class)
    ->name('platform.reemplazos.edit');

use App\Orchid\Screens\Repuesto\RepuestoListScreen;
use App\Orchid\Screens\Repuesto\RepuestoEditScreen;
use App\Orchid\Screens\Repuesto\RepuestoCreateScreen;

Route::screen('repuestos', RepuestoListScreen::class)
    ->name('platform.repuestos.list');

Route::screen('repuestos/create', RepuestoCreateScreen::class)
    ->name('platform.repuestos.create');

Route::screen('repuestos/{repuesto}/edit', RepuestoEditScreen::class)
    ->name('platform.repuestos.edit');

use App\Orchid\Screens\ReemplazoRepuesto\ReemplazoRepuestoListScreen;
use App\Orchid\Screens\ReemplazoRepuesto\ReemplazoRepuestoEditScreen;
use App\Orchid\Screens\ReemplazoRepuesto\ReemplazoRepuestoCreateScreen;

Route::screen('reemplazos_repuestos', ReemplazoRepuestoListScreen::class)
    ->name('platform.reemplazosRepuestos.list');

Route::screen('reemplazos_repuestos/create', ReemplazoRepuestoCreateScreen::class)
    ->name('platform.reemplazosRepuestos.create');

Route::screen('reemplazos_repuestos/{reemplazoRepuesto}/edit', ReemplazoRepuestoEditScreen::class)
    ->name('platform.reemplazosRepuestos.edit');

use App\Orchid\Screens\ContratoImpresoras\ContratoImpresoraListScreen;
use App\Orchid\Screens\ContratoImpresoras\ContratoImpresoraEditScreen;
use App\Orchid\Screens\ContratoImpresoras\ContratoImpresoraCreateScreen;

Route::screen('contratos_impresoras', ContratoImpresoraListScreen::class)
    ->name('platform.contratosImpresoras.list');

Route::screen('contratos_impresoras/create', ContratoImpresoraCreateScreen::class)  
    ->name('platform.contratosImpresoras.create');

Route::screen('contratos_impresoras/{contratoImpresora}/edit', ContratoImpresoraEditScreen::class)
    ->name('platform.contratosImpresoras.edit');

use App\Orchid\Screens\ContratoImpresorasScreen;

Route::screen('contratos_impresoras.prueba', ContratoImpresorasScreen::class)
    ->name('platform.contratos_impresoras');




use App\Orchid\Screens\ImpresoraContadorScreen;

Route::screen('impresoras_contador', ImpresoraContadorScreen::class)
    ->name('platform.impresoras_contador');

use App\Orchid\Screens\ImpresoraContadorEditableScreen;

Route::screen('impresoras_contador_editable', ImpresoraContadorEditableScreen::class)
    ->name('platform.impresoras_contador_editable');





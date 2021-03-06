<?php

Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('config:clear');
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('config:cache');
    return 'DONE'; //Return anything
});

//Intranet routes
Route::group(['prefix' => 'amolca'], function() {
    Route::get('novedades', 'Ecommerce\IntranetCatalogsController@all_catalogs');
    Route::get('novedades/medicina', 'Ecommerce\IntranetCatalogsController@medician_catalogs');
    Route::get('novedades/odontologia', 'Ecommerce\IntranetCatalogsController@odontology_catalogs');
});

//Admin routes
Route::group(['prefix' => 'am-admin'], function() {

	//Home routes
	Route::get('/', 'Admin\AdminHomeController@index');
	Route::get('/lost-password', function() {
		return session('access_token');
	});

	//Login & logout
    Route::get('/restore-password', 'AuthController@restore_password');
	Route::get('/login', 'AuthController@login');
	Route::post('/register', 'AuthController@register');
	Route::get('/logout', 'AuthController@AdminLogout');

	Route::group(['middleware' => 'admin'], function() {

		Route::get('/dashboard', 'Admin\AdminAccountController@index');
		Route::get('/mi-cuenta', 'Admin\AdminAccountController@MyAccount');

		//Custom routes for "Setting"
		Route::prefix('ajustes')->group(function(){
			Route::get('/', 'Admin\AdminSettingsController@settings');
			Route::get('/tienda', 'Admin\AdminSettingsController@shop');
		});

		//Custom routes for "LIBROS"
		Route::get('libros/inventario', 'Admin\AdminBooksController@inventory');
		Route::get('libros/especialidad', 'Admin\AdminBooksController@specialty');

		//Custom routes for "ESPECIALIDADES"
		Route::get('especialidades/{id}/libros', 'Admin\AdminSpecialtiesController@books');
		Route::get('especialidades/{id}/libros/{book}', 'Admin\AdminSpecialtiesController@show_book');

		//Custom routes for "Usuarios"
		Route::get('clientes', 'Admin\AdminUsersController@clients');

		Route::resources([
		    'libros' => 'Admin\AdminBooksController',
			'catalogos' => 'Admin\AdminCatalogsController',
            'intranet/catalogos' => 'Admin\AdminIntranetCatalogsController',
		    'especialidades' => 'Admin\AdminSpecialtiesController',
		    'autores' => 'Admin\AdminAuthorsController',
		    'sliders' => 'Admin\AdminSlidersController',
		    'usuarios' => 'Admin\AdminUsersController',
		    'blog' => 'Admin\AdminBlogsController',
		    'eventos' => 'Admin\AdminEventsController',
		    'pedidos' => 'Admin\AdminOrdersController',
		    'formularios' => 'Admin\AdminFormsController',
		    'lotes' => 'Admin\AdminLotsController',
		    'cupones' => 'Admin\AdminCouponsController',
		    'distribuidores' => 'Admin\AdminDealersController',
		    'banner' => 'Admin\AdminBannersController',
		]);

		Route::get('carritos', 'Admin\AdminOrdersController@carts');
		Route::get('carritos/{id}', 'Admin\AdminOrdersController@show');

		Route::get('usuarios/{id}/pedidos', 'Admin\AdminUsersController@orders');

		//Routes for get info "LIBOS"
		Route::prefix('books')->group(function(){
			Route::get('/', 'Admin\AdminBooksController@all');
			Route::post('/edit/{id}', 'Admin\AdminBooksController@edit');
			Route::post('/inventory', 'Admin\AdminBooksController@update_inventory');
		});

		//Routes for get info "CATALOGS"
		Route::prefix('catalogs')->group(function(){
			Route::get('/', 'Admin\AdminCatalogsController@all');
			Route::post('/edit/{id}', 'Admin\AdminCatalogsController@edit');
		});

        //Routes for get info "CATALOGS" de la intranet
		Route::prefix('intranet/catalogs')->group(function(){
			Route::get('/', 'Admin\AdminIntranetCatalogsController@all');
			Route::post('/edit/{id}', 'Admin\AdminIntranetCatalogsController@edit');
		});

		//Routes for get info "BLOGS"
		Route::prefix('blogs')->group(function(){
			Route::get('/', 'Admin\AdminBlogsController@all');
			Route::post('/edit/{id}', 'Admin\AdminBlogsController@edit');
		});

		//Routes for get info "EVENTOS"
		Route::prefix('events')->group(function(){
			Route::get('/', 'Admin\AdminEventsController@all');
			Route::post('/edit/{id}', 'Admin\AdminEventsController@edit');
		});

		//Routes for get info "ESPECIALIDADES"
		Route::prefix('specialties')->group(function(){
			Route::get('/', 'Admin\AdminSpecialtiesController@all');
			Route::get('/{id}/books', 'Admin\AdminSpecialtiesController@get_books');
			Route::post('/', 'Admin\AdminSpecialtiesController@store');
			Route::post('/edit/{id}', 'Admin\AdminSpecialtiesController@edit');
		});

		//Routes for get info "AUTORES"
		Route::prefix('authors')->group(function(){
			Route::get('/all', 'Admin\AdminAuthorsController@all');
			Route::post('/edit/{id}', 'Admin\AdminAuthorsController@edit');
		});

		//Routes for get info "SLIDERS"
		Route::prefix('api-sliders')->group(function(){
			Route::get('/all', 'Admin\AdminSlidersController@all');
			Route::post('/edit/{id}', 'Admin\AdminSlidersController@edit');
		});

		//Routes for get info "SLIDERS"
		Route::prefix('api-banners')->group(function(){
			Route::get('/all', 'Admin\AdminBannersController@all');
			Route::post('/edit/{id}', 'Admin\AdminBannersController@edit');
		});

		//Routes for get info "USERS"
		Route::prefix('users')->group(function(){
			Route::get('/all', 'Admin\AdminUsersController@all');
			Route::get('/clients', 'Admin\AdminUsersController@getclients');
			Route::post('/edit/{id}', 'Admin\AdminUsersController@edit');
			Route::get('/{id}/orders', 'Admin\AdminUsersController@orders');
		});

		//Routes for get info "PEDIDOS"
		Route::prefix('orders')->group(function(){
			Route::get('/', 'Admin\AdminOrdersController@all');
			Route::get('/carts', 'Admin\AdminOrdersController@all_carts');
			Route::post('/edit/{id}', 'Admin\AdminOrdersController@edit');
			Route::post('/{id}/states/store', 'Admin\AdminOrdersController@store_state');
		});

		//Routes for get info "FORMULARIOS"
		Route::prefix('forms')->group(function(){
			Route::get('/', 'Admin\AdminFormsController@all');
		});

		//Routes for get info "LOTES"
		Route::prefix('lots')->group(function(){
			Route::get('/', 'Admin\AdminLotsController@all');
			Route::post('/edit/{id}', 'Admin\AdminLotsController@edit');
		});

		//Routes for get info "LOTES"
		Route::prefix('coupons')->group(function(){
			Route::get('/', 'Admin\AdminCouponsController@all');
			Route::post('/edit/{id}', 'Admin\AdminCouponsController@edit');
		});
	});

	//Routes for get info "DISTRIBUIDORES"
	Route::prefix('dealers')->group(function(){
		Route::get('/', 'Admin\AdminDealersController@all');
		Route::get('/country/{id}', 'Admin\AdminDealersController@getbycountry');
		Route::post('/edit/{id}', 'Admin\AdminDealersController@edit');
	});

	//Routes for get info "PAISES"
	Route::prefix('countries')->group(function(){
		Route::get('/title/{title}', 'CountriesController@bytitle');
		Route::get('/all', 'CountriesController@index');
	});
});

//Authentication ecommerce routes
Route::get('/autologin', 'AuthController@autologin');
Route::get('/iniciar-sesion', 'Ecommerce\HomeController@login');
Route::get('/registrarse', 'Ecommerce\HomeController@register');
Route::get('/logout', 'AuthController@EcommerceLogout');
Route::get('/validate-wb', 'AuthController@validate_wb');

Route::group(['middleware' => 'ecommerce', 'prefix' => 'mi-cuenta'], function() {
	Route::get('/', 'Ecommerce\AccountController@account');
	Route::get('/pedidos', 'Ecommerce\AccountController@orders');
	Route::get('/direccion', 'Ecommerce\AccountController@direction');
	Route::get('/informacion', 'Ecommerce\AccountController@information');
});

//Rutas simples
Route::get('/', 'Ecommerce\HomeController@index');
Route::get('/carrito', 'Ecommerce\CartsController@index');
Route::get('/contacto', 'Ecommerce\HomeController@contact');
Route::get('/terminos-y-condiciones', 'Ecommerce\HomeController@termsandconditions');

Route::get('/finalizar-compra', 'Ecommerce\CheckoutController@checkout');
Route::post('/checkout/response', 'Ecommerce\CheckoutController@PaymentResponse');
Route::get('/checkout/respuesta', 'Ecommerce\CheckoutController@PaymentResponseView');
Route::post('/checkout/mercadopago', 'Ecommerce\CheckoutController@mercadopago');

//Novedades
Route::get('/novedades/{slug}', 'Ecommerce\BooksController@news');

//Catalogos
Route::get('/catalogos/medicina', 'Ecommerce\HomeController@MedicianCatalog');
Route::get('/catalogos/odontologia', 'Ecommerce\HomeController@OdontologyCatalog');

//Especialidades
Route::get('/especialidad/{slug}', 'Ecommerce\SpecialtiesController@show');
Route::get('/especialidad', function() { return redirect('/especialidades'); });
Route::get('/especialidades', 'Ecommerce\SpecialtiesController@index');

//Eventos
Route::get('/evento/{slug}', 'Ecommerce\EventsController@show');
Route::get('/evento', function() { return redirect('/eventos'); });
Route::get('/eventos', 'Ecommerce\EventsController@index');

//Autores
Route::get('/autores', 'Ecommerce\AuthorsController@index');
Route::get('/autor', function() { return redirect('/autores'); });
Route::get('/autor/{slug}', 'Ecommerce\AuthorsController@show');

Route::get('/blog', 'Ecommerce\PostsController@index');
Route::get('/buscar', 'Ecommerce\PostsController@searcher');
Route::get('/{slug}', 'Ecommerce\PostsController@show');

Auth::routes();

//Carts
Route::group(['prefix' => 'carts'], function() {
	Route::post('/', 'Ecommerce\CartsController@store');
	Route::post('/checkout', 'Ecommerce\CartsController@create_order');
	Route::put('/', 'Ecommerce\CartsController@update');
	Route::get('/{id}', 'Ecommerce\CartsController@show');
	Route::get('/{id}', 'Ecommerce\CartsController@get_orders');

	Route::post('/amount', 'Ecommerce\CartsController@change_amount');
	Route::get('/coupons/{code}', 'Ecommerce\CartsController@validate_coupon');

    // Visanet
    Route::post('/peru/visanet/save', 'Ecommerce\CartsController@visanet_save');
});

//Books
Route::group(['prefix' => 'books'], function() {
	Route::get('/{id}', 'Ecommerce\PostsController@post_info');
});

<?php 

use App\Http\Models\Options;
use App\Http\Models\Menus;
use App\Http\Models\MenuItems;

function COPMoney($money) {

	switch (get_option('sitecountry')) {
		case 'COLOMBIA':
			return '$' . number_format($money, 0, ',', '.') . ' COP';
			break;

		case 'PANAMA':
			return '$' . number_format($money, 2, '.', ',') . ' USD';
			break;

		case 'ARGENTINA':
			return '$' . number_format($money, 2, ',', '.') . ' ARS';
			break;

		case 'MEXICO':
			return '$' . number_format($money, 2, '.', ',') . ' MXN';
			break;

		case 'PERU':
			//return 'S/ ' . number_format($money, 2, '.', ',');
			return '$' . number_format($money, 2, '.', ',') . 'USD';
			break;

		case 'DOMINICAN REPUBLIC':
			return 'DOP$ ' . number_format($money, 2, ',', '.');
			break;
	}
}

/*Mailer*/
function mailer_get_country() {
    return "COLOMBIA";
}

function mailer_get_domain() {
    return "www.amolca.com.co";
}

function mailer_get_name() {
    return "Amolca Colombia";
}

function mailer_get_me() {
    return "ventas@amolca.com.co";
}

function mailer_get_cc() {
	return ["gerencia@amolca.com.co", "asistentepresidencia@amolca.us", "contacto@amolca.com"];
}

function format_date($str) {
	$date = new Date($str);
	$date = $date->format('j F, Y');

	return $date;
}

//Global function to get option of this single database
function get_option($name) {
	$option = Options::where('option_name', '=', $name)->first();

	if($option == null) {
        return 'NULL';
    }

    return $option->option_value;
}

//GLobal function to get an menu by slug
function get_nav_menu($slug, $class = 'hmenu', $id = 'hmenu') {

	$menu = Menus::where('slug', '=', $slug)->first();

	if($menu == null) {
        return response()->json(['status' => 404, 'message' => 'El recurso que estas buscando no existe'], 404);
    }

    $items = MenuItems::where([ ['menu_id', '=', $menu->id], ['parent_id', '=', 0] ])
    					->orderBy('order', 'asc')
    					->get();

    //Recorrer parent items y agregar los items hijos si es que existen
    for ($i = 0; $i < count($items); $i++) { 

    	$childs = MenuItems::where([ ['menu_id', '=', $menu->id], ['parent_id', '=', $items[$i]->id] ])
    					->orderBy('order', 'asc')
    					->get();

    	if( $childs !== null && count($childs) > 0 ) {
    		$items[$i]->childs = $childs;
    	}

    } ?>

    <ul class="<?php echo $class; ?>" id="<?php echo $id; ?>">

		<?php foreach ($items as $item) { ?>

			<?php if ($item->state == 'PUBLISHED' && $item->parent_id == 0) { ?>

			<li class="<?php echo 'item-menu-' . $class . ' item-' . $item->id; ($item->class !== null) ? ' ' . $item->class : ''; ?>" id="item-<?php echo $item->id; ?>">

				<a href="<?php echo $item->link ?>" target="<?php echo $item->target_link ?>">

					<?php if($item->icon !== null) { ?>
					<img src="<?php echo $item->icon ?>" alt="<?php echo $item->title ?>">
					<?php } ?>
					<span><?php echo $item->title; ?></span>

				</a>

				<?php if ( $item->childs && count($item->childs) > 0 ) { ?>

				<ul class="submenu" id="submenu-item-<?php echo $item->id; ?>">
					
					<?php foreach ($item->childs as $child) { ?>

					<li class="<?php echo 'item-menu-' . $class . ' item-' . $child->id; ($child->class !== null) ? ' ' . $child->class : ''; ?>" id="item-<?php echo $child->id; ?>">

						<a href="<?php echo $child->link; ?>" target="<?php echo $child->target_link; ?>">

							<?php if($child->icon !== null) { ?>
							<img src="<?php echo $child->icon; ?>" alt="<?php echo $child->title; ?>">
							<?php } ?>

							<span><?php echo $child->title; ?></span>
						</a>

					</li>

					<?php }; ?>

				</ul>

				<?php } ?>
			</li>

			<?php } ?>
			
		<?php }; ?>
		
	</ul>

	<?php

}
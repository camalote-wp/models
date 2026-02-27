<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/tingeka
 * @since      1.0.0
 *
 * @package    Et_Models
 * @subpackage Et_Models/admin
 */

namespace EnfantTerrible\Models\Definitions\Fotoperiodismo;

use EnfantTerrible\Models\Abstracts\AbstractBootstrap;

use EnfantTerrible\Models\Definitions\Fotoperiodismo\PostType;
use EnfantTerrible\Models\Definitions\Fotoperiodismo\Meta;
use EnfantTerrible\Models\Definitions\Fotoperiodismo\Blocks;
use EnfantTerrible\Models\Definitions\Fotoperiodismo\View;
use EnfantTerrible\Models\Definitions\Fotoperiodismo\Rest;
use EnfantTerrible\Models\Definitions\Fotoperiodismo\MigrationPage;

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Et_Models
 * @subpackage Et_Models/admin
 * @author     Martín García <tin.geka@gmail.com>
 */
class Bootstrap extends AbstractBootstrap {

	/**
	 * The model name.
	 * 
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $model_name    The name of the model.
	 */
	protected string $model_name = 'fotoperiodismo';

	/**
	 * Retrieves the components to be registered for the model.
	 *
	 * @since    1.0.0
	 * @return    array   An array of class names of the components to be registered for the model.
	 */
	public function get_components(): array {
		return [
			PostType::class,
			Meta::class,
			Blocks::class,
			Rest::class,
			MigrationPage::class
		];
	}
}

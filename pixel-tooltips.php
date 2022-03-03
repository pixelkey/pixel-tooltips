<?php
/**
 * pixel-tooltips
 *
 * @package       PIXELTOOLTIP
 * @author        Pixel Key
 * @license       gplv2
 * @version       1.0.0
 *
 * @wordpress-plugin
 * Plugin Name:   pixel-tooltips
 * Plugin URI:    https://pixelkey.com
 * Description:   Customisable tooltips for any occasion
 * Version:       1.0.0
 * Author:        Pixel Key
 * Author URI:    https://pixelkey.com
 * Text Domain:   pixel-tooltips
 * Domain Path:   /languages
 * License:       GPLv2
 * License URI:   https://www.gnu.org/licenses/gpl-2.0.html
 *
 * You should have received a copy of the GNU General Public License
 * along with pixel-tooltips. If not, see <https://www.gnu.org/licenses/gpl-2.0.html/>.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * HELPER COMMENT START
 * 
 * This file contains the main information about the plugin.
 * It is used to register all components necessary to run the plugin.
 * 
 * The comment above contains all information about the plugin 
 * that are used by WordPress to differenciate the plugin and register it properly.
 * It also contains further PHPDocs parameter for a better documentation
 * 
 * The function PIXELTOOLTIP() is the main function that you will be able to 
 * use throughout your plugin to extend the logic. Further information
 * about that is available within the sub classes.
 * 
 * HELPER COMMENT END
 */

// Plugin name
define( 'PIXELTOOLTIP_NAME',			'pixel-tooltips' );

// Plugin version
define( 'PIXELTOOLTIP_VERSION',		'1.0.0' );

// Plugin Root File
define( 'PIXELTOOLTIP_PLUGIN_FILE',	__FILE__ );

// Plugin base
define( 'PIXELTOOLTIP_PLUGIN_BASE',	plugin_basename( PIXELTOOLTIP_PLUGIN_FILE ) );

// Plugin Folder Path
define( 'PIXELTOOLTIP_PLUGIN_DIR',	plugin_dir_path( PIXELTOOLTIP_PLUGIN_FILE ) );

// Plugin Folder URL
define( 'PIXELTOOLTIP_PLUGIN_URL',	plugin_dir_url( PIXELTOOLTIP_PLUGIN_FILE ) );

/**
 * Load the main class for the core functionality
 */
require_once PIXELTOOLTIP_PLUGIN_DIR . 'core/class-pixel-tooltips.php';

/**
 * The main function to load the only instance
 * of our master class.
 *
 * @author  Pixel Key
 * @since   1.0.0
 * @return  object|Pixel_Tooltips
 */
function PIXELTOOLTIP() {
	return Pixel_Tooltips::instance();
}

PIXELTOOLTIP();

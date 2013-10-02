<?php
/**
 * Kunena Component
 * @package Kunena.Administrator.Template
 * @subpackage Categories
 *
 * @copyright (C) 2008 - 2013 Kunena Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.kunena.org
 **/
defined ( '_JEXEC' ) or die ();

/**
 * Implements Kunena specific functions for page layouts.
 *
 * @see KunenaLayout
 */
class KunenaLayoutPage extends KunenaLayout
{
	/**
	 * Get main MVC triad from current layout.
	 *
	 * @param   $path
	 * @return  KunenaControllerDisplay
	 */
	public function request($path)
	{
		return KunenaRequest::factory($path.'/Display')->setPrimary()->set('layout', $this->getLayout());
	}

	/**
	 * Execute main MVC triad to get the current layout.
	 *
	 * @param   $path
	 * @return  KunenaLayout
	 */
	public function execute($path)
	{
		return $this->request($path)->execute();
	}

	/**
	 * Add path to breadcrumbs.
	 * @param $text
	 * @param $uri
	 * @param $ignore
	 *
	 * @return $this
	 */
	public function addBreadcrumb($text, $uri, $ignore = false)
	{
		/*if ($ignore) {
			$menu = JFactory::getApplication()->getMenu();
			if ($input->getCmd('view').'/'.$input->getCmd('layout', 'default') == $this->name) return $this;
		}*/
		$this->breadcrumb->addItem($text, KunenaRoute::normalize($uri));

		return $this;
	}

	/**
	 * Returns layout class.
	 *
	 * <code>
	 *	// Output pagination/pages layout with current cart instance.
	 *	echo KunenaLayout::factory('Pagination/Pages')->set('pagination', $this->pagination);
	 * </code>
	 *
	 * @param   mixed $paths String or array of strings.
	 * @param   string $base Base path.
	 * @return  KunenaLayout
	 */
	public static function factory($paths, $base = 'pages') {
		$paths = (array) $paths;

		$app = JFactory::getApplication();
		// Add all paths for the template overrides.
		if ($app->isAdmin()) {
			$template = KunenaFactory::getAdminTemplate();
		} else {
			$template = KunenaFactory::getTemplate();
		}

		$templatePaths = array();
		foreach ($paths as $path) {
			if (!$path) continue;

			$path = (string) preg_replace('|\\\|', '/', strtolower($path));
			$lookup = $template->getTemplatePaths("{$base}/{$path}", true);
			foreach ($lookup as $loc) {
				array_unshift($templatePaths, $loc);
			}
		}

		// Go through all the matching layouts.
		$path = 'Undefined';
		foreach ($paths as $path) {
			if (!$path) continue;

			// Attempt to load layout class if it doesn't exist.
			$class = 'KunenaPage' . (string) preg_replace('/[^A-Z0-9_]/i', '', $path);
			$fpath = (string) preg_replace('|\\\|', '/', strtolower($path));
			if (!class_exists($class)) {
				$filename = JPATH_BASE . "/components/com_kunena/page/{$fpath}.php";
				if (!is_file($filename)) {
					continue;
				}
				require_once $filename;
			}

			// Create layout object.
			return new $class($fpath, $templatePaths);
		}

		// Create default layout object.
		return new KunenaLayoutPage($path, $templatePaths);
	}
}

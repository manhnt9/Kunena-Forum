<?php
/**
 * Kunena Component
 *
 * @package         Kunena.Administrator
 * @subpackage      Controllers
 *
 * @copyright       Copyright (C) 2008 - 2016 Kunena Team. All rights reserved.
 * @license         http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link            https://www.kunena.org
 **/
defined('_JEXEC') or die();

/**
 * Kunena Backend Stats Controller
 *
 * @since  2.0
 */
class KunenaAdminControllerStats extends KunenaController
{
	/**
	 * @var null|string
	 */
	protected $baseurl = null;

	/**
	 * Construct
	 *
	 * @param   array $config config
	 *
	 * @since    2.0
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);
		$this->baseurl = 'index.php?option=com_kunena&view=stats';
	}
}

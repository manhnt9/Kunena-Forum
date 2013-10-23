<?php
/**
 * Kunena Component
 * @package Kunena.Site
 * @subpackage Controllers.User
 *
 * @copyright (C) 2008 - 2013 Kunena Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.kunena.org
 **/
defined ( '_JEXEC' ) or die ();

/**
 * Class ComponentKunenaControllerUserEditUserDisplay
 */
class ComponentKunenaControllerUserEditUserDisplay extends ComponentKunenaControllerUserEditDisplay
{
	protected $name = 'User/Edit/User';

	public $changeUsername;
	public $frontendForm;

	protected function before()
	{
		parent::before();

		$userParams = JComponentHelper::getParams('com_users');

		// Check if user is allowed to change his name.
		$this->changeUsername = $userParams->get('change_login_name', 1);

		// Check to see if Frontend User Params have been enabled.
		if ($userParams->get('frontend_userparams', 0))
		{
			JFactory::getLanguage()->load('com_users', JPATH_ADMINISTRATOR);

			JForm::addFormPath(JPATH_ROOT.'/components/com_users/models/forms');
			JForm::addFieldPath(JPATH_ROOT.'/components/com_users/models/fields');

			JPluginHelper::importPlugin('user');

			$registry = new JRegistry($this->user->params);
			$form = JForm::getInstance('com_users.profile', 'frontend');
			$data = new StdClass();
			$data->params = $registry->toArray();
			$dispatcher = JDispatcher::getInstance();
			$dispatcher->trigger('onContentPrepareForm', array($form, $data));

			$form->bind($data);
			$this->frontendForm = $form->getFieldset('params');
		}

		$this->headerText = JText::_('COM_KUNENA_PROFILE_EDIT_USER_TITLE');
	}

	protected function prepareDocument()
	{
		$this->setTitle($this->headerText);
	}
}

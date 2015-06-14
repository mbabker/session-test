<?php
namespace BabDev;

use Joomla\Application\AbstractWebApplication;
use Joomla\Registry\Registry;
use Joomla\Session\Session;

final class Application extends AbstractWebApplication
{
	protected function doExecute()
	{
		$session = $this->getSession();

		echo sprintf('The session has been hit %d times.', $session->get('session.counter'));
	}

	public function getSession()
	{
		try
		{
			return parent::getSession();
		}
		catch (\RuntimeException $exception)
		{
			// The session isn't loaded
			$this->loadSession();

			return parent::getSession();
		}
	}

	private function loadSession()
	{
		// Generate a session name.
		$name = md5($this->get('secret', 'abc123') . $this->get('session_name', get_class($this)));

		// Calculate the session lifetime.
		$lifetime = (($this->get('lifetime')) ? $this->get('lifetime') * 60 : 900);

		// Get the session handler from the configuration.
		$handler = $this->get('session_handler', 'none');

		// Initialize the options for the Session object.
		$options = [
			'name'      => $name,
			'expire'    => $lifetime,
			'force_ssl' => $this->get('force_ssl')
		];

		// Instantiate the session object.
		$session = Session::getInstance($handler, $options);
		$session->initialise($this->input);

		if ($session->getState() == 'expired')
		{
			$session->restart();
		}
		else
		{
			$session->start();
		}

		if (!$session->get('registry') instanceof Registry)
		{
			// Registry has been corrupted somehow.
			$session->set('registry', new Registry('session'));
		}

		// Set the session object.
		$this->setSession($session);
	}
}

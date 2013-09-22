<?php
/**
 * @author Tomáš Blatný
 */
namespace GeoCaching\Model;

use GeoCaching\RegisterException;
use GeoCaching\Security\PasswordHasher;
use GeoCaching\VerifyException;
use Nette\ArrayHash;
use Nette\Utils\Strings;

class UserFacade extends Facade {
	/** @var \GeoCaching\Model\Users */
	protected $users;

	public function __construct(Users $users)
	{
		$this->users = $users;
	}

	public function registerUser(ArrayHash $data)
	{
		if($this->users->findOneBy('name', $data->name)) {
			throw new RegisterException("Uživatel '{$data->name}' již existuje.");
		}
		if($this->users->findOneBy('email', $data->email)) {
			throw new RegisterException("Uživatel s emailovou adresou '{$data->email}' již existuje.");
		}

		$salt = Strings::random(10, 'a-zA-Z0-9');
		$verify = Strings::random(5, 'A-Z');

		$user = ArrayHash::from(array(
			'name' => $data->name,
			'password' => PasswordHasher::hash($data->name . "@" . $data->password, $salt),
			'email' => $data->email,
			'salt' => $salt,
			'last_login' => time(),
			'verification_code' => $verify,
			'role' => 'member',
		));

		$this->users->insert($user);
		return $user;
	}

	public function verifyUser($name, $code)
	{
		if(!$user = $this->users->findOneBy('name', $name)) {
			throw new VerifyException("Uživatel '$name' neexistuje.");
		}

		if($user->verification_code !== $code) {
			throw new VerifyException("Špatný ověřovací kód.");
		}

		$user->update(array('email_verified' => 1));

		return true;
	}
}
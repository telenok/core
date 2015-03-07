<?php

namespace Telenok\Core\Security;

class UserProvider extends \Illuminate\Auth\EloquentUserProvider {

	/**
	 * Retrieve a user by their unique identifier.
	 *
	 * @param  mixed  $identifier
	 * @return \Illuminate\Auth\UserInterface|null
	 */
	public function retrieveById($identifier)
	{
		$now = \Carbon\Carbon::now();
		
		return $this->createModel()->newQuery()
				->whereId($identifier)
                ->active()->first();
	}
	
	/**
	 * Retrieve a user by their unique identifier and "remember me" token.
	 *
	 * @param  mixed  $identifier
	 * @param  string  $token
	 * @return \Illuminate\Auth\UserInterface|null
	 */
	public function retrieveByToken($identifier, $token)
	{
		$model = $this->createModel();
		$now = \Carbon\Carbon::now();

		return $model->newQuery()
                        ->where($model->getKeyName(), $identifier)
                        ->where($model->getRememberTokenName(), $token)
						->where('active', 1)
						->where('active_at_start', '<=', $now)
						->where('active_at_end', '>=', $now)->first();
	}
	
	/**
	 * Retrieve a user by the given credentials.
	 *
	 * @param  array  $credentials
	 * @return \Illuminate\Auth\UserInterface|null
	 */
	public function retrieveByCredentials(array $credentials)
	{
		// First we will add each credential element to the query as a where clause.
		// Then we can execute the query and, if we found a user, return it in a
		// Eloquent User "model" that will be utilized by the Guard instances.
		$query = $this->createModel()->newQuery();
		$now = \Carbon\Carbon::now();

		foreach ($credentials as $key => $value)
		{
			if ( ! str_contains($key, 'password')) $query->where($key, $value);
		}
		
		$query->where('active', 1)
				->where('active_at_start', '<=', $now)
				->where('active_at_end', '>=', $now);

		return $query->first();
	}

}
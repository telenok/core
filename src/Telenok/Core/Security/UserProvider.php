<?php namespace Telenok\Core\Security;

class UserProvider extends \Illuminate\Auth\EloquentUserProvider {

	/**
	 * Retrieve a user by their unique identifier.
	 *
	 * @param  mixed  $identifier
	 * @return \Illuminate\Auth\UserInterface|null
	 */
	public function retrieveById($identifier)
	{
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
        $r = range_minutes(config('cache.query.minutes', 0));

		return $model->newQuery()
                        ->where($model->getKeyName(), $identifier)
                        ->where($model->getRememberTokenName(), $token)
						->where('active', 1)
						->where('active_at_start', '<=', $r)
						->where('active_at_end', '>=', $r)->first();
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
        $r = range_minutes(config('cache.query.minutes', 0));

		foreach ($credentials as $key => $value)
		{
			if ( ! str_contains($key, 'password')) $query->where($key, $value);
		}
		
		$query->where('active', 1)
				->where('active_at_start', '<=', $r)
				->where('active_at_end', '>=', $r);

		return $query->first();
	}
}
<?php

namespace Telenok\Core\Security;

/**
 * @class Telenok.Core.Security.UserProvider
 * Class for user's authorization.
 */
class UserProvider extends \Illuminate\Auth\EloquentUserProvider
{
    /**
     * @method retrieveById
     * Retrieve a user by their unique identifier.
     *
     * @param {mixed} $identifier
     *
     * @return {Illuminate.Auth.UserInterface}
     * @member Telenok.Core.Security.UserProvider
     */
    public function retrieveById($identifier)
    {
        return $this->createModel()->newQuery()
                        ->whereId($identifier)
                        ->active()->first();
    }

    /**
     * @method retrieveByToken
     * Retrieve a user by their unique identifier and "remember me" token.
     *
     * @param {mixed}  $identifier
     * @param {String} $token
     *
     * @return {Illuminate.Auth.UserInterface}
     * @member Telenok.Core.Security.UserProvider
     */
    public function retrieveByToken($identifier, $token)
    {
        $model = $this->createModel();
        $r = range_minutes(config('cache.db_query.minutes', 0));

        return $model->newQuery()
                        ->where($model->getKeyName(), $identifier)
                        ->where($model->getRememberTokenName(), $token)
                        ->where('active', 1)
                        ->where('active_at_start', '<=', $r[1])
                        ->where('active_at_end', '>=', $r[0])->first();
    }

    /**
     * @method retrieveByCredentials
     * Retrieve a user by their unique identifier and "remember me" token.
     *
     * @param {Array} $credentials
     *
     * @return {Illuminate.Auth.UserInterface}
     * @member Telenok.Core.Security.UserProvider
     */
    public function retrieveByCredentials(array $credentials)
    {
        // First we will add each credential element to the query as a where clause.
        // Then we can execute the query and, if we found a user, return it in a
        // Eloquent User "model" that will be utilized by the Guard instances.
        $query = $this->createModel()->newQuery();
        $r = range_minutes(config('cache.db_query.minutes', 0));

        foreach ($credentials as $key => $value) {
            if (!str_contains($key, 'password')) {
                $query->where($key, $value);
            }
        }

        $query->where('active', 1)
                ->where('active_at_start', '<=', $r[1])
                ->where('active_at_end', '>=', $r[0]);

        return $query->first();
    }
}

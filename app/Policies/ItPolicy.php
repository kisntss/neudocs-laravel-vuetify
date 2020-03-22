<?php

namespace NeubusSrm\Policies;

use NeubusSrm\Models\Auth\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Class ItPolicy
 * @package NeubusSrm\Policies
 */
class ItPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

	/**
	 * @return bool
	 */
    public function menu() : bool {
    	return \Auth::user()->role == User::ROLE_IT;
    }
}

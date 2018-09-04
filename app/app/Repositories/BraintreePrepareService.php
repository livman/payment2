<?php

namespace App\Repositories;

use App\Repositories\PrepareServiceInterface;


Class BraintreePrepareService implements PrepareServiceInterface
{

	public function __construct()
	{

	}

	public function prepareService()
	{
		return $this;
	}

}
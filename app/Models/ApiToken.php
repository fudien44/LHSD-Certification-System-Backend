<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
class ApiToken extends Model implements Auditable
{
	use \OwenIt\Auditing\Auditable;
    protected $table = 'api_tokens';
    protected $guarded = array();
}

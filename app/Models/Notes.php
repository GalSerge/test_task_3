<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(
 *  definition="Notes",
 *  @SWG\Property(
 *      property="id",
 *      type="integer"
 *  ),
 *  @SWG\Property(
 *      property="fio",
 *      type="string"
 *  ),
 *  @SWG\Property(
 *      property="company",
 *      type="string"
 *  ),
 *  @SWG\Property(
 *      property="email",
 *      type="string"
 *  ),
 *     @SWG\Property(
 *      property="phone",
 *      type="string"
 *  ),
 *     @SWG\Property(
 *      property="company",
 *      type="string"
 *  ),
 *     @SWG\Property(
 *      property="date_birth",
 *      type="string"
 *  ),
 *     @SWG\Property(
 *      property="photo",
 *      type="string"
 *  )
 * )
 */
class Notes extends Model
{
    use HasFactory;

    protected $guarded = [];
}

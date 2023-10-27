<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\DefaultProfilePicture
 *
 * @property int $id
 * @property string $path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Database\Factories\DefaultProfilePictureFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|DefaultProfilePicture newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DefaultProfilePicture newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DefaultProfilePicture query()
 * @method static \Illuminate\Database\Eloquent\Builder|DefaultProfilePicture whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DefaultProfilePicture whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DefaultProfilePicture wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DefaultProfilePicture whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class DefaultProfilePicture extends Model
{
    use HasFactory;

    protected $fillable = [
        'path',
    ];
}

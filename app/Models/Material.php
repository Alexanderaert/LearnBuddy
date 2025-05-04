<?php
// app/Models/Material.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'mentor_id',
        'title',
        'description',
        'file_path',
        'url',
        'category',
    ];

    public function mentor()
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'material_skill');
    }
}
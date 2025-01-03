<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'icon_class',
        'parent_id',
        'order',
        'route',
    ];
    public function subMenus(){
        return $this->hasMany(Menu::class,'parent_id');
    }
    public function parent(){
        return $this->belongsTo(Menu::class,'parent_id');
    }
}

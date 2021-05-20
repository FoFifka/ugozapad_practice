<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vacancy extends Model
{
    use HasFactory;
    protected $fillable = ['vacancy_name', 'vacancy_description'];

    public function company() {
        return $this->belongsTo(Company::class);
    }

}

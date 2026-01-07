<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zeugnis extends Model
{
    use HasFactory;
    protected $fillable = [
        'identnr',
        'teilenr',
        'bestellnr',
        'filename',
        'filehash',
        'bezeichnung_id',
        'werkstoff',
        'herstellungsjahr',
        'dn1',
        'dn2',
        'werk_id',
        'druckstufe_id',
        'rohrabmessung',
        'materialnummer',
        'zaehlergroesse',
        'zaehlerart_id',
        'created_at',
        'updated_at'
    ];    

    protected $table = 'zeugnisse';

    public function getCreatedForHumansAttribute(){
        return $this->created_at->format('M, d Y');
    }
}

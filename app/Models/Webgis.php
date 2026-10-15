<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Webgis extends Model
{
    protected $fillable = ['name', 'file', 'description'];

    public function getExtensionAttribute(): string
    {
        return strtolower(pathinfo($this->file ?? '', PATHINFO_EXTENSION));
    }

    public function getTypeLabelAttribute(): string
    {
        $ext = $this->extension;
        if ($ext === 'geojson' || $ext === 'json') {
            return 'GeoJSON';
        }
        if ($ext === 'kml') {
            return 'KML';
        }
        if ($ext === 'zip' || $ext === 'shp') {
            return 'Shapefile';
        }
        return strtoupper($ext);
    }

    public function getIsRenderableAttribute(): bool
    {
        return in_array($this->extension, ['geojson', 'json', 'kml', 'zip', 'shp']);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            'Shimano', 'Daiwa', 'Abu Garcia', 'Penn', 'Megabass', 'Okuma', 'Ryobi',
            'G.Loomis', 'Pflueger', 'Quantum', 'KastKing', "Lew's", 'Mitchell', 'Tica',
            'Accurate', 'Avet', 'EverGreen', 'Jackall', 'Zebco', 'Fin-Nor', 'Van Staal',
            'Stella', 'Core', 'Tatula', 'Orvis', 'Sage', 'Hardy', 'Lamson', 'Ross',
            'Nautilus', 'Cadence', 'Fox Rage', 'Savage Gear', 'Gamakatsu', 'Tailwalk',
            'Evergreen', 'Gancraft', 'Studio Ocean Mark', 'Alphatackle', 'Smith',
            'Yamaga Blanks', 'Zenaq', 'Major Craft', 'Tenryu', 'Dobyns', 'St. Croix',
            'Fenwick', 'Ugly Stik', 'Berkley', 'Rapala', 'Seigler', 'Alutecnos',
            'Jigging Master', 'Ocea', 'Torsa', 'Maxel', 'Marine Sports', 'Banax',
            'Shakespeare', 'Bass Pro Shops', 'Cabela\'s', 'Favorite', 'Wright & McGill',
            'Sufix', 'Kistler', 'Phenix', 'Lunkerhunt', 'Piscifun', 'Sougayilang',
            'Mavllos', 'Goture', 'Daiko', 'Gomexus', 'Lurekiller', 'Salamandra',
            'อื่นๆ (Others)',
        ];

        foreach (array_unique($brands) as $name) {
            Brand::firstOrCreate(['name' => $name]);
        }
    }
}
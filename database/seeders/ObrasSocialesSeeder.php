<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ObraSocial;

class ObrasSocialesSeeder extends Seeder
{
    public function run()
    {
        $obras = [
            ['nombre' => 'ACA SALUD/AVALIAN'],
            ['nombre' => 'ACLISA/OSPAT'],
            ['nombre' => 'AMFFA SALUD'],
            ['nombre' => 'ASO MUTUAL MOTOCICLISTA'],
            ['nombre' => 'BOREAL'],
            ['nombre' => 'BRAMED'],
            ['nombre' => 'CONSOLIDAR'],
            ['nombre' => 'DOSEP'],
            ['nombre' => 'DOSPU'],
            ['nombre' => 'FARMACIA'],
            ['nombre' => 'FEDERADA SALUD'],
            ['nombre' => 'FEMESA SALUD'],
            ['nombre' => 'GALENO'],
            ['nombre' => 'INTEGRAL SALUD'],
            ['nombre' => 'JERARQUICOS SALUD'],
            ['nombre' => 'LUZ Y FUERZA'],
            ['nombre' => 'MEDIFE'],
            ['nombre' => 'MEDISALUD'],
            ['nombre' => 'MEDYCIN'],
            ['nombre' => 'MINEROS-ITER MEDICINA'],
            ['nombre' => 'MUTUAL MED. RIO CUARTO'],
            ['nombre' => 'OMINT'],
            ['nombre' => 'OSETYA'],
            ['nombre' => 'OSPATCA'],
            ['nombre' => 'OSSEG'],
            ['nombre' => 'OSFATUN'],
            ['nombre' => 'OSMATA'],
            ['nombre' => 'OSDE'],
            ['nombre' => 'O.SOCIAL DEL PERS DEL PAPEL/IMESA'],
            ['nombre' => 'PAMI'],
            ['nombre' => 'PERFUMISTAS'],
            ['nombre' => 'PREVENCION SALUD'],
            ['nombre' => 'RED SEGUROS MEDICO'],
            ['nombre' => 'ROISA-DOCTORED'],
            ['nombre' => 'SANCOR SALUD'],
            ['nombre' => 'SCIS'],
            ['nombre' => 'SWISS MEDICAL'],
            ['nombre' => 'TV SALUD'],
            ['nombre' => 'UNIMED'],
            ['nombre' => 'UTA-OSCTCP'],
            ['nombre' => 'SIN OBRA SOCIAL']
        ];

        foreach ($obras as $obra) {
            ObraSocial::create($obra);
        }
    }
}

<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

use App\Entity\Location;
use App\Entity\Forecast;
use App\Repository\ForecastRepository;
use App\Repository\LocationRepository;

class WeatherController extends AbstractController
{
    public function cityAction(Location $city, ForecastRepository $measurementRepository): Response
    {
        $measurements = $measurementRepository->findByLocation($city);
        return $this->render('weather/city.html.twig', [
            'location' => $city,
            'measurements' => $measurements,
         ]);
        }
    public function cityActionByName($name, ForecastRepository $measurementRepository, LocationRepository $locationRepository): Response
    {
        #$location = $locationRepository -> findOneByName(['Name' => $Name]);
        $location = $locationRepository -> findOneByName($name); #zmień nazwy właściwośi na małe litery
        $measurements = $measurementRepository->findByLocationByName($name);
        return $this->render('weather/city.html.twig', [
            'location' => $location,
        'measurements' => $measurements,
     ]);
    }
}

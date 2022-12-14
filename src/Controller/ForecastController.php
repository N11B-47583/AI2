<?php

namespace App\Controller;

use App\Entity\Forecast;
use App\Form\ForecastType;
use App\Repository\ForecastRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

#[Route('/forecast')]
class ForecastController extends AbstractController
{
    #[Route('/', name: 'app_forecast_index', methods: ['GET'])]
    public function index(ForecastRepository $forecastRepository): Response
    {
        return $this->render('forecast/index.html.twig', [
            'forecasts' => $forecastRepository->findAll(),
        ]);
    }
    ##[IsGranted('ROLE_MEASUREMENT_CREATE')]
    #[Route('/new', name: 'app_forecast_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ForecastRepository $forecastRepository): Response
    {
        $forecast = new Forecast();
        #$form = $this->createForm(ForecastType::class, $forecast);
        $form = $this->createForm(ForecastType::class, $forecast, [
            'validation_groups' => ['new', 'edit']
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $forecastRepository->save($forecast, true);

            return $this->redirectToRoute('app_forecast_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('forecast/new.html.twig', [
            'forecast' => $forecast,
            'form' => $form,
        ]);
    }
    ##[IsGranted('ROLE_MEASUREMENT_SHOW')]
    #[Route('/{id}', name: 'app_forecast_show', methods: ['GET'])]
    public function show(Forecast $forecast): Response
    {
        return $this->render('forecast/show.html.twig', [
            'forecast' => $forecast,
        ]);
    }
    ##[IsGranted('ROLE_MEASUREMENT_UPDATE')]
    #[Route('/{id}/edit', name: 'app_forecast_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Forecast $forecast, ForecastRepository $forecastRepository): Response
    {
        #$form = $this->createForm(ForecastType::class, $forecast);
        $form = $this->createForm(ForecastType::class, $forecast, [
            'validation_groups' => ['new', 'edit']
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $forecastRepository->save($forecast, true);

            return $this->redirectToRoute('app_forecast_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('forecast/edit.html.twig', [
            'forecast' => $forecast,
            'form' => $form,
        ]);
    }
    ##[IsGranted('ROLE_MEASUREMENT_DELETE')]
    #[Route('/{id}', name: 'app_forecast_delete', methods: ['POST'])]
    public function delete(Request $request, Forecast $forecast, ForecastRepository $forecastRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$forecast->getId(), $request->request->get('_token'))) {
            $forecastRepository->remove($forecast, true);
        }

        return $this->redirectToRoute('app_forecast_index', [], Response::HTTP_SEE_OTHER);
    }
}

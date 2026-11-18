<?php

use App\Http\Controllers\Api\AlimentController;
use App\Http\Controllers\Api\BassinController;
use App\Http\Controllers\Api\CycleElevageController;
use App\Http\Controllers\Api\DistributionAlimentController;
use App\Http\Controllers\Api\EspeceController;
use App\Http\Controllers\Api\ExploitationController;
use App\Http\Controllers\Api\MortaliteController;
use App\Http\Controllers\Api\PrelevementEauController;
use App\Http\Controllers\Api\RecolteController;
use App\Http\Controllers\Api\TraitementController;
use App\Http\Controllers\Api\VenteController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function (): void {
    Route::apiResources([
        'exploitations' => ExploitationController::class,
        'bassins' => BassinController::class,
        'especes' => EspeceController::class,
        'cycles-elevage' => CycleElevageController::class,
        'aliments' => AlimentController::class,
        'distributions-aliment' => DistributionAlimentController::class,
        'mortalites' => MortaliteController::class,
        'prelevements-eau' => PrelevementEauController::class,
        'traitements' => TraitementController::class,
        'recoltes' => RecolteController::class,
        'ventes' => VenteController::class,
    ]);
});

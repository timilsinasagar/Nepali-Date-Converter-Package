<?php
// src/Http/Controllers/NepaliDateDemoController.php

namespace Sagartimilsina\NepaliDate\Http\Controllers;

use Illuminate\Routing\Controller;
use Sagartimilsina\NepaliDate\Http\Requests\ConvertDateRequest;
use Sagartimilsina\NepaliDate\Services\NepaliDateService;
use Throwable;

/**
 * Bundled demo controller. Deliberately thin -- validation lives in
 * ConvertDateRequest, conversion logic lives in NepaliDateService.
 * This class only wires the two together and shapes the HTTP response.
 */
class NepaliDateDemoController extends Controller
{
    public function __construct(private NepaliDateService $service)
    {
    }

    public function index()
    {
        return view('nepali-date::demo', [
            'today' => $this->service->today(),
        ]);
    }

    public function convert(ConvertDateRequest $request)
    {
        $result = null;
        $error = null;

        try {
            $result = $this->service->convert($request->direction(), $request->dateValue());
        } catch (Throwable $e) {
            $error = $e->getMessage();
        }

        return back()->with([
            'result' => $result,
            'error' => $error,
            'input_direction' => $request->direction(),
            'input_value' => $request->dateValue(),
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\HashService;

class HashController extends Controller
{
    public function __construct(
        private HashService $hashService
    ) {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $attemptsFilter = (int) $request->input('attempts');

        return response()->json([
            'data' => $this->hashService->findAll($attemptsFilter),
            'status' => Response::HTTP_OK
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $response['data'] = [];
        $response['status'] = Response::HTTP_CREATED;

        $this->validate($request, ['text' => 'required']);

        try {
            $response['data'] = $this->hashService
                ->setText($request->input('text'))
                ->setAttempts(10000000)
                ->getHashedString();
        } catch (\Exception $exception) {
            $response['data'] = $exception->getMessage();
            $response['status'] = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return response()->json([
            'data' => $response['data'],
            'status' => $response['status']
        ], $response['status']);
    }
}

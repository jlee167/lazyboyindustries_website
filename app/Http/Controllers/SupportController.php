<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\SupportRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Redirector;
use APP\Models\User;
use App\Repositories\Support\SupportRepository;

class SupportController extends BaseController
{

    protected $repository;

    public function __construct(SupportRepository $repository)
    {
        $this->repository = $repository;
    }


    /**
     * Register a support request into the main database.
     *
     * @param  Request $request
     * @return Response
     */
    public function requestSupport(Request $request)
    {
        try {
            $this->repository->create([
                'uid'       => Auth::id(),
                'type'      => $request->type,
                'status'    => 'PENDING',
                'contact'   => $request->contact,
                'contents'  => $request->contents
            ]);
            return response([], 200);
        } catch (Exception $e) {
            return response([], 500);
        }
    }
}

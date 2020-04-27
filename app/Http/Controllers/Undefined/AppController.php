<?php

namespace App\Http\Controllers\Undefined;

use Illuminate\Http\Request;
use Flashy;
use App\Http\Traits\AppTreatment;

class AppController extends Controller
{
    use AppTreatment;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $params = [
            'generic' => (object) array(
                'idView' => $this->retrieveIdView($request),
                'middle_view' => $this->retrieveMiddleView($request),
                'pseudo' => $this->getPseudo($request),
                'countNotif' => $this->getCountNotif($request),
                'ids' => (object) array_except($this->specialIds, array('error')),
                'menu' => $this->retrieveMenu($request),
            ),
            'datas' => (object) $this->getProfile($request),
        ];
        if ($params['generic']->idView == $this->specialIds['error']) {
            Flashy::error("Erreur! Impossible d'accéder à la page demandée.");
        }
        return view('app', $params);
    }

    /**
     * Search f.
     * This version calculates and orders by relevance score.
     *
     * @param \Illuminate\Http\Request $request
     * @param Integer $idView
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show(Request $request, $idView) {
        $request->session()->put('idView', $idView);
        return $this->index($request);
    }

}
